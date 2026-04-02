<?php
// Personalized Welcome System
require_once 'config.php';
require_once 'db.php';

session_start();

// Get user information if logged in
$user_name = '';
$user_full_name = '';
if (isset($_SESSION['user_id'])) {
    try {
        $user = $db->selectOne("SELECT first_name, last_name, username FROM users WHERE id = ?", [$_SESSION['user_id']]);
        if ($user) {
            $user_full_name = trim($user['first_name'] . ' ' . $user['last_name']);
            $user_name = $user['first_name'] ?: $user['username'];
        }
    } catch (Exception $e) {
        // Handle error silently
    }
}

// Welcome messages for different times of day
function getWelcomeMessage($name = '') {
    $hour = date('H');
    $greeting = '';
    
    if ($hour < 12) {
        $greeting = 'Good morning';
    } elseif ($hour < 17) {
        $greeting = 'Good afternoon';
    } else {
        $greeting = 'Good evening';
    }
    
    $messages = [
        "$greeting" . ($name ? ", $name" : "") . "! Welcome to Salem Dominion Ministries.",
        "$greeting" . ($name ? ", $name" : "") . "! We're blessed to have you here.",
        "$greeting" . ($name ? ", $name" : "") . "! God bless your visit to our platform.",
        "$greeting" . ($name ? ", $name" : "") . "! Welcome to your spiritual home online.",
        "$greeting" . ($name ? ", dear $name" : "") . "! Welcome to our church family."
    ];
    
    return $messages[array_rand($messages)];
}

// Bible verses for descriptions
$bible_verses = [
    ["For I know the plans I have for you, declares the Lord, plans to prosper you and not to harm you, plans to give you hope and a future." => "Jeremiah 29:11"],
    ["Trust in the Lord with all your heart and lean not on your own understanding; in all your ways submit to him, and he will make your paths straight." => "Proverbs 3:5-6"],
    ["Be strong and courageous. Do not be afraid; do not be discouraged, for the Lord your God will be with you wherever you go." => "Joshua 1:9"],
    ["The Lord is my shepherd; I shall not want. He makes me lie down in green pastures. He leads me beside still waters." => "Psalm 23:1-2"],
    ["I can do all this through him who gives me strength." => "Philippians 4:13"],
    ["For God so loved the world that he gave his one and only Son, that whoever believes in him shall not perish but have eternal life." => "John 3:16"],
    ["Be still, and know that I am God." => "Psalm 46:10"],
    ["The Lord your God is with you, the Mighty Warrior who saves." => "Zephaniah 3:17"],
    ["But those who hope in the Lord will renew their strength. They will soar on wings like eagles; they will run and not grow weary, they will walk and not be faint." => "Isaiah 40:31"],
    ["And we know that in all things God works for the good of those who love him, who have been called according to his purpose." => "Romans 8:28"]
];

// Random bible verse
$random_verse = $bible_verses[array_rand($bible_verses)];
$verse_text = array_keys($random_verse)[0];
$verse_reference = array_values($random_verse)[0];

echo "<!DOCTYPE html>
<html>
<head>
    <title>Welcome System - Salem Dominion Ministries</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 0; padding: 20px; background: linear-gradient(135deg, #f0fdf4 0%, #e0f2fe 100%); }
        .container { max-width: 800px; margin: 0 auto; background: white; padding: 40px; border-radius: 15px; box-shadow: 0 10px 30px rgba(0,0,0,0.1); }
        h1 { color: #16a34a; text-align: center; margin-bottom: 30px; }
        .welcome-section { background: linear-gradient(135deg, #0ea5e9 0%, #0284c7 100%); color: white; padding: 30px; border-radius: 10px; margin: 20px 0; text-align: center; }
        .verse-section { background: #f8fafc; padding: 25px; border-radius: 10px; border-left: 5px solid #16a34a; margin: 20px 0; }
        .btn { background: #0ea5e9; color: white; padding: 12px 25px; text-decoration: none; border-radius: 25px; display: inline-block; margin: 10px 5px; font-weight: 600; }
        .code-block { background: #1e293b; color: #e2e8f0; padding: 20px; border-radius: 8px; font-family: 'Courier New', monospace; margin: 15px 0; overflow-x: auto; }
    </style>
</head>
<body>
    <div class='container'>
        <h1>🎉 Personalized Welcome System</h1>
        
        <div class='welcome-section'>
            <h2>🎵 Current Welcome Message:</h2>
            <p style='font-size: 1.2rem; font-style: italic;'>\"" . getWelcomeMessage($user_name) . "\"</p>
            <p><small>Current User: " . ($user_full_name ?: 'Guest') . "</small></p>
        </div>
        
        <div class='verse-section'>
            <h3>📖 Today's Featured Bible Verse:</h3>
            <p style='font-size: 1.1rem; font-style: italic; line-height: 1.6; color: #374151;'>\"{$verse_text}\"</p>
            <p style='text-align: right; font-weight: bold; color: #16a34a;'>- {$verse_reference}</p>
        </div>
        
        <h2>🔧 Implementation Instructions:</h2>
        
        <h3>1. Audio Welcome System</h3>
        <p>Add this JavaScript to your pages for auto-playing welcome messages:</p>
        <div class='code-block'>
// Text-to-Speech Welcome System
function playWelcomeMessage(userName, isLoggedIn) {
    // Check if we've already played welcome recently (within last 30 minutes)
    const lastWelcome = localStorage.getItem('lastWelcomeTime');
    const now = Date.now();
    
    if (lastWelcome && (now - parseInt(lastWelcome)) < 1800000) { // 30 minutes
        return; // Don't repeat too frequently
    }
    
    const welcomeCount = parseInt(localStorage.getItem('welcomeCount') || '0');
    if (welcomeCount >= 2) {
        return; // Limit to 2 welcomes per session
    }
    
    const hour = new Date().getHours();
    let greeting = '';
    
    if (hour < 12) greeting = 'Good morning';
    else if (hour < 17) greeting = 'Good afternoon';
    else greeting = 'Good evening';
    
    const messages = isLoggedIn ? [
        `${greeting}, ${userName}! Welcome to Salem Dominion Ministries.`,
        `${greeting}, ${userName}! We're blessed to have you here.`,
        `${greeting}, ${userName}! Welcome to your spiritual home online.`
    ] : [
        `${greeting}! Welcome to Salem Dominion Ministries.`,
        `${greeting}! We're blessed to have you visit our platform.`,
        `${greeting}! Welcome to our church family online.`
    ];
    
    const message = messages[Math.floor(Math.random() * messages.length)];
    
    // Use Web Speech API
    if ('speechSynthesis' in window) {
        const utterance = new SpeechSynthesisUtterance(message);
        utterance.rate = 0.9;
        utterance.pitch = 1.0;
        utterance.volume = 0.8;
        
        speechSynthesis.speak(utterance);
        
        // Update tracking
        localStorage.setItem('lastWelcomeTime', now.toString());
        localStorage.setItem('welcomeCount', (welcomeCount + 1).toString());
    }
}

// Call this when page loads
document.addEventListener('DOMContentLoaded', function() {
    // For logged-in users
    const userName = '" . json_encode($user_name) . "';
    const isLoggedIn = userName !== '';
    
    // Play welcome after 2 seconds
    setTimeout(() => {
        playWelcomeMessage(userName, isLoggedIn);
    }, 2000);
});
        </div>
        
        <h3>2. Bible Verse Integration for Gallery Upload</h3>
        <p>Update your gallery upload form to include bible verses:</p>
        <div class='code-block'>
<!-- Add to your gallery upload form -->
<div class='form-group'>
    <label for='bible_verse'>Bible Scripture (Optional)</label>
    <textarea class='form-control' id='bible_verse' name='bible_verse' rows='3' 
              placeholder='Enter a bible verse that relates to your image...'></textarea>
    <small class='form-text text-muted'>Share a scripture that inspired this image or moment</small>
</div>

<div class='form-group'>
    <label for='verse_reference'>Verse Reference</label>
    <input type='text' class='form-control' id='verse_reference' name='verse_reference' 
           placeholder='e.g., John 3:16, Psalm 23:1'>
</div>

<!-- Add suggested verses -->
<div class='suggested-verses'>
    <h6>Suggested Verses:</h6>
    <div class='verse-suggestions'>
        <button type='button' class='btn btn-sm btn-outline-primary' onclick='setVerse(\"For I know the plans I have for you\", \"Jeremiah 29:11\")'>Jeremiah 29:11</button>
        <button type='button' class='btn btn-sm btn-outline-primary' onclick='setVerse(\"Trust in the Lord with all your heart\", \"Proverbs 3:5-6\")'>Proverbs 3:5-6</button>
        <button type='button' class='btn btn-sm btn-outline-primary' onclick='setVerse(\"Be strong and courageous\", \"Joshua 1:9\")'>Joshua 1:9</button>
        <button type='button' class='btn btn-sm btn-outline-primary' onclick='setVerse(\"I can do all this through him\", \"Philippians 4:13\")'>Philippians 4:13</button>
    </div>
</div>

<script>
function setVerse(text, reference) {
    document.getElementById('bible_verse').value = text;
    document.getElementById('verse_reference').value = reference;
}
</script>
        </div>
        
        <h3>3. Database Update for Bible Verses</h3>
        <p>Add bible verse fields to your gallery table:</p>
        <div class='code-block'>
ALTER TABLE gallery ADD COLUMN bible_verse TEXT AFTER description;
ALTER TABLE gallery ADD COLUMN verse_reference VARCHAR(100) AFTER bible_verse;
        </div>
        
        <h3>4. Enhanced Gallery Display</h3>
        <p>Show bible verses in your gallery display:</p>
        <div class='code-block'>
<!-- In your gallery loop -->
<?php if (!empty($image['bible_verse'])): ?>
    <div class='bible-verse'>
        <p style='font-style: italic; color: #16a34a; margin: 10px 0;'>
            \"<?php echo htmlspecialchars($image['bible_verse']); ?>\"
            <small style='display: block; text-align: right; font-weight: bold;'>- <?php echo htmlspecialchars($image['verse_reference']); ?></small>
        </p>
    </div>
<?php endif; ?>
        </div>
        
        <div style='text-align: center; margin-top: 40px; padding: 30px; background: #dcfce7; border-radius: 10px;'>
            <h3 style='color: #16a34a;'>🎉 Welcome System Ready!</h3>
            <p>Your personalized welcome system with bible verses is ready to implement!</p>
            <a href='index.php' class='btn'>🏠 Homepage</a>
            <a href='gallery.php' class='btn'>🖼️ Gallery</a>
        </div>
    </div>
</body>
</html>";
?>
