/* CREATING DATABASE ac_engine */
CREATE DATABASE ticket_system DEFAULT CHARACTER SET cp1257 COLLATE cp1257_general_ci;


/* CREATE TABLE ts_users */
CREATE TABLE IF NOT EXISTS ts_users (
	id INT UNSIGNED NOT NULL AUTO_INCREMENT,
	username VARCHAR(30) NOT NULL,
	email VARCHAR(50) NOT NULL,
	userpass VARCHAR(32) NOT NULL,
	ugroup SMALLINT UNSIGNED DEFAULT 2 NOT NULL
	PRIMARY KEY(id),
	UNIQUE(username),
	UNIQUE(email)
) ENGINE = InnoDB;

/* CREATE TABLE ts_ugroup */
CREATE TABLE IF NOT EXISTS ts_ugroup (
	id SMALLINT UNSIGNED NOT NULL,
	ugroup_name VARCHAR(30) NOT NULL,
	PRIMARY KEY(id),
	UNIQUE(ugroup_name)
) ENGINE = InnoDB;

/* Adding FOREIGN KEY TO TABLE ts_users */
ALTER TABLE ts_users ADD CONSTRAINT fk_ts_users1 FOREIGN KEY(ugroup) REFERENCES ts_ugroup(id) ON DELETE CASCADE ON UPDATE CASCADE;


/* CREATE TABLE ts_ustatus */
CREATE TABLE IF NOT EXISTS ts_ustatus (
	id INT UNSIGNED NOT NULL AUTO_INCREMENT,
	user_id INT UNSIGNED NOT NULL,
	user_status_id SMALLINT UNSIGNED NOT NULL,
	logedIn DATETIME,
	logedOut DATETIME,
	PRIMARY KEY(id)
) ENGINE = InnoDB;

/* CREATE TABLE ts_user_status */
CREATE TABLE IF NOT EXISTS ts_user_status (
	id SMALLINT UNSIGNED NOT NULL,
	user_status_name VARCHAR(30) NOT NULL,
	PRIMARY KEY(id),
	UNIQUE(user_status_name)
) ENGINE = InnoDB;

/* Adding FOREIGN KEY TO TABLE ts_ustatus */
ALTER TABLE ts_ustatus ADD CONSTRAINT fk_ts_ustatus1 FOREIGN KEY(user_id) REFERENCES ts_users(id) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE ts_ustatus ADD CONSTRAINT fk_ts_ustatus2 FOREIGN KEY(user_status_id) REFERENCES ts_user_status(id) ON DELETE CASCADE ON UPDATE CASCADE;


/* CREATE TABLE ts_ticket_topic */
CREATE TABLE IF NOT EXISTS ts_ticket_topic (
	id INT UNSIGNED NOT NULL AUTO_INCREMENT,
	author_id INT UNSIGNED NOT NULL,
	recepient_id INT UNSIGNED NOT NULL,
	subject VARCHAR(255) NOT NULL,
	date_time DATETIME NOT NULL,
	category_id SMALLINT UNSIGNED NOT NULL,
	priority_id SMALLINT UNSIGNED NOT NULL,
	status_id SMALLINT UNSIGNED DEFAULT 1 NOT NULL,
	content VARCHAR(4294967295) NOT NULL,
	user_ip VARCHAR(20) NOT NULL,
	PRIMARY KEY(id)
) ENGINE = InnoDB;

/* CREATE TABLE ts_ticket_category */
CREATE TABLE IF NOT EXISTS ts_ticket_category (
	id SMALLINT UNSIGNED NOT NULL,
	category_name VARCHAR(50) NOT NULL,
	PRIMARY KEY(id),
	UNIQUE(category_name)
) ENGINE = InnoDB;

/* CREATE TABLE ts_ticket_priority */
CREATE TABLE IF NOT EXISTS ts_ticket_priority (
	id SMALLINT UNSIGNED NOT NULL,
	priority_name VARCHAR(30) NOT NULL,
	PRIMARY KEY(id),
	UNIQUE(priority_name)
) ENGINE = InnoDB;

/* CREATE TABLE ts_ticket_status */
CREATE TABLE IF NOT EXISTS ts_ticket_status (
	id SMALLINT UNSIGNED NOT NULL,
	status_name VARCHAR(30) NOT NULL,
	PRIMARY KEY(id),
	UNIQUE(status_name)
) ENGINE = InnoDB;

/* Adding FOREIGN KEY TO TABLE ts_ticket_topic */
ALTER TABLE ts_ticket_topic ADD CONSTRAINT fk_ts_ticket_topic1 FOREIGN KEY(author_id) REFERENCES ts_users(id) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE ts_ticket_topic ADD CONSTRAINT fk_ts_ticket_topic2 FOREIGN KEY(recepient_id) REFERENCES ts_users(id) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE ts_ticket_topic ADD CONSTRAINT fk_ts_ticket_topic3 FOREIGN KEY(category_id) REFERENCES ts_ticket_category(id) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE ts_ticket_topic ADD CONSTRAINT fk_ts_ticket_topic4 FOREIGN KEY(priority_id) REFERENCES ts_ticket_priority(id) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE ts_ticket_topic ADD CONSTRAINT fk_ts_ticket_topic5 FOREIGN KEY(status_id) REFERENCES ts_ticket_status(id) ON DELETE CASCADE ON UPDATE CASCADE;


/* CREATE TABLE ts_ticket_reply */
CREATE TABLE IF NOT EXISTS ts_ticket_reply (
	id INT UNSIGNED NOT NULL AUTO_INCREMENT,
	ticket_id INT UNSIGNED NOT NULL,
	resp_id INT UNSIGNED NOT NULL,
	date_time DATETIME NOT NULL,
	content VARCHAR(4294967295) NOT NULL,
	PRIMARY KEY(id)
) ENGINE = InnoDB;

/* Adding FOREIGN KEY TO TABLE ts_ticket_reply */
ALTER TABLE ts_ticket_reply ADD CONSTRAINT fk_ts_ticket_reply1 FOREIGN KEY(ticket_id) REFERENCES ts_ticket_topic(id) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE ts_ticket_reply ADD CONSTRAINT fk_ts_ticket_reply2 FOREIGN KEY(resp_id) REFERENCES ts_users(id) ON DELETE CASCADE ON UPDATE CASCADE;


/* Insert data into classifier */
INSERT INTO ts_ugroup(id, ugroup_name) VALUES(1, 'Admin');
INSERT INTO ts_ugroup(id, ugroup_name) VALUES(2, 'User');

INSERT INTO ts_ticket_category(id, category_name) VALUES(1, 'Test_category1');
INSERT INTO ts_ticket_category(id, category_name) VALUES(2, 'Test_category2');
INSERT INTO ts_ticket_category(id, category_name) VALUES(3, 'Test_category3');

INSERT INTO ts_ticket_priority(id, priority_name) VALUES(1, 'High');
INSERT INTO ts_ticket_priority(id, priority_name) VALUES(2, 'Medium');
INSERT INTO ts_ticket_priority(id, priority_name) VALUES(3, 'Low');

INSERT INTO ts_ticket_status(id, status_name) VALUES(1, 'Opened');
INSERT INTO ts_ticket_status(id, status_name) VALUES(2, 'Closed');

INSERT INTO ts_user_status(id, user_status_name) VALUES(1, 'Online');
INSERT INTO ts_user_status(id, user_status_name) VALUES(2, 'Offline');

/* CREATE VIEW ts_tickets_view */
CREATE OR REPLACE VIEW ts_tickets_view AS
SELECT topic.id, topic.date_time, cat.category_name, topic.subject, stat.status_name, prior.priority_name, topic.content, topic.user_ip, topic.author_id, users.username, ugroup.ugroup_name
FROM ts_ticket_topic AS topic, ts_ticket_category AS cat, ts_ticket_status AS stat, ts_ticket_priority AS prior, ts_users AS users, ts_ugroup AS ugroup
WHERE topic.category_id = cat.id
AND topic.status_id = stat.id
AND topic.priority_id = prior.id
AND topic.author_id = users.id
AND users.ugroup = ugroup.id
ORDER BY topic.id

/* CREATE VIEW ts_replies_view */
CREATE OR REPLACE VIEW ts_replies_view AS
SELECT reply.ticket_id, users.username, ugroup.ugroup_name, reply.date_time, reply.content, users.ugroup
FROM ts_users AS users, ts_ugroup AS ugroup, ts_ticket_reply AS reply
WHERE users.id = reply.resp_id
AND users.ugroup = ugroup.id
ORDER BY reply.id