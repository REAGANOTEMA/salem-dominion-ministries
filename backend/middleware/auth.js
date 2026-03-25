const jwt = require('jsonwebtoken');
const { query } = require('../config/database');

// Authentication middleware
const authenticate = async (req, res, next) => {
  try {
    const token = req.header('Authorization')?.replace('Bearer ', '');
    
    if (!token) {
      return res.status(401).json({
        success: false,
        message: 'Access denied. No token provided.'
      });
    }

    const decoded = jwt.verify(token, process.env.JWT_SECRET);
    
    // Get user from database
    const userResult = await query(
      'SELECT id, first_name, last_name, email, role, is_active FROM users WHERE id = ?',
      [decoded.userId]
    );

    if (!userResult.success || userResult.data.length === 0) {
      return res.status(401).json({
        success: false,
        message: 'Invalid token. User not found.'
      });
    }

    const user = userResult.data[0];
    
    if (!user.is_active) {
      return res.status(401).json({
        success: false,
        message: 'Account is deactivated.'
      });
    }

    req.user = user;
    next();
  } catch (error) {
    if (error.name === 'JsonWebTokenError') {
      return res.status(401).json({
        success: false,
        message: 'Invalid token.'
      });
    }
    
    if (error.name === 'TokenExpiredError') {
      return res.status(401).json({
        success: false,
        message: 'Token expired.'
      });
    }

    console.error('Auth middleware error:', error);
    res.status(500).json({
      success: false,
      message: 'Server error in authentication.'
    });
  }
};

// Role-based access control
const authorize = (...roles) => {
  return (req, res, next) => {
    if (!req.user) {
      return res.status(401).json({
        success: false,
        message: 'Access denied. Authentication required.'
      });
    }

    if (!roles.includes(req.user.role)) {
      return res.status(403).json({
        success: false,
        message: 'Access denied. Insufficient permissions.'
      });
    }

    next();
  };
};

// Optional authentication (doesn't fail if no token)
const optionalAuth = async (req, res, next) => {
  try {
    const token = req.header('Authorization')?.replace('Bearer ', '');
    
    if (token) {
      const decoded = jwt.verify(token, process.env.JWT_SECRET);
      
      const userResult = await query(
        'SELECT id, first_name, last_name, email, role, is_active FROM users WHERE id = ?',
        [decoded.userId]
      );

      if (userResult.success && userResult.data.length > 0) {
        const user = userResult.data[0];
        if (user.is_active) {
          req.user = user;
        }
      }
    }
    
    next();
  } catch (error) {
    // Optional auth should not fail the request
    next();
  }
};

module.exports = {
  authenticate,
  authorize,
  optionalAuth
};
