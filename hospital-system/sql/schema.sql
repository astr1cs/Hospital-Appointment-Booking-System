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

-- ========================================
-- APPOINTMENTS TABLE
-- ========================================
CREATE TABLE IF NOT EXISTS appointments (
    id INT PRIMARY KEY AUTO_INCREMENT,
    patient_id INT NOT NULL,
    doctor_id INT NOT NULL,
    appointment_date DATE NOT NULL,
    appointment_time TIME NOT NULL,
    reason TEXT,
    status ENUM('pending', 'confirmed', 'checked_in', 'completed', 'cancelled', 'no_show') DEFAULT 'pending',
    booked_by ENUM('patient', 'receptionist') DEFAULT 'patient',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    
    FOREIGN KEY (patient_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (doctor_id) REFERENCES users(id) ON DELETE CASCADE,
    INDEX idx_date (appointment_date),
    INDEX idx_status (status),
    INDEX idx_doctor (doctor_id),
    INDEX idx_patient (patient_id)
);

-- ========================================
-- BILLING TABLE
-- ========================================
CREATE TABLE IF NOT EXISTS billing (
    id INT PRIMARY KEY AUTO_INCREMENT,
    appointment_id INT NOT NULL,
    patient_id INT NOT NULL,
    amount DECIMAL(10,2) NOT NULL,
    payment_method ENUM('cash', 'card', 'insurance', 'online') DEFAULT 'cash',
    payment_status ENUM('pending', 'paid', 'refunded') DEFAULT 'pending',
    paid_at TIMESTAMP NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    
    FOREIGN KEY (appointment_id) REFERENCES appointments(id) ON DELETE CASCADE,
    FOREIGN KEY (patient_id) REFERENCES users(id) ON DELETE CASCADE,
    INDEX idx_status (payment_status),
    INDEX idx_patient (patient_id)
);

-- ========================================
-- CONSULTATION NOTES TABLE
-- ========================================
CREATE TABLE IF NOT EXISTS consultation_notes (
    id INT PRIMARY KEY AUTO_INCREMENT,
    appointment_id INT NOT NULL,
    doctor_id INT NOT NULL,
    patient_id INT NOT NULL,
    symptoms TEXT,
    diagnosis TEXT,
    prescription TEXT,
    follow_up_date DATE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    
    FOREIGN KEY (appointment_id) REFERENCES appointments(id) ON DELETE CASCADE,
    FOREIGN KEY (doctor_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (patient_id) REFERENCES users(id) ON DELETE CASCADE,
    INDEX idx_appointment (appointment_id),
    INDEX idx_patient (patient_id)
);

-- ========================================
-- DOCTOR REVIEWS TABLE
-- ========================================
CREATE TABLE IF NOT EXISTS doctor_reviews (
    id INT PRIMARY KEY AUTO_INCREMENT,
    appointment_id INT NOT NULL,
    patient_id INT NOT NULL,
    doctor_id INT NOT NULL,
    rating INT CHECK (rating >= 1 AND rating <= 5),
    review_text TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    
    FOREIGN KEY (appointment_id) REFERENCES appointments(id) ON DELETE CASCADE,
    FOREIGN KEY (patient_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (doctor_id) REFERENCES users(id) ON DELETE CASCADE,
    UNIQUE KEY unique_review (appointment_id),
    INDEX idx_doctor (doctor_id),
    INDEX idx_rating (rating)
);

-- ========================================
-- DOCTOR AVAILABILITY TABLE
-- ========================================
CREATE TABLE IF NOT EXISTS doctor_availability (
    id INT PRIMARY KEY AUTO_INCREMENT,
    doctor_id INT NOT NULL,
    day_of_week ENUM('Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday') NOT NULL,
    start_time TIME NOT NULL,
    end_time TIME NOT NULL,
    slot_duration_minutes INT DEFAULT 30,
    is_available TINYINT(1) DEFAULT 1,
    
    FOREIGN KEY (doctor_id) REFERENCES users(id) ON DELETE CASCADE,
    INDEX idx_doctor (doctor_id),
    INDEX idx_day (day_of_week)
);

-- ========================================
-- LEAVE DATES TABLE
-- ========================================
CREATE TABLE IF NOT EXISTS leave_dates (
    id INT PRIMARY KEY AUTO_INCREMENT,
    doctor_id INT NOT NULL,
    leave_date DATE NOT NULL,
    reason VARCHAR(255),
    
    FOREIGN KEY (doctor_id) REFERENCES users(id) ON DELETE CASCADE,
    INDEX idx_doctor (doctor_id),
    INDEX idx_date (leave_date),
    UNIQUE KEY unique_leave (doctor_id, leave_date)
);

-- ========================================
-- DEPENDENTS TABLE
-- ========================================
CREATE TABLE IF NOT EXISTS dependents (
    id INT PRIMARY KEY AUTO_INCREMENT,
    primary_patient_id INT NOT NULL,
    name VARCHAR(100) NOT NULL,
    date_of_birth DATE,
    relationship VARCHAR(50),
    blood_group ENUM('A+', 'A-', 'B+', 'B-', 'O+', 'O-', 'AB+', 'AB-'),
    
    FOREIGN KEY (primary_patient_id) REFERENCES users(id) ON DELETE CASCADE,
    INDEX idx_patient (primary_patient_id)
);

-- ========================================
-- ANNOUNCEMENTS TABLE
-- ========================================
CREATE TABLE IF NOT EXISTS announcements (
    id INT PRIMARY KEY AUTO_INCREMENT,
    author_id INT NOT NULL,
    title VARCHAR(255) NOT NULL,
    body TEXT NOT NULL,
    target_role ENUM('all', 'patient', 'doctor', 'receptionist') DEFAULT 'all',
    published_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    
    FOREIGN KEY (author_id) REFERENCES users(id) ON DELETE CASCADE,
    INDEX idx_target (target_role),
    INDEX idx_published (published_at)
);

-- ========================================
-- COMPLAINTS TABLE
-- ========================================
CREATE TABLE IF NOT EXISTS complaints (
    id INT PRIMARY KEY AUTO_INCREMENT,
    patient_id INT NOT NULL,
    appointment_id INT NULL,
    subject VARCHAR(255) NOT NULL,
    message TEXT NOT NULL,
    status ENUM('pending', 'resolved', 'rejected') DEFAULT 'pending',
    admin_response TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    resolved_at TIMESTAMP NULL,
    
    FOREIGN KEY (patient_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (appointment_id) REFERENCES appointments(id) ON DELETE SET NULL,
    INDEX idx_status (status),
    INDEX idx_patient (patient_id)
);

-- ========================================
-- SETTINGS TABLE (for global policies)
-- ========================================
CREATE TABLE IF NOT EXISTS settings (
    id INT PRIMARY KEY AUTO_INCREMENT,
    setting_key VARCHAR(100) UNIQUE NOT NULL,
    setting_value TEXT,
    setting_description TEXT,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Insert default settings
INSERT INTO settings (setting_key, setting_value, setting_description) VALUES
('cancellation_hours', '2', 'Minimum hours before appointment to allow cancellation'),
('max_booking_days', '30', 'Maximum days in advance patients can book'),
('default_consultation_fee', '50', 'Default consultation fee for doctors'),
('hospital_name', 'City Hospital', 'Name of the hospital'),
('hospital_phone', '+1234567890', 'Hospital contact number'),
('hospital_email', 'info@cityhospital.com', 'Hospital email address')
ON DUPLICATE KEY UPDATE setting_key = setting_key; 

-- Insert test appointments
INSERT INTO appointments (patient_id, doctor_id, appointment_date, appointment_time, reason, status, booked_by) 
VALUES 
(3, 4, CURDATE(), '09:00:00', 'Regular checkup', 'confirmed', 'patient'),
(7, 4, CURDATE(), '11:00:00', 'Follow-up visit', 'pending', 'patient'),
(3, 5, DATE_ADD(CURDATE(), INTERVAL 1 DAY), '14:00:00', 'Consultation', 'confirmed', 'receptionist'),
(7, 6, DATE_SUB(CURDATE(), INTERVAL 5 DAY), '10:00:00', 'Emergency visit', 'completed', 'patient');

-- Insert test billing
INSERT INTO billing (appointment_id, patient_id, amount, payment_status) 
VALUES 
(1, 3, 150.00, 'pending'),
(2, 7, 100.00, 'pending'),
(4, 7, 200.00, 'paid');

-- Insert test announcements
INSERT INTO announcements (author_id, title, body, target_role) 
VALUES 
(2, 'Welcome to Our Hospital', 'We are pleased to announce our new online booking system.', 'all'),
(2, 'Special Health Camp', 'Free health checkup camp on Sunday.', 'patient');

-- Insert a test receptionist (password: receptionist123)
INSERT INTO users (name, email, password_hash, phone, role, is_active) 
VALUES ('Sarah Johnson', 'receptionist@hospital.com', '$2y$12$5k2pYtKqHx5ZqQ5rL5cQOe5xL5qX5zY5w5v5u5t5s5r5q5p5o5n5m', '5551112222', 'receptionist', 1);

-- Insert a test patient
INSERT INTO users (name, email, password_hash, phone, role, is_active) 
VALUES ('John Doe', 'john@patient.com', '$2y$12$5k2pYtKqHx5ZqQ5rL5cQOe5xL5qX5zY5w5v5u5t5s5r5q5p5o5n5m', '5551234567', 'patient', 1);

INSERT INTO patients (user_id, date_of_birth, blood_group, gender, address, emergency_contact_name, emergency_contact_phone) 
SELECT id, '1990-05-15', 'O+', 'male', '123 Main St, City', 'Jane Doe', '5559876543'
FROM users WHERE email = 'john@patient.com';

-- Create settings table
CREATE TABLE IF NOT EXISTS settings (
    id INT PRIMARY KEY AUTO_INCREMENT,
    setting_key VARCHAR(100) UNIQUE NOT NULL,
    setting_value TEXT,
    setting_description TEXT,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Insert default settings
INSERT INTO settings (setting_key, setting_value, setting_description) VALUES
('cancellation_hours', '2', 'Minimum hours before appointment to allow cancellation'),
('max_booking_days', '30', 'Maximum days in advance patients can book'),
('default_consultation_fee', '50', 'Default consultation fee for doctors'),
('hospital_name', 'City Hospital', 'Name of the hospital'),
('hospital_phone', '+1234567890', 'Hospital contact number'),
('hospital_email', 'info@cityhospital.com', 'Hospital email address'),
('hospital_address', '123 Healthcare Ave, Medical District, City', 'Hospital physical address'),
('working_hours_start', '09:00', 'Hospital working hours start'),
('working_hours_end', '17:00', 'Hospital working hours end'),
('timezone', 'Asia/Dhaka', 'System timezone')
ON DUPLICATE KEY UPDATE setting_key = setting_key;

-- Insert test billing records if none exist
INSERT INTO billing (appointment_id, patient_id, amount, payment_status, created_at)
SELECT a.id, a.patient_id, 150.00, 'pending', NOW()
FROM appointments a
WHERE NOT EXISTS (SELECT 1 FROM billing WHERE appointment_id = a.id)
LIMIT 5;

-- Insert some paid records
INSERT INTO billing (appointment_id, patient_id, amount, payment_status, paid_at, created_at)
SELECT a.id, a.patient_id, 200.00, 'paid', NOW(), DATE_SUB(NOW(), INTERVAL 5 DAY)
FROM appointments a
WHERE a.status = 'completed'
AND NOT EXISTS (SELECT 1 FROM billing WHERE appointment_id = a.id AND payment_status = 'paid')
LIMIT 3;