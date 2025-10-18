
# Hackerboard

A vulnerable web application for security testing and learning web security concepts. This project contains intentional security vulnerabilities and should NEVER be used in production.

## Project Structure

```
hackerboard/
├── api/               # PHP Backend
│   ├── src/          # PHP source files
│   ├── uploads/      # Image uploads directory
│   └── Dockerfile    # PHP-Apache container setup
├── frontend/         # React Frontend
│   ├── src/          # React source files
│   └── Dockerfile    # Node.js container setup
├── db/               # MySQL Database
│   └── init/         # Database initialization scripts
└── docker-compose.yml
```

## Quick Start

1. Clone the repository
2. Run `docker-compose up --build`
3. Access the application:
   - Frontend: http://localhost:3000
   - API: http://localhost:8080
   - Database: localhost:3306

## Default Users

- admin:admin123 (Administrator)
- alice:alice123
- bob:bob123
- chuck:chuck123

## Security Lab Challenges

### Easy Mode
1. **XSS in Thread Content**
   - Goal: Execute JavaScript in thread content
   - Hint: Try posting a thread with HTML/JavaScript content
   - Success: Alert box appears when viewing the thread

2. **Insecure File Upload**
   - Goal: Upload a malicious SVG file
   - Hint: Enable SVG uploads in admin panel
   - Success: SVG with embedded script executes

### Medium Mode
1. **SSRF via Webhook**
   - Goal: Access internal network via webhook fetcher
   - Hint: Enable external fetch in admin panel
   - Success: Fetch content from localhost or internal network

2. **OAuth Redirect**
   - Goal: Bypass redirect URL validation
   - Hint: Disable strict redirect matching
   - Success: Register OAuth client with arbitrary redirect URL

### Boss Mode
1. **Content Security Policy Bypass**
   - Goal: Execute JavaScript despite CSP
   - Hint: Find a way to inject script through allowed sources
   - Success: Execute JavaScript with CSP enabled

2. **SQL Injection via Thread Search**
   - Goal: Extract user data through SQL injection
   - Hint: Disable input sanitization
   - Success: Extract password hashes from users table

## Security Controls

Access the Admin Panel to toggle security features:

1. **Sanitization Level**
   - none: No input sanitization
   - basic: Basic HTML tag stripping
   - strict: Full HTML escaping

2. **File Upload Controls**
   - allow_svg_upload: Enable/disable SVG file uploads

3. **External Resources**
   - allow_external_fetch: Enable/disable external URL fetching
   - csp_enabled: Toggle Content Security Policy
   - require_strict_redirect_match: OAuth redirect URL validation

## Testing Checklist

### Basic Functionality
- [ ] User registration and login
- [ ] Thread creation and viewing
- [ ] Image upload and thumbnailing
- [ ] Admin panel access

### Security Features
- [ ] Input sanitization levels
- [ ] File upload restrictions
- [ ] External fetch controls
- [ ] Content Security Policy
- [ ] OAuth redirect validation

### Vulnerability Testing
- [ ] XSS in thread content
- [ ] SVG file upload exploit
- [ ] SSRF via webhook
- [ ] OAuth redirect bypass
- [ ] CSP bypass
- [ ] SQL injection

## Development Notes

This is a deliberately vulnerable application for security testing. DO NOT USE IN PRODUCTION.

### API Endpoints

- POST /api/users/login
- GET/POST /api/threads
- GET/PUT /api/admin
- POST /api/oauth/register
- POST /api/oauth/authorize

### Environment Variables

Frontend:
- REACT_APP_API_URL=http://localhost:8080

API:
- DB_HOST=db
- DB_USER=hackerboard
- DB_PASSWORD=hackerpass
- DB_NAME=hackerboard
- ALLOWED_ORIGINS=http://localhost:3000

## Disclaimer

This application contains intentional security vulnerabilities for educational purposes. It should never be deployed in a production environment or exposed to the public internet.