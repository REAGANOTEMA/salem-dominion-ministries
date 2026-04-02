<?php
require_once 'auth_system.php';
require_once 'db.php';

require_admin();

$user = get_current_user();
$errors = [];
$success = '';

// Handle donation status updates
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    $action = $_POST['action'];
    
    if ($action === 'update_status') {
        $donation_id = intval($_POST['donation_id']);
        $new_status = $_POST['status'];
        $processed_by = $user['id'];
        
        try {
            $stmt = $conn->prepare("UPDATE donations SET status = ?, processed_by = ?, updated_at = NOW() WHERE id = ?");
            $stmt->bind_param('sii', $new_status, $processed_by, $donation_id);
            
            if ($stmt->execute()) {
                $success = 'Donation status updated successfully!';
            } else {
                $errors[] = 'Failed to update donation status.';
            }
            $stmt->close();
            
        } catch (Exception $e) {
            $errors[] = 'Database error: ' . $e->getMessage();
        }
    }
    
    if ($action === 'add_note') {
        $donation_id = intval($_POST['donation_id']);
        $notes = trim($_POST['notes'] ?? '');
        
        try {
            $stmt = $conn->prepare("UPDATE donations SET notes = ?, updated_at = NOW() WHERE id = ?");
            $stmt->bind_param('si', $notes, $donation_id);
            
            if ($stmt->execute()) {
                $success = 'Notes added successfully!';
            } else {
                $errors[] = 'Failed to add notes.';
            }
            $stmt->close();
            
        } catch (Exception $e) {
            $errors[] = 'Database error: ' . $e->getMessage();
        }
    }
}

// Get donations with filtering - matching exact database structure
$filter_status = $_GET['filter_status'] ?? 'all';
$filter_type = $_GET['filter_type'] ?? 'all';

try {
    $where_clauses = [];
    $params = [];
    
    if ($filter_status !== 'all') {
        $where_clauses[] = "d.status = ?";
        $params[] = $filter_status;
    }
    
    if ($filter_type !== 'all') {
        $where_clauses[] = "d.donation_type = ?";
        $params[] = $filter_type;
    }
    
    $where_sql = !empty($where_clauses) ? 'WHERE ' . implode(' AND ', $where_clauses) : '';
    
    $sql = "SELECT d.*, u.first_name, u.last_name as processor_name 
            FROM donations d 
            LEFT JOIN users u ON d.processed_by = u.id 
            $where_sql 
            ORDER BY d.created_at DESC";
    
    $donations = $db->select($sql, $params);
    
    // Get statistics
    $total_donations = $db->selectOne("SELECT COUNT(*) as count FROM donations")['count'];
    $pending_count = $db->selectOne("SELECT COUNT(*) as count FROM donations WHERE status = 'pending'")['count'];
    $completed_count = $db->selectOne("SELECT COUNT(*) as count FROM donations WHERE status = 'completed'")['count'];
    $total_amount = $db->selectOne("SELECT SUM(amount) as total FROM donations WHERE status = 'completed'")['total'] ?? 0;
    
} catch (Exception $e) {
    $donations = [];
    $total_donations = 0;
    $pending_count = 0;
    $completed_count = 0;
    $total_amount = 0;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Donations Management - Salem Dominion Ministries</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f8fafc;
        }
        
        .admin-header {
            background: linear-gradient(135deg, #dc2626 0%, #b91c1c 100%);
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
            color: #dc2626;
            margin-bottom: 10px;
        }
        
        .donation-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.08);
            padding: 25px;
            margin-bottom: 20px;
            border-left: 5px solid #dc2626;
        }
        
        .donation-card.pending {
            border-left-color: #f59e0b;
        }
        
        .donation-card.completed {
            border-left-color: #16a34a;
        }
        
        .donation-card.failed {
            border-left-color: #dc2626;
        }
        
        .donation-card.refunded {
            border-left-color: #6b7280;
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
        
        .status-completed {
            background: #dcfce7;
            color: #166534;
        }
        
        .status-failed {
            background: #fef2f2;
            color: #991b1b;
        }
        
        .status-refunded {
            background: #f3f4f6;
            color: #4b5563;
        }
        
        .btn-admin {
            background: linear-gradient(135deg, #dc2626 0%, #b91c1c 100%);
            border: none;
            border-radius: 10px;
            padding: 10px 20px;
            font-weight: 600;
            color: white;
            transition: all 0.3s ease;
        }
        
        .btn-admin:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(220, 38, 38, 0.3);
            color: white;
        }
        
        .filter-section {
            background: white;
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.08);
            padding: 25px;
            margin-bottom: 30px;
        }
        
        .donation-type-badge {
            background: #f3f4f6;
            padding: 4px 12px;
            border-radius: 15px;
            font-size: 0.8rem;
            display: inline-block;
            margin: 2px;
        }
        
        .payment-method-badge {
            background: #e0f2fe;
            color: #0c4a6e;
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
            background: linear-gradient(135deg, #dc2626 0%, #b91c1c 100%);
            color: white;
            border-radius: 15px 15px 0 0;
        }
        
        .transaction-id {
            background: #fef3c7;
            color: #92400e;
            padding: 4px 8px;
            border-radius: 8px;
            font-size: 0.8rem;
            font-family: monospace;
        }
        
        @media (max-width: 768px) {
            .stats-card {
                padding: 20px;
            }
            
            .stats-number {
                font-size: 2rem;
            }
            
            .donation-card {
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
            <h1><i class="fas fa-donate"></i> Donations Management</h1>
            <p class="lead">Track and manage all donation pledges - Perfect Database Integration</p>
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
                    <div class="stats-number"><?php echo $total_donations; ?></div>
                    <div class="text-muted">Total Donations</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stats-card">
                    <div class="stats-number"><?php echo $pending_count; ?></div>
                    <div class="text-muted">Pending Follow-up</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stats-card">
                    <div class="stats-number"><?php echo $completed_count; ?></div>
                    <div class="text-muted">Completed</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stats-card">
                    <div class="stats-number">$<?php echo number_format($total_amount, 0); ?></div>
                    <div class="text-muted">Total Raised</div>
                </div>
            </div>
        </div>
        
        <!-- Filters -->
        <div class="filter-section">
            <h5><i class="fas fa-filter"></i> Filter Donations</h5>
            <form method="GET" class="row g-3">
                <div class="col-md-4">
                    <label for="filter_status" class="form-label">Status</label>
                    <select class="form-select" id="filter_status" name="filter_status">
                        <option value="all" <?php echo $filter_status === 'all' ? 'selected' : ''; ?>>All Status</option>
                        <option value="pending" <?php echo $filter_status === 'pending' ? 'selected' : ''; ?>>Pending</option>
                        <option value="completed" <?php echo $filter_status === 'completed' ? 'selected' : ''; ?>>Completed</option>
                        <option value="failed" <?php echo $filter_status === 'failed' ? 'selected' : ''; ?>>Failed</option>
                        <option value="refunded" <?php echo $filter_status === 'refunded' ? 'selected' : ''; ?>>Refunded</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label for="filter_type" class="form-label">Donation Type</label>
                    <select class="form-select" id="filter_type" name="filter_type">
                        <option value="all" <?php echo $filter_type === 'all' ? 'selected' : ''; ?>>All Types</option>
                        <option value="tithe" <?php echo $filter_type === 'tithe' ? 'selected' : ''; ?>>Tithe</option>
                        <option value="offering" <?php echo $filter_type === 'offering' ? 'selected' : ''; ?>>Offering</option>
                        <option value="special" <?php echo $filter_type === 'special' ? 'selected' : ''; ?>>Special</option>
                        <option value="building_fund" <?php echo $filter_type === 'building_fund' ? 'selected' : ''; ?>>Building Fund</option>
                        <option value="missions" <?php echo $filter_type === 'missions' ? 'selected' : ''; ?>>Missions</option>
                        <option value="children_ministry" <?php echo $filter_type === 'children_ministry' ? 'selected' : ''; ?>>Children Ministry</option>
                        <option value="other" <?php echo $filter_type === 'other' ? 'selected' : ''; ?>>Other</option>
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
        
        <!-- Donations List -->
        <?php if (empty($donations)): ?>
            <div class="text-center py-5">
                <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                <h4 class="text-muted">No donations found</h4>
                <p class="text-muted">No donations match the current filters.</p>
            </div>
        <?php else: ?>
            <?php foreach ($donations as $donation): ?>
                <div class="donation-card <?php echo $donation['status']; ?>">
                    <div class="row">
                        <div class="col-md-8">
                            <div class="d-flex justify-content-between align-items-start mb-3">
                                <div>
                                    <h5><?php echo htmlspecialchars($donation['donor_name']); ?></h5>
                                    <div class="mb-2">
                                        <span class="status-badge status-<?php echo $donation['status']; ?>">
                                            <?php echo ucfirst($donation['status']); ?>
                                        </span>
                                        <span class="donation-type-badge">
                                            <?php echo ucfirst(str_replace('_', ' ', $donation['donation_type'])); ?>
                                        </span>
                                        <span class="payment-method-badge">
                                            <?php echo ucfirst(str_replace('_', ' ', $donation['payment_method'])); ?>
                                        </span>
                                        <?php if ($donation['transaction_id']): ?>
                                            <span class="transaction-id">
                                                <i class="fas fa-receipt"></i> <?php echo htmlspecialchars($donation['transaction_id']); ?>
                                            </span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <div class="text-end">
                                    <h4 class="text-success mb-0">$<?php echo number_format($donation['amount'], 2); ?></h4>
                                    <small class="text-muted">
                                        <?php echo date('M j, Y g:i A', strtotime($donation['created_at'])); ?>
                                    </small>
                                </div>
                            </div>
                            
                            <div class="contact-info">
                                <div class="row">
                                    <div class="col-md-4">
                                        <i class="fas fa-envelope"></i> <?php echo htmlspecialchars($donation['donor_email']); ?>
                                    </div>
                                    <?php if ($donation['donor_phone']): ?>
                                        <div class="col-md-4">
                                            <i class="fas fa-phone"></i> <?php echo htmlspecialchars($donation['donor_phone']); ?>
                                        </div>
                                    <?php endif; ?>
                                    <?php if ($donation['purpose']): ?>
                                        <div class="col-md-4">
                                            <i class="fas fa-tag"></i> <?php echo htmlspecialchars($donation['purpose']); ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                            
                            <?php if ($donation['is_recurring']): ?>
                                <div class="mt-2">
                                    <span class="badge bg-info">
                                        <i class="fas fa-sync"></i> Recurring: <?php echo ucfirst($donation['recurring_frequency']); ?>
                                    </span>
                                </div>
                            <?php endif; ?>
                            
                            <?php if ($donation['notes']): ?>
                                <div class="mt-3">
                                    <small class="text-muted">
                                        <strong>Notes:</strong> <?php echo htmlspecialchars($donation['notes']); ?>
                                    </small>
                                </div>
                            <?php endif; ?>
                            
                            <?php if ($donation['processor_name']): ?>
                                <div class="mt-2">
                                    <small class="text-muted">
                                        <i class="fas fa-user-check"></i> Processed by: <?php echo htmlspecialchars($donation['processor_name']); ?>
                                    </small>
                                </div>
                            <?php endif; ?>
                        </div>
                        
                        <div class="col-md-4">
                            <div class="text-end">
                                <?php if ($donation['status'] === 'pending'): ?>
                                    <button class="btn btn-success btn-sm mb-2" onclick="updateStatus(<?php echo $donation['id']; ?>, 'completed')">
                                        <i class="fas fa-check"></i> Mark Complete
                                    </button>
                                    <button class="btn btn-warning btn-sm mb-2" onclick="updateStatus(<?php echo $donation['id']; ?>, 'failed')">
                                        <i class="fas fa-times"></i> Mark Failed
                                    </button>
                                <?php endif; ?>
                                
                                <button class="btn btn-outline-primary btn-sm mb-2" onclick="addNotes(<?php echo $donation['id']; ?>, '<?php echo htmlspecialchars($donation['notes'] ?? ''); ?>')">
                                    <i class="fas fa-edit"></i> Add Notes
                                </button>
                                
                                <button class="btn btn-outline-info btn-sm" onclick="contactDonor('<?php echo htmlspecialchars($donation['donor_email']); ?>', '<?php echo htmlspecialchars($donation['donor_phone'] ?? ''); ?>')">
                                    <i class="fas fa-phone"></i> Contact
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
                    <h5 class="modal-title">Update Donation Status</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form method="POST" action="admin_donations_perfect.php">
                        <input type="hidden" name="action" value="update_status">
                        <input type="hidden" id="modal_donation_id" name="donation_id">
                        
                        <div class="mb-3">
                            <label for="modal_status" class="form-label">New Status</label>
                            <select class="form-select" id="modal_status" name="status">
                                <option value="pending">Pending</option>
                                <option value="completed">Completed</option>
                                <option value="failed">Failed</option>
                                <option value="refunded">Refunded</option>
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
                    <h5 class="modal-title">Add Notes</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form method="POST" action="admin_donations_perfect.php">
                        <input type="hidden" name="action" value="add_note">
                        <input type="hidden" id="notes_donation_id" name="donation_id">
                        
                        <div class="mb-3">
                            <label for="modal_notes" class="form-label">Notes</label>
                            <textarea class="form-control" id="modal_notes" name="notes" rows="4" placeholder="Add follow-up notes, payment details, etc."></textarea>
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
        // Update donation status
        function updateStatus(donationId, currentStatus) {
            document.getElementById('modal_donation_id').value = donationId;
            document.getElementById('modal_status').value = currentStatus;
            
            const modal = new bootstrap.Modal(document.getElementById('statusModal'));
            modal.show();
        }
        
        // Add notes
        function addNotes(donationId, currentNotes) {
            document.getElementById('notes_donation_id').value = donationId;
            document.getElementById('modal_notes').value = currentNotes;
            
            const modal = new bootstrap.Modal(document.getElementById('notesModal'));
            modal.show();
        }
        
        // Contact donor
        function contactDonor(email, phone) {
            let message = 'Contact Information:\n\n';
            message += 'Email: ' + email + '\n';
            if (phone) {
                message += 'Phone: ' + phone + '\n';
            }
            message += '\nClick OK to copy email to clipboard.';
            
            if (confirm(message)) {
                navigator.clipboard.writeText(email);
                alert('Email copied to clipboard!');
            }
        }
    </script>
</body>
</html>
