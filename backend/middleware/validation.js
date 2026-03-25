const { body, validationResult } = require('express-validator');

// Validation middleware handler
const handleValidationErrors = (req, res, next) => {
  const errors = validationResult(req);
  if (!errors.isEmpty()) {
    return res.status(400).json({
      success: false,
      message: 'Validation failed',
      errors: errors.array()
    });
  }
  next();
};

// User registration validation
const validateUserRegistration = [
  body('first_name')
    .trim()
    .isLength({ min: 2, max: 100 })
    .withMessage('First name must be between 2 and 100 characters')
    .matches(/^[a-zA-Z\s]+$/)
    .withMessage('First name can only contain letters and spaces'),
  
  body('last_name')
    .trim()
    .isLength({ min: 2, max: 100 })
    .withMessage('Last name must be between 2 and 100 characters')
    .matches(/^[a-zA-Z\s]+$/)
    .withMessage('Last name can only contain letters and spaces'),
  
  body('email')
    .trim()
    .isEmail()
    .withMessage('Please provide a valid email address')
    .normalizeEmail(),
  
  body('phone')
    .optional()
    .trim()
    .matches(/^[\+]?[1-9][\d]{0,15}$/)
    .withMessage('Please provide a valid phone number'),
  
  body('password')
    .isLength({ min: 8 })
    .withMessage('Password must be at least 8 characters long')
    .matches(/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)/)
    .withMessage('Password must contain at least one lowercase letter, one uppercase letter, and one number'),
  
  handleValidationErrors
];

// User login validation
const validateUserLogin = [
  body('email')
    .trim()
    .isEmail()
    .withMessage('Please provide a valid email address')
    .normalizeEmail(),
  
  body('password')
    .notEmpty()
    .withMessage('Password is required'),
  
  handleValidationErrors
];

// Event validation
const validateEvent = [
  body('title')
    .trim()
    .isLength({ min: 3, max: 255 })
    .withMessage('Event title must be between 3 and 255 characters'),
  
  body('description')
    .optional()
    .trim()
    .isLength({ max: 2000 })
    .withMessage('Description must not exceed 2000 characters'),
  
  body('event_date')
    .isISO8601()
    .withMessage('Please provide a valid event date'),
  
  body('end_date')
    .optional()
    .isISO8601()
    .withMessage('Please provide a valid end date'),
  
  body('location')
    .optional()
    .trim()
    .isLength({ max: 255 })
    .withMessage('Location must not exceed 255 characters'),
  
  body('event_type')
    .isIn(['service', 'meeting', 'conference', 'fellowship', 'outreach', 'other'])
    .withMessage('Invalid event type'),
  
  body('max_attendees')
    .optional()
    .isInt({ min: 1 })
    .withMessage('Max attendees must be a positive integer'),
  
  handleValidationErrors
];

// Sermon validation
const validateSermon = [
  body('title')
    .trim()
    .isLength({ min: 3, max: 255 })
    .withMessage('Sermon title must be between 3 and 255 characters'),
  
  body('preacher')
    .optional()
    .trim()
    .isLength({ max: 255 })
    .withMessage('Preacher name must not exceed 255 characters'),
  
  body('bible_reference')
    .optional()
    .trim()
    .isLength({ max: 100 })
    .withMessage('Bible reference must not exceed 100 characters'),
  
  body('sermon_date')
    .isISO8601()
    .withMessage('Please provide a valid sermon date'),
  
  body('description')
    .optional()
    .trim()
    .isLength({ max: 2000 })
    .withMessage('Description must not exceed 2000 characters'),
  
  handleValidationErrors
];

// Prayer request validation
const validatePrayerRequest = [
  body('requester_name')
    .trim()
    .isLength({ min: 2, max: 255 })
    .withMessage('Name must be between 2 and 255 characters'),
  
  body('requester_email')
    .optional()
    .trim()
    .isEmail()
    .withMessage('Please provide a valid email address')
    .normalizeEmail(),
  
  body('title')
    .trim()
    .isLength({ min: 3, max: 255 })
    .withMessage('Prayer title must be between 3 and 255 characters'),
  
  body('description')
    .trim()
    .isLength({ min: 10, max: 2000 })
    .withMessage('Prayer description must be between 10 and 2000 characters'),
  
  body('request_type')
    .isIn(['general', 'healing', 'guidance', 'protection', 'thanksgiving', 'other'])
    .withMessage('Invalid prayer request type'),
  
  handleValidationErrors
];

// Contact message validation
const validateContactMessage = [
  body('name')
    .trim()
    .isLength({ min: 2, max: 255 })
    .withMessage('Name must be between 2 and 255 characters'),
  
  body('email')
    .trim()
    .isEmail()
    .withMessage('Please provide a valid email address')
    .normalizeEmail(),
  
  body('subject')
    .trim()
    .isLength({ min: 3, max: 255 })
    .withMessage('Subject must be between 3 and 255 characters'),
  
  body('message')
    .trim()
    .isLength({ min: 10, max: 2000 })
    .withMessage('Message must be between 10 and 2000 characters'),
  
  body('message_type')
    .optional()
    .isIn(['general', 'prayer', 'testimony', 'feedback', 'complaint', 'other'])
    .withMessage('Invalid message type'),
  
  handleValidationErrors
];

// Donation validation
const validateDonation = [
  body('donor_name')
    .trim()
    .isLength({ min: 2, max: 255 })
    .withMessage('Donor name must be between 2 and 255 characters'),
  
  body('donor_email')
    .optional()
    .trim()
    .isEmail()
    .withMessage('Please provide a valid email address')
    .normalizeEmail(),
  
  body('amount')
    .isFloat({ min: 0.01 })
    .withMessage('Amount must be a positive number'),
  
  body('donation_type')
    .isIn(['tithe', 'offering', 'special', 'building_fund', 'missions', 'other'])
    .withMessage('Invalid donation type'),
  
  body('payment_method')
    .isIn(['cash', 'bank_transfer', 'credit_card', 'mobile_money', 'online'])
    .withMessage('Invalid payment method'),
  
  handleValidationErrors
];

module.exports = {
  validateUserRegistration,
  validateUserLogin,
  validateEvent,
  validateSermon,
  validatePrayerRequest,
  validateContactMessage,
  validateDonation
};
