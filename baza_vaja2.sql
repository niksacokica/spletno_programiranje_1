SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Zbirka podatkov: `vaja2`
--
CREATE DATABASE IF NOT EXISTS `vaja2` DEFAULT CHARACTER SET utf8 COLLATE utf8_slovenian_ci;
USE `vaja2`;

-- ---------------------------------------------------------

--
-- Struktura tabele `ads`
--

DROP TABLE IF EXISTS `ads`;
CREATE TABLE IF NOT EXISTS `ads` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` text COLLATE utf8_slovenian_ci NOT NULL,
  `description` text COLLATE utf8_slovenian_ci NOT NULL,
  `user_id` int(11) NOT NULL,
  `images` text COLLATE utf8_slovenian_ci NOT NULL,
  `show_image` text COLLATE utf8_slovenian_ci NOT NULL,
  `postdate` datetime NOT NULL,
  `enddate` datetime NOT NULL,
  `categories_ids` text COLLATE utf8_slovenian_ci NOT NULL,
  `views` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_slovenian_ci;

-- ---------------------------------------------------------

--
-- Struktura tabele `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `email` text COLLATE utf8_slovenian_ci NOT NULL,
  `username` text COLLATE utf8_slovenian_ci NOT NULL,
  `firstname` text COLLATE utf8_slovenian_ci NOT NULL,
  `lastname` text COLLATE utf8_slovenian_ci NOT NULL,
  `password` text COLLATE utf8_slovenian_ci NOT NULL,
  `address` text COLLATE utf8_slovenian_ci,
  `postalcode` text COLLATE utf8_slovenian_ci,
  `phone` int(11),
  `sex` text COLLATE utf8_slovenian_ci,
  `age` int(3),
  `isAdmin` tinyint,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_slovenian_ci;

-- ---------------------------------------------------------

--
-- Struktura tabele `categories`
--

DROP TABLE IF EXISTS `categories`;
CREATE TABLE IF NOT EXISTS `categories` (
	`id` int(11) NOT NULL AUTO_INCREMENT,
	`name` text COLLATE utf8_slovenian_ci NOT NULL,
	`deep` int(1),
	`sub_categories` text COLLATE utf8_slovenian_ci,
	PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_slovenian_ci;

-- ---------------------------------------------------------

--
-- Struktura tabele `comments`
--

DROP TABLE IF EXISTS `comments`;
CREATE TABLE IF NOT EXISTS `comments` (
	`id` int(11) NOT NULL AUTO_INCREMENT,
	`ad_id` int(11) NOT NULL,
	`email` text COLLATE utf8_slovenian_ci NOT NULL,
	`username` text COLLATE utf8_slovenian_ci NOT NULL,
	`content` text COLLATE utf8_slovenian_ci NOT NULL,
	`postdate` datetime NOT NULL,
	`ip` text COLLATE utf8_slovenian_ci NOT NULL,
	PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_slovenian_ci;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;