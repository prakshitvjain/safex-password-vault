CREATE TABLE users (
  user_id INT AUTO_INCREMENT PRIMARY KEY,
  username VARCHAR(255) NOT NULL,
  hashed_password VARCHAR(255) NOT NULL,
  created_on TIMESTAMP DEFAULT CURRENT_TIMESTAMP(),
  last_modified TIMESTAMP DEFAULT CURRENT_TIMESTAMP() ON UPDATE CURRENT_TIMESTAMP()
);

CREATE TABLE items (
  item_id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(255) NOT NULL,
  username VARCHAR(255) NOT NULL,
  password VARCHAR(255) NOT NULL,
  notes VARCHAR(255) DEFAULT NULL,
  user_id INT NOT NULL,
  FOREIGN KEY (user_id) REFERENCES users(user_id)
);

CREATE USER 'safex_signup'@'localhost' IDENTIFIED BY 'signup_password';
CREATE USER 'safex_login'@'localhost' IDENTIFIED BY 'login_password';
CREATE USER 'safex_main'@'localhost' IDENTIFIED BY 'main_password';

GRANT INSERT, SELECT ON users.* TO 'signup'@'localhost';

GRANT SELECT ON users.* TO 'login'@'localhost';

GRANT SELECT, INSERT, DELETE, UPDATE ON items.* TO 'main'@'localhost';
GRANT SELECT ON users.* TO 'main'@'localhost';

FLUSH PRIVILEGES;

