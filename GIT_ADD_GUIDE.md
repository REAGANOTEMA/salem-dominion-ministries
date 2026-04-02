# Git Add Commands Guide

## 🚀 Git Add Commands for Salem Dominion Ministries

### 📋 Current Status
You have many untracked files that need to be added to git before committing.

### 🔧 Git Add Commands

#### 1. **Add Specific Files**
```bash
git add filename.php
git add config.php
git add index.php
git add leadership.php
```

#### 2. **Add Multiple Files**
```bash
git add config.php index.php leadership.php db.php
```

#### 3. **Add All Files in Current Directory**
```bash
git add .
```

#### 4. **Add All PHP Files**
```bash
git add *.php
```

#### 5. **Add All Files in a Directory**
```bash
git add components/
git add assets/
git add uploads/
```

#### 6. **Add Files with Pattern**
```bash
git add *_perfect.php
git add *_complete.php
git add *_fixed.php
```

### 📁 Recommended Files to Add

#### **Core Files (Essential):**
```bash
git add config.php db.php index.php leadership.php
```

#### **Production Files:**
```bash
git add index_production_ready.php leadership.php dashboard_no_paths.php
```

#### **Components:**
```bash
git add components/
```

#### **Perfect Components:**
```bash
git add perfect_error_free.php
git add components/perfect_footer.php
git add components/developer_whatsapp.php
git add components/universal_nav_perfect.php
```

### 🎯 Quick Start Commands

#### **Option 1: Add All Files (Recommended for First Time)**
```bash
git add .
```

#### **Option 2: Add Only Essential Files**
```bash
git add config.php db.php index.php leadership.php dashboard.php login.php register.php
git add components/
git add assets/
```

#### **Option 3: Add Production Files Only**
```bash
git add index_production_ready.php leadership.php dashboard_no_paths.php
git add perfect_error_free.php
git add components/perfect_footer.php
git add components/developer_whatsapp.php
git add components/universal_nav_perfect.php
```

### 📝 After Adding Files

#### **Check Status:**
```bash
git status
```

#### **Commit Changes:**
```bash
git commit -m "Initial commit - Salem Dominion Ministries website"
```

#### **Set Up Remote (if needed):**
```bash
git remote add origin https://github.com/yourusername/salem-dominion-ministries.git
```

#### **Push to GitHub:**
```bash
git push -u origin main
```

### ⚠️ Important Notes

1. **First Time**: Use `git add .` to add all files initially
2. **Large Repository**: Add files in batches if you have many files
3. **Ignore Files**: Create `.gitignore` file to exclude unnecessary files
4. **Check Before Commit**: Always run `git status` before committing

### 🚀 Quick Commands to Run Now

```bash
# Add all files
git add .

# Check status
git status

# Commit
git commit -m "Complete Salem Dominion Ministries website with all features"

# Push (if remote is set up)
git push
```

### 📋 What Files Will Be Added?

Based on your git status, you'll be adding:
- ✅ Core PHP files (index.php, leadership.php, etc.)
- ✅ Configuration files (config.php, db.php)
- ✅ Components directory
- ✅ Assets directory
- ✅ Production-ready files
- ✅ Error handling files
- ✅ PWA files
- ✅ Documentation files

### 🎯 Recommended Action

**For now, run this command:**
```bash
git add .
```

This will add all your untracked files to git staging area, then you can commit them all at once!
