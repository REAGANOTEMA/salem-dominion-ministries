const multer = require('multer');
const path = require('path');
const fs = require('fs');

// Ensure upload directory exists
const uploadDir = path.join(__dirname, '../uploads');
if (!fs.existsSync(uploadDir)) {
  fs.mkdirSync(uploadDir, { recursive: true });
}

// Create subdirectories
const subdirs = ['events', 'sermons', 'gallery', 'blog', 'users'];
subdirs.forEach(dir => {
  const fullPath = path.join(uploadDir, dir);
  if (!fs.existsSync(fullPath)) {
    fs.mkdirSync(fullPath, { recursive: true });
  }
});

// Storage configuration
const storage = multer.diskStorage({
  destination: (req, file, cb) => {
    let uploadPath = uploadDir;
    
    // Determine subdirectory based on file type or route
    if (req.originalUrl.includes('/events')) {
      uploadPath = path.join(uploadDir, 'events');
    } else if (req.originalUrl.includes('/sermons')) {
      uploadPath = path.join(uploadDir, 'sermons');
    } else if (req.originalUrl.includes('/gallery')) {
      uploadPath = path.join(uploadDir, 'gallery');
    } else if (req.originalUrl.includes('/blog')) {
      uploadPath = path.join(uploadDir, 'blog');
    } else if (req.originalUrl.includes('/users')) {
      uploadPath = path.join(uploadDir, 'users');
    }
    
    cb(null, uploadPath);
  },
  filename: (req, file, cb) => {
    // Generate unique filename
    const uniqueSuffix = Date.now() + '-' + Math.round(Math.random() * 1E9);
    const ext = path.extname(file.originalname);
    const name = path.basename(file.originalname, ext);
    cb(null, `${name}-${uniqueSuffix}${ext}`);
  }
});

// File filter
const fileFilter = (req, file, cb) => {
  // Allowed file types
  const allowedTypes = {
    'image/jpeg': true,
    'image/jpg': true,
    'image/png': true,
    'image/gif': true,
    'image/webp': true,
    'image/svg+xml': true,
    'video/mp4': true,
    'video/webm': true,
    'video/ogg': true,
    'audio/mpeg': true,
    'audio/wav': true,
    'audio/ogg': true,
    'application/pdf': true
  };
  
  if (allowedTypes[file.mimetype]) {
    cb(null, true);
  } else {
    cb(new Error('Invalid file type. Only images, videos, audio, and PDF files are allowed.'), false);
  }
};

// Multer configuration
const upload = multer({
  storage: storage,
  fileFilter: fileFilter,
  limits: {
    fileSize: 10 * 1024 * 1024, // 10MB limit
    files: 5 // Maximum 5 files per request
  }
});

// Single file upload middleware
const uploadSingle = (fieldName) => {
  return (req, res, next) => {
    const singleUpload = upload.single(fieldName);
    
    singleUpload(req, res, (err) => {
      if (err instanceof multer.MulterError) {
        if (err.code === 'LIMIT_FILE_SIZE') {
          return res.status(400).json({
            success: false,
            message: 'File too large. Maximum size is 10MB.'
          });
        }
        if (err.code === 'LIMIT_FILE_COUNT') {
          return res.status(400).json({
            success: false,
            message: 'Too many files. Maximum is 5 files per request.'
          });
        }
        return res.status(400).json({
          success: false,
          message: 'File upload error: ' + err.message
        });
      } else if (err) {
        return res.status(400).json({
          success: false,
          message: err.message
        });
      }
      
      // Add file URL to request
      if (req.file) {
        const relativePath = path.relative(path.join(__dirname, '../uploads'), req.file.path);
        req.fileUrl = `/uploads/${relativePath.replace(/\\/g, '/')}`;
      }
      
      next();
    });
  };
};

// Multiple files upload middleware
const uploadMultiple = (fieldName, maxCount = 5) => {
  return (req, res, next) => {
    const multiUpload = upload.array(fieldName, maxCount);
    
    multiUpload(req, res, (err) => {
      if (err instanceof multer.MulterError) {
        if (err.code === 'LIMIT_FILE_SIZE') {
          return res.status(400).json({
            success: false,
            message: 'File too large. Maximum size is 10MB.'
          });
        }
        if (err.code === 'LIMIT_FILE_COUNT') {
          return res.status(400).json({
            success: false,
            message: `Too many files. Maximum is ${maxCount} files per request.`
          });
        }
        return res.status(400).json({
          success: false,
          message: 'File upload error: ' + err.message
        });
      } else if (err) {
        return res.status(400).json({
          success: false,
          message: err.message
        });
      }
      
      // Add file URLs to request
      if (req.files && req.files.length > 0) {
        req.fileUrls = req.files.map(file => {
          const relativePath = path.relative(path.join(__dirname, '../uploads'), file.path);
          return `/uploads/${relativePath.replace(/\\/g, '/')}`;
        });
      }
      
      next();
    });
  };
};

// Delete file utility
const deleteFile = (filePath) => {
  try {
    const fullPath = path.join(__dirname, '../uploads', filePath.replace('/uploads/', ''));
    if (fs.existsSync(fullPath)) {
      fs.unlinkSync(fullPath);
      return true;
    }
    return false;
  } catch (error) {
    console.error('Error deleting file:', error);
    return false;
  }
};

// Get file info utility
const getFileInfo = (filePath) => {
  try {
    const fullPath = path.join(__dirname, '../uploads', filePath.replace('/uploads/', ''));
    if (fs.existsSync(fullPath)) {
      const stats = fs.statSync(fullPath);
      return {
        size: stats.size,
        created: stats.birthtime,
        modified: stats.mtime,
        isFile: stats.isFile(),
        extension: path.extname(fullPath)
      };
    }
    return null;
  } catch (error) {
    console.error('Error getting file info:', error);
    return null;
  }
};

module.exports = {
  uploadSingle,
  uploadMultiple,
  deleteFile,
  getFileInfo
};
