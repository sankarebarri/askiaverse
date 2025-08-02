# Environment File Management Guide

## Overview

This guide explains how to properly manage environment files (`.env`) for different deployment environments while maintaining security and following industry standards.

## File Structure

```
askiaverse/
├── .env.example          # Template file (committed to git)
├── .env                  # Local development (NOT committed)
├── .env.production       # Production template (NOT committed)
└── .env.staging         # Staging template (NOT committed)
```

## Security Rules

### ✅ **DO Commit to Git:**
- `.env.example` - Template with dummy values
- `.gitignore` - Ensures `.env` files are ignored

### ❌ **NEVER Commit to Git:**
- `.env` - Contains real credentials
- `.env.production` - Production secrets
- `.env.staging` - Staging secrets
- Any file with real passwords, API keys, or database credentials

## Environment File Templates

### 1. `.env.example` (Template File)

Create this file in your project root:

```env
# ===================================================================
# ASKIAVERSE - Environment Configuration Template
# Copy this file to .env and fill in your actual values
# ===================================================================

# Application Environment
APP_ENV=local
APP_DEBUG=true
APP_URL=http://askiaverse.local

# Asset Configuration
ASSET_PREFIX=/assets/
ASSET_URL=

# Database Configuration
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=askiaverse
DB_USERNAME=root
DB_PASSWORD=

# Security
APP_KEY=base64:your-32-character-random-key-here

# Mail Configuration
MAIL_MAILER=smtp
MAIL_HOST=mailpit
MAIL_PORT=1025
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_FROM_ADDRESS="hello@example.com"
```

### 2. Local Development (`.env`)

```env
# Local Development Environment
APP_ENV=local
APP_DEBUG=true
APP_URL=http://askiaverse.local
ASSET_PREFIX=/assets/
DB_HOST=127.0.0.1
DB_DATABASE=askiaverse
DB_USERNAME=root
DB_PASSWORD=
APP_KEY=base64:local-development-key-here
```

### 3. Hostinger Production (`.env` on server)

```env
# Production Environment
APP_ENV=production
APP_DEBUG=false
APP_URL=https://askiagames.com
ASSET_PREFIX=/public/assets/
DB_HOST=localhost
DB_DATABASE=your_production_db
DB_USERNAME=your_db_user
DB_PASSWORD=your_secure_password
APP_KEY=base64:production-random-key-here
```

## Setup Process

### For New Developers

1. **Clone the repository**
   ```bash
   git clone https://github.com/your-username/askiaverse.git
   cd askiaverse
   ```

2. **Copy the template**
   ```bash
   cp .env.example .env
   ```

3. **Edit the `.env` file**
   - Update database credentials
   - Set correct URLs
   - Generate a random APP_KEY

4. **Install dependencies**
   ```bash
   composer install
   npm install
   ```

5. **Build assets**
   ```bash
   npm run build
   ```

### For Production Deployment

1. **Upload files to server**
   ```bash
   # Upload all files to public_html/
   ```

2. **Create production `.env`**
   ```bash
   # On the server, create .env file
   cp .env.example .env
   ```

3. **Edit production `.env`**
   - Set `APP_ENV=production`
   - Set `APP_DEBUG=false`
   - Use production database credentials
   - Set `ASSET_PREFIX=/public/assets/`
   - Generate a secure APP_KEY

4. **Set proper permissions**
   ```bash
   chmod 644 .env
   chmod 755 public/
   ```

## Environment-Specific Configurations

### Local Development
- **Asset Path**: `/assets/`
- **Database**: Local MySQL/XAMPP
- **Debug**: Enabled
- **URL**: `http://askiaverse.local`

### Hostinger Production
- **Asset Path**: `/public/assets/`
- **Database**: Hostinger MySQL
- **Debug**: Disabled
- **URL**: `https://askiagames.com`

### Staging (Optional)
- **Asset Path**: `/assets/` or `/public/assets/`
- **Database**: Staging database
- **Debug**: Enabled for testing
- **URL**: `https://staging.askiagames.com`

## Security Best Practices

### 1. **Generate Secure Keys**
```bash
# Generate a random 32-character base64 key
openssl rand -base64 32
```

### 2. **Use Strong Passwords**
- Database passwords: 16+ characters, mixed case, numbers, symbols
- API keys: Use secure random generators
- Never use default passwords

### 3. **Environment Separation**
- Different databases for each environment
- Different API keys for each environment
- Never share production credentials

### 4. **Access Control**
```bash
# Set proper file permissions
chmod 644 .env
chmod 755 public/
chmod 644 public/.htaccess
```

## Troubleshooting

### Common Issues

1. **"Configuration not found"**
   - Check if `.env` file exists
   - Verify file permissions
   - Ensure `config/app.php` loads correctly

2. **"Assets not loading"**
   - Check `ASSET_PREFIX` value
   - Verify assets are compiled (`npm run build`)
   - Check file permissions on `public/assets/`

3. **"Database connection failed"**
   - Verify database credentials
   - Check if database server is running
   - Ensure database exists

### Debug Commands

```bash
# Check if .env is loaded
php -r "require 'vendor/autoload.php'; echo env('APP_ENV');"

# Verify asset paths
php -r "require 'src/Shared/Config.php'; echo \Shared\Config::asset('app.css');"

# Test database connection
php public/dbtest.php
```

## Deployment Checklist

### Before Deployment
- [ ] `.env.example` is up to date
- [ ] `.gitignore` excludes `.env` files
- [ ] Production credentials are ready
- [ ] Assets are compiled (`npm run build`)

### During Deployment
- [ ] Upload all files to server
- [ ] Create `.env` file with production values
- [ ] Set correct file permissions
- [ ] Test database connection
- [ ] Verify assets are loading

### After Deployment
- [ ] Test all functionality
- [ ] Check error logs
- [ ] Verify security headers
- [ ] Test asset caching

## Backup Strategy

### Environment Files
- Keep encrypted backups of production `.env`
- Store in secure password manager
- Never email or share via chat

### Database
- Regular automated backups
- Test restore procedures
- Store backups securely

This approach ensures your application is secure, maintainable, and follows industry standards for environment management. 