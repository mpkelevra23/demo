-- Файл для импорта триггеров и видов
-- Удаляем функцию (чтобы удалить функцию, надо сначала удалить зависимый от неё триггер)
DROP FUNCTION demo.update_basket();

-- Создаём функцию для добавления id заказа в корзину и изменения статуса товара в корзине
CREATE OR REPLACE FUNCTION demo.update_basket() RETURNS TRIGGER
AS
$$
BEGIN
    UPDATE demo.basket SET id_order = NEW.id WHERE id_user = NEW.id_user AND is_in_order = false;
    UPDATE demo.basket SET is_in_order = true WHERE id_order = NEW.id;
    RETURN NEW;
END;
$$ LANGUAGE plpgsql;

-- Удаляем триггер, если он существует
DROP TRIGGER IF EXISTS tr_update_basket ON demo."order";

-- Создаём триггер на таблице заказа
CREATE TRIGGER tr_update_basket
    AFTER INSERT
    ON demo."order"
    FOR EACH ROW
EXECUTE PROCEDURE demo.update_basket();

-- Удаляем функцию (чтобы удалить функцию, надо сначала удалить зависимый от неё триггер)
DROP FUNCTION demo.add_user_role();

-- Создаём функцию для добавления id пользователя в таблицу user_role ()
CREATE OR REPLACE FUNCTION demo.add_user_role() RETURNS TRIGGER
AS
$$
BEGIN
    INSERT INTO demo.user_role (id_user, id_role) VALUES (NEW.id, 1);
    RETURN NEW;
END;
$$ LANGUAGE plpgsql;

-- Удаляем триггер, если он существует
DROP TRIGGER IF EXISTS tr_add_user_role ON demo.users;

-- Создаём триггер на таблице users
CREATE TRIGGER tr_add_user_role
    AFTER INSERT
    ON demo.users
    FOR EACH ROW
EXECUTE PROCEDURE demo.add_user_role();

-- Создаём вид order_info
CREATE VIEW demo.order_info AS
SELECT demo.order.id_user     AS user_id,
       demo.order.id          AS order_id,
       demo.goods.id          AS goods_id,
       demo.order_status.id   AS status_id,
       demo.order.created,
       demo.basket.price,
       demo.order_status.name AS status,
       demo.goods.name        AS good_name
FROM demo.order
         INNER JOIN demo.basket ON demo.order.id = demo.basket.id_order
         INNER JOIN demo.goods ON demo.basket.id_good = demo.goods.id
         INNER JOIN demo.order_status ON demo.order.id_order_status = demo.order_status.id;

-- Удаляем вид order_info
DROP VIEW demo.order_info;

-- Создаём вид order_list
CREATE VIEW demo.order_list AS
SELECT demo.order.id          AS id,
       demo.users.id          AS user_id,
       demo.users.name        AS user_name,
       demo.users.email       AS user_email,
       demo.order.created     AS created,
       demo.order.total_price AS total_price,
       demo.order_status.id   AS status_id,
       demo.order_status.name AS status
FROM demo.order
         INNER JOIN demo.users ON demo.order.id_user = demo.users.id
         INNER JOIN demo.order_status ON demo.order.id_order_status = demo.order_status.id
ORDER BY demo.order.id DESC;

-- Удаляем вид order_list
DROP VIEW demo.order_list;

-- Создаём вид goods_list
CREATE VIEW demo.goods_list AS
SELECT demo.goods.id                AS id,
       demo.goods.name              AS name,
       demo.goods.price             AS price,
       demo.goods.img_thumb_address AS img_thumb_address,
       demo.goods.img_address       AS img_address,
       demo.categories.name         AS category,
       demo.goods.status            AS status,
       demo.goods.description       AS description
FROM demo.goods
         INNER JOIN demo.categories ON demo.goods.id_category = demo.categories.id
ORDER BY demo.goods.id;

-- Удаляем вид goods_list
DROP VIEW demo.goods_list;
