const express = require('express');
const { query } = require('../config/database');
const { authenticate, authorize } = require('../middleware/auth');
const { validatePrayerRequest } = require('../middleware/validation');

const router = express.Router();

// Get all prayer requests (public - only approved ones)
router.get('/', async (req, res) => {
  try {
    const { 
      page = 1, 
      limit = 10, 
      request_type,
      status = 'pending'
    } = req.query;

    const offset = (page - 1) * limit;
    let whereClause = 'WHERE is_public = true AND status = ?';
    let params = [status];

    if (request_type) {
      whereClause += ' AND request_type = ?';
      params.push(request_type);
    }

    const prayersQuery = `
      SELECT * FROM prayer_requests 
      ${whereClause}
      ORDER BY created_at DESC
      LIMIT ? OFFSET ?
    `;

    const prayersResult = await query(prayersQuery, [...params, parseInt(limit), offset]);

    // Get total count for pagination
    const countQuery = `
      SELECT COUNT(*) as total FROM prayer_requests ${whereClause}
    `;

    const countResult = await query(countQuery, params);

    if (!prayersResult.success || !countResult.success) {
      return res.status(500).json({
        success: false,
        message: 'Failed to fetch prayer requests'
      });
    }

    res.json({
      success: true,
      data: {
        prayer_requests: prayersResult.data,
        pagination: {
          current_page: parseInt(page),
          per_page: parseInt(limit),
          total: countResult.data[0].total,
          total_pages: Math.ceil(countResult.data[0].total / limit)
        }
      }
    });

  } catch (error) {
    console.error('Get prayer requests error:', error);
    res.status(500).json({
      success: false,
      message: 'Server error fetching prayer requests'
    });
  }
});

// Get single prayer request with responses
router.get('/:id', async (req, res) => {
  try {
    const { id } = req.params;

    // Get prayer request
    const prayerResult = await query(
      'SELECT * FROM prayer_requests WHERE id = ? AND is_public = true',
      [id]
    );

    if (!prayerResult.success) {
      return res.status(500).json({
        success: false,
        message: 'Failed to fetch prayer request'
      });
    }

    if (prayerResult.data.length === 0) {
      return res.status(404).json({
        success: false,
        message: 'Prayer request not found'
      });
    }

    // Get prayer responses
    const responsesResult = await query(
      'SELECT * FROM prayer_responses WHERE prayer_request_id = ? ORDER BY created_at DESC',
      [id]
    );

    res.json({
      success: true,
      data: {
        prayer_request: prayerResult.data[0],
        responses: responsesResult.data || []
      }
    });

  } catch (error) {
    console.error('Get prayer request error:', error);
    res.status(500).json({
      success: false,
      message: 'Server error fetching prayer request'
    });
  }
});

// Create new prayer request
router.post('/', validatePrayerRequest, async (req, res) => {
  try {
    const {
      requester_name,
      requester_email,
      request_type,
      title,
      description,
      is_anonymous = false,
      is_public = true
    } = req.body;

    const result = await query(
      `INSERT INTO prayer_requests (
        requester_name, requester_email, request_type, title, description,
        is_anonymous, is_public
      ) VALUES (?, ?, ?, ?, ?, ?, ?)`,
      [requester_name, requester_email, request_type, title, description, is_anonymous, is_public]
    );

    if (!result.success) {
      return res.status(500).json({
        success: false,
        message: 'Failed to create prayer request'
      });
    }

    // Get the created prayer request
    const createdPrayer = await query(
      'SELECT * FROM prayer_requests WHERE id = ?',
      [result.data.insertId]
    );

    res.status(201).json({
      success: true,
      message: 'Prayer request created successfully',
      data: { prayer_request: createdPrayer.data[0] }
    });

  } catch (error) {
    console.error('Create prayer request error:', error);
    res.status(500).json({
      success: false,
      message: 'Server error creating prayer request'
    });
  }
});

// Add prayer response
router.post('/:id/respond', async (req, res) => {
  try {
    const { id } = req.params;
    const {
      responder_name,
      responder_email,
      message,
      is_anonymous = true
    } = req.body;

    // Check if prayer request exists
    const prayerResult = await query(
      'SELECT id FROM prayer_requests WHERE id = ? AND is_public = true',
      [id]
    );

    if (!prayerResult.success || prayerResult.data.length === 0) {
      return res.status(404).json({
        success: false,
        message: 'Prayer request not found'
      });
    }

    const result = await query(
      `INSERT INTO prayer_responses (
        prayer_request_id, responder_name, responder_email, message, is_anonymous
      ) VALUES (?, ?, ?, ?, ?)`,
      [id, responder_name, responder_email, message, is_anonymous]
    );

    if (!result.success) {
      return res.status(500).json({
        success: false,
        message: 'Failed to add prayer response'
      });
    }

    // Increment prayer count
    await query(
      'UPDATE prayer_requests SET prayer_count = prayer_count + 1 WHERE id = ?',
      [id]
    );

    res.status(201).json({
      success: true,
      message: 'Prayer response added successfully',
      data: { response_id: result.data.insertId }
    });

  } catch (error) {
    console.error('Add prayer response error:', error);
    res.status(500).json({
      success: false,
      message: 'Server error adding prayer response'
    });
  }
});

// Get all prayer requests (admin/pastor only)
router.get('/admin/all', authenticate, authorize('admin', 'pastor'), async (req, res) => {
  try {
    const { page = 1, limit = 20, status, request_type } = req.query;
    const offset = (page - 1) * limit;
    let whereClause = 'WHERE 1=1';
    let params = [];

    if (status) {
      whereClause += ' AND status = ?';
      params.push(status);
    }

    if (request_type) {
      whereClause += ' AND request_type = ?';
      params.push(request_type);
    }

    const prayersQuery = `
      SELECT * FROM prayer_requests 
      ${whereClause}
      ORDER BY created_at DESC
      LIMIT ? OFFSET ?
    `;

    const prayersResult = await query(prayersQuery, [...params, parseInt(limit), offset]);

    const countResult = await query(
      `SELECT COUNT(*) as total FROM prayer_requests ${whereClause}`,
      params
    );

    if (!prayersResult.success || !countResult.success) {
      return res.status(500).json({
        success: false,
        message: 'Failed to fetch prayer requests'
      });
    }

    res.json({
      success: true,
      data: {
        prayer_requests: prayersResult.data,
        pagination: {
          current_page: parseInt(page),
          per_page: parseInt(limit),
          total: countResult.data[0].total,
          total_pages: Math.ceil(countResult.data[0].total / limit)
        }
      }
    });

  } catch (error) {
    console.error('Get admin prayer requests error:', error);
    res.status(500).json({
      success: false,
      message: 'Server error fetching prayer requests'
    });
  }
});

// Update prayer request status (admin/pastor only)
router.put('/:id/status', authenticate, authorize('admin', 'pastor'), async (req, res) => {
  try {
    const { id } = req.params;
    const { status } = req.body;

    if (!['pending', 'praying', 'answered', 'closed'].includes(status)) {
      return res.status(400).json({
        success: false,
        message: 'Invalid status'
      });
    }

    const updateData = { status };
    if (status === 'answered') {
      updateData.answered_date = new Date();
    }

    const result = await query(
      'UPDATE prayer_requests SET status = ?, answered_date = ? WHERE id = ?',
      [status, updateData.answered_date || null, id]
    );

    if (!result.success) {
      return res.status(500).json({
        success: false,
        message: 'Failed to update prayer request status'
      });
    }

    res.json({
      success: true,
      message: 'Prayer request status updated successfully'
    });

  } catch (error) {
    console.error('Update prayer status error:', error);
    res.status(500).json({
      success: false,
      message: 'Server error updating prayer request status'
    });
  }
});

module.exports = router;
