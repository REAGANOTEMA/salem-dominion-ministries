<?php
// Production configuration
return [
    'database' => [
        'host' => 'localhost',
        'name' => 'salem-dominion-ministries',
        'user' => 'root',
        'password' => 'ReagaN23#',
        'charset' => 'utf8mb4'
    ],
    'app' => [
        'url' => 'http://localhost/salem-dominion-ministries',
        'env' => 'production',
        'debug' => false
    ],
    'jwt' => [
        'secret' => 'your_super_secret_jwt_key_here_change_in_production',
        'expire' => '7d'
    ],
    'upload' => [
        'max_size' => 5242880, // 5MB
        'path' => 'uploads/',
        'allowed_types' => ['jpg', 'jpeg', 'png', 'gif', 'mp3', 'mp4', 'wav']
    ]
];
?>
