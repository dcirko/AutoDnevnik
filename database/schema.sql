-- Initial schema. Run through bin/database-install.php on an empty database.
-- Monetary amounts are EUR. Event dates are local calendar dates, timestamps UTC.

CREATE TABLE roles (
    id TINYINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    code VARCHAR(20) NOT NULL UNIQUE,
    name VARCHAR(50) NOT NULL,
    CONSTRAINT chk_role_code CHECK (code IN ('user', 'editor', 'admin'))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

CREATE TABLE users (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    role_id TINYINT UNSIGNED NOT NULL,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(254) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    is_active BOOLEAN NOT NULL DEFAULT TRUE,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT fk_users_role FOREIGN KEY (role_id) REFERENCES roles(id) ON DELETE RESTRICT,
    CONSTRAINT chk_user_active CHECK (is_active IN (0, 1))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

CREATE TABLE categories (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL UNIQUE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

CREATE TABLE articles (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    author_id BIGINT UNSIGNED NOT NULL,
    category_id BIGINT UNSIGNED NOT NULL,
    title VARCHAR(200) NOT NULL,
    summary VARCHAR(500) NOT NULL,
    content_html MEDIUMTEXT NOT NULL,
    status VARCHAR(20) NOT NULL DEFAULT 'draft',
    published_at DATETIME NULL,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_articles_public (status, published_at, id),
    INDEX idx_articles_category (category_id, status, published_at),
    CONSTRAINT fk_articles_author FOREIGN KEY (author_id) REFERENCES users(id) ON DELETE RESTRICT,
    CONSTRAINT fk_articles_category FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE RESTRICT,
    CONSTRAINT chk_article_status CHECK (status IN ('draft', 'published')),
    CONSTRAINT chk_article_publication CHECK (
        (status = 'draft' AND published_at IS NULL) OR
        (status = 'published' AND published_at IS NOT NULL)
    )
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

CREATE TABLE article_images (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    article_id BIGINT UNSIGNED NOT NULL UNIQUE,
    storage_path VARCHAR(255) NOT NULL UNIQUE,
    original_name VARCHAR(255) NOT NULL,
    mime_type VARCHAR(30) NOT NULL,
    size_bytes INT UNSIGNED NOT NULL,
    alt_text VARCHAR(200) NOT NULL DEFAULT '',
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_images_article FOREIGN KEY (article_id) REFERENCES articles(id) ON DELETE CASCADE,
    CONSTRAINT chk_image_mime CHECK (mime_type IN ('image/jpeg', 'image/png', 'image/webp')),
    CONSTRAINT chk_image_size CHECK (size_bytes > 0)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

CREATE TABLE vehicles (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    owner_id BIGINT UNSIGNED NOT NULL,
    name VARCHAR(100) NOT NULL,
    make VARCHAR(80) NOT NULL,
    model VARCHAR(100) NOT NULL,
    model_year SMALLINT UNSIGNED NOT NULL,
    energy_type VARCHAR(20) NOT NULL,
    initial_odometer_km INT UNSIGNED NOT NULL,
    vin VARCHAR(17) CHARACTER SET ascii COLLATE ascii_general_ci NULL,
    license_plate VARCHAR(20) NULL,
    vin_fetched_at DATETIME NULL,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    UNIQUE KEY uq_vehicle_owner_vin (owner_id, vin),
    UNIQUE KEY uq_vehicle_energy (id, energy_type),
    CONSTRAINT fk_vehicles_owner FOREIGN KEY (owner_id) REFERENCES users(id) ON DELETE RESTRICT,
    CONSTRAINT chk_vehicle_year CHECK (model_year BETWEEN 1886 AND 2100),
    CONSTRAINT chk_vehicle_energy CHECK (energy_type IN ('petrol', 'diesel', 'electricity')),
    CONSTRAINT chk_vehicle_vin_length CHECK (vin IS NULL OR CHAR_LENGTH(vin) = 17)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

CREATE TABLE service_entries (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    vehicle_id BIGINT UNSIGNED NOT NULL,
    occurred_on DATE NOT NULL,
    odometer_km INT UNSIGNED NOT NULL,
    description TEXT NOT NULL,
    workshop VARCHAR(150) NULL,
    amount_eur DECIMAL(12,2) NOT NULL,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_services_vehicle_date (vehicle_id, occurred_on, id),
    CONSTRAINT fk_services_vehicle FOREIGN KEY (vehicle_id) REFERENCES vehicles(id) ON DELETE CASCADE,
    CONSTRAINT chk_service_amount CHECK (amount_eur >= 0)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

CREATE TABLE energy_entries (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    vehicle_id BIGINT UNSIGNED NOT NULL,
    energy_type VARCHAR(20) NOT NULL,
    occurred_on DATE NOT NULL,
    odometer_km INT UNSIGNED NOT NULL,
    quantity DECIMAL(12,3) NOT NULL,
    amount_eur DECIMAL(12,2) NOT NULL,
    is_full_tank BOOLEAN NULL,
    note VARCHAR(500) NULL,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_energy_vehicle_date (vehicle_id, occurred_on, id),
    INDEX idx_energy_vehicle_odometer (vehicle_id, odometer_km, id),
    CONSTRAINT fk_energy_vehicle FOREIGN KEY (vehicle_id, energy_type)
        REFERENCES vehicles(id, energy_type),
    CONSTRAINT chk_energy_quantity CHECK (quantity > 0),
    CONSTRAINT chk_energy_amount CHECK (amount_eur >= 0),
    CONSTRAINT chk_energy_full_tank CHECK (
        (energy_type = 'electricity' AND is_full_tank IS NULL) OR
        (energy_type IN ('petrol', 'diesel') AND is_full_tank IS NOT NULL AND is_full_tank IN (0, 1))
    )
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

CREATE TABLE expenses (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    vehicle_id BIGINT UNSIGNED NOT NULL,
    occurred_on DATE NOT NULL,
    category VARCHAR(30) NOT NULL,
    description VARCHAR(500) NOT NULL,
    amount_eur DECIMAL(12,2) NOT NULL,
    odometer_km INT UNSIGNED NULL,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_expenses_vehicle_date (vehicle_id, occurred_on, id),
    CONSTRAINT fk_expenses_vehicle FOREIGN KEY (vehicle_id) REFERENCES vehicles(id) ON DELETE CASCADE,
    CONSTRAINT chk_expense_amount CHECK (amount_eur >= 0),
    CONSTRAINT chk_expense_category CHECK (category IN ('registration', 'insurance', 'parking', 'toll', 'equipment', 'other'))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

CREATE TABLE reminders (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    vehicle_id BIGINT UNSIGNED NOT NULL,
    title VARCHAR(150) NOT NULL,
    due_on DATE NULL,
    due_odometer_km INT UNSIGNED NULL,
    completed_at DATETIME NULL,
    note VARCHAR(500) NULL,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_reminders_vehicle_open (vehicle_id, completed_at, due_on),
    CONSTRAINT fk_reminders_vehicle FOREIGN KEY (vehicle_id) REFERENCES vehicles(id) ON DELETE CASCADE,
    CONSTRAINT chk_reminder_deadline CHECK (due_on IS NOT NULL OR due_odometer_km IS NOT NULL)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

CREATE TABLE schema_versions (
    version VARCHAR(50) PRIMARY KEY,
    checksum CHAR(64) NOT NULL,
    applied_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

INSERT INTO roles (code, name) VALUES ('user', 'Korisnik'), ('editor', 'Urednik'), ('admin', 'Administrator');
