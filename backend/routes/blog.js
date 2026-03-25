const express = require('express');
const { query } = require('../config/database');
const { authenticate, authorize, optionalAuth } = require('../middleware/auth');

const router = express.Router();

// Get all blog posts (public)
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

    const postsQuery = `
      SELECT 
        bp.*,
        u.first_name as author_first_name,
        u.last_name as author_last_name
      FROM blog_posts bp
      LEFT JOIN users u ON bp.author_id = u.id
      ${whereClause}
      ORDER BY bp.published_at DESC
      LIMIT ? OFFSET ?
    `;

    const postsResult = await query(postsQuery, [...params, parseInt(limit), offset]);

    const countResult = await query(
      `SELECT COUNT(*) as total FROM blog_posts bp ${whereClause}`,
      params
    );

    if (!postsResult.success || !countResult.success) {
      return res.status(500).json({
        success: false,
        message: 'Failed to fetch blog posts'
      });
    }

    res.json({
      success: true,
      data: {
        posts: postsResult.data,
        pagination: {
          current_page: parseInt(page),
          per_page: parseInt(limit),
          total: countResult.data[0].total,
          total_pages: Math.ceil(countResult.data[0].total / limit)
        }
      }
    });

  } catch (error) {
    console.error('Get blog posts error:', error);
    res.status(500).json({
      success: false,
      message: 'Server error fetching blog posts'
    });
  }
});

// Get featured blog posts
router.get('/featured', async (req, res) => {
  try {
    const postsResult = await query(
      `SELECT bp.*, u.first_name as author_first_name, u.last_name as author_last_name
       FROM blog_posts bp 
       LEFT JOIN users u ON bp.author_id = u.id
       WHERE bp.status = 'published' AND bp.is_featured = true
       ORDER BY bp.published_at DESC
       LIMIT 5`
    );

    if (!postsResult.success) {
      return res.status(500).json({
        success: false,
        message: 'Failed to fetch featured posts'
      });
    }

    res.json({
      success: true,
      data: { posts: postsResult.data }
    });

  } catch (error) {
    console.error('Get featured posts error:', error);
    res.status(500).json({
      success: false,
      message: 'Server error fetching featured posts'
    });
  }
});

// Get single blog post
router.get('/:slug', optionalAuth, async (req, res) => {
  try {
    const { slug } = req.params;

    // Increment view count
    await query('UPDATE blog_posts SET views_count = views_count + 1 WHERE slug = ?', [slug]);

    const postQuery = `
      SELECT 
        bp.*,
        u.first_name as author_first_name,
        u.last_name as author_last_name
      FROM blog_posts bp
      LEFT JOIN users u ON bp.author_id = u.id
      WHERE bp.slug = ?
    `;

    const postResult = await query(postQuery, [slug]);

    if (!postResult.success) {
      return res.status(500).json({
        success: false,
        message: 'Failed to fetch blog post'
      });
    }

    if (postResult.data.length === 0) {
      return res.status(404).json({
        success: false,
        message: 'Blog post not found'
      });
    }

    res.json({
      success: true,
      data: { post: postResult.data[0] }
    });

  } catch (error) {
    console.error('Get blog post error:', error);
    res.status(500).json({
      success: false,
      message: 'Server error fetching blog post'
    });
  }
});

// Create new blog post (admin/pastor only)
router.post('/', authenticate, authorize('admin', 'pastor'), async (req, res) => {
  try {
    const {
      title,
      slug,
      excerpt,
      content,
      featured_image_url,
      category,
      tags,
      meta_title,
      meta_description,
      is_featured,
      status
    } = req.body;

    const result = await query(
      `INSERT INTO blog_posts (
        title, slug, excerpt, content, featured_image_url, category,
        tags, meta_title, meta_description, is_featured, status,
        author_id, published_at
      ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)`,
      [
        title, slug, excerpt, content, featured_image_url, category,
        JSON.stringify(tags || []), meta_title, meta_description,
        is_featured || false, status, req.user.id,
        status === 'published' ? new Date() : null
      ]
    );

    if (!result.success) {
      return res.status(500).json({
        success: false,
        message: 'Failed to create blog post'
      });
    }

    // Get the created post
    const createdPost = await query(
      'SELECT * FROM blog_posts WHERE id = ?',
      [result.data.insertId]
    );

    res.status(201).json({
      success: true,
      message: 'Blog post created successfully',
      data: { post: createdPost.data[0] }
    });

  } catch (error) {
    console.error('Create blog post error:', error);
    res.status(500).json({
      success: false,
      message: 'Server error creating blog post'
    });
  }
});

// Update blog post (admin/pastor only)
router.put('/:id', authenticate, authorize('admin', 'pastor'), async (req, res) => {
  try {
    const { id } = req.params;
    const {
      title,
      slug,
      excerpt,
      content,
      featured_image_url,
      category,
      tags,
      meta_title,
      meta_description,
      is_featured,
      status
    } = req.body;

    // Check if post exists
    const existingPost = await query('SELECT id, status FROM blog_posts WHERE id = ?', [id]);

    if (!existingPost.success || existingPost.data.length === 0) {
      return res.status(404).json({
        success: false,
        message: 'Blog post not found'
      });
    }

    const currentStatus = existingPost.data[0].status;
    const published_at = status === 'published' && currentStatus !== 'published' ? new Date() : null;

    const result = await query(
      `UPDATE blog_posts SET 
        title = ?, slug = ?, excerpt = ?, content = ?, featured_image_url = ?,
        category = ?, tags = ?, meta_title = ?, meta_description = ?,
        is_featured = ?, status = ?, published_at = COALESCE(?, published_at)
      WHERE id = ?`,
      [
        title, slug, excerpt, content, featured_image_url, category,
        JSON.stringify(tags || []), meta_title, meta_description,
        is_featured || false, status, published_at, id
      ]
    );

    if (!result.success) {
      return res.status(500).json({
        success: false,
        message: 'Failed to update blog post'
      });
    }

    // Get updated post
    const updatedPost = await query('SELECT * FROM blog_posts WHERE id = ?', [id]);

    res.json({
      success: true,
      message: 'Blog post updated successfully',
      data: { post: updatedPost.data[0] }
    });

  } catch (error) {
    console.error('Update blog post error:', error);
    res.status(500).json({
      success: false,
      message: 'Server error updating blog post'
    });
  }
});

// Delete blog post (admin only)
router.delete('/:id', authenticate, authorize('admin'), async (req, res) => {
  try {
    const { id } = req.params;

    const result = await query('DELETE FROM blog_posts WHERE id = ?', [id]);

    if (!result.success) {
      return res.status(500).json({
        success: false,
        message: 'Failed to delete blog post'
      });
    }

    res.json({
      success: true,
      message: 'Blog post deleted successfully'
    });

  } catch (error) {
    console.error('Delete blog post error:', error);
    res.status(500).json({
      success: false,
      message: 'Server error deleting blog post'
    });
  }
});

module.exports = router;
