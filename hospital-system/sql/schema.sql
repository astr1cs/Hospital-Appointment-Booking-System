-- First, drop tables if they exist (in correct order due to foreign keys)
SET FOREIGN_KEY_CHECKS = 0;

DROP TABLE IF EXISTS user_sessions;
DROP TABLE IF EXISTS password_resets;
DROP TABLE IF EXISTS patients;
DROP TABLE IF EXISTS users;

SET FOREIGN_KEY_CHECKS = 1;

-- Users table
CREATE TABLE users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password_hash VARCHAR(255) NOT NULL,
    phone VARCHAR(20) NOT NULL,
    role ENUM('patient', 'doctor', 'receptionist', 'admin') NOT NULL,
    profile_pic VARCHAR(255) DEFAULT 'default.jpg',
    is_active TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    INDEX idx_email (email),
    INDEX idx_role (role),
    INDEX idx_is_active (is_active)
);

-- Patients extended table
CREATE TABLE patients (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL UNIQUE,
    date_of_birth DATE,
    blood_group ENUM('A+', 'A-', 'B+', 'B-', 'O+', 'O-', 'AB+', 'AB-'),
    gender ENUM('male', 'female', 'other'),
    address TEXT,
    emergency_contact_name VARCHAR(100),
    emergency_contact_phone VARCHAR(20),
    medical_history_notes TEXT,
    
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Password reset tokens
CREATE TABLE password_resets (
    id INT PRIMARY KEY AUTO_INCREMENT,
    email VARCHAR(100) NOT NULL,
    token VARCHAR(255) NOT NULL,
    expires_at TIMESTAMP NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    
    INDEX idx_token (token),
    INDEX idx_email (email)
);

-- User sessions log
CREATE TABLE user_sessions (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    ip_address VARCHAR(45),
    user_agent TEXT,
    last_activity TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    INDEX idx_user_id (user_id)
);

-- Create specializations table
CREATE TABLE IF NOT EXISTS specializations (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL UNIQUE,
    description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Insert some sample data
INSERT INTO specializations (name, description) VALUES
('Cardiology', 'Heart and cardiovascular system specialist'),
('Dermatology', 'Skin, hair, and nail conditions'),
('Neurology', 'Brain and nervous system disorders'),
('Pediatrics', 'Medical care for infants, children, and adolescents'),
('Orthopedics', 'Bones, joints, ligaments, tendons, and muscles'),
('Ophthalmology', 'Eye and vision care'),
('Psychiatry', 'Mental health and emotional well-being'),
('Gynecology', 'Women reproductive health');


-- Create doctors table (if not exists)
CREATE TABLE IF NOT EXISTS doctors (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL UNIQUE,
    specialization_id INT,
    bio TEXT,
    consultation_fee DECIMAL(10,2) DEFAULT 0,
    photo_path VARCHAR(255),
    license_number VARCHAR(100),
    experience_years INT DEFAULT 0,
    is_approved TINYINT(1) DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (specialization_id) REFERENCES specializations(id) ON DELETE SET NULL,
    INDEX idx_specialization (specialization_id),
    INDEX idx_is_approved (is_approved)
);

-- Insert sample doctor (optional - for testing)
-- Note: Password is 'doctor123' (will be hashed)
INSERT INTO users (name, email, password_hash, phone, role, is_active) 
SELECT 'Dr. John Smith', 'doctor@hospital.com', '$2y$12$5k2pYtKqHx5ZqQ5rL5cQOe5xL5qX5zY5w5v5u5t5s5r5q5p5o5n5m', '1234567890', 'doctor', 1
WHERE NOT EXISTS (SELECT 1 FROM users WHERE email = 'doctor@hospital.com');

-- Insert into doctors table (corrected - removed ambiguous ON DUPLICATE KEY UPDATE)
INSERT INTO doctors (user_id, specialization_id, bio, consultation_fee, license_number, experience_years, is_approved)
SELECT u.id, 1, 'Experienced cardiologist with 10+ years of practice', 150.00, 'LIC123456', 10, 1
FROM users u 
WHERE u.email = 'doctor@hospital.com'
AND NOT EXISTS (SELECT 1 FROM doctors d WHERE d.user_id = u.id);