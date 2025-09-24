-- Create the fluffyplanet database
CREATE DATABASE IF NOT EXISTS fluffyplanet;
USE fluffyplanet;

-- Create the orders table
CREATE TABLE IF NOT EXISTS orders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    customer VARCHAR(255) NOT NULL,
    gmail VARCHAR(255) NOT NULL,
    tel_number VARCHAR(50) NOT NULL,
    address TEXT NOT NULL,
    animal TEXT NOT NULL COMMENT 'JSON string containing pet details',
    total DECIMAL(10,2) NOT NULL DEFAULT 0.00,
    payment_method VARCHAR(50) DEFAULT 'COD',
    payment_status VARCHAR(50) DEFAULT 'Pending',
    order_status VARCHAR(50) DEFAULT 'Processing',
    date DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Insert some sample data (optional)
INSERT INTO orders (customer, gmail, tel_number, address, animal, total, payment_method, payment_status, order_status) VALUES
('John Doe', 'john@example.com', '+1234567890', '123 Main St, City, Country', '[{"name":"Persian Cat","price":"$150.00","qty":1}]', 150.00, 'COD', 'Paid', 'Processing'),
('Jane Smith', 'jane@example.com', '+0987654321', '456 Oak Ave, Town, Country', '[{"name":"Golden Retriever","price":"$200.00","qty":1}]', 200.00, 'G CASH', 'Paid', 'Completed');

-- Show the created table structure
DESCRIBE orders;

-- Show sample data
SELECT * FROM orders;
