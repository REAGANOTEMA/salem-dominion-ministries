<?php
require_once __DIR__ . '/../config/database.php';
$db = new Database();

$method = $_SERVER['REQUEST_METHOD'];
$input = json_decode(file_get_contents('php://input'), true);

switch ($method) {
    case 'GET':
        $id = $_GET['id'] ?? '';
        
        if ($id) {
            $result = $db->query("SELECT * FROM donations WHERE id = ?", [$id]);
            
            if ($result['success'] && count($result['data']) > 0) {
                echo json_encode([
                    'success' => true,
                    'data' => $result['data'][0]
                ]);
            } else {
                http_response_code(404);
                echo json_encode([
                    'success' => false,
                    'message' => 'Donation not found'
                ]);
            }
        } else {
            $result = $db->query("SELECT * FROM donations ORDER BY created_at DESC");
            
            echo json_encode([
                'success' => true,
                'data' => $result['data']
            ]);
        }
        break;
        
    case 'POST':
        $name = $input['name'] ?? '';
        $email = $input['email'] ?? '';
        $amount = $input['amount'] ?? '';
        $payment_method = $input['payment_method'] ?? '';
        $transaction_id = $input['transaction_id'] ?? '';
        $status = $input['status'] ?? 'pending';
        
        $result = $db->insert(
            "INSERT INTO donations (name, email, amount, payment_method, transaction_id, status, created_at) VALUES (?, ?, ?, ?, ?, ?, NOW())",
            [$name, $email, $amount, $payment_method, $transaction_id, $status]
        );
        
        if ($result['success']) {
            echo json_encode([
                'success' => true,
                'message' => 'Donation recorded successfully',
                'donation_id' => $result['insert_id']
            ]);
        } else {
            http_response_code(500);
            echo json_encode([
                'success' => false,
                'message' => 'Failed to record donation',
                'error' => $result['error']
            ]);
        }
        break;
        
    case 'PUT':
        $id = $_GET['id'] ?? '';
        $status = $input['status'] ?? '';
        
        $result = $db->update(
            "UPDATE donations SET status = ?, updated_at = NOW() WHERE id = ?",
            [$status, $id]
        );
        
        if ($result['success']) {
            echo json_encode([
                'success' => true,
                'message' => 'Donation updated successfully'
            ]);
        } else {
            http_response_code(500);
            echo json_encode([
                'success' => false,
                'message' => 'Failed to update donation',
                'error' => $result['error']
            ]);
        }
        break;
        
    case 'DELETE':
        $id = $_GET['id'] ?? '';
        
        $result = $db->delete("DELETE FROM donations WHERE id = ?", [$id]);
        
        if ($result['success']) {
            echo json_encode([
                'success' => true,
                'message' => 'Donation deleted successfully'
            ]);
        } else {
            http_response_code(500);
            echo json_encode([
                'success' => false,
                'message' => 'Failed to delete donation',
                'error' => $result['error']
            ]);
        }
        break;
        
    default:
        http_response_code(405);
        echo json_encode([
            'success' => false,
            'message' => 'Method not allowed'
        ]);
        break;
}
?>
