# Production Deployment Guide - Hostinger

## Overview

This guide walks you through deploying Askiaverse to your Hostinger hosting environment with proper production configuration.

## Pre-Deployment Checklist

- [ ] All code is tested locally
- [ ] Assets are compiled (`npm run build`)
- [ ] Database schema is ready
- [ ] Hostinger hosting plan is active
- [ ] Domain is configured

## Step 1: Prepare Your Local Environment

### 1.1 Generate Production Environment File

```bash
# In your local project directory
php scripts/setup-env.php production
```

This creates a `.env` file with production settings.

### 1.2 Build Production Assets

```bash
npm run build
```

This compiles and optimizes your CSS/JS for production.

### 1.3 Verify Local Production Build

```bash
# Test the production configuration locally
php public/test-config.php
```

You should see:
- ✅ Production environment detected
- ✅ Asset prefix correctly set for production (`/public/assets/`)

## Step 2: Upload to Hostinger

### 2.1 File Structure on Hostinger

Your files should be uploaded to:
```
public_html/
├── .env                    # Production environment file
├── app/
├── config/
├── database/
├── public/                 # This is your web root
│   ├── index.php
│   ├── assets/            # Compiled assets
│   └── .htaccess
├── resources/
├── routes/
├── src/
├── vendor/
├── composer.json
└── package.json
```

### 2.2 Upload Methods

**Option A: FTP/SFTP**
1. Connect to your Hostinger FTP
2. Upload all files to `public_html/`
3. Ensure `public/` folder is accessible

**Option B: File Manager**
1. Use Hostinger's File Manager
2. Upload files to `public_html/`
3. Extract if needed

## Step 3: Configure Production Environment

### 3.1 Create Production .env File

On your Hostinger server, create a `.env` file in the root directory:

```env
# ===================================================================
# ASKIAVERSE - Production Environment (Hostinger)
# ===================================================================

# Application Environment
APP_ENV=production
APP_DEBUG=false
APP_URL=https://askiagames.com

# Asset Configuration
ASSET_PREFIX=/public/assets/

# Database Configuration (Get these from Hostinger control panel)
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=your_actual_database_name
DB_USERNAME=your_actual_db_username
DB_PASSWORD=your_actual_db_password

# Security
APP_KEY=base64:your-generated-random-key

# Mail Configuration
MAIL_MAILER=smtp
MAIL_HOST=mail.askiagames.com
MAIL_PORT=587
MAIL_USERNAME=your_email@askiagames.com
MAIL_PASSWORD=your_email_password
MAIL_FROM_ADDRESS="noreply@askiagames.com"
```

### 3.2 Get Hostinger Database Credentials

1. Log into Hostinger control panel
2. Go to "Databases" → "MySQL Databases"
3. Note down:
   - Database name
   - Username
   - Password
   - Host (usually `localhost`)

### 3.3 Generate Secure APP_KEY

```bash
# On your local machine, generate a secure key
php -r "echo 'base64:' . base64_encode(random_bytes(32));"
```

Copy the output and use it as your `APP_KEY`.

## Step 4: Set Up Database

### 4.1 Create Database Tables

If you have database migrations, run them:

```bash
# If you have access to SSH/CLI on Hostinger
php migrate.php
```

Or manually create tables using Hostinger's phpMyAdmin.

### 4.2 Import Sample Data (Optional)

If you have seed data, import it through phpMyAdmin.

## Step 5: Configure Web Server

### 5.1 Set Document Root

In Hostinger control panel:
1. Go to "Websites" → "Manage"
2. Set document root to `public_html/public/`

### 5.2 Verify .htaccess Files

Ensure these files are uploaded and have correct permissions:
- `public_html/.htaccess` (644)
- `public_html/public/.htaccess` (644)

### 5.3 Set File Permissions

```bash
# Set proper permissions (if you have SSH access)
chmod 644 .env
chmod 755 public/
chmod 644 public/.htaccess
chmod 755 public/assets/
```

## Step 6: Test Production Deployment

### 6.1 Test Configuration

Visit: `https://askiagames.com/public/test-config.php`

You should see:
- ✅ Production environment detected
- ✅ Asset prefix correctly set for production
- ✅ CSS loading correctly

### 6.2 Test Main Application

Visit: `https://askiagames.com/public/index.php`

You should see your styled "ASKIAGAME COMING SOON" page.

### 6.3 Test Asset Loading

Check browser developer tools:
- CSS should load from `/public/assets/app.css`
- No 404 errors for assets

## Step 7: Security Hardening

### 7.1 Remove Test Files

```bash
# Remove test files from production
rm public/test-config.php
rm public/dbtest.php
rm public/config-test.php
```

### 7.2 Verify .env Protection

Ensure `.env` file is not accessible via web:
- Visit `https://askiagames.com/.env`
- Should return 403 Forbidden or 404 Not Found

### 7.3 Enable HTTPS

In Hostinger control panel:
1. Go to "SSL" section
2. Enable SSL certificate
3. Force HTTPS redirect

## Step 8: Monitoring & Maintenance

### 8.1 Set Up Error Logging

Check Hostinger error logs:
- cPanel → "Error Logs"
- Monitor for any PHP errors

### 8.2 Performance Monitoring

- Use browser developer tools to check load times
- Monitor server response times
- Check asset caching headers

### 8.3 Regular Backups

- Set up automated database backups
- Keep backups of your `.env` file (securely)
- Regular code backups

## Troubleshooting

### Common Issues

1. **"Assets not loading"**
   - Check `ASSET_PREFIX=/public/assets/`
   - Verify assets are uploaded to `public_html/public/assets/`
   - Check file permissions

2. **"Database connection failed"**
   - Verify database credentials in `.env`
   - Check if database exists in Hostinger
   - Ensure database user has proper permissions

3. **"Configuration not found"**
   - Check if `.env` file exists in root directory
   - Verify file permissions (644)
   - Check for syntax errors in `.env`

4. **"500 Internal Server Error"**
   - Check Hostinger error logs
   - Verify PHP version compatibility
   - Check file permissions

### Debug Commands

If you have SSH access to Hostinger:

```bash
# Check PHP version
php -v

# Test configuration loading
php -r "require 'config/config.php'; echo 'Config loaded successfully';"

# Test database connection
php -r "require 'config/config.php'; echo 'Database config: ' . env('DB_HOST');"
```

## Environment Comparison

| Setting | Local | Production |
|---------|-------|------------|
| `APP_ENV` | `local` | `production` |
| `APP_DEBUG` | `true` | `false` |
| `ASSET_PREFIX` | `/assets/` | `/public/assets/` |
| `APP_URL` | `http://askiaverse.local` | `https://askiagames.com` |
| Database | Local MySQL | Hostinger MySQL |

## Next Steps

After successful deployment:

1. **Set up monitoring** for uptime and performance
2. **Configure backups** for database and files
3. **Set up staging environment** for testing updates
4. **Plan CI/CD pipeline** for automated deployments
5. **Document deployment process** for team members

This ensures your Askiaverse application runs smoothly in production with proper security and performance optimizations. 