<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;

class CleanupDeliveryConfirmations extends BaseCommand
{
    protected $group       = 'Database';
    protected $name        = 'cleanup:deliveries';
    protected $description = 'Remove duplicate delivery confirmations and clean up NULL values';

    public function run(array $params)
    {
        $db = \Config\Database::connect();
        
        CLI::write('Starting cleanup of delivery_confirmations table...', 'yellow');
        
        // Step 1: Remove duplicate delivery confirmations (keep most recent per order_id)
        CLI::write('Step 1: Removing duplicate delivery confirmations...', 'cyan');
        
        // Find duplicates grouped by order_id, staff_id, customer_id, animal_id
        $duplicates = $db->query("
            SELECT order_id, staff_id, customer_id, animal_id, COUNT(*) as count
            FROM delivery_confirmations
            WHERE order_id IS NOT NULL
            GROUP BY order_id, staff_id, customer_id, animal_id
            HAVING COUNT(*) > 1
        ")->getResultArray();
        
        $totalRemoved = 0;
        
        foreach ($duplicates as $duplicate) {
            // Get all records for this combination, ordered by created_at DESC (most recent first)
            $records = $db->table('delivery_confirmations')
                ->where('order_id', $duplicate['order_id'])
                ->where('staff_id', $duplicate['staff_id'])
                ->where('customer_id', $duplicate['customer_id'])
                ->where('animal_id', $duplicate['animal_id'])
                ->orderBy('created_at', 'DESC')
                ->get()
                ->getResultArray();
            
            if (count($records) > 1) {
                // Keep the first one (most recent), delete the rest
                $keepId = $records[0]['id'];
                $toDelete = array_slice($records, 1);
                
                foreach ($toDelete as $record) {
                    $db->table('delivery_confirmations')->delete(['id' => $record['id']]);
                    $totalRemoved++;
                    CLI::write("  Deleted duplicate record ID: {$record['id']} (keeping ID: {$keepId})", 'green');
                }
            }
        }
        
        CLI::write("Removed {$totalRemoved} duplicate records.", 'green');
        
        // Step 2: Clean up NULL values
        CLI::write('Step 2: Cleaning up NULL values...', 'cyan');
        
        $nullUpdates = [
            'delivery_photo' => '',
            'payment_photo' => '',
            'delivery_notes' => '',
            'admin_notes' => '',
            'delivery_address' => '',
            'payment_method' => 'N/A'
        ];
        
        $updated = 0;
        foreach ($nullUpdates as $column => $defaultValue) {
            $result = $db->table('delivery_confirmations')
                ->where($column . ' IS NULL', null, false)
                ->update([$column => $defaultValue]);
            
            if ($result) {
                $updated += $db->affectedRows();
                CLI::write("  Updated NULL values in {$column} to '{$defaultValue}'", 'green');
            }
        }
        
        // Handle payment_amount NULL values
        $db->query("UPDATE delivery_confirmations SET payment_amount = 0 WHERE payment_amount IS NULL");
        $updated += $db->affectedRows();
        CLI::write("  Updated NULL values in payment_amount to 0", 'green');
        
        // Handle delivery_date NULL values (set to created_at if available, otherwise current date)
        $db->query("
            UPDATE delivery_confirmations 
            SET delivery_date = COALESCE(created_at, NOW()) 
            WHERE delivery_date IS NULL
        ");
        $updated += $db->affectedRows();
        CLI::write("  Updated NULL values in delivery_date", 'green');
        
        CLI::write("Updated {$updated} NULL values.", 'green');
        CLI::write('Cleanup completed successfully!', 'green');
    }
}

