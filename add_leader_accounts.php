<?php
require_once 'backend/config/database.php';
$db = new Database();

echo "Creating accounts for all church leaders...\n";

// Leader accounts with their roles and permissions
$leaders = [
    [
        'first_name' => 'Nabulya',
        'last_name' => 'Joyce',
        'email' => 'joyce.nabulya@salemdominionministries.org',
        'phone' => '+256772514889',
        'role' => 'admin', // Treasurer needs admin access
        'password' => 'Treasurer123'
    ],
    [
        'first_name' => 'Irene',
        'last_name' => 'Mirembe',
        'email' => 'irene.mirembe@salemdominionministries.org',
        'phone' => '+256772514889',
        'role' => 'admin', // Administrator needs admin access
        'password' => 'Admin123'
    ],
    [
        'first_name' => 'Kisakye',
        'last_name' => 'Halima',
        'email' => 'halima.kisakye@salemdominionministries.org',
        'phone' => '+256772514889',
        'role' => 'pastor', // Mission Director needs pastor access
        'password' => 'Mission123'
    ],
    [
        'first_name' => 'Damali',
        'last_name' => 'Namwima',
        'email' => 'damali.namwima@salemdominionministries.org',
        'phone' => '+256772514889',
        'role' => 'pastor', // Altars Director needs pastor access
        'password' => 'Altars123'
    ],
    [
        'first_name' => 'Jotham',
        'last_name' => 'Bright Mulinde',
        'email' => 'jotham.mulinde@salemdominionministries.org',
        'phone' => '+256772514889',
        'role' => 'pastor', // Church Elder needs pastor access
        'password' => 'Elder123'
    ],
    [
        'first_name' => 'Jonathan',
        'last_name' => 'Ngobi',
        'email' => 'jonathan.ngobi@salemdominionministries.org',
        'phone' => '+256772514889',
        'role' => 'pastor', // Branch Pastor needs pastor access
        'password' => 'Branch123'
    ],
    [
        'first_name' => 'Miriam',
        'last_name' => 'Gerald',
        'email' => 'miriam.gerald@salemdominionministries.org',
        'phone' => '+256772514889',
        'role' => 'pastor', // Senior Pastor needs pastor access
        'password' => 'Senior123'
    ]
];

foreach ($leaders as $leader) {
    // Check if user already exists
    $existing = $db->query("SELECT id FROM users WHERE email = ?", [$leader['email']]);
    
    if ($existing['success'] && count($existing['data']) > 0) {
        echo "⚠️  User {$leader['email']} already exists - skipping\n";
        continue;
    }
    
    // Hash password
    $hashedPassword = password_hash($leader['password'], PASSWORD_DEFAULT);
    
    // Insert user
    $result = $db->insert(
        "INSERT INTO users (first_name, last_name, email, phone, password_hash, role, is_active, email_verified, created_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW())",
        [$leader['first_name'], $leader['last_name'], $leader['email'], $leader['phone'], $hashedPassword, $leader['role'], true, true]
    );
    
    if ($result['success']) {
        echo "✅ Created account for {$leader['first_name']} {$leader['last_name']} ({$leader['role']})\n";
        echo "   Email: {$leader['email']}\n";
        echo "   Password: {$leader['password']}\n";
        echo "   Role: {$leader['role']}\n\n";
    } else {
        echo "❌ Failed to create account for {$leader['first_name']} {$leader['last_name']}\n";
    }
}

echo "\n🎉 Leader accounts creation completed!\n";
echo "\n📋 Login Credentials Summary:\n";
echo "==============================\n";
foreach ($leaders as $leader) {
    echo "{$leader['email']} | {$leader['password']} | {$leader['role']}\n";
}
echo "==============================\n";
echo "\n💡 All leaders can now:\n";
echo "- Login to admin panel\n";
echo "- Manage their respective ministries\n";
echo "- Create and edit content\n";
echo "- View reports and analytics\n";
echo "- Access all church management features\n";
?>
