CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    first_name VARCHAR(100) NOT NULL,
    middle_name VARCHAR(100),
    last_name VARCHAR(100) NOT NULL,
    dob DATE NOT NULL,
    cn_num VARCHAR(20) NOT NULL,
    email VARCHAR(150) NOT NULL UNIQUE,
    gender ENUM('Male', 'Female', 'Other') NOT NULL,
    year VARCHAR(10) NOT NULL,
    course VARCHAR(100) NOT NULL,
    password VARCHAR(255) NOT NULL, -- Store hashed passwords
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS user_submissions  (
    submission_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    answers JSON NOT NULL,          -- Store answers as JSON
    score DECIMAL(5,2) NOT NULL,    -- e.g. up to 999.99
    status VARCHAR(50) NOT NULL,    -- e.g. "completed", "in_progress"
    probability DECIMAL(5,4),       -- e.g. 0.0000 to 0.9999
    submission_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    
    -- Optional: link user_id to a users table
    CONSTRAINT fk_user FOREIGN KEY (user_id) REFERENCES users(id)
);

CREATE TABLE IF NOT EXISTS admins(
    admin_id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(100) NOT NULL UNIQUE,
    email VARCHAR(150) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL, -- Store hashed passwords
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS archived_users (
    archive_id INT AUTO_INCREMENT PRIMARY KEY,
    first_name VARCHAR(100) NOT NULL,
    middle_name VARCHAR(100),
    last_name VARCHAR(100) NOT NULL,
    dob DATE NOT NULL,
    cn_num VARCHAR(20),
    email VARCHAR(150) NOT NULL,
    gender ENUM('Male', 'Female', 'Other') NOT NULL,
    year VARCHAR(10),
    course VARCHAR(100),
    password VARCHAR(255),
    created_at TIMESTAMP,
    archived_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS notifications (
    notification_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NULL,                 -- target user/admin (null for system-wide)
    type VARCHAR(50) NOT NULL,       -- e.g., 'user_archived', 'new_submission'
    message TEXT NOT NULL,           -- the notification content
    link VARCHAR(255),               -- optional URL (e.g., to user details or submission)
    is_read TINYINT(1) DEFAULT 0,    -- 0 = unread, 1 = read
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id)
);

CREATE TABLE IF NOT EXISTS notification_tracker (
    id INT AUTO_INCREMENT PRIMARY KEY,
    type VARCHAR(50) NOT NULL,
    last_sent DATETIME NOT NULL
);

CREATE TABLE IF NOT EXISTS failed_logins (
    id INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(255),
    ip_address VARCHAR(50),
    attempt_time TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);


-- ALTER TABLE users ADD COLUMN archived TINYINT(1) DEFAULT 0;
-- ALTER TABLE admins
-- ADD COLUMN theme VARCHAR(50) DEFAULT 'light',
-- ADD COLUMN notifications TINYINT(1) DEFAULT 1, -- 1 = enabled, 0 = disabled
-- ADD COLUMN data_retention INT DEFAULT 30;      -- in days

-- ALTER TABLE notifications ADD COLUMN is_read TINYINT(1) DEFAULT 0;

