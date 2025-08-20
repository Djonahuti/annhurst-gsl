# Annhurst Transport Service Limited - Company Website

A modern, responsive company website template built with PHP, Tailwind CSS, and MySQL for Annhurst Transport Service Limited. This website specializes in bus higher purchase services with a comprehensive admin panel for content management.

## Features

### Frontend
- **Responsive Design**: Mobile-first approach with Tailwind CSS
- **Modern UI**: Clean, professional design using the company's primary color (#b5121b)
- **Dynamic Content**: Content managed through the admin panel
- **Contact Form**: Functional contact form with database storage
- **Bus Listings**: Showcase available buses with detailed information
- **SEO Optimized**: Meta tags and structured content

### Admin Panel
- **Dashboard**: Overview of website statistics
- **Pages Management**: Create, edit, and manage website pages (WordPress-like)
- **Bus Management**: Add, edit, and manage bus listings
- **Contact Management**: View and manage contact form submissions
- **Settings**: Configure website settings and contact information
- **User Authentication**: Secure admin login system

## Technology Stack

- **Backend**: PHP 7.4+
- **Frontend**: Tailwind CSS (via CDN)
- **Database**: MySQL 5.7+
- **Server**: Apache/Nginx with PHP support

## Installation

### Prerequisites
- PHP 7.4 or higher
- MySQL 5.7 or higher
- Web server (Apache/Nginx)
- Composer (optional, for dependency management)

### Step 1: Database Setup
1. Create a new MySQL database
2. Import the `database_schema.sql` file:
   ```bash
   mysql -u username -p database_name < database_schema.sql
   ```

### Step 2: Configuration
1. Update database connection settings in `config/database.php`:
   ```php
   define('DB_HOST', 'localhost');
   define('DB_NAME', 'your_database_name');
   define('DB_USER', 'your_username');
   define('DB_PASS', 'your_password');
   ```

### Step 3: File Permissions
Ensure the web server has read/write permissions to the project directory.

### Step 4: Web Server Configuration
Configure your web server to point to the project directory and ensure PHP is enabled.

## Default Admin Credentials

- **Username**: `admin`
- **Password**: `admin123`

**Important**: Change these credentials after first login for security.

## File Structure

```
annhurst-transport/
├── admin/                 # Admin panel files
│   ├── dashboard.php     # Admin dashboard
│   ├── login.php         # Admin login
│   ├── logout.php        # Admin logout
│   ├── pages.php         # Pages management
│   ├── buses.php         # Buses management
│   ├── contacts.php      # Contact messages
│   └── settings.php      # Website settings
├── config/
│   └── database.php      # Database configuration
├── assets/               # Static assets (CSS, JS, images)
├── index.php             # Main website file
├── process_contact.php   # Contact form processor
├── database_schema.sql   # Database structure
└── README.md            # This file
```

## Usage

### Frontend
- Access the main website at your domain
- Navigate through different pages using the navigation menu
- View bus listings and contact information
- Submit contact forms

### Admin Panel
- Access admin panel at `/admin/login.php`
- Login with admin credentials
- Manage website content through the intuitive interface
- Update settings and manage bus listings

## Customization

### Colors
The primary brand color (#b5121b) is configured in the Tailwind CSS configuration. To change colors:

1. Update the color values in `index.php` and admin files
2. Modify the CSS custom properties

### Content
- **Pages**: Create and edit pages through the admin panel
- **Buses**: Add new bus listings with detailed information
- **Settings**: Update contact information and website details

### Logo
Replace the placeholder logo by:
1. Adding your logo file to the `assets/images/` directory
2. Updating the logo path in the database settings
3. Modifying the logo display in the navigation

## Security Features

- **SQL Injection Protection**: Prepared statements for all database queries
- **XSS Protection**: HTML escaping for user input
- **Session Management**: Secure admin authentication
- **Input Validation**: Server-side validation for all forms

## Browser Support

- Chrome (latest)
- Firefox (latest)
- Safari (latest)
- Edge (latest)
- Mobile browsers

## Performance

- **CDN Resources**: Tailwind CSS loaded via CDN for faster loading
- **Optimized Images**: Responsive image handling
- **Minimal Dependencies**: Lightweight PHP implementation

## Troubleshooting

### Common Issues

1. **Database Connection Error**
   - Verify database credentials in `config/database.php`
   - Ensure MySQL service is running
   - Check database permissions

2. **Admin Login Issues**
   - Verify admin credentials in the database
   - Check PHP session configuration
   - Ensure proper file permissions

3. **Contact Form Not Working**
   - Verify database table exists
   - Check PHP mail configuration
   - Review server error logs

### Error Logs
Check your web server error logs for detailed error information:
- Apache: `/var/log/apache2/error.log`
- Nginx: `/var/log/nginx/error.log`
- PHP: Check `php.ini` error log settings

## Support

For technical support or customization requests, please contact the development team.

## License

This project is proprietary software developed for Annhurst Transport Service Limited.

## Changelog

### Version 1.0.0
- Initial release
- Complete website with admin panel
- Responsive design with Tailwind CSS
- Database-driven content management
- Contact form functionality
- Bus listing management

---

**Note**: This is a production-ready website template. Ensure proper testing and security measures before deploying to production environments.
