<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit(0);
}

require_once '../config/database.php';

// Get request method and path
$method = $_SERVER['REQUEST_METHOD'];
$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$path_parts = explode('/', trim($path, '/'));

// Remove 'api' from path if present
if ($path_parts[0] === 'api') {
    array_shift($path_parts);
}

// Remove 'children-ministry' from path if present
if ($path_parts[0] === 'children-ministry') {
    array_shift($path_parts);
}

$endpoint = $path_parts[0] ?? '';
$id = $path_parts[1] ?? null;

try {
    switch ($endpoint) {
        case 'classes':
            handleClasses($method, $id);
            break;
        case 'registration':
            handleRegistration($method, $id);
            break;
        case 'events':
            handleChildrenEvents($method, $id);
            break;
        case 'lessons':
            handleLessons($method, $id);
            break;
        case 'attendance':
            handleAttendance($method, $id);
            break;
        case 'gallery':
            handleChildrenGallery($method, $id);
            break;
        case 'teachers':
            handleTeachers($method, $id);
            break;
        case 'resources':
            handleResources($method, $id);
            break;
        case 'news':
            handleChildrenNews($method, $id);
            break;
        default:
            sendResponse(['error' => 'Endpoint not found'], 404);
    }
} catch (Exception $e) {
    sendResponse(['error' => $e->getMessage()], 500);
}

function handleClasses($method, $id) {
    global $pdo;
    
    switch ($method) {
        case 'GET':
            if ($id) {
                $stmt = $pdo->prepare("SELECT * FROM children_classes WHERE id = ?");
                $stmt->execute([$id]);
                $class = $stmt->fetch(PDO::FETCH_ASSOC);
                sendResponse($class ?: ['error' => 'Class not found'], $class ? 200 : 404);
            } else {
                $stmt = $pdo->query("SELECT * FROM children_classes WHERE status = 'active' ORDER BY age_group");
                $classes = $stmt->fetchAll(PDO::FETCH_ASSOC);
                sendResponse($classes);
            }
            break;
            
        case 'POST':
            $data = json_decode(file_get_contents('php://input'), true);
            $stmt = $pdo->prepare("INSERT INTO children_classes (class_name, age_group, description, capacity, meeting_time, meeting_room, teacher_id) VALUES (?, ?, ?, ?, ?, ?, ?)");
            $stmt->execute([
                $data['class_name'],
                $data['age_group'],
                $data['description'],
                $data['capacity'],
                $data['meeting_time'],
                $data['meeting_room'],
                $data['teacher_id'] ?? null
            ]);
            sendResponse(['id' => $pdo->lastInsertId(), 'message' => 'Class created successfully'], 201);
            break;
            
        case 'PUT':
            if (!$id) {
                sendResponse(['error' => 'Class ID required'], 400);
            }
            $data = json_decode(file_get_contents('php://input'), true);
            $stmt = $pdo->prepare("UPDATE children_classes SET class_name = ?, age_group = ?, description = ?, capacity = ?, meeting_time = ?, meeting_room = ?, teacher_id = ? WHERE id = ?");
            $stmt->execute([
                $data['class_name'],
                $data['age_group'],
                $data['description'],
                $data['capacity'],
                $data['meeting_time'],
                $data['meeting_room'],
                $data['teacher_id'] ?? null,
                $id
            ]);
            sendResponse(['message' => 'Class updated successfully']);
            break;
            
        case 'DELETE':
            if (!$id) {
                sendResponse(['error' => 'Class ID required'], 400);
            }
            $stmt = $pdo->prepare("UPDATE children_classes SET status = 'inactive' WHERE id = ?");
            $stmt->execute([$id]);
            sendResponse(['message' => 'Class deleted successfully']);
            break;
    }
}

function handleRegistration($method, $id) {
    global $pdo;
    
    switch ($method) {
        case 'GET':
            if ($id) {
                $stmt = $pdo->prepare("SELECT cr.*, cc.class_name, cc.age_group FROM children_registration cr LEFT JOIN children_classes cc ON cr.class_id = cc.id WHERE cr.id = ?");
                $stmt->execute([$id]);
                $child = $stmt->fetch(PDO::FETCH_ASSOC);
                sendResponse($child ?: ['error' => 'Registration not found'], $child ? 200 : 404);
            } else {
                $stmt = $pdo->query("SELECT cr.*, cc.class_name, cc.age_group FROM children_registration cr LEFT JOIN children_classes cc ON cr.class_id = cc.id WHERE cr.status = 'active' ORDER BY cr.created_at DESC");
                $registrations = $stmt->fetchAll(PDO::FETCH_ASSOC);
                sendResponse($registrations);
            }
            break;
            
        case 'POST':
            $data = json_decode(file_get_contents('php://input'), true);
            $stmt = $pdo->prepare("INSERT INTO children_registration (first_name, last_name, date_of_birth, age, gender, parent_name, parent_email, parent_phone, address, emergency_contact, medical_info, allergies, special_needs, class_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->execute([
                $data['first_name'],
                $data['last_name'],
                $data['date_of_birth'],
                $data['age'],
                $data['gender'],
                $data['parent_name'],
                $data['parent_email'],
                $data['parent_phone'],
                $data['address'],
                $data['emergency_contact'],
                $data['medical_info'],
                $data['allergies'],
                $data['special_needs'],
                $data['class_id'] ?? null
            ]);
            sendResponse(['id' => $pdo->lastInsertId(), 'message' => 'Child registered successfully'], 201);
            break;
            
        case 'PUT':
            if (!$id) {
                sendResponse(['error' => 'Registration ID required'], 400);
            }
            $data = json_decode(file_get_contents('php://input'), true);
            $stmt = $pdo->prepare("UPDATE children_registration SET first_name = ?, last_name = ?, date_of_birth = ?, age = ?, gender = ?, parent_name = ?, parent_email = ?, parent_phone = ?, address = ?, emergency_contact = ?, medical_info = ?, allergies = ?, special_needs = ?, class_id = ? WHERE id = ?");
            $stmt->execute([
                $data['first_name'],
                $data['last_name'],
                $data['date_of_birth'],
                $data['age'],
                $data['gender'],
                $data['parent_name'],
                $data['parent_email'],
                $data['parent_phone'],
                $data['address'],
                $data['emergency_contact'],
                $data['medical_info'],
                $data['allergies'],
                $data['special_needs'],
                $data['class_id'] ?? null,
                $id
            ]);
            sendResponse(['message' => 'Registration updated successfully']);
            break;
    }
}

function handleChildrenEvents($method, $id) {
    global $pdo;
    
    switch ($method) {
        case 'GET':
            if ($id) {
                $stmt = $pdo->prepare("SELECT * FROM children_events WHERE id = ?");
                $stmt->execute([$id]);
                $event = $stmt->fetch(PDO::FETCH_ASSOC);
                sendResponse($event ?: ['error' => 'Event not found'], $event ? 200 : 404);
            } else {
                $stmt = $pdo->query("SELECT * FROM children_events WHERE status = 'published' AND event_date >= NOW() ORDER BY event_date LIMIT 10");
                $events = $stmt->fetchAll(PDO::FETCH_ASSOC);
                sendResponse($events);
            }
            break;
            
        case 'POST':
            $data = json_decode(file_get_contents('php://input'), true);
            $stmt = $pdo->prepare("INSERT INTO children_events (title, description, event_date, end_date, location, age_group, max_participants, registration_fee, image_url, event_type, created_by) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->execute([
                $data['title'],
                $data['description'],
                $data['event_date'],
                $data['end_date'],
                $data['location'],
                $data['age_group'],
                $data['max_participants'],
                $data['registration_fee'],
                $data['image_url'],
                $data['event_type'],
                $data['created_by'] ?? null
            ]);
            sendResponse(['id' => $pdo->lastInsertId(), 'message' => 'Event created successfully'], 201);
            break;
            
        case 'PUT':
            if (!$id) {
                sendResponse(['error' => 'Event ID required'], 400);
            }
            $data = json_decode(file_get_contents('php://input'), true);
            $stmt = $pdo->prepare("UPDATE children_events SET title = ?, description = ?, event_date = ?, end_date = ?, location = ?, age_group = ?, max_participants = ?, registration_fee = ?, image_url = ?, event_type = ?, status = ? WHERE id = ?");
            $stmt->execute([
                $data['title'],
                $data['description'],
                $data['event_date'],
                $data['end_date'],
                $data['location'],
                $data['age_group'],
                $data['max_participants'],
                $data['registration_fee'],
                $data['image_url'],
                $data['event_type'],
                $data['status'],
                $id
            ]);
            sendResponse(['message' => 'Event updated successfully']);
            break;
    }
}

function handleLessons($method, $id) {
    global $pdo;
    
    switch ($method) {
        case 'GET':
            if ($id) {
                $stmt = $pdo->prepare("SELECT cl.*, cc.class_name, u.first_name, u.last_name FROM children_lessons cl LEFT JOIN children_classes cc ON cl.class_id = cc.id LEFT JOIN users u ON cl.teacher_id = u.id WHERE cl.id = ?");
                $stmt->execute([$id]);
                $lesson = $stmt->fetch(PDO::FETCH_ASSOC);
                sendResponse($lesson ?: ['error' => 'Lesson not found'], $lesson ? 200 : 404);
            } else {
                $stmt = $pdo->query("SELECT cl.*, cc.class_name, u.first_name, u.last_name FROM children_lessons cl LEFT JOIN children_classes cc ON cl.class_id = cc.id LEFT JOIN users u ON cl.teacher_id = u.id WHERE cl.status = 'published' ORDER BY cl.lesson_date DESC LIMIT 20");
                $lessons = $stmt->fetchAll(PDO::FETCH_ASSOC);
                sendResponse($lessons);
            }
            break;
            
        case 'POST':
            $data = json_decode(file_get_contents('php://input'), true);
            $stmt = $pdo->prepare("INSERT INTO children_lessons (title, bible_verse, lesson_content, lesson_objectives, age_group, class_id, lesson_date, teacher_id, materials_needed, activity_description, prayer_points, memory_verse, image_url, video_url) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->execute([
                $data['title'],
                $data['bible_verse'],
                $data['lesson_content'],
                $data['lesson_objectives'],
                $data['age_group'],
                $data['class_id'],
                $data['lesson_date'],
                $data['teacher_id'],
                $data['materials_needed'],
                $data['activity_description'],
                $data['prayer_points'],
                $data['memory_verse'],
                $data['image_url'],
                $data['video_url']
            ]);
            sendResponse(['id' => $pdo->lastInsertId(), 'message' => 'Lesson created successfully'], 201);
            break;
    }
}

function handleAttendance($method, $id) {
    global $pdo;
    
    switch ($method) {
        case 'GET':
            if ($id) {
                $stmt = $pdo->prepare("SELECT ca.*, cr.first_name, cr.last_name, cc.class_name FROM children_attendance ca LEFT JOIN children_registration cr ON ca.child_id = cr.id LEFT JOIN children_classes cc ON ca.class_id = cc.id WHERE ca.id = ?");
                $stmt->execute([$id]);
                $attendance = $stmt->fetch(PDO::FETCH_ASSOC);
                sendResponse($attendance ?: ['error' => 'Attendance record not found'], $attendance ? 200 : 404);
            } else {
                $date = $_GET['date'] ?? date('Y-m-d');
                $stmt = $pdo->prepare("SELECT ca.*, cr.first_name, cr.last_name, cc.class_name FROM children_attendance ca LEFT JOIN children_registration cr ON ca.child_id = cr.id LEFT JOIN children_classes cc ON ca.class_id = cc.id WHERE ca.attendance_date = ? ORDER BY ca.check_in_time");
                $stmt->execute([$date]);
                $attendance = $stmt->fetchAll(PDO::FETCH_ASSOC);
                sendResponse($attendance);
            }
            break;
            
        case 'POST':
            $data = json_decode(file_get_contents('php://input'), true);
            $stmt = $pdo->prepare("INSERT INTO children_attendance (child_id, class_id, lesson_id, attendance_date, check_in_time, check_out_time, attended, notes, marked_by) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->execute([
                $data['child_id'],
                $data['class_id'],
                $data['lesson_id'],
                $data['attendance_date'],
                $data['check_in_time'],
                $data['check_out_time'],
                $data['attended'],
                $data['notes'],
                $data['marked_by']
            ]);
            sendResponse(['id' => $pdo->lastInsertId(), 'message' => 'Attendance recorded successfully'], 201);
            break;
    }
}

function handleChildrenGallery($method, $id) {
    global $pdo;
    
    switch ($method) {
        case 'GET':
            if ($id) {
                $stmt = $pdo->prepare("SELECT * FROM children_gallery WHERE id = ?");
                $stmt->execute([$id]);
                $item = $stmt->fetch(PDO::FETCH_ASSOC);
                sendResponse($item ?: ['error' => 'Gallery item not found'], $item ? 200 : 404);
            } else {
                $category = $_GET['category'] ?? null;
                $sql = "SELECT * FROM children_gallery WHERE status = 'published'";
                $params = [];
                
                if ($category) {
                    $sql .= " AND category = ?";
                    $params[] = $category;
                }
                
                $sql .= " ORDER BY created_at DESC LIMIT 50";
                $stmt = $pdo->prepare($sql);
                $stmt->execute($params);
                $gallery = $stmt->fetchAll(PDO::FETCH_ASSOC);
                sendResponse($gallery);
            }
            break;
            
        case 'POST':
            $data = json_decode(file_get_contents('php://input'), true);
            $stmt = $pdo->prepare("INSERT INTO children_gallery (title, description, file_url, file_type, file_size, dimensions, category, event_id, class_id, tags, is_featured, uploaded_by) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->execute([
                $data['title'],
                $data['description'],
                $data['file_url'],
                $data['file_type'],
                $data['file_size'],
                $data['dimensions'],
                $data['category'],
                $data['event_id'],
                $data['class_id'],
                json_encode($data['tags'] ?? []),
                $data['is_featured'],
                $data['uploaded_by']
            ]);
            sendResponse(['id' => $pdo->lastInsertId(), 'message' => 'Gallery item added successfully'], 201);
            break;
    }
}

function handleTeachers($method, $id) {
    global $pdo;
    
    switch ($method) {
        case 'GET':
            if ($id) {
                $stmt = $pdo->prepare("SELECT ct.*, u.email, u.phone FROM children_teachers ct LEFT JOIN users u ON ct.user_id = u.id WHERE ct.id = ?");
                $stmt->execute([$id]);
                $teacher = $stmt->fetch(PDO::FETCH_ASSOC);
                sendResponse($teacher ?: ['error' => 'Teacher not found'], $teacher ? 200 : 404);
            } else {
                $stmt = $pdo->query("SELECT ct.*, u.email, u.phone FROM children_teachers ct LEFT JOIN users u ON ct.user_id = u.id WHERE ct.status = 'active' ORDER BY ct.hire_date DESC");
                $teachers = $stmt->fetchAll(PDO::FETCH_ASSOC);
                sendResponse($teachers);
            }
            break;
            
        case 'POST':
            $data = json_decode(file_get_contents('php://input'), true);
            $stmt = $pdo->prepare("INSERT INTO children_teachers (user_id, bio, experience_years, qualifications, background_check, background_check_date, training_completed, preferred_age_group, specialties, status, hire_date) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->execute([
                $data['user_id'],
                $data['bio'],
                $data['experience_years'],
                $data['qualifications'],
                $data['background_check'],
                $data['background_check_date'],
                $data['training_completed'],
                $data['preferred_age_group'],
                $data['specialties'],
                $data['status'],
                $data['hire_date']
            ]);
            sendResponse(['id' => $pdo->lastInsertId(), 'message' => 'Teacher added successfully'], 201);
            break;
    }
}

function handleResources($method, $id) {
    global $pdo;
    
    switch ($method) {
        case 'GET':
            if ($id) {
                $stmt = $pdo->prepare("SELECT * FROM children_resources WHERE id = ?");
                $stmt->execute([$id]);
                $resource = $stmt->fetch(PDO::FETCH_ASSOC);
                sendResponse($resource ?: ['error' => 'Resource not found'], $resource ? 200 : 404);
            } else {
                $age_group = $_GET['age_group'] ?? null;
                $resource_type = $_GET['resource_type'] ?? null;
                
                $sql = "SELECT * FROM children_resources WHERE status = 'published'";
                $params = [];
                
                if ($age_group) {
                    $sql .= " AND age_group = ?";
                    $params[] = $age_group;
                }
                
                if ($resource_type) {
                    $sql .= " AND resource_type = ?";
                    $params[] = $resource_type;
                }
                
                $sql .= " ORDER BY created_at DESC LIMIT 30";
                $stmt = $pdo->prepare($sql);
                $stmt->execute($params);
                $resources = $stmt->fetchAll(PDO::FETCH_ASSOC);
                sendResponse($resources);
            }
            break;
            
        case 'POST':
            $data = json_decode(file_get_contents('php://input'), true);
            $stmt = $pdo->prepare("INSERT INTO children_resources (title, description, resource_type, file_url, file_size, age_group, category, tags, is_free, created_by) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->execute([
                $data['title'],
                $data['description'],
                $data['resource_type'],
                $data['file_url'],
                $data['file_size'],
                $data['age_group'],
                $data['category'],
                json_encode($data['tags'] ?? []),
                $data['is_free'],
                $data['created_by']
            ]);
            sendResponse(['id' => $pdo->lastInsertId(), 'message' => 'Resource created successfully'], 201);
            break;
    }
}

function handleChildrenNews($method, $id) {
    global $pdo;
    
    switch ($method) {
        case 'GET':
            if ($id) {
                $stmt = $pdo->prepare("SELECT * FROM children_news WHERE id = ?");
                $stmt->execute([$id]);
                $news = $stmt->fetch(PDO::FETCH_ASSOC);
                sendResponse($news ?: ['error' => 'News not found'], $news ? 200 : 404);
            } else {
                $stmt = $pdo->query("SELECT * FROM children_news WHERE status = 'published' ORDER BY published_at DESC LIMIT 20");
                $news = $stmt->fetchAll(PDO::FETCH_ASSOC);
                sendResponse($news);
            }
            break;
            
        case 'POST':
            $data = json_decode(file_get_contents('php://input'), true);
            $stmt = $pdo->prepare("INSERT INTO children_news (title, slug, content, excerpt, author, image_url, status, category, tags, target_audience, is_featured, is_breaking, published_at, created_by) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->execute([
                $data['title'],
                $data['slug'],
                $data['content'],
                $data['excerpt'],
                $data['author'],
                $data['image_url'],
                $data['status'],
                $data['category'],
                json_encode($data['tags'] ?? []),
                $data['target_audience'],
                $data['is_featured'],
                $data['is_breaking'],
                $data['published_at'],
                $data['created_by']
            ]);
            sendResponse(['id' => $pdo->lastInsertId(), 'message' => 'News created successfully'], 201);
            break;
    }
}

function sendResponse($data, $statusCode = 200) {
    http_response_code($statusCode);
    echo json_encode($data);
    exit;
}
?>
