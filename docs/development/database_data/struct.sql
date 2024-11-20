CREATE TABLE `Users` (
	`user_id` INTEGER AUTO_INCREMENT,
	`username` VARCHAR(255) NOT NULL,
	`password` VARCHAR(255) NOT NULL,
	`phone` VARCHAR(20) NOT NULL,
	`email` VARCHAR(255) NOT NULL,
	`language` VARCHAR(10) DEFAULT 'uk',
	`theme` VARCHAR(20) DEFAULT 'light',
	`deviceId` VARCHAR(255),
	PRIMARY KEY(`user_id`)
);


CREATE TABLE `Addresses` (
	`address_id` INTEGER AUTO_INCREMENT,
	`address` VARCHAR(255) NOT NULL,
	`status` VARCHAR(20) NOT NULL,
	`tariff_id` INTEGER,
	`balance` DECIMAL NOT NULL,
	PRIMARY KEY(`address_id`)
);


CREATE TABLE `Tariffs` (
	`tariff_id` INTEGER AUTO_INCREMENT,
	`tariff_name` VARCHAR(255) NOT NULL,
	PRIMARY KEY(`tariff_id`)
);


CREATE TABLE `Services` (
	`service_id` INTEGER AUTO_INCREMENT,
	`service_name` VARCHAR(255) NOT NULL,
	PRIMARY KEY(`service_id`)
);


CREATE TABLE `TariffServices` (
	`tariff_id` INTEGER,
	`service_id` INTEGER,
	PRIMARY KEY(`tariff_id`, `service_id`)
);


CREATE TABLE `UserAddresses` (
	`user_id` INTEGER,
	`address_id` INTEGER,
	PRIMARY KEY(`user_id`, `address_id`)
);


CREATE TABLE `AddressServices` (
	`address_id` INTEGER,
	`service_id` INTEGER,
	`ip` VARCHAR(15) NOT NULL,
	PRIMARY KEY(`address_id`, `service_id`)
);


ALTER TABLE `TariffServices`
ADD FOREIGN KEY(`tariff_id`) REFERENCES `Tariffs`(`tariff_id`)
ON UPDATE NO ACTION ON DELETE NO ACTION;
ALTER TABLE `TariffServices`
ADD FOREIGN KEY(`service_id`) REFERENCES `Services`(`service_id`)
ON UPDATE NO ACTION ON DELETE NO ACTION;
ALTER TABLE `UserAddresses`
ADD FOREIGN KEY(`user_id`) REFERENCES `Users`(`user_id`)
ON UPDATE NO ACTION ON DELETE NO ACTION;
ALTER TABLE `UserAddresses`
ADD FOREIGN KEY(`address_id`) REFERENCES `Addresses`(`address_id`)
ON UPDATE NO ACTION ON DELETE NO ACTION;
ALTER TABLE `AddressServices`
ADD FOREIGN KEY(`address_id`) REFERENCES `Addresses`(`address_id`)
ON UPDATE NO ACTION ON DELETE NO ACTION;
ALTER TABLE `AddressServices`
ADD FOREIGN KEY(`service_id`) REFERENCES `Services`(`service_id`)
ON UPDATE NO ACTION ON DELETE NO ACTION;
