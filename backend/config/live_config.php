<?php
// Live Production Configuration for Salem Dominion Ministries
return [
    'app' => [
        'name' => 'Salem Dominion Ministries',
        'url' => 'http://192.168.1.10/salem-dominion-ministries',
        'env' => 'production',
        'debug' => false,
        'timezone' => 'Africa/Kampala'
    ],
    'database' => [
        'host' => 'localhost',
        'name' => 'salem_dominion_ministries',
        'user' => 'root',
        'password' => 'ReagaN23#',
        'charset' => 'utf8mb4',
        'collation' => 'utf8mb4_unicode_ci'
    ],
    'jwt' => [
        'secret' => 'salem_dominion_ministries_jwt_secret_key_2026_production_secure',
        'expire' => '7d',
        'algorithm' => 'HS256'
    ],
    'upload' => [
        'max_size' => 5242880, // 5MB
        'path' => 'uploads/',
        'allowed_types' => ['jpg', 'jpeg', 'png', 'gif', 'mp3', 'mp4', 'wav', 'pdf'],
        'allowed_mimes' => [
            'image/jpeg', 'image/png', 'image/gif',
            'audio/mpeg', 'audio/wav', 'video/mp4',
            'application/pdf'
        ]
    ],
    'email' => [
        'from_email' => 'info@salemdominionministries.org',
        'from_name' => 'Salem Dominion Ministries',
        'smtp_host' => 'localhost',
        'smtp_port' => 587,
        'smtp_username' => '',
        'smtp_password' => ''
    ],
    'cors' => [
        'allowed_origins' => [
            'http://192.168.1.10',
            'http://192.168.1.10/salem-dominion-ministries',
            'http://localhost',
            'http://localhost/salem-dominion-ministries'
        ],
        'allowed_methods' => ['GET', 'POST', 'PUT', 'DELETE', 'OPTIONS'],
        'allowed_headers' => ['Content-Type', 'Authorization', 'X-Requested-With']
    ],
    'security' => [
        'password_min_length' => 6,
        'session_timeout' => 3600, // 1 hour
        'max_login_attempts' => 5,
        'lockout_duration' => 900 // 15 minutes
    ]
];
?>
