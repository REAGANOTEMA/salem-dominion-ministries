<?php
/**
 * Comprehensive Image Integration System
 * Places all images from public/images into their respective pages with proper animations
 */

// Define image mappings with their exact names and positions
$imageMappings = [
    'index.php' => [
        'hero-bg.jpg' => [
            'position' => 'hero-background',
            'animation' => 'hero-pulse',
            'container_class' => 'hero-section',
            'alt_text' => 'Salem Dominion Ministries - Welcome'
        ],
        'church-building.jpg' => [
            'position' => 'about-section',
            'animation' => 'fade-in-up',
            'container_class' => 'church-image-container',
            'alt_text' => 'Salem Dominion Ministries Church Building'
        ],
        'worship-service.jpg' => [
            'position' => 'worship-section',
            'animation' => 'fade-in-left',
            'container_class' => 'worship-image-container',
            'alt_text' => 'Worship Service at Salem Dominion Ministries'
        ],
        'community-service.jpg' => [
            'position' => 'community-section',
            'animation' => 'fade-in-right',
            'container_class' => 'community-image-container',
            'alt_text' => 'Community Service Activities'
        ],
        'bible-study.jpg' => [
            'position' => 'bible-study-section',
            'animation' => 'fade-in-up',
            'container_class' => 'bible-study-image-container',
            'alt_text' => 'Bible Study Group'
        ]
    ],
    'about.php' => [
        'about-banner.jpg' => [
            'position' => 'hero-background',
            'animation' => 'hero-pulse',
            'container_class' => 'hero-section',
            'alt_text' => 'About Salem Dominion Ministries'
        ],
        'church-history.jpg' => [
            'position' => 'history-section',
            'animation' => 'fade-in-left',
            'container_class' => 'history-image-container',
            'alt_text' => 'Church History and Heritage'
        ],
        'mission-vision.jpg' => [
            'position' => 'mission-section',
            'animation' => 'fade-in-right',
            'container_class' => 'mission-image-container',
            'alt_text' => 'Our Mission and Vision'
        ],
        'beliefs.jpg' => [
            'position' => 'beliefs-section',
            'animation' => 'fade-in-up',
            'container_class' => 'beliefs-image-container',
            'alt_text' => 'Our Beliefs and Values'
        ]
    ],
    'ministries.php' => [
        'ministries-hero.jpg' => [
            'position' => 'hero-background',
            'animation' => 'hero-pulse',
            'container_class' => 'hero-section',
            'alt_text' => 'Our Ministries'
        ],
        'children-ministry.jpg' => [
            'position' => 'children-ministry-section',
            'animation' => 'fade-in-left',
            'container_class' => 'ministry-image-container',
            'alt_text' => 'Children Ministry Activities'
        ],
        'youth-ministry.jpg' => [
            'position' => 'youth-ministry-section',
            'animation' => 'fade-in-right',
            'container_class' => 'ministry-image-container',
            'alt_text' => 'Youth Ministry Programs'
        ],
        'women-ministry.jpg' => [
            'position' => 'women-ministry-section',
            'animation' => 'fade-in-up',
            'container_class' => 'ministry-image-container',
            'alt_text' => 'Women Ministry Fellowship'
        ],
        'men-ministry.jpg' => [
            'position' => 'men-ministry-section',
            'animation' => 'fade-in-left',
            'container_class' => 'ministry-image-container',
            'alt_text' => 'Men Ministry Activities'
        ]
    ],
    'events.php' => [
        'events-banner.jpg' => [
            'position' => 'hero-background',
            'animation' => 'hero-pulse',
            'container_class' => 'hero-section',
            'alt_text' => 'Church Events and Activities'
        ],
        'conference.jpg' => [
            'position' => 'conference-section',
            'animation' => 'fade-in-up',
            'container_class' => 'event-image-container',
            'alt_text' => 'Church Conference Event'
        ],
        'worship-night.jpg' => [
            'position' => 'worship-night-section',
            'animation' => 'fade-in-left',
            'container_class' => 'event-image-container',
            'alt_text' => 'Worship Night Service'
        ],
        'community-outreach.jpg' => [
            'position' => 'outreach-section',
            'animation' => 'fade-in-right',
            'container_class' => 'event-image-container',
            'alt_text' => 'Community Outreach Program'
        ]
    ],
    'leadership.php' => [
        'pastor.jpeg' => [
            'position' => 'senior-pastor',
            'animation' => 'fade-in-up',
            'container_class' => 'pastor-image-container',
            'alt_text' => 'Apostle Faty Musasizi - Senior Pastor',
            'name' => 'Apostle Faty Musasizi',
            'title' => 'Senior Pastor & Founder'
        ],
        'pastor1.jpeg' => [
            'position' => 'leadership-team',
            'animation' => 'fade-in-left',
            'container_class' => 'leader-image-container',
            'alt_text' => 'Pastor Nabulya Joyce - Church Treasurer',
            'name' => 'Pastor Nabulya Joyce',
            'title' => 'Church Treasurer'
        ],
        'pastor2.jpeg' => [
            'position' => 'leadership-team',
            'animation' => 'fade-in-right',
            'container_class' => 'leader-image-container',
            'alt_text' => 'Apostle Irene Mirembe - Church Administrator',
            'name' => 'Apostle Irene Mirembe',
            'title' => 'Church Administrator'
        ],
        'pastor3.jpeg' => [
            'position' => 'leadership-team',
            'animation' => 'fade-in-up',
            'container_class' => 'leader-image-container',
            'alt_text' => 'Evangelist Kisakye Halima - Mission Director',
            'name' => 'Evangelist Kisakye Halima',
            'title' => 'Mission Director'
        ],
        'pastor4.jpeg' => [
            'position' => 'leadership-team',
            'animation' => 'fade-in-left',
            'container_class' => 'leader-image-container',
            'alt_text' => 'Pastor Damali Namwima - Altars Director',
            'name' => 'Pastor Damali Namwima',
            'title' => 'Altars Director'
        ],
        'pastor5.jpeg' => [
            'position' => 'leadership-team',
            'animation' => 'fade-in-right',
            'container_class' => 'leader-image-container',
            'alt_text' => 'Pastor Jotham Bright Mulinde - Church Elder',
            'name' => 'Pastor Jotham Bright Mulinde',
            'title' => 'Church Elder'
        ],
        'pastor6.jpeg' => [
            'position' => 'leadership-team',
            'animation' => 'fade-in-up',
            'container_class' => 'leader-image-container',
            'alt_text' => 'Pastor Jonathan Ngobi - Bulanga Branch Pastor',
            'name' => 'Pastor Jonathan Ngobi',
            'title' => 'Bulanga Branch Pastor'
        ],
        'pastor7.jpeg' => [
            'position' => 'leadership-team',
            'animation' => 'fade-in-left',
            'container_class' => 'leader-image-container',
            'alt_text' => 'Pastor Miriam Gerald - Children\'s Ministry Director',
            'name' => 'Pastor Miriam Gerald',
            'title' => 'Children\'s Ministry Director'
        ]
    ],
    'gallery.php' => [
        'gallery1.jpg' => [
            'position' => 'gallery-grid',
            'animation' => 'fade-in-up',
            'container_class' => 'gallery-item',
            'alt_text' => 'Church Gallery Image 1'
        ],
        'gallery2.jpg' => [
            'position' => 'gallery-grid',
            'animation' => 'fade-in-left',
            'container_class' => 'gallery-item',
            'alt_text' => 'Church Gallery Image 2'
        ],
        'gallery3.jpg' => [
            'position' => 'gallery-grid',
            'animation' => 'fade-in-right',
            'container_class' => 'gallery-item',
            'alt_text' => 'Church Gallery Image 3'
        ],
        'gallery4.jpg' => [
            'position' => 'gallery-grid',
            'animation' => 'fade-in-up',
            'container_class' => 'gallery-item',
            'alt_text' => 'Church Gallery Image 4'
        ],
        'gallery5.jpg' => [
            'position' => 'gallery-grid',
            'animation' => 'fade-in-left',
            'container_class' => 'gallery-item',
            'alt_text' => 'Church Gallery Image 5'
        ],
        'gallery6.jpg' => [
            'position' => 'gallery-grid',
            'animation' => 'fade-in-right',
            'container_class' => 'gallery-item',
            'alt_text' => 'Church Gallery Image 6'
        ]
    ],
    'sermons.php' => [
        'sermons-banner.jpg' => [
            'position' => 'hero-background',
            'animation' => 'hero-pulse',
            'container_class' => 'hero-section',
            'alt_text' => 'Sermons and Teachings'
        ],
        'preaching.jpg' => [
            'position' => 'preaching-section',
            'animation' => 'fade-in-left',
            'container_class' => 'sermon-image-container',
            'alt_text' => 'Preaching and Teaching'
        ],
        'bible-teaching.jpg' => [
            'position' => 'teaching-section',
            'animation' => 'fade-in-right',
            'container_class' => 'sermon-image-container',
            'alt_text' => 'Bible Teaching Session'
        ]
    ],
    'news.php' => [
        'news-banner.jpg' => [
            'position' => 'hero-background',
            'animation' => 'hero-pulse',
            'container_class' => 'hero-section',
            'alt_text' => 'Church News and Updates'
        ],
        'news1.jpg' => [
            'position' => 'news-grid',
            'animation' => 'fade-in-up',
            'container_class' => 'news-item',
            'alt_text' => 'Church News Article 1'
        ],
        'news2.jpg' => [
            'position' => 'news-grid',
            'animation' => 'fade-in-left',
            'container_class' => 'news-item',
            'alt_text' => 'Church News Article 2'
        ]
    ],
    'contact.php' => [
        'contact-banner.jpg' => [
            'position' => 'hero-background',
            'animation' => 'hero-pulse',
            'container_class' => 'hero-section',
            'alt_text' => 'Contact Salem Dominion Ministries'
        ],
        'church-location.jpg' => [
            'position' => 'location-section',
            'animation' => 'fade-in-up',
            'container_class' => 'location-image-container',
            'alt_text' => 'Church Location and Building'
        ]
    ],
    'map.php' => [
        'map-hero.jpg' => [
            'position' => 'hero-background',
            'animation' => 'hero-pulse',
            'container_class' => 'hero-section',
            'alt_text' => 'Find Us - Church Locations Map'
        ],
        'church-main.jpg' => [
            'position' => 'location-cards',
            'animation' => 'fade-in-up',
            'container_class' => 'location-image-container',
            'alt_text' => 'Main Church Location'
        ],
        'church-branch1.jpg' => [
            'position' => 'location-cards',
            'animation' => 'fade-in-left',
            'container_class' => 'location-image-container',
            'alt_text' => 'Busowa Branch Church'
        ],
        'church-branch2.jpg' => [
            'position' => 'location-cards',
            'animation' => 'fade-in-right',
            'container_class' => 'location-image-container',
            'alt_text' => 'Nakavule Branch Church'
        ],
        'prayer-center.jpg' => [
            'position' => 'location-cards',
            'animation' => 'fade-in-up',
            'container_class' => 'location-image-container',
            'alt_text' => 'Prayer Center Location'
        ],
        'youth-center.jpg' => [
            'position' => 'location-cards',
            'animation' => 'fade-in-left',
            'container_class' => 'location-image-container',
            'alt_text' => 'Youth Center Location'
        ],
        'map-preview.jpg' => [
            'position' => 'map-preview',
            'animation' => 'fade-in-right',
            'container_class' => 'map-preview-container',
            'alt_text' => 'Map Preview'
        ]
    ]
];

// Generate comprehensive CSS for all image animations
$imageAnimationCSS = '
/* Image Loading Animations */
.image-loading {
    position: relative;
    background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
    background-size: 200% 100%;
    animation: loading 1.5s infinite;
    border-radius: 10px;
}

@keyframes loading {
    0% { background-position: 200% 0; }
    100% { background-position: -200% 0; }
}

/* Image Fade In Animations */
.image-fade-in-up {
    animation: fadeInUp 0.8s ease-in-out;
}

.image-fade-in-left {
    animation: fadeInLeft 0.8s ease-in-out;
}

.image-fade-in-right {
    animation: fadeInRight 0.8s ease-in-out;
}

.image-fade-in-down {
    animation: fadeInDown 0.8s ease-in-out;
}

@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

@keyframes fadeInLeft {
    from {
        opacity: 0;
        transform: translateX(-30px);
    }
    to {
        opacity: 1;
        transform: translateX(0);
    }
}

@keyframes fadeInRight {
    from {
        opacity: 0;
        transform: translateX(30px);
    }
    to {
        opacity: 1;
        transform: translateX(0);
    }
}

@keyframes fadeInDown {
    from {
        opacity: 0;
        transform: translateY(-30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

/* Hero Pulse Animation */
.hero-pulse {
    animation: heroPulse 4s ease-in-out infinite;
}

@keyframes heroPulse {
    0%, 100% { transform: scale(1); }
    50% { transform: scale(1.02); }
}

/* Hero Background Styles */
.hero-section {
    position: relative;
    background-size: cover;
    background-position: center;
    background-attachment: fixed;
}

.hero-section::before {
    content: "";
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0, 0, 0, 0.5);
    z-index: 1;
}

.hero-content {
    position: relative;
    z-index: 2;
}

/* Leadership Image Styles */
.pastor-image-container {
    position: relative;
    height: 400px;
    overflow: hidden;
    border-radius: 20px 20px 0 0;
}

.pastor-image {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.3s ease;
}

.pastor-card:hover .pastor-image {
    transform: scale(1.05);
}

.leader-image-container {
    position: relative;
    height: 250px;
    overflow: hidden;
    border-radius: 15px 15px 0 0;
}

.leader-image {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.3s ease;
}

.leader-card:hover .leader-image {
    transform: scale(1.1);
}

/* Gallery Image Styles */
.gallery-item {
    position: relative;
    overflow: hidden;
    border-radius: 15px;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
    transition: all 0.3s ease;
}

.gallery-item:hover {
    transform: translateY(-10px);
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.2);
}

.gallery-item img {
    width: 100%;
    height: 250px;
    object-fit: cover;
    transition: transform 0.3s ease;
}

.gallery-item:hover img {
    transform: scale(1.05);
}

/* Ministry Image Styles */
.ministry-image-container {
    position: relative;
    height: 200px;
    overflow: hidden;
    border-radius: 15px;
    margin-bottom: 1.5rem;
}

.ministry-image {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.3s ease;
}

.ministry-card:hover .ministry-image {
    transform: scale(1.05);
}

/* Event Image Styles */
.event-image-container {
    position: relative;
    height: 250px;
    overflow: hidden;
    border-radius: 15px;
    margin-bottom: 1.5rem;
}

.event-image {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.3s ease;
}

.event-card:hover .event-image {
    transform: scale(1.05);
}

/* News Image Styles */
.news-item {
    position: relative;
    overflow: hidden;
    border-radius: 15px;
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
    transition: all 0.3s ease;
}

.news-item:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
}

.news-item img {
    width: 100%;
    height: 200px;
    object-fit: cover;
    transition: transform 0.3s ease;
}

.news-item:hover img {
    transform: scale(1.05);
}

/* Sermon Image Styles */
.sermon-image-container {
    position: relative;
    height: 300px;
    overflow: hidden;
    border-radius: 15px;
    margin-bottom: 1.5rem;
}

.sermon-image {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.3s ease;
}

.sermon-card:hover .sermon-image {
    transform: scale(1.05);
}

/* Location Image Styles */
.location-image-container {
    position: relative;
    height: 200px;
    overflow: hidden;
    border-radius: 15px;
    margin-bottom: 1rem;
}

.location-image {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.3s ease;
}

.location-card:hover .location-image {
    transform: scale(1.05);
}

/* Responsive Images */
.responsive-image {
    max-width: 100%;
    height: auto;
    display: block;
}

/* Image Overlay Effects */
.image-overlay {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: linear-gradient(transparent, rgba(0, 0, 0, 0.7));
    display: flex;
    align-items: flex-end;
    padding: 1.5rem;
    color: white;
    opacity: 0;
    transition: opacity 0.3s ease;
}

.gallery-item:hover .image-overlay,
.news-item:hover .image-overlay {
    opacity: 1;
}

/* Position Badges */
.position-badge {
    position: absolute;
    top: 15px;
    right: 15px;
    background: linear-gradient(135deg, #FFD700, #FFA500);
    color: #1a1a2e;
    padding: 0.5rem 1rem;
    border-radius: 20px;
    font-weight: 600;
    font-size: 0.8rem;
    box-shadow: 0 3px 10px rgba(255, 215, 0, 0.3);
    z-index: 2;
}

/* Mobile Responsive */
@media (max-width: 768px) {
    .pastor-image-container {
        height: 300px;
    }
    
    .leader-image-container {
        height: 200px;
    }
    
    .gallery-item img,
    .news-item img,
    .event-image,
    .sermon-image {
        height: 200px;
    }
    
    .ministry-image-container,
    .location-image-container {
        height: 150px;
    }
}
';

// Function to generate HTML for images with proper animations
function generateImageHTML($imageName, $imageData, $pageName) {
    $containerClass = $imageData['container_class'] ?? 'image-container';
    $animation = $imageData['animation'] ?? 'fade-in-up';
    $altText = $imageData['alt_text'] ?? $imageName;
    $position = $imageData['position'] ?? 'default';
    
    // Special handling for leadership images
    if ($pageName === 'leadership.php' && isset($imageData['name'])) {
        $badgeHTML = isset($imageData['title']) ? 
            "<div class='position-badge'>{$imageData['title']}</div>" : '';
        
        return "
        <div class='{$containerClass}'>
            <img src='public/images/{$imageName}' 
                 alt='{$altText}' 
                 class='{$animation} image-loading'
                 onload='this.classList.remove(\"image-loading\"); this.classList.add(\"image-fade-in\");'
                 onerror='this.src=\"assets/images/placeholder.jpg\"; this.classList.remove(\"image-loading\");'>
            {$badgeHTML}
        </div>";
    }
    
    // Special handling for hero backgrounds
    if ($position === 'hero-background') {
        return "
        <div class='{$containerClass}' style='background-image: url(\"public/images/{$imageName}\");'>
            <div class='hero-content'>
                <!-- Hero content will be added here -->
            </div>
        </div>";
    }
    
    // Standard image HTML
    return "
    <div class='{$containerClass}'>
        <img src='public/images/{$imageName}' 
             alt='{$altText}' 
             class='{$animation} image-loading'
             onload='this.classList.remove(\"image-loading\"); this.classList.add(\"image-fade-in\");'
             onerror='this.src=\"assets/images/placeholder.jpg\"; this.classList.remove(\"image-loading\");'>
    </div>";
}

// Function to create image grid for gallery
function createGalleryGrid($images) {
    $html = "<div class='gallery-grid row g-4'>";
    foreach ($images as $imageName => $imageData) {
        $html .= "
        <div class='col-lg-4 col-md-6'>
            <div class='gallery-item'>
                <img src='public/images/{$imageName}' 
                     alt='{$imageData['alt_text']}' 
                     class='{$imageData['animation']} image-loading'
                     onload='this.classList.remove(\"image-loading\"); this.classList.add(\"image-fade-in\");'
                     onerror='this.src=\"assets/images/placeholder.jpg\"; this.classList.remove(\"image-loading\");'>
                <div class='image-overlay'>
                    <h5>{$imageData['alt_text']}</h5>
                </div>
            </div>
        </div>";
    }
    $html .= "</div>";
    return $html;
}

// Function to create leadership team section
function createLeadershipSection($leadershipImages) {
    $html = "<div class='leadership-grid'>";
    foreach ($leadershipImages as $imageName => $imageData) {
        $html .= "
        <div class='leader-card'>
            <div class='leader-image-container'>
                <img src='public/images/{$imageName}' 
                     alt='{$imageData['alt_text']}' 
                     class='{$imageData['animation']} image-loading'
                     onload='this.classList.remove(\"image-loading\"); this.classList.add(\"image-fade-in\");'
                     onerror='this.src=\"assets/images/placeholder.jpg\"; this.classList.remove(\"image-loading\");'>
                <div class='position-badge'>{$imageData['title']}</div>
            </div>
            <div class='leader-content'>
                <h3 class='leader-name'>{$imageData['name']}</h3>
                <p class='leader-position'>{$imageData['title']}</p>
            </div>
        </div>";
    }
    $html .= "</div>";
    return $html;
}

// Main integration class
class ImageIntegrator {
    private $imageMappings;
    private $imageAnimationCSS;
    
    public function __construct() {
        global $imageMappings, $imageAnimationCSS;
        $this->imageMappings = $imageMappings;
        $this->imageAnimationCSS = $imageAnimationCSS;
    }
    
    public function generateCSS() {
        return $this->imageAnimationCSS;
    }
    
    public function getImagesForPage($pageName) {
        return $this->imageMappings[$pageName] ?? [];
    }
    
    public function generatePageImages($pageName) {
        $images = $this->getImagesForPage($pageName);
        $html = "";
        
        if ($pageName === 'leadership.php') {
            // Special handling for leadership page
            $html .= createLeadershipSection($images);
        } elseif ($pageName === 'gallery.php') {
            // Special handling for gallery page
            $html .= createGalleryGrid($images);
        } else {
            // Standard handling for other pages
            foreach ($images as $imageName => $imageData) {
                $html .= generateImageHTML($imageName, $imageData, $pageName);
            }
        }
        
        return $html;
    }
    
    public function validateImages() {
        $results = [];
        $publicImagesDir = __DIR__ . '/public/images';
        
        if (!is_dir($publicImagesDir)) {
            $results['error'] = 'Public images directory not found';
            return $results;
        }
        
        $existingImages = scandir($publicImagesDir);
        $existingImages = array_diff($existingImages, ['.', '..']);
        
        $results['existing'] = $existingImages;
        $results['mapped'] = [];
        $results['missing'] = [];
        
        foreach ($this->imageMappings as $page => $images) {
            foreach ($images as $imageName => $imageData) {
                if (in_array($imageName, $existingImages)) {
                    $results['mapped'][] = $imageName;
                } else {
                    $results['missing'][] = $imageName;
                }
            }
        }
        
        return $results;
    }
    
    public function createImageIndex() {
        $index = [];
        foreach ($this->imageMappings as $page => $images) {
            $index[$page] = array_keys($images);
        }
        return $index;
    }
}

// Initialize image integrator
$imageIntegrator = new ImageIntegrator();

// Create CSS file for image animations
if (!file_exists(__DIR__ . '/assets/css/image_animations.css')) {
    file_put_contents(__DIR__ . '/assets/css/image_animations.css', $imageIntegrator->generateCSS());
}

// Create image index file
$imageIndex = $imageIntegrator->createImageIndex();
file_put_contents(__DIR__ . 'image_index.json', json_encode($imageIndex, JSON_PRETTY_PRINT));

// Display results
if (isset($_GET['validate_images'])) {
    $validation = $imageIntegrator->validateImages();
    echo "<h3>Image Validation Results</h3>";
    echo "<pre>";
    print_r($validation);
    echo "</pre>";
    exit;
}

// Display image mappings
if (isset($_GET['show_mappings'])) {
    echo "<h3>Image Mappings</h3>";
    echo "<pre>";
    print_r($imageIntegrator->createImageIndex());
    echo "</pre>";
    exit;
}

echo "Image integration system created successfully!";
echo "<br>";
echo "<a href='?validate_images=1'>Validate Images</a> | ";
echo "<a href='?show_mappings=1'>Show Mappings</a>";
echo "<br>";
echo "<a href='image_index.json'>View Image Index</a>";
?>
