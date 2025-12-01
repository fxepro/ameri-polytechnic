# SSL Certificate Setup Guide

This guide will help you set up SSL certificates for `ameripolytech.com` and `api.ameripolytech.com` using Let's Encrypt (Certbot).

## Prerequisites

- Domain DNS records pointing to your Oracle VM:
  - `ameripolytech.com` → `129.146.195.38`
  - `api.ameripolytech.com` → `129.146.195.38`
- Nginx installed and running
- Ports 80 and 443 open in Oracle Cloud Security Lists and firewall

## Step 1: Install Certbot

On your app VM:

```bash
# For Oracle Linux / RHEL
sudo dnf install certbot python3-certbot-nginx -y
```

## Step 2: Obtain SSL Certificates

Certbot will automatically configure Nginx for you:

```bash
# Get certificates for both domains
sudo certbot --nginx -d ameripolytech.com -d www.ameripolytech.com -d api.ameripolytech.com
```

Follow the prompts:
- Enter your email address
- Agree to terms of service
- Choose whether to redirect HTTP to HTTPS (recommended: **Yes**)

## Step 3: Verify Nginx Configuration

Certbot automatically updates your Nginx configs. Verify:

```bash
sudo nginx -t
sudo systemctl reload nginx
```

## Step 4: Test SSL

```bash
# Test frontend
curl -I https://ameripolytech.com

# Test API
curl -I https://api.ameripolytech.com/api/programs/1
```

## Step 5: Auto-Renewal Setup

Certbot certificates expire after 90 days. Set up auto-renewal:

```bash
# Test renewal
sudo certbot renew --dry-run

# Certbot creates a systemd timer automatically, but verify:
sudo systemctl status certbot.timer
```

## Manual Nginx Configuration (if needed)

If Certbot doesn't automatically configure Nginx, you'll need to update the configs manually:

### Frontend Config (`/etc/nginx/conf.d/ameri-polytechnic-frontend.conf`)

```nginx
server {
    listen 80;
    listen [::]:80;
    server_name ameripolytech.com www.ameripolytech.com;
    
    # Redirect HTTP to HTTPS
    return 301 https://$server_name$request_uri;
}

server {
    listen 443 ssl http2;
    listen [::]:443 ssl http2;
    server_name ameripolytech.com www.ameripolytech.com;

    ssl_certificate /etc/letsencrypt/live/ameripolytech.com/fullchain.pem;
    ssl_certificate_key /etc/letsencrypt/live/ameripolytech.com/privkey.pem;
    
    root /var/www/html/ameri-polytechnic/ameri-polytechnic/dist/myapp/browser;
    index index.html;

    location / {
        try_files $uri $uri/ /index.html;
    }

    location ~* \.(js|css|png|jpg|jpeg|gif|ico|svg|woff2?|ttf|eot)$ {
        expires 1y;
        add_header Cache-Control "public, immutable";
    }
}
```

### API Config (`/etc/nginx/conf.d/ameri-polytechnic-api.conf`)

```nginx
server {
    listen 80;
    listen [::]:80;
    server_name api.ameripolytech.com;
    
    # Redirect HTTP to HTTPS
    return 301 https://$server_name$request_uri;
}

server {
    listen 443 ssl http2;
    listen [::]:443 ssl http2;
    server_name api.ameripolytech.com;

    ssl_certificate /etc/letsencrypt/live/api.ameripolytech.com/fullchain.pem;
    ssl_certificate_key /etc/letsencrypt/live/api.ameripolytech.com/privkey.pem;

    root /var/www/html/ameri-polytechnic/ameri-polytechnic-api/public;

    index index.php;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php-fpm/www.sock;
        fastcgi_index index.php;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }
}
```

## Troubleshooting

### Certificate Not Issued

- **DNS not propagated**: Wait 24-48 hours after DNS changes
- **Port 80 blocked**: Ensure Oracle Cloud Security List allows port 80
- **Firewall blocking**: `sudo firewall-cmd --permanent --add-service=http && sudo firewall-cmd --reload`

### Nginx Errors After SSL Setup

```bash
# Check Nginx config
sudo nginx -t

# Check error logs
sudo tail -f /var/log/nginx/error.log

# Restart Nginx
sudo systemctl restart nginx
```

### Certificate Renewal Issues

```bash
# Check renewal status
sudo certbot certificates

# Force renewal (if needed)
sudo certbot renew --force-renewal
```

## Security Headers (Optional but Recommended)

Add these to your Nginx SSL server blocks:

```nginx
add_header Strict-Transport-Security "max-age=31536000; includeSubDomains" always;
add_header X-Frame-Options "SAMEORIGIN" always;
add_header X-Content-Type-Options "nosniff" always;
add_header X-XSS-Protection "1; mode=block" always;
```

