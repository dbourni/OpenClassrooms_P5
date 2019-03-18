-- phpMyAdmin SQL Dump
-- version 4.8.3
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le :  mer. 13 mars 2019 à 08:57
-- Version du serveur :  5.7.23
-- Version de PHP :  7.2.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Structure de la table `comments`
--

DROP TABLE IF EXISTS `comments`;
CREATE TABLE IF NOT EXISTS `comments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `post_id` int(11) NOT NULL,
  `date` datetime NOT NULL,
  `comment` text COLLATE utf8_unicode_ci NOT NULL,
  `validated` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=39 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Déchargement des données de la table `comments`
--

INSERT INTO `comments` (`id`, `user_id`, `post_id`, `date`, `comment`, `validated`) VALUES
(22, 17, 5, '2019-03-12 18:02:00', 'Je me permets de commenter cet article.', 1),
(18, 17, 3, '2019-03-12 17:57:48', 'Cet article est parfait !', 1),
(19, 17, 3, '2019-03-12 17:58:33', 'Encore un très bon article. Continuez !', 1),
(20, 17, 1, '2019-03-12 18:00:05', 'Merci d\'accepter mon commentaire.', 1),
(21, 17, 5, '2019-03-12 18:01:45', 'Je me permets de commenter cet article.', 1);

-- --------------------------------------------------------

--
-- Structure de la table `posts`
--

DROP TABLE IF EXISTS `posts`;
CREATE TABLE IF NOT EXISTS `posts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `chapo` text COLLATE utf8_unicode_ci,
  `author_id` int(11) NOT NULL,
  `date` datetime NOT NULL,
  `content` text COLLATE utf8_unicode_ci NOT NULL,
  `image` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=27 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Déchargement des données de la table `posts`
--

INSERT INTO `posts` (`id`, `title`, `chapo`, `author_id`, `date`, `content`, `image`) VALUES
(1, 'Titre du post 1', 'Contenu du post 1. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec fermentum dignissim tristique. Maecenas vehicula volutpat cursus. Duis tincidunt, lectus eget tempus consectetur, arcu est imperdiet nibh, nec vulputate tellus tortor vitae odio. Donec hendrerit quis turpis a porttitor. Nunc sagittis condimentum lacus vel dignissim. Donec ut sodales tellus, imperdiet molestie mauris. Sed vitae lacinia turpis. Cras eget odio non turpis iaculis pharetra. Morbi mi tellus, tristique id ligula at, tristique tincidunt urna. Phasellus varius elit at nibh tempus molestie.', 17, '2019-03-12 17:52:14', 'Contenu du post 1. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec fermentum dignissim tristique. Maecenas vehicula volutpat cursus. Duis tincidunt, lectus eget tempus consectetur, arcu est imperdiet nibh, nec vulputate tellus tortor vitae odio. Donec hendrerit quis turpis a porttitor. Nunc sagittis condimentum lacus vel dignissim. Donec ut sodales tellus, imperdiet molestie mauris. Sed vitae lacinia turpis. Cras eget odio non turpis iaculis pharetra. Morbi mi tellus, tristique id ligula at, tristique tincidunt urna. Phasellus varius elit at nibh tempus molestie. Etiam congue, eros id tempus sagittis, arcu dui pretium arcu, vel porta magna urna vitae ex. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Nulla et ante risus. Integer vitae nibh ac urna fermentum sodales ut ut ex. Ut ex sem, pulvinar eu nulla ac, iaculis posuere nisi.', 'public/uploads/image_example_1.jpg'),
(3, 'Titre du post 3', 'Contenu du post 3. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec fermentum dignissim tristique. Maecenas vehicula volutpat cursus. Duis tincidunt, lectus eget tempus consectetur, arcu est imperdiet nibh, nec vulputate tellus tortor vitae odio. Donec hendrerit quis turpis a porttitor. Nunc sagittis condimentum lacus vel dignissim. Donec ut sodales tellus, imperdiet molestie mauris. Sed vitae lacinia turpis. Cras eget odio non turpis iaculis pharetra. Morbi mi tellus, tristique id ligula at, tristique tincidunt urna. Phasellus varius elit at nibh tempus molestie.', 4, '2019-03-12 17:52:45', 'Contenu du post 3. <b>Lorem ipsum dolor</b> sit amet, consectetur adipiscing elit. Donec fermentum dignissim tristique. Maecenas vehicula volutpat cursus. Duis tincidunt, lectus eget tempus consectetur, arcu est imperdiet nibh, nec vulputate tellus tortor vitae odio. Donec hendrerit quis turpis a porttitor. Nunc sagittis condimentum lacus vel dignissim. Donec ut sodales tellus, imperdiet molestie mauris. Sed vitae lacinia turpis. Cras eget odio non turpis iaculis pharetra. Morbi mi tellus, tristique id ligula at, tristique tincidunt urna. Phasellus varius elit at nibh tempus molestie. Etiam congue, eros id tempus sagittis, arcu dui pretium arcu, vel porta magna urna vitae ex. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Nulla et ante risus. Integer vitae nibh ac urna fermentum sodales ut ut ex. Ut ex sem, pulvinar eu nulla ac, iaculis posuere nisi.', 'public/uploads/image_example_3.jpg'),
(5, 'Titre du post 4', 'Contenu du post 4. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec fermentum dignissim tristique. Maecenas vehicula volutpat cursus. Duis tincidunt, lectus eget tempus consectetur, arcu est imperdiet nibh, nec vulputate tellus tortor vitae odio. Donec hendrerit quis turpis a porttitor. Nunc sagittis condimentum lacus vel dignissim. Donec ut sodales tellus, imperdiet molestie mauris. Sed vitae lacinia turpis. Cras eget odio non turpis iaculis pharetra.', 4, '2019-03-12 18:39:44', 'Contenu du post 4. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec fermentum dignissim tristique. Maecenas vehicula volutpat cursus. Duis tincidunt, lectus eget tempus consectetur, arcu est imperdiet nibh, nec vulputate tellus tortor vitae odio. Donec hendrerit quis turpis a porttitor. Nunc sagittis condimentum lacus vel dignissim. Donec ut sodales tellus, imperdiet molestie mauris. Sed vitae lacinia turpis. Cras eget odio non turpis iaculis pharetra. Morbi mi tellus, tristique id ligula at, tristique tincidunt ur', 'public/uploads/image_example_4.jpg'),
(6, 'Titre du post 2', 'Contenu du post 2. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec fermentum dignissim tristique. Maecenas vehicula volutpat cursus. Duis tincidunt, lectus eget tempus consectetur, arcu est imperdiet nibh, nec vulputate tellus tortor vitae odio. Donec hendrerit quis turpis a porttitor. Nunc sagittis condimentum lacus vel dignissim. Donec ut sodales tellus, imperdiet molestie mauris. Sed vitae lacinia turpis. Cras eget odio non turpis iaculis pharetra.', 17, '2019-03-12 18:30:37', 'Contenu du post 2. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec fermentum dignissim tristique. Maecenas vehicula volutpat cursus. Duis tincidunt, lectus eget tempus consectetur, arcu est imperdiet nibh, nec vulputate tellus tortor vitae odio. Donec hendrerit quis turpis a porttitor. Nunc sagittis condimentum lacus vel dignissim. Donec ut sodales tellus, imperdiet molestie mauris. Sed vitae lacinia turpis. Cras eget odio non turpis iaculis pharetra. Morbi mi tellus, tristique id ligula at, tristique tincidunt urna. Phasellus varius elit at nibh tempus molestie. Etiam congue, eros id tempus sagittis, arcu dui pretium arcu, vel porta magna urna vitae ex. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Nulla et ante risus. Integer vitae nibh ac urna fermentum sodales ut ut ex. Ut ex sem, pulvinar eu nulla ac, iaculis posuere nisi.', 'public/uploads/image_example_2.jpg');

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `role` varchar(25) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `validated` tinyint(1) NOT NULL DEFAULT '0',
  `init_key` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=18 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Déchargement des données de la table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `role`, `password`, `validated`, `init_key`) VALUES
(4, 'Pierre', 'pierre@pierre.fr', 'editor', '$2y$10$QDL8Lw0CchAtvwn/VSCIzeBLl18AV7ptU3QUjUvKGONDWWBISmjPy', 1, NULL),
(7, 'Paul', 'paul@paul.fr', 'reader', '$2y$10$3dMoIgLvfgKxIWR0urpB0uyWXNVyYmS7MbPKWD9Ixwgwmh9Xkwx0i', 1, '115a82205f9688f662dfa347f212283b'),
(17, 'admin', 'dbourni@gmail.com', 'admin', '$2y$10$YC/0Joiq/uK4S1QIm/ef8eigAw3QBidCSlYulnzzK.H6RspS3sjgu', 1, NULL);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
