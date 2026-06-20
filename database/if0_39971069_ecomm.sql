-- phpMyAdmin SQL Dump
-- version 4.9.0.1
-- https://www.phpmyadmin.net/
--
-- Host: sql100.infinityfree.com
-- Generation Time: Nov 01, 2025 at 01:27 PM
-- Server version: 10.6.22-MariaDB
-- PHP Version: 7.2.22

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;


-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

CREATE TABLE `cart` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `cart`
--

INSERT INTO `cart` (`id`, `user_id`, `product_id`, `quantity`) VALUES
(10, 9, 212, 1),
(11, 9, 150, 1),
(12, 9, 210, 1),
(14, 14, 212, 1),
(15, 14, 170, 2),
(16, 14, 206, 3);

-- --------------------------------------------------------

--
-- Table structure for table `category`
--

CREATE TABLE `category` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `cat_slug` varchar(150) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `category`
--

INSERT INTO `category` (`id`, `name`, `cat_slug`) VALUES
(1, 'Bandhej Sarees', 'bandhej-sarees'),
(2, 'Banarasi Sarees', 'banarasi-sarees'),
(3, 'Gotta Patti Chunri Sarees', 'gotta-patti-sarees'),
(4, 'Pittan Work Sarees', 'pittan-work-sarees'),
(5, 'Printed Sarees', 'printed-sarees'),
(6, 'Pyor Gotta Patti Sarees', 'pyor-gotta-patti-sarees');

-- --------------------------------------------------------

--
-- Table structure for table `details`
--

CREATE TABLE `details` (
  `id` int(11) NOT NULL,
  `sales_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `details`
--

INSERT INTO `details` (`id`, `sales_id`, `product_id`, `quantity`) VALUES
(14, 9, 11, 2),
(15, 9, 13, 5),
(16, 9, 3, 2),
(17, 9, 1, 3),
(18, 10, 13, 3),
(19, 10, 2, 4),
(20, 10, 19, 5);

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `fullname` varchar(255) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `contact` varchar(15) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `payment_id` varchar(100) DEFAULT NULL,
  `created_on` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `id` int(11) NOT NULL,
  `order_id` int(11) DEFAULT NULL,
  `product_id` int(11) DEFAULT NULL,
  `quantity` int(11) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  `name` text NOT NULL,
  `description` text NOT NULL,
  `slug` varchar(200) NOT NULL,
  `price` double NOT NULL,
  `photo` varchar(200) NOT NULL,
  `date_view` date NOT NULL,
  `counter` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `category_id`, `name`, `description`, `slug`, `price`, `photo`, `date_view`, `counter`) VALUES
(147, 1, 'Pink Bandhej', '<p>A vibrant <strong>pink Bandhej saree</strong> featuring the classic tie-dye pattern is elegantly finished with a bold, contrasting gold border.</p>\r\n', 'pink-bandhej', 3900, 'Bandhej (37).jpg', '2025-09-18', 0),
(148, 1, 'Emerald Bandhej', '<p>A deep teal-green Bandhej saree, also featuring the traditional dotted design and finished with a complementary golden border.</p>\r\n', 'emerald-bandhej', 3900, 'Bandhej (38).jpg', '2025-09-18', 0),
(149, 1, 'Mocha Bandhej', '<p>An elegant <strong>maroon Bandhej saree</strong> with its rich, deep color and traditional dot design, accented by a striking, contrasting gold border. This classic combination is a timeless choice for festive occasions.</p>\r\n', 'mocha-bandhej', 3900, 'Bandhej (39).jpg', '2025-09-18', 0),
(150, 1, 'Cobalt Saree', 'Bandhej', 'cobalt-bandhej', 3900, 'Bandhej (40).jpg', '2025-09-18', 1),
(151, 1, 'Mahogany Saree', 'Bandhej', 'mahogany-bandhej', 3900, 'Bandhej (41).jpg', '2025-09-18', 0),
(152, 1, 'Slate Saree', 'Bandhej', 'slate-bandhej', 3900, 'Bandhej (42).jpg', '2025-09-18', 0),
(153, 1, 'Indigo Saree', 'Bandhej', 'indigo-bandhej', 3900, 'Bandhej (43).jpg', '2025-09-18', 0),
(154, 1, 'Lilac Saree', 'Bandhej', 'lilac-bandhej', 3900, 'Bandhej (44).jpg', '2025-09-18', 0),
(157, 1, 'Gotta Patti Bandhej ', '<p>Beautiful pink Bandhej saree with intricate gotta patti embroidery and golden border.</p>\r\n', 'gotta-patti-bandhej', 5500, 'gotta-patti-bandhej-1_1758375534.jpg', '2025-09-18', 0),
(159, 1, 'Gotta Patti Bandhej ', '<p>Red Bandhej saree with heavy gotta patti work and golden finishing.</p>\r\n', 'gotta-patti-bandhej', 5800, 'gotta-patti-bandhej-3_1758375578.jpg', '2025-09-18', 0),
(163, 1, 'Gotta Patti Bandhej ', '<p>Designer pink Bandhej saree with elephant and peacock motifs in gotta patti.</p>\r\n', 'gotta-patti-bandhej', 6200, 'gotta-patti-bandhej-7_1758376059.jpg', '2025-09-18', 0),
(164, 1, 'Gotta Patti Bandhej ', '<p>Traditional Bandhej saree in magenta shade with heavy border work.</p>\r\n', 'gotta-patti-bandhej', 5900, 'gotta-patti-bandhej-8_1758376232.jpg', '2025-09-18', 0),
(166, 1, 'Gotta Patti Chunri ', '<p>Red Bandhej Chunri saree with intricate gotta patti border, floral motifs, and diamond-shaped Bandhej patterns.</p>\r\n', 'gotta-patti-chunri', 4600, 'Gotta Patti Chunri (1).jpg', '2025-09-18', 0),
(167, 3, 'Red Bandhani Chunri', 'Traditional red bandhani saree with golden gotta patti border', 'red-bandhani-chunri', 4800, 'Gotta Patti Chunri (2).jpg', '2025-10-08', 1),
(169, 3, 'Designer Bandhani Chunri', 'Handcrafted bandhani chunri with floral motifs and zari work', 'designer-bandhani-chunri', 4500, 'Gotta Patti Chunri (4).jpg', '2025-10-08', 1),
(171, 3, 'Classic Red Bandhani Chunri', 'Rich red bandhani saree with detailed golden border', 'classic-red-bandhani-chunri', 4800, 'Gotta Patti Chunri (6).jpg', '2025-10-08', 1),
(172, 3, 'Red Bandhani with Floral Work', 'Chunri with red base, white bandhani dots and floral gotta motifs', 'red-floral-bandhani-chunri', 5000, 'Gotta Patti Chunri (7).jpg', '2025-10-08', 1),
(173, 3, 'Velvet Style Bandhani Chunri', 'Luxurious velvet-look bandhani chunri with intricate gotta patti border', 'velvet-style-bandhani-chunri', 4900, 'Gotta Patti Chunri (8).jpg', '2025-10-08', 1),
(176, 3, 'Red Gotta Patti Chunri - Peacock Design', 'Traditional red Chunri with intricate Gotta Patti and Peacock motifs', 'red-gotta-patti-chunri-peacock', 4399, 'Gotta Patti Chunri (13).jpg', '2025-09-18', 0),
(177, 3, 'Red Gotta Patti Chunri - Floral Border', 'Elegant Chunri featuring golden floral border embroidery with bandhani style dots', 'red-gotta-patti-chunri-floral', 5599, 'Gotta Patti Chunri (14).jpg', '2025-10-08', 1),
(178, 3, 'Red Gotta Patti Chunri - Large Floral Pattern', 'Red Chunri with central floral embroidery and detailed border work', 'red-gotta-patti-chunri-large-floral', 4799, 'Gotta Patti Chunri (15).jpg', '2025-10-08', 1),
(179, 3, 'Red Gotta Patti Chunri - Full Embroidery Border', 'Heavy border embroidered Chunri with delicate Gotta Patti details', 'red-gotta-patti-chunri-heavy-border', 4799, 'Gotta Patti Chunri (16).jpg', '2025-10-08', 1),
(180, 3, 'Red Gotta Patti Chunri - Traditional Bandhani Style', 'Traditional bandhani style Chunri enhanced with golden Gotta Patti border', 'red-gotta-patti-chunri-bandhani', 3399, 'Gotta Patti Chunri (17).jpg', '2025-10-08', 1),
(181, 3, 'Red Gotta Patti Chunri - Golden Leaf Border', 'Red Chunri featuring golden leaf motifs and Gotta Patti embellishments', 'red-gotta-patti-chunri-leaf-border', 3599, 'Gotta Patti Chunri (18).jpg', '2025-10-08', 1),
(182, 3, 'Red Gotta Patti Chunri - Wedding Special', 'Special wedding Chunri with intricate Gotta Patti and peacock motifs', 'red-gotta-patti-chunri-wedding', 3999, 'Gotta Patti Chunri (19).jpg', '2025-09-18', 0),
(183, 3, 'Red Gotta Patti Chunri - Designer Border', 'Designer Chunri with diamond patterned embroidery and golden lace border', 'red-gotta-patti-chunri-designer', 3899, 'Gotta Patti Chunri (20).jpg', '2025-10-08', 1),
(184, 3, 'Red Gotta Patti Chunri - Royal Design', 'Royal style Chunri with detailed peacock, floral motifs and fine border work', 'red-gotta-patti-chunri-royal', 3399, 'Gotta Patti Chunri (21).jpg', '2025-10-08', 1),
(185, 3, 'Red Bandhani Chunri - Scalloped Silver Border', 'A vibrant red bandhani chunri featuring a beautiful silver gota patti border with a scalloped edge and delicate floral designs. The main fabric has a classic dotted pattern with an elegant central motif.', 'red-bandhani-chunri-scalloped-silver-border', 2650, 'Gotta Patti Chunri (22).jpg', '2025-10-08', 1),
(186, 3, 'Red Bandhani Chunri - Silver Floral and Paisley', 'A traditional red chunri with a series of wavy and intricate bandhani patterns across the body. The chunri is finished with a striking silver gotta patti border featuring paisley and floral designs.', 'red-bandhani-chunri-silver-floral-paisley', 2800, 'Gotta Patti Chunri (23).jpg', '2025-10-08', 1),
(187, 3, 'Deep Red Chunri - Sun Motif and Silver Border', 'A stunning chunri in a deep red shade, showcasing a detailed bandhani tie-and-dye pattern with large, bright sun-like motifs. It is embellished with a decorative silver gotta patti border.', 'deep-red-chunri-sun-motif-silver-border', 3450, 'Gotta Patti Chunri (24).jpg', '2025-10-08', 1),
(188, 3, 'Red and Orange Chunri - Wavy Silver Gota Work', 'An elegant chunri in red and orange with intricate wavy and paisley patterns in silver gotta patti. The border is finished with a detailed floral motif design, perfect for festive wear.', 'red-orange-chunri-wavy-silver-gota', 3050, 'Gotta Patti Chunri (25).jpg', '2025-10-08', 1),
(189, 3, 'Pinkish-Red Bandhani Chunri - Sunburst Gota Patti', 'A vibrant chunri in a beautiful mix of pink and red. This piece features a traditional dotted bandhani pattern with large, eye-catching sunburst motifs and a silver gotta patti border.', 'pinkish-red-bandhani-chunri-sunburst-gota-patti', 3500, 'Gotta Patti Chunri (26).jpg', '2025-10-08', 1),
(190, 3, 'Red Chunri - Detailed Silver Gota Patti Border', 'A classic red bandhani chunri that is heavily adorned with intricate silver gotta patti work. The detailed border features a scalloped edge and elaborate floral designs, adding a rich look to the piece.', 'red-chunri-detailed-silver-gota-patti-border', 4000, 'Gotta Patti Chunri (27).jpg', '2025-10-08', 1),
(191, 3, 'Red and Orange Chunri - Large Floral Motifs', 'This chunri combines a traditional red and orange bandhani pattern with large floral motifs scattered across the fabric. The border is a wide, intricate silver gotta patti design, creating a bold and beautiful statement.', 'red-orange-chunri-large-floral-motifs', 3300, 'Gotta Patti Chunri (28).jpg', '2025-10-08', 1),
(192, 3, 'Red Bandhani Chunri - Diamond and Circular Motifs', 'A traditional red bandhani chunri featuring a large diamond-shaped motif and several concentric circular patterns. The border is heavily embellished with silver gotta patti in a floral design, adding elegance to the piece.', 'red-bandhani-chunri-diamond-circular-motifs', 2600, 'Gotta Patti Chunri (29).jpg', '2025-09-18', 0),
(193, 3, 'Red Bandhani Chunri - Golden Wavy Gota Work', 'A rich red chunri with a traditional dotted bandhani pattern and a luxurious gold gotta patti border. The intricate design features wavy and circular patterns, giving it a classic and festive appeal.', 'red-bandhani-chunri-golden-wavy-gota', 3700, 'Gotta Patti Chunri (30).jpg', '2025-10-08', 1),
(194, 3, 'Red Chunri - Silver and Gold Gota Patti Border', 'A beautiful red chunri with a traditional bandhani pattern. It is uniquely embellished with both silver and gold gotta patti work, creating a dual-toned, rich border effect with wavy and paisley motifs.', 'red-chunri-silver-gold-gota-patti-border', 3300, 'Gotta Patti Chunri (31).jpg', '2025-10-08', 1),
(195, 3, 'Dark Red Chunri - Orange Sun Motifs', 'A dark red chunri with a beautiful bandhani pattern. It is decorated with large, vibrant orange sun-like motifs and a detailed silver gota patti border, giving it a rich and festive appearance.', 'dark-red-chunri-orange-sun-motifs', 2800, 'Gotta Patti Chunri (32).jpg', '2025-10-08', 1),
(196, 3, 'Maroon Chunri - Gold Gota Patti and Paisley', 'A stunning maroon chunri with a traditional bandhani pattern. The entire piece is richly adorned with intricate gold gota patti work, featuring elegant paisley and floral designs, perfect for weddings and special events.', 'maroon-chunri-gold-gota-patti-paisley', 4600, 'Gotta Patti Chunri (33).jpg', '2025-10-08', 1),
(197, 3, 'Maroon Chunri - Silver and Gold Gota Patti', 'A stunning maroon chunri with a traditional bandhani pattern. The entire piece is richly adorned with intricate silver and gold gota patti work, featuring elegant paisley and floral designs, perfect for special events.', 'maroon-chunri-silver-gold-gota-patti', 3250, 'Gotta Patti Chunri (34).jpg', '2025-09-18', 0),
(198, 3, 'Maroon Chunri - Wavy Silver Gota Patti', 'This maroon chunri features a traditional bandhani pattern with wavy and circular designs. The wide silver gota patti border, adorned with a floral and leaf pattern, adds a touch of sophistication.', 'maroon-chunri-wavy-silver-gota-patti', 4850, 'Gotta Patti Chunri (35).jpg', '2025-10-08', 1),
(199, 3, 'Dark Red Chunri - Large Sun Motifs', 'A dark red chunri with a traditional bandhani pattern. It is decorated with large, vibrant orange and silver sun-like motifs and a detailed silver gota patti border, giving it a rich and festive appearance.', 'dark-red-chunri-large-sun-motifs', 4300, 'Gotta Patti Chunri (36).jpg', '2025-10-08', 1),
(200, 3, 'Maroon Chunri - Leafy Silver Gota Patti', 'This elegant maroon chunri features a subtle bandhani pattern and is bordered with intricate silver gota patti work. The border showcases a beautiful leafy and floral design, perfect for adding a touch of grace to any outfit.', 'maroon-chunri-leafy-silver-gota-patti', 4900, 'Gotta Patti Chunri (37).jpg', '2025-10-08', 1),
(201, 3, 'Red and Orange Chunri - Circular Gota Patti', 'A folded red and orange chunri with intricate bandhani patterns in concentric circles and diamonds. The piece is bordered with a detailed silver gota patti design featuring floral motifs.', 'red-orange-chunri-circular-gota-patti', 2800, 'Gotta Patti Chunri (38).jpg', '2025-10-08', 1),
(202, 3, 'Maroon Bandhani Chunri - Floral Sun Motifs', 'This maroon chunri features a traditional dotted bandhani pattern with large, eye-catching floral-sun motifs. The border is finished with intricate silver gotta patti work, perfect for any festive occasion.', 'maroon-bandhani-chunri-floral-sun-motifs', 2950, 'Gotta Patti Chunri (39).jpg', '2025-10-08', 1),
(203, 3, 'Maroon Chunri - Silver Floral and Paisley', 'A traditional maroon chunri with a series of wavy and intricate bandhani patterns across the body. The chunri is finished with a striking silver gota patti border featuring paisley and floral designs.', 'maroon-chunri-silver-floral-paisley', 2850, 'Gotta Patti Chunri (40).jpg', '2025-10-08', 1),
(204, 3, 'Maroon Chunri - Leafy Border with Silver Gota Patti', 'This maroon chunri features an elegant bandhani pattern with scattered floral motifs. The border is a highlight, with intricate silver gota patti work showcasing a beautiful leafy and floral design.', 'maroon-chunri-leafy-border-silver-gota-patti', 4500, 'Gotta Patti Chunri (41).jpg', '2025-10-08', 1),
(205, 3, 'Folded Red Chunri - Gota Patti Sun and Floral', 'A folded red chunri showcasing a beautiful central sun motif and other floral designs in silver gota patti. The entire piece is bordered with intricate silver gota patti work, highlighting its craftsmanship.', 'folded-red-chunri-gota-patti-sun-floral', 2900, 'Gotta Patti Chunri (42).jpg', '2025-10-08', 1),
(206, 3, 'Red Silk Chunri - Gold Gota Patti and Checkered Pattern', 'A luxurious red silk chunri with a lustrous finish. The chunri features a unique checkered bandhani pattern and is bordered with a rich gold gota patti design, perfect for an opulent and traditional look.', 'red-silk-chunri-gold-gota-patti-checkered', 3500, 'Gotta Patti Chunri (43).jpg', '2025-09-20', 3),
(207, 3, 'Maroon Chunri - Pink and Silver Paisley', 'A maroon chunri with a traditional bandhani pattern and a detailed silver gota patti border. The border features a beautiful paisley design with subtle pink accents, adding a unique touch of color.', 'maroon-chunri-pink-silver-paisley', 3200, 'Gotta Patti Chunri (44).jpg', '2025-10-09', 1),
(208, 3, 'Red Chunri - Intricate Golden Gota Patti', 'A stunning red chunri with a grid-like pattern of golden gota patti. The border is heavily embellished with intricate golden embroidery and mirror work, giving the piece a rich and luxurious feel.', 'red-chunri-intricate-golden-gota-patti', 2800, 'Gotta Patti Chunri (45).jpg', '2025-11-01', 1),
(209, 3, 'Maroon Chunri - Leafy and Floral Gota Patti', 'This elegant maroon chunri features a subtle bandhani pattern and is bordered with intricate silver gota patti work. The border showcases a beautiful leafy and floral design with small, scattered motifs.', 'maroon-chunri-leafy-floral-gota-patti', 3700, 'Gotta Patti Chunri (46).jpg', '2025-10-08', 1),
(210, 3, 'Red Chunri - Large Circular Gota Patti Motif', 'A striking red chunri featuring a very large, intricate circular motif made of silver gota patti. The rest of the chunri has a classic bandhani pattern and a beautiful floral silver gota patti border.', 'red-chunri-large-circular-gota-patti-motif', 3100, 'Gotta Patti Chunri (47).jpg', '2025-09-19', 1),
(211, 3, 'Red and Orange Bandhani Chunri - Sunbursts', 'A vibrant chunri in red and orange, showcasing a traditional dotted bandhani pattern with large, bright sunburst motifs. It is finished with a decorative silver and gold gota patti border, perfect for festive occasions.', 'red-orange-bandhani-chunri-sunbursts', 4900, 'Gotta Patti Chunri (48).jpg', '2025-10-08', 1),
(212, 3, 'Maroon Chunri - Orange Sun Motifs with Silver Gota', 'A maroon chunri featuring a traditional bandhani dotted pattern and large orange sun-like motifs. It is finished with a wide and intricate silver gotta patti border, perfect for adding a touch of traditional elegance to any outfit.', 'maroon-chunri-orange-sun-motifs-silver-gota', 3100, 'Gotta Patti Chunri (49).jpg', '2025-09-26', 1),
(216, 1, 'Navy Blue and White Bandhej Saree', '<p>A striking navy blue Bandhej saree with a large, intricate white and light blue circular pattern on the pallu and a bold gold striped border.</p>\r\n', 'navy-blue-and-white-bandhej-saree', 4500, 'navy-blue-and-white-bandhej-saree_1758550174.jpg', '2025-09-21', 0),
(217, 1, 'Teal Bandhej Saree', 'A rich teal Bandhej saree featuring small yellow dot patterns and a golden pallu with detailed motifs of human figures.', 'teal-bandhej-saree', 4200, 'teal-bandhej-saree_1758550483.jpg', '2025-09-21', 0),
(218, 1, 'Light Purple Bandhej Saree', 'A delicate light purple Bandhej saree with subtle white tie-dye dots and a wide, elegant golden border on the pallu.', 'light-purple-bandhej-saree', 3800, 'light-purple-bandhej-saree_1758551343.jpg', '2025-09-21', 0),
(219, 1, 'Red and Rainbow Bandhej Saree', 'A striking Bandhej saree with a red top half and a vibrant rainbow-striped bottom half, finished with a classic red and gold border.', 'red-rainbow-bandhej-saree', 5000, 'red-rainbow-bandhej-saree_1758551373.jpg', '2025-09-21', 0),
(220, 1, 'Classic Red Bandhej Saree', 'A traditional red Bandhej saree adorned with small white and yellow tie-dye dot patterns and a pallu featuring intricate white paisley designs and a golden border.', 'classic-red-bandhej-saree', 4100, 'classic-red-bandhej-saree_1758551399.jpg', '2025-09-21', 0),
(221, 1, 'Rainbow and Red Bandhej Saree', 'A vibrant Bandhej saree featuring a red top portion and a multi-colored rainbow-striped design, leading to a traditional red and gold woven border.', 'rainbow-red-bandhej-saree', 5000, 'rainbow-red-bandhej-saree_1758551425.jpg', '2025-09-21', 0),
(222, 1, 'Purple Geometric Bandhej Saree', 'A sophisticated purple Bandhej saree with complex geometric patterns in white and orange, complemented by an ornate golden border.', 'purple-geometric-bandhej-saree', 4800, 'purple-geometric-bandhej-saree_1758551448.jpg', '2025-09-21', 0),
(223, 1, 'Green Bandhej Saree', 'A bright green Bandhej saree with all-over intricate white and yellow patterns forming floral and geometric shapes.', 'green-bandhej-saree', 4300, 'green-bandhej-saree_1758551479.jpg', '2025-09-21', 0),
(224, 1, 'Coral Red Bandhej Saree', 'A beautiful coral red Bandhej saree with subtle white and orange tie-dye dots arranged in floral and diamond-shaped motifs across the fabric.', 'coral-red-bandhej-saree', 4100, 'coral-red-bandhej-saree_1758551505.jpg', '2025-09-21', 0),
(225, 1, 'Red and Pink Diamond Bandhej Saree', 'A vibrant Bandhej saree in a red and pink color combination, featuring distinct diamond-shaped patterns with intricate floral and dot motifs.', 'red-pink-diamond-bandhej-saree', 5200, 'red-pink-diamond-bandhej-saree_1758551541.jpg', '2025-09-21', 0),
(226, 1, 'Hot Pink and Red Bandhej Saree', 'A stunning hot pink Bandhej saree with prominent red and orange diamond patterns, each with a detailed floral center.', 'hot-pink-red-bandhej-saree', 5100, 'hot-pink-red-bandhej-saree_1758551578.jpg', '2025-09-21', 0),
(227, 1, 'Hot Pink Circle Bandhej Saree', 'This hot pink Bandhej saree features two large, prominent circular patterns created from fine white tie-dye dots, giving it a classic and delicate look.', 'hot-pink-circle-bandhej-saree', 4900, 'hot-pink-circle-bandhej-saree_1758551632.jpg', '2025-09-21', 0),
(228, 1, 'Green Circle Bandhej Saree', 'A bright green Bandhej saree with two prominent circular patterns created from fine white tie-dye dots, complemented by scattered dots across the fabric.', 'green-circle-bandhej-saree', 5000, 'green-circle-bandhej-saree_1758551671.jpg', '2025-09-21', 0),
(229, 1, 'Red and Orange Bandhej Saree', 'A vibrant red Bandhej saree with two large circular patterns that transition from orange to yellow and a deep red center, and fine wave-like patterns.', 'red-orange-bandhej-saree', 5300, 'red-orange-bandhej-saree_1758551794.jpg', '2025-09-21', 0),
(230, 1, 'Red and Hot Pink Circle Bandhej Saree', 'This vibrant red Bandhej saree features two large, circular designs in a bold hot pink with finely detailed concentric patterns.', 'red-hot-pink-circle-bandhej-saree', 5200, 'red-hot-pink-circle-bandhej-saree_1758551901.jpg', '2025-09-21', 0),
(231, 1, 'Red, Pink, and Gold Brocade Saree', 'A luxurious Bandhej saree that transitions from deep red to hot pink, adorned with a heavy gold woven brocade pattern and a broad, intricate gold border.', 'red-pink-gold-brocade-saree', 6500, 'red-pink-gold-brocade-saree_1758551961.jpg', '2025-09-21', 0),
(232, 1, 'Green and Gold Brocade Saree', 'An elegant dark green Bandhej saree with a rich, all-over gold woven brocade pattern featuring large paisley and geometric motifs, and a wide, detailed border.', 'green-gold-brocade-saree', 6800, 'green-gold-brocade-saree_1758552023.jpg', '2025-09-21', 0),
(233, 1, 'Blue, Green, and Gold Brocade Saree', 'A stunning saree that fades from royal blue to teal green, featuring an elaborate gold woven brocade pattern of floral and geometric shapes with a wide, decorative border.', 'blue-green-gold-brocade-saree', 6700, 'blue-green-gold-brocade-saree_1758552101.jpg', '2025-09-21', 0),
(234, 1, 'Magenta and Gold Brocade Saree', 'A rich magenta and hot pink Bandhej saree with a broad, ornate gold woven border and an all-over intricate gold pattern, reminiscent of a Banarasi weave.', 'magenta-gold-brocade-saree', 6600, 'magenta-gold-brocade-saree_1758552146.jpg', '2025-09-21', 0),
(235, 1, 'Royal Blue and Gold Brocade Saree', 'An elegant royal blue saree with an all-over heavy gold brocade pattern, including large paisley-like motifs and a diamond pattern, and a wide, intricate gold border.', 'royal-blue-gold-brocade-saree', 7200, 'royal-blue-gold-brocade-saree_1758552278.jpg', '2025-09-21', 0),
(236, 1, 'Pink, Green, and Gold Brocade Saree', 'A multi-colored saree that transitions from magenta and pink to green and gold, covered in a heavy gold brocade pattern featuring intricate leaf and floral motifs.', 'pink-green-gold-brocade-saree', 7300, 'pink-green-gold-brocade-saree_1758552327.jpg', '2025-09-21', 0),
(237, 1, 'Red and Gold Banarasi-style Saree', 'A traditional red saree with a lush gold woven pattern that combines large oval motifs with smaller floral patterns, and a wide, intricate gold border.', 'red-gold-banarasi-style-saree', 7000, 'red-gold-banarasi-style-saree_1758552367.jpg', '2025-09-21', 0),
(238, 1, 'Red and Gold Brocade Saree', 'This stunning red saree is adorned with a rich, all-over gold brocade pattern, featuring a mix of a large, intricate diamond pattern and a detailed leaf motif, with a heavy, ornate gold border.', 'red-gold-brocade-saree', 7100, 'red-gold-brocade-saree_1758552411.jpg', '2025-09-21', 0),
(239, 1, 'Teal Bandhej Saree', 'A beautiful teal Bandhej saree with an all-over fine white dot pattern and a pallu highlighted by a wide, striking gold border.', 'teal-bandhej-saree', 4500, 'teal-bandhej-saree_1758552578.jpg', '2025-09-21', 0),
(240, 1, 'Golden Yellow Bandhej Saree', 'A bright golden yellow Bandhej saree with small white dot patterns, and a wide, shimmery gold border on the pallu.', 'golden-yellow-bandhej-saree', 4400, 'golden-yellow-bandhej-saree_1758552721.jpg', '2025-09-21', 0),
(249, 1, 'Golden-Pink-Red Gotta Patti Bandhej', 'Oval and floral gold zari design', 'golden-pink-red-zari', 5700, 'golden-pink-red-zari_1758552902.jpg', '2025-09-21', 0),
(261, 3, 'Red Bandhani Chunri', '<p>Traditional red bandhani saree with golden gotta patti border</p>\r\n', 'red-bandhani-chunri', 4800, 'Gotta Patti Chunri (2).jpg', '2025-09-21', 0),
(262, 3, 'Magenta Bandhani Chunri', 'Elegant magenta bandhani saree with golden border', 'magenta-bandhani-chunri', 4800, 'Gotta Patti Chunri (3).jpg', '2025-10-08', 1),
(263, 3, 'Designer Bandhani Chunri', 'Handcrafted bandhani chunri with floral motifs and zari work', 'designer-bandhani-chunri', 4500, 'Gotta Patti Chunri (4).jpg', '2025-09-21', 0),
(264, 3, 'Multicolor Bandhani Chunri', 'Red saree with multicolor bandhani designs and gotta work', 'multicolor-bandhani-chunri', 4500, 'Gotta Patti Chunri (5).jpg', '2025-10-08', 1),
(265, 3, 'Classic Red Bandhani Chunri', 'Rich red bandhani saree with detailed golden border', 'classic-red-bandhani-chunri', 4800, 'Gotta Patti Chunri (6).jpg', '2025-09-21', 0),
(266, 3, 'Red Bandhani with Floral Work', 'Chunri with red base, white bandhani dots and floral gotta motifs', 'red-floral-bandhani-chunri', 5000, 'Gotta Patti Chunri (7).jpg', '2025-09-21', 0),
(267, 3, 'Velvet Style Bandhani Chunri', 'Luxurious velvet-look bandhani chunri with intricate gotta patti border', 'velvet-style-bandhani-chunri', 4900, 'Gotta Patti Chunri (8).jpg', '2025-09-21', 0),
(268, 3, 'Heavy Border Bandhani Chunri', 'Saree with heavy embroidery border and colorful motifs', 'heavy-border-bandhani-chunri', 6000, 'Gotta Patti Chunri (9).jpg', '2025-10-08', 1),
(269, 3, 'Dark Red Chunri - Intricate Silver Gota Patti', 'A stunning dark red chunri with a traditional bandhani pattern. The entire piece is richly adorned with intricate silver gota patti work, featuring elegant paisley and floral designs, perfect for weddings and special events.', 'dark-red-chunri-intricate-silver-gota-patti', 6200, 'Gotta Patti Chunri (12).jpg', '2025-10-08', 1),
(270, 3, 'Red Gotta Patti Chunri - Peacock Design', 'Traditional red Chunri with intricate Gotta Patti and Peacock motifs', 'red-gotta-patti-chunri-peacock', 4399, 'Gotta Patti Chunri (13).jpg', '2025-09-21', 0),
(271, 3, 'Red Gotta Patti Chunri - Floral Border', 'Elegant Chunri featuring golden floral border embroidery with bandhani style dots', 'red-gotta-patti-chunri-floral', 5599, 'Gotta Patti Chunri (14).jpg', '2025-09-21', 0),
(272, 3, 'Red Gotta Patti Chunri - Large Floral Pattern', 'Red Chunri with central floral embroidery and detailed border work', 'red-gotta-patti-chunri-large-floral', 4799, 'Gotta Patti Chunri (15).jpg', '2025-09-21', 0),
(273, 3, 'Red Gotta Patti Chunri - Full Embroidery Border', 'Heavy border embroidered Chunri with delicate Gotta Patti details', 'red-gotta-patti-chunri-heavy-border', 4799, 'Gotta Patti Chunri (16).jpg', '2025-09-21', 0),
(274, 3, 'Red Gotta Patti Chunri - Traditional Bandhani Style', 'Traditional bandhani style Chunri enhanced with golden Gotta Patti border', 'red-gotta-patti-chunri-bandhani', 3399, 'Gotta Patti Chunri (17).jpg', '2025-09-21', 0),
(275, 3, 'Red Gotta Patti Chunri - Golden Leaf Border', 'Red Chunri featuring golden leaf motifs and Gotta Patti embellishments', 'red-gotta-patti-chunri-leaf-border', 3599, 'Gotta Patti Chunri (18).jpg', '2025-09-21', 0),
(276, 3, 'Red Gotta Patti Chunri - Wedding Special', 'Special wedding Chunri with intricate Gotta Patti and peacock motifs', 'red-gotta-patti-chunri-wedding', 3999, 'Gotta Patti Chunri (19).jpg', '2025-09-21', 0),
(277, 3, 'Red Gotta Patti Chunri - Designer Border', 'Designer Chunri with diamond patterned embroidery and golden lace border', 'red-gotta-patti-chunri-designer', 3899, 'Gotta Patti Chunri (20).jpg', '2025-09-21', 0),
(278, 3, 'Red Gotta Patti Chunri - Royal Design', 'Royal style Chunri with detailed peacock, floral motifs and fine border work', 'red-gotta-patti-chunri-royal', 3399, 'Gotta Patti Chunri (21).jpg', '2025-09-21', 0),
(279, 3, 'Red Bandhani Chunri - Scalloped Silver Border', 'A vibrant red bandhani chunri featuring a beautiful silver gota patti border with a scalloped edge and delicate floral designs. The main fabric has a classic dotted pattern with an elegant central motif.', 'red-bandhani-chunri-scalloped-silver-border', 2650, 'Gotta Patti Chunri (22).jpg', '2025-09-21', 0),
(280, 3, 'Red Bandhani Chunri - Silver Floral and Paisley', 'A traditional red chunri with a series of wavy and intricate bandhani patterns across the body. The chunri is finished with a striking silver gotta patti border featuring paisley and floral designs.', 'red-bandhani-chunri-silver-floral-paisley', 2800, 'Gotta Patti Chunri (23).jpg', '2025-09-21', 0),
(281, 3, 'Deep Red Chunri - Sun Motif and Silver Border', 'A stunning chunri in a deep red shade, showcasing a detailed bandhani tie-and-dye pattern with large, bright sun-like motifs. It is embellished with a decorative silver gotta patti border.', 'deep-red-chunri-sun-motif-silver-border', 3450, 'Gotta Patti Chunri (24).jpg', '2025-09-21', 0),
(282, 3, 'Red and Orange Chunri - Wavy Silver Gota Work', 'An elegant chunri in red and orange with intricate wavy and paisley patterns in silver gotta patti. The border is finished with a detailed floral motif design, perfect for festive wear.', 'red-orange-chunri-wavy-silver-gota', 3050, 'Gotta Patti Chunri (25).jpg', '2025-09-21', 0),
(283, 3, 'Pinkish-Red Bandhani Chunri - Sunburst Gota Patti', 'A vibrant chunri in a beautiful mix of pink and red. This piece features a traditional dotted bandhani pattern with large, eye-catching sunburst motifs and a silver gotta patti border.', 'pinkish-red-bandhani-chunri-sunburst-gota-patti', 3500, 'Gotta Patti Chunri (26).jpg', '2025-09-21', 0),
(284, 3, 'Red Chunri - Detailed Silver Gota Patti Border', 'A classic red bandhani chunri that is heavily adorned with intricate silver gotta patti work. The detailed border features a scalloped edge and elaborate floral designs, adding a rich look to the piece.', 'red-chunri-detailed-silver-gota-patti-border', 4000, 'Gotta Patti Chunri (27).jpg', '2025-09-21', 0),
(285, 3, 'Red and Orange Chunri - Large Floral Motifs', 'This chunri combines a traditional red and orange bandhani pattern with large floral motifs scattered across the fabric. The border is a wide, intricate silver gotta patti design, creating a bold and beautiful statement.', 'red-orange-chunri-large-floral-motifs', 3300, 'Gotta Patti Chunri (28).jpg', '2025-09-21', 0),
(286, 3, 'Red Bandhani Chunri - Diamond and Circular Motifs', 'A traditional red bandhani chunri featuring a large diamond-shaped motif and several concentric circular patterns. The border is heavily embellished with silver gotta patti in a floral design, adding elegance to the piece.', 'red-bandhani-chunri-diamond-circular-motifs', 2600, 'Gotta Patti Chunri (29).jpg', '2025-09-21', 0),
(287, 3, 'Red Bandhani Chunri - Golden Wavy Gota Work', 'A rich red chunri with a traditional dotted bandhani pattern and a luxurious gold gotta patti border. The intricate design features wavy and circular patterns, giving it a classic and festive appeal.', 'red-bandhani-chunri-golden-wavy-gota', 3700, 'Gotta Patti Chunri (30).jpg', '2025-09-21', 0),
(288, 3, 'Red Chunri - Silver and Gold Gota Patti Border', 'A beautiful red chunri with a traditional bandhani pattern. It is uniquely embellished with both silver and gold gotta patti work, creating a dual-toned, rich border effect with wavy and paisley motifs.', 'red-chunri-silver-gold-gota-patti-border', 3300, 'Gotta Patti Chunri (31).jpg', '2025-09-21', 0),
(289, 3, 'Dark Red Chunri - Orange Sun Motifs', 'A dark red chunri with a beautiful bandhani pattern. It is decorated with large, vibrant orange sun-like motifs and a detailed silver gota patti border, giving it a rich and festive appearance.', 'dark-red-chunri-orange-sun-motifs', 2800, 'Gotta Patti Chunri (32).jpg', '2025-09-21', 0),
(290, 3, 'Maroon Chunri - Gold Gota Patti and Paisley', 'A stunning maroon chunri with a traditional bandhani pattern. The entire piece is richly adorned with intricate gold gota patti work, featuring elegant paisley and floral designs, perfect for weddings and special events.', 'maroon-chunri-gold-gota-patti-paisley', 4600, 'Gotta Patti Chunri (33).jpg', '2025-09-21', 0),
(291, 3, 'Maroon Chunri - Silver and Gold Gota Patti', 'A stunning maroon chunri with a traditional bandhani pattern. The entire piece is richly adorned with intricate silver and gold gota patti work, featuring elegant paisley and floral designs, perfect for special events.', 'maroon-chunri-silver-gold-gota-patti', 3250, 'Gotta Patti Chunri (34).jpg', '2025-09-21', 0),
(292, 3, 'Maroon Chunri - Wavy Silver Gota Patti', 'This maroon chunri features a traditional bandhani pattern with wavy and circular designs. The wide silver gota patti border, adorned with a floral and leaf pattern, adds a touch of sophistication.', 'maroon-chunri-wavy-silver-gota-patti', 4850, 'Gotta Patti Chunri (35).jpg', '2025-09-21', 0),
(293, 3, 'Dark Red Chunri - Large Sun Motifs', 'A dark red chunri with a traditional bandhani pattern. It is decorated with large, vibrant orange and silver sun-like motifs and a detailed silver gota patti border, giving it a rich and festive appearance.', 'dark-red-chunri-large-sun-motifs', 4300, 'Gotta Patti Chunri (36).jpg', '2025-09-21', 0),
(294, 3, 'Maroon Chunri - Leafy Silver Gota Patti', 'This elegant maroon chunri features a subtle bandhani pattern and is bordered with intricate silver gota patti work. The border showcases a beautiful leafy and floral design, perfect for adding a touch of grace to any outfit.', 'maroon-chunri-leafy-silver-gota-patti', 4900, 'Gotta Patti Chunri (37).jpg', '2025-09-21', 0),
(295, 3, 'Red and Orange Chunri - Circular Gota Patti', 'A folded red and orange chunri with intricate bandhani patterns in concentric circles and diamonds. The piece is bordered with a detailed silver gota patti design featuring floral motifs.', 'red-orange-chunri-circular-gota-patti', 2800, 'Gotta Patti Chunri (38).jpg', '2025-09-21', 0),
(296, 3, 'Maroon Bandhani Chunri - Floral Sun Motifs', 'This maroon chunri features a traditional dotted bandhani pattern with large, eye-catching floral-sun motifs. The border is finished with intricate silver gotta patti work, perfect for any festive occasion.', 'maroon-bandhani-chunri-floral-sun-motifs', 2950, 'Gotta Patti Chunri (39).jpg', '2025-09-21', 0),
(297, 3, 'Maroon Chunri - Silver Floral and Paisley', 'A traditional maroon chunri with a series of wavy and intricate bandhani patterns across the body. The chunri is finished with a striking silver gota patti border featuring paisley and floral designs.', 'maroon-chunri-silver-floral-paisley', 2850, 'Gotta Patti Chunri (40).jpg', '2025-09-21', 0),
(298, 3, 'Maroon Chunri - Leafy Border with Silver Gota Patti', 'This maroon chunri features an elegant bandhani pattern with scattered floral motifs. The border is a highlight, with intricate silver gota patti work showcasing a beautiful leafy and floral design.', 'maroon-chunri-leafy-border-silver-gota-patti', 4500, 'Gotta Patti Chunri (41).jpg', '2025-09-21', 0),
(299, 3, 'Folded Red Chunri - Gota Patti Sun and Floral', 'A folded red chunri showcasing a beautiful central sun motif and other floral designs in silver gota patti. The entire piece is bordered with intricate silver gota patti work, highlighting its craftsmanship.', 'folded-red-chunri-gota-patti-sun-floral', 2900, 'Gotta Patti Chunri (42).jpg', '2025-09-21', 0),
(300, 3, 'Red Silk Chunri - Gold Gota Patti and Checkered Pattern', 'A luxurious red silk chunri with a lustrous finish. The chunri features a unique checkered bandhani pattern and is bordered with a rich gold gota patti design, perfect for an opulent and traditional look.', 'red-silk-chunri-gold-gota-patti-checkered', 3500, 'Gotta Patti Chunri (43).jpg', '2025-09-21', 0),
(301, 3, 'Maroon Chunri - Pink and Silver Paisley', 'A maroon chunri with a traditional bandhani pattern and a detailed silver gota patti border. The border features a beautiful paisley design with subtle pink accents, adding a unique touch of color.', 'maroon-chunri-pink-silver-paisley', 3200, 'Gotta Patti Chunri (44).jpg', '2025-09-21', 0),
(302, 3, 'Red Chunri - Intricate Golden Gota Patti', 'A stunning red chunri with a grid-like pattern of golden gota patti. The border is heavily embellished with intricate golden embroidery and mirror work, giving the piece a rich and luxurious feel.', 'red-chunri-intricate-golden-gota-patti', 2800, 'Gotta Patti Chunri (45).jpg', '2025-09-21', 0),
(303, 3, 'Maroon Chunri - Leafy and Floral Gota Patti', 'This elegant maroon chunri features a subtle bandhani pattern and is bordered with intricate silver gota patti work. The border showcases a beautiful leafy and floral design with small, scattered motifs.', 'maroon-chunri-leafy-floral-gota-patti', 3700, 'Gotta Patti Chunri (46).jpg', '2025-09-21', 0),
(304, 3, 'Red Chunri - Large Circular Gota Patti Motif', 'A striking red chunri featuring a very large, intricate circular motif made of silver gota patti. The rest of the chunri has a classic bandhani pattern and a beautiful floral silver gota patti border.', 'red-chunri-large-circular-gota-patti-motif', 3100, 'Gotta Patti Chunri (47).jpg', '2025-09-21', 0),
(305, 3, 'Red and Orange Bandhani Chunri - Sunbursts', 'A vibrant chunri in red and orange, showcasing a traditional dotted bandhani pattern with large, bright sunburst motifs. It is finished with a decorative silver and gold gota patti border, perfect for festive occasions.', 'red-orange-bandhani-chunri-sunbursts', 4900, 'Gotta Patti Chunri (48).jpg', '2025-09-21', 0),
(306, 3, 'Maroon Chunri - Orange Sun Motifs with Silver Gota', 'A maroon chunri featuring a traditional bandhani dotted pattern and large orange sun-like motifs. It is finished with a wide and intricate silver gotta patti border, perfect for adding a touch of traditional elegance to any outfit.', 'maroon-chunri-orange-sun-motifs-silver-gota', 3100, 'Gotta Patti Chunri (49).jpg', '2025-09-21', 0),
(308, 4, 'Crimson Red Pittan Work Saree', 'A striking crimson red saree with classic Pittan work, making it an ideal choice for celebrations and traditional events.', 'crimson-red-pittan-work-saree', 3750, 'Pittan work (1).jpg', '2025-10-08', 1),
(309, 4, 'Pink Pittan Work Saree', 'Embrace a vibrant look with this beautiful pink saree, highlighted by exquisite Pittan work. The striking color combined with the detailed craftsmanship on the border and pallu offers a festive and celebratory feel.', 'pink-pittan-work-saree', 3750, 'Pittan work (2).jpg', '2025-10-08', 1),
(310, 4, 'Mauve Pittan Work Saree', 'A stunning Mauve saree adorned with delicate Pittan work. The soft color and shimmering gold embroidery create a regal look, ideal for evening events and formal gatherings where you want to make a sophisticated statement.', 'mauve-pittan-work-saree', 3750, 'Pittan work (3).jpg', '2025-10-08', 1),
(311, 4, 'Green Pittan Work Saree', 'This elegant green saree features intricate Pittan work, a traditional Rajasthani embroidery technique. The rich detailing on the border and scattered motifs on the body make it perfect for weddings, festivals, and other special occasions.', 'green-pittan-work-saree', 3750, 'Pittan work (4).jpg', '2025-09-21', 0),
(312, 4, 'Magenta Pittan Work Saree', 'A gorgeous magenta saree with intricate Pittan work, making it a bold and beautiful statement piece for any celebration.', 'magenta-pittan-work-saree', 3750, 'Pittan work (5).jpg', '2025-10-08', 1),
(313, 4, 'Plum Purple Pittan Work Saree', 'A royal plum purple saree featuring exquisite Pittan work along the borders, offering a sophisticated and elegant look.', 'plum-purple-pittan-work-saree', 3750, 'Pittan work (6).jpg', '2025-10-08', 1),
(314, 4, 'Fuchsia Pink Pittan Work Saree', 'A vibrant fuchsia saree adorned with detailed Pittan work on the borders and small motifs across the body, perfect for festive occasions.', 'fuchsia-pink-pittan-work-saree', 3750, 'Pittan work (7).jpg', '2025-10-08', 1),
(315, 4, 'Mustard Yellow Pittan Work Saree', 'A vibrant mustard yellow saree with contrasting silver Pittan work on the border and motifs, offering a bright and cheerful look.', 'mustard-yellow-pittan-work-saree', 3750, 'Pittan work (8).jpg', '2025-10-08', 1),
(316, 4, 'Dusty Rose Pittan Work Saree', 'This elegant saree in a dusty rose shade features a delicate self-woven floral pattern and a scalloped border with intricate Pittan work.', 'dusty-rose-pittan-work-saree', 3750, 'Pittan work (9).jpg', '2025-10-08', 1),
(317, 4, 'Dusty Rose Pittan Work Saree', 'This elegant saree features a self-woven floral pattern and a scalloped border with intricate Pittan work, creating a sophisticated and luxurious look.', 'dusty-rose-pittan-work-saree', 3750, 'Pittan work (10).jpg', '2025-09-21', 0),
(318, 4, 'Violet Crushed Pittan Work Saree', 'This unique violet saree features a crushed fabric texture with delicate gold Pittan motifs and a scalloped border, perfect for a contemporary festive style.', 'violet-crushed-pittan-work-saree', 3350, 'Pittan work (11).jpg', '2025-10-08', 1),
(319, 4, 'Aqua Blue Pittan Work Saree', 'This soothing aqua blue saree features a subtle self-woven pattern and is beautifully embellished with detailed Pittan work on the borders, perfect for a chic and elegant event.', 'aqua-blue-pittan-work-saree', 3750, 'Pittan work (12).jpg', '2025-10-08', 1),
(320, 4, 'Mint Green Pittan Work Saree', 'This serene mint green saree features a subtle self-woven pattern with shimmering Pittan work on the borders, creating a graceful and light festive outfit.', 'mint-green-pittan-work-saree', 3750, 'Pittan work (13).jpg', '2025-10-08', 1),
(321, 4, 'Dusty Rose Pittan Saree with Floral Pattern', 'This luxurious dusty rose saree combines a self-woven floral pattern with a beautiful scalloped border, meticulously handcrafted with Pittan work and small bead detailing.', 'dusty-rose-pittan-saree-floral-pattern', 3750, 'Pittan work (14).jpg', '2025-10-08', 1),
(322, 4, 'Mustard Yellow Pittan Saree with Scalloped Border', 'A vibrant mustard yellow saree featuring an intricately designed scalloped border with shimmering silver and pink Pittan work, perfect for adding a pop of color to any festive look.', 'mustard-yellow-pittan-saree-scalloped-border', 3750, 'Pittan work (15).jpg', '2025-10-08', 1),
(323, 4, 'Forest Green Pittan Saree with Scalloped Border', 'A rich forest green saree with a classic look, highlighted by a detailed scalloped border featuring a blend of silver and pink Pittan work and fine embroidery.', 'forest-green-pittan-saree-scalloped-border', 3750, 'Pittan work (16).jpg', '2025-10-09', 1),
(324, 4, 'Off-White Crushed Saree with Pittan Motifs', 'An elegant off-white saree with a stylish crushed texture, beautifully embellished with scattered golden Pittan motifs and a matching scalloped border for a sophisticated appearance.', 'off-white-crushed-saree-pittan-motifs', 3350, 'Pittan work (17).jpg', '2025-09-21', 0),
(325, 4, 'Dusty Blue Crushed Saree with Pittan Motifs', 'This elegant saree in a beautiful dusty blue shade features a crushed texture and is adorned with delicate Pittan motifs scattered across the body, finished with a simple scalloped border.', 'dusty-blue-crushed-saree-pittan-motifs', 3350, 'Pittan work (18).jpg', '2025-10-08', 1),
(326, 5, 'Navy Blue Paisley Printed Saree', 'A comfortable and stylish printed saree featuring a striking navy blue base with intricate red and white paisley and geometric patterns, ideal for daily wear or casual events.', 'navy-blue-paisley-printed-saree', 3750, 'Printed (1).jpg', '2025-10-08', 1),
(327, 5, 'Purple Paisley Printed Saree', 'A comfortable and stylish printed saree featuring a rich purple base with intricate pink and white paisley and geometric patterns, ideal for daily wear or casual events.', 'purple-paisley-printed-saree', 3750, 'Printed (2).jpg', '2025-09-21', 0),
(328, 5, 'Red Paisley Printed Saree', 'A comfortable and stylish printed saree featuring a vibrant red base with intricate black and white paisley and geometric patterns, ideal for daily wear or casual events.', 'red-paisley-printed-saree', 3750, 'Printed (3).jpg', '2025-10-30', 1),
(329, 5, 'Navy Blue Paisley Printed Saree', 'A comfortable and stylish printed saree featuring a striking navy blue base with intricate red and white paisley and geometric patterns, ideal for daily wear or casual events.', 'navy-blue-paisley-printed-saree', 3750, 'Printed (4).jpg', '2025-09-21', 0),
(330, 5, 'Pink Floral Motif Printed Saree', 'A rich pink saree with a delicate all-over floral motif and a heavy, ornate border, perfect for weddings and formal gatherings.', 'pink-floral-motif-printed-saree', 3050, 'Printed (5).jpg', '2025-10-08', 1),
(331, 5, 'Magenta Elephant and Geometric Printed Saree', 'A stunning magenta saree with a detailed elephant and geometric pattern on the pallu and body, perfect for special occasions and festive wear.', 'magenta-elephant-geometric-printed-saree', 3050, 'Printed (6).jpg', '2025-10-08', 1),
(332, 5, 'Purple Floral Bandhani Printed Saree', 'A purple saree with a traditional floral bandhani print, featuring a detailed border and a unique floral design on the pallu, suitable for traditional events.', 'purple-floral-bandhani-printed-saree', 3050, 'Printed (7).jpg', '2025-10-08', 1),
(333, 5, 'Red Geometric Floral Printed Saree', 'A vibrant red saree with a detailed geometric and floral print, featuring a striking floral border, ideal for festive events and celebrations.', 'red-geometric-floral-printed-saree', 3050, 'Printed (8).jpg', '2025-09-21', 0),
(334, 5, 'Pink Geometric Floral Printed Saree', 'A vibrant pink saree with a detailed geometric and floral print, featuring a striking floral border, ideal for festive events and celebrations.', 'pink-geometric-floral-printed-saree', 3050, 'Printed (9).jpg', '2025-09-21', 0),
(335, 5, 'Purple Floral Motif Printed Saree', 'A rich purple saree with a delicate all-over floral motif and a heavy, ornate border, perfect for weddings and formal gatherings.', 'purple-floral-motif-printed-saree', 3050, 'Printed (10).jpg', '2025-09-21', 0),
(336, 5, 'Purple Elephant and Geometric Printed Saree', 'A stunning purple saree with a detailed elephant and geometric pattern on the pallu and body, perfect for special occasions and festive wear.', 'purple-elephant-geometric-printed-saree', 3050, 'Printed (11).jpg', '2025-11-01', 1),
(337, 5, 'Purple Geometric Floral Printed Saree', 'A vibrant purple saree with a detailed geometric and floral print, featuring a striking floral border, ideal for festive events and celebrations.', 'purple-geometric-floral-printed-saree', 3050, 'Printed (12).jpg', '2025-09-21', 0),
(338, 5, 'Magenta Floral Bandhani Printed Saree', 'A magenta saree with a traditional floral bandhani print, featuring a detailed border and a unique floral design on the pallu, suitable for traditional events.', 'magenta-floral-bandhani-printed-saree', 3050, 'Printed (13).jpg', '2025-10-08', 1),
(339, 5, 'Pink Floral Bandhani Printed Saree', 'A pink saree with a traditional floral bandhani print, featuring a detailed border and a unique floral design on the pallu, suitable for traditional events.', 'pink-floral-bandhani-printed-saree', 3050, 'Printed (14).jpg', '2025-09-21', 0),
(340, 5, 'Pink Elephant and Geometric Printed Saree', 'A stunning pink saree with a detailed elephant and geometric pattern on the pallu and body, perfect for special occasions and festive wear.', 'pink-elephant-geometric-printed-saree', 3050, 'Printed (15).jpg', '2025-10-08', 1),
(341, 5, 'Yellow Elephant and Geometric Printed Saree', 'A stunning yellow saree with a detailed elephant and geometric pattern on the pallu and body, perfect for special occasions and festive wear.', 'yellow-elephant-geometric-printed-saree', 3050, 'Printed (16).jpg', '2025-09-21', 0),
(342, 5, 'Pink Floral Motif Printed Saree', 'A vibrant pink saree featuring large, intricate floral motifs, perfect for a chic and traditional look.', 'pink-floral-motif-printed-saree', 3050, 'Printed (17).jpg', '2025-09-21', 0),
(343, 5, 'Dark Purple Elephant and Geometric Printed Saree', 'A stunning dark purple saree with a detailed elephant and geometric pattern on the pallu and body, perfect for special occasions and festive wear.', 'dark-purple-elephant-geometric-printed-saree', 3050, 'Printed (18).jpg', '2025-09-21', 0),
(344, 5, 'Purple Floral Bandhani Printed Saree', 'A purple saree with a traditional floral bandhani print, featuring a detailed border and a unique floral design on the pallu, suitable for traditional events.', 'purple-floral-bandhani-printed-saree', 3050, 'Printed (19).jpg', '2025-09-21', 0),
(345, 5, 'Red Elephant and Geometric Saree', 'A stunning red saree with a detailed elephant and geometric pattern on the pallu and body, perfect for special occasions and festive wear.', 'red-elephant-and-geometric-saree', 3050, 'Printed (20).jpg', '2025-10-08', 1),
(346, 5, 'Magenta Floral Bandhani Saree', 'A magenta saree with a traditional floral bandhani print, featuring a detailed border and a unique floral design on the pallu, suitable for traditional events.', 'magenta-floral-bandhani-saree', 3050, 'Printed (21).jpg', '2025-10-08', 1),
(347, 5, 'Purple Floral Motif Saree', 'A rich purple saree with a delicate all-over floral motif and a heavy, ornate border, perfect for weddings and formal gatherings.', 'purple-floral-motif-saree', 3050, 'Printed (22).jpg', '2025-11-01', 1),
(348, 5, 'Pink Floral Motif Saree', 'A rich pink saree with a delicate all-over floral motif and a heavy, ornate border, perfect for weddings and formal gatherings.', 'pink-floral-motif-saree', 3050, 'Printed (23).jpg', '2025-10-08', 1),
(349, 5, 'Pink Geometric and Floral Saree', 'A vibrant pink saree with a detailed geometric and floral print, featuring a striking floral border, ideal for festive events and celebrations.', 'pink-geometric-and-floral-saree', 3050, 'Printed (24).jpg', '2025-10-08', 1),
(350, 5, 'Red Geometric and Floral Saree', 'A vibrant red saree with a detailed geometric and floral print, featuring a striking floral border, ideal for festive events and celebrations.', 'red-geometric-and-floral-saree', 3050, 'Printed (25).jpg', '2025-10-08', 1),
(351, 5, 'Dark Purple Floral Bandhani Saree', 'A dark purple saree with a traditional floral bandhani print, featuring a detailed border and a unique floral design on the pallu, suitable for traditional events.', 'dark-purple-floral-bandhani-saree', 3050, 'Printed (26).jpg', '2025-10-08', 1),
(352, 5, 'Maroon Floral Motif Saree', 'A rich maroon saree with a delicate all-over floral motif and a heavy, ornate border, perfect for weddings and formal gatherings.', 'maroon-floral-motif-saree', 3050, 'Printed (27).jpg', '2025-10-08', 1),
(353, 5, 'Yellow Geometric and Floral Saree', 'A vibrant yellow saree with a detailed geometric and floral print, featuring a striking floral border, ideal for festive events and celebrations.', 'yellow-geometric-and-floral-saree', 3050, 'Printed (28).jpg', '2025-09-21', 0),
(354, 5, 'Red Floral Motif Saree', 'A rich red saree with a delicate all-over floral motif and a heavy, ornate border, perfect for weddings and formal gatherings.', 'red-floral-motif-saree', 3050, 'Printed (29).jpg', '2025-09-21', 0);
INSERT INTO `products` (`id`, `category_id`, `name`, `description`, `slug`, `price`, `photo`, `date_view`, `counter`) VALUES
(355, 5, 'Red Geometric Printed Saree', 'A rich red saree with a geometric bandhani print and a heavy, ornate circle pattern on the pallu, perfect for weddings and formal gatherings.', 'red-geometric-printed-saree', 3050, 'Printed (30).jpg', '2025-09-21', 0),
(356, 5, 'Red Floral Bandhani Saree', 'A red saree with a traditional floral bandhani print, featuring a detailed border and a unique floral design on the pallu, suitable for traditional events.', 'red-floral-bandhani-saree', 3050, 'Printed (31).jpg', '2025-09-21', 0),
(357, 5, 'Purple Geometric and Floral Saree', 'A vibrant purple saree with a detailed geometric and floral print, featuring a striking floral border, ideal for festive events and celebrations.', 'purple-geometric-and-floral-saree', 3050, 'Printed (32).jpg', '2025-10-08', 1),
(358, 5, 'Magenta Floral Bandhani Saree', 'A magenta saree with a traditional floral bandhani print, featuring a detailed border and a unique floral design on the pallu, suitable for traditional events.', 'magenta-floral-bandhani-saree', 3050, 'Printed (33).jpg', '2025-09-21', 0),
(359, 5, 'Orange Elephant and Circle Print Saree', 'A stunning orange saree with a detailed elephant and geometric pattern on the pallu and an intricate circle design on the body, perfect for special occasions.', 'orange-elephant-and-circle-print-saree', 3050, 'Printed (34).jpg', '2025-10-08', 1),
(360, 5, 'Yellow Elephant and Geometric Print Saree', 'A stunning yellow saree with a detailed elephant and geometric pattern on the pallu and an intricate circle design on the body, perfect for special occasions.', 'yellow-elephant-and-geometric-print-saree', 3050, 'Printed (35).jpg', '2025-10-08', 1),
(361, 5, 'Red Bird and Geometric Print Saree', 'A vibrant red saree featuring a unique checked and bird pattern on the pallu and a delicate geometric print on the body, perfect for special occasions.', 'red-bird-and-geometric-print-saree', 3050, 'Printed (36).jpg', '2025-10-08', 1),
(362, 5, 'Purple Geometric and Striped Saree', 'A rich purple saree featuring a bold geometric and striped design on the pallu and an intricate check pattern on the body, perfect for a traditional and stylish look.', 'purple-geometric-and-striped-saree', 3050, 'Printed (37).jpg', '2025-10-08', 1),
(363, 5, 'Pink Geometric Bandhani Saree', 'A stunning pink saree with a geometric bandhani print and a heavy, ornate circle and mirror work border, perfect for weddings and formal gatherings.', 'pink-geometric-bandhani-saree', 3050, 'Printed (38).jpg', '2025-10-08', 1),
(364, 5, 'Purple Geometric Bandhani Saree', 'A stunning purple saree with a geometric bandhani print and a heavy, ornate circle and mirror work border, perfect for weddings and formal gatherings.', 'purple-geometric-bandhani-saree', 3050, 'Printed (39).jpg', '2025-10-08', 1),
(365, 5, 'Orange Geometric Bandhani Saree', 'A stunning orange saree with a geometric bandhani print and a heavy, ornate circle and mirror work border, perfect for weddings and formal gatherings.', 'orange-geometric-bandhani-saree', 3050, 'Printed (40).jpg', '2025-10-08', 1),
(366, 5, 'Purple Elephant and Geometric Print Saree', 'A stunning purple saree with a detailed elephant and geometric pattern on the pallu and an intricate circle design on the body, perfect for special occasions.', 'purple-elephant-and-geometric-print-saree', 3050, 'Printed (41).jpg', '2025-10-08', 1),
(367, 5, 'Red Geometric Bandhani Saree', 'A stunning red saree with a geometric bandhani print and a heavy, ornate circle and mirror work border, perfect for weddings and formal gatherings.', 'red-geometric-bandhani-saree', 3050, 'Printed (42).jpg', '2025-10-08', 1),
(368, 5, 'Red Wedding Motif Saree', 'A traditional red saree with intricate wedding motifs and a detailed geometric print, featuring a heavy golden border, ideal for bridal wear.', 'red-wedding-motif-saree', 3050, 'Printed (43).jpg', '2025-10-08', 1),
(369, 5, 'Yellow Bird and Geometric Print Saree', 'A vibrant yellow saree featuring a unique checked and bird pattern on the pallu and a delicate geometric print on the body, perfect for special occasions.', 'yellow-bird-and-geometric-print-saree', 3050, 'Printed (44).jpg', '2025-10-08', 1),
(370, 5, 'Red Elephant and Circle Print Saree', 'A stunning red saree with a detailed elephant and geometric pattern on the pallu and an intricate circle design on the body, perfect for special occasions.', 'red-elephant-and-circle-print-saree', 3050, 'Printed (45).jpg', '2025-10-08', 1),
(371, 5, 'Lavender Wedding Motif Saree', 'A traditional lavender saree with intricate wedding motifs and a detailed geometric print, featuring a heavy golden border, ideal for bridal wear.', 'lavender-wedding-motif-saree', 3050, 'Printed (46).jpg', '2025-10-08', 1),
(372, 5, 'Green Geometric Bandhani Saree', 'A stunning green saree with a geometric bandhani print and a heavy, ornate circle and mirror work border, perfect for weddings and formal gatherings.', 'green-geometric-bandhani-saree', 3050, 'Printed (47).jpg', '2025-10-08', 1),
(373, 5, 'Maroon Bird and Geometric Print Saree', 'A vibrant maroon saree featuring a unique checked and bird pattern on the pallu and a delicate geometric print on the body, perfect for special occasions.', 'maroon-bird-and-geometric-print-saree', 3050, 'Printed (48).jpg', '2025-09-21', 0),
(374, 5, 'Purple Bird and Geometric Print Saree', 'A vibrant purple saree featuring a unique checked and bird pattern on the pallu and a delicate geometric print on the body, perfect for special occasions.', 'purple-bird-and-geometric-print-saree', 3050, 'Printed (49).jpg', '2025-10-08', 1),
(375, 5, 'Green Geometric Bandhani Saree', 'A stunning green saree with a geometric bandhani print and a heavy, ornate circle and mirror work border, perfect for weddings and formal gatherings.', 'green-geometric-bandhani-saree', 3050, 'Printed (50).jpg', '2025-09-21', 0),
(376, 5, 'Maroon Bird and Geometric Print Saree', 'A vibrant maroon saree featuring a unique checked and bird pattern on the pallu and a delicate geometric print on the body, perfect for special occasions.', 'maroon-bird-and-geometric-print-saree', 3050, 'Printed (51).jpg', '2025-09-21', 0),
(377, 5, 'Purple Elephant and Circle Print Saree', 'A stunning purple saree with a detailed elephant and geometric pattern on the pallu and an intricate circle design on the body, perfect for special occasions.', 'purple-elephant-and-circle-print-saree', 3050, 'Printed (52).jpg', '2025-10-08', 1),
(378, 5, 'Dark Purple Geometric Bandhani Saree', 'A stunning dark purple saree with a geometric bandhani print and a heavy, ornate circle and mirror work border, perfect for weddings and formal gatherings.', 'dark-purple-geometric-bandhani-saree', 3050, 'Printed (53).jpg', '2025-10-08', 1),
(379, 5, 'Purple Wedding Motif Saree', 'A traditional purple saree with intricate wedding motifs and a detailed geometric print, featuring a heavy golden border, ideal for bridal wear.', 'purple-wedding-motif-saree', 3050, 'Printed (54).jpg', '2025-10-08', 1),
(380, 5, 'Yellow Geometric Bandhani Saree', 'A stunning yellow saree with a geometric bandhani print and a heavy, ornate circle and mirror work border, perfect for weddings and formal gatherings.', 'yellow-geometric-bandhani-saree', 3050, 'Printed (55).jpg', '2025-10-08', 1),
(381, 5, 'Lavender Wedding Motif Saree', 'A traditional lavender saree with intricate wedding motifs and a detailed geometric print, featuring a heavy golden border, ideal for bridal wear.', 'lavender-wedding-motif-saree', 3050, 'Printed (56).jpg', '2025-09-21', 0),
(382, 6, 'Pyor Gotta Patti Saree', 'A stunning saree featuring intricate Gota Patti work on a silk fabric. The saree has a beautiful border with floral motifs and a delicate single flower on the pallu.', 'pyor-gotta-patti-saree', 2950, 'Pyor Gotta Patti (1).jpg', '2025-11-01', 1),
(383, 6, 'Pyor Gotta Patti Saree', 'A stunning magenta saree featuring intricate Gota Patti work on a silk fabric. The saree has a beautiful border with a zigzag pattern and scattered star motifs on the body.', 'pyor-gotta-patti-saree', 2950, 'Pyor Gotta Patti (2).jpg', '2025-09-21', 0),
(384, 6, 'Pyor Gotta Patti Saree', 'A stunning rust-colored saree with fine silver Gota Patti work. The saree has a unique geometric and floral border and delicate scattered motifs on the body.', 'pyor-gotta-patti-saree-rust', 3200, 'Pyor Gotta Patti (3).jpg', '2025-10-08', 1),
(385, 6, 'Pyor Gotta Patti Saree', 'A stunning dusty rose saree featuring intricate Gota Patti work on a pure silk fabric. The saree has a beautiful border with floral and leaf motifs and delicate leaf motifs scattered on the body.', 'pyor-gotta-patti-saree-dusty-rose', 3150, 'Pyor Gotta Patti (4).jpg', '2025-09-21', 0),
(386, 6, 'Pyor Gotta Patti Saree', 'A gorgeous pink saree with golden Gota Patti work. The border features a beautiful floral pattern, complemented by small floral motifs scattered across the fabric.', 'pyor-gotta-patti-saree-pink', 2950, 'Pyor Gotta Patti (5).jpg', '2025-09-21', 0),
(387, 6, 'Pyor Gotta Patti Saree', 'An elegant dark green saree with Gota Patti work. The border showcases a delicate floral design, and the body is adorned with small scattered star motifs.', 'pyor-gotta-patti-saree-dark-green', 2950, 'Pyor Gotta Patti (6).jpg', '2025-10-08', 1),
(388, 6, 'Pyor Gotta Patti Saree', 'A lovely olive green saree with intricate Gota Patti work. The saree has a classic floral and zigzag border, with tiny star motifs scattered on the body.', 'pyor-gotta-patti-saree-olive-green', 2950, 'Pyor Gotta Patti (7).jpg', '2025-09-21', 0),
(389, 6, 'Pyor Gotta Patti Saree', 'A beautiful purple saree with fine Gota Patti work. The saree features a border with zigzag and floral patterns, with small star motifs scattered throughout the fabric.', 'pyor-gotta-patti-saree-purple', 2950, 'Pyor Gotta Patti (8).jpg', '2025-10-08', 1),
(390, 6, 'Pyor Gotta Patti Saree', 'A stunning red saree with classic Gota Patti work. The saree features a zigzag and floral patterned border and is decorated with small, scattered star motifs.', 'pyor-gotta-patti-saree-red', 3150, 'Pyor Gotta Patti (9).jpg', '2025-10-08', 1),
(391, 6, 'Pyor Gotta Patti Saree', 'A radiant fuchsia saree featuring delicate Gota Patti work on pure silk. The border has a mix of zigzag and floral patterns, with small flower motifs scattered across the body.', 'pyor-gotta-patti-saree-fuchsia', 3150, 'Pyor Gotta Patti (10).jpg', '2025-10-08', 1),
(392, 6, 'Pyor Gotta Patti Saree', 'A striking magenta saree adorned with intricate silver Gota Patti work. The border showcases a charming combination of floral and zigzag designs, with small floral motifs scattered on the body.', 'pyor-gotta-patti-saree-magenta', 2950, 'Pyor Gotta Patti (11).jpg', '2025-09-21', 0),
(393, 6, 'Pyor Gotta Patti Saree', 'An elegant purple saree with fine golden Gota Patti work. The border features a zigzag and floral pattern, with delicate leaf motifs scattered throughout the fabric.', 'pyor-gotta-patti-saree-purple-2', 2950, 'Pyor Gotta Patti (12).jpg', '2025-09-21', 0),
(394, 6, 'Pyor Gotta Patti Saree', 'A gorgeous blue-green saree with heavy Gota Patti work. The border is designed with large spiral and zigzag patterns, with small floral motifs scattered on the brocade fabric.', 'pyor-gotta-patti-saree-blue-green-brocade', 3800, 'Pyor Gotta Patti (13).jpg', '2025-10-08', 1),
(395, 6, 'Pyor Gotta Patti Saree', 'A sophisticated beige saree with Gota Patti work on a brocade pattern. The saree has a stunning border with floral and zigzag motifs, with scattered floral designs on the body.', 'pyor-gotta-patti-saree-beige-brocade', 3200, 'Pyor Gotta Patti (14).jpg', '2025-10-08', 1),
(396, 6, 'Pyor Gotta Patti Saree', 'A beautiful pista green saree with intricate Gota Patti work on a brocade pattern. The border features a zigzag pattern with floral motifs, with scattered star motifs on the body.', 'pyor-gotta-patti-saree-pista-green-brocade', 2950, 'Pyor Gotta Patti (15).jpg', '2025-10-08', 1),
(397, 6, 'Pyor Gotta Patti Saree', 'A stunning pista green saree with Gota Patti work on a brocade pattern. The saree has a bold Gota Patti border with unique floral motifs and scattered leaf designs on the body.', 'pyor-gotta-patti-saree-pista-green', 3200, 'Pyor Gotta Patti (16).jpg', '2025-10-08', 1),
(398, 6, 'Pyor Gotta Patti Saree', 'An elegant pink saree with intricate Gota Patti work on a brocade pattern. The saree features a detailed floral and zigzag border with beautiful square floral patterns and scattered motifs on the body.', 'pyor-gotta-patti-saree-pink-brocade', 3500, 'Pyor Gotta Patti (17).jpg', '2025-10-08', 1),
(399, 6, 'Pyor Gotta Patti Saree', 'A sophisticated dusty grey saree featuring intricate Gota Patti work on a brocade pattern. The border has a scalloped floral design, with small floral motifs scattered throughout the fabric.', 'pyor-gotta-patti-saree-dusty-grey', 3200, 'Pyor Gotta Patti (18).jpg', '2025-10-08', 1),
(400, 6, 'Pyor Gotta Patti Saree', 'A beautiful pista green saree featuring intricate Gota Patti work on a brocade pattern. The border is a unique zigzag and floral design, with delicate motifs scattered on the body.', 'pyor-gotta-patti-saree-pista-green-2', 3200, 'Pyor Gotta Patti (19).jpg', '2025-10-08', 1),
(401, 6, 'Pyor Gotta Patti Saree', 'A graceful dusty pink saree with fine Gota Patti work on a brocade pattern. The border showcases an elegant scroll and floral design with scattered small motifs on the body.', 'pyor-gotta-patti-saree-dusty-pink', 3200, 'Pyor Gotta Patti (20).jpg', '2025-10-08', 1),
(402, 6, 'Pyor Gotta Patti Saree', 'A luxurious wine-colored saree with rich Gota Patti work. The border features a bold scalloped design with leaf motifs, and the body has a delicate star motif.', 'pyor-gotta-patti-saree-wine', 3950, 'Pyor Gotta Patti (21).jpg', '2025-10-08', 1),
(403, 6, 'Pyor Gotta Patti Saree', 'A beautiful deep plum saree featuring intricate Gota Patti work and pearl detailing. The border has a detailed leaf pattern with a bold floral motif on the body.', 'pyor-gotta-patti-saree-deep-plum', 3950, 'Pyor Gotta Patti (22).jpg', '2025-10-08', 1),
(404, 6, 'Pyor Gotta Patti Saree', 'A rich maroon saree with a combination of golden and silver Gota Patti work. The border features a detailed floral pattern, with bold floral motifs on the body.', 'pyor-gotta-patti-saree-maroon-gota-patti', 4000, 'Pyor Gotta Patti (23).jpg', '2025-09-21', 0),
(405, 6, 'Pyor Gotta Patti Saree', 'A striking deep maroon saree with dense golden Gota Patti work. The saree features a heavy border with floral motifs and a single large floral motif on the pallu.', 'pyor-gotta-patti-saree-deep-maroon', 3950, 'Pyor Gotta Patti (24).jpg', '2025-10-08', 1),
(406, 6, 'Pyor Gotta Patti Saree', 'An elegant plum saree with detailed Gota Patti work. The border is a bold and dense floral pattern, with a large circular floral motif on the pallu.', 'pyor-gotta-patti-saree-plum-floral', 3600, 'Pyor Gotta Patti (25).jpg', '2025-10-08', 1),
(407, 6, 'Pyor Gotta Patti Saree', 'A stunning brown saree featuring intricate Gota Patti work. The border combines geometric and floral patterns, with a detailed floral design on the pallu and scattered motifs on the body.', 'pyor-gotta-patti-saree-brown-floral', 3800, 'Pyor Gotta Patti (26).jpg', '2025-09-21', 0),
(408, 6, 'Pyor Gotta Patti Saree', 'A beautiful mauve saree featuring intricate Gota Patti work. The border showcases a detailed peacock and floral design, with a single leaf motif on the body.', 'pyor-gotta-patti-saree-mauve', 3800, 'Pyor Gotta Patti (27).jpg', '2025-10-08', 1),
(409, 6, 'Pyor Gotta Patti Saree', 'A luxurious deep green saree with Gota Patti and stone work. The border has a detailed leaf pattern with a bold floral motif and a center stone on the body.', 'pyor-gotta-patti-saree-deep-green', 3400, 'Pyor Gotta Patti (28).jpg', '2025-10-08', 1),
(410, 6, 'Pyor Gotta Patti Saree', 'A deep purple saree with intricate Gota Patti work and sequin details. The border features a geometric pattern, with a delicate floral motif on the body.', 'pyor-gotta-patti-saree-deep-purple', 3600, 'Pyor Gotta Patti (29).jpg', '2025-10-08', 1),
(411, 6, 'Pyor Gotta Patti Saree', 'An elegant magenta saree with a classic Gota Patti border. The saree is adorned with intricate floral and leaf motifs and has a large circular floral motif on the body.', 'pyor-gotta-patti-saree-magenta-2', 3600, 'Pyor Gotta Patti (30).jpg', '2025-10-08', 1),
(412, 6, 'Pyor Gotta Patti Saree', 'A festive red saree with intricate Gota Patti work. The border features a scalloped leaf pattern, and the body has a large circular motif with delicate flowers and connecting lines.', 'pyor-gotta-patti-saree-red-2', 3400, 'Pyor Gotta Patti (31).jpg', '2025-10-08', 1),
(413, 6, 'Pyor Gotta Patti Saree', 'A rich plum saree with heavy Gota Patti work. The scalloped border features bold leaf motifs with a stunning star motif on the body of the saree.', 'pyor-gotta-patti-saree-plum', 3800, 'Pyor Gotta Patti (32).jpg', '2025-09-21', 0),
(414, 6, 'Pyor Gotta Patti Saree', 'A vibrant yellow saree with heavy silver Gota Patti work. The border features a dense floral and geometric pattern, with scattered leaf and floral motifs on the body.', 'pyor-gotta-patti-saree-yellow', 3600, 'Pyor Gotta Patti (33).jpg', '2025-10-08', 1),
(415, 6, 'Pyor Gotta Patti Saree', 'A stunning white saree with elaborate Gota Patti and mirror work. The border features a dense floral design with intricate patterns on the pallu and a contrasting red piping.', 'pyor-gotta-patti-saree-white', 3700, 'Pyor Gotta Patti (34).jpg', '2025-09-21', 0),
(416, 6, 'Pyor Gotta Patti Saree', 'A beautiful dark green saree with delicate Gota Patti work. The saree has a stunning border with floral motifs and is decorated with small scattered flowers on the body.', 'pyor-gotta-patti-saree-dark-green-2', 3150, 'Pyor Gotta Patti (35).jpg', '2025-10-08', 1),
(417, 6, 'Pyor Gotta Patti Saree', 'A charming pink saree with golden Gota Patti work. The saree features a border with zigzag and floral motifs and is decorated with small scattered floral motifs on the body.', 'pyor-gotta-patti-saree-pink-2', 3150, 'Pyor Gotta Patti (36).jpg', '2025-09-23', 1),
(418, 6, 'Pyor Gotta Patti Saree', 'A lovely rose pink saree with Gota Patti work. The border features a zigzag and floral design with small floral motifs scattered throughout the fabric.', 'pyor-gotta-patti-saree-rose-pink', 3150, 'Pyor Gotta Patti (37).jpg', '2025-10-08', 1),
(419, 6, 'Pyor Gotta Patti Saree', 'A graceful mauve saree with golden Gota Patti work. The border showcases a zigzag and floral design, with small floral motifs scattered on the body.', 'pyor-gotta-patti-saree-mauve-2', 3150, 'Pyor Gotta Patti (38).jpg', '2025-10-08', 1),
(420, 6, 'Pyor Gotta Patti Saree', 'A gorgeous magenta saree with golden Gota Patti work. The border features a detailed floral and zigzag pattern, with small scattered motifs on the body.', 'pyor-gotta-patti-saree-magenta-3', 3150, 'Pyor Gotta Patti (39).jpg', '2025-10-08', 1),
(421, 6, 'Pyor Gotta Patti Saree', 'A beautiful pista green saree with Gota Patti work. The border features a zigzag pattern with floral motifs and is decorated with small scattered flowers on the body.', 'pyor-gotta-patti-saree-pista-green-3', 3150, 'Pyor Gotta Patti (40).jpg', '2025-10-08', 1),
(422, 6, 'Pyor Gotta Patti Saree', 'A classic red saree with a heavy Gota Patti border. The border features a dense floral pattern with leaf motifs and a detailed floral design on the body of the saree.', 'pyor-gotta-patti-saree-red-3', 3400, 'Pyor Gotta Patti (41).jpg', '2025-09-21', 0),
(423, 6, 'Pyor Gotta Patti Saree', 'A stunning olive-green saree with intricate Gota Patti work. The saree features a border with elegant circular and floral motifs and delicate leaf designs on the body.', 'pyor-gotta-patti-saree-olive-green-2', 3900, 'Pyor Gotta Patti (42).jpg', '2025-10-08', 1),
(424, 6, 'Pyor Gotta Patti Saree', 'A beautiful pista green saree with silver Gota Patti work. The border showcases a detailed pattern of circular floral motifs, with scattered leaf designs on the body.', 'pyor-gotta-patti-saree-pista-green-4', 3900, 'Pyor Gotta Patti (43).jpg', '2025-10-08', 1),
(425, 6, 'Pyor Gotta Patti Saree', 'An elegant lavender saree with heavy golden Gota Patti and mirror work. The saree features a scalloped border with intricate floral and mirror work, and a delicate floral brocade pattern.', 'pyor-gotta-patti-saree-lavender', 3700, 'Pyor Gotta Patti (44).jpg', '2025-10-08', 1),
(426, 6, 'Pyor Gotta Patti Saree', 'A beautiful mauve saree with intricate Gota Patti work. The border is adorned with a zigzag and leaf pattern, with a large leaf motif on the pallu.', 'pyor-gotta-patti-saree-mauve-3', 3200, 'Pyor Gotta Patti (45).jpg', '2025-10-08', 1),
(427, 6, 'Pyor Gotta Patti Saree', 'A sophisticated dusty pink saree with detailed Gota Patti and mirror work. The border features a scalloped design with floral and leaf motifs, with a subtle brocade pattern on the fabric.', 'pyor-gotta-patti-saree-dusty-pink-2', 3200, 'Pyor Gotta Patti (46).jpg', '2025-10-08', 1),
(428, 6, 'Pyor Gotta Patti Saree', 'A stunning magenta saree with Gota Patti and mirror work. The border has a graceful zigzag and leaf pattern, with a bold floral motif on the pallu.', 'pyor-gotta-patti-saree-magenta-4', 3200, 'Pyor Gotta Patti (47).jpg', '2025-10-08', 1),
(429, 6, 'Pyor Gotta Patti Saree', 'A classic royal blue saree with intricate Gota Patti work. The border is a bold combination of geometric and floral motifs, with stunning floral designs scattered on the body.', 'pyor-gotta-patti-saree-royal-blue', 3500, 'Pyor Gotta Patti (48).jpg', '2025-10-08', 1),
(430, 6, 'Pyor Gotta Patti Saree', 'A stunning beige saree with intricate Gota Patti and mirror work. The saree features a broad border with floral and mirror motifs, with a small check pattern on the fabric.', 'pyor-gotta-patti-saree-beige', 3200, 'Pyor Gotta Patti (49).jpg', '2025-11-01', 1),
(431, 6, 'Pyor Gotta Patti Saree', 'An elegant off-white saree with delicate golden Gota Patti work. The border has a beautiful scalloped floral design with sequins, and the body has a subtle check pattern with scattered motifs.', 'pyor-gotta-patti-saree-off-white', 3200, 'Pyor Gotta Patti (50).jpg', '2025-10-08', 1),
(432, 6, 'Pyor Gotta Patti Saree', 'An elegant off-white saree with delicate golden Gota Patti work. The saree features a broad scalloped border with floral motifs and subtle scattered patterns on the body.', 'pyor-gotta-patti-saree-off-white-2', 3100, 'Pyor Gotta Patti (51).jpg', '2025-10-08', 1),
(433, 6, 'Pyor Gotta Patti Saree', 'A beautiful dusty rose saree with intricate Gota Patti work on a brocade pattern. The saree has a heavy scalloped border with floral motifs and a delicate brocade pattern on the fabric.', 'pyor-gotta-patti-saree-dusty-rose-brocade', 3100, 'Pyor Gotta Patti (52).jpg', '2025-10-08', 1),
(434, 6, 'Pyor Gotta Patti Saree', 'A beautiful dusty pink saree with intricate Gota Patti work. The saree has a stunning border with a combination of leaf and floral patterns, with a brocade pattern on the fabric.', 'pyor-gotta-patti-saree-dusty-pink-3', 3100, 'Pyor Gotta Patti (53).jpg', '2025-10-08', 1),
(435, 6, 'Pyor Gotta Patti Saree', 'An elegant peach-colored saree with fine Gota Patti work. The border features a unique floral and zigzag pattern with scattered small floral motifs on the body.', 'pyor-gotta-patti-saree-peach', 3100, 'Pyor Gotta Patti (54).jpg', '2025-10-08', 1),
(436, 6, 'Pyor Gotta Patti Saree', 'An elegant pink saree with intricate Gota Patti work on a brocade pattern. The saree has a stunning scalloped border with floral motifs and scattered patterns on the body.', 'pyor-gotta-patti-saree-pink-brocade-2', 3100, 'Pyor Gotta Patti (55).jpg', '2025-09-21', 0),
(437, 6, 'Pyor Gotta Patti Saree', 'A beautiful dusty grey saree with Gota Patti and mirror work. The border features a combination of floral and mirror patterns, with large floral motifs on the body.', 'pyor-gotta-patti-saree-dusty-grey-2', 3700, 'pyor-gotta-patti-saree-dusty-grey-2_1758553141.jpg', '2025-10-08', 1),
(438, 6, 'Pyor Gotta Patti Saree', '<p>A gorgeous plum saree with intricate Gota Patti and mirror work. The saree features a scalloped border with intricate floral and mirror work, with a brocade pattern on the fabric.</p>\r\n', 'pyor-gotta-patti-saree', 6500, 'pyor-gotta-patti-saree-plum-2_1758553407.jpg', '2025-09-21', 0),
(439, 6, 'Pyor Gotta Patti Saree', 'A stunning red saree with a heavy golden Gota Patti border. The saree has a broad scalloped border with leaf motifs and scattered star patterns on the body.', 'pyor-gotta-patti-saree-red-4', 3200, 'pyor-gotta-patti-saree-red-4_1758553516.jpg', '2025-11-01', 1);

-- --------------------------------------------------------

--
-- Table structure for table `sales`
--

CREATE TABLE `sales` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `pay_id` varchar(50) NOT NULL,
  `sales_date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `sales`
--

INSERT INTO `sales` (`id`, `user_id`, `pay_id`, `sales_date`) VALUES
(9, 9, 'PAY-1RT494832H294925RLLZ7TZA', '2018-05-10'),
(10, 9, 'PAY-21700797GV667562HLLZ7ZVY', '2018-05-10');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `email` varchar(200) NOT NULL,
  `password` varchar(60) NOT NULL,
  `type` int(1) NOT NULL,
  `firstname` varchar(50) NOT NULL,
  `lastname` varchar(50) NOT NULL,
  `address` text NOT NULL,
  `contact_info` varchar(100) NOT NULL,
  `photo` varchar(200) NOT NULL,
  `status` int(1) NOT NULL,
  `activate_code` varchar(15) NOT NULL,
  `reset_code` varchar(15) NOT NULL,
  `created_on` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `email`, `password`, `type`, `firstname`, `lastname`, `address`, `contact_info`, `photo`, `status`, `activate_code`, `reset_code`, `created_on`) VALUES
(1, 'admin@admin.com', '$2y$10$8lFMF7Xpdg2JmxamfaMimOPdEnGHdHqgtHv23rJcIjiPryoAc3K8y', 1, 'pavitra', 'Sarees', '', '', 'pavitra_logo_updated.png', 1, '', '', '2018-05-01'),
(9, 'harry@den.com', '$2y$10$Oongyx.Rv0Y/vbHGOxywl.qf18bXFiZOcEaI4ZpRRLzFNGKAhObSC', 0, 'Harry', 'Den', 'Silay City, Negros Occidental', '09092735719', 'profile.jpg', 1, 'k8FBpynQfqsv', 'wzPGkX5IODlTYHg', '2018-05-09'),
(12, 'christine@gmail.com', '$2y$10$8lFMF7Xpdg2JmxamfaMimOPdEnGHdHqgtHv23rJcIjiPryoAc3K8y', 1, 'Christine', 'becker', 'demo', '7542214500', 'profile.jpg', 1, '', '', '2018-07-09');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `category`
--
ALTER TABLE `category`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `details`
--
ALTER TABLE `details`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sales`
--
ALTER TABLE `sales`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `cart`
--
ALTER TABLE `cart`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `category`
--
ALTER TABLE `category`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `details`
--
ALTER TABLE `details`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=440;

--
-- AUTO_INCREMENT for table `sales`
--
ALTER TABLE `sales`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
