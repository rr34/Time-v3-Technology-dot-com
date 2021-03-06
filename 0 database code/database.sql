CREATE USER 'timev3technologywebsite'@'localhost' IDENTIFIED BY 'password';

GRANT SELECT, INSERT, UPDATE ON timev3technologydotcom.* TO 'timev3technologywebsite'@'localhost';

ALTER USER 'timev3technologywebsite'@'localhost' IDENTIFIED BY 'Hiatus32Hiatus32';

FLUSH PRIVILEGES;

CREATE database timev3technologydotcom;

USE database timev3technologydotcom;

CREATE TABLE workorders (
	orderUID int(11) PRIMARY KEY AUTO_INCREMENT NOT NULL,
	customerName varchar(128) NOT NULL,
	customerEmail varchar(128) NOT NULL,
	orderNumber char(12) NOT NULL,
	requestResponse blob NOT NULL,
	quote float NOT NULL,
	discount float NOT NULL,
	tax float NOT NULL,
	billed float NOT NULL,
	status enum ('Requested','In queue','Awaiting parts','Complete awaiting payment','Archive') NOT NULL,
	paid enum ('No','Paid') NOT NULL,
	owed float NOT NULL,
	photos varchar (256) NOT NULL,
	notes blob NOT NULL
);