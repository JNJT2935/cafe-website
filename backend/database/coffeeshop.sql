CREATE DATABASE coffeeshop;
USE coffeeshop;

CREATE TABLE menu_items (
    item_id INT AUTO_INCREMENT PRIMARY KEY,
    item_name VARCHAR(50) NOT NULL,
    description TEXT NOT NULL,
    price DECIMAL (10,2) NOT NULL,
    image VARCHAR(255) NOT NULL
);

INSERT INTO menu_items (item_name, description, price, image) VALUES
('Espresso', 'Bold in flavor, small in size. Pure coffee bliss.', 100.00, 'espresso.jpg'),
('Macchiato', 'Strong espresso delicately stained with milk foam.', 160.00, 'macchiato.jpg'),
('Americano', 'Rich espresso mixed with hot water for a lighter drink.', 110.00, 'americano.jpg'),
('Coffee Latte', 'Velvety steamed milk with espresso and light foam.', 175.00, 'latte.jpg'),
('Cappuccino', 'Rich espresso with steamed milk and thick milk foam.', 165.00, 'cappuccino.jpg'),
('Affogato', 'Espresso poured over vanilla ice cream.', 150.00, 'affogato.jpg');

CREATE TABLE cart (
    id INT AUTO_INCREMENT PRIMARY KEY,
    item_id INT NOT NULL,
    item_name VARCHAR(50) NOT NULL,
    price DECIMAL (10,2) NOT NULL,
    added_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (item_id) REFERENCES menu_items(item_id)
);