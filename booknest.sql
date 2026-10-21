-- BookNest database
CREATE DATABASE IF NOT EXISTS `booknest_db` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `booknest_db`;
SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS=0;
DROP TABLE IF EXISTS order_items, orders, products, categories, users;
SET FOREIGN_KEY_CHECKS=1;
CREATE TABLE users(id INT AUTO_INCREMENT PRIMARY KEY, name VARCHAR(100) NOT NULL, email VARCHAR(150) NOT NULL UNIQUE, password VARCHAR(255) NOT NULL, role ENUM('customer','admin') DEFAULT 'customer', created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP);
CREATE TABLE categories(id INT AUTO_INCREMENT PRIMARY KEY, name VARCHAR(100) NOT NULL);
CREATE TABLE products(id INT AUTO_INCREMENT PRIMARY KEY, name VARCHAR(200) NOT NULL, description TEXT, price DECIMAL(10,2) NOT NULL, stock INT DEFAULT 0, emoji VARCHAR(20) NULL, image VARCHAR(255) DEFAULT '', category_id INT, created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP, FOREIGN KEY(category_id) REFERENCES categories(id) ON DELETE SET NULL);
CREATE TABLE orders(id INT AUTO_INCREMENT PRIMARY KEY, user_id INT NOT NULL, name VARCHAR(100), phone VARCHAR(30), address TEXT, total DECIMAL(10,2) NOT NULL, status ENUM('pending','shipped','delivered','cancelled') DEFAULT 'pending', created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP, FOREIGN KEY(user_id) REFERENCES users(id));
CREATE TABLE order_items(id INT AUTO_INCREMENT PRIMARY KEY, order_id INT NOT NULL, product_id INT NOT NULL, quantity INT NOT NULL, price DECIMAL(10,2) NOT NULL, FOREIGN KEY(order_id) REFERENCES orders(id) ON DELETE CASCADE, FOREIGN KEY(product_id) REFERENCES products(id));
-- Admin login: admin@store.com / password  (baad mein change kar lein)
INSERT INTO users(name,email,password,role) VALUES('Admin','admin@store.com','$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi','admin');
INSERT INTO categories(name) VALUES ('Novels'),('Islamic'),('Education'),('Stationery');
INSERT INTO products(name,category_id,price,stock,emoji,description) VALUES
('The Alchemist',1,1299,30,'📖','A magical story about following your dreams.'),
('Peer-e-Kamil',1,1599,20,'📕','Popular Urdu novel by Umera Ahmed.'),
('Tafseer Ibn Kathir (Urdu)',2,4999,10,'📗','Complete Quran tafseer in Urdu.'),
('Riyad-us-Saliheen',2,1899,25,'📘','Collection of authentic hadith.'),
('Matric Physics Guide',3,899,50,'📙','Complete guide with solved exercises.'),
('Learn Python Programming',3,2499,18,'💡','Beginner friendly programming book.'),
('English Grammar Made Easy',3,1199,28,'📝','Grammar rules with examples.'),
('Premium Notebook Pack',4,799,60,'📓','Pack of 5 ruled notebooks.'),
('Gel Pen Set (12)',4,499,80,'🖊️','Smooth writing colourful gel pens.'),
('Desk Organizer',4,1299,15,'🗂️','Wooden desk organizer for stationery.');
