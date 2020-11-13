-- PostgreSQL database dump
--

-- Dumped from database version 10.12 (Ubuntu 10.12-0ubuntu0.18.04.1)
-- Dumped by pg_dump version 10.12 (Ubuntu 10.12-0ubuntu0.18.04.1)

SET statement_timeout = 0;
SET lock_timeout = 0;
SET idle_in_transaction_session_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = on;
SELECT pg_catalog.set_config('search_path', '', false);
SET check_function_bodies = false;
SET xmloption = content;
SET client_min_messages = warning;
SET row_security = off;

ALTER TABLE ONLY test.user_role
    DROP CONSTRAINT user_role_users_id_fk;
ALTER TABLE ONLY test.user_role
    DROP CONSTRAINT user_role_role_id_fk;
ALTER TABLE ONLY test."order"
    DROP CONSTRAINT order_users_id_fk;
ALTER TABLE ONLY test."order"
    DROP CONSTRAINT order_order_status_id_fk;
ALTER TABLE ONLY test.goods
    DROP CONSTRAINT goods_categories_id_fk;
ALTER TABLE ONLY test.basket
    DROP CONSTRAINT basket_users_id_fk;
ALTER TABLE ONLY test.basket
    DROP CONSTRAINT basket_order_id_fk;
ALTER TABLE ONLY test.basket
    DROP CONSTRAINT basket_goods_id_fk;
DROP TRIGGER tr_update_basket ON test."order";
DROP TRIGGER tr_add_user_role ON test.users;
DROP INDEX test.users_id_uindex;
DROP INDEX test.users_email_uindex;
DROP INDEX test.user_role_id_uindex;
DROP INDEX test.role_role_name_uindex;
DROP INDEX test.role_id_uindex;
DROP INDEX test.order_status_id_uindex;
DROP INDEX test.order_id_uindex;
DROP INDEX test.goods_id_uindex;
DROP INDEX test.categories_id_uindex;
DROP INDEX test.basket_id_uindex;
ALTER TABLE ONLY test.users
    DROP CONSTRAINT users_pk;
ALTER TABLE ONLY test.user_role
    DROP CONSTRAINT user_role_pk;
ALTER TABLE ONLY test.role
    DROP CONSTRAINT role_pk;
ALTER TABLE ONLY test.order_status
    DROP CONSTRAINT order_status_pk;
ALTER TABLE ONLY test."order"
    DROP CONSTRAINT order_pk;
ALTER TABLE ONLY test.goods
    DROP CONSTRAINT goods_pk;
ALTER TABLE ONLY test.categories
    DROP CONSTRAINT categories_pk;
ALTER TABLE ONLY test.basket
    DROP CONSTRAINT basket_pk;
ALTER TABLE test.users
    ALTER COLUMN id DROP DEFAULT;
ALTER TABLE test.user_role
    ALTER COLUMN id DROP DEFAULT;
ALTER TABLE test.role
    ALTER COLUMN id DROP DEFAULT;
ALTER TABLE test.order_status
    ALTER COLUMN id DROP DEFAULT;
ALTER TABLE test."order"
    ALTER COLUMN id DROP DEFAULT;
ALTER TABLE test.goods
    ALTER COLUMN id DROP DEFAULT;
ALTER TABLE test.categories
    ALTER COLUMN id DROP DEFAULT;
ALTER TABLE test.basket
    ALTER COLUMN id DROP DEFAULT;
DROP SEQUENCE test.users_id_seq;
DROP SEQUENCE test.user_role_id_seq;
DROP TABLE test.user_role;
DROP SEQUENCE test.role_id_seq;
DROP TABLE test.role;
DROP SEQUENCE test.order_status_id_seq;
DROP VIEW test.order_list;
DROP TABLE test.users;
DROP VIEW test.order_info;
DROP TABLE test.order_status;
DROP SEQUENCE test.order_id_seq;
DROP TABLE test."order";
DROP VIEW test.goods_list;
DROP SEQUENCE test.goods_id_seq;
DROP TABLE test.goods;
DROP SEQUENCE test.categories_id_seq;
DROP TABLE test.categories;
DROP SEQUENCE test.basket_id_seq;
DROP TABLE test.basket;
DROP FUNCTION test.update_basket();
DROP FUNCTION test.add_user_role();
DROP SCHEMA test;
--
-- Name: test; Type: SCHEMA; Schema: -; Owner: admin
--

CREATE SCHEMA test;


ALTER SCHEMA test OWNER TO admin;

--
-- Name: add_user_role(); Type: FUNCTION; Schema: test; Owner: admin
--

CREATE FUNCTION test.add_user_role() RETURNS trigger
    LANGUAGE plpgsql
AS
$$
BEGIN
    INSERT INTO test.user_role (id_user, id_role) VALUES (NEW.id, 1);
    RETURN NEW;
END;
$$;


ALTER FUNCTION test.add_user_role() OWNER TO admin;

--
-- Name: update_basket(); Type: FUNCTION; Schema: test; Owner: admin
--

CREATE FUNCTION test.update_basket() RETURNS trigger
    LANGUAGE plpgsql
AS
$$
BEGIN
    UPDATE test.basket SET id_order = NEW.id WHERE id_user = NEW.id_user AND is_in_order = false;
    UPDATE test.basket SET is_in_order = true WHERE id_order = NEW.id;
    RETURN NEW;
END;
$$;


ALTER FUNCTION test.update_basket() OWNER TO admin;

SET default_tablespace = '';

SET default_with_oids = false;

--
-- Name: basket; Type: TABLE; Schema: test; Owner: admin
--

CREATE TABLE test.basket
(
    id          integer               NOT NULL,
    id_user     integer               NOT NULL,
    id_good     integer               NOT NULL,
    price       numeric(10, 2)        NOT NULL,
    is_in_order boolean DEFAULT false NOT NULL,
    id_order    integer
);


ALTER TABLE test.basket
    OWNER TO admin;

--
-- Name: basket_id_seq; Type: SEQUENCE; Schema: test; Owner: admin
--

CREATE SEQUENCE test.basket_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE test.basket_id_seq
    OWNER TO admin;

--
-- Name: basket_id_seq; Type: SEQUENCE OWNED BY; Schema: test; Owner: admin
--

ALTER SEQUENCE test.basket_id_seq OWNED BY test.basket.id;


--
-- Name: categories; Type: TABLE; Schema: test; Owner: admin
--

CREATE TABLE test.categories
(
    id     integer           NOT NULL,
    status integer,
    name   character varying NOT NULL
);


ALTER TABLE test.categories
    OWNER TO admin;

--
-- Name: categories_id_seq; Type: SEQUENCE; Schema: test; Owner: admin
--

CREATE SEQUENCE test.categories_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE test.categories_id_seq
    OWNER TO admin;

--
-- Name: categories_id_seq; Type: SEQUENCE OWNED BY; Schema: test; Owner: admin
--

ALTER SEQUENCE test.categories_id_seq OWNED BY test.categories.id;


--
-- Name: goods; Type: TABLE; Schema: test; Owner: admin
--

CREATE TABLE test.goods
(
    id                integer                NOT NULL,
    name              character varying      NOT NULL,
    price             numeric(10, 2)         NOT NULL,
    id_category       integer                NOT NULL,
    description       character varying(2056),
    img_address       character varying(256) NOT NULL,
    img_thumb_address character varying(256) NOT NULL,
    status            boolean DEFAULT true   NOT NULL
);


ALTER TABLE test.goods
    OWNER TO admin;

--
-- Name: goods_id_seq; Type: SEQUENCE; Schema: test; Owner: admin
--

CREATE SEQUENCE test.goods_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE test.goods_id_seq
    OWNER TO admin;

--
-- Name: goods_id_seq; Type: SEQUENCE OWNED BY; Schema: test; Owner: admin
--

ALTER SEQUENCE test.goods_id_seq OWNED BY test.goods.id;


--
-- Name: goods_list; Type: VIEW; Schema: test; Owner: admin
--

CREATE VIEW test.goods_list AS
SELECT goods.id,
       goods.name,
       goods.price,
       goods.img_thumb_address,
       goods.img_address,
       categories.name AS category,
       goods.status,
       goods.description
FROM (test.goods
         JOIN test.categories ON ((goods.id_category = categories.id)))
ORDER BY goods.id;


ALTER TABLE test.goods_list
    OWNER TO admin;

--
-- Name: order; Type: TABLE; Schema: test; Owner: admin
--

CREATE TABLE test."order"
(
    id              integer                     NOT NULL,
    id_user         integer                     NOT NULL,
    created         timestamp without time zone NOT NULL,
    id_order_status integer DEFAULT 1           NOT NULL,
    total_price     numeric(10, 2)              NOT NULL
);


ALTER TABLE test."order"
    OWNER TO admin;

--
-- Name: order_id_seq; Type: SEQUENCE; Schema: test; Owner: admin
--

CREATE SEQUENCE test.order_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE test.order_id_seq
    OWNER TO admin;

--
-- Name: order_id_seq; Type: SEQUENCE OWNED BY; Schema: test; Owner: admin
--

ALTER SEQUENCE test.order_id_seq OWNED BY test."order".id;


--
-- Name: order_status; Type: TABLE; Schema: test; Owner: admin
--

CREATE TABLE test.order_status
(
    id   integer               NOT NULL,
    name character varying(50) NOT NULL
);


ALTER TABLE test.order_status
    OWNER TO admin;

--
-- Name: order_info; Type: VIEW; Schema: test; Owner: admin
--

CREATE VIEW test.order_info AS
SELECT "order".id_user   AS user_id,
       "order".id        AS order_id,
       goods.id          AS goods_id,
       order_status.id   AS status_id,
       "order".created,
       basket.price,
       order_status.name AS status,
       goods.name        AS good_name
FROM (((test."order"
    JOIN test.basket ON (("order".id = basket.id_order)))
    JOIN test.goods ON ((basket.id_good = goods.id)))
         JOIN test.order_status ON (("order".id_order_status = order_status.id)));


ALTER TABLE test.order_info
    OWNER TO admin;

--
-- Name: users; Type: TABLE; Schema: test; Owner: admin
--

CREATE TABLE test.users
(
    id           integer                NOT NULL,
    name         character varying(128) NOT NULL,
    email        character varying(128) NOT NULL,
    password     character varying(64)  NOT NULL,
    last_actions character varying(256)
);


ALTER TABLE test.users
    OWNER TO admin;

--
-- Name: order_list; Type: VIEW; Schema: test; Owner: admin
--

CREATE VIEW test.order_list AS
SELECT "order".id,
       users.id          AS user_id,
       users.name        AS user_name,
       users.email       AS user_email,
       "order".created,
       "order".total_price,
       order_status.id   AS status_id,
       order_status.name AS status
FROM ((test."order"
    JOIN test.users ON (("order".id_user = users.id)))
         JOIN test.order_status ON (("order".id_order_status = order_status.id)))
ORDER BY "order".id DESC;


ALTER TABLE test.order_list
    OWNER TO admin;

--
-- Name: order_status_id_seq; Type: SEQUENCE; Schema: test; Owner: admin
--

CREATE SEQUENCE test.order_status_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE test.order_status_id_seq
    OWNER TO admin;

--
-- Name: order_status_id_seq; Type: SEQUENCE OWNED BY; Schema: test; Owner: admin
--

ALTER SEQUENCE test.order_status_id_seq OWNED BY test.order_status.id;


--
-- Name: role; Type: TABLE; Schema: test; Owner: admin
--

CREATE TABLE test.role
(
    id        integer           NOT NULL,
    role_name character varying NOT NULL
);


ALTER TABLE test.role
    OWNER TO admin;

--
-- Name: role_id_seq; Type: SEQUENCE; Schema: test; Owner: admin
--

CREATE SEQUENCE test.role_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE test.role_id_seq
    OWNER TO admin;

--
-- Name: role_id_seq; Type: SEQUENCE OWNED BY; Schema: test; Owner: admin
--

ALTER SEQUENCE test.role_id_seq OWNED BY test.role.id;


--
-- Name: user_role; Type: TABLE; Schema: test; Owner: admin
--

CREATE TABLE test.user_role
(
    id      integer NOT NULL,
    id_user integer NOT NULL,
    id_role integer NOT NULL
);


ALTER TABLE test.user_role
    OWNER TO admin;

--
-- Name: user_role_id_seq; Type: SEQUENCE; Schema: test; Owner: admin
--

CREATE SEQUENCE test.user_role_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE test.user_role_id_seq
    OWNER TO admin;

--
-- Name: user_role_id_seq; Type: SEQUENCE OWNED BY; Schema: test; Owner: admin
--

ALTER SEQUENCE test.user_role_id_seq OWNED BY test.user_role.id;


--
-- Name: users_id_seq; Type: SEQUENCE; Schema: test; Owner: admin
--

CREATE SEQUENCE test.users_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE test.users_id_seq
    OWNER TO admin;

--
-- Name: users_id_seq; Type: SEQUENCE OWNED BY; Schema: test; Owner: admin
--

ALTER SEQUENCE test.users_id_seq OWNED BY test.users.id;


--
-- Name: basket id; Type: DEFAULT; Schema: test; Owner: admin
--

ALTER TABLE ONLY test.basket
    ALTER COLUMN id SET DEFAULT nextval('test.basket_id_seq'::regclass);


--
-- Name: categories id; Type: DEFAULT; Schema: test; Owner: admin
--

ALTER TABLE ONLY test.categories
    ALTER COLUMN id SET DEFAULT nextval('test.categories_id_seq'::regclass);


--
-- Name: goods id; Type: DEFAULT; Schema: test; Owner: admin
--

ALTER TABLE ONLY test.goods
    ALTER COLUMN id SET DEFAULT nextval('test.goods_id_seq'::regclass);


--
-- Name: order id; Type: DEFAULT; Schema: test; Owner: admin
--

ALTER TABLE ONLY test."order"
    ALTER COLUMN id SET DEFAULT nextval('test.order_id_seq'::regclass);


--
-- Name: order_status id; Type: DEFAULT; Schema: test; Owner: admin
--

ALTER TABLE ONLY test.order_status
    ALTER COLUMN id SET DEFAULT nextval('test.order_status_id_seq'::regclass);


--
-- Name: role id; Type: DEFAULT; Schema: test; Owner: admin
--

ALTER TABLE ONLY test.role
    ALTER COLUMN id SET DEFAULT nextval('test.role_id_seq'::regclass);


--
-- Name: user_role id; Type: DEFAULT; Schema: test; Owner: admin
--

ALTER TABLE ONLY test.user_role
    ALTER COLUMN id SET DEFAULT nextval('test.user_role_id_seq'::regclass);


--
-- Name: users id; Type: DEFAULT; Schema: test; Owner: admin
--

ALTER TABLE ONLY test.users
    ALTER COLUMN id SET DEFAULT nextval('test.users_id_seq'::regclass);


--
-- Data for Name: basket; Type: TABLE DATA; Schema: test; Owner: admin
--

COPY test.basket (id, id_user, id_good, price, is_in_order, id_order) FROM stdin;
546	20	183	5699.00	t	219
547	20	184	9999.00	t	219
475	20	184	9999.00	t	190
478	21	183	5699.00	t	191
479	21	182	7899.00	t	192
480	21	184	9999.00	t	192
534	20	184	9999.00	t	213
481	22	182	7899.00	t	193
482	22	184	9999.00	t	193
483	22	183	5699.00	t	193
544	20	183	5699.00	t	218
545	20	184	9999.00	t	218
\.


--
-- Data for Name: categories; Type: TABLE DATA; Schema: test; Owner: admin
--

COPY test.categories (id, status, name) FROM stdin;
2	1	Ноутбуки
1	1	Телефоны
3	1	Обувь
\.


--
-- Data for Name: goods; Type: TABLE DATA; Schema: test; Owner: admin
--

COPY test.goods (id, name, price, id_category, description, img_address, img_thumb_address, status) FROM stdin;
182	Nike Air Zoom Pegasus 33	7899.00	3	Lorem ipsum dolor sit amet, consectetur adipisicing elit. Atque eaque et, exercitationem hic impedit labore, libero modi neque nisi quas, recusandae temporibus ut. Asperiores delectus, fugiat officiis quo sequi tempore?                	test/homework/upload/img/air_zoom_pegasus_33_shield_3.jpg	test/homework/upload/thumb/air_zoom_pegasus_33_shield_3.jpg	f
184	NIKE AIR ZOOM PEGASUS 33 SHIELD	9999.00	3	Lorem ipsum dolor sit amet, consectetur adipisicing elit. Ad aliquid amet dolorum inventore iusto laborum nemo quidem quos sunt tenetur! Ab animi doloremque earum, impedit ratione sit sunt voluptatem voluptatibus.	test/homework/upload/img/air_zoom_pegasus_33_ultd_3.jpg	test/homework/upload/thumb/air_zoom_pegasus_33_ultd_3.jpg	t
183	NIKE AIR ZOOM PEGASUS 33 ULTD	5699.00	3	Lorem ipsum dolor sit amet, consectetur adipisicing elit. Animi, atque consectetur deserunt doloremque ducimus, facilis inventore ipsa, laudantium magnam magni maiores minus odit officiis quisquam quos sint sunt suscipit vitae?	test/homework/upload/img/air_zoom_pegasus_33_3.jpg	test/homework/upload/thumb/air_zoom_pegasus_33_3.jpg	t
\.


--
-- Data for Name: order; Type: TABLE DATA; Schema: test; Owner: admin
--

COPY test."order" (id, id_user, created, id_order_status, total_price) FROM stdin;
190	20	2020-02-04 13:30:07	4	9999.00
191	21	2020-02-04 18:45:15	1	5699.00
193	22	2020-02-04 18:46:27	3	23597.00
192	21	2020-02-04 18:45:40	4	17898.00
213	20	2020-02-07 20:44:16	4	9999.00
219	20	2020-02-27 14:49:18	3	15698.00
218	20	2020-02-27 14:47:49	2	15698.00
\.


--
-- Data for Name: order_status; Type: TABLE DATA; Schema: test; Owner: admin
--

COPY test.order_status (id, name) FROM stdin;
1	В работе
2	Отгружен со склада
3	В пути
4	Доставлен
\.


--
-- Data for Name: role; Type: TABLE DATA; Schema: test; Owner: admin
--

COPY test.role (id, role_name) FROM stdin;
2	admin
1	user
\.


--
-- Data for Name: user_role; Type: TABLE DATA; Schema: test; Owner: admin
--

COPY test.user_role (id, id_user, id_role) FROM stdin;
18	20	1
19	21	1
20	22	1
22	20	2
29	23	1
\.


--
-- Data for Name: users; Type: TABLE DATA; Schema: test; Owner: admin
--

COPY test.users (id, name, email, password, last_actions) FROM stdin;
20	kelevra23	kelevra23@gmail.com	$2y$10$fMLS8ii2qdHlsssyQ/.SEuI/T6UTXhkf6kZFTdNswybpMO5I7yhXy	a:5:{i:0;s:0:"";i:1;s:5:"admin";i:2;s:11:"admin/order";i:3;s:0:"";i:4;s:11:"user/logout";}
23	kelevra26	kelevra26@gmail.com	$2y$10$A8vg/SBgKOBhie7FHrLH8.4A7gN9NKJ2j30MpSSzZ7jBya3IbJJgi	a:5:{i:0;s:7:"cabinet";i:1;s:10:"order/list";i:2;s:13:"order/view/56";i:3;s:0:"";i:4;s:11:"user/logout";}
21	kelevra24	kelevra24@gmail.com	$2y$10$ZmIL8HOajn3aQqJCDf.XnuNlsv9c0OLOepaqS/bdYzigyp1fBxGOi	a:5:{i:0;s:7:"cabinet";i:1;s:6:"basket";i:2;s:12:"order/create";i:3;s:0:"";i:4;s:11:"user/logout";}
22	kelevra25	kelevra25@gmail.com	$2y$10$Xy8aFAN6HY.T4Q24x6pZy.HrlrTuOEOCe3W3fzRWluNSAeqhNnFFK	a:5:{i:0;s:0:"";i:1;s:6:"basket";i:2;s:12:"order/create";i:3;s:0:"";i:4;s:11:"user/logout";}
\.


--
-- Name: basket_id_seq; Type: SEQUENCE SET; Schema: test; Owner: admin
--

SELECT pg_catalog.setval('test.basket_id_seq', 547, true);


--
-- Name: categories_id_seq; Type: SEQUENCE SET; Schema: test; Owner: admin
--

SELECT pg_catalog.setval('test.categories_id_seq', 3, true);


--
-- Name: goods_id_seq; Type: SEQUENCE SET; Schema: test; Owner: admin
--

SELECT pg_catalog.setval('test.goods_id_seq', 212, true);


--
-- Name: order_id_seq; Type: SEQUENCE SET; Schema: test; Owner: admin
--

SELECT pg_catalog.setval('test.order_id_seq', 219, true);


--
-- Name: order_status_id_seq; Type: SEQUENCE SET; Schema: test; Owner: admin
--

SELECT pg_catalog.setval('test.order_status_id_seq', 4, true);


--
-- Name: role_id_seq; Type: SEQUENCE SET; Schema: test; Owner: admin
--

SELECT pg_catalog.setval('test.role_id_seq', 2, true);


--
-- Name: user_role_id_seq; Type: SEQUENCE SET; Schema: test; Owner: admin
--

SELECT pg_catalog.setval('test.user_role_id_seq', 506, true);


--
-- Name: users_id_seq; Type: SEQUENCE SET; Schema: test; Owner: admin
--

SELECT pg_catalog.setval('test.users_id_seq', 118, true);


--
-- Name: basket basket_pk; Type: CONSTRAINT; Schema: test; Owner: admin
--

ALTER TABLE ONLY test.basket
    ADD CONSTRAINT basket_pk PRIMARY KEY (id);


--
-- Name: categories categories_pk; Type: CONSTRAINT; Schema: test; Owner: admin
--

ALTER TABLE ONLY test.categories
    ADD CONSTRAINT categories_pk PRIMARY KEY (id);


--
-- Name: goods goods_pk; Type: CONSTRAINT; Schema: test; Owner: admin
--

ALTER TABLE ONLY test.goods
    ADD CONSTRAINT goods_pk PRIMARY KEY (id);


--
-- Name: order order_pk; Type: CONSTRAINT; Schema: test; Owner: admin
--

ALTER TABLE ONLY test."order"
    ADD CONSTRAINT order_pk PRIMARY KEY (id);


--
-- Name: order_status order_status_pk; Type: CONSTRAINT; Schema: test; Owner: admin
--

ALTER TABLE ONLY test.order_status
    ADD CONSTRAINT order_status_pk PRIMARY KEY (id);


--
-- Name: role role_pk; Type: CONSTRAINT; Schema: test; Owner: admin
--

ALTER TABLE ONLY test.role
    ADD CONSTRAINT role_pk PRIMARY KEY (id);


--
-- Name: user_role user_role_pk; Type: CONSTRAINT; Schema: test; Owner: admin
--

ALTER TABLE ONLY test.user_role
    ADD CONSTRAINT user_role_pk PRIMARY KEY (id);


--
-- Name: users users_pk; Type: CONSTRAINT; Schema: test; Owner: admin
--

ALTER TABLE ONLY test.users
    ADD CONSTRAINT users_pk PRIMARY KEY (id);


--
-- Name: basket_id_uindex; Type: INDEX; Schema: test; Owner: admin
--

CREATE UNIQUE INDEX basket_id_uindex ON test.basket USING btree (id);


--
-- Name: categories_id_uindex; Type: INDEX; Schema: test; Owner: admin
--

CREATE UNIQUE INDEX categories_id_uindex ON test.categories USING btree (id);


--
-- Name: goods_id_uindex; Type: INDEX; Schema: test; Owner: admin
--

CREATE UNIQUE INDEX goods_id_uindex ON test.goods USING btree (id);


--
-- Name: order_id_uindex; Type: INDEX; Schema: test; Owner: admin
--

CREATE UNIQUE INDEX order_id_uindex ON test."order" USING btree (id);


--
-- Name: order_status_id_uindex; Type: INDEX; Schema: test; Owner: admin
--

CREATE UNIQUE INDEX order_status_id_uindex ON test.order_status USING btree (id);


--
-- Name: role_id_uindex; Type: INDEX; Schema: test; Owner: admin
--

CREATE UNIQUE INDEX role_id_uindex ON test.role USING btree (id);


--
-- Name: role_role_name_uindex; Type: INDEX; Schema: test; Owner: admin
--

CREATE UNIQUE INDEX role_role_name_uindex ON test.role USING btree (role_name);


--
-- Name: user_role_id_uindex; Type: INDEX; Schema: test; Owner: admin
--

CREATE UNIQUE INDEX user_role_id_uindex ON test.user_role USING btree (id);


--
-- Name: users_email_uindex; Type: INDEX; Schema: test; Owner: admin
--

CREATE UNIQUE INDEX users_email_uindex ON test.users USING btree (email);


--
-- Name: users_id_uindex; Type: INDEX; Schema: test; Owner: admin
--

CREATE UNIQUE INDEX users_id_uindex ON test.users USING btree (id);


--
-- Name: users tr_add_user_role; Type: TRIGGER; Schema: test; Owner: admin
--

CREATE TRIGGER tr_add_user_role
    AFTER INSERT
    ON test.users
    FOR EACH ROW
EXECUTE PROCEDURE test.add_user_role();


--
-- Name: order tr_update_basket; Type: TRIGGER; Schema: test; Owner: admin
--

CREATE TRIGGER tr_update_basket
    AFTER INSERT
    ON test."order"
    FOR EACH ROW
EXECUTE PROCEDURE test.update_basket();


--
-- Name: basket basket_goods_id_fk; Type: FK CONSTRAINT; Schema: test; Owner: admin
--

ALTER TABLE ONLY test.basket
    ADD CONSTRAINT basket_goods_id_fk FOREIGN KEY (id_good) REFERENCES test.goods (id) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- Name: basket basket_order_id_fk; Type: FK CONSTRAINT; Schema: test; Owner: admin
--

ALTER TABLE ONLY test.basket
    ADD CONSTRAINT basket_order_id_fk FOREIGN KEY (id_order) REFERENCES test."order" (id) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- Name: basket basket_users_id_fk; Type: FK CONSTRAINT; Schema: test; Owner: admin
--

ALTER TABLE ONLY test.basket
    ADD CONSTRAINT basket_users_id_fk FOREIGN KEY (id_user) REFERENCES test.users (id) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- Name: goods goods_categories_id_fk; Type: FK CONSTRAINT; Schema: test; Owner: admin
--

ALTER TABLE ONLY test.goods
    ADD CONSTRAINT goods_categories_id_fk FOREIGN KEY (id_category) REFERENCES test.categories (id) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- Name: order order_order_status_id_fk; Type: FK CONSTRAINT; Schema: test; Owner: admin
--

ALTER TABLE ONLY test."order"
    ADD CONSTRAINT order_order_status_id_fk FOREIGN KEY (id_order_status) REFERENCES test.order_status (id) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- Name: order order_users_id_fk; Type: FK CONSTRAINT; Schema: test; Owner: admin
--

ALTER TABLE ONLY test."order"
    ADD CONSTRAINT order_users_id_fk FOREIGN KEY (id_user) REFERENCES test.users (id) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- Name: user_role user_role_role_id_fk; Type: FK CONSTRAINT; Schema: test; Owner: admin
--

ALTER TABLE ONLY test.user_role
    ADD CONSTRAINT user_role_role_id_fk FOREIGN KEY (id_role) REFERENCES test.role (id) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- Name: user_role user_role_users_id_fk; Type: FK CONSTRAINT; Schema: test; Owner: admin
--

ALTER TABLE ONLY test.user_role
    ADD CONSTRAINT user_role_users_id_fk FOREIGN KEY (id_user) REFERENCES test.users (id) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- PostgreSQL database dump complete
--
