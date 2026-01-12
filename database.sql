-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le : dim. 11 jan. 2026 à 23:44
-- Version du serveur : 9.1.0
-- Version de PHP : 8.3.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `ecommerce_db`
--

-- Supprimer la base de données si elle existe
DROP DATABASE IF EXISTS ecommerce_db;

-- Créer la base de données
CREATE DATABASE ecommerce_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- Utiliser la base de données
USE ecommerce_db;

-- --------------------------------------------------------

--
-- Structure de la table `categories`
--

DROP TABLE IF EXISTS `categories`;
CREATE TABLE IF NOT EXISTS `categories` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `categories`
--

INSERT INTO `categories` (`id`, `name`, `description`, `created_at`) VALUES
(1, 'Electronics', 'Electronic devices and gadgets', '2026-01-10 04:54:01'),
(2, 'Clothing', 'Fashion and apparel', '2026-01-10 04:54:01'),
(3, 'Accessories', 'Various accessories and extras', '2026-01-10 04:54:01');

-- --------------------------------------------------------

--
-- Structure de la table `products`
--

DROP TABLE IF EXISTS `products`;
CREATE TABLE IF NOT EXISTS `products` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `price` decimal(10,2) NOT NULL,
  `category_id` int NOT NULL,
  `stock` int DEFAULT '0',
  `image_url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `category_id` (`category_id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `products`
--

INSERT INTO `products` (`id`, `name`, `description`, `price`, `category_id`, `stock`, `image_url`, `created_at`) VALUES
(1, 'Wireless Headphones', 'High quality sound with noise cancellation', 79.99, 1, 50, '', '2026-01-10 04:54:01'),
(2, 'Smart Watch', 'Track your activities and stay connected', 129.99, 1, 30, '', '2026-01-10 04:54:01'),
(3, 'Laptop Stand', 'Ergonomic aluminum laptop stand', 49.99, 1, 40, '', '2026-01-10 04:54:01'),
(4, 'USB-C Hub', 'Multi-port USB-C adapter', 34.99, 1, 60, '', '2026-01-10 04:54:01'),
(5, 'T-Shirt', '100% cotton comfortable t-shirt', 19.99, 2, 100, '', '2026-01-10 04:54:01'),
(6, 'Jeans', 'Classic blue denim jeans', 59.99, 2, 45, '', '2026-01-10 04:54:01'),
(7, 'Hoodie', 'Warm and cozy hoodie', 44.99, 2, 35, '', '2026-01-10 04:54:01'),
(8, 'Sneakers', 'Comfortable running shoes', 89.99, 2, 25, '', '2026-01-10 04:54:01'),
(9, 'Leather Wallet', 'Genuine leather bifold wallet', 39.99, 3, 70, '', '2026-01-10 04:54:01'),
(10, 'Sunglasses', 'UV protection sunglasses', 29.99, 3, 55, '', '2026-01-10 04:54:01'),
(11, 'Backpack', 'Durable travel backpack', 69.99, 3, 40, '', '2026-01-10 04:54:01'),
(12, 'Phone Case', 'Protective silicone phone case', 14.99, 3, 120, '', '2026-01-10 04:54:01');

--
-- Contraintes pour la table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `fk_products_category` 
  FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE;

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` int NOT NULL AUTO_INCREMENT,
  `username` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `email`, `created_at`) VALUES
(1, 'admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin@store.com', '2026-01-10 04:53:59');

COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
