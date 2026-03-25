const express = require('express');
const { query, transaction } = require('../config/database');
const { authenticate, authorize, optionalAuth } = require('../middleware/auth');
const { validateEvent } = require('../middleware/validation');

const router = express.Router();

// Get all events (public)
router.get('/', optionalAuth, async (req, res) => {
  try {
    const { 
      page = 1, 
      limit = 10, 
      status = 'upcoming', 
      event_type,
      search 
    } = req.query;

    const offset = (page - 1) * limit;
    let whereClause = 'WHERE e.status = ?';
    let params = [status];

    if (event_type) {
      whereClause += ' AND e.event_type = ?';
      params.push(event_type);
    }

    if (search) {
      whereClause += ' AND (e.title LIKE ? OR e.description LIKE ?)';
      params.push(`%${search}%`, `%${search}%`);
    }

    const eventsQuery = `
      SELECT 
        e.*,
        u.first_name as created_by_first_name,
        u.last_name as created_by_last_name,
        (SELECT COUNT(*) FROM event_registrations er WHERE er.event_id = e.id AND er.status = 'registered') as registered_count
      FROM events e
      LEFT JOIN users u ON e.created_by = u.id
      ${whereClause}
      ORDER BY e.event_date ASC
      LIMIT ? OFFSET ?
    `;

    const eventsResult = await query(eventsQuery, [...params, parseInt(limit), offset]);

    // Get total count for pagination
    const countQuery = `
      SELECT COUNT(*) as total
      FROM events e
      ${whereClause}
    `;

    const countResult = await query(countQuery, params);

    if (!eventsResult.success || !countResult.success) {
      return res.status(500).json({
        success: false,
        message: 'Failed to fetch events'
      });
    }

    res.json({
      success: true,
      data: {
        events: eventsResult.data,
        pagination: {
          current_page: parseInt(page),
          per_page: parseInt(limit),
          total: countResult.data[0].total,
          total_pages: Math.ceil(countResult.data[0].total / limit)
        }
      }
    });

  } catch (error) {
    console.error('Get events error:', error);
    res.status(500).json({
      success: false,
      message: 'Server error fetching events'
    });
  }
});

// Get single event
router.get('/:id', optionalAuth, async (req, res) => {
  try {
    const { id } = req.params;

    const eventQuery = `
      SELECT 
        e.*,
        u.first_name as created_by_first_name,
        u.last_name as created_by_last_name,
        (SELECT COUNT(*) FROM event_registrations er WHERE er.event_id = e.id AND er.status = 'registered') as registered_count
      FROM events e
      LEFT JOIN users u ON e.created_by = u.id
      WHERE e.id = ?
    `;

    const eventResult = await query(eventQuery, [id]);

    if (!eventResult.success) {
      return res.status(500).json({
        success: false,
        message: 'Failed to fetch event'
      });
    }

    if (eventResult.data.length === 0) {
      return res.status(404).json({
        success: false,
        message: 'Event not found'
      });
    }

    res.json({
      success: true,
      data: { event: eventResult.data[0] }
    });

  } catch (error) {
    console.error('Get event error:', error);
    res.status(500).json({
      success: false,
      message: 'Server error fetching event'
    });
  }
});

// Create new event (admin/pastor only)
router.post('/', authenticate, authorize('admin', 'pastor'), validateEvent, async (req, res) => {
  try {
    const {
      title,
      description,
      event_date,
      end_date,
      location,
      event_type,
      max_attendees,
      is_recurring,
      recurring_pattern,
      featured_image_url
    } = req.body;

    const result = await query(
      `INSERT INTO events (
        title, description, event_date, end_date, location, event_type,
        max_attendees, is_recurring, recurring_pattern, featured_image_url,
        created_by
      ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)`,
      [
        title, description, event_date, end_date, location, event_type,
        max_attendees, is_recurring, JSON.stringify(recurring_pattern || {}),
        featured_image_url, req.user.id
      ]
    );

    if (!result.success) {
      return res.status(500).json({
        success: false,
        message: 'Failed to create event'
      });
    }

    // Get the created event
    const createdEvent = await query(
      'SELECT * FROM events WHERE id = ?',
      [result.data.insertId]
    );

    res.status(201).json({
      success: true,
      message: 'Event created successfully',
      data: { event: createdEvent.data[0] }
    });

  } catch (error) {
    console.error('Create event error:', error);
    res.status(500).json({
      success: false,
      message: 'Server error creating event'
    });
  }
});

// Update event (admin/pastor only)
router.put('/:id', authenticate, authorize('admin', 'pastor'), validateEvent, async (req, res) => {
  try {
    const { id } = req.params;
    const {
      title,
      description,
      event_date,
      end_date,
      location,
      event_type,
      max_attendees,
      is_recurring,
      recurring_pattern,
      featured_image_url,
      status
    } = req.body;

    // Check if event exists
    const existingEvent = await query('SELECT id FROM events WHERE id = ?', [id]);

    if (!existingEvent.success || existingEvent.data.length === 0) {
      return res.status(404).json({
        success: false,
        message: 'Event not found'
      });
    }

    const result = await query(
      `UPDATE events SET 
        title = ?, description = ?, event_date = ?, end_date = ?, location = ?,
        event_type = ?, max_attendees = ?, is_recurring = ?, 
        recurring_pattern = ?, featured_image_url = ?, status = ?
      WHERE id = ?`,
      [
        title, description, event_date, end_date, location, event_type,
        max_attendees, is_recurring, JSON.stringify(recurring_pattern || {}),
        featured_image_url, status, id
      ]
    );

    if (!result.success) {
      return res.status(500).json({
        success: false,
        message: 'Failed to update event'
      });
    }

    // Get updated event
    const updatedEvent = await query('SELECT * FROM events WHERE id = ?', [id]);

    res.json({
      success: true,
      message: 'Event updated successfully',
      data: { event: updatedEvent.data[0] }
    });

  } catch (error) {
    console.error('Update event error:', error);
    res.status(500).json({
      success: false,
      message: 'Server error updating event'
    });
  }
});

// Delete event (admin only)
router.delete('/:id', authenticate, authorize('admin'), async (req, res) => {
  try {
    const { id } = req.params;

    // Check if event exists
    const existingEvent = await query('SELECT id FROM events WHERE id = ?', [id]);

    if (!existingEvent.success || existingEvent.data.length === 0) {
      return res.status(404).json({
        success: false,
        message: 'Event not found'
      });
    }

    const result = await query('DELETE FROM events WHERE id = ?', [id]);

    if (!result.success) {
      return res.status(500).json({
        success: false,
        message: 'Failed to delete event'
      });
    }

    res.json({
      success: true,
      message: 'Event deleted successfully'
    });

  } catch (error) {
    console.error('Delete event error:', error);
    res.status(500).json({
      success: false,
      message: 'Server error deleting event'
    });
  }
});

// Register for event
router.post('/:id/register', async (req, res) => {
  try {
    const { id } = req.params;
    const {
      first_name,
      last_name,
      email,
      phone,
      number_of_guests = 1,
      special_requests
    } = req.body;

    // Check if event exists and is upcoming
    const eventResult = await query(
      'SELECT * FROM events WHERE id = ? AND status = "upcoming"',
      [id]
    );

    if (!eventResult.success || eventResult.data.length === 0) {
      return res.status(404).json({
        success: false,
        message: 'Event not found or not available for registration'
      });
    }

    const event = eventResult.data[0];

    // Check if already registered
    const existingRegistration = await query(
      'SELECT id FROM event_registrations WHERE event_id = ? AND email = ?',
      [id, email]
    );

    if (!existingRegistration.success) {
      return res.status(500).json({
        success: false,
        message: 'Database error checking registration'
      });
    }

    if (existingRegistration.data.length > 0) {
      return res.status(400).json({
        success: false,
        message: 'Already registered for this event'
      });
    }

    // Check if event has max attendees limit
    if (event.max_attendees) {
      const currentRegistrations = await query(
        'SELECT COUNT(*) as count FROM event_registrations WHERE event_id = ? AND status = "registered"',
        [id]
      );

      if (!currentRegistrations.success) {
        return res.status(500).json({
          success: false,
          message: 'Database error checking registrations'
        });
      }

      const totalAttendees = currentRegistrations.data[0].count + number_of_guests;
      
      if (totalAttendees > event.max_attendees) {
        return res.status(400).json({
          success: false,
          message: 'Event is fully booked'
        });
      }
    }

    // Create registration
    const result = await query(
      `INSERT INTO event_registrations (
        event_id, first_name, last_name, email, phone, number_of_guests, special_requests
      ) VALUES (?, ?, ?, ?, ?, ?, ?)`,
      [id, first_name, last_name, email, phone, number_of_guests, special_requests]
    );

    if (!result.success) {
      return res.status(500).json({
        success: false,
        message: 'Failed to register for event'
      });
    }

    res.status(201).json({
      success: true,
      message: 'Registration successful',
      data: { registration_id: result.data.insertId }
    });

  } catch (error) {
    console.error('Event registration error:', error);
    res.status(500).json({
      success: false,
      message: 'Server error during event registration'
    });
  }
});

// Get event registrations (admin/pastor only)
router.get('/:id/registrations', authenticate, authorize('admin', 'pastor'), async (req, res) => {
  try {
    const { id } = req.params;
    const { page = 1, limit = 20 } = req.query;
    const offset = (page - 1) * limit;

    const registrationsQuery = `
      SELECT * FROM event_registrations 
      WHERE event_id = ?
      ORDER BY registration_date DESC
      LIMIT ? OFFSET ?
    `;

    const registrationsResult = await query(registrationsQuery, [id, parseInt(limit), offset]);

    // Get total count
    const countResult = await query(
      'SELECT COUNT(*) as total FROM event_registrations WHERE event_id = ?',
      [id]
    );

    if (!registrationsResult.success || !countResult.success) {
      return res.status(500).json({
        success: false,
        message: 'Failed to fetch registrations'
      });
    }

    res.json({
      success: true,
      data: {
        registrations: registrationsResult.data,
        pagination: {
          current_page: parseInt(page),
          per_page: parseInt(limit),
          total: countResult.data[0].total,
          total_pages: Math.ceil(countResult.data[0].total / limit)
        }
      }
    });

  } catch (error) {
    console.error('Get registrations error:', error);
    res.status(500).json({
      success: false,
      message: 'Server error fetching registrations'
    });
  }
});

module.exports = router;
