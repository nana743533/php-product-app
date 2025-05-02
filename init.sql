-- 文字コード設定
SET NAMES utf8mb4;
SET CHARACTER_SET_CLIENT = utf8mb4;
SET CHARACTER_SET_CONNECTION = utf8mb4;

DROP TABLE IF EXISTS products;

CREATE TABLE products (
    product_id      INTEGER AUTO_INCREMENT PRIMARY KEY,
    product_code    INTEGER NOT NULL,
    product_name    VARCHAR(256) CHARACTER SET utf8mb4 NOT NULL,
    color           VARCHAR(16) CHARACTER SET utf8mb4,
    price           INTEGER NOT NULL,
    delete_flag     BOOLEAN NOT NULL DEFAULT FALSE,
    created_at      DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at      DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) DEFAULT CHARSET=utf8mb4;

-- 初期データ挿入
INSERT INTO products (product_code, product_name, color, price)
VALUES
    (1, 'コップ（大）', '白', 100),
    (2, 'コップ（大）', '黒', 100),
    (3, 'コップ（大）', '青', 110),
    (4, 'コップ（大）', '赤', 120),
    (5, '皿', '白', 100),
    (6, '皿', '黒', 110),
    (7, '皿', '青', 150),
    (8, '皿', '赤', 130),
    (9, '箸', '黒', 300),
    (10, '箸', '茶', 200),
    (11, 'コップ（小）', '白', 80),
    (12, 'コップ（小）', '黒', 80),
    (13, 'コップ（小）', '青', 90),
    (14, 'コップ（小）', '赤', 100);
