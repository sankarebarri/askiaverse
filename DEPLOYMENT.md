# Deployment Guide - Industry Standard Setup

## Overview

This guide explains how to properly deploy the Askiaverse application following industry standards.

## File Structure (Industry Standard)

```
askiaverse/
├── app/                    # Application logic
├── config/                 # Configuration files
│   ├── app.php            # Main application config
│   └── database.php       # Database config
├── database/              # Database migrations and seeds
├── public/                # Web server document root
│   ├── index.php         # Front controller
│   ├── assets/           # Compiled assets (CSS, JS, images)
│   └── .htaccess         # URL rewriting rules
├── resources/             # Source assets (CSS, JS, views)
├── routes/                # Route definitions
├── src/                   # Core application code
├── tests/                 # Test files
├── vendor/                # Composer dependencies
├── .env                   # Environment variables (not in git)
├── .env.example          # Example environment file
└── composer.json         # PHP dependencies
```

## Environment Configuration

### Local Development (.env)
```env
APP_ENV=local
APP_DEBUG=true
APP_URL=http://askiaverse.local
ASSET_PREFIX=/assets/
DB_HOST=127.0.0.1
DB_DATABASE=askiaverse
DB_USERNAME=root
DB_PASSWORD=
```

### Hostinger Production (.env)
```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://askiagames.com
ASSET_PREFIX=/public/assets/
DB_HOST=localhost
DB_DATABASE=your_database_name
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

## Deployment Steps

### 1. Local Development Setup
1. Copy `.env.example` to `.env`
2. Set `ASSET_PREFIX=/assets/`
3. Run `npm run build` to compile assets
4. Configure your local web server to point to the `public/` directory

### 2. Hostinger Deployment
1. Upload all files to `public_html/`
2. Create `.env` file in the root with production settings
3. Set `ASSET_PREFIX=/public/assets/`
4. Run `npm run build` locally and upload the compiled assets
5. Ensure `public_html/public/` is your web root

## Web Server Configuration

### Apache (.htaccess in public/)
```apache
RewriteEngine On
RewriteCond %{REQUEST_FILENAME} !-d
RewriteCond %{REQUEST_FILENAME} !-f
RewriteRule ^ index.php [L]
```

### Nginx
```nginx
location / {
    try_files $uri $uri/ /index.php?$query_string;
}
```

## Asset Management

### Development
- Assets are served from `/assets/`
- Vite handles hot reloading
- Use `npm run dev` for development

### Production
- Assets are compiled to `public/assets/`
- Use `npm run build` to compile
- Assets are served with cache headers

## Security Best Practices

1. **Environment Variables**: Never commit `.env` files
2. **Document Root**: Only expose the `public/` directory
3. **Asset Caching**: Use proper cache headers for static assets
4. **Security Headers**: Implement XSS protection, CSRF tokens
5. **Database**: Use prepared statements, validate all inputs

## Performance Optimization

1. **Asset Compression**: Enable gzip compression
2. **Caching**: Use browser and server-side caching
3. **CDN**: Consider using a CDN for assets in production
4. **Database**: Optimize queries, use indexes
5. **Images**: Optimize and use appropriate formats

## Monitoring and Maintenance

1. **Error Logging**: Implement proper error logging
2. **Performance Monitoring**: Monitor response times
3. **Security Updates**: Keep dependencies updated
4. **Backups**: Regular database and file backups
5. **SSL**: Always use HTTPS in production 