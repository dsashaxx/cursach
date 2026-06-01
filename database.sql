DROP VIEW IF EXISTS v_movements_full;
DROP VIEW IF EXISTS v_parts_full;
DROP TABLE IF EXISTS movements;
DROP TABLE IF EXISTS parts;
DROP TABLE IF EXISTS warehouses;
DROP TABLE IF EXISTS suppliers;
DROP TABLE IF EXISTS categories;

CREATE TABLE categories (
    id SERIAL PRIMARY KEY,
    name VARCHAR(100) NOT NULL UNIQUE,
    description TEXT
);

CREATE TABLE suppliers (
    id SERIAL PRIMARY KEY,
    name VARCHAR(100) NOT NULL UNIQUE,
    phone VARCHAR(30),
    email VARCHAR(100),
    address TEXT
);

CREATE TABLE warehouses (
    id SERIAL PRIMARY KEY,
    name VARCHAR(100) NOT NULL UNIQUE,
    address TEXT
);

CREATE TABLE parts (
    id SERIAL PRIMARY KEY,
    name VARCHAR(100) NOT NULL UNIQUE,
    number VARCHAR(50) NOT NULL,
    category_id INTEGER REFERENCES categories(id),
    supplier_id INTEGER REFERENCES suppliers(id),
    price NUMERIC(10,2) DEFAULT 0,
    stock INTEGER DEFAULT 0,
    description TEXT
);

CREATE TABLE movements (
    id SERIAL PRIMARY KEY,
    part_id INTEGER REFERENCES parts(id),
    warehouse_id INTEGER REFERENCES warehouses(id),
    type VARCHAR(20) NOT NULL,
    quantity INTEGER NOT NULL,
    date DATE DEFAULT CURRENT_DATE,
    comment TEXT
);

CREATE VIEW v_parts_full AS
SELECT
    p.id,
    p.name,
    p.number,
    c.name AS category,
    s.name AS supplier,
    p.price,
    p.stock,
    p.description
FROM parts p
LEFT JOIN categories c ON c.id = p.category_id
LEFT JOIN suppliers s ON s.id = p.supplier_id;

CREATE VIEW v_movements_full AS
SELECT
    m.id,
    p.name AS part,
    w.name AS warehouse,
    m.type,
    m.quantity,
    m.date,
    m.comment
FROM movements m
LEFT JOIN parts p ON p.id = m.part_id
LEFT JOIN warehouses w ON w.id = m.warehouse_id;

CREATE OR REPLACE PROCEDURE sp_delete_part(p_id INTEGER)
LANGUAGE plpgsql
AS $$
BEGIN
    DELETE FROM movements WHERE part_id = p_id;
    DELETE FROM parts WHERE id = p_id;
END;
$$;

CREATE OR REPLACE PROCEDURE sp_add_movement(
    p_part_id INTEGER,
    p_warehouse_id INTEGER,
    p_type VARCHAR,
    p_quantity INTEGER,
    p_comment TEXT
)
LANGUAGE plpgsql
AS $$
BEGIN
    IF p_part_id IS NULL THEN
        RAISE EXCEPTION 'Деталь не найдена';
    END IF;

    IF p_warehouse_id IS NULL THEN
        RAISE EXCEPTION 'Склад не найден';
    END IF;

    INSERT INTO movements (part_id, warehouse_id, type, quantity, comment)
    VALUES (p_part_id, p_warehouse_id, p_type, p_quantity, p_comment);

    IF p_type = 'Приход' THEN
        UPDATE parts SET stock = stock + p_quantity WHERE id = p_part_id;
    ELSE
        UPDATE parts SET stock = stock - p_quantity WHERE id = p_part_id;
    END IF;
END;
$$;

INSERT INTO categories (name, description) VALUES
('Процессоры', 'CPU'),
('Видеокарты', 'GPU'),
('Оперативная память', 'RAM'),
('Материнские платы', 'Motherboard'),
('Блоки питания', 'Power Supply'),
('SSD накопители', 'SSD'),
('Корпусы', 'Корпусы ПК'),
('Охлаждение', 'Кулеры и системы охлаждения'),
('Жесткие диски', 'HDD');

INSERT INTO suppliers (name, phone, email, address) VALUES
('DNS', '+79999999999', 'dns@mail.ru', 'Москва'),
('Regard', '+78888888888', 'regard@mail.ru', 'Москва'),
('Citilink', '+77777777777', 'citilink@mail.ru', 'Санкт-Петербург'),
('XCOM', '+76666666666', 'xcom@mail.ru', 'Казань'),
('Ozon', '+75555555555', 'ozon@mail.ru', 'Москва');

INSERT INTO warehouses (name, address) VALUES
('Главный склад', 'Москва'),
('Склад №2', 'Санкт-Петербург'),
('Склад №3', 'Казань');

INSERT INTO parts (name, number, category_id, supplier_id, price, stock, description) VALUES
('Intel Core i5', 'CPU001', 1, 1, 25000, 10, 'Процессор Intel'),
('AMD Ryzen 5 5600', 'CPU002', 1, 3, 18000, 15, 'Процессор AMD'),
('RTX 4060', 'GPU001', 2, 2, 45000, 5, 'Видеокарта NVIDIA'),
('RTX 4070', 'GPU002', 2, 2, 68000, 4, 'Видеокарта NVIDIA'),
('Kingston Fury 16GB', 'RAM001', 3, 4, 4500, 25, 'Оперативная память'),
('Corsair Vengeance 32GB', 'RAM002', 3, 5, 9800, 12, 'Оперативная память'),
('ASUS PRIME B550M', 'MB001', 4, 2, 11000, 10, 'Материнская плата'),
('MSI B760 Gaming', 'MB002', 4, 3, 14500, 7, 'Материнская плата'),
('DeepCool 650W', 'PSU001', 5, 1, 5500, 18, 'Блок питания'),
('Chieftec 750W', 'PSU002', 5, 2, 7800, 9, 'Блок питания'),
('Kingston NV2 1TB', 'SSD001', 6, 4, 5200, 30, 'SSD накопитель'),
('Samsung 980 PRO 1TB', 'SSD002', 6, 5, 8900, 14, 'SSD накопитель'),
('Zalman S2', 'CASE001', 7, 1, 4200, 8, 'Корпус'),
('DeepCool GAMMAXX 400', 'COOL001', 8, 3, 2300, 20, 'Кулер'),
('Seagate Barracuda 2TB', 'HDD001', 9, 2, 6100, 11, 'Жесткий диск');

INSERT INTO movements (part_id, warehouse_id, type, quantity, comment) VALUES
(1, 1, 'Приход', 10, 'Начальная поставка'),
(2, 1, 'Приход', 15, 'Начальная поставка'),
(3, 2, 'Приход', 5, 'Поставка видеокарт'),
(4, 2, 'Приход', 4, 'Поставка видеокарт'),
(5, 1, 'Приход', 25, 'Поставка памяти'),
(6, 3, 'Приход', 12, 'Поставка памяти'),
(7, 1, 'Приход', 10, 'Материнские платы'),
(8, 2, 'Приход', 7, 'Материнские платы'),
(9, 1, 'Приход', 18, 'Блоки питания'),
(10, 2, 'Приход', 9, 'Блоки питания'),
(11, 3, 'Приход', 30, 'SSD'),
(12, 1, 'Приход', 14, 'SSD'),
(3, 2, 'Расход', 1, 'Продажа'),
(5, 1, 'Расход', 2, 'Продажа');
