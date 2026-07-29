CREATE TABLE users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  username VARCHAR(255) NOT NULL,
  email VARCHAR(255) NOT NULL UNIQUE,
  password VARCHAR(255) NOT NULL,
  role ENUM('guest', 'user', 'admin') NOT NULL DEFAULT 'guest',
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  INDEX idx_email (email)
);

CREATE TABLE مواقف (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(255) NOT NULL,
  capacity INT NOT NULL,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

CREATE TABLE حجز (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT NOT NULL,
  مواقف_id INT NOT NULL,
  start_time DATETIME NOT NULL,
  end_time DATETIME NOT NULL,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (user_id) REFERENCES users(id),
  FOREIGN KEY (مواقف_id) REFERENCES مواقف(id)
);

CREATE TABLE دفع (
  id INT AUTO_INCREMENT PRIMARY KEY,
  حجز_id INT NOT NULL,
  payment_method VARCHAR(255) NOT NULL,
  payment_status ENUM('pending', 'paid') NOT NULL DEFAULT 'pending',
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (حجز_id) REFERENCES حجز(id)
);

CREATE TABLE تتبع (
  id INT AUTO_INCREMENT PRIMARY KEY,
  حجز_id INT NOT NULL,
  location VARCHAR(255) NOT NULL,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (حجز_id) REFERENCES حجز(id)
);

INSERT INTO users (username, email, password, role) VALUES
  ('admin', 'admin@example.com', '$2y$10$TKh8H1.PfQx37YgCzwiKb.KjNyWgaHb9cbcoQgdIVFlYg7B77UdFm', 'admin');

INSERT INTO مواقف (name, capacity) VALUES
  ('مواقف 1', 10),
  ('مواقف 2', 20);

INSERT INTO حجز (user_id, مواقف_id, start_time, end_time) VALUES
  (1, 1, '2022-01-01 00:00:00', '2022-01-01 01:00:00'),
  (1, 2, '2022-01-02 00:00:00', '2022-01-02 01:00:00');

INSERT INTO دفع (حجز_id, payment_method, payment_status) VALUES
  (1, 'cash', 'paid'),
  (2, 'credit card', 'pending');

INSERT INTO تتبع (حجز_id, location) VALUES
  (1, 'location 1'),
  (2, 'location 2');