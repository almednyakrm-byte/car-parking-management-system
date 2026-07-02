CREATE TABLE users (
  id INT AUTO_INCREMENT,
  username VARCHAR(255) NOT NULL,
  email VARCHAR(255) NOT NULL,
  password VARCHAR(255) NOT NULL,
  role ENUM('guest', 'user', 'admin') NOT NULL DEFAULT 'guest',
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  UNIQUE KEY (email)
);

CREATE TABLE maqafat (
  id INT AUTO_INCREMENT,
  name VARCHAR(255) NOT NULL,
  capacity INT NOT NULL,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (id)
);

CREATE TABLE reservations (
  id INT AUTO_INCREMENT,
  user_id INT NOT NULL,
  maqa_id INT NOT NULL,
  start_time DATETIME NOT NULL,
  end_time DATETIME NOT NULL,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  FOREIGN KEY (user_id) REFERENCES users(id),
  FOREIGN KEY (maqa_id) REFERENCES maqafat(id)
);

CREATE TABLE payments (
  id INT AUTO_INCREMENT,
  reservation_id INT NOT NULL,
  amount DECIMAL(10, 2) NOT NULL,
  payment_method VARCHAR(255) NOT NULL,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  FOREIGN KEY (reservation_id) REFERENCES reservations(id)
);

INSERT INTO users (username, email, password, role)
VALUES ('admin', 'admin@example.com', '$2y$10$TKh8H1.PfQx37YgCzwiKb.KjNyWgaHb9cbcoQgdIVFlYg7B77UdFm', 'admin');

INSERT INTO maqafat (name, capacity)
VALUES ('موقف 1', 10),
       ('موقف 2', 20),
       ('موقف 3', 30);

INSERT INTO reservations (user_id, maqa_id, start_time, end_time)
VALUES (1, 1, '2024-01-01 00:00:00', '2024-01-01 01:00:00'),
       (1, 2, '2024-01-02 00:00:00', '2024-01-02 01:00:00'),
       (1, 3, '2024-01-03 00:00:00', '2024-01-03 01:00:00');

INSERT INTO payments (reservation_id, amount, payment_method)
VALUES (1, 10.00, 'cash'),
       (2, 20.00, 'credit card'),
       (3, 30.00, 'bank transfer');