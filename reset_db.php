<?php
// Reset DB: drop known tables, recreate ecommerce schema, seed admin
try {
	$pdo = new PDO('mysql:host=localhost;dbname=fluffyplanet', 'root', '');
	$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	
	$pdo->exec('SET FOREIGN_KEY_CHECKS=0');
	$tables = [
		'order_items', 'orders', 'cart', 'animals', 'categories', 'users'
	];
	foreach ($tables as $t) {
		$pdo->exec("DROP TABLE IF EXISTS `$t`");
	}
	$pdo->exec('SET FOREIGN_KEY_CHECKS=1');

	// Create users
	$pdo->exec("CREATE TABLE users (
		id INT AUTO_INCREMENT PRIMARY KEY,
		name VARCHAR(255) NOT NULL,
		email VARCHAR(255) NOT NULL UNIQUE,
		password VARCHAR(255) NOT NULL,
		role ENUM('customer','admin','staff') NOT NULL DEFAULT 'customer'
	)");

	// Create categories
	$pdo->exec("CREATE TABLE categories (
		id INT AUTO_INCREMENT PRIMARY KEY,
		name VARCHAR(255) NOT NULL,
		image VARCHAR(255) DEFAULT NULL,
		status ENUM('active','inactive') NOT NULL DEFAULT 'active',
		created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
	)");

	// Create animals
	$pdo->exec("CREATE TABLE animals (
		id INT AUTO_INCREMENT PRIMARY KEY,
		name VARCHAR(255) NOT NULL,
		category_id INT NULL,
		age INT NOT NULL DEFAULT 0,
		gender ENUM('male','female') NULL,
		price DECIMAL(10,2) NOT NULL,
		description TEXT NULL,
		image VARCHAR(255) NULL,
		status ENUM('available','reserved','sold') NOT NULL DEFAULT 'available',
		created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
		FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE SET NULL
	)");

	// Create cart
	$pdo->exec("CREATE TABLE cart (
		id INT AUTO_INCREMENT PRIMARY KEY,
		user_id INT NOT NULL,
		animal_id INT NOT NULL,
		quantity INT NOT NULL DEFAULT 1,
		created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
		FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
		FOREIGN KEY (animal_id) REFERENCES animals(id) ON DELETE CASCADE
	)");

	// Create orders
	$pdo->exec("CREATE TABLE orders (
		id INT AUTO_INCREMENT PRIMARY KEY,
		order_number VARCHAR(50) NOT NULL UNIQUE,
		user_id INT NOT NULL,
		total_amount DECIMAL(10,2) NOT NULL,
		status ENUM('pending','confirmed','processing','shipped','delivered','cancelled') DEFAULT 'pending',
		delivery_type ENUM('pickup','delivery') NOT NULL,
		delivery_address TEXT NULL,
		delivery_fee DECIMAL(10,2) DEFAULT 0,
		payment_method ENUM('cod','gcash','bank_transfer') DEFAULT 'cod',
		payment_status ENUM('pending','paid','failed') DEFAULT 'pending',
		notes TEXT NULL,
		created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
		updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
		FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
	)");

	// Create order_items (no updated_at column)
	$pdo->exec("CREATE TABLE order_items (
		id INT AUTO_INCREMENT PRIMARY KEY,
		order_id INT NOT NULL,
		animal_id INT NOT NULL,
		quantity INT NOT NULL,
		price DECIMAL(10,2) NOT NULL,
		created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
		FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE,
		FOREIGN KEY (animal_id) REFERENCES animals(id) ON DELETE CASCADE
	)");

	// Seed admin and sample data
	$hash = password_hash('123456', PASSWORD_DEFAULT);
	$pdo->prepare("INSERT INTO users (name,email,password,role) VALUES (?,?,?,?)")
		->execute(['Admin User','admin@fluffyplanet.com',$hash,'admin']);
	$pdo->prepare("INSERT INTO users (name,email,password,role) VALUES (?,?,?,?)")
		->execute(['Kyle','kyle@gmail.com',$hash,'customer']);

	$pdo->exec("INSERT INTO categories (name) VALUES ('Cats'),('Dogs'),('Fish'),('Hamster'),('Rabbit')");
	$pdo->exec("INSERT INTO animals (name,category_id,age,gender,price,description,image,status) VALUES
		('George',1,6,'male',5000.00,'Friendly Persian cat','1757561956_d49de50442ebbc242871.png','available')");

	echo "RESET OK\n";
} catch (Throwable $e) {
	echo 'ERROR: ' . $e->getMessage() . "\n";
}


