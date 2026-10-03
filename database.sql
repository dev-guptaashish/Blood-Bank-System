-- Blood Bank System Database Schema (EMPTY - No Dummy Records)
-- Run in phpMyAdmin: Import database.sql
-- Login: admin / password


CREATE DATABASE bloodbank_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE bloodbank_db;

-- Users table
CREATE TABLE users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  username VARCHAR(50) UNIQUE NOT NULL,
  password VARCHAR(255) NOT NULL,
  role ENUM('admin') DEFAULT 'admin',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Admin account only
INSERT INTO users (username, password, role) VALUES 
('admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin'); -- password: password

-- Donors table (empty - add via Donor.php)
CREATE TABLE donors (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100) NOT NULL,
  age INT CHECK (age >= 18 AND age <= 70),
  dob DATE,
  blood_group VARCHAR(5) NOT NULL,
  gender ENUM('Male', 'Female', 'Other'),
  address TEXT,
  mobile VARCHAR(15) UNIQUE,
  email VARCHAR(100),
  city VARCHAR(50),
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Stock table (empty - populate via stock_increase.php)
CREATE TABLE stock (
  blood_group VARCHAR(5) PRIMARY KEY,
  quantity INT DEFAULT 0 CHECK (quantity >= 0),
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Insert minimal stock structure (0 quantity)
INSERT INTO stock (blood_group, quantity) VALUES 
('O+', 0), ('O-', 0), ('A+', 0), ('A-', 0),
('B+', 0), ('B-', 0), ('AB+', 0), ('AB-', 0);

-- Indexes
CREATE INDEX idx_donor_city ON donors(city);
CREATE INDEX idx_donor_blood ON donors(blood_group);

-- View for stock_detail.php
CREATE VIEW stock_summary AS 
SELECT blood_group, quantity AS `Total Number of Quantity` 
FROM stock ORDER BY blood_group;

SELECT 'Database created EMPTY - Ready for real data entry via PHP forms. Admin: admin/password' AS setup_complete;

