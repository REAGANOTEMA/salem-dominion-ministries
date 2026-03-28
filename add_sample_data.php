<?php
require_once 'backend/config/database.php';
$db = new Database();

echo "Adding sample data...\n";

// Add sample gallery data
$gallery_items = [
    [
        'title' => 'Sunday Worship Service',
        'description' => 'Powerful worship session with the congregation',
        'image_url' => '/salem-dominion-ministries/backend/uploads/gallery/worship1.jpg',
        'category' => 'worship'
    ],
    [
        'title' => 'Community Outreach',
        'description' => 'Reaching out to the community with love and support',
        'image_url' => '/salem-dominion-ministries/backend/uploads/gallery/outreach1.jpg',
        'category' => 'outreach'
    ],
    [
        'title' => 'Youth Conference',
        'description' => 'Annual youth gathering for spiritual growth',
        'image_url' => '/salem-dominion-ministries/backend/uploads/gallery/youth1.jpg',
        'category' => 'youth'
    ],
    [
        'title' => 'Prayer Meeting',
        'description' => 'Mid-week prayer and fellowship',
        'image_url' => '/salem-dominion-ministries/backend/uploads/gallery/prayer1.jpg',
        'category' => 'prayer'
    ]
];

foreach ($gallery_items as $item) {
    $result = $db->insert(
        "INSERT INTO gallery (title, description, image_url, category, status, created_at) VALUES (?, ?, ?, ?, 'published', NOW())",
        [$item['title'], $item['description'], $item['image_url'], $item['category']]
    );
    if ($result['success']) {
        echo "✅ Added gallery item: {$item['title']}\n";
    } else {
        echo "❌ Failed to add gallery item: {$item['title']}\n";
    }
}

// Add sample news data
$news_items = [
    [
        'title' => 'Annual Prayer & Fasting Week',
        'content' => 'Join us for a week of dedicated prayer and fasting as we seek God\'s guidance for the new year.',
        'category' => 'events',
        'status' => 'published'
    ],
    [
        'title' => 'New Youth Ministry Program',
        'content' => 'We are excited to launch our new youth ministry program focused on spiritual growth and leadership development.',
        'category' => 'ministry',
        'status' => 'published'
    ],
    [
        'title' => 'Community Service Day',
        'content' => 'Our church family will be serving the local community through various outreach activities this weekend.',
        'category' => 'outreach',
        'status' => 'published'
    ]
];

foreach ($news_items as $item) {
    $result = $db->insert(
        "INSERT INTO news (title, content, category, status, created_at) VALUES (?, ?, ?, ?, NOW())",
        [$item['title'], $item['content'], $item['category'], $item['status']]
    );
    if ($result['success']) {
        echo "✅ Added news item: {$item['title']}\n";
    } else {
        echo "❌ Failed to add news item: {$item['title']}\n";
    }
}

echo "Sample data added successfully!\n";
?>
