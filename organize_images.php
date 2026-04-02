<?php
/**
 * Image Organization Script
 * Places all images from public/images into their respective pages with proper animations
 */

// Define image mappings for different pages
$imageMappings = [
    'index.php' => [
        'hero-bg.jpg' => 'Hero section background',
        'church-building.jpg' => 'Church building image',
        'worship-service.jpg' => 'Worship service image',
        'community-service.jpg' => 'Community service image',
        'bible-study.jpg' => 'Bible study group image'
    ],
    'about.php' => [
        'about-banner.jpg' => 'About page banner',
        'church-history.jpg' => 'Church history image',
        'mission-vision.jpg' => 'Mission and vision image',
        'beliefs.jpg' => 'Beliefs and values image'
    ],
    'ministries.php' => [
        'ministries-hero.jpg' => 'Ministries hero image',
        'children-ministry.jpg' => 'Children ministry image',
        'youth-ministry.jpg' => 'Youth ministry image',
        'women-ministry.jpg' => 'Women ministry image',
        'men-ministry.jpg' => 'Men ministry image'
    ],
    'events.php' => [
        'events-banner.jpg' => 'Events banner image',
        'conference.jpg' => 'Conference image',
        'worship-night.jpg' => 'Worship night image',
        'community-outreach.jpg' => 'Community outreach image'
    ],
    'leadership.php' => [
        'pastor.jpeg' => 'Senior Pastor - Apostle Faty Musasizi',
        'pastor1.jpeg' => 'Pastor Nabulya Joyce - Church Treasurer',
        'pastor2.jpeg' => 'Apostle Irene Mirembe - Church Administrator',
        'pastor3.jpeg' => 'Evangelist Kisakye Halima - Mission Director',
        'pastor4.jpeg' => 'Pastor Damali Namwima - Altars Director',
        'pastor5.jpeg' => 'Pastor Jotham Bright Mulinde - Church Elder',
        'pastor6.jpeg' => 'Pastor Jonathan Ngobi - Bulanga Branch Pastor',
        'pastor7.jpeg' => 'Pastor Miriam Gerald - Children\'s Ministry Director'
    ],
    'gallery.php' => [
        'gallery1.jpg' => 'Gallery image 1',
        'gallery2.jpg' => 'Gallery image 2',
        'gallery3.jpg' => 'Gallery image 3',
        'gallery4.jpg' => 'Gallery image 4',
        'gallery5.jpg' => 'Gallery image 5',
        'gallery6.jpg' => 'Gallery image 6'
    ],
    'sermons.php' => [
        'sermons-banner.jpg' => 'Sermons banner image',
        'preaching.jpg' => 'Preaching image',
        'bible-teaching.jpg' => 'Bible teaching image'
    ],
    'news.php' => [
        'news-banner.jpg' => 'News banner image',
        'news1.jpg' => 'News image 1',
        'news2.jpg' => 'News image 2'
    ],
    'contact.php' => [
        'contact-banner.jpg' => 'Contact banner image',
        'church-location.jpg' => 'Church location image'
    ],
    'map.php' => [
        'map-hero.jpg' => 'Map page hero image',
        'church-main.jpg' => 'Main church image',
        'church-branch1.jpg' => 'Busowa branch image',
        'church-branch2.jpg' => 'Nakavule branch image',
        'prayer-center.jpg' => 'Prayer center image',
        'youth-center.jpg' => 'Youth center image',
        'map-preview.jpg' => 'Map preview image'
    ]
];

// Create CSS for image animations
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

/* Image Fade In Animation */
.image-fade-in {
    animation: fadeIn 0.8s ease-in-out;
}

@keyframes fadeIn {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

/* Image Hover Effects */
.image-hover-scale {
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.image-hover-scale:hover {
    transform: scale(1.05);
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
}

/* Leadership Image Styles */
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
.gallery-image {
    width: 100%;
    height: 250px;
    object-fit: cover;
    border-radius: 10px;
    transition: all 0.3s ease;
}

.gallery-image:hover {
    transform: scale(1.05);
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
}

/* Hero Image Styles */
.hero-image {
    width: 100%;
    height: 100%;
    object-fit: cover;
    animation: heroPulse 4s ease-in-out infinite;
}

@keyframes heroPulse {
    0%, 100% { transform: scale(1); }
    50% { transform: scale(1.02); }
}

/* Responsive Images */
.responsive-image {
    max-width: 100%;
    height: auto;
    display: block;
}
';

// Function to generate HTML for images with animations
function generateImageHTML($imageName, $altText, $containerClass = '', $imageClass = '') {
    return "
    <div class='image-container $containerClass'>
        <img src='public/images/$imageName' 
             alt='$altText' 
             class='$imageClass image-loading'
             onload='this.classList.remove(\"image-loading\"); this.classList.add(\"image-fade-in\");'
             onerror='this.src=\"assets/images/placeholder.jpg\"; this.classList.remove(\"image-loading\");'>
    </div>";
}

// Function to create image gallery
function createImageGallery($images, $columns = 3) {
    $html = "<div class='image-gallery row g-4'>";
    foreach ($images as $imageName => $altText) {
        $html .= "
        <div class='col-md-$columns'>
            " . generateImageHTML($imageName, $altText, 'gallery-item', 'gallery-image image-hover-scale') . "
        </div>";
    }
    $html .= "</div>";
    return $html;
}

// Function to create hero section with image
function createHeroSection($imageName, $title, $subtitle) {
    return "
    <section class='hero-section' style='background-image: url(\"public/images/$imageName\"); background-size: cover; background-position: center;'>
        <div class='hero-overlay'>
            <div class='container text-center'>
                <h1 class='hero-title'>$title</h1>
                <p class='hero-subtitle'>$subtitle</p>
            </div>
        </div>
    </section>";
}

// Function to create leadership card
function createLeadershipCard($imageName, $name, $position, $bio) {
    return "
    <div class='leader-card'>
        <div class='leader-image-container'>
            <img src='public/images/$imageName' alt='$name' class='leader-image'>
            <div class='leader-position-badge'>$position</div>
        </div>
        <div class='leader-content'>
            <h3 class='leader-name'>$name</h3>
            <p class='leader-position'>$position</p>
            <p class='leader-bio'>$bio</p>
        </div>
    </div>";
}

// Function to add images to existing pages
function addImagesToPage($pageName, $images) {
    $html = "";
    
    switch ($pageName) {
        case 'index.php':
            $html .= createHeroSection('hero-bg.jpg', 'Welcome to Salem Dominion Ministries', 'Where Faith Meets Hope');
            $html .= createImageGallery(array_slice($images, 1, 3), 4);
            break;
            
        case 'leadership.php':
            // Senior Pastor Section
            $html .= createLeadershipCard('pastor.jpeg', 'Apostle Faty Musasizi', 'Senior Pastor & Founder', 
                'Apostle Faty Musasizi is the visionary founder and Senior Pastor of Salem Dominion Ministries...');
            
            // Leadership Team
            $html .= "<div class='leadership-grid'>";
            $leadershipImages = [
                'pastor1.jpeg' => ['Pastor Nabulya Joyce', 'Church Treasurer', 'Pastor Nabulya Joyce serves faithfully as our Church Treasurer...'],
                'pastor2.jpeg' => ['Apostle Irene Mirembe', 'Church Administrator', 'Apostle Irene Mirembe oversees the administrative operations...'],
                'pastor3.jpeg' => ['Evangelist Kisakye Halima', 'Mission Director', 'Evangelist Kisakye Halima leads our mission initiatives...'],
                'pastor4.jpeg' => ['Pastor Damali Namwima', 'Altars Director', 'Pastor Damali Namwima oversees our altar ministry...'],
                'pastor5.jpeg' => ['Pastor Jotham Bright Mulinde', 'Church Elder', 'Pastor Jotham Bright Mulinde serves as a respected Church Elder...'],
                'pastor6.jpeg' => ['Pastor Jonathan Ngobi', 'Bulanga Branch Pastor', 'Pastor Jonathan Ngobi faithfully leads our Bulanga branch...'],
                'pastor7.jpeg' => ['Pastor Miriam Gerald', 'Children\'s Ministry Director', 'Pastor Miriam Gerald leads our children\'s ministry...']
            ];
            
            foreach ($leadershipImages as $imageName => $leaderInfo) {
                $html .= createLeadershipCard($imageName, $leaderInfo[0], $leaderInfo[1], $leaderInfo[2]);
            }
            $html .= "</div>";
            break;
            
        case 'gallery.php':
            $html .= createImageGallery($images, 4);
            break;
            
        case 'ministries.php':
            $html .= createHeroSection('ministries-hero.jpg', 'Our Ministries', 'Serving Together in Love');
            $html .= createImageGallery(array_slice($images, 1, 4), 6);
            break;
            
        default:
            $html .= createImageGallery($images, 3);
            break;
    }
    
    return $html;
}

// Create a comprehensive image management system
class ImageManager {
    private $imageMappings;
    
    public function __construct() {
        global $imageMappings;
        $this->imageMappings = $imageMappings;
    }
    
    public function getImagesForPage($pageName) {
        return $this->imageMappings[$pageName] ?? [];
    }
    
    public function generateImageCSS() {
        global $imageAnimationCSS;
        return $imageAnimationCSS;
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
        
        foreach ($this->imageMappings as $page => $images) {
            foreach ($images as $imageName => $description) {
                if (in_array($imageName, $existingImages)) {
                    $results['mapped'][] = $imageName;
                }
            }
        }
        
        $results['missing'] = array_diff($existingImages, $results['mapped']);
        
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

// Initialize image manager
$imageManager = new ImageManager();

// Display image validation results
if (isset($_GET['validate_images'])) {
    $validation = $imageManager->validateImages();
    echo "<pre>";
    print_r($validation);
    echo "</pre>";
    exit;
}

// Create CSS file for image animations
if (!file_exists(__DIR__ . '/assets/css/image_animations.css')) {
    file_put_contents(__DIR__ . '/assets/css/image_animations.css', $imageManager->generateImageCSS());
}

// Create image index file
$imageIndex = $imageManager->createImageIndex();
file_put_contents(__DIR__ . 'image_index.json', json_encode($imageIndex, JSON_PRETTY_PRINT));

echo "Image organization system created successfully!";
echo "<br>";
echo "<a href='?validate_images=1'>Validate Images</a>";
echo "<br>";
echo "<a href='image_index.json'>View Image Index</a>";
?>
