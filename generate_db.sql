CREATE TABLE `categories` (
	`category_id` INT NOT NULL AUTO_INCREMENT,
	`subcategory_of` INT,
	`name_pl` VARCHAR(500),
	`name_en` VARCHAR(500),
	PRIMARY KEY (`category_id`)
);

CREATE TABLE `products` (
	`product_id` INT NOT NULL AUTO_INCREMENT,
	`category_id` INT NOT NULL,
	`product_code` VARCHAR(200),
	`name_pl` VARCHAR(500),
	`name_en` VARCHAR(500),
	`description_pl` VARCHAR(10000),
	`description_en` VARCHAR(10000),
	PRIMARY KEY (`product_id`)
);

CREATE TABLE `images` (
	`image_id` INT NOT NULL AUTO_INCREMENT,
	`belongs_to` INT,
	`priority` INT,
	`image_full` VARCHAR(10000),
	`image_small` VARCHAR(10000),
	PRIMARY KEY (`image_id`)
);

CREATE TABLE `accounts` (
	`user_id` INT NOT NULL AUTO_INCREMENT,
	`login` VARCHAR(100) NOT NULL UNIQUE,
	`password` VARCHAR(300) NOT NULL,
	PRIMARY KEY (`user_id`)
);

ALTER TABLE `products` AUTO_INCREMENT=10000;

ALTER TABLE `products` ADD CONSTRAINT `products_fk0` FOREIGN KEY (`category_id`) REFERENCES `categories`(`category_id`);

ALTER TABLE `images` ADD CONSTRAINT `images_fk0` FOREIGN KEY (`belongs_to`) REFERENCES `products`(`product_id`);
