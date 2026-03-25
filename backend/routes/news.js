const express = require('express');
const { query } = require('../config/database');
const { authenticate, authorize, optionalAuth } = require('../middleware/auth');
const { uploadSingle } = require('../middleware/upload');

const router = express.Router();

// Get all news articles (public)
router.get('/', optionalAuth, async (req, res) => {
  try {
    const { 
      page = 1, 
      limit = 10, 
      status = 'published',
      category,
      search
    } = req.query;

    const offset = (page - 1) * limit;
    let whereClause = 'WHERE status = ?';
    let params = [status];

    if (category) {
      whereClause += ' AND category = ?';
      params.push(category);
    }

    if (search) {
      whereClause += ' AND (title LIKE ? OR excerpt LIKE ? OR content LIKE ?)';
      params.push(`%${search}%`, `%${search}%`, `%${search}%`);
    }

    const newsQuery = `
      SELECT 
        n.*,
        u.first_name as author_first_name,
        u.last_name as author_last_name
      FROM news n
      LEFT JOIN users u ON n.author_id = u.id
      ${whereClause}
      ORDER BY n.published_at DESC
      LIMIT ? OFFSET ?
    `;

    const newsResult = await query(newsQuery, [...params, parseInt(limit), offset]);

    const countResult = await query(
      `SELECT COUNT(*) as total FROM news n ${whereClause}`,
      params
    );

    if (!newsResult.success || !countResult.success) {
      return res.status(500).json({
        success: false,
        message: 'Failed to fetch news articles'
      });
    }

    res.json({
      success: true,
      data: {
        articles: newsResult.data,
        pagination: {
          current_page: parseInt(page),
          per_page: parseInt(limit),
          total: countResult.data[0].total,
          total_pages: Math.ceil(countResult.data[0].total / limit)
        }
      }
    });

  } catch (error) {
    console.error('Get news articles error:', error);
    res.status(500).json({
      success: false,
      message: 'Server error fetching news articles'
    });
  }
});

// Get featured news articles
router.get('/featured', async (req, res) => {
  try {
    const articlesResult = await query(
      `SELECT n.*, u.first_name as author_first_name, u.last_name as author_last_name
       FROM news n 
       LEFT JOIN users u ON n.author_id = u.id
       WHERE n.status = 'published' AND n.is_featured = true
       ORDER BY n.published_at DESC
       LIMIT 5`
    );

    if (!articlesResult.success) {
      return res.status(500).json({
        success: false,
        message: 'Failed to fetch featured articles'
      });
    }

    res.json({
      success: true,
      data: { articles: articlesResult.data }
    });

  } catch (error) {
    console.error('Get featured articles error:', error);
    res.status(500).json({
      success: false,
      message: 'Server error fetching featured articles'
    });
  }
});

// Get breaking news
router.get('/breaking', async (req, res) => {
  try {
    const articlesResult = await query(
      `SELECT n.*, u.first_name as author_first_name, u.last_name as author_last_name
       FROM news n 
       LEFT JOIN users u ON n.author_id = u.id
       WHERE n.status = 'published' AND n.is_breaking = true
       ORDER BY n.published_at DESC
       LIMIT 3`
    );

    if (!articlesResult.success) {
      return res.status(500).json({
        success: false,
        message: 'Failed to fetch breaking news'
      });
    }

    res.json({
      success: true,
      data: { articles: articlesResult.data }
    });

  } catch (error) {
    console.error('Get breaking news error:', error);
    res.status(500).json({
      success: false,
      message: 'Server error fetching breaking news'
    });
  }
});

// Get single news article
router.get('/:id', optionalAuth, async (req, res) => {
  try {
    const { id } = req.params;

    // Increment view count
    await query('UPDATE news SET views_count = views_count + 1 WHERE id = ?', [id]);

    const articleQuery = `
      SELECT 
        n.*,
        u.first_name as author_first_name,
        u.last_name as author_last_name
      FROM news n
      LEFT JOIN users u ON n.author_id = u.id
      WHERE n.id = ?
    `;

    const articleResult = await query(articleQuery, [id]);

    if (!articleResult.success) {
      return res.status(500).json({
        success: false,
        message: 'Failed to fetch news article'
      });
    }

    if (articleResult.data.length === 0) {
      return res.status(404).json({
        success: false,
        message: 'News article not found'
      });
    }

    res.json({
      success: true,
      data: { article: articleResult.data[0] }
    });

  } catch (error) {
    console.error('Get news article error:', error);
    res.status(500).json({
      success: false,
      message: 'Server error fetching news article'
    });
  }
});

// Create new news article (admin/pastor only)
router.post('/', authenticate, authorize('admin', 'pastor'), uploadSingle('featured_image'), async (req, res) => {
  try {
    const {
      title,
      slug,
      excerpt,
      content,
      category,
      tags,
      meta_title,
      meta_description,
      is_featured,
      is_breaking,
      status
    } = req.body;

    const featured_image_url = req.fileUrl || null;

    // Generate slug if not provided
    const articleSlug = slug || title.toLowerCase()
      .replace(/[^a-z0-9]+/g, '-')
      .replace(/^-+|-+$/g, '');

    const result = await query(
      `INSERT INTO news (
        title, slug, excerpt, content, featured_image_url, category,
        tags, meta_title, meta_description, is_featured, is_breaking, status,
        author_id, published_at
      ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)`,
      [
        title, articleSlug, excerpt, content, featured_image_url, category,
        JSON.stringify(tags || []), meta_title, meta_description,
        is_featured || false, is_breaking || false, status, req.user.id,
        status === 'published' ? new Date() : null
      ]
    );

    if (!result.success) {
      return res.status(500).json({
        success: false,
        message: 'Failed to create news article'
      });
    }

    // Get the created article
    const createdArticle = await query(
      'SELECT * FROM news WHERE id = ?',
      [result.data.insertId]
    );

    res.status(201).json({
      success: true,
      message: 'News article created successfully',
      data: { article: createdArticle.data[0] }
    });

  } catch (error) {
    console.error('Create news article error:', error);
    res.status(500).json({
      success: false,
      message: 'Server error creating news article'
    });
  }
});

// Update news article (admin/pastor only)
router.put('/:id', authenticate, authorize('admin', 'pastor'), uploadSingle('featured_image'), async (req, res) => {
  try {
    const { id } = req.params;
    const {
      title,
      slug,
      excerpt,
      content,
      category,
      tags,
      meta_title,
      meta_description,
      is_featured,
      is_breaking,
      status
    } = req.body;

    // Check if article exists
    const existingArticle = await query('SELECT id, status FROM news WHERE id = ?', [id]);

    if (!existingArticle.success || existingArticle.data.length === 0) {
      return res.status(404).json({
        success: false,
        message: 'News article not found'
      });
    }

    const currentStatus = existingArticle.data[0].status;
    const published_at = status === 'published' && currentStatus !== 'published' ? new Date() : null;
    const featured_image_url = req.fileUrl || null;

    const result = await query(
      `UPDATE news SET 
        title = ?, slug = ?, excerpt = ?, content = ?, featured_image_url = ?,
        category = ?, tags = ?, meta_title = ?, meta_description = ?,
        is_featured = ?, is_breaking = ?, status = ?, published_at = COALESCE(?, published_at)
      WHERE id = ?`,
      [
        title, slug, excerpt, content, featured_image_url, category,
        JSON.stringify(tags || []), meta_title, meta_description,
        is_featured || false, is_breaking || false, status, published_at, id
      ]
    );

    if (!result.success) {
      return res.status(500).json({
        success: false,
        message: 'Failed to update news article'
      });
    }

    // Get updated article
    const updatedArticle = await query('SELECT * FROM news WHERE id = ?', [id]);

    res.json({
      success: true,
      message: 'News article updated successfully',
      data: { article: updatedArticle.data[0] }
    });

  } catch (error) {
    console.error('Update news article error:', error);
    res.status(500).json({
      success: false,
      message: 'Server error updating news article'
    });
  }
});

// Delete news article (admin only)
router.delete('/:id', authenticate, authorize('admin'), async (req, res) => {
  try {
    const { id } = req.params;

    // Get article to delete associated image
    const articleResult = await query('SELECT featured_image_url FROM news WHERE id = ?', [id]);

    const result = await query('DELETE FROM news WHERE id = ?', [id]);

    if (!result.success) {
      return res.status(500).json({
        success: false,
        message: 'Failed to delete news article'
      });
    }

    // Delete associated image if exists
    if (articleResult.success && articleResult.data.length > 0) {
      const imageUrl = articleResult.data[0].featured_image_url;
      if (imageUrl) {
        try {
          const { deleteFile } = require('../middleware/upload');
          deleteFile(imageUrl);
        } catch (error) {
          console.error('Error deleting image:', error);
        }
      }
    }

    res.json({
      success: true,
      message: 'News article deleted successfully'
    });

  } catch (error) {
    console.error('Delete news article error:', error);
    res.status(500).json({
      success: false,
      message: 'Server error deleting news article'
    });
  }
});

// Get news categories
router.get('/categories/list', async (req, res) => {
  try {
    const categoriesResult = await query(
      `SELECT DISTINCT category, COUNT(*) as count 
       FROM news 
       WHERE status = 'published' AND category IS NOT NULL
       GROUP BY category
       ORDER BY count DESC`
    );

    if (!categoriesResult.success) {
      return res.status(500).json({
        success: false,
        message: 'Failed to fetch categories'
      });
    }

    res.json({
      success: true,
      data: { categories: categoriesResult.data }
    });

  } catch (error) {
    console.error('Get categories error:', error);
    res.status(500).json({
      success: false,
      message: 'Server error fetching categories'
    });
  }
});

module.exports = router;
