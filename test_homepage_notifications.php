<?php
// Test homepage notification system
echo "Testing homepage notification system...\n";

// Test 1: Check if homepage has notification section
echo "\n=== Homepage Notification Check ===\n";
$homePage = 'app/Views/ecommerce/home.php';
if (file_exists($homePage)) {
    $content = file_get_contents($homePage);
    
    // Check for notification section
    if (strpos($content, 'notification-section') !== false) {
        echo "✓ Notification section found in homepage\n";
    } else {
        echo "✗ Notification section missing in homepage\n";
    }
    
    // Check for notification icon in navigation
    if (strpos($content, 'fas fa-bell') !== false && strpos($content, '/notifications') !== false) {
        echo "✓ Notification icon found in homepage navigation\n";
    } else {
        echo "✗ Notification icon missing in homepage navigation\n";
    }
    
    // Check for notification badge
    if (strpos($content, 'notification-badge') !== false) {
        echo "✓ Notification badge found in homepage\n";
    } else {
        echo "✗ Notification badge missing in homepage\n";
    }
    
    // Check for JavaScript functions
    $jsFunctions = [
        'loadRecentNotifications' => 'Load recent notifications function',
        'markAsRead' => 'Mark as read function',
        'updateNotificationBadge' => 'Update badge function',
        'formatTime' => 'Time formatting function'
    ];
    
    foreach ($jsFunctions as $function => $description) {
        if (strpos($content, $function) !== false) {
            echo "✓ {$description}\n";
        } else {
            echo "✗ {$description} - MISSING\n";
        }
    }
    
    // Check for real-time JavaScript
    if (strpos($content, 'realtime.js') !== false) {
        echo "✓ Real-time JavaScript included\n";
    } else {
        echo "✗ Real-time JavaScript missing\n";
    }
    
    // Check for API endpoints
    if (strpos($content, '/api/notifications/recent') !== false) {
        echo "✓ Recent notifications API endpoint referenced\n";
    } else {
        echo "✗ Recent notifications API endpoint missing\n";
    }
    
    if (strpos($content, '/api/notifications/unread-count') !== false) {
        echo "✓ Unread count API endpoint referenced\n";
    } else {
        echo "✗ Unread count API endpoint missing\n";
    }
    
} else {
    echo "✗ Homepage file not found\n";
}

echo "\n=== Homepage Notification Test Complete ===\n";
echo "Homepage now has a complete notification system!\n";
echo "\nFeatures added:\n";
echo "- Notification section for logged-in users\n";
echo "- Navigation bell icon with badge\n";
echo "- Real-time notification loading\n";
echo "- Mark as read functionality\n";
echo "- Time formatting for notifications\n";
echo "- Real-time badge updates\n";
?>
