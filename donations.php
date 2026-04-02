<?php
require_once 'config.php';
require_once 'db.php';

$errors = [];
$success = '';

// Handle donation form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    $action = $_POST['action'];
    
    if ($action === 'submit_donation') {
        // Collect and validate form data
        $donor_name = trim($_POST['donor_name'] ?? '');
        $donor_email = trim($_POST['donor_email'] ?? '');
        $donor_phone = trim($_POST['donor_phone'] ?? '');
        $amount = floatval($_POST['amount'] ?? 0);
        $donation_type = $_POST['donation_type'] ?? 'offering';
        $payment_method = $_POST['payment_method'] ?? 'cash';
        $purpose = trim($_POST['purpose'] ?? '');
        $notes = trim($_POST['notes'] ?? '');
        $is_recurring = isset($_POST['is_recurring']) ? 1 : 0;
        $recurring_frequency = $_POST['recurring_frequency'] ?? 'monthly';
        $anonymous = isset($_POST['anonymous']) ? 1 : 0;
        
        // Validation
        if (empty($donor_name)) {
            $errors[] = 'Donor name is required.';
        }
        
        if (empty($donor_email) || !filter_var($donor_email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'Valid email address is required.';
        }
        
        if ($amount <= 0) {
            $errors[] = 'Donation amount must be greater than 0.';
        }
        
        if (empty($errors)) {
            try {
                // Insert donation into database - matching exact table structure
                $stmt = $conn->prepare("INSERT INTO donations (donor_name, donor_email, donor_phone, amount, donation_type, payment_method, transaction_id, is_recurring, recurring_frequency, purpose, notes, status, created_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'pending', NOW())");
                
                $display_name = $anonymous ? 'Anonymous Donor' : $donor_name;
                $transaction_id = 'TXN' . date('Y') . strtoupper(substr(md5(uniqid()), 0, 8));
                
                $stmt->bind_param('sssdsissssss', $display_name, $donor_email, $donor_phone, $amount, $donation_type, $payment_method, $transaction_id, $is_recurring, $recurring_frequency, $purpose, $notes);
                
                if ($stmt->execute()) {
                    $success = 'Thank you for your donation pledge! We will contact you soon to arrange payment details. Your reference number is: ' . $transaction_id;
                    
                    // Send notification email to church admin (you can implement email sending later)
                    // For now, we'll just log it
                    error_log("New donation pledge: $donor_name - $amount - $donor_email - Ref: $transaction_id");
                    
                } else {
                    $errors[] = 'Failed to submit donation. Please try again.';
                }
                $stmt->close();
                
            } catch (Exception $e) {
                $errors[] = 'Database error: ' . $e->getMessage();
            }
        }
    }
}

// Get donation statistics
try {
    $total_donations = $db->selectOne("SELECT COUNT(*) as count FROM donations WHERE status = 'completed'")['count'];
    $total_amount = $db->selectOne("SELECT SUM(amount) as total FROM donations WHERE status = 'completed'")['total'] ?? 0;
    $pending_donations = $db->selectOne("SELECT COUNT(*) as count FROM donations WHERE status = 'pending'")['count'];
    
} catch (Exception $e) {
    $total_donations = 0;
    $total_amount = 0;
    $pending_donations = 0;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Give - Salem Dominion Ministries</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
        }
        
        .donation-header {
            background: linear-gradient(135deg, #16a34a 0%, #15803d 100%);
            color: white;
            padding: 80px 0 60px;
            text-align: center;
            position: relative;
            overflow: hidden;
        }
        
        .donation-header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1440 320'%3E%3Cpath fill='%23ffffff' fill-opacity='0.1' d='M0,96L48,112C96,128,192,160,288,160C384,160,480,128,576,122.7C672,117,768,139,864,138.7C960,139,1056,117,1152,96C1248,75,1344,53,1392,42.7L1440,32L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z'%3E%3C/path%3E%3C/svg%3E") no-repeat;
            background-size: cover;
            opacity: 0.3;
        }
        
        .donation-header h1 {
            font-size: 3rem;
            font-weight: bold;
            margin-bottom: 20px;
            position: relative;
            z-index: 1;
        }
        
        .donation-header p {
            font-size: 1.2rem;
            margin-bottom: 30px;
            position: relative;
            z-index: 1;
        }
        
        .donation-form {
            background: white;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            padding: 40px;
            margin: -30px auto 50px;
            max-width: 800px;
            position: relative;
            z-index: 10;
        }
        
        .form-control, .form-select {
            border-radius: 10px;
            border: 2px solid #e5e7eb;
            padding: 12px 20px;
            margin-bottom: 20px;
        }
        
        .form-control:focus, .form-select:focus {
            border-color: #16a34a;
            box-shadow: 0 0 0 0.2rem rgba(22, 163, 74, 0.25);
        }
        
        .btn-donate {
            background: linear-gradient(135deg, #16a34a 0%, #15803d 100%);
            border: none;
            border-radius: 10px;
            padding: 12px 30px;
            font-weight: 600;
            color: white;
            transition: all 0.3s ease;
        }
        
        .btn-donate:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(22, 163, 74, 0.3);
            color: white;
        }
        
        .stats-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.08);
            padding: 30px;
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
            color: #16a34a;
            margin-bottom: 10px;
        }
        
        .donation-type-card {
            background: #f8fafc;
            border: 2px solid #e5e7eb;
            border-radius: 15px;
            padding: 25px;
            text-align: center;
            margin-bottom: 20px;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .donation-type-card:hover, .donation-type-card.selected {
            border-color: #16a34a;
            background: linear-gradient(135deg, #f0fdf4 0%, #dcfce7 100%);
            transform: translateY(-3px);
        }
        
        .donation-type-card i {
            font-size: 2.5rem;
            color: #16a34a;
            margin-bottom: 15px;
        }
        
        .amount-suggestion {
            display: inline-block;
            background: #f8fafc;
            border: 2px solid #e5e7eb;
            border-radius: 8px;
            padding: 10px 20px;
            margin: 5px;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .amount-suggestion:hover, .amount-suggestion.selected {
            border-color: #16a34a;
            background: #16a34a;
            color: white;
        }
        
        .alert {
            border-radius: 10px;
            border: none;
            margin-bottom: 20px;
        }
        
        .payment-method {
            display: flex;
            align-items: center;
            padding: 15px;
            background: #f8fafc;
            border: 2px solid #e5e7eb;
            border-radius: 10px;
            margin-bottom: 15px;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .payment-method:hover, .payment-method.selected {
            border-color: #16a34a;
            background: #f0fdf4;
        }
        
        .payment-method i {
            font-size: 1.5rem;
            color: #16a34a;
            margin-right: 15px;
        }
        
        .info-box {
            background: linear-gradient(135deg, #f0fdf4 0%, #dcfce7 100%);
            border-left: 4px solid #16a34a;
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 30px;
        }
        
        @media (max-width: 768px) {
            .donation-header h1 {
                font-size: 2rem;
            }
            
            .donation-form {
                padding: 30px 20px;
                margin: -20px 15px 30px;
            }
            
            .stats-card {
                padding: 20px;
            }
            
            .stats-number {
                font-size: 2rem;
            }
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="index.php">
                <i class="fas fa-church"></i> Salem Dominion Ministries
            </a>
            <div class="navbar-nav ms-auto">
                <a class="nav-link" href="index.php">
                    <i class="fas fa-home"></i> Home
                </a>
                <a class="nav-link active" href="donations.php">
                    <i class="fas fa-donate"></i> Give
                </a>
                <a class="nav-link" href="gallery.php">
                    <i class="fas fa-images"></i> Gallery
                </a>
                <a class="nav-link" href="events.php">
                    <i class="fas fa-calendar"></i> Events
                </a>
                <a class="nav-link" href="contact.php">
                    <i class="fas fa-envelope"></i> Contact
                </a>
            </div>
        </div>
    </nav>

    <!-- Donation Header -->
    <div class="donation-header">
        <div class="container">
            <h1><i class="fas fa-heart"></i> Give to God's Work</h1>
            <p>Your generous donations help us spread the Gospel and serve our community</p>
            <div class="row justify-content-center">
                <div class="col-md-3 col-6 mb-3">
                    <div class="stats-card">
                        <div class="stats-number"><?php echo number_format($total_donations); ?></div>
                        <div class="text-white">Total Donors</div>
                    </div>
                </div>
                <div class="col-md-3 col-6 mb-3">
                    <div class="stats-card">
                        <div class="stats-number">$<?php echo number_format($total_amount, 0); ?></div>
                        <div class="text-white">Total Raised</div>
                    </div>
                </div>
                <div class="col-md-3 col-6 mb-3">
                    <div class="stats-card">
                        <div class="stats-number"><?php echo $pending_donations; ?></div>
                        <div class="text-white">Pending</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Donation Form -->
    <div class="container">
        <div class="donation-form">
            <h2 class="text-center mb-4">Make Your Donation Pledge</h2>
            
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
            
            <div class="info-box">
                <h5><i class="fas fa-info-circle"></i> How It Works</h5>
                <p class="mb-0">Since we don't have online payment processing yet, your donation pledge will be recorded in our system. Our church administrator will contact you within 24 hours to arrange payment details (bank transfer, mobile money, cash, etc.).</p>
            </div>
            
            <form method="POST" action="donations.php">
                <input type="hidden" name="action" value="submit_donation">
                
                <!-- Personal Information -->
                <h4 class="mb-3"><i class="fas fa-user"></i> Personal Information</h4>
                <div class="row">
                    <div class="col-md-6">
                        <label for="donor_name" class="form-label">Full Name *</label>
                        <input type="text" class="form-control" id="donor_name" name="donor_name" required
                               value="<?php echo htmlspecialchars($_POST['donor_name'] ?? ''); ?>">
                    </div>
                    <div class="col-md-6">
                        <label for="donor_email" class="form-label">Email Address *</label>
                        <input type="email" class="form-control" id="donor_email" name="donor_email" required
                               value="<?php echo htmlspecialchars($_POST['donor_email'] ?? ''); ?>">
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-6">
                        <label for="donor_phone" class="form-label">Phone Number</label>
                        <input type="tel" class="form-control" id="donor_phone" name="donor_phone"
                               value="<?php echo htmlspecialchars($_POST['donor_phone'] ?? ''); ?>">
                    </div>
                    <div class="col-md-6">
                        <div class="form-check mt-4">
                            <input class="form-check-input" type="checkbox" id="anonymous" name="anonymous">
                            <label class="form-check-label" for="anonymous">
                                <i class="fas fa-user-secret"></i> Donate anonymously
                            </label>
                        </div>
                    </div>
                </div>
                
                <!-- Donation Type -->
                <h4 class="mb-3 mt-4"><i class="fas fa-hand-holding-heart"></i> Donation Type</h4>
                <div class="row">
                    <div class="col-md-4">
                        <div class="donation-type-card" onclick="selectDonationType('tithe')">
                            <i class="fas fa-percentage"></i>
                            <h6>Tithe</h6>
                            <small class="text-muted">10% of your income</small>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="donation-type-card" onclick="selectDonationType('offering')">
                            <i class="fas fa-gift"></i>
                            <h6>Offering</h6>
                            <small class="text-muted">General offering</small>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="donation-type-card" onclick="selectDonationType('special')">
                            <i class="fas fa-star"></i>
                            <h6>Special</h6>
                            <small class="text-muted">Special donation</small>
                        </div>
                    </div>
                </div>
                
                <div class="row mt-3">
                    <div class="col-md-4">
                        <div class="donation-type-card" onclick="selectDonationType('building_fund')">
                            <i class="fas fa-church"></i>
                            <h6>Building Fund</h6>
                            <small class="text-muted">Church construction</small>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="donation-type-card" onclick="selectDonationType('missions')">
                            <i class="fas fa-globe"></i>
                            <h6>Missions</h6>
                            <small class="text-muted">Outreach programs</small>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="donation-type-card" onclick="selectDonationType('children_ministry')">
                            <i class="fas fa-child"></i>
                            <h6>Children Ministry</h6>
                            <small class="text-muted">Kids programs</small>
                        </div>
                    </div>
                </div>
                
                <input type="hidden" id="donation_type" name="donation_type" value="offering">
                
                <!-- Amount -->
                <h4 class="mb-3 mt-4"><i class="fas fa-dollar-sign"></i> Donation Amount</h4>
                <div class="text-center mb-3">
                    <span class="amount-suggestion" onclick="setAmount(25)">$25</span>
                    <span class="amount-suggestion" onclick="setAmount(50)">$50</span>
                    <span class="amount-suggestion" onclick="setAmount(100)">$100</span>
                    <span class="amount-suggestion" onclick="setAmount(250)">$250</span>
                    <span class="amount-suggestion" onclick="setAmount(500)">$500</span>
                    <span class="amount-suggestion" onclick="setAmount(1000)">$1000</span>
                </div>
                
                <div class="row">
                    <div class="col-md-6">
                        <label for="amount" class="form-label">Custom Amount ($) *</label>
                        <input type="number" class="form-control" id="amount" name="amount" min="1" step="0.01" required
                               value="<?php echo htmlspecialchars($_POST['amount'] ?? ''); ?>">
                    </div>
                    <div class="col-md-6">
                        <label for="purpose" class="form-label">Purpose (Optional)</label>
                        <input type="text" class="form-control" id="purpose" name="purpose" placeholder="e.g., Building fund, missions"
                               value="<?php echo htmlspecialchars($_POST['purpose'] ?? ''); ?>">
                    </div>
                </div>
                
                <!-- Recurring Donation -->
                <div class="form-check mt-3">
                    <input class="form-check-input" type="checkbox" id="is_recurring" name="is_recurring">
                    <label class="form-check-label" for="is_recurring">
                        <i class="fas fa-sync"></i> Make this a recurring donation
                    </label>
                </div>
                
                <div class="row mt-3" id="recurring_options" style="display: none;">
                    <div class="col-md-6">
                        <label for="recurring_frequency" class="form-label">Frequency</label>
                        <select class="form-select" id="recurring_frequency" name="recurring_frequency">
                            <option value="weekly">Weekly</option>
                            <option value="monthly" selected>Monthly</option>
                            <option value="quarterly">Quarterly</option>
                            <option value="yearly">Yearly</option>
                        </select>
                    </div>
                </div>
                
                <!-- Payment Method -->
                <h4 class="mb-3 mt-4"><i class="fas fa-credit-card"></i> Preferred Payment Method</h4>
                <div class="payment-method" onclick="selectPaymentMethod('cash')">
                    <i class="fas fa-money-bill-wave"></i>
                    <div>
                        <h6 class="mb-0">Cash</h6>
                        <small class="text-muted">Pay in person at church</small>
                    </div>
                </div>
                
                <div class="payment-method" onclick="selectPaymentMethod('bank_transfer')">
                    <i class="fas fa-university"></i>
                    <div>
                        <h6 class="mb-0">Bank Transfer</h6>
                        <small class="text-muted">Direct bank deposit</small>
                    </div>
                </div>
                
                <div class="payment-method" onclick="selectPaymentMethod('mobile_money')">
                    <i class="fas fa-mobile-alt"></i>
                    <div>
                        <h6 class="mb-0">Mobile Money</h6>
                        <small class="text-muted">Mobile money transfer</small>
                    </div>
                </div>
                
                <input type="hidden" id="payment_method" name="payment_method" value="cash">
                
                <!-- Additional Notes -->
                <h4 class="mb-3 mt-4"><i class="fas fa-comment"></i> Additional Notes</h4>
                <textarea class="form-control" id="notes" name="notes" rows="3" placeholder="Any special instructions or prayer requests..."><?php echo htmlspecialchars($_POST['notes'] ?? ''); ?></textarea>
                
                <div class="text-center mt-4">
                    <button type="submit" class="btn btn-donate btn-lg">
                        <i class="fas fa-heart"></i> Submit Donation Pledge
                    </button>
                </div>
            </form>
        </div>
    </div>
    
    <!-- Include Footer -->
    <?php include 'components/ultimate_footer_new.php'; ?>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Donation type selection
        function selectDonationType(type) {
            document.querySelectorAll('.donation-type-card').forEach(card => {
                card.classList.remove('selected');
            });
            event.currentTarget.classList.add('selected');
            document.getElementById('donation_type').value = type;
        }
        
        // Amount selection
        function setAmount(amount) {
            document.getElementById('amount').value = amount;
            document.querySelectorAll('.amount-suggestion').forEach(btn => {
                btn.classList.remove('selected');
            });
            event.currentTarget.classList.add('selected');
        }
        
        // Payment method selection
        function selectPaymentMethod(method) {
            document.querySelectorAll('.payment-method').forEach(card => {
                card.classList.remove('selected');
            });
            event.currentTarget.classList.add('selected');
            document.getElementById('payment_method').value = method;
        }
        
        // Recurring donation toggle
        document.getElementById('is_recurring').addEventListener('change', function() {
            const recurringOptions = document.getElementById('recurring_options');
            recurringOptions.style.display = this.checked ? 'block' : 'none';
        });
        
        // Set default selections
        document.addEventListener('DOMContentLoaded', function() {
            // Select default donation type
            document.querySelector('.donation-type-card').classList.add('selected');
            
            // Select default payment method
            document.querySelector('.payment-method').classList.add('selected');
        });
    </script>
</body>
</html>
