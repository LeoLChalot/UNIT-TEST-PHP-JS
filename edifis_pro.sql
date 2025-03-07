-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le : jeu. 06 mars 2025 à 22:49
-- Version du serveur : 8.3.0
-- Version de PHP : 8.2.18

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `edifis_pro`
--

-- --------------------------------------------------------

--
-- Structure de la table `assignation`
--

DROP TABLE IF EXISTS `assignation`;
CREATE TABLE IF NOT EXISTS `assignation` (
  `id` int NOT NULL AUTO_INCREMENT,
  `employe_id` int NOT NULL,
  `tache_id` int NOT NULL,
  `date_de_debut` date NOT NULL,
  `date_de_fin` date NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_D2A03CE01B65292` (`employe_id`),
  KEY `IDX_D2A03CE0D2235D39` (`tache_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `chantier`
--

DROP TABLE IF EXISTS `chantier`;
CREATE TABLE IF NOT EXISTS `chantier` (
  `id` int NOT NULL AUTO_INCREMENT,
  `chef_de_chantier_id` int NOT NULL,
  `client_id` int NOT NULL,
  `date_de_debut` date NOT NULL,
  `date_de_fin` date NOT NULL,
  `nom` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `date_tache_suivante` date NOT NULL,
  `numero_de_la_voie` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type_de_voie` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `libelle_de_la_voie` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `code_postal` int NOT NULL,
  `ville` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `statut` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_636F27F6A6C7D29B` (`chef_de_chantier_id`),
  KEY `IDX_636F27F619EB6921` (`client_id`)
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `chantier`
--

INSERT INTO `chantier` (`id`, `chef_de_chantier_id`, `client_id`, `date_de_debut`, `date_de_fin`, `nom`, `date_tache_suivante`, `numero_de_la_voie`, `type_de_voie`, `libelle_de_la_voie`, `code_postal`, `ville`, `statut`) VALUES
(19, 41, 29, '2025-03-01', '2025-03-05', 'Rénovation Maison', '2025-03-01', '123', 'Rue', 'de la Paix', 75000, 'Paris', 'Terminé'),
(20, 43, 30, '2025-03-06', '2025-03-10', 'Construction Immeuble', '2025-03-06', '456', 'Avenue', 'de la République', 69000, 'Lyon', 'En cours'),
(21, 43, 31, '2025-03-11', '2025-03-15', 'Extension Maison', '2025-03-11', '789', 'Boulevard', 'des Champs-Élysées', 75008, 'Paris', 'A venir'),
(22, 44, 32, '2025-03-16', '2025-03-20', 'Rénovation Appartement', '2025-03-16', '321', 'Rue', 'Saint-Honoré', 75001, 'Paris', 'A venir'),
(23, 41, 33, '2025-03-21', '2025-03-25', 'Construction Villa', '2025-03-21', '654', 'Allée', 'des Roses', 13000, 'Marseille', 'A venir'),
(24, 42, 34, '2025-03-26', '2025-03-30', 'Rénovation Bureau', '2025-03-26', '987', 'Avenue', 'des Fleurs', 75009, 'Paris', 'A venir');

-- --------------------------------------------------------

--
-- Structure de la table `client`
--

DROP TABLE IF EXISTS `client`;
CREATE TABLE IF NOT EXISTS `client` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nom` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `telephone` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=36 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `client`
--

INSERT INTO `client` (`id`, `nom`, `telephone`) VALUES
(29, 'Bernard', '061234567898'),
(30, 'Dubois', '061234567899'),
(31, 'Moreau', '061234567800'),
(32, 'Lemoine', '061234567803'),
(33, 'Renaud', '061234567804'),
(34, 'Durant', '061234567805'),
(35, 'Leroux', '061234567806');

-- --------------------------------------------------------

--
-- Structure de la table `doctrine_migration_versions`
--

DROP TABLE IF EXISTS `doctrine_migration_versions`;
CREATE TABLE IF NOT EXISTS `doctrine_migration_versions` (
  `version` varchar(191) COLLATE utf8mb3_unicode_ci NOT NULL,
  `executed_at` datetime DEFAULT NULL,
  `execution_time` int DEFAULT NULL,
  PRIMARY KEY (`version`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

--
-- Déchargement des données de la table `doctrine_migration_versions`
--

INSERT INTO `doctrine_migration_versions` (`version`, `executed_at`, `execution_time`) VALUES
('DoctrineMigrations\\Version20250306213717', '2025-03-06 21:37:21', 392),
('DoctrineMigrations\\Version20250306213909', '2025-03-06 21:39:14', 35);

-- --------------------------------------------------------

--
-- Structure de la table `employe`
--

DROP TABLE IF EXISTS `employe`;
CREATE TABLE IF NOT EXISTS `employe` (
  `id` int NOT NULL AUTO_INCREMENT,
  `metier_id` int NOT NULL,
  `nom` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `prenom` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `telephone` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `est_chef_de_chantier` tinyint(1) NOT NULL,
  `disponible` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_F804D3B9ED16FA20` (`metier_id`)
) ENGINE=InnoDB AUTO_INCREMENT=51 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `employe`
--

INSERT INTO `employe` (`id`, `metier_id`, `nom`, `prenom`, `telephone`, `est_chef_de_chantier`, `disponible`) VALUES
(41, 25, 'Dupont', 'Jean', '061234567892', 1, 1),
(42, 26, 'Martin', 'Marie', '061234567893', 1, 1),
(43, 27, 'Durand', 'Paul', '061234567894', 1, 1),
(44, 28, 'Lefevre', 'Julie', '061234567895', 1, 1),
(45, 29, 'Leroy', 'Pierre', '061234567896', 0, 1),
(46, 30, 'Roux', 'Sophie', '061234567897', 1, 1),
(47, 25, 'Petit', 'Luc', '061234567801', 0, 1),
(48, 26, 'Garcia', 'Laura', '061234567802', 0, 1),
(49, 27, 'Blanc', 'Alice', '061234567803', 0, 1),
(50, 28, 'Noir', 'Jacques', '061234567804', 0, 1);

-- --------------------------------------------------------

--
-- Structure de la table `messenger_messages`
--

DROP TABLE IF EXISTS `messenger_messages`;
CREATE TABLE IF NOT EXISTS `messenger_messages` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `body` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `headers` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue_name` varchar(190) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  `available_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  `delivered_at` datetime DEFAULT NULL COMMENT '(DC2Type:datetime_immutable)',
  PRIMARY KEY (`id`),
  KEY `IDX_75EA56E0FB7336F0` (`queue_name`),
  KEY `IDX_75EA56E0E3BD61CE` (`available_at`),
  KEY `IDX_75EA56E016BA31DB` (`delivered_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `metier`
--

DROP TABLE IF EXISTS `metier`;
CREATE TABLE IF NOT EXISTS `metier` (
  `id` int NOT NULL AUTO_INCREMENT,
  `label` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=31 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `metier`
--

INSERT INTO `metier` (`id`, `label`) VALUES
(25, 'Plombier'),
(26, 'Électricien'),
(27, 'Maçon'),
(28, 'Carreleur'),
(29, 'Peintre'),
(30, 'Menuisier');

-- --------------------------------------------------------

--
-- Structure de la table `tache`
--

DROP TABLE IF EXISTS `tache`;
CREATE TABLE IF NOT EXISTS `tache` (
  `id` int NOT NULL AUTO_INCREMENT,
  `chantier_id` int NOT NULL,
  `description` longtext COLLATE utf8mb4_unicode_ci,
  `statut` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `duree` double NOT NULL,
  `date_de_fin` date NOT NULL,
  `date_de_debut` date NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_93872075D0C0049D` (`chantier_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `tache_employe`
--

DROP TABLE IF EXISTS `tache_employe`;
CREATE TABLE IF NOT EXISTS `tache_employe` (
  `tache_id` int NOT NULL,
  `employe_id` int NOT NULL,
  PRIMARY KEY (`tache_id`,`employe_id`),
  KEY `IDX_3252ED0CD2235D39` (`tache_id`),
  KEY `IDX_3252ED0C1B65292` (`employe_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `user`
--

DROP TABLE IF EXISTS `user`;
CREATE TABLE IF NOT EXISTS `user` (
  `id` int NOT NULL AUTO_INCREMENT,
  `email` varchar(180) COLLATE utf8mb4_unicode_ci NOT NULL,
  `roles` json NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nom` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `prenom` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `telephone` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_IDENTIFIER_EMAIL` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `user`
--

INSERT INTO `user` (`id`, `email`, `roles`, `password`, `nom`, `prenom`, `telephone`) VALUES
(9, 'superadmin@admin.fr', '[\"ROLE_SUPER_ADMIN\"]', '$2y$13$LNvtJ5oO4vV5I/Us9qySRObY5t4TaKMSCBvLEEp7IWp5maoJx/3Hi', 'super', 'admin', '061234567890'),
(10, 'admin@admin.fr', '[\"ROLE_ADMIN\"]', '$2y$13$h.gtaqHK/QIBrqKTRQgfIO2JECctjk6vT/vxGcQyrXLPg6xGLrcrK', 'admin', 'admin', '061234567891');

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `assignation`
--
ALTER TABLE `assignation`
  ADD CONSTRAINT `FK_D2A03CE01B65292` FOREIGN KEY (`employe_id`) REFERENCES `employe` (`id`),
  ADD CONSTRAINT `FK_D2A03CE0D2235D39` FOREIGN KEY (`tache_id`) REFERENCES `tache` (`id`);

--
-- Contraintes pour la table `chantier`
--
ALTER TABLE `chantier`
  ADD CONSTRAINT `FK_636F27F619EB6921` FOREIGN KEY (`client_id`) REFERENCES `client` (`id`),
  ADD CONSTRAINT `FK_636F27F6A6C7D29B` FOREIGN KEY (`chef_de_chantier_id`) REFERENCES `employe` (`id`);

--
-- Contraintes pour la table `employe`
--
ALTER TABLE `employe`
  ADD CONSTRAINT `FK_F804D3B9ED16FA20` FOREIGN KEY (`metier_id`) REFERENCES `metier` (`id`);

--
-- Contraintes pour la table `tache`
--
ALTER TABLE `tache`
  ADD CONSTRAINT `FK_93872075D0C0049D` FOREIGN KEY (`chantier_id`) REFERENCES `chantier` (`id`);

--
-- Contraintes pour la table `tache_employe`
--
ALTER TABLE `tache_employe`
  ADD CONSTRAINT `FK_3252ED0C1B65292` FOREIGN KEY (`employe_id`) REFERENCES `employe` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `FK_3252ED0CD2235D39` FOREIGN KEY (`tache_id`) REFERENCES `tache` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
