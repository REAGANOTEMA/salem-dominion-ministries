const express = require('express');
const { query } = require('../config/database');
const { authenticate, authorize } = require('../middleware/auth');
const { validateContactMessage } = require('../middleware/validation');

const router = express.Router();

// Create new contact message
router.post('/', validateContactMessage, async (req, res) => {
  try {
    const {
      name,
      email,
      phone,
      subject,
      message,
      message_type = 'general'
    } = req.body;

    const result = await query(
      `INSERT INTO contact_messages (
        name, email, phone, subject, message, message_type
      ) VALUES (?, ?, ?, ?, ?, ?)`,
      [name, email, phone, subject, message, message_type]
    );

    if (!result.success) {
      return res.status(500).json({
        success: false,
        message: 'Failed to send message'
      });
    }

    res.status(201).json({
      success: true,
      message: 'Message sent successfully',
      data: { message_id: result.data.insertId }
    });

  } catch (error) {
    console.error('Send contact message error:', error);
    res.status(500).json({
      success: false,
      message: 'Server error sending message'
    });
  }
});

// Get all contact messages (admin/pastor only)
router.get('/', authenticate, authorize('admin', 'pastor'), async (req, res) => {
  try {
    const { 
      page = 1, 
      limit = 20, 
      status,
      priority,
      message_type
    } = req.query;

    const offset = (page - 1) * limit;
    let whereClause = 'WHERE 1=1';
    let params = [];

    if (status) {
      whereClause += ' AND status = ?';
      params.push(status);
    }

    if (priority) {
      whereClause += ' AND priority = ?';
      params.push(priority);
    }

    if (message_type) {
      whereClause += ' AND message_type = ?';
      params.push(message_type);
    }

    const messagesQuery = `
      SELECT 
        cm.*,
        u.first_name as assigned_to_first_name,
        u.last_name as assigned_to_last_name
      FROM contact_messages cm
      LEFT JOIN users u ON cm.assigned_to = u.id
      ${whereClause}
      ORDER BY cm.created_at DESC
      LIMIT ? OFFSET ?
    `;

    const messagesResult = await query(messagesQuery, [...params, parseInt(limit), offset]);

    const countResult = await query(
      `SELECT COUNT(*) as total FROM contact_messages cm ${whereClause}`,
      params
    );

    if (!messagesResult.success || !countResult.success) {
      return res.status(500).json({
        success: false,
        message: 'Failed to fetch messages'
      });
    }

    res.json({
      success: true,
      data: {
        messages: messagesResult.data,
        pagination: {
          current_page: parseInt(page),
          per_page: parseInt(limit),
          total: countResult.data[0].total,
          total_pages: Math.ceil(countResult.data[0].total / limit)
        }
      }
    });

  } catch (error) {
    console.error('Get contact messages error:', error);
    res.status(500).json({
      success: false,
      message: 'Server error fetching messages'
    });
  }
});

// Get single contact message
router.get('/:id', authenticate, authorize('admin', 'pastor'), async (req, res) => {
  try {
    const { id } = req.params;

    const messageQuery = `
      SELECT 
        cm.*,
        u.first_name as assigned_to_first_name,
        u.last_name as assigned_to_last_name
      FROM contact_messages cm
      LEFT JOIN users u ON cm.assigned_to = u.id
      WHERE cm.id = ?
    `;

    const messageResult = await query(messageQuery, [id]);

    if (!messageResult.success) {
      return res.status(500).json({
        success: false,
        message: 'Failed to fetch message'
      });
    }

    if (messageResult.data.length === 0) {
      return res.status(404).json({
        success: false,
        message: 'Message not found'
      });
    }

    res.json({
      success: true,
      data: { message: messageResult.data[0] }
    });

  } catch (error) {
    console.error('Get contact message error:', error);
    res.status(500).json({
      success: false,
      message: 'Server error fetching message'
    });
  }
});

// Update message status (admin/pastor only)
router.put('/:id/status', authenticate, authorize('admin', 'pastor'), async (req, res) => {
  try {
    const { id } = req.params;
    const { status, assigned_to, response } = req.body;

    if (!['unread', 'read', 'responded', 'closed'].includes(status)) {
      return res.status(400).json({
        success: false,
        message: 'Invalid status'
      });
    }

    const updateData = { status };
    if (assigned_to) updateData.assigned_to = assigned_to;
    if (response) updateData.response = response;
    if (status === 'responded' || status === 'closed') {
      updateData.responded_at = new Date();
    }

    const result = await query(
      `UPDATE contact_messages SET 
        status = ?, assigned_to = ?, response = ?, responded_at = ?
      WHERE id = ?`,
      [status, assigned_to || null, response || null, updateData.responded_at || null, id]
    );

    if (!result.success) {
      return res.status(500).json({
        success: false,
        message: 'Failed to update message status'
      });
    }

    res.json({
      success: true,
      message: 'Message status updated successfully'
    });

  } catch (error) {
    console.error('Update message status error:', error);
    res.status(500).json({
      success: false,
      message: 'Server error updating message status'
    });
  }
});

// Delete message (admin only)
router.delete('/:id', authenticate, authorize('admin'), async (req, res) => {
  try {
    const { id } = req.params;

    const result = await query('DELETE FROM contact_messages WHERE id = ?', [id]);

    if (!result.success) {
      return res.status(500).json({
        success: false,
        message: 'Failed to delete message'
      });
    }

    res.json({
      success: true,
      message: 'Message deleted successfully'
    });

  } catch (error) {
    console.error('Delete message error:', error);
    res.status(500).json({
      success: false,
      message: 'Server error deleting message'
    });
  }
});

module.exports = router;
