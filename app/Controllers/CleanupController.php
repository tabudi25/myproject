<?php

namespace App\Controllers;

use CodeIgniter\Controller;

class CleanupController extends BaseController
{
    public function cleanupDeliveries()
    {
        // Only allow admin access
        if (!session()->get('isLoggedIn') || session()->get('role') !== 'admin') {
            return $this->response->setJSON(['success' => false, 'message' => 'Admin access required']);
        }

        $db = \Config\Database::connect();
        $results = [
            'duplicates_removed' => 0,
            'nulls_updated' => 0,
            'messages' => []
        ];

        try {
            // Step 1: Remove duplicate delivery confirmations (keep most recent per order_id)
            $results['messages'][] = 'Step 1: Removing duplicate delivery confirmations...';

            // Find duplicates grouped by order_id (most common case)
            // Also check for duplicates by order_id, staff_id, customer_id, animal_id combination
            $duplicates = $db->query("
                SELECT order_id, COUNT(*) as count
                FROM delivery_confirmations
                WHERE order_id IS NOT NULL
                GROUP BY order_id
                HAVING COUNT(*) > 1
            ")->getResultArray();

            foreach ($duplicates as $duplicate) {
                // Get all records for this order_id (regardless of other fields)
                $records = $db->table('delivery_confirmations')
                    ->where('order_id', $duplicate['order_id'])
                    ->orderBy('created_at', 'DESC')
                    ->get()
                    ->getResultArray();

                if (count($records) > 1) {
                    // Find the best record to keep (one with photos or most complete data)
                    $bestRecord = null;
                    $bestScore = -1;
                    
                    foreach ($records as $record) {
                        $score = 0;
                        // Prioritize records with photos
                        if (!empty($record['delivery_photo'])) $score += 10;
                        if (!empty($record['payment_photo'])) $score += 10;
                        // Prioritize records with notes
                        if (!empty($record['delivery_notes'])) $score += 5;
                        if (!empty($record['admin_notes'])) $score += 5;
                        // Prioritize records with delivery address
                        if (!empty($record['delivery_address'])) $score += 3;
                        // Prioritize more recent records
                        if (!empty($record['created_at'])) {
                            $score += 1;
                        }
                        
                        if ($score > $bestScore) {
                            $bestScore = $score;
                            $bestRecord = $record;
                        }
                    }
                    
                    // If no best record found, use the most recent
                    if (!$bestRecord) {
                        $bestRecord = $records[0];
                    }
                    
                    $keepId = $bestRecord['id'];
                    $toDelete = array_filter($records, function($r) use ($keepId) {
                        return $r['id'] != $keepId;
                    });

                    foreach ($toDelete as $record) {
                        $db->table('delivery_confirmations')->delete(['id' => $record['id']]);
                        $results['duplicates_removed']++;
                        $results['messages'][] = "Deleted duplicate record ID: {$record['id']} (keeping ID: {$keepId})";
                    }
                }
            }

            // Step 2: Clean up NULL values
            $results['messages'][] = 'Step 2: Cleaning up NULL values...';

            $nullUpdates = [
                'delivery_photo' => '',
                'payment_photo' => '',
                'delivery_notes' => '',
                'admin_notes' => '',
                'delivery_address' => '',
                'payment_method' => 'N/A'
            ];

            foreach ($nullUpdates as $column => $defaultValue) {
                $db->table('delivery_confirmations')
                    ->where($column . ' IS NULL', null, false)
                    ->update([$column => $defaultValue]);

                $affected = $db->affectedRows();
                if ($affected > 0) {
                    $results['nulls_updated'] += $affected;
                    $results['messages'][] = "Updated {$affected} NULL values in {$column} to '{$defaultValue}'";
                }
            }

            // Handle payment_amount NULL values
            $db->query("UPDATE delivery_confirmations SET payment_amount = 0 WHERE payment_amount IS NULL");
            $affected = $db->affectedRows();
            if ($affected > 0) {
                $results['nulls_updated'] += $affected;
                $results['messages'][] = "Updated {$affected} NULL values in payment_amount to 0";
            }

            // Handle delivery_date NULL values
            $db->query("
                UPDATE delivery_confirmations 
                SET delivery_date = COALESCE(created_at, NOW()) 
                WHERE delivery_date IS NULL
            ");
            $affected = $db->affectedRows();
            if ($affected > 0) {
                $results['nulls_updated'] += $affected;
                $results['messages'][] = "Updated {$affected} NULL values in delivery_date";
            }

            $results['messages'][] = 'Cleanup completed successfully!';
            $results['success'] = true;

            return $this->response->setJSON($results);
        } catch (\Exception $e) {
            log_message('error', 'Cleanup error: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error during cleanup: ' . $e->getMessage()
            ]);
        }
    }
}

