# Team Member Registration System

## Overview

A comprehensive team member registration system that handles the complete workflow of adding new team members to a platform. The system includes form validation, database management, file handling, and email notifications.

**Current Status:** ✅ Complete and Production Ready

---

## Features

### 1. **Dynamic Registration Form**
- Platform credentials (email, password)
- Member information (name, last name, position)
- Profile picture upload (temporary handling)
- 10 social media contact fields
- User-chosen email recipient for confirmation

### 2. **Robust Validation**
- Server-side form validation
- Client-side real-time password validation
- Email format validation (platform and recipient)
- File type and size validation (jpg, jpeg, png, gif, webp - max 5MB)
- Password matching and minimum length requirements

### 3. **Database Management**
- Two-table architecture (users + team)
- UUID-based unique identification
- BCRYPT password hashing for security
- Automatic username generation from name/last_name

### 4. **Email Notifications**
- HTML-formatted confirmation emails via PHPMailer
- Profile picture attached to email
- User-selected email recipient
- Displays all member information and social media contacts

### 5. **Audit Logging**
- Registration audit trail in `logs/registrations.log`
- Timestamp, member details, and UUID tracking
- Compliance-ready logging format

### 6. **File Handling**
- Temporary file storage (no disk persistence)
- Automatic cleanup by PHP
- Secure file validation
- Supports multiple image formats

---

## Architecture

### Service-Oriented Design

The system is built with 5 independent service classes in `/src/` folder, following the Single Responsibility Principle:

```
plataforma/painel/team/
├── add.php                              # Registration form (HTML/PHP)
├── insert.php                           # Orchestrator - coordinates workflow
├── config.example.php                   # Configuration template
├── config.php                           # Actual config (not in repo, use .env)
├── .env.example                         # Environment variables template
├── .env                                 # Actual env vars (not in repo)
├── styles.css                           # Form styling
├── _config_form.php                     # Form configuration (arrays)
├── _messages.php                        # Message display component
├── conexao.php                          # Database connection
├── src/
│   ├── RegistrationValidator.php        # Form validation
│   ├── FileUploadHandler.php            # File upload processing
│   ├── RegistrationService.php          # Database operations
│   ├── RegistrationLogger.php           # Audit logging
│   └── EmailNotificationService.php     # Email delivery
└── logs/
    └── registrations.log                # Registration audit trail
```

### Workflow Flow

```
User Submit Form
        ↓
   insert.php (Orchestrator)
        ↓
   RegistrationValidator → Validate form data
        ↓
   FileUploadHandler → Process temporary file
        ↓
   RegistrationService → Insert to database
        ├── insertUserData() → users table
        └── insertTeamData() → team tables
        ↓
   RegistrationLogger → Write to audit log
        ↓
   EmailNotificationService → Send confirmation email
        ↓
   Redirect with Success Message
```

---

## File Documentation

### Core Files

#### **add.php** - Registration Form
- **Purpose:** User-facing HTML form for team member registration
- **Features:**
  - Dynamic social media fields generated from config array
  - Real-time password validation with visual feedback
  - External CSS styling
  - Component-based includes
  - HTML5 form validation attributes
- **JavaScript Functions:**
  - `validatePassword()` - Real-time password match feedback
  - `validateForm()` - Form submission validation
- **Dependencies:** _config_form.php, _messages.php, styles.css

#### **insert.php** - Orchestrator
- **Purpose:** Coordinates entire registration workflow
- **Workflow:**
  1. Validate form data
  2. Process file upload
  3. Insert to database
  4. Log registration
  5. Send email
  6. Redirect with message
- **Error Handling:** try/catch block with session messages
- **Dependencies:** All 5 service classes + config.php + conexao.php

### Service Classes

#### **RegistrationValidator.php**
- **Purpose:** Validate all form inputs
- **Methods:**
  - `validate()` - Comprehensive form validation
  - `getFormData()` - Return validated form data
  - `getSocialMedia()` - Return social media information
- **Validations:**
  - HTTP POST method check
  - Required fields check
  - Email format validation (dual - platform and recipient)
  - Password matching
  - Password minimum 6 characters
  - Social media field collection

#### **FileUploadHandler.php**
- **Purpose:** Handle profile picture uploads
- **Constants:**
  - ALLOWED_EXTS: jpg, jpeg, png, gif, webp
  - MAX_SIZE: 5MB
- **Methods:**
  - `handle()` - Validate and return temporary file info
- **Returns:**
  - Null if no file uploaded
  - Array with originalName and tmpPath
  - Throws Exception on validation failure
- **Design:** No disk storage - uses PHP temporary files

#### **RegistrationService.php**
- **Purpose:** Database operations
- **Methods:**
  - `register()` - Main entry point
  - `insertUserData()` - Insert to users table
  - `insertTeamData()` - Insert to team table
- **Features:**
  - UUID generation (bin2hex(random_bytes(16)))
  - BCRYPT password hashing
  - Automatic username generation
  - Prepared statements for security
  - Empty teamProfilePicture field (no storage)

#### **RegistrationLogger.php**
- **Purpose:** Audit trail creation
- **Methods:**
  - `write()` - Log registration to file
- **Log Format:** `[YYYY-MM-DD HH:MM:SS] Registration - Name: X Y | Email: Z | Position: P | UUID: U`
- **Log File:** logs/registrations.log

#### **EmailNotificationService.php**
- **Purpose:** Send confirmation emails
- **Features:**
  - PHPMailer via SMTP (Gmail)
  - HTML formatted emails
  - Profile picture attachment
  - User-selected recipient
  - Only shows non-empty social media fields
- **Methods:**
  - `send()` - Main email sending method
  - `buildEmailBody()` - HTML generation
  - `getMimeType()` - MIME type mapping

### Configuration Files

#### **.env.example**
Template for environment variables:
- SMTP settings (host, port, username, password)
- Database credentials
- Application settings

#### **config.example.php**
Demonstrates loading .env and defining constants:
- Parses .env key=value pairs
- Handles quote removal with trim()
- Provides fallback values
- Used by both config files and applications

#### **_config_form.php**
Form configuration arrays:
- `$socialMedias` - 10 social media platforms with metadata
- `$positions` - Available job positions (intern)
- Supports dynamic form generation in add.php

#### **_messages.php**
Component for displaying session messages:
- Success message display
- Error message display
- Clears session after display

### Styling

#### **styles.css**
Form and message styling with sections:
- Form elements (list, items, labels, inputs)
- Input focus states (green border highlight)
- Success/error message styling
- Password validation feedback
- Submit button styling
- Responsive design
- Professional appearance

---

## Database Schema

### Users Table
```sql
CREATE TABLE users (
    uuid VARCHAR(32) PRIMARY KEY,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,    -- BCRYPT hashed
    name VARCHAR(50) NOT NULL,
    last_name VARCHAR(50) NOT NULL,
    username VARCHAR(100) UNIQUE NOT NULL  -- Generated: name.last_name
);
```

### Team Table
```sql
CREATE TABLE team (
    uuid VARCHAR(32) PRIMARY KEY,
    teamProfilePicture VARCHAR(255),    -- Empty (not stored)
    teamPosition VARCHAR(50) NOT NULL,
    teamName VARCHAR(100) NOT NULL,
    teamSocialMedia0 VARCHAR(200),      -- WhatsApp
    teamSocialMedia1 VARCHAR(200),      -- Instagram
    teamSocialMedia2 VARCHAR(200),      -- Telegram
    teamSocialMedia3 VARCHAR(200),      -- Facebook
    teamSocialMedia4 VARCHAR(200),      -- GitHub
    teamSocialMedia5 VARCHAR(200),      -- Email
    teamSocialMedia6 VARCHAR(200),      -- Twitter
    teamSocialMedia7 VARCHAR(200),      -- LinkedIn
    teamSocialMedia8 VARCHAR(200),      -- Twitch
    teamSocialMedia9 VARCHAR(200)       -- Medium
);
```

---

## Setup Instructions

### Prerequisites
- PHP 8.5.1+
- MySQL/MariaDB
- Composer (for PHPMailer)
- SMTP access (Gmail recommended)

### Installation Steps

1. **Install Dependencies**
   ```bash
   cd plataforma/painel/team
   composer install
   ```

2. **Configure Environment**
   ```bash
   # Copy template
   cp .env.example .env
   
   # Edit .env with your values
   nano .env
   ```

3. **Create Database Tables**
   ```bash
   # Execute the schema above in your MySQL client
   ```

4. **Verify Configuration**
   ```bash
   # Create config.php (optional if using .env)
   cp config.example.php config.php
   ```

5. **Set Permissions**
   ```bash
   # Make logs directory writable
   mkdir -p logs
   chmod 755 logs
   ```

### Configuration

**Required .env variables:**
```env
# SMTP Configuration
SMTP_HOST=smtp.gmail.com
SMTP_PORT=587
SMTP_USERNAME=your-email@gmail.com
SMTP_PASSWORD=your-app-password
SMTP_FROM_EMAIL=your-email@gmail.com
SMTP_FROM_NAME=Team Platform

# Database Configuration
DB_HOST=localhost
DB_USER=root
DB_PASS=your-password
DB_NAME=plata_team

# Application Settings
APP_NAME=Team Platform
APP_URL=http://127.0.0.1:8000
```

---

## Usage

### Register a Team Member

1. Navigate to `http://localhost:8000/plataforma/painel/team/add.php`
2. Fill in the form:
   - **Platform Info:** Email, password (with real-time validation)
   - **Member Info:** Name, last name, position, profile picture
   - **Social Media:** Add contacts (optional fields)
   - **Email Recipient:** Where to send confirmation
3. Click Submit
4. Form validates on client and server
5. On success:
   - User inserted to database with BCRYPT password
   - Team profile created with social media
   - Registration logged to audit file
   - Confirmation email sent with profile picture
   - User redirected to form with success message

### Check Logs

View registration audit trail:
```bash
cat logs/registrations.log
```

Example log entry:
```
[2026-01-08 14:23:45] Registration - Name: John Doe | Email: john@example.com | Position: senior | UUID: a1b2c3d4e5f6g7h8i9j0k1l2m3n4o5p6
```

---

## Security Features

1. **Password Security**
   - BCRYPT hashing with PHP's password_hash()
   - Minimum 6 characters enforced
   - Client + server validation
   - Never stored in plain text

2. **File Security**
   - Extension validation (whitelist)
   - Size validation (5MB max)
   - Temporary storage (automatic cleanup)
   - MIME type validation

3. **SQL Security**
   - Prepared statements with bound parameters
   - No string concatenation in queries
   - Protected against SQL injection

4. **Email Security**
   - TLS encryption with SMTP
   - Secure credential management via .env
   - User-selected recipient (not hardcoded)

5. **Data Validation**
   - Client-side HTML5 validation
   - Server-side comprehensive validation
   - Input sanitization (trim, filter_var)
   - Proper error handling

---

## Code Quality Standards

### Documentation
✅ All files include English PHPDoc comments
✅ All methods have @param, @return, @throws documentation
✅ Inline comments explain complex logic
✅ CSS organized with section headers

### Naming Conventions
✅ English names for all functions and variables
✅ Consistent camelCase for JavaScript
✅ PascalCase for class names
✅ Descriptive, self-documenting names

### Architecture
✅ Service-oriented design pattern
✅ Single Responsibility Principle
✅ Separation of concerns
✅ Reusable components (_config_form.php, _messages.php)

### Error Handling
✅ try/catch blocks in orchestrator
✅ Exception throwing for validation failures
✅ Graceful error messages
✅ Error logging

---

## Troubleshooting

### Email Not Sending
1. Verify SMTP credentials in .env
2. Check Gmail "Less secure app access" or use App Passwords
3. Review error log: error_log()
4. Test SMTP connection

### File Upload Fails
1. Check file format (jpg, jpeg, png, gif, webp)
2. Verify file size < 5MB
3. Check PHP upload_max_filesize setting
4. Verify temporary directory is writable

### Database Connection Error
1. Verify MySQL is running
2. Check DB_HOST, DB_USER, DB_PASS in .env
3. Verify database and tables exist
4. Check user permissions

### Logs Directory Permission Error
1. Create logs directory: `mkdir -p logs`
2. Set permissions: `chmod 755 logs`
3. Verify web server can write: `chmod 777 logs`

---

## File Modifications Summary

### New Files Created
1. `add.php` - Registration form with validation
2. `insert.php` - Workflow orchestrator
3. `config.example.php` - Configuration template
4. `.env.example` - Environment variables template
5. `styles.css` - Form styling
6. `_config_form.php` - Form configuration
7. `_messages.php` - Message component
8. `src/RegistrationValidator.php` - Validation service
9. `src/FileUploadHandler.php` - File handling service
10. `src/RegistrationService.php` - Database service
11. `src/RegistrationLogger.php` - Logging service
12. `src/EmailNotificationService.php` - Email service

### Total Files: 12 main files + 1 README

---

## Future Enhancements

- [ ] Email template customization
- [ ] Bulk import via CSV
- [ ] Admin dashboard for member management
- [ ] Email verification before storage
- [ ] Social media validation
- [ ] Profile picture preview before submission
- [ ] Member profile viewing
- [ ] Member edit/delete functionality
- [ ] Role-based access control
- [ ] API endpoints for programmatic access

---

## Support

For issues or questions:
1. Check logs: `logs/registrations.log` and PHP error log
2. Review error messages from form submission
3. Verify configuration matches .env.example
4. Test database connectivity
5. Validate file permissions

---

## License

Project is part of Plata Team Platform

**Last Updated:** January 8, 2026
**Status:** ✅ Production Ready
**Version:** 1.0.0

---

## Changelog

### Version 1.0.0 (2026-01-08)
- ✅ Complete registration form with validation
- ✅ Service-based architecture (5 classes)
- ✅ Database integration with UUID tracking
- ✅ Email notifications with attachments
- ✅ Audit logging
- ✅ Comprehensive English documentation
- ✅ Professional code comments
- ✅ Security best practices implemented
