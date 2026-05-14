-- Insert admin user (password: admin123)
INSERT INTO users (name, email, password_hash, phone, role, is_active) 
VALUES (
    'System Administrator',
    'admin@hospital.com',
    'admin123456',  -- Use: password_hash('admin123', PASSWORD_BCRYPT)
    '0000000000',
    'admin',
    1
);