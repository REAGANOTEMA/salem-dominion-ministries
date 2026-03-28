<?php
require_once 'backend/config/database.php';
$db = new Database();

echo "Adding sample donation data...\n";

// Add sample donation data
$donations = [
    [
        'donor_name' => 'John Smith',
        'donor_email' => 'john.smith@example.com',
        'donor_phone' => '+256 123 456 789',
        'amount' => 100.00,
        'donation_type' => 'tithe',
        'payment_method' => 'mobile_money',
        'status' => 'completed',
        'purpose' => 'Monthly tithe contribution',
        'notes' => 'Regular monthly tithe'
    ],
    [
        'donor_name' => 'Mary Johnson',
        'donor_email' => 'mary.johnson@example.com',
        'donor_phone' => '+256 234 567 890',
        'amount' => 50.00,
        'donation_type' => 'offering',
        'payment_method' => 'cash',
        'status' => 'completed',
        'purpose' => 'Sunday offering',
        'notes' => 'Weekly church offering'
    ],
    [
        'donor_name' => 'David Williams',
        'donor_email' => 'david.williams@example.com',
        'donor_phone' => '+256 345 678 901',
        'amount' => 200.00,
        'donation_type' => 'building_fund',
        'payment_method' => 'bank_transfer',
        'status' => 'completed',
        'purpose' => 'Building fund contribution',
        'notes' => 'Special contribution for church building'
    ]
];

foreach ($donations as $donation) {
    $result = $db->insert(
        "INSERT INTO donations (donor_name, donor_email, donor_phone, amount, donation_type, payment_method, status, purpose, notes, created_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())",
        [$donation['donor_name'], $donation['donor_email'], $donation['donor_phone'], $donation['amount'], $donation['donation_type'], $donation['payment_method'], $donation['status'], $donation['purpose'], $donation['notes']]
    );
    if ($result['success']) {
        echo "✅ Added donation: {$donation['donor_name']} - {$donation['amount']}\n";
    } else {
        echo "❌ Failed to add donation: {$donation['donor_name']}\n";
    }
}

echo "Sample donation data added successfully!\n";
?>
