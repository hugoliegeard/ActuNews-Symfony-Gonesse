-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le :  jeu. 07 mai 2020 à 12:34
-- Version du serveur :  5.7.26
-- Version de PHP :  7.3.5

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données :  `actunewssf`
--

-- --------------------------------------------------------

--
-- Structure de la table `article`
--

DROP TABLE IF EXISTS `article`;
CREATE TABLE IF NOT EXISTS `article` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `category_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `alias` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `content` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_date` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_23A0E6612469DE2` (`category_id`),
  KEY `IDX_23A0E66A76ED395` (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `article`
--

INSERT INTO `article` (`id`, `category_id`, `user_id`, `title`, `alias`, `content`, `image`, `created_date`) VALUES
(1, 1, 1, 'Six Français sur dix ne font pas confiance au gouvernement pour réussir le déconfinement, selon notre sondage', 'six-francais-sur-dix-ne-font-pas-confiance-au-gouvernement-pour-reussir-le-deconfinement-selon-notre-sondage', '<p>Initialement confiants dans le gouvernement lors de l’annonce du confinement, les Français ne lui font plus confiance depuis fin mars, selon un sondage Odoxa-Dentsu Consulting pour franceinfo et \"Le Figaro\" publié mercredi.</p>', '21470759.jpg', '2020-05-07 12:12:59'),
(2, 2, 2, '\"Ça m’aurait fait suer de couler mon exploitation\" : la réouverture des marchés dissipe un peu la morosité des maraîchers', 'ca-maurait-fait-suer-de-couler-mon-exploitation-la-reouverture-des-marches-dissipe-un-peu-la-morosite-des-maraichers', '<p>À l’arrêt depuis deux mois, les marchés alimentaires vont pouvoir rouvrir dès lundi, avec le déconfinement progressif. Un soulagement pour les petits producteurs et notamment les maraîchers qui y vendent leur production.</p>', '21474455.jpg', '2020-05-07 12:27:19'),
(3, 3, 3, 'Street art : notre deuxième tour du monde des plus belles œuvres face au coronavirus', 'street-art-notre-deuxieme-tour-du-monde-des-plus-belles-oeuvres-face-au-coronavirus', '<p>Les street artistes du monde entier s\'en sont visiblement donné à coeur joie lors de leurs brèves sorties en temps de confinement pour égayer les murs des villes, rendre hommage aux soignants, bousculer le virus et rappeler les consignes de sécurité face à la pandémie.</p>', '21467399.jpg', '2020-05-07 12:29:01'),
(4, 4, 4, 'Coronavirus : la piste d’une contamination pour des participants aux Jeux militaires de Wuhan en octobre \"tout à fait plausible\" estime un infectiologue', 'coronavirus-la-piste-dune-contamination-pour-des-participants-aux-jeux-militaires-de-wuhan-en-octobre-tout-a-fait-plausible-estime-un-infectiologue', '<p>Le professeur Éric Caumes, chef du service des maladies infectieuses et tropicales de l\'hôpital de la Pitié-Salpêtrière à Paris envisage comme \"tout à fait plausible\" la piste d’une contamination au Covid-19 pour des participants aux Jeux militaires de Wuhan (Chine) en octobre.</p>', '21471351.jpg', '2020-05-07 12:31:14'),
(5, 5, 5, 'Applications contre le Covid-19 : une tribune cosignée par Marlène Schiappa appelle à ne pas \"cautionner des discriminations sous couvert d’innovation\"', 'applications-contre-le-covid-19-une-tribune-cosignee-par-marlene-schiappa-appelle-a-ne-pas-cautionner-des-discriminations-sous-couvert-dinnovation', '<p>Les auteurs de la tribune, dont la secrétaire d\'Etat, estiment qu\'\"il y a un danger manifeste pour les libertés fondamentales\" avec les applications pour lutter contre le coronavirus.</p>', '21463399.jpg', '2020-05-07 12:33:56');

-- --------------------------------------------------------

--
-- Structure de la table `category`
--

DROP TABLE IF EXISTS `category`;
CREATE TABLE IF NOT EXISTS `category` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `alias` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `category`
--

INSERT INTO `category` (`id`, `name`, `alias`) VALUES
(1, 'Politique', 'politique'),
(2, 'Economie', 'economie'),
(3, 'Culture', 'culture'),
(4, 'Santé', 'sante'),
(5, 'Sciences', 'sciences');

-- --------------------------------------------------------

--
-- Structure de la table `migration_versions`
--

DROP TABLE IF EXISTS `migration_versions`;
CREATE TABLE IF NOT EXISTS `migration_versions` (
  `version` varchar(14) COLLATE utf8mb4_unicode_ci NOT NULL,
  `executed_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  PRIMARY KEY (`version`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `migration_versions`
--

INSERT INTO `migration_versions` (`version`, `executed_at`) VALUES
('20200507095347', '2020-05-07 09:58:16'),
('20200507100149', '2020-05-07 10:02:49');

-- --------------------------------------------------------

--
-- Structure de la table `user`
--

DROP TABLE IF EXISTS `user`;
CREATE TABLE IF NOT EXISTS `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `firstname` varchar(80) COLLATE utf8mb4_unicode_ci NOT NULL,
  `lastname` varchar(80) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(80) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `roles` longtext COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '(DC2Type:array)',
  `registration_date` datetime NOT NULL,
  `last_login_date` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `user`
--

INSERT INTO `user` (`id`, `firstname`, `lastname`, `email`, `password`, `roles`, `registration_date`, `last_login_date`) VALUES
(1, 'Hugo', 'LIEGEARD', 'hugo@actunews.com', '1234', 'a:1:{i:0;s:11:\"ROLE_AUTHOR\";}', '2020-05-07 12:12:59', NULL),
(2, 'Houda', 'JAADAR', 'houda@actunews.com', '1234', 'a:1:{i:0;s:11:\"ROLE_AUTHOR\";}', '2020-05-07 12:27:19', NULL),
(3, 'Yaya', 'DIALLO', 'yaya@actunews.com', '1234', 'a:1:{i:0;s:11:\"ROLE_AUTHOR\";}', '2020-05-07 12:29:01', NULL),
(4, 'Hicham', 'AMARA', 'hicham@actunews.com', '1234', 'a:1:{i:0;s:11:\"ROLE_AUTHOR\";}', '2020-05-07 12:31:14', NULL),
(5, 'Anthony', 'DRAPIER', 'anthony@actunews.com', '1234', 'a:1:{i:0;s:11:\"ROLE_AUTHOR\";}', '2020-05-07 12:33:56', NULL);

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `article`
--
ALTER TABLE `article`
  ADD CONSTRAINT `FK_23A0E6612469DE2` FOREIGN KEY (`category_id`) REFERENCES `category` (`id`),
  ADD CONSTRAINT `FK_23A0E66A76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
