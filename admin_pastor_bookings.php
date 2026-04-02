<?php
require_once 'auth_system.php';
require_once 'db.php';

require_admin();

$user = get_current_user();
$errors = [];
$success = '';

// Handle booking status updates
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    $action = $_POST['action'];
    
    if ($action === 'update_status') {
        $booking_id = intval($_POST['booking_id']);
        $new_status = $_POST['status'];
        $confirmed_by = $user['id'];
        
        try {
            $stmt = $conn->prepare("UPDATE pastor_bookings SET status = ?, confirmed_by = ?, confirmed_at = NOW(), updated_at = NOW() WHERE id = ?");
            $stmt->bind_param('sii', $new_status, $confirmed_by, $booking_id);
            
            if ($stmt->execute()) {
                $success = 'Booking status updated successfully!';
            } else {
                $errors[] = 'Failed to update booking status.';
            }
            $stmt->close();
            
        } catch (Exception $e) {
            $errors[] = 'Database error: ' . $e->getMessage();
        }
    }
    
    if ($action === 'add_notes') {
        $booking_id = intval($_POST['booking_id']);
        $internal_notes = trim($_POST['internal_notes'] ?? '');
        
        try {
            $stmt = $conn->prepare("UPDATE pastor_bookings SET internal_notes = ?, updated_at = NOW() WHERE id = ?");
            $stmt->bind_param('si', $internal_notes, $booking_id);
            
            if ($stmt->execute()) {
                $success = 'Internal notes added successfully!';
            } else {
                $errors[] = 'Failed to add notes.';
            }
            $stmt->close();
            
        } catch (Exception $e) {
            $errors[] = 'Database error: ' . $e->getMessage();
        }
    }
}

// Get bookings with filtering
$filter_status = $_GET['filter_status'] ?? 'all';
$filter_type = $_GET['filter_type'] ?? 'all';

try {
    $where_clauses = [];
    $params = [];
    
    if ($filter_status !== 'all') {
        $where_clauses[] = "status = ?";
        $params[] = $filter_status;
    }
    
    if ($filter_type !== 'all') {
        $where_clauses[] = "booking_type = ?";
        $params[] = $filter_type;
    }
    
    $where_sql = !empty($where_clauses) ? 'WHERE ' . implode(' AND ', $where_clauses) : '';
    
    $sql = "SELECT pb.*, u.first_name, u.last_name as confirmed_by_name 
            FROM pastor_bookings pb 
            LEFT JOIN users u ON pb.confirmed_by = u.id 
            $where_sql 
            ORDER BY pb.booking_date DESC, pb.start_time DESC";
    
    $bookings = $db->select($sql, $params);
    
    // Get statistics
    $total_bookings = $db->selectOne("SELECT COUNT(*) as count FROM pastor_bookings")['count'];
    $pending_count = $db->selectOne("SELECT COUNT(*) as count FROM pastor_bookings WHERE status = 'pending'")['count'];
    $confirmed_count = $db->selectOne("SELECT COUNT(*) as count FROM pastor_bookings WHERE status = 'confirmed'")['count'];
    $completed_count = $db->selectOne("SELECT COUNT(*) as count FROM pastor_bookings WHERE status = 'completed'")['count'];
    
} catch (Exception $e) {
    $bookings = [];
    $total_bookings = 0;
    $pending_count = 0;
    $confirmed_count = 0;
    $completed_count = 0;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pastor Bookings Management - Salem Dominion Ministries</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f8fafc;
        }
        
        .admin-header {
            background: linear-gradient(135deg, #7c3aed 0%, #6d28d0 100%);
            color: white;
            padding: 60px 0;
            margin-bottom: 30px;
        }
        
        .stats-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.08);
            padding: 25px;
            text-align: center;
            margin-bottom: 30px;
            transition: transform 0.3s ease;
        }
        
        .stats-card:hover {
            transform: translateY(-5px);
        }
        
        .stats-number {
            font-size: 2.5rem;
            font-weight: bold;
            color: #7c3aed;
            margin-bottom: 10px;
        }
        
        .booking-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.08);
            padding: 25px;
            margin-bottom: 20px;
            border-left: 5px solid #7c3aed;
        }
        
        .booking-card.pending {
            border-left-color: #f59e0b;
        }
        
        .booking-card.confirmed {
            border-left-color: #16a34a;
        }
        
        .booking-card.completed {
            border-left-color: #3b82f6;
        }
        
        .booking-card.cancelled {
            border-left-color: #dc2626;
        }
        
        .status-badge {
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
            display: inline-block;
        }
        
        .status-pending {
            background: #fef3c7;
            color: #92400e;
        }
        
        .status-confirmed {
            background: #dcfce7;
            color: #166534;
        }
        
        .status-completed {
            background: #dbeafe;
            color: #1e40af;
        }
        
        .status-cancelled {
            background: #fef2f2;
            color: #991b1b;
        }
        
        .btn-admin {
            background: linear-gradient(135deg, #7c3aed 0%, #6d28d0 100%);
            border: none;
            border-radius: 10px;
            padding: 10px 20px;
            font-weight: 600;
            color: white;
            transition: all 0.3s ease;
        }
        
        .btn-admin:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(124, 58, 237, 0.3);
            color: white;
        }
        
        .filter-section {
            background: white;
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.08);
            padding: 25px;
            margin-bottom: 30px;
        }
        
        .booking-type-badge {
            background: #f3f4f6;
            padding: 4px 12px;
            border-radius: 15px;
            font-size: 0.8rem;
            display: inline-block;
            margin: 2px;
        }
        
        .contact-info {
            background: #f8fafc;
            padding: 15px;
            border-radius: 10px;
            margin-top: 15px;
        }
        
        .modal-content {
            border-radius: 15px;
            border: none;
        }
        
        .modal-header {
            background: linear-gradient(135deg, #7c3aed 0%, #6d28d0 100%);
            color: white;
            border-radius: 15px 15px 0 0;
        }
        
        .whatsapp-btn {
            background: #25d366;
            color: white;
            border: none;
            border-radius: 10px;
            padding: 8px 15px;
            font-size: 0.9rem;
            transition: all 0.3s ease;
        }
        
        .whatsapp-btn:hover {
            background: #128c7e;
            color: white;
            transform: translateY(-2px);
        }
        
        @media (max-width: 768px) {
            .stats-card {
                padding: 20px;
            }
            
            .stats-number {
                font-size: 2rem;
            }
            
            .booking-card {
                padding: 20px;
            }
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <?php include 'components/universal_nav.php'; ?>

    <!-- Admin Header -->
    <div class="admin-header">
        <div class="container text-center">
            <h1><i class="fas fa-user-tie"></i> Pastor Bookings Management</h1>
            <p class="lead">Manage all pastor appointment requests</p>
        </div>
    </div>

    <div class="container">
        <?php if (!empty($errors)): ?>
            <div class="alert alert-danger">
                <?php foreach ($errors as $error): ?>
                    <div><?php echo htmlspecialchars($error); ?></div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
        
        <?php if ($success): ?>
            <div class="alert alert-success">
                <?php echo htmlspecialchars($success); ?>
            </div>
        <?php endif; ?>
        
        <!-- Statistics -->
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="stats-card">
                    <div class="stats-number"><?php echo $total_bookings; ?></div>
                    <div class="text-muted">Total Bookings</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stats-card">
                    <div class="stats-number"><?php echo $pending_count; ?></div>
                    <div class="text-muted">Pending</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stats-card">
                    <div class="stats-number"><?php echo $confirmed_count; ?></div>
                    <div class="text-muted">Confirmed</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stats-card">
                    <div class="stats-number"><?php echo $completed_count; ?></div>
                    <div class="text-muted">Completed</div>
                </div>
            </div>
        </div>
        
        <!-- Filters -->
        <div class="filter-section">
            <h5><i class="fas fa-filter"></i> Filter Bookings</h5>
            <form method="GET" class="row g-3">
                <div class="col-md-4">
                    <label for="filter_status" class="form-label">Status</label>
                    <select class="form-select" id="filter_status" name="filter_status">
                        <option value="all" <?php echo $filter_status === 'all' ? 'selected' : ''; ?>>All Status</option>
                        <option value="pending" <?php echo $filter_status === 'pending' ? 'selected' : ''; ?>>Pending</option>
                        <option value="confirmed" <?php echo $filter_status === 'confirmed' ? 'selected' : ''; ?>>Confirmed</option>
                        <option value="completed" <?php echo $filter_status === 'completed' ? 'selected' : ''; ?>>Completed</option>
                        <option value="cancelled" <?php echo $filter_status === 'cancelled' ? 'selected' : ''; ?>>Cancelled</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label for="filter_type" class="form-label">Booking Type</label>
                    <select class="form-select" id="filter_type" name="filter_type">
                        <option value="all" <?php echo $filter_type === 'all' ? 'selected' : ''; ?>>All Types</option>
                        <option value="counseling" <?php echo $filter_type === 'counseling' ? 'selected' : ''; ?>>Counseling</option>
                        <option value="prayer" <?php echo $filter_type === 'prayer' ? 'selected' : ''; ?>>Prayer</option>
                        <option value="guidance" <?php echo $filter_type === 'guidance' ? 'selected' : ''; ?>>Guidance</option>
                        <option value="deliverance" <?php echo $filter_type === 'deliverance' ? 'selected' : ''; ?>>Deliverance</option>
                        <option value="thanksgiving" <?php echo $filter_type === 'thanksgiving' ? 'selected' : ''; ?>>Thanksgiving</option>
                        <option value="general" <?php echo $filter_type === 'general' ? 'selected' : ''; ?>>General</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">&nbsp;</label>
                    <button type="submit" class="btn btn-admin w-100">
                        <i class="fas fa-search"></i> Apply Filters
                    </button>
                </div>
            </form>
        </div>
        
        <!-- Bookings List -->
        <?php if (empty($bookings)): ?>
            <div class="text-center py-5">
                <i class="fas fa-calendar-times fa-3x text-muted mb-3"></i>
                <h4 class="text-muted">No bookings found</h4>
                <p class="text-muted">No bookings match the current filters.</p>
            </div>
        <?php else: ?>
            <?php foreach ($bookings as $booking): ?>
                <div class="booking-card <?php echo $booking['status']; ?>">
                    <div class="row">
                        <div class="col-md-8">
                            <div class="d-flex justify-content-between align-items-start mb-3">
                                <div>
                                    <h5><?php echo htmlspecialchars($booking['client_name']); ?></h5>
                                    <div class="mb-2">
                                        <span class="status-badge status-<?php echo $booking['status']; ?>">
                                            <?php echo ucfirst($booking['status']); ?>
                                        </span>
                                        <span class="booking-type-badge">
                                            <?php echo ucfirst($booking['booking_type']); ?>
                                        </span>
                                        <span class="badge bg-info">
                                            <i class="fas fa-hashtag"></i> <?php echo htmlspecialchars($booking['booking_reference']); ?>
                                        </span>
                                    </div>
                                </div>
                                <div class="text-end">
                                    <h6 class="text-primary mb-0">
                                        <i class="fas fa-calendar"></i> <?php echo date('M j, Y', strtotime($booking['booking_date'])); ?>
                                    </h6>
                                    <small class="text-muted">
                                        <i class="fas fa-clock"></i> <?php echo date('g:i A', strtotime($booking['start_time'])); ?> - <?php echo date('g:i A', strtotime($booking['end_time'])); ?>
                                    </small>
                                </div>
                            </div>
                            
                            <div class="contact-info">
                                <div class="row">
                                    <div class="col-md-4">
                                        <i class="fas fa-envelope"></i> <?php echo htmlspecialchars($booking['client_email']); ?>
                                    </div>
                                    <div class="col-md-4">
                                        <i class="fas fa-phone"></i> <?php echo htmlspecialchars($booking['client_phone']); ?>
                                    </div>
                                    <div class="col-md-4">
                                        <i class="fas fa-hourglass-half"></i> <?php echo $booking['duration_minutes']; ?> minutes
                                    </div>
                                </div>
                            </div>
                            
                            <?php if ($booking['subject']): ?>
                                <div class="mt-2">
                                    <strong>Subject:</strong> <?php echo htmlspecialchars($booking['subject']); ?>
                                </div>
                            <?php endif; ?>
                            
                            <?php if ($booking['description']): ?>
                                <div class="mt-2">
                                    <strong>Description:</strong> <?php echo htmlspecialchars(substr($booking['description'], 0, 100)); ?>...
                                </div>
                            <?php endif; ?>
                            
                            <?php if ($booking['prayer_points']): ?>
                                <div class="mt-2">
                                    <strong>Prayer Points:</strong> <?php echo htmlspecialchars($booking['prayer_points']); ?>
                                </div>
                            <?php endif; ?>
                            
                            <?php if ($booking['internal_notes']): ?>
                                <div class="mt-2">
                                    <strong>Internal Notes:</strong> <?php echo htmlspecialchars($booking['internal_notes']); ?>
                                </div>
                            <?php endif; ?>
                            
                            <?php if ($booking['confirmed_by_name']): ?>
                                <div class="mt-2">
                                    <small class="text-muted">
                                        <i class="fas fa-user-check"></i> Confirmed by: <?php echo htmlspecialchars($booking['confirmed_by_name']); ?>
                                    </small>
                                </div>
                            <?php endif; ?>
                        </div>
                        
                        <div class="col-md-4">
                            <div class="text-end">
                                <?php if ($booking['status'] === 'pending'): ?>
                                    <button class="btn btn-success btn-sm mb-2" onclick="updateStatus(<?php echo $booking['id']; ?>, 'confirmed')">
                                        <i class="fas fa-check"></i> Confirm
                                    </button>
                                    <button class="btn btn-warning btn-sm mb-2" onclick="updateStatus(<?php echo $booking['id']; ?>, 'cancelled')">
                                        <i class="fas fa-times"></i> Cancel
                                    </button>
                                <?php endif; ?>
                                
                                <?php if ($booking['status'] === 'confirmed'): ?>
                                    <button class="btn btn-primary btn-sm mb-2" onclick="updateStatus(<?php echo $booking['id']; ?>, 'completed')">
                                        <i class="fas fa-check-double"></i> Mark Complete
                                    </button>
                                <?php endif; ?>
                                
                                <button class="btn btn-outline-primary btn-sm mb-2" onclick="addNotes(<?php echo $booking['id']; ?>, '<?php echo htmlspecialchars($booking['internal_notes'] ?? ''); ?>')">
                                    <i class="fas fa-edit"></i> Add Notes
                                </button>
                                
                                <button class="btn whatsapp-btn btn-sm mb-2" onclick="contactClient('<?php echo htmlspecialchars($booking['client_phone']); ?>', '<?php echo htmlspecialchars($booking['client_name']); ?>')">
                                    <i class="fab fa-whatsapp"></i> WhatsApp
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
    
    <!-- Status Update Modal -->
    <div class="modal fade" id="statusModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Update Booking Status</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form method="POST" action="admin_pastor_bookings.php">
                        <input type="hidden" name="action" value="update_status">
                        <input type="hidden" id="modal_booking_id" name="booking_id">
                        
                        <div class="mb-3">
                            <label for="modal_status" class="form-label">New Status</label>
                            <select class="form-select" id="modal_status" name="status">
                                <option value="pending">Pending</option>
                                <option value="confirmed">Confirmed</option>
                                <option value="completed">Completed</option>
                                <option value="cancelled">Cancelled</option>
                                <option value="no_show">No Show</option>
                            </select>
                        </div>
                        
                        <div class="text-end">
                            <button type="submit" class="btn btn-admin">Update Status</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Notes Modal -->
    <div class="modal fade" id="notesModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add Internal Notes</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form method="POST" action="admin_pastor_bookings.php">
                        <input type="hidden" name="action" value="add_notes">
                        <input type="hidden" id="notes_booking_id" name="booking_id">
                        
                        <div class="mb-3">
                            <label for="modal_notes" class="form-label">Internal Notes</label>
                            <textarea class="form-control" id="modal_notes" name="internal_notes" rows="4" placeholder="Add internal notes for staff..."></textarea>
                        </div>
                        
                        <div class="text-end">
                            <button type="submit" class="btn btn-admin">Save Notes</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Include Footer -->
    <?php include 'components/ultimate_footer_new.php'; ?>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Update booking status
        function updateStatus(bookingId, currentStatus) {
            document.getElementById('modal_booking_id').value = bookingId;
            document.getElementById('modal_status').value = currentStatus;
            
            const modal = new bootstrap.Modal(document.getElementById('statusModal'));
            modal.show();
        }
        
        // Add notes
        function addNotes(bookingId, currentNotes) {
            document.getElementById('notes_booking_id').value = bookingId;
            document.getElementById('modal_notes').value = currentNotes;
            
            const modal = new bootstrap.Modal(document.getElementById('notesModal'));
            modal.show();
        }
        
        // Contact client via WhatsApp
        function contactClient(phone, name) {
            const message = `Hello ${name}, this is Salem Dominion Ministries. We're following up on your pastor booking request. Please let us know your availability for confirmation. God bless! 🙏`;
            const whatsappUrl = `https://wa.me/${phone.replace(/[^\d]/g, '')}?text=${encodeURIComponent(message)}`;
            window.open(whatsappUrl, '_blank');
        }
    </script>
</body>
</html>
