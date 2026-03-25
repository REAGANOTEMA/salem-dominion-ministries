const express = require('express');
const { query } = require('../config/database');
const { authenticate, authorize } = require('../middleware/auth');
const { validateDonation } = require('../middleware/validation');

const router = express.Router();

// Create new donation
router.post('/', validateDonation, async (req, res) => {
  try {
    const {
      donor_name,
      donor_email,
      donor_phone,
      amount,
      donation_type = 'offering',
      payment_method = 'online',
      transaction_id,
      is_recurring = false,
      recurring_frequency,
      purpose,
      notes
    } = req.body;

    const result = await query(
      `INSERT INTO donations (
        donor_name, donor_email, donor_phone, amount, donation_type,
        payment_method, transaction_id, is_recurring, recurring_frequency,
        purpose, notes, status
      ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'pending')`,
      [
        donor_name, donor_email, donor_phone, amount, donation_type,
        payment_method, transaction_id, is_recurring, recurring_frequency,
        purpose, notes
      ]
    );

    if (!result.success) {
      return res.status(500).json({
        success: false,
        message: 'Failed to process donation'
      });
    }

    res.status(201).json({
      success: true,
      message: 'Donation received successfully',
      data: { donation_id: result.data.insertId }
    });

  } catch (error) {
    console.error('Process donation error:', error);
    res.status(500).json({
      success: false,
      message: 'Server error processing donation'
    });
  }
});

// Get all donations (admin/pastor only)
router.get('/', authenticate, authorize('admin', 'pastor'), async (req, res) => {
  try {
    const { 
      page = 1, 
      limit = 20, 
      status,
      donation_type,
      start_date,
      end_date
    } = req.query;

    const offset = (page - 1) * limit;
    let whereClause = 'WHERE 1=1';
    let params = [];

    if (status) {
      whereClause += ' AND status = ?';
      params.push(status);
    }

    if (donation_type) {
      whereClause += ' AND donation_type = ?';
      params.push(donation_type);
    }

    if (start_date) {
      whereClause += ' AND DATE(created_at) >= ?';
      params.push(start_date);
    }

    if (end_date) {
      whereClause += ' AND DATE(created_at) <= ?';
      params.push(end_date);
    }

    const donationsQuery = `
      SELECT 
        d.*,
        u.first_name as processed_by_first_name,
        u.last_name as processed_by_last_name
      FROM donations d
      LEFT JOIN users u ON d.processed_by = u.id
      ${whereClause}
      ORDER BY d.created_at DESC
      LIMIT ? OFFSET ?
    `;

    const donationsResult = await query(donationsQuery, [...params, parseInt(limit), offset]);

    // Get total count and sum
    const statsQuery = `
      SELECT 
        COUNT(*) as total,
        SUM(amount) as total_amount
      FROM donations d
      ${whereClause}
    `;

    const statsResult = await query(statsQuery, params);

    if (!donationsResult.success || !statsResult.success) {
      return res.status(500).json({
        success: false,
        message: 'Failed to fetch donations'
      });
    }

    res.json({
      success: true,
      data: {
        donations: donationsResult.data,
        pagination: {
          current_page: parseInt(page),
          per_page: parseInt(limit),
          total: statsResult.data[0].total,
          total_pages: Math.ceil(statsResult.data[0].total / limit)
        },
        stats: {
          total_amount: statsResult.data[0].total_amount || 0
        }
      }
    });

  } catch (error) {
    console.error('Get donations error:', error);
    res.status(500).json({
      success: false,
      message: 'Server error fetching donations'
    });
  }
});

// Get donation statistics (admin/pastor only)
router.get('/stats', authenticate, authorize('admin', 'pastor'), async (req, res) => {
  try {
    // Overall stats
    const overallStats = await query(`
      SELECT 
        COUNT(*) as total_donations,
        SUM(amount) as total_amount,
        AVG(amount) as average_amount
      FROM donations 
      WHERE status = 'completed'
    `);

    // Monthly stats for current year
    const monthlyStats = await query(`
      SELECT 
        MONTH(created_at) as month,
        COUNT(*) as donations_count,
        SUM(amount) as total_amount
      FROM donations 
      WHERE status = 'completed' AND YEAR(created_at) = YEAR(CURRENT_DATE)
      GROUP BY MONTH(created_at)
      ORDER BY month
    `);

    // Donation type breakdown
    const typeStats = await query(`
      SELECT 
        donation_type,
        COUNT(*) as count,
        SUM(amount) as total
      FROM donations 
      WHERE status = 'completed'
      GROUP BY donation_type
      ORDER BY total DESC
    `);

    if (!overallStats.success || !monthlyStats.success || !typeStats.success) {
      return res.status(500).json({
        success: false,
        message: 'Failed to fetch donation statistics'
      });
    }

    res.json({
      success: true,
      data: {
        overall: overallStats.data[0],
        monthly: monthlyStats.data,
        by_type: typeStats.data
      }
    });

  } catch (error) {
    console.error('Get donation stats error:', error);
    res.status(500).json({
      success: false,
      message: 'Server error fetching donation statistics'
    });
  }
});

// Update donation status (admin/pastor only)
router.put('/:id/status', authenticate, authorize('admin', 'pastor'), async (req, res) => {
  try {
    const { id } = req.params;
    const { status, processed_by } = req.body;

    if (!['pending', 'completed', 'failed', 'refunded'].includes(status)) {
      return res.status(400).json({
        success: false,
        message: 'Invalid status'
      });
    }

    const result = await query(
      'UPDATE donations SET status = ?, processed_by = ? WHERE id = ?',
      [status, processed_by || req.user.id, id]
    );

    if (!result.success) {
      return res.status(500).json({
        success: false,
        message: 'Failed to update donation status'
      });
    }

    res.json({
      success: true,
      message: 'Donation status updated successfully'
    });

  } catch (error) {
    console.error('Update donation status error:', error);
    res.status(500).json({
      success: false,
      message: 'Server error updating donation status'
    });
  }
});

module.exports = router;
