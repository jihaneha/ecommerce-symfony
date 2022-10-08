-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le : sam. 08 oct. 2022 à 15:23
-- Version du serveur : 5.7.36
-- Version de PHP : 7.4.26

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `gigisbrand`
--

-- --------------------------------------------------------

--
-- Structure de la table `category`
--

DROP TABLE IF EXISTS `category`;
CREATE TABLE IF NOT EXISTS `category` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_64C19C17E3C61F9` (`owner_id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `category`
--

INSERT INTO `category` (`id`, `name`, `slug`, `owner_id`) VALUES
(9, 'NOUVEAUTÉS', 'nouveautes', 13),
(10, 'ROBES', 'robes', 15),
(11, 'BLAZERS', 'blazers', 16),
(12, 'ACCESSOIRES', 'accessoires', NULL);

-- --------------------------------------------------------

--
-- Structure de la table `doctrine_migration_versions`
--

DROP TABLE IF EXISTS `doctrine_migration_versions`;
CREATE TABLE IF NOT EXISTS `doctrine_migration_versions` (
  `version` varchar(191) COLLATE utf8_unicode_ci NOT NULL,
  `executed_at` datetime DEFAULT NULL,
  `execution_time` int(11) DEFAULT NULL,
  PRIMARY KEY (`version`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Déchargement des données de la table `doctrine_migration_versions`
--

INSERT INTO `doctrine_migration_versions` (`version`, `executed_at`, `execution_time`) VALUES
('DoctrineMigrations\\Version20220802101124', '2022-08-02 10:13:32', 172),
('DoctrineMigrations\\Version20220802102155', '2022-08-02 10:22:15', 123),
('DoctrineMigrations\\Version20220802141617', '2022-08-02 14:16:40', 74),
('DoctrineMigrations\\Version20220803074951', '2022-08-03 07:50:34', 256),
('DoctrineMigrations\\Version20220803080448', '2022-08-03 08:05:59', 119),
('DoctrineMigrations\\Version20220804075033', '2022-08-04 07:50:58', 170),
('DoctrineMigrations\\Version20220816174707', '2022-08-16 17:53:16', 81),
('DoctrineMigrations\\Version20220823071448', '2022-08-23 07:15:14', 164),
('DoctrineMigrations\\Version20220904113817', '2022-09-04 11:40:20', 289),
('DoctrineMigrations\\Version20220912163405', '2022-09-12 16:41:08', 266),
('DoctrineMigrations\\Version20220912172904', '2022-09-12 17:29:57', 279),
('DoctrineMigrations\\Version20220913181800', '2022-09-13 18:18:31', 88),
('DoctrineMigrations\\Version20220913182946', '2022-09-13 18:29:59', 1036);

-- --------------------------------------------------------

--
-- Structure de la table `messenger_messages`
--

DROP TABLE IF EXISTS `messenger_messages`;
CREATE TABLE IF NOT EXISTS `messenger_messages` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `body` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `headers` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue_name` varchar(190) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` datetime NOT NULL,
  `available_at` datetime NOT NULL,
  `delivered_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_75EA56E0FB7336F0` (`queue_name`),
  KEY `IDX_75EA56E0E3BD61CE` (`available_at`),
  KEY `IDX_75EA56E016BA31DB` (`delivered_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `product`
--

DROP TABLE IF EXISTS `product`;
CREATE TABLE IF NOT EXISTS `product` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `price` int(11) NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `category_id` int(11) DEFAULT NULL,
  `mainpicture` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `short_description` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `mainpicture2` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_D34A04AD12469DE2` (`category_id`)
) ENGINE=InnoDB AUTO_INCREMENT=50 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `product`
--

INSERT INTO `product` (`id`, `name`, `price`, `slug`, `category_id`, `mainpicture`, `short_description`, `mainpicture2`) VALUES
(32, 'ROBE PINK', 3999, 'robe-pink', 10, 'pexels-anastasiya-gepp-2065194.jpg', 'Robe mi-longue confectionnée dans un tissu en mélange de laine. Col rond et manches courtes. Tissu drapé. Fermeture zip dissimulée dans la couture.', 'pexels-anastasiya-gepp-2065195.jpg'),
(33, 'ROBE FLEURIE', 4999, 'robe-fleurie', 9, 'pexels-chalo-garcia-5489730.jpg', 'Robe mi-longue à décolleté en V sans manches. Tissus variés en contraste. Doublure. Fermeture zip à l\'arrière dissimulée dans la couture.', 'pexels-chalo-garcia-5489750.jpg'),
(34, 'ROBE NOIR', 2999, 'robe-noir', 10, 'pexels-chalo-garcia-8050046.jpg', 'Robe mi-longue imprimée avec encolure droite. Bretelles fines réglables par nœuds. Plis sur les côtés.', 'pexels-chalo-garcia-8050047.jpg'),
(35, 'BLAZER DROIT', 4999, 'blazer-droit', 11, 'pexels-karolina-grabowska-7680147.jpg', 'Blazer droit confectionné dans un tissu en mélange de viscose. Col à revers et manches longues terminées par des plumes amovibles. Poches à rabat sur le devant. Doublure ton sur ton. Fermeture par bouton sur le devant.', 'pexels-karolina-grabowska-7680327.jpg'),
(36, 'VESTE OVERSIZE', 4999, 'veste-oversize', 11, 'pexels-monstera-7691003.jpg', 'Blazer avec col smoking et manches longues. Poches à rabat sur le devant et à passepoil sur la poitrine. Doublure ton sur ton. Fermeture par bouton sur le devant.', 'pexels-monstera-7691008.jpg'),
(37, 'BLAZER BLANC', 4999, 'blazer-blanc', 11, 'pexels-photo-7959624.jpeg', 'Blazer avec col à revers et manches longues. Poches avant à rabat. Bas avec fente à l\'arrière. Fermeture croisée sur le devant avec boutons revêtus ton sur ton.', 'pexels-photo-7959652.jpeg'),
(38, 'ENSEMBLE JUPE', 3999, 'ensemble-jupe', 9, 'pexels-chalo-garcia-8091951.jpg', 'Ensemble jupe et top à col rond, décolleté en V et manches longues. Taille élastique avec nœud. Poches avant plaquées à rabat. Revers à l\'ourlet. Fermeture par boutons dorés sur le devant.', 'pexels-chalo-garcia-8091950.jpg'),
(39, 'ROBE COURTE', 2999, 'robe-courte', 9, 'pexels-chalo-garcia-8050052.jpg', 'Robe courte avec col à revers, décolleté en V et manches longues. Plis nervurés et pinces sur le devant.', 'pexels-chalo-garcia-8050051.jpg'),
(40, 'ROBE ROUGE', 2995, 'robe-rpuge', 10, 'pexels-chalo-garcia-8049904.jpg', 'Short dress featuring a Johnny collar and long sleeves with cuffs. Flared hem.', 'pexels-chalo-garcia-8049902.jpg'),
(41, 'ROBE LONGUE IMPRIMÉE', 4999, 'robe-longue-imprimeee', 10, 'pexels-chalo-garcia-5372356.jpg', 'Robe longue à col montant et manches longues à boutons bimatière. Ouverture au dos avec nœud et bouton au col. Fermeture zip dissimulée dans la couture sur le côté.', 'pexels-chalo-garcia-5372355.jpg'),
(42, 'ROBE MI-LONGUE', 3999, 'robe-mi-longue-avec-plis', 10, 'pexels-chalo-garcia-8195732.jpg', 'Robe mi-longue avec col à revers et manches courtes. Poches latérales dissimulées dans la couture. Plis sur le devant. Fermeture à boutons sur le devant.', 'pexels-chalo-garcia-8195730.jpg'),
(43, 'ROBE COMBI', 2999, 'robe-conbi', 10, 'pexels-chalo-garcia-8050052.jpg', 'Robe mi-longue avec col à revers et manches courtes. Poches latérales dissimulées dans la couture. Plis sur le devant. Fermeture à boutons sur le devant.\r\n\r\n', 'pexels-chalo-garcia-8050051.jpg'),
(44, 'ROBE NOIR SIMPLE', 3999, 'robe-noir-simple', 10, 'pexels-chalo-garcia-8050083.jpg', 'Robe mi-longue avec col à revers et manches courtes. Poches latérales dissimulées dans la couture. Plis sur le devant. Fermeture à boutons sur le devant.', 'pexels-chalo-garcia-8050086.jpg');

-- --------------------------------------------------------

--
-- Structure de la table `purchase`
--

DROP TABLE IF EXISTS `purchase`;
CREATE TABLE IF NOT EXISTS `purchase` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `full_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `adress` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `postal_code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `city` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total` int(11) NOT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `purchased_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  PRIMARY KEY (`id`),
  KEY `IDX_6117D13BA76ED395` (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=68 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `purchase`
--

INSERT INTO `purchase` (`id`, `user_id`, `full_name`, `adress`, `postal_code`, `city`, `total`, `status`, `purchased_at`) VALUES
(27, 16, 'Guy Boulay', '17, boulevard Teixeira', '51 624', 'Grenierboeuf', 19191, 'PAID', '2022-09-12 18:41:08'),
(28, 16, 'Augustin Da Silva', '45, chemin Thierry Letellier', '15900', 'Fournier-sur-Lelievre', 13066, 'PAID', '2022-09-12 18:41:08'),
(29, 14, 'Denis Duhamel', '165, avenue de Gonzalez', '32943', 'Laurent', 11201, 'PAID', '2022-09-12 18:41:08'),
(30, 15, 'Julie de la Tessier', '23, place de Gay', '53339', 'Lamy-sur-Brun', 29405, 'PAID', '2022-09-12 18:41:08'),
(31, 14, 'Gérard Ferrand', '10, rue Inès Boutin', '09893', 'Boucher', 13655, 'PAID', '2022-09-12 18:41:08'),
(32, 17, 'Capucine Lambert', '746, boulevard de Caron', '44 064', 'Jacques', 4998, 'PAID', '2022-09-12 18:41:08'),
(33, 18, 'Lucas Lucas-Bouchet', '1, boulevard Lucie Picard', '47 064', 'Guerin', 18625, 'PAID', '2022-09-12 18:41:08'),
(34, 15, 'Sophie Brunet', '558, place de Antoine', '01992', 'Carlier-la-Forêt', 21386, 'PAID', '2022-09-12 18:41:08'),
(35, 18, 'Joseph Ollivier', '36, chemin Gregoire', '56617', 'Wagnerdan', 29123, 'PAID', '2022-09-12 18:41:08'),
(36, 16, 'Aimée Herve', '11, rue Charpentier', '98 698', 'Costeboeuf', 11043, 'PAID', '2022-09-12 18:41:08'),
(37, 15, 'Célina-Élodie Munoz', '797, impasse Neveu', '32335', 'Arnaud', 21539, 'PAID', '2022-09-12 18:41:08'),
(38, 18, 'Julie Lefebvre', '28, rue Maurice Joubert', '01791', 'Perret-sur-Seguin', 19492, 'PENDING', '2022-09-12 18:41:08'),
(39, 18, 'Denise de la Seguin', '47, chemin de Lamy', '31500', 'Gomes-la-Forêt', 2252, 'PAID', '2022-09-12 18:41:08'),
(40, 16, 'Denise Hoarau', '83, boulevard Christophe Rodrigues', '63830', 'Becker', 11296, 'PAID', '2022-09-12 18:41:08'),
(41, 14, 'Maurice Rousseau', '5, rue Gomes', '37141', 'Allain', 11641, 'PAID', '2022-09-12 18:41:08'),
(42, 16, 'Aurélie Bousquet', '715, chemin Nicolas Breton', '53297', 'Lacombe', 26253, 'PAID', '2022-09-12 18:41:08'),
(43, 16, 'Laurent Auger', '793, rue de Guillon', '40308', 'Perrin', 18385, 'PAID', '2022-09-12 18:41:08'),
(44, 16, 'Astrid de la Bonneau', 'rue de Bernard', '77901', 'Jacob-sur-Bousquet', 28542, 'PAID', '2022-09-12 18:41:08'),
(45, 18, 'Pénélope Carpentier', '84, boulevard Lesage', '33726', 'Ferrand', 9694, 'PAID', '2022-09-12 18:41:08'),
(46, 15, 'Aimée Brunet', '16, place Aimée Dupre', '84 458', 'Delahayenec', 3295, 'PAID', '2022-09-12 18:41:08'),
(47, 15, 'Pierre Clement', '57, boulevard Voisin', '62 931', 'Bruneau-les-Bains', 8347, 'PAID', '2022-09-12 18:41:08'),
(48, 17, 'Marguerite Caron', '78, place Teixeira', '52888', 'Auger', 14256, 'PAID', '2022-09-12 18:41:08'),
(49, 14, 'Isabelle-Julie Coste', 'place Duval', '01 313', 'Buisson', 17895, 'PENDING', '2022-09-12 18:41:08'),
(50, 17, 'Auguste-Grégoire Bourgeois', '299, avenue de Bourgeois', '95 336', 'Texier', 28602, 'PAID', '2022-09-12 18:41:08'),
(51, 17, 'Alfred Mahe', '4, boulevard Fischer', '88421', 'Ruiz', 4946, 'PAID', '2022-09-12 18:41:08'),
(52, 15, 'Marcelle Vincent', '19, place Christophe Simon', '85341', 'Jacobboeuf', 10745, 'PAID', '2022-09-12 18:41:08'),
(53, 16, 'Guy Gros', '85, impasse de Guerin', '47458', 'Mercier-les-Bains', 4230, 'PAID', '2022-09-12 18:41:08'),
(54, 16, 'jihane hamidou', '406 Boulevard Romain Rolland', '13009', 'Marseille 09', 5998, 'PENDING', '2022-09-13 19:07:30'),
(55, 16, 'jihane hamidou', '406 Boulevard Romain Rolland', '13009', 'Marseille 09', 5998, 'PENDING', '2022-09-13 19:08:20'),
(56, 16, 'test test', 'test test test', '13009', 'Marseille', 6998, 'PENDING', '2022-09-13 19:15:36'),
(57, 15, 'user', 'test', '13010', 'Marseille', 9998, 'PENDING', '2022-09-13 19:19:40'),
(58, 15, 'jihane hamidou', '406 Boulevard Romain Rolland', '13009', 'Marseille', 4999, 'PENDING', '2022-09-13 19:39:20'),
(59, 15, 'jiji', 'jijijijjj', '13009', 'Marseille', 14997, 'PENDING', '2022-09-13 19:52:11'),
(60, 15, 'jihane hamidou', '406 Boulevard Romain Rolland', '13009', 'Marseille', 14997, 'PENDING', '2022-09-13 19:54:06'),
(61, 15, 'jiji ha', '406 Boulevard Romain Rolland', '13009', 'Marseille', 9998, 'PENDING', '2022-09-14 10:31:22'),
(62, 15, 'jihane hamidou', '406 Boulevard Romain Rolland', '13009', 'Marseille 09', 4999, 'PENDING', '2022-09-14 14:03:44'),
(63, 15, 'jihane hamidou', '406 Boulevard Romain Rolland', '13009', 'Marseille 09', 4999, 'PENDING', '2022-09-20 09:25:59'),
(64, 15, 'jihane hamidou', '20 Parc Dromel\r\n9ème Arrondissement', '13009', 'Marseille', 4999, 'PENDING', '2022-09-20 09:28:30'),
(65, 15, 'jihane hamidou', '406 Boulevard Romain Rolland', '13009', 'Marseille 09', 4999, 'PENDING', '2022-09-20 12:35:58'),
(66, 15, 'jihane hamidou', '406 Boulevard Romain Rolland', '13009', 'Marseille 09', 9998, 'PENDING', '2022-10-07 10:26:19'),
(67, 21, 'jihane hamidou', '406 Boulevard Romain Rolland', '13009', 'Marseille 09', 3999, 'PENDING', '2022-10-07 20:01:17');

-- --------------------------------------------------------

--
-- Structure de la table `purchase_item`
--

DROP TABLE IF EXISTS `purchase_item`;
CREATE TABLE IF NOT EXISTS `purchase_item` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `product_id` int(11) DEFAULT NULL,
  `purchase_id` int(11) NOT NULL,
  `product_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `product_price` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `total` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_6FA8ED7D4584665A` (`product_id`),
  KEY `IDX_6FA8ED7D558FBEB9` (`purchase_id`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `purchase_item`
--

INSERT INTO `purchase_item` (`id`, `product_id`, `purchase_id`, `product_name`, `product_price`, `quantity`, `total`) VALUES
(3, 38, 56, 'ENSEMBLE JUPE', 3999, 1, 3999),
(4, 39, 56, 'ROBE COURTE', 2999, 1, 2999),
(5, 36, 57, 'VESTE OVERSIZE', 4999, 1, 4999),
(6, 35, 57, 'BLAZER DROIT', 4999, 1, 4999),
(7, 33, 58, 'ROBE FLEURIE', 4999, 1, 4999),
(8, 33, 59, 'ROBE FLEURIE', 4999, 3, 14997),
(9, 33, 60, 'ROBE FLEURIE', 4999, 3, 14997),
(10, 33, 61, 'ROBE FLEURIE', 4999, 2, 9998),
(11, 33, 62, 'ROBE FLEURIE', 4999, 1, 4999),
(12, 33, 63, 'ROBE FLEURIE', 4999, 1, 4999),
(13, 33, 64, 'ROBE FLEURIE', 4999, 1, 4999),
(14, 33, 65, 'ROBE FLEURIE', 4999, 1, 4999),
(15, 33, 66, 'ROBE FLEURIE', 4999, 2, 9998),
(16, 32, 67, 'ROBE PINK', 3999, 1, 3999);

-- --------------------------------------------------------

--
-- Structure de la table `user`
--

DROP TABLE IF EXISTS `user`;
CREATE TABLE IF NOT EXISTS `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(180) COLLATE utf8mb4_unicode_ci NOT NULL,
  `roles` json NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `full_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_8D93D649E7927C74` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `user`
--

INSERT INTO `user` (`id`, `email`, `roles`, `password`, `full_name`) VALUES
(13, 'admin@gmail.com', '[\"ROLE_ADMIN\"]', '$2y$13$knlPZVVTrngjE1a5dqP30OUNgSvzhxNgZrjLcxP6xcxAM40SGyb1m', 'Admin'),
(14, 'user0@gmail.com', '[]', '$2y$13$HbZe5lNIuVKs9pd.ink.deJ.G/v2XqmuznG4Qih85aEoZYhbDsIBS', 'René Perret'),
(15, 'user1@gmail.com', '[]', '$2y$13$XF2FtxggqoEmH0QBAAn.YOTVMnvQN4vikzZUTQuy8pBVnk.wmt.ma', 'Jeannine Ruiz'),
(16, 'user2@gmail.com', '[]', '$2y$13$Za/Rrb5fpfo1SysCdc0VY.zkOOjqVvN1WgPZJthaqmUUGlRTluXuu', 'Émile Marchand'),
(17, 'user3@gmail.com', '[]', '$2y$13$7Snl5PRYsa/llIERT6rmT.96SK6tGBy7.EkGTNxehAOVTacdcmEWi', 'Sylvie Ferrand'),
(18, 'user4@gmail.com', '[]', '$2y$13$Faaz7a4EE/x1m2nsOi7p1ummJ/KAZ3CDJFtZcYeo.nCA.QX/kMKeW', 'Alphonse Delmas'),
(20, 'jihanehamidou@gmail.com', '[]', '$2y$13$y6CRr6FAI/eL9DesUXU5U.O36SDVb2nnxyY9hrn5EBWYy.JAB0Wdm', 'jihane hamidou'),
(21, 'atta@hotmail.com', '[]', '$2y$13$OvHS2r6Rx42E7pxv8oBO7OeV1.yPxUgTiHHx6Uwi822WA55t/47PK', 'youssefatta');

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `category`
--
ALTER TABLE `category`
  ADD CONSTRAINT `FK_64C19C17E3C61F9` FOREIGN KEY (`owner_id`) REFERENCES `user` (`id`);

--
-- Contraintes pour la table `product`
--
ALTER TABLE `product`
  ADD CONSTRAINT `FK_D34A04AD12469DE2` FOREIGN KEY (`category_id`) REFERENCES `category` (`id`);

--
-- Contraintes pour la table `purchase`
--
ALTER TABLE `purchase`
  ADD CONSTRAINT `FK_6117D13BA76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`);

--
-- Contraintes pour la table `purchase_item`
--
ALTER TABLE `purchase_item`
  ADD CONSTRAINT `FK_6FA8ED7D4584665A` FOREIGN KEY (`product_id`) REFERENCES `product` (`id`),
  ADD CONSTRAINT `FK_6FA8ED7D558FBEB9` FOREIGN KEY (`purchase_id`) REFERENCES `purchase` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
