CREATE DATABASE IF NOT EXISTS rtg_tourism CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE rtg_tourism;

CREATE TABLE IF NOT EXISTS users (
  user_id INT AUTO_INCREMENT PRIMARY KEY,
  full_name VARCHAR(120) NOT NULL,
  email VARCHAR(190) NOT NULL UNIQUE,
  password_hash VARCHAR(255) NOT NULL,
  role ENUM('admin','customer') NOT NULL DEFAULT 'customer'
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS services (
  service_id INT AUTO_INCREMENT PRIMARY KEY,
  service_name VARCHAR(150) NOT NULL,
  category VARCHAR(80),
  location VARCHAR(150),
  description TEXT,
  image_path VARCHAR(500),
  active TINYINT(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS bookings (
  booking_id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT NULL,
  service_id INT NOT NULL,
  check_in DATE NOT NULL,
  check_out DATE NOT NULL,
  guest_count INT NOT NULL,
  contact_details VARCHAR(255) NOT NULL,
  booking_reference VARCHAR(20) NOT NULL UNIQUE,
  status ENUM('Pending','Confirmed','Declined','Cancelled') NOT NULL DEFAULT 'Pending',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT fk_booking_service FOREIGN KEY (service_id) REFERENCES services(service_id),
  CONSTRAINT fk_booking_user FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE SET NULL
) ENGINE=InnoDB;

INSERT INTO services(service_name,category,location,description,image_path)
SELECT 'City Hotel Stay','Accommodation','Harare, Zimbabwe','A sample city stay for guests exploring the capital.','https://images.unsplash.com/photo-1566073771259-6a8506099945?auto=format&fit=crop&w=900&q=80'
WHERE NOT EXISTS (SELECT 1 FROM services);
INSERT INTO services(service_name,category,location,description,image_path)
SELECT 'Victoria Falls Escape','Tourism experience','Victoria Falls, Zimbabwe','A sample travel experience near one of Zimbabwe’s best-known natural attractions.','https://images.unsplash.com/photo-1509316785289-025f5b846b35?auto=format&fit=crop&w=900&q=80'
WHERE (SELECT COUNT(*) FROM services) = 1;
INSERT INTO services(service_name,category,location,description,image_path)
SELECT 'Eastern Highlands Retreat','Accommodation','Nyanga, Zimbabwe','A sample mountain retreat surrounded by scenic highland landscapes.','https://images.unsplash.com/photo-1449158743715-0a90dc1d2d8f?auto=format&fit=crop&w=900&q=80'
WHERE (SELECT COUNT(*) FROM services) = 2;
