<?php
require_once 'backend/config/database.php';
$db = new Database();

echo "Adding sample events data...\n";

// Add sample events data
$events = [
    [
        'title' => 'Annual Prayer & Fasting Week',
        'description' => 'Join us for a week of dedicated prayer and fasting as we seek God\'s guidance for the new year.',
        'event_date' => '2026-03-10 18:00:00',
        'end_date' => '2026-03-15 20:00:00',
        'location' => 'Main Sanctuary',
        'event_type' => 'service',
        'max_attendees' => 200,
        'featured_image_url' => '/salem-dominion-ministries/backend/uploads/events/prayer.jpg',
        'status' => 'upcoming'
    ],
    [
        'title' => 'Youth Conference',
        'description' => 'Annual youth gathering for spiritual growth, fellowship, and fun activities.',
        'event_date' => '2026-04-12 09:00:00',
        'end_date' => '2026-04-13 17:00:00',
        'location' => 'Church Hall',
        'event_type' => 'conference',
        'max_attendees' => 100,
        'featured_image_url' => '/salem-dominion-ministries/backend/uploads/events/youth.jpg',
        'status' => 'upcoming'
    ],
    [
        'title' => 'Women\'s Fellowship Breakfast',
        'description' => 'Monthly gathering for women to fellowship, share testimonies, and enjoy breakfast together.',
        'event_date' => '2026-04-20 08:00:00',
        'end_date' => '2026-04-20 10:00:00',
        'location' => 'Fellowship Hall',
        'event_type' => 'fellowship',
        'max_attendees' => 50,
        'featured_image_url' => '/salem-dominion-ministries/backend/uploads/events/women.jpg',
        'status' => 'upcoming'
    ],
    [
        'title' => 'Community Outreach Day',
        'description' => 'Serving our local community through various outreach activities and showing God\'s love.',
        'event_date' => '2026-05-03 10:00:00',
        'end_date' => '2026-05-03 16:00:00',
        'location' => 'Various Locations',
        'event_type' => 'outreach',
        'max_attendees' => 150,
        'featured_image_url' => '/salem-dominion-ministries/backend/uploads/events/outreach.jpg',
        'status' => 'upcoming'
    ]
];

foreach ($events as $event) {
    $result = $db->insert(
        "INSERT INTO events (title, description, event_date, end_date, location, event_type, max_attendees, featured_image_url, status, created_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())",
        [$event['title'], $event['description'], $event['event_date'], $event['end_date'], $event['location'], $event['event_type'], $event['max_attendees'], $event['featured_image_url'], $event['status']]
    );
    if ($result['success']) {
        echo "✅ Added event: {$event['title']}\n";
    } else {
        echo "❌ Failed to add event: {$event['title']}\n";
    }
}

echo "Sample events data added successfully!\n";
?>
