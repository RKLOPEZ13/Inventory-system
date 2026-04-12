-- ============================================
-- AssetTrack — MySQL Database Schema
-- Database: assettrack_db
-- ============================================

CREATE DATABASE IF NOT EXISTS assettrack_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE assettrack_db;

-- ============================================
-- USERS
-- ============================================
CREATE TABLE IF NOT EXISTS users (
    id            INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_code     VARCHAR(20) NOT NULL UNIQUE,
    first_name    VARCHAR(100) NOT NULL,
    last_name     VARCHAR(100) NOT NULL,
    email         VARCHAR(191) NOT NULL UNIQUE,
    username      VARCHAR(80) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    role          ENUM('Administrator', 'Staff', 'Viewer') NOT NULL DEFAULT 'Staff',
    department_id INT UNSIGNED NULL,
    status        ENUM('Active', 'Inactive') NOT NULL DEFAULT 'Active',
    last_login_at DATETIME NULL,
    created_at    DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at    DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_role (role),
    INDEX idx_status (status)
) ENGINE=InnoDB;

-- ============================================
-- DEPARTMENTS
-- ============================================
CREATE TABLE IF NOT EXISTS departments (
    id         INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name       VARCHAR(100) NOT NULL UNIQUE,
    code       VARCHAR(20) NOT NULL UNIQUE,
    head_user_id INT UNSIGNED NULL,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

ALTER TABLE users
    ADD CONSTRAINT fk_users_dept
    FOREIGN KEY (department_id) REFERENCES departments(id)
    ON DELETE SET NULL ON UPDATE CASCADE;

-- ============================================
-- ASSET CATEGORIES
-- ============================================
CREATE TABLE IF NOT EXISTS categories (
    id         INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name       VARCHAR(100) NOT NULL UNIQUE,
    prefix     VARCHAR(5) NOT NULL COMMENT 'Used for Asset ID generation, e.g. IT, FN, AV',
    description TEXT NULL,
    is_active  TINYINT(1) NOT NULL DEFAULT 1,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- ============================================
-- ASSETS (INVENTORY)
-- ============================================
CREATE TABLE IF NOT EXISTS assets (
    id              INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    asset_code      VARCHAR(30) NOT NULL UNIQUE COMMENT 'e.g. IT-001',
    name            VARCHAR(200) NOT NULL,
    category_id     INT UNSIGNED NOT NULL,
    department_id   INT UNSIGNED NULL,
    serial_number   VARCHAR(100) NULL,
    model           VARCHAR(150) NULL,
    brand           VARCHAR(100) NULL,
    condition_status ENUM('New', 'Good', 'Fair', 'Poor') NOT NULL DEFAULT 'Good',
    deploy_status   ENUM('Available', 'Deployed', 'Maintenance', 'Written Off') NOT NULL DEFAULT 'Available',
    acquisition_date DATE NULL,
    value           DECIMAL(12,2) NULL COMMENT 'Purchase value in local currency',
    assigned_to     VARCHAR(200) NULL COMMENT 'Employee name or location',
    notes           TEXT NULL,
    image_path      VARCHAR(255) NULL,
    created_by      INT UNSIGNED NULL,
    created_at      DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at      DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_category (category_id),
    INDEX idx_deploy_status (deploy_status),
    INDEX idx_department (department_id),
    CONSTRAINT fk_assets_category
        FOREIGN KEY (category_id) REFERENCES categories(id)
        ON DELETE RESTRICT ON UPDATE CASCADE,
    CONSTRAINT fk_assets_department
        FOREIGN KEY (department_id) REFERENCES departments(id)
        ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB;

-- ============================================
-- DEPLOYMENT HISTORY LOG
-- ============================================
CREATE TABLE IF NOT EXISTS deployment_logs (
    id            INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    log_code      VARCHAR(20) NOT NULL UNIQUE COMMENT 'e.g. LOG-0001',
    asset_id      INT UNSIGNED NOT NULL,
    action        ENUM('Deployed', 'Returned', 'Maintenance', 'Written Off', 'Added', 'Edited') NOT NULL,
    assigned_to   VARCHAR(200) NULL,
    department_id INT UNSIGNED NULL,
    processed_by  INT UNSIGNED NULL COMMENT 'User who logged this transaction',
    remarks       TEXT NULL,
    transaction_date DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    created_at    DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_asset (asset_id),
    INDEX idx_action (action),
    INDEX idx_date (transaction_date),
    CONSTRAINT fk_logs_asset
        FOREIGN KEY (asset_id) REFERENCES assets(id)
        ON DELETE RESTRICT ON UPDATE CASCADE,
    CONSTRAINT fk_logs_dept
        FOREIGN KEY (department_id) REFERENCES departments(id)
        ON DELETE SET NULL ON UPDATE CASCADE,
    CONSTRAINT fk_logs_user
        FOREIGN KEY (processed_by) REFERENCES users(id)
        ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB;

-- ============================================
-- SYSTEM SETTINGS
-- ============================================
CREATE TABLE IF NOT EXISTS system_settings (
    setting_key   VARCHAR(100) PRIMARY KEY,
    setting_value TEXT NOT NULL,
    updated_at    DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- ============================================
-- SEED DATA
-- ============================================

-- Departments
INSERT INTO departments (name, code) VALUES
    ('Information Technology', 'IT'),
    ('Human Resources', 'HR'),
    ('Finance', 'FIN'),
    ('Sales', 'SLS'),
    ('Marketing', 'MKT'),
    ('Legal', 'LGL'),
    ('Operations', 'OPS'),
    ('Executive', 'EXEC');

-- Categories
INSERT INTO categories (name, prefix, description) VALUES
    ('Computer',    'IT',  'Laptops, desktops, workstations'),
    ('Mobile',      'MB',  'Smartphones and feature phones'),
    ('Tablet',      'TB',  'iPads, Android tablets'),
    ('Printer',     'PR',  'Laser and inkjet printers'),
    ('Monitor',     'MN',  'Display screens and monitors'),
    ('Telecom',     'TC',  'IP phones, headsets'),
    ('AV Equipment','AV',  'Projectors, cameras, audio gear'),
    ('Furniture',   'FN',  'Desks, chairs, cabinets'),
    ('Vehicle',     'VH',  'Company cars and motorcycles'),
    ('Other',       'OT',  'Miscellaneous assets');

-- Default admin user (password: admin123)
INSERT INTO users (user_code, first_name, last_name, email, username, password_hash, role, department_id) VALUES
    ('USR-001', 'Maria', 'Santos', 'maria@company.com', 'admin',
     '$2y$12$eImiTXuWVxfM37uY4JANjOe5XbNIjlcDSGJjjXfVlqVo3h.rRH3u6',
     'Administrator', 1);

-- Sample assets
INSERT INTO assets (asset_code, name, category_id, department_id, serial_number, condition_status, deploy_status, acquisition_date, value, assigned_to, created_by) VALUES
    ('IT-001', 'Laptop Dell XPS 15',    1, 1, 'SN-DELL-001', 'Good', 'Available', '2024-01-15', 85000.00, NULL, 1),
    ('IT-002', 'MacBook Air M3',         1, 5, 'SN-APPL-001', 'Good', 'Deployed',  '2024-02-20', 95000.00, 'Carlo Diaz', 1),
    ('MB-001', 'iPhone 15 Pro',          2, 4, 'SN-APPL-002', 'Good', 'Deployed',  '2024-03-01', 65000.00, 'Juan Cruz', 1),
    ('PR-001', 'HP LaserJet Pro',        4, 3, 'SN-HPLJ-001', 'Fair', 'Deployed',  '2023-11-10', 25000.00, 'Finance Dept', 1),
    ('FN-001', 'Office Chair Ergo',      8, 2, NULL,           'Good', 'Deployed',  '2023-09-05', 12000.00, 'Ana Reyes', 1),
    ('AV-001', 'Projector Epson X',      7, 7, 'SN-EPSN-001', 'Good', 'Deployed',  '2023-07-20', 45000.00, 'Training Room', 1),
    ('TB-001', 'iPad Pro 12.9',          3, 6, 'SN-APPL-003', 'Good', 'Deployed',  '2024-04-15', 55000.00, 'Liza Ramos', 1),
    ('VH-001', 'Toyota Innova 2023',     9, 8, 'TYT-INV-2023', 'Good','Deployed', '2023-12-01', 1250000.00,'Exec Driver', 1);

-- Sample deployment logs
INSERT INTO deployment_logs (log_code, asset_id, action, assigned_to, department_id, processed_by, remarks, transaction_date) VALUES
    ('LOG-0001', 2, 'Deployed',  'Carlo Diaz',    5, 1, 'New hire setup',       '2024-02-20 09:00:00'),
    ('LOG-0002', 3, 'Deployed',  'Juan Cruz',      4, 1, 'For field work',       '2024-03-01 10:00:00'),
    ('LOG-0003', 5, 'Deployed',  'Ana Reyes',      2, 1, 'New hire setup',       '2023-09-05 08:30:00'),
    ('LOG-0004', 7, 'Deployed',  'Liza Ramos',     6, 1, 'Client meetings',      '2024-04-15 11:00:00'),
    ('LOG-0005', 8, 'Deployed',  'Exec Driver',    8, 1, 'Monthly assignment',   '2023-12-01 07:00:00'),
    ('LOG-0006', 4, 'Maintenance','Finance Dept',  3, 1, 'Paper jam repair',     '2026-04-03 13:00:00');

-- System settings defaults
INSERT INTO system_settings (setting_key, setting_value) VALUES
    ('org_name',        'ACME Corporation'),
    ('system_name',     'AssetTrack'),
    ('currency',        '₱'),
    ('date_format',     'Y-m-d'),
    ('timezone',        'Asia/Manila'),
    ('viewer_passcode', '123456'),
    ('session_timeout', '60'),
    ('max_login_attempts','5');
