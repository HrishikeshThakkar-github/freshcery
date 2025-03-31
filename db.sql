create database freshcery;


CREATE TABLE `users` (
  `id` int NOT NULL auto_increment primary key,
  `fullname` varchar(200) NOT NULL,
  `email` varchar(200) NOT NULL,
  `username` varchar(200) NOT NULL ,
  `mypassword` varchar(200) NOT NULL,
  `image` varchar(200) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `categories` (
  `id` int(3) NOT NULL primary key,
  `name` varchar(200) NOT NULL,
  `image` varchar(200) NOT NULL,
  `icon` varchar(200) NOT NULL,
  `description` varchar(200) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


INSERT INTO `categories` (`id`,`name`, `image`, `icon`, `description`)
VALUES (1,'Vegetables', 'vegetables.jpg', 'bistro-carrot', 'Fresh farm vegetables from local growers'),(2,'Fruits', 'fruits.jpg', 'bistro-apple', 'Variety of Fruits From Local Growers');


INSERT INTO `categories` (`id`,`name`, `image`, `icon`, `description`)
VALUES (3,'Frozen', 'frozen.jpg', 'bistro-french-fries', 'In house made Frozen products'),(4,'Packed products', 'package.jpg', 'bistro-appetizer', 'In house made Packed raw Products');

INSERT INTO `categories` (`id`,`name`, `image`, `icon`, `description`)
VALUES (5,'Meat', 'meat.jpg', 'bistro-chicken', 'meat');


CREATE TABLE `products` (
  `id` int(3) NOT NULL unique,
  `title` varchar(200) NOT NULL,
  `description` text NOT NULL,
  `price` varchar(10) NOT NULL,
  `quantity` int(3) NOT NULL DEFAULT 1,
  `image` varchar(200) NOT NULL,
  `exp_date` varchar(200) NOT NULL,
  `category_id` int(3) NOT NULL,
  `status` int(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `products` (`id`, `title`, `description`, `price`, `quantity`, `image`, `exp_date`, `category_id`, `status`) VALUES
(1, 'Carrots', 'Crunchy and sweet organic carrots.', '35/kg', 100, 'vegetables.jpg', '2025-04-30', 1, 1),
(2, 'mango', 'alphonso mango', '60/piece', 20, 'fruits.jpg', '2025-04-02', 2, 1),
(3, 'Tomatoes', 'Freshly grown organic tomatoes', '25/kg', 30, 'vegetables.jpg', '2025-04-20', 1, 1);


INSERT INTO `products` (`id`, `title`, `description`, `price`, `quantity`, `image`, `exp_date`, `category_id`, `status`) VALUES
(4, 'french fries', 'Crunchy fries.', '250/kg', 100, 'frozen.jpg', '2025-10-30', 3, 1),
(5, 'mackains smiley', 'stay happy with smiles', '200/kg', 20, 'frozen.jpg', '2025-10-02', 4, 1),
(6, 'mix sprouts', 'mixture of sprouts 200g', '135', 30, 'package.jpg', '2025-04-01', 4, 1);


ALTER TABLE `products`
ADD CONSTRAINT `fk_category_product`
FOREIGN KEY (`category_id`) REFERENCES `categories`(`id`)
ON DELETE CASCADE;

INSERT INTO `products` (`id`, `title`, `description`, `price`, `quantity`, `image`, `exp_date`, `category_id`, `status`) VALUES
(7, 'orange', 'simple orange', '60/500g', 20, 'fruits.jpg', '2025-04-02', 2, 1);
INSERT INTO `products` (`id`, `title`, `description`, `price`, `quantity`, `image`, `exp_date`, `category_id`, `status`) VALUES
(8, 'bannana', 'simple bannana', '60/kg', 200, 'fruits.jpg', '2025-04-05', 2, 1);



CREATE TABLE `cart` (
  `id` int NOT NULL auto_increment primary key,
  `pro_id` int NOT NULL,
  `pro_title` varchar(200) NOT NULL,
  `pro_image` varchar(200) NOT NULL,
  `pro_price` varchar(20) NOT NULL,
  `pro_qty` int NOT NULL,
  `user_id` int NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


INSERT INTO cart(pro_id,pro_title,pro_image,pro_price,pro_qty,user_id) VALUES(1,'1','1',1,1,1);