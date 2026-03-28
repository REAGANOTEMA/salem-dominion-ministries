<?php
require_once 'backend/config/database.php';
$db = new Database();

echo "Adding sample contact messages...\n";

// Add sample contact messages
$contacts = [
    [
        'name' => 'Sarah Thompson',
        'email' => 'sarah.thompson@example.com',
        'phone' => '+256 456 789 012',
        'subject' => 'Prayer Request',
        'message' => 'I would like to request prayer for my family. We are going through a difficult time and would appreciate your prayers.',
        'message_type' => 'prayer',
        'status' => 'unread',
        'priority' => 'medium'
    ],
    [
        'name' => 'Michael Davis',
        'email' => 'michael.davis@example.com',
        'phone' => '+256 567 890 123',
        'subject' => 'Volunteer Opportunity',
        'message' => 'I would like to volunteer for the youth ministry. I have experience working with young people and would love to serve.',
        'message_type' => 'general',
        'status' => 'unread',
        'priority' => 'medium'
    ],
    [
        'name' => 'Emily Wilson',
        'email' => 'emily.wilson@example.com',
        'phone' => '+256 678 901 234',
        'subject' => 'Service Information',
        'message' => 'I am new to the area and would like to know more about your service times and how I can get involved.',
        'message_type' => 'general',
        'status' => 'responded',
        'priority' => 'low'
    ]
];

foreach ($contacts as $contact) {
    $result = $db->insert(
        "INSERT INTO contact_messages (name, email, phone, subject, message, message_type, status, priority, created_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW())",
        [$contact['name'], $contact['email'], $contact['phone'], $contact['subject'], $contact['message'], $contact['message_type'], $contact['status'], $contact['priority']]
    );
    if ($result['success']) {
        echo "✅ Added contact message: {$contact['name']} - {$contact['subject']}\n";
    } else {
        echo "❌ Failed to add contact message: {$contact['name']}\n";
    }
}

echo "Sample contact messages added successfully!\n";
?>
