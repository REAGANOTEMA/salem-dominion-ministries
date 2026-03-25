const express = require('express');
const bcrypt = require('bcryptjs');
const { query } = require('../config/database');
const { authenticate, authorize } = require('../middleware/auth');

const router = express.Router();

// Get user profile
router.get('/profile', authenticate, async (req, res) => {
  try {
    const userResult = await query(
      'SELECT id, first_name, last_name, email, phone, role, avatar_url, created_at FROM users WHERE id = ?',
      [req.user.id]
    );

    if (!userResult.success) {
      return res.status(500).json({
        success: false,
        message: 'Failed to fetch user profile'
      });
    }

    if (userResult.data.length === 0) {
      return res.status(404).json({
        success: false,
        message: 'User not found'
      });
    }

    res.json({
      success: true,
      data: { user: userResult.data[0] }
    });

  } catch (error) {
    console.error('Get profile error:', error);
    res.status(500).json({
      success: false,
      message: 'Server error fetching profile'
    });
  }
});

// Update user profile
router.put('/profile', authenticate, async (req, res) => {
  try {
    const { first_name, last_name, phone, avatar_url } = req.body;

    const result = await query(
      'UPDATE users SET first_name = ?, last_name = ?, phone = ?, avatar_url = ? WHERE id = ?',
      [first_name, last_name, phone, avatar_url, req.user.id]
    );

    if (!result.success) {
      return res.status(500).json({
        success: false,
        message: 'Failed to update profile'
      });
    }

    // Get updated profile
    const updatedProfile = await query(
      'SELECT id, first_name, last_name, email, phone, role, avatar_url FROM users WHERE id = ?',
      [req.user.id]
    );

    res.json({
      success: true,
      message: 'Profile updated successfully',
      data: { user: updatedProfile.data[0] }
    });

  } catch (error) {
    console.error('Update profile error:', error);
    res.status(500).json({
      success: false,
      message: 'Server error updating profile'
    });
  }
});

// Change password
router.put('/password', authenticate, async (req, res) => {
  try {
    const { current_password, new_password } = req.body;

    if (!current_password || !new_password) {
      return res.status(400).json({
        success: false,
        message: 'Current password and new password are required'
      });
    }

    // Get current password hash
    const userResult = await query(
      'SELECT password_hash FROM users WHERE id = ?',
      [req.user.id]
    );

    if (!userResult.success || userResult.data.length === 0) {
      return res.status(404).json({
        success: false,
        message: 'User not found'
      });
    }

    // Verify current password
    const isCurrentPasswordValid = await bcrypt.compare(current_password, userResult.data[0].password_hash);

    if (!isCurrentPasswordValid) {
      return res.status(400).json({
        success: false,
        message: 'Current password is incorrect'
      });
    }

    // Hash new password
    const saltRounds = 12;
    const newPasswordHash = await bcrypt.hash(new_password, saltRounds);

    // Update password
    const result = await query(
      'UPDATE users SET password_hash = ? WHERE id = ?',
      [newPasswordHash, req.user.id]
    );

    if (!result.success) {
      return res.status(500).json({
        success: false,
        message: 'Failed to update password'
      });
    }

    res.json({
      success: true,
      message: 'Password updated successfully'
    });

  } catch (error) {
    console.error('Change password error:', error);
    res.status(500).json({
      success: false,
      message: 'Server error changing password'
    });
  }
});

// Get all users (admin only)
router.get('/', authenticate, authorize('admin'), async (req, res) => {
  try {
    const { page = 1, limit = 20, role, search } = req.query;
    const offset = (page - 1) * limit;
    let whereClause = 'WHERE 1=1';
    let params = [];

    if (role) {
      whereClause += ' AND role = ?';
      params.push(role);
    }

    if (search) {
      whereClause += ' AND (first_name LIKE ? OR last_name LIKE ? OR email LIKE ?)';
      params.push(`%${search}%`, `%${search}%`, `%${search}%`);
    }

    const usersQuery = `
      SELECT id, first_name, last_name, email, phone, role, is_active, email_verified, created_at
      FROM users 
      ${whereClause}
      ORDER BY created_at DESC
      LIMIT ? OFFSET ?
    `;

    const usersResult = await query(usersQuery, [...params, parseInt(limit), offset]);

    const countResult = await query(
      `SELECT COUNT(*) as total FROM users ${whereClause}`,
      params
    );

    if (!usersResult.success || !countResult.success) {
      return res.status(500).json({
        success: false,
        message: 'Failed to fetch users'
      });
    }

    res.json({
      success: true,
      data: {
        users: usersResult.data,
        pagination: {
          current_page: parseInt(page),
          per_page: parseInt(limit),
          total: countResult.data[0].total,
          total_pages: Math.ceil(countResult.data[0].total / limit)
        }
      }
    });

  } catch (error) {
    console.error('Get users error:', error);
    res.status(500).json({
      success: false,
      message: 'Server error fetching users'
    });
  }
});

// Update user role (admin only)
router.put('/:id/role', authenticate, authorize('admin'), async (req, res) => {
  try {
    const { id } = req.params;
    const { role } = req.body;

    if (!['admin', 'pastor', 'member', 'visitor'].includes(role)) {
      return res.status(400).json({
        success: false,
        message: 'Invalid role'
      });
    }

    const result = await query(
      'UPDATE users SET role = ? WHERE id = ?',
      [role, id]
    );

    if (!result.success) {
      return res.status(500).json({
        success: false,
        message: 'Failed to update user role'
      });
    }

    res.json({
      success: true,
      message: 'User role updated successfully'
    });

  } catch (error) {
    console.error('Update user role error:', error);
    res.status(500).json({
      success: false,
      message: 'Server error updating user role'
    });
  }
});

// Deactivate/activate user (admin only)
router.put('/:id/status', authenticate, authorize('admin'), async (req, res) => {
  try {
    const { id } = req.params;
    const { is_active } = req.body;

    const result = await query(
      'UPDATE users SET is_active = ? WHERE id = ?',
      [is_active, id]
    );

    if (!result.success) {
      return res.status(500).json({
        success: false,
        message: 'Failed to update user status'
      });
    }

    res.json({
      success: true,
      message: `User ${is_active ? 'activated' : 'deactivated'} successfully`
    });

  } catch (error) {
    console.error('Update user status error:', error);
    res.status(500).json({
      success: false,
      message: 'Server error updating user status'
    });
  }
});

module.exports = router;
