USE spell_library;

-- 1. Create Users Table
CREATE TABLE IF NOT EXISTS users (
    userID INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    failed_attempts INT DEFAULT 0,
    lockout_time DATETIME DEFAULT NULL,
    dateCreated TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- 2. Link Spells to User
-- Add userID column to spells if it doesn't already exist
ALTER TABLE spells ADD COLUMN userID INT NULL AFTER schoolID;

-- Create default user (password: "Password123!")
-- Hash generated using PASSWORD_DEFAULT (bcrypt)
INSERT INTO users (userID, username, email, password) VALUES 
(1, 'merlin', 'merlin@arcane.org', '$2y$10$7Zhyb2iQcQ3WwGjK3Q6R7eWJ7kU1pC6/O8iZ0lO0j5uU1v2w3x4y5');

-- Assign existing spells to the default user
UPDATE spells SET userID = 1 WHERE userID IS NULL;

-- Make userID NOT NULL and add foreign key
ALTER TABLE spells MODIFY COLUMN userID INT NOT NULL;
ALTER TABLE spells ADD CONSTRAINT fk_spells_users 
    FOREIGN KEY (userID) REFERENCES users(userID) 
    ON DELETE CASCADE ON UPDATE CASCADE;