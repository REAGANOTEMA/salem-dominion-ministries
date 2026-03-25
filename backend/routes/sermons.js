const express = require('express');
const { query } = require('../config/database');
const { authenticate, authorize, optionalAuth } = require('../middleware/auth');
const { validateSermon } = require('../middleware/validation');

const router = express.Router();

// Get all sermons (public)
router.get('/', optionalAuth, async (req, res) => {
  try {
    const { 
      page = 1, 
      limit = 10, 
      status = 'published',
      search,
      sermon_series
    } = req.query;

    const offset = (page - 1) * limit;
    let whereClause = 'WHERE s.status = ?';
    let params = [status];

    if (search) {
      whereClause += ' AND (s.title LIKE ? OR s.description LIKE ? OR s.preacher LIKE ?)';
      params.push(`%${search}%`, `%${search}%`, `%${search}%`);
    }

    if (sermon_series) {
      whereClause += ' AND s.sermon_series = ?';
      params.push(sermon_series);
    }

    const sermonsQuery = `
      SELECT 
        s.*,
        u.first_name as created_by_first_name,
        u.last_name as created_by_last_name
      FROM sermons s
      LEFT JOIN users u ON s.created_by = u.id
      ${whereClause}
      ORDER BY s.sermon_date DESC
      LIMIT ? OFFSET ?
    `;

    const sermonsResult = await query(sermonsQuery, [...params, parseInt(limit), offset]);

    // Get total count for pagination
    const countQuery = `
      SELECT COUNT(*) as total FROM sermons s ${whereClause}
    `;

    const countResult = await query(countQuery, params);

    if (!sermonsResult.success || !countResult.success) {
      return res.status(500).json({
        success: false,
        message: 'Failed to fetch sermons'
      });
    }

    res.json({
      success: true,
      data: {
        sermons: sermonsResult.data,
        pagination: {
          current_page: parseInt(page),
          per_page: parseInt(limit),
          total: countResult.data[0].total,
          total_pages: Math.ceil(countResult.data[0].total / limit)
        }
      }
    });

  } catch (error) {
    console.error('Get sermons error:', error);
    res.status(500).json({
      success: false,
      message: 'Server error fetching sermons'
    });
  }
});

// Get featured sermons
router.get('/featured', async (req, res) => {
  try {
    const sermonsResult = await query(
      `SELECT s.*, u.first_name as created_by_first_name, u.last_name as created_by_last_name
       FROM sermons s 
       LEFT JOIN users u ON s.created_by = u.id
       WHERE s.status = 'published' AND s.is_featured = true
       ORDER BY s.sermon_date DESC
       LIMIT 5`
    );

    if (!sermonsResult.success) {
      return res.status(500).json({
        success: false,
        message: 'Failed to fetch featured sermons'
      });
    }

    res.json({
      success: true,
      data: { sermons: sermonsResult.data }
    });

  } catch (error) {
    console.error('Get featured sermons error:', error);
    res.status(500).json({
      success: false,
      message: 'Server error fetching featured sermons'
    });
  }
});

// Get single sermon
router.get('/:id', optionalAuth, async (req, res) => {
  try {
    const { id } = req.params;

    // Increment view count
    await query('UPDATE sermons SET views_count = views_count + 1 WHERE id = ?', [id]);

    const sermonQuery = `
      SELECT 
        s.*,
        u.first_name as created_by_first_name,
        u.last_name as created_by_last_name
      FROM sermons s
      LEFT JOIN users u ON s.created_by = u.id
      WHERE s.id = ?
    `;

    const sermonResult = await query(sermonQuery, [id]);

    if (!sermonResult.success) {
      return res.status(500).json({
        success: false,
        message: 'Failed to fetch sermon'
      });
    }

    if (sermonResult.data.length === 0) {
      return res.status(404).json({
        success: false,
        message: 'Sermon not found'
      });
    }

    res.json({
      success: true,
      data: { sermon: sermonResult.data[0] }
    });

  } catch (error) {
    console.error('Get sermon error:', error);
    res.status(500).json({
      success: false,
      message: 'Server error fetching sermon'
    });
  }
});

// Create new sermon (admin/pastor only)
router.post('/', authenticate, authorize('admin', 'pastor'), validateSermon, async (req, res) => {
  try {
    const {
      title,
      preacher,
      bible_reference,
      sermon_date,
      description,
      video_url,
      audio_url,
      transcript,
      featured_image_url,
      sermon_series,
      tags,
      is_featured,
      status
    } = req.body;

    const result = await query(
      `INSERT INTO sermons (
        title, preacher, bible_reference, sermon_date, description, video_url,
        audio_url, transcript, featured_image_url, sermon_series, tags,
        is_featured, status, created_by
      ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)`,
      [
        title, preacher, bible_reference, sermon_date, description, video_url,
        audio_url, transcript, featured_image_url, sermon_series,
        JSON.stringify(tags || []), is_featured || false, status, req.user.id
      ]
    );

    if (!result.success) {
      return res.status(500).json({
        success: false,
        message: 'Failed to create sermon'
      });
    }

    // Get the created sermon
    const createdSermon = await query(
      'SELECT * FROM sermons WHERE id = ?',
      [result.data.insertId]
    );

    res.status(201).json({
      success: true,
      message: 'Sermon created successfully',
      data: { sermon: createdSermon.data[0] }
    });

  } catch (error) {
    console.error('Create sermon error:', error);
    res.status(500).json({
      success: false,
      message: 'Server error creating sermon'
    });
  }
});

// Update sermon (admin/pastor only)
router.put('/:id', authenticate, authorize('admin', 'pastor'), validateSermon, async (req, res) => {
  try {
    const { id } = req.params;
    const {
      title,
      preacher,
      bible_reference,
      sermon_date,
      description,
      video_url,
      audio_url,
      transcript,
      featured_image_url,
      sermon_series,
      tags,
      is_featured,
      status
    } = req.body;

    // Check if sermon exists
    const existingSermon = await query('SELECT id FROM sermons WHERE id = ?', [id]);

    if (!existingSermon.success || existingSermon.data.length === 0) {
      return res.status(404).json({
        success: false,
        message: 'Sermon not found'
      });
    }

    const result = await query(
      `UPDATE sermons SET 
        title = ?, preacher = ?, bible_reference = ?, sermon_date = ?, description = ?,
        video_url = ?, audio_url = ?, transcript = ?, featured_image_url = ?,
        sermon_series = ?, tags = ?, is_featured = ?, status = ?
      WHERE id = ?`,
      [
        title, preacher, bible_reference, sermon_date, description, video_url,
        audio_url, transcript, featured_image_url, sermon_series,
        JSON.stringify(tags || []), is_featured || false, status, id
      ]
    );

    if (!result.success) {
      return res.status(500).json({
        success: false,
        message: 'Failed to update sermon'
      });
    }

    // Get updated sermon
    const updatedSermon = await query('SELECT * FROM sermons WHERE id = ?', [id]);

    res.json({
      success: true,
      message: 'Sermon updated successfully',
      data: { sermon: updatedSermon.data[0] }
    });

  } catch (error) {
    console.error('Update sermon error:', error);
    res.status(500).json({
      success: false,
      message: 'Server error updating sermon'
    });
  }
});

// Delete sermon (admin only)
router.delete('/:id', authenticate, authorize('admin'), async (req, res) => {
  try {
    const { id } = req.params;

    // Check if sermon exists
    const existingSermon = await query('SELECT id FROM sermons WHERE id = ?', [id]);

    if (!existingSermon.success || existingSermon.data.length === 0) {
      return res.status(404).json({
        success: false,
        message: 'Sermon not found'
      });
    }

    const result = await query('DELETE FROM sermons WHERE id = ?', [id]);

    if (!result.success) {
      return res.status(500).json({
        success: false,
        message: 'Failed to delete sermon'
      });
    }

    res.json({
      success: true,
      message: 'Sermon deleted successfully'
    });

  } catch (error) {
    console.error('Delete sermon error:', error);
    res.status(500).json({
      success: false,
      message: 'Server error deleting sermon'
    });
  }
});

module.exports = router;
