CREATE TABLE users (
	id BIGINT AUTO_INCREMENT NOT NULL,
	firstname VARCHAR(100) NOT NULL,
	lastname VARCHAR(200) NOT NULL,
	email VARCHAR(100) NOT NULL,
	password VARCHAR(100) NOT NULL,
	created DATETIME NOT NULL,
	modified TIMESTAMP NOT NULL,
	PRIMARY KEY (id),
)