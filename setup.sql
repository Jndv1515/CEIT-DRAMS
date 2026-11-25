CREATE DATABASE IF NOT EXISTS ceit_drams CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE ceit_drams;

CREATE TABLE IF NOT EXISTS users (
    id          INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name        VARCHAR(150)        NOT NULL,
    email       VARCHAR(150)        NOT NULL UNIQUE,
    student_number VARCHAR(30)      NOT NULL UNIQUE,
    role        ENUM('student','admin') NOT NULL DEFAULT 'student',
    course      VARCHAR(150)        NOT NULL DEFAULT '',
    organization VARCHAR(150)       NOT NULL DEFAULT 'CEIT Student Council',
    organization_role VARCHAR(150)  NOT NULL DEFAULT '',
    password_hash VARCHAR(255)      NOT NULL,
    created_at  DATETIME            NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS document_requests (
    id              VARCHAR(30)     PRIMARY KEY,
    user_id         INT UNSIGNED    NOT NULL,
    document        VARCHAR(150)    NOT NULL,
    category        VARCHAR(150)    NOT NULL,
    type            ENUM('student','organization') NOT NULL,
    purpose         TEXT            NOT NULL,
    notes           TEXT            NOT NULL DEFAULT '',
    status          ENUM('pending','processing','approved','rejected','forwarded') NOT NULL DEFAULT 'pending',
    attachment_name VARCHAR(255)    NOT NULL DEFAULT '',
    attachment_path VARCHAR(255)    NOT NULL DEFAULT '',
    attachment_type VARCHAR(100)    NOT NULL DEFAULT '',
    attachment_size INT UNSIGNED    NOT NULL DEFAULT 0,
    rejection_reason TEXT           NOT NULL DEFAULT '',
    submitted_at    DATETIME        NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at      DATETIME        NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB;
