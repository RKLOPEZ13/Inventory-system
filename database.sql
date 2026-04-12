-- =========================================================
-- INVENTORY SYSTEM DATABASE
-- MySQL 8.0+
-- =========================================================

-- Optional: create database
CREATE DATABASE IF NOT EXISTS inventory_system
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE inventory_system;

-- =========================================================
-- SAFETY: Drop tables in reverse order if you want to rerun
-- =========================================================
SET FOREIGN_KEY_CHECKS = 0;

DROP TABLE IF EXISTS inventory;
DROP TABLE IF EXISTS custodians;
DROP TABLE IF EXISTS brands;
DROP TABLE IF EXISTS sub_categories;
DROP TABLE IF EXISTS categories;
DROP TABLE IF EXISTS departments;
DROP TABLE IF EXISTS companies;
DROP TABLE IF EXISTS age_statuses;

SET FOREIGN_KEY_CHECKS = 1;

-- =========================================================
-- 1) COMPANY
-- =========================================================
CREATE TABLE companies (
    company_id       BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    company_code     VARCHAR(20) NOT NULL,
    company_name     VARCHAR(100) NOT NULL,
    description      VARCHAR(255) NULL,
    is_active        TINYINT(1) NOT NULL DEFAULT 1,
    created_at       TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at       TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    CONSTRAINT uq_companies_code UNIQUE (company_code),
    CONSTRAINT uq_companies_name UNIQUE (company_name)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =========================================================
-- 2) CATEGORY
-- =========================================================
CREATE TABLE categories (
    category_id      BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    category_name    VARCHAR(100) NOT NULL,
    description      VARCHAR(255) NULL,
    is_active        TINYINT(1) NOT NULL DEFAULT 1,
    created_at       TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at       TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    CONSTRAINT uq_categories_name UNIQUE (category_name)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =========================================================
-- 3) SUB CATEGORY
-- =========================================================
CREATE TABLE sub_categories (
    sub_category_id  BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    sub_category_name VARCHAR(150) NOT NULL,
    description      VARCHAR(255) NULL,
    is_active        TINYINT(1) NOT NULL DEFAULT 1,
    created_at       TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at       TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    CONSTRAINT uq_sub_categories_name UNIQUE (sub_category_name)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =========================================================
-- 4) BRAND
-- =========================================================
CREATE TABLE brands (
    brand_id         BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    brand_name       VARCHAR(150) NOT NULL,
    description      VARCHAR(255) NULL,
    is_active        TINYINT(1) NOT NULL DEFAULT 1,
    created_at       TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at       TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    CONSTRAINT uq_brands_name UNIQUE (brand_name)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =========================================================
-- 5) CUSTODIANS
-- description: employee or place
-- =========================================================
CREATE TABLE custodians (
    custodian_id      BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    custodian_name    VARCHAR(150) NOT NULL,
    custodian_type    ENUM('EMPLOYEE', 'PLACE', 'OTHER') NOT NULL DEFAULT 'EMPLOYEE',
    employee_code     VARCHAR(50) NULL,
    email             VARCHAR(150) NULL,
    mobile_no         VARCHAR(50) NULL,
    notes             TEXT NULL,
    is_active         TINYINT(1) NOT NULL DEFAULT 1,
    created_at        TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at        TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    CONSTRAINT uq_custodians_name UNIQUE (custodian_name),
    CONSTRAINT uq_custodians_employee_code UNIQUE (employee_code),
    CONSTRAINT uq_custodians_email UNIQUE (email)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =========================================================
-- 6) DEPARTMENTS
-- =========================================================
CREATE TABLE departments (
    department_id     BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    department_code   VARCHAR(20) NULL,
    department_name   VARCHAR(150) NOT NULL,
    description       VARCHAR(255) NULL,
    is_active         TINYINT(1) NOT NULL DEFAULT 1,
    created_at        TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at        TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    CONSTRAINT uq_departments_name UNIQUE (department_name),
    CONSTRAINT uq_departments_code UNIQUE (department_code)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =========================================================
-- 7) AGE STATUS
-- =========================================================
CREATE TABLE age_statuses (
    age_status_id     BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    status_name       VARCHAR(50) NOT NULL,
    description       VARCHAR(255) NULL,
    is_active         TINYINT(1) NOT NULL DEFAULT 1,
    created_at        TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at        TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    CONSTRAINT uq_age_statuses_name UNIQUE (status_name)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =========================================================
-- 8) INVENTORY
-- main table
-- =========================================================
CREATE TABLE inventory (
    inventory_id         BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,

    -- Example: NCIA-0001
    inventory_no         VARCHAR(30) NOT NULL,

    company_id           BIGINT UNSIGNED NOT NULL,
    category_id          BIGINT UNSIGNED NOT NULL,
    sub_category_id      BIGINT UNSIGNED NOT NULL,
    brand_id             BIGINT UNSIGNED NOT NULL,

    model                VARCHAR(150) NULL,
    item_description     TEXT NULL,

    serial_number        VARCHAR(150) NULL,

    custodian_id         BIGINT UNSIGNED NULL,
    department_id        BIGINT UNSIGNED NULL,

    mac_address          VARCHAR(50) NULL,
    device_name          VARCHAR(150) NULL,
    current_os           VARCHAR(100) NULL,

    -- e.g. 126 months
    device_age_months    INT UNSIGNED NULL,

    age_status_id        BIGINT UNSIGNED NULL,

    purchase_date        DATE NULL,
    purchase_month       VARCHAR(20) NULL,
    purchase_year        YEAR NULL,

    remarks              TEXT NULL,

    status               ENUM('ACTIVE', 'INACTIVE', 'RETIRED', 'DISPOSED', 'LOST', 'DAMAGED') NOT NULL DEFAULT 'ACTIVE',

    created_at           TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at           TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    CONSTRAINT uq_inventory_no UNIQUE (inventory_no),
    CONSTRAINT uq_inventory_serial_number UNIQUE (serial_number),

    CONSTRAINT chk_device_age_months CHECK (device_age_months IS NULL OR device_age_months >= 0),

    CONSTRAINT fk_inventory_company
        FOREIGN KEY (company_id) REFERENCES companies(company_id)
        ON UPDATE CASCADE
        ON DELETE RESTRICT,

    CONSTRAINT fk_inventory_category
        FOREIGN KEY (category_id) REFERENCES categories(category_id)
        ON UPDATE CASCADE
        ON DELETE RESTRICT,

    CONSTRAINT fk_inventory_sub_category
        FOREIGN KEY (sub_category_id) REFERENCES sub_categories(sub_category_id)
        ON UPDATE CASCADE
        ON DELETE RESTRICT,

    CONSTRAINT fk_inventory_brand
        FOREIGN KEY (brand_id) REFERENCES brands(brand_id)
        ON UPDATE CASCADE
        ON DELETE RESTRICT,

    CONSTRAINT fk_inventory_custodian
        FOREIGN KEY (custodian_id) REFERENCES custodians(custodian_id)
        ON UPDATE CASCADE
        ON DELETE SET NULL,

    CONSTRAINT fk_inventory_department
        FOREIGN KEY (department_id) REFERENCES departments(department_id)
        ON UPDATE CASCADE
        ON DELETE SET NULL,

    CONSTRAINT fk_inventory_age_status
        FOREIGN KEY (age_status_id) REFERENCES age_statuses(age_status_id)
        ON UPDATE CASCADE
        ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =========================================================
-- INDEXES FOR FASTER SEARCHING
-- =========================================================
CREATE INDEX idx_inventory_company        ON inventory(company_id);
CREATE INDEX idx_inventory_category       ON inventory(category_id);
CREATE INDEX idx_inventory_sub_category   ON inventory(sub_category_id);
CREATE INDEX idx_inventory_brand          ON inventory(brand_id);
CREATE INDEX idx_inventory_custodian      ON inventory(custodian_id);
CREATE INDEX idx_inventory_department     ON inventory(department_id);
CREATE INDEX idx_inventory_age_status     ON inventory(age_status_id);
CREATE INDEX idx_inventory_purchase_date  ON inventory(purchase_date);
CREATE INDEX idx_inventory_device_name    ON inventory(device_name);
CREATE INDEX idx_inventory_model          ON inventory(model);
CREATE INDEX idx_inventory_status         ON inventory(status);

-- =========================================================
-- TRIGGERS:
-- auto-fill purchase_month and purchase_year from purchase_date
-- =========================================================
DROP TRIGGER IF EXISTS trg_inventory_before_insert;
DELIMITER $$
CREATE TRIGGER trg_inventory_before_insert
BEFORE INSERT ON inventory
FOR EACH ROW
BEGIN
    IF NEW.purchase_date IS NOT NULL THEN
        SET NEW.purchase_month = MONTHNAME(NEW.purchase_date);
        SET NEW.purchase_year  = YEAR(NEW.purchase_date);
    END IF;
END$$
DELIMITER ;

DROP TRIGGER IF EXISTS trg_inventory_before_update;
DELIMITER $$
CREATE TRIGGER trg_inventory_before_update
BEFORE UPDATE ON inventory
FOR EACH ROW
BEGIN
    IF NEW.purchase_date IS NOT NULL THEN
        SET NEW.purchase_month = MONTHNAME(NEW.purchase_date);
        SET NEW.purchase_year  = YEAR(NEW.purchase_date);
    ELSE
        SET NEW.purchase_month = NULL;
        SET NEW.purchase_year  = NULL;
    END IF;
END$$
DELIMITER ;

-- =========================================================
-- SEED DATA
-- =========================================================

-- Companies
INSERT INTO companies (company_code, company_name, description) VALUES
('NCIA', 'NCIA', 'Company list'),
('NCIA1', 'NCIA 1', 'Company list'),
('NCIA2', 'NCIA 2', 'Company list'),
('NCIALIFE', 'NCIA Life', 'Company list');

-- Categories
INSERT INTO categories (category_name, description) VALUES
('IT ACCESSORY', 'Item category'),
('HARDWARE', 'Item category');

-- Sub Categories
INSERT INTO sub_categories (sub_category_name, description) VALUES
('LAPTOP', 'Item sub category'),
('Bag', 'Item sub category'),
('Laptop Charger', 'Item sub category'),
('SATA enclosure', 'Item sub category'),
('CCTV camera', 'Item sub category'),
('FlashDrive', 'Item sub category'),
('HDMI', 'Item sub category'),
('Keyboard', 'Item sub category'),
('Mouse', 'Item sub category'),
('Network', 'Item sub category'),
('Tools', 'Item sub category'),
('Monitor', 'Item sub category'),
('Laser Presenter', 'Item sub category'),
('Cable', 'Item sub category'),
('PSU', 'Item sub category'),
('RAM', 'Item sub category'),
('Power Cord', 'Item sub category'),
('Headphones', 'Item sub category'),
('USB HUB', 'Item sub category'),
('RFID Scanner', 'Item sub category'),
('WiFi Adapter', 'Item sub category'),
('Phone', 'Item sub category'),
('Phone Charger', 'Item sub category'),
('Desktop', 'Item sub category'),
('Digital Ballpen', 'Item sub category'),
('Nvm e ssd', 'Item sub category');

-- Brands
INSERT INTO brands (brand_name, description) VALUES
('DELL', 'Item brand'),
('LENOVO', 'Item brand'),
('HUAWEI', 'Item brand'),
('HP', 'Item brand'),
('ACER', 'Item brand'),
('ORICO', 'Item brand'),
('SanDisk', 'Item brand'),
('Transcend', 'Item brand'),
('Red Mesh', 'Item brand'),
('A4 Tech', 'Item brand'),
('Logitech', 'Item brand'),
('MegaBox', 'Item brand'),
('MSI', 'Item brand'),
('Networking Tools', 'Item brand'),
('HAVIT', 'Item brand'),
('ARMAK', 'Item brand'),
('DeepCoot', 'Item brand'),
('Secure', 'Item brand'),
('SAMSUNG', 'Item brand'),
('STANDARD', 'Item brand'),
('WEIBO', 'Item brand'),
('RAMAXEL', 'Item brand'),
('WALRAM', 'Item brand'),
('ROYU', 'Item brand'),
('Ugreen', 'Item brand'),
('CYGNETT', 'Item brand'),
('FEELTEK', 'Item brand'),
('OPPO', 'Item brand'),
('HONOR', 'Item brand'),
('IPHONE', 'Item brand'),
('ASUS', 'Item brand'),
('MAC', 'Item brand'),
('MOTIVO', 'Item brand'),
('WD Green', 'Item brand');

-- Custodians
-- Placeholder record; you can add real employees/places later
INSERT INTO custodians (custodian_name, custodian_type, notes) VALUES
('TBD', 'OTHER', 'Placeholder custodian');

-- Departments
INSERT INTO departments (department_code, department_name, description) VALUES
('IFG', 'IFG (Individual & Family Group)', 'Company department'),
('IT', 'I.T (Information Technology)', 'Company department'),
('FIN', 'Finance', 'Company department'),
('MKT', 'Marketing', 'Company department'),
('ADM', 'Admin', 'Company department'),
('HR', 'HR (Human Resources)', 'Company department'),
('EB', 'EB (Employee Benefits)', 'Company department'),
('PC', 'P&C (Property & Casualty)', 'Company department'),
('BCD', 'Bacolod Branch', 'Company department'),
('CLM', 'Claims', 'Company department'),
('EXE', 'Executive', 'Company department'),
('HD', 'Helpdesk', 'Company department');

-- Age Statuses
INSERT INTO age_statuses (status_name, description) VALUES
('NEW', 'Age status of item'),
('OLD', 'Age status of item');

-- =========================================================
-- SAMPLE INVENTORY INSERT
-- =========================================================
INSERT INTO inventory (
    inventory_no,
    company_id,
    category_id,
    sub_category_id,
    brand_id,
    model,
    item_description,
    serial_number,
    custodian_id,
    department_id,
    mac_address,
    device_name,
    current_os,
    device_age_months,
    age_status_id,
    purchase_date,
    remarks
)
VALUES (
    'NCIA-0001',
    (SELECT company_id FROM companies WHERE company_name = 'NCIA'),
    (SELECT category_id FROM categories WHERE category_name = 'HARDWARE'),
    (SELECT sub_category_id FROM sub_categories WHERE sub_category_name = 'LAPTOP'),
    (SELECT brand_id FROM brands WHERE brand_name = 'DELL'),
    'INSPIRON 15 3000',
    'Processor: i3 7th Gen
RAM: 12 GB
Storage HDD: 1 TB
Storage SDD: 250 GB
w+ charger',
    'J1JF5P2',
    (SELECT custodian_id FROM custodians WHERE custodian_name = 'TBD'),
    (SELECT department_id FROM departments WHERE department_name = 'I.T (Information Technology)'),
    '20-BD-1D-83-D6-B4',
    'LAPTOP-PC-FJRABAD',
    'WINDOW 11',
    126,
    (SELECT age_status_id FROM age_statuses WHERE status_name = 'OLD'),
    '2026-02-06',
    'This is originally from ms Paula but was swapped
PREVIOUS USER: JOSIE RUANES'
);

-- =========================================================
-- OPTIONAL VIEW FOR EASY REPORTING
-- =========================================================
CREATE OR REPLACE VIEW vw_inventory_details AS
SELECT
    i.inventory_id,
    i.inventory_no,
    c.company_name,
    cat.category_name,
    sc.sub_category_name,
    b.brand_name,
    i.model,
    i.item_description,
    i.serial_number,
    cu.custodian_name AS assigned_to,
    d.department_name,
    i.mac_address,
    i.device_name,
    i.current_os,
    i.device_age_months,
    ag.status_name AS age_status,
    i.purchase_date,
    i.purchase_month,
    i.purchase_year,
    i.remarks,
    i.status,
    i.created_at,
    i.updated_at
FROM inventory i
JOIN companies c        ON i.company_id = c.company_id
JOIN categories cat     ON i.category_id = cat.category_id
JOIN sub_categories sc  ON i.sub_category_id = sc.sub_category_id
JOIN brands b           ON i.brand_id = b.brand_id
LEFT JOIN custodians cu ON i.custodian_id = cu.custodian_id
LEFT JOIN departments d ON i.department_id = d.department_id
LEFT JOIN age_statuses ag ON i.age_status_id = ag.age_status_id;