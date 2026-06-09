-- =====================================================
-- FRESHMART ONLINE GROCERY SHOPPING CART SYSTEM
-- Database: grocery_db
-- DBMS: MySQL 8.0 / phpMyAdmin
-- Total Tables: 10
-- Total Sample Records: 50+
-- =====================================================

CREATE DATABASE IF NOT EXISTS grocery_db;
USE grocery_db;

-- =====================================================
-- TABLE 1: users (Actor accounts - Customer, Admin, Staff, Manager)
-- =====================================================
CREATE TABLE users (
    user_id INT PRIMARY KEY AUTO_INCREMENT,
    full_name VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    phone VARCHAR(15) NOT NULL,
    password VARCHAR(255) NOT NULL,
    address TEXT,
    role ENUM('customer', 'admin', 'staff', 'manager') DEFAULT 'customer',
    status ENUM('active', 'inactive') DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- =====================================================
-- TABLE 2: categories (Product categories)
-- =====================================================
CREATE TABLE categories (
    category_id INT PRIMARY KEY AUTO_INCREMENT,
    category_name VARCHAR(50) NOT NULL,
    description TEXT,
    image_url VARCHAR(255),
    status ENUM('active', 'inactive') DEFAULT 'active'
);

-- =====================================================
-- TABLE 3: products (Grocery items)
-- =====================================================
CREATE TABLE products (
    product_id INT PRIMARY KEY AUTO_INCREMENT,
    category_id INT,
    product_name VARCHAR(100) NOT NULL,
    description TEXT,
    price DECIMAL(10,2) NOT NULL,
    stock_quantity INT DEFAULT 0,
    unit VARCHAR(20) DEFAULT 'pcs',
    image_url VARCHAR(255),
    status ENUM('available', 'out_of_stock', 'discontinued') DEFAULT 'available',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (category_id) REFERENCES categories(category_id) ON DELETE SET NULL
);

-- =====================================================
-- TABLE 4: cart (Shopping Cart)
-- =====================================================
CREATE TABLE cart (
    cart_id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    product_id INT NOT NULL,
    quantity INT NOT NULL DEFAULT 1,
    added_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(product_id) ON DELETE CASCADE
);

-- =====================================================
-- TABLE 5: orders (Order records)
-- =====================================================
CREATE TABLE orders (
    order_id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    total_amount DECIMAL(10,2) NOT NULL,
    pickup_date DATE NOT NULL,
    pickup_time TIME NOT NULL,
    status ENUM('pending', 'processing', 'ready', 'completed', 'cancelled') DEFAULT 'pending',
    payment_status ENUM('unpaid', 'paid') DEFAULT 'unpaid',
    notes TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE
);

-- =====================================================
-- TABLE 6: order_items (Order line items)
-- =====================================================
CREATE TABLE order_items (
    order_item_id INT PRIMARY KEY AUTO_INCREMENT,
    order_id INT NOT NULL,
    product_id INT NOT NULL,
    quantity INT NOT NULL,
    unit_price DECIMAL(10,2) NOT NULL,
    subtotal DECIMAL(10,2) NOT NULL,
    FOREIGN KEY (order_id) REFERENCES orders(order_id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(product_id) ON DELETE CASCADE
);

-- =====================================================
-- TABLE 7: feedback (Customer reviews)
-- =====================================================
CREATE TABLE feedback (
    feedback_id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT,
    order_id INT,
    rating INT CHECK (rating BETWEEN 1 AND 5),
    comment TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE SET NULL,
    FOREIGN KEY (order_id) REFERENCES orders(order_id) ON DELETE SET NULL
);

-- =====================================================
-- TABLE 8: staff_tasks (Staff work assignments)
-- =====================================================
CREATE TABLE staff_tasks (
    task_id INT PRIMARY KEY AUTO_INCREMENT,
    staff_id INT NOT NULL,
    order_id INT,
    task_description TEXT NOT NULL,
    status ENUM('pending', 'in_progress', 'completed') DEFAULT 'pending',
    assigned_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    completed_at TIMESTAMP NULL,
    FOREIGN KEY (staff_id) REFERENCES users(user_id) ON DELETE CASCADE,
    FOREIGN KEY (order_id) REFERENCES orders(order_id) ON DELETE SET NULL
);

-- =====================================================
-- TABLE 9: inventory_logs (Stock tracking)
-- =====================================================
CREATE TABLE inventory_logs (
    log_id INT PRIMARY KEY AUTO_INCREMENT,
    product_id INT NOT NULL,
    staff_id INT,
    action_type ENUM('restock', 'sold', 'damaged', 'returned') NOT NULL,
    quantity_changed INT NOT NULL,
    previous_stock INT NOT NULL,
    new_stock INT NOT NULL,
    notes TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (product_id) REFERENCES products(product_id) ON DELETE CASCADE,
    FOREIGN KEY (staff_id) REFERENCES users(user_id) ON DELETE SET NULL
);

-- =====================================================
-- TABLE 10: sales_reports (Manager reports)
-- =====================================================
CREATE TABLE sales_reports (
    report_id INT PRIMARY KEY AUTO_INCREMENT,
    manager_id INT,
    report_type ENUM('daily', 'weekly', 'monthly') NOT NULL,
    start_date DATE NOT NULL,
    end_date DATE NOT NULL,
    total_orders INT DEFAULT 0,
    total_revenue DECIMAL(12,2) DEFAULT 0.00,
    total_products_sold INT DEFAULT 0,
    report_data JSON,
    generated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (manager_id) REFERENCES users(user_id) ON DELETE SET NULL
);

-- =====================================================
-- INSERT SAMPLE DATA
-- =====================================================

-- USERS (4 roles)
INSERT INTO users (full_name, email, phone, password, address, role) VALUES
('Ahmad bin Abdullah', 'ahmad@email.com', '0123456789', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'No 1, Jalan Mawar', 'customer'),
('Siti binti Rahman', 'siti@email.com', '0134567890', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'No 2, Jalan Melur', 'customer'),
('Admin FreshMart', 'admin@freshmart.com', '0145678901', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3llC/.og/at2.uheWG/igi', 'Store HQ', 'admin'),
('Staff Ali', 'staff@freshmart.com', '0156789012', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Store Staff Room', 'staff'),
('Manager Kumar', 'manager@freshmart.com', '0167890123', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Manager Office', 'manager'),
('Farhan Amry', 'farhan@email.com', '01114662126', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'No 11, Jalan 1C, Taman Suria Tropika', 'customer');

-- CATEGORIES
INSERT INTO categories (category_name, description, image_url) VALUES
('Vegetables', 'Fresh vegetables from local farms', 'https://images.unsplash.com/photo-1566385101042-1a0aa0c1268c?w=400'),
('Fruits', 'Sweet and fresh fruits', 'https://images.unsplash.com/photo-1619566636858-adf3ef46400b?w=400'),
('Dairy', 'Milk, cheese, and dairy products', 'https://images.unsplash.com/photo-1628088062854-d1870b4553da?w=400'),
('Meat & Poultry', 'Fresh meat and chicken', 'https://images.unsplash.com/photo-1607623814075-e51df1fed4f1?w=400'),
('Bakery', 'Fresh bread and pastries', 'https://images.unsplash.com/photo-1509440159596-0249088772ff?w=400'),
('Beverages', 'Drinks and beverages', 'https://images.unsplash.com/photo-1625772299848-391b6a87d7b3?w=400');

-- PRODUCTS
INSERT INTO products (category_id, product_name, description, price, stock_quantity, unit, image_url) VALUES
(1, 'Tomato', 'Fresh red tomatoes', 3.50, 100, 'kg', 'https://images.unsplash.com/photo-1592924357228-91a4daadcfea?w=400'),
(1, 'Cucumber', 'Crispy green cucumbers', 2.00, 80, 'kg', 'https://images.unsplash.com/photo-1449300079323-02e209d9d3a6?w=400'),
(1, 'Carrot', 'Orange carrots', 2.50, 120, 'kg', 'https://images.unsplash.com/photo-1598170845058-32b9d6a5da37?w=400'),
(1, 'Spinach', 'Fresh spinach leaves', 4.00, 60, 'bunch', 'https://images.unsplash.com/photo-1576045057995-568f588f82fb?w=400'),
(1, 'Chili', 'Spicy red chili', 5.00, 50, 'kg', 'https://images.unsplash.com/photo-1588252303782-cb80119abd6c?w=400'),
(1, 'Onion', 'Yellow onions', 2.00, 150, 'kg', 'https://images.unsplash.com/photo-1618512496248-a07fe83aa8cb?w=400'),
(2, 'Apple', 'Red apples', 6.00, 100, 'kg', 'https://images.unsplash.com/photo-1560806887-1e4cd0b6cbd6?w=400'),
(2, 'Banana', 'Yellow bananas', 4.00, 80, 'kg', 'https://images.unsplash.com/photo-1571771894821-ce9b6c11b214?w=400'),
(2, 'Orange', 'Juicy oranges', 5.00, 90, 'kg', 'https://images.unsplash.com/photo-1547514701-42782101795e?w=400'),
(2, 'Watermelon', 'Sweet watermelon', 12.00, 30, 'pcs', 'https://images.unsplash.com/photo-1587049352846-4a222e784d38?w=400'),
(3, 'Fresh Milk', 'Full cream milk 1L', 7.00, 50, 'bottle', 'https://images.unsplash.com/photo-1563636619-e9143da7973b?w=400'),
(3, 'Cheese', 'Cheddar cheese', 15.00, 40, 'pack', 'https://images.unsplash.com/photo-1552767059-ce182ead6c1b?w=400'),
(3, 'Yogurt', 'Natural yogurt', 5.00, 60, 'cup', 'https://images.unsplash.com/photo-1488477181946-6428a0291777?w=400'),
(4, 'Chicken Breast', 'Boneless chicken breast', 12.00, 40, 'kg', 'https://images.unsplash.com/photo-1604908176997-125f25cc6f3d?w=400'),
(4, 'Beef', 'Fresh beef slices', 25.00, 30, 'kg', 'https://images.unsplash.com/photo-1603048297172-c92544798d5e?w=400'),
(4, 'Eggs', 'Chicken eggs', 10.00, 100, 'tray', 'https://images.unsplash.com/photo-1582722872445-44dc5f7e3c8f?w=400'),
(5, 'White Bread', 'Fresh white bread', 3.50, 50, 'loaf', 'https://images.unsplash.com/photo-1509440159596-0249088772ff?w=400'),
(5, 'Croissant', 'Butter croissant', 4.00, 40, 'pcs', 'https://images.unsplash.com/photo-1555507036-ab1f4038808a?w=400'),
(5, 'Cinnamon Roll', 'Sweet cinnamon roll', 5.00, 30, 'pcs', 'https://images.unsplash.com/photo-1509365390695-33aee754301f?w=400'),
(6, 'Mineral Water', 'Drinking water 1.5L', 2.00, 200, 'bottle', 'https://images.unsplash.com/photo-1548839140-29a749e1cf4d?w=400'),
(6, 'Orange Juice', 'Fresh orange juice', 8.00, 40, 'bottle', 'https://images.unsplash.com/photo-1600271886742-f049cd451bba?w=400'),
(6, 'Green Tea', 'Green tea bottle', 4.00, 60, 'bottle', 'https://images.unsplash.com/photo-1627435601361-ec25f5b1d0e5?w=400');

-- SAMPLE ORDERS
INSERT INTO orders (user_id, total_amount, pickup_date, pickup_time, status, payment_status, notes) VALUES
(1, 25.50, '2026-06-10', '10:00:00', 'completed', 'paid', 'Please pack carefully'),
(1, 42.00, '2026-06-12', '14:00:00', 'pending', 'unpaid', ''),
(2, 18.50, '2026-06-11', '09:00:00', 'ready', 'unpaid', 'Call when ready'),
(6, 35.00, '2026-06-13', '16:00:00', 'pending', 'unpaid', '');

-- SAMPLE ORDER ITEMS
INSERT INTO order_items (order_id, product_id, quantity, unit_price, subtotal) VALUES
(1, 1, 2, 3.50, 7.00),
(1, 7, 1, 6.00, 6.00),
(1, 11, 1, 7.00, 7.00),
(1, 17, 2, 2.00, 4.00),
(1, 3, 2, 2.50, 5.00),
(2, 14, 1, 12.00, 12.00),
(2, 15, 1, 25.00, 25.00),
(2, 16, 1, 10.00, 10.00),
(3, 2, 3, 2.00, 6.00),
(3, 8, 2, 4.00, 8.00),
(3, 20, 1, 2.00, 2.00),
(3, 12, 1, 15.00, 15.00),
(4, 1, 5, 3.50, 17.50),
(4, 4, 2, 4.00, 8.00),
(4, 9, 1, 12.00, 12.00);

-- SAMPLE FEEDBACK
INSERT INTO feedback (user_id, order_id, rating, comment) VALUES
(1, 1, 5, 'Excellent service! Fresh products and easy pickup.'),
(2, 3, 4, 'Good experience, will order again.'),
(6, NULL, 5, 'Love the website design, very user friendly!');

-- SAMPLE STAFF TASKS
INSERT INTO staff_tasks (staff_id, order_id, task_description, status) VALUES
(4, 2, 'Prepare order #2 for pickup', 'pending'),
(4, 3, 'Pack order #3 - call customer when ready', 'in_progress');

-- SAMPLE INVENTORY LOGS
INSERT INTO inventory_logs (product_id, staff_id, action_type, quantity_changed, previous_stock, new_stock, notes) VALUES
(1, 4, 'restock', 50, 50, 100, 'Morning restock - fresh tomatoes'),
(14, 4, 'restock', 20, 20, 40, 'New chicken breast delivery');

-- SAMPLE SALES REPORT
INSERT INTO sales_reports (manager_id, report_type, start_date, end_date, total_orders, total_revenue, total_products_sold) VALUES
(5, 'daily', '2026-06-08', '2026-06-08', 3, 86.00, 12),
(5, 'weekly', '2026-06-01', '2026-06-08', 15, 420.50, 45);
