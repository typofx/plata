# Email Notification System

Simple PHP system for sending registration confirmation emails using PHPMailer.

## Quick Start

### 1. Install Dependencies
```bash
cd plataforma/painel/team/send_email
composer install
```

### 2. Configure SMTP
```bash
# Copy the example configuration
copy .env.example .env

# Edit .env with your SMTP credentials
```

### 3. Start Server
```bash
php -S localhost:8000
```

### 4. Open Form
Visit: `http://localhost:8000/add.php`

## Configuration

Edit `.env` file with your SMTP settings:

```dotenv
SMTP_HOST=smtp.gmail.com
SMTP_PORT=587
SMTP_ENCRYPTION=tls
SMTP_USERNAME=your-email@gmail.com
SMTP_PASSWORD=your-app-specific-password
SMTP_FROM_EMAIL=your-email@gmail.com
SMTP_FROM_NAME=Team Platform
```

### For Gmail Users
1. Enable 2-Step Verification
2. Generate App Password: https://myaccount.google.com/apppasswords
3. Use the generated password in `SMTP_PASSWORD`

## Features

✅ Registration form with email and social media fields
✅ Profile picture upload with validation
✅ Email confirmation with attachment
✅ Success/Error feedback messages
✅ Input validation and sanitization
✅ Automatic SMTP configuration from `.env`

## Project Structure

```
send_email/
├── add.php                          # Registration form with validation
├── insert.php                       # Form processing & email sending
├── config.php                       # SMTP configuration loader
├── .env                            # Configuration (do not commit)
├── .env.example                    # Configuration template
├── composer.json                   # PHP dependencies (PHPMailer)
├── composer.lock                   # Dependency lock file
├── .gitignore                      # Git ignore rules
├── README.md                       # Quick start guide
├── DOCUMENTATION.md                # Full documentation
├── COMMIT_MESSAGES.md              # Git commit message templates
├── test_form.php                   # Testing utility
└── src/
    └── EmailNotificationService.php # Email service class (SMTP)
```

## Supported Email Providers

- **Gmail**: `smtp.gmail.com:587` (TLS)
- **Outlook**: `smtp-mail.outlook.com:587` (TLS)
- **Custom Server**: Use your provider's SMTP settings

## File Upload Validation

- **Allowed formats**: JPG, JPEG, PNG, GIF, WebP
- **Max size**: 5 MB
- **Validation**: Type and size checked on server

## Error Handling

Errors are displayed to users and logged to PHP error log with helpful messages.

## Full Documentation

See [DOCUMENTATION.md](DOCUMENTATION.md) for comprehensive setup instructions and troubleshooting.

---

**Created**: January 2026
