const express = require('express');
const { query } = require('../config/database');
const { authenticate, authorize } = require('../middleware/auth');
const { uploadSingle, uploadMultiple, deleteFile } = require('../middleware/upload');

const router = express.Router();

// Get all gallery items (public)
router.get('/', async (req, res) => {
  try {
    const { 
      page = 1, 
      limit = 20, 
      file_type,
      category,
      status = 'published'
    } = req.query;

    const offset = (page - 1) * limit;
    let whereClause = 'WHERE status = ?';
    let params = [status];

    if (file_type) {
      whereClause += ' AND file_type = ?';
      params.push(file_type);
    }

    if (category) {
      whereClause += ' AND category = ?';
      params.push(category);
    }

    const galleryQuery = `
      SELECT 
        g.*,
        u.first_name as uploaded_by_first_name,
        u.last_name as uploaded_by_last_name
      FROM gallery g
      LEFT JOIN users u ON g.uploaded_by = u.id
      ${whereClause}
      ORDER BY g.created_at DESC
      LIMIT ? OFFSET ?
    `;

    const galleryResult = await query(galleryQuery, [...params, parseInt(limit), offset]);

    const countResult = await query(
      `SELECT COUNT(*) as total FROM gallery g ${whereClause}`,
      params
    );

    if (!galleryResult.success || !countResult.success) {
      return res.status(500).json({
        success: false,
        message: 'Failed to fetch gallery items'
      });
    }

    res.json({
      success: true,
      data: {
        items: galleryResult.data,
        pagination: {
          current_page: parseInt(page),
          per_page: parseInt(limit),
          total: countResult.data[0].total,
          total_pages: Math.ceil(countResult.data[0].total / limit)
        }
      }
    });

  } catch (error) {
    console.error('Get gallery error:', error);
    res.status(500).json({
      success: false,
      message: 'Server error fetching gallery'
    });
  }
});

// Get featured gallery items
router.get('/featured', async (req, res) => {
  try {
    const itemsResult = await query(
      `SELECT g.*, u.first_name as uploaded_by_first_name, u.last_name as uploaded_by_last_name
       FROM gallery g 
       LEFT JOIN users u ON g.uploaded_by = u.id
       WHERE g.status = 'published' AND g.is_featured = true
       ORDER BY g.created_at DESC
       LIMIT 10`
    );

    if (!itemsResult.success) {
      return res.status(500).json({
        success: false,
        message: 'Failed to fetch featured items'
      });
    }

    res.json({
      success: true,
      data: { items: itemsResult.data }
    });

  } catch (error) {
    console.error('Get featured items error:', error);
    res.status(500).json({
      success: false,
      message: 'Server error fetching featured items'
    });
  }
});

// Get single gallery item
router.get('/:id', async (req, res) => {
  try {
    const { id } = req.params;

    const itemQuery = `
      SELECT 
        g.*,
        u.first_name as uploaded_by_first_name,
        u.last_name as uploaded_by_last_name
      FROM gallery g
      LEFT JOIN users u ON g.uploaded_by = u.id
      WHERE g.id = ?
    `;

    const itemResult = await query(itemQuery, [id]);

    if (!itemResult.success) {
      return res.status(500).json({
        success: false,
        message: 'Failed to fetch gallery item'
      });
    }

    if (itemResult.data.length === 0) {
      return res.status(404).json({
        success: false,
        message: 'Gallery item not found'
      });
    }

    res.json({
      success: true,
      data: { item: itemResult.data[0] }
    });

  } catch (error) {
    console.error('Get gallery item error:', error);
    res.status(500).json({
      success: false,
      message: 'Server error fetching gallery item'
    });
  }
});

// Upload gallery item (admin/pastor only)
router.post('/', authenticate, authorize('admin', 'pastor'), uploadSingle('file'), async (req, res) => {
  try {
    const {
      title,
      description,
      file_type,
      category,
      tags,
      is_featured,
      status
    } = req.body;

    if (!req.file && !req.fileUrl) {
      return res.status(400).json({
        success: false,
        message: 'No file uploaded'
      });
    }

    const file_url = req.fileUrl;
    const file_size = req.file.size;
    const dimensions = `${req.file.width || 'N/A'}x${req.file.height || 'N/A'}`;

    const result = await query(
      `INSERT INTO gallery (
        title, description, file_url, file_type, file_size, dimensions,
        category, tags, is_featured, status, uploaded_by
      ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)`,
      [
        title, description, file_url, file_type, file_size, dimensions,
        category, JSON.stringify(tags || []), is_featured || false, status, req.user.id
      ]
    );

    if (!result.success) {
      return res.status(500).json({
        success: false,
        message: 'Failed to upload gallery item'
      });
    }

    // Get the created item
    const createdItem = await query(
      'SELECT * FROM gallery WHERE id = ?',
      [result.data.insertId]
    );

    res.status(201).json({
      success: true,
      message: 'Gallery item uploaded successfully',
      data: { item: createdItem.data[0] }
    });

  } catch (error) {
    console.error('Upload gallery item error:', error);
    res.status(500).json({
      success: false,
      message: 'Server error uploading gallery item'
    });
  }
});

// Update gallery item (admin/pastor only)
router.put('/:id', authenticate, authorize('admin', 'pastor'), async (req, res) => {
  try {
    const { id } = req.params;
    const {
      title,
      description,
      file_url,
      file_type,
      file_size,
      dimensions,
      category,
      tags,
      is_featured,
      status
    } = req.body;

    // Check if item exists
    const existingItem = await query('SELECT id FROM gallery WHERE id = ?', [id]);

    if (!existingItem.success || existingItem.data.length === 0) {
      return res.status(404).json({
        success: false,
        message: 'Gallery item not found'
      });
    }

    const result = await query(
      `UPDATE gallery SET 
        title = ?, description = ?, file_url = ?, file_type = ?, file_size = ?,
        dimensions = ?, category = ?, tags = ?, is_featured = ?, status = ?
      WHERE id = ?`,
      [
        title, description, file_url, file_type, file_size, dimensions,
        category, JSON.stringify(tags || []), is_featured || false, status, id
      ]
    );

    if (!result.success) {
      return res.status(500).json({
        success: false,
        message: 'Failed to update gallery item'
      });
    }

    // Get updated item
    const updatedItem = await query('SELECT * FROM gallery WHERE id = ?', [id]);

    res.json({
      success: true,
      message: 'Gallery item updated successfully',
      data: { item: updatedItem.data[0] }
    });

  } catch (error) {
    console.error('Update gallery item error:', error);
    res.status(500).json({
      success: false,
      message: 'Server error updating gallery item'
    });
  }
});

// Delete gallery item (admin only)
router.delete('/:id', authenticate, authorize('admin'), async (req, res) => {
  try {
    const { id } = req.params;

    const result = await query('DELETE FROM gallery WHERE id = ?', [id]);

    if (!result.success) {
      return res.status(500).json({
        success: false,
        message: 'Failed to delete gallery item'
      });
    }

    res.json({
      success: true,
      message: 'Gallery item deleted successfully'
    });

  } catch (error) {
    console.error('Delete gallery item error:', error);
    res.status(500).json({
      success: false,
      message: 'Server error deleting gallery item'
    });
  }
});

module.exports = router;
