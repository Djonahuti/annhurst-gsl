-- Annhurst Transport Service Limited Database Schema
-- Database: annhurst_transport

CREATE DATABASE IF NOT EXISTS annhurst_gsl;
USE annhurst_gsl;

-- Users table for admin authentication
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password_hash VARCHAR(255) NOT NULL,
    full_name VARCHAR(100) NOT NULL,
    role ENUM('admin', 'editor') DEFAULT 'editor',
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Pages table for content management
CREATE TABLE pages (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    slug VARCHAR(255) UNIQUE NOT NULL,
    content LONGTEXT,
    meta_description TEXT,
    meta_keywords TEXT,
    status ENUM('published', 'draft', 'private') DEFAULT 'draft',
    featured_image VARCHAR(255),
    author_id INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (author_id) REFERENCES users(id) ON DELETE SET NULL
);

-- Bus listings table
CREATE TABLE buses (
    id INT AUTO_INCREMENT PRIMARY KEY,
    bus_name VARCHAR(255) NOT NULL,
    model VARCHAR(100),
    year INT,
    capacity INT,
    price DECIMAL(10, 2),
    description TEXT,
    features TEXT,
    images TEXT, -- JSON array of image paths
    status ENUM('available', 'sold', 'reserved') DEFAULT 'available',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Contact form submissions
CREATE TABLE contact_submissions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    phone VARCHAR(20),
    subject VARCHAR(200),
    message TEXT NOT NULL,
    status ENUM('new', 'read', 'replied') DEFAULT 'new',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Settings table for site configuration
CREATE TABLE settings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    setting_key VARCHAR(100) UNIQUE NOT NULL,
    setting_value TEXT,
    description TEXT,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Insert default admin user (password: admin123)
INSERT INTO users (username, email, password_hash, full_name, role) VALUES 
('admin', 'admin@annhurst.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Administrator', 'admin');

-- Insert default pages
INSERT INTO pages (title, slug, content, status, author_id) VALUES 
('Home', 'home', '<h1>Welcome to Annhurst Transport Service Limited</h1><p>Your trusted partner for bus higher purchase solutions.</p>', 'published', 1),
('About Us', 'about', '<h1>About Annhurst Transport</h1><p>We specialize in providing quality buses for higher purchase.</p>', 'published', 1),
('Our Buses', 'buses', '<h1>Available Buses</h1><p>Browse our selection of quality buses available for higher purchase.</p>', 'published', 1),
('Contact Us', 'contact', '<h1>Contact Information</h1><p>Get in touch with us for your bus higher purchase needs.</p>', 'published', 1);

-- Insert default settings
INSERT INTO settings (setting_key, setting_value, description) VALUES 
('site_title', 'Annhurst Transport Service Limited', 'Website title'),
('site_description', 'Your trusted partner for bus higher purchase solutions', 'Website description'),
('contact_email', 'info@annhurst.com', 'Contact email address'),
('contact_phone', '+234 123 456 7890', 'Contact phone number'),
('contact_address', '123 Transport Street, Lagos, Nigeria', 'Company address'),
('logo_path', '/assets/images/logo.png', 'Path to company logo'),
('primary_color', '#b5121b', 'Primary brand color'),
('footer_text', '© 2024 Annhurst Transport Service Limited. All rights reserved.', 'Footer copyright text');

-- Insert sample bus data
INSERT INTO buses (bus_name, model, year, capacity, price, description, features, status) VALUES 
('Luxury Coach A', 'Mercedes-Benz O500', 2020, 45, 25000000.00, 'Premium luxury coach with modern amenities', 'Air conditioning, WiFi, USB charging, Reclining seats, Entertainment system', 'available'),
('City Bus B', 'Toyota Coaster', 2019, 25, 15000000.00, 'Reliable city bus perfect for urban transport', 'Air conditioning, Comfortable seating, Good fuel economy', 'available'),
('Mini Bus C', 'Nissan Civilian', 2021, 18, 12000000.00, 'Compact mini bus for small groups', 'Air conditioning, Compact design, Easy maneuverability', 'available');
