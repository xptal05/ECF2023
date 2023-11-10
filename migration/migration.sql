-- phpMyAdmin SQL Dump
-- version 4.9.3
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3003
-- Generation Time: Nov 10, 2023 at 09:56 AM
-- Server version: 5.7.26
-- PHP Version: 7.3.9

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- Database: `studi_ecf`
--

-- --------------------------------------------------------

--
-- Table structure for table `brands`
--

CREATE TABLE `brands` (
  `id_brand` int(11) NOT NULL,
  `name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `brands`
--

INSERT INTO `brands` (`id_brand`, `name`) VALUES
(1, 'Škoda'),
(2, 'Ford'),
(3, 'Citroen'),
(4, 'Audi'),
(5, 'Volkswagen');

-- --------------------------------------------------------

--
-- Table structure for table `feedbacks`
--

CREATE TABLE `feedbacks` (
  `id_feedback` int(11) NOT NULL,
  `client_name` varchar(255) NOT NULL,
  `rating` int(11) NOT NULL,
  `comment` varchar(255) DEFAULT NULL,
  `created` datetime NOT NULL,
  `modified` datetime DEFAULT NULL,
  `modified_by` int(11) DEFAULT NULL,
  `status` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `feedbacks`
--

INSERT INTO `feedbacks` (`id_feedback`, `client_name`, `rating`, `comment`, `created`, `modified`, `modified_by`, `status`) VALUES
(1, 'Marie Dupont', 5, 'Exceptional service! I purchased a second-hand car from this dealership, and I couldn\'t be happier. The staff was friendly, and the car was in perfect condition. Highly recommended!', '2023-10-05 10:35:50', '2023-10-31 22:45:47', 1, 1),
(2, 'Jean Leclerc', 4, 'Great selection of vehicles and competitive prices. I found the perfect car for my needs. The only reason I didn\'t give it 5 stars is because the paperwork process took a bit longer than expected.', '2023-10-05 10:35:50', '2023-10-20 15:24:16', 1, 6),
(3, 'Sophie Martin', 5, 'I had a fantastic experience buying my car here. The team was professional and knowledgeable. The car runs smoothly, and I got a great deal. I would definitely buy from them again.', '2023-10-05 10:35:50', '2023-10-20 15:24:53', 1, 6),
(4, 'Pierre Lefebvre', 4, 'Overall, a positive experience. The staff was helpful, and I got a good deal on my vehicle. The only minor issue was that the car needed a bit of cleaning on the inside.', '2023-10-05 10:35:50', '2023-10-31 10:08:44', 1, 1),
(5, 'Isabelle Tremblay', 5, 'I was impressed with the customer service at this dealership. They answered all my questions, and the car I bought is in excellent condition. I feel like I made the right choice.', '2023-10-05 10:35:50', '2023-10-31 10:08:50', 1, 6);

-- --------------------------------------------------------

--
-- Table structure for table `images`
--

CREATE TABLE `images` (
  `id_img` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `link` varchar(255) NOT NULL,
  `type` int(11) NOT NULL DEFAULT '4',
  `associated_to_vehicle` int(11) DEFAULT NULL,
  `associated_to_info` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `images`
--

INSERT INTO `images` (`id_img`, `name`, `link`, `type`, `associated_to_vehicle`, `associated_to_info`) VALUES
(1, 'skoda_octavia_main', '../uploads/1.webp', 2, 1, NULL),
(2, 'skoda_octavia_gallery', '../uploads/1_1.webp', 1, 1, NULL),
(3, 'skoda_octavia_gallery', '../uploads/1_2.webp', 1, 1, NULL),
(4, 'skoda_octavia_gallery', '../uploads/1_3.webp', 1, 1, NULL),
(5, 'ford_kuga_main', '../uploads/2.webp', 2, 2, NULL),
(6, 'ford_kuga_gallery', '../uploads/2_1.webp', 1, 2, NULL),
(7, 'ford_kuga_gallery', '../uploads/2_2.webp', 1, 2, NULL),
(8, 'ford_kuga_gallery', '../uploads/2_3.webp', 1, 2, NULL),
(9, 'citroen_jumper_main', '../uploads/3.webp', 2, 3, NULL),
(10, 'citroen_jumper_gallery', '../uploads/3_1.webp', 1, 3, NULL),
(11, 'citroen_jumper_gallery', '../uploads/3_2.webp', 1, 3, NULL),
(12, 'citroen_jumper_gallery', '../uploads/3_3.webp', 1, 3, NULL),
(13, 'audi_A6_main', '../uploads/4.webp', 2, 4, NULL),
(14, 'audi_A6_gallery', '../uploads/4_1.webp', 1, 4, NULL),
(15, 'audi_A6_gallery', '../uploads/4_2.webp', 1, 4, NULL),
(16, 'audi_A6_gallery', '../uploads/4_3.webp', 1, 4, NULL),
(17, 'volkswagen_beetle_main', '../uploads/5.webp', 2, 5, NULL),
(18, 'volkswagen_beetle_gallery', '../uploads/5_1.webp', 1, 5, NULL),
(19, 'volkswagen_beetle_gallery', '../uploads/5_2.webp', 1, 5, NULL),
(20, 'volkswagen_beetle_gallery', '../uploads/5_3.webp', 1, 5, NULL),
(22, 'repair.png', '../uploads/repair.png', 3, NULL, 1),
(23, 'car.png', '../uploads/car.png', 3, NULL, 4),
(24, 'hours.png', '../uploads/hours.png', 3, NULL, 3),
(25, 'car-repair.png', '../uploads/car-repair.png', 3, NULL, 2),
(26, 'car test.webp', '../uploads/car test.webp', 2, NULL, NULL),
(27, 'car2.jpeg', '../uploads/car2.jpeg', 1, NULL, NULL),
(28, 'car3.jpeg', '../uploads/car3.jpeg', 1, NULL, NULL),
(81, '', '../uploads/skoda_fabia.webp', 4, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `image_types`
--

CREATE TABLE `image_types` (
  `id_img_type` int(11) NOT NULL,
  `name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `image_types`
--

INSERT INTO `image_types` (`id_img_type`, `name`) VALUES
(1, 'Gallery'),
(2, 'Main_img'),
(3, 'icon'),
(4, 'undefined');

-- --------------------------------------------------------

--
-- Table structure for table `info_types`
--

CREATE TABLE `info_types` (
  `id_info_type` int(11) NOT NULL,
  `name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `info_types`
--

INSERT INTO `info_types` (`id_info_type`, `name`) VALUES
(1, 'Services'),
(2, 'Address'),
(3, 'Contact'),
(4, 'Hours'),
(5, 'About_us'),
(7, 'Reasons');

-- --------------------------------------------------------

--
-- Table structure for table `messages`
--

CREATE TABLE `messages` (
  `id_message` int(11) NOT NULL,
  `client_first_name` varchar(255) NOT NULL,
  `client_last_name` varchar(255) NOT NULL,
  `client_email` varchar(255) DEFAULT NULL,
  `client_phone` varchar(255) DEFAULT NULL,
  `message` text NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime DEFAULT NULL,
  `modified_by` int(11) DEFAULT NULL,
  `status` int(11) NOT NULL,
  `subject` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `messages`
--

INSERT INTO `messages` (`id_message`, `client_first_name`, `client_last_name`, `client_email`, `client_phone`, `message`, `created`, `modified`, `modified_by`, `status`, `subject`) VALUES
(1, 'Jean', 'Dupont', 'jean.dupont@email.com', '+33 6 12 34 56 78', 'Bonjour, je suis intéressé par votre annonce pour la voiture d\'occasion. Pouvez-vous me fournir plus d\'informations sur le véhicule ?', '2023-10-05 00:00:00', '2023-10-18 12:38:24', 1, 6, 'Demande d\'informations sur une voiture d\'occasion'),
(2, 'Marie', 'Martin', 'marie.martin@email.com', '+33 6 98 76 54 32', 'Bonjour, je souhaiterais prendre rendez-vous pour une réparation de ma voiture. Pouvez-vous me donner vos disponibilités ?', '2023-09-13 00:00:00', '2023-10-20 10:43:15', 1, 6, 'Prise de rendez-vous pour réparation'),
(3, 'Pierre', 'Lambert', 'pierre.lambert@email.com', '+33 7 11 22 33 44', 'Bonjour, j\'aimerais vendre ma voiture d\'occasion. Pourriez-vous me donner des détails sur le processus de reprise et l\'estimation de la valeur de ma voiture ?', '2023-10-05 00:00:00', '2023-10-31 10:07:42', 1, 6, 'Vente de ma voiture d\'occasion'),
(4, 'Sophie', 'Dubois', 'sophie.dubois@email.com', '+33 6 55 44 33 22', 'Bonjour, je recherche une voiture d\'occasion avec faible kilométrage et bon état. Avez-vous des véhicules correspondant à mes critères ?', '2023-08-14 00:00:00', '2023-10-20 16:40:20', 1, 6, 'Recherche de voiture d\'occasion'),
(5, 'Thomas', 'Renaud', 'thomas.renaud@email.com', '+33 6 77 88 99 00', 'Bonjour, ma voiture a besoin d\'une révision. Pouvez-vous me fournir un devis pour l\'entretien ?', '2023-10-05 00:00:00', '2023-11-02 11:37:40', 1, 8, 'Devis pour l\'entretien de ma voiture'),
(6, 'Jean', 'Dupont', 'jean.dupont@email.com', '+33 6 12 34 56 78', 'Bonjour, je suis intéressé par votre annonce pour la voiture d\'occasion. Pouvez-vous me fournir plus d\'informations sur le véhicule ?', '2023-10-05 09:51:08', '2023-11-02 11:37:47', 1, 8, 'Demande d\'informations sur une voiture d\'occasion'),
(7, 'Marie', 'Martin', 'marie.martin@email.com', '+33 6 98 76 54 32', 'Bonjour, je souhaiterais prendre rendez-vous pour une réparation de ma voiture. Pouvez-vous me donner vos disponibilités ?', '2023-10-05 09:51:08', '2023-11-01 16:25:17', 1, 6, 'Prise de rendez-vous pour réparation'),
(8, 'Pierre', 'Lambert', 'pierre.lambert@email.com', '+33 7 11 22 33 44', 'Bonjour, j\'aimerais vendre ma voiture d\'occasion. Pourriez-vous me donner des détails sur le processus de reprise et l\'estimation de la valeur de ma voiture ?', '2023-10-05 09:51:08', '2023-10-20 22:13:55', 1, 6, 'Vente de ma voiture d\'occasion'),
(9, 'Sophie', 'Dubois', 'sophie.dubois@email.com', '+33 6 55 44 33 22', 'Bonjour, je recherche une voiture d\'occasion avec faible kilométrage et bon état. Avez-vous des véhicules correspondant à mes critères ?', '2023-10-05 09:51:08', '2023-10-10 13:14:53', NULL, 8, 'Recherche de voiture d\'occasion'),
(10, 'Thomas', 'Renaud', 'thomas.renaud@email.com', '+33 6 77 88 99 00', 'Bonjour, ma voiture a besoin d\'une révision. Pouvez-vous me fournir un devis pour l\'entretien ?', '2023-10-05 09:51:08', '2023-11-09 12:05:24', 1, 6, 'Devis pour l\'entretien de ma voiture');

-- --------------------------------------------------------

--
-- Table structure for table `models`
--

CREATE TABLE `models` (
  `id_model` int(11) NOT NULL,
  `brand` int(11) NOT NULL,
  `name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `models`
--

INSERT INTO `models` (`id_model`, `brand`, `name`) VALUES
(1, 1, 'Octavia'),
(2, 2, 'Kuga'),
(3, 3, 'Jumper'),
(4, 4, 'A6 Allroad'),
(5, 5, 'Beetle');

-- --------------------------------------------------------

--
-- Table structure for table `properties_meta`
--

CREATE TABLE `properties_meta` (
  `id_meta` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `value` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `properties_meta`
--

INSERT INTO `properties_meta` (`id_meta`, `name`, `value`) VALUES
(1, 'Caroserie', 'Combi'),
(2, 'Caroserie', 'SUV'),
(3, 'Caroserie', 'Van'),
(4, 'Caroserie', 'coupe'),
(5, 'Transmission', 'Manuelle - 5 vitesses'),
(6, 'Transmission', 'Manuelle - 6 vitesses'),
(7, 'Transmission', 'Automatique - 7 vitesses'),
(8, 'Carbourant', 'Diesel'),
(9, 'Carbourant', 'Essence'),
(10, 'Carbourant', 'Hybrid'),
(11, 'Carbourant', 'Electrique'),
(12, 'Couleur', 'Blanc/#ffffff'),
(13, 'Couleur', 'Rouge-métalique/#ff0000'),
(14, 'Couleur', 'Noir-métalique/#000000'),
(15, 'Couleur', 'Noir/#000000'),
(16, 'Portes', '5'),
(17, 'Portes', '4'),
(18, 'Portes', '3'),
(19, 'Climatisation Manuelle', 'Oui'),
(20, 'Climatisation Automatique', 'Oui'),
(21, 'Régulateur Vitesse', 'Oui'),
(22, 'Capteurs de stationnement', 'Oui'),
(23, 'Caméra de recul', 'Oui'),
(24, 'Connectivité Smartphone', 'Oui'),
(25, 'Sieges chauffants', 'Oui'),
(26, 'Détecteur de pluie', 'Oui'),
(27, 'ABS', 'Oui'),
(28, 'Navigation GPS', 'Oui'),
(51, 'Couleur', 'yellow/#e3dd16'),
(55, 'Options', 'Climatisation Manuelle, Climatisation Automatique, Régulateur Vitesse, Capteurs de stationnement, Caméra de recul, Connectivité Smartphone, Sièges chauffants, Détecteur de pluie, Système d\'Assistance à la Conduite ABS, Navigation GPS');

-- --------------------------------------------------------

--
-- Table structure for table `sections_meta`
--

CREATE TABLE `sections_meta` (
  `id_section_meta` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `value` mediumtext
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `statuses`
--

CREATE TABLE `statuses` (
  `id_status` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `statuses`
--

INSERT INTO `statuses` (`id_status`, `name`, `description`) VALUES
(1, 'Active', 'vehicles,feedbacks,users'),
(2, 'New', 'vehicles,feedbacks,messages'),
(3, 'Pending', 'vehicles'),
(4, 'Reserved', 'vehicles'),
(5, 'Sold', 'vehicles'),
(6, 'Archived', 'vehicles,feedbacks,messages'),
(8, 'Done', 'messages');

-- --------------------------------------------------------

--
-- Table structure for table `testBdd`
--

CREATE TABLE `testBdd` (
  `id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id_user` int(11) NOT NULL,
  `last_name` varchar(255) DEFAULT NULL,
  `first_name` varchar(255) DEFAULT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `active_since` datetime DEFAULT NULL,
  `role` int(11) NOT NULL,
  `status` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id_user`, `last_name`, `first_name`, `email`, `password`, `active_since`, `role`, `status`) VALUES
(1, 'Parrot', 'Jean', 'parrot@parrot.fr', '$2y$12$7asORAKeO.qLVu7fu8O8ge.49h6WIBHbZbBu/C.P6RvcR8amUPnHy', '2023-10-01 00:00:00', 1, 2),
(13, 'Lanneree', 'Seb', 'seb@gmail.com', '$2y$12$k/rB8Flc05U.lReh57S/gerz7/SoIEKAOoi4OXdtGrrhBtR8jtK2m', '2023-10-31 10:25:06', 2, 1);

-- --------------------------------------------------------

--
-- Table structure for table `user_roles`
--

CREATE TABLE `user_roles` (
  `id_role` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `user_roles`
--

INSERT INTO `user_roles` (`id_role`, `name`, `description`) VALUES
(1, 'Admin', 'all rights.......'),
(2, 'Employee', 'messages, feedback, cars');

-- --------------------------------------------------------

--
-- Table structure for table `vehicles`
--

CREATE TABLE `vehicles` (
  `id_vehicle` int(11) NOT NULL,
  `brand` int(11) NOT NULL,
  `model` int(11) NOT NULL,
  `status` int(11) NOT NULL,
  `year` int(11) DEFAULT NULL,
  `km` int(11) DEFAULT NULL,
  `price` int(11) DEFAULT NULL,
  `conformity` varchar(255) DEFAULT NULL,
  `consumption` varchar(255) DEFAULT NULL,
  `other_equipment` mediumtext,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  `modified_by` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `vehicles`
--

INSERT INTO `vehicles` (`id_vehicle`, `brand`, `model`, `status`, `year`, `km`, `price`, `conformity`, `consumption`, `other_equipment`, `created`, `modified`, `modified_by`) VALUES
(1, 1, 1, 1, 2014, 221408, 9394, 'EURO5', '1.6 TDI, 77kW', 'Autorádio,Centrální zamykání,Elektrická okna,Kožený volant,Loketní opěrka,Multifunkční volant,Palubní počítač,Tónovaná skla,Ukotvení pro dětské sedačky', NULL, '2023-11-09 11:14:47', 1),
(2, 2, 2, 6, 2020, 8560, 680003, 'EURO6, 10/2026', '1.5 EcoBoost, 110kW', 'Autorádio,Bezklíčkové startování,Centrální zamykání,Elektrická okna,Elektrická parkovací brzda,Kožený volant,Multifunkční volant,Palubní počítač,Posilovač řízení,Stop Start system,Tónovaná skla,Ukotvení pro dětské sedačky,Volba jízdního režimu,Vyhřívané čelní sklo,Vyhřívaný volant', '2023-10-01 21:51:06', '2023-10-31 10:10:24', 1),
(3, 3, 3, 1, 2020, 198591, 13600, 'EURO6, 06/2025', '2.0 BlueHDi, 120kW', 'Autorádio,Centrální zamykání,Elektrická okna,Multifunkční volant,Palubní počítač,Posilovač řízení', NULL, '2023-10-31 21:48:41', NULL),
(4, 4, 4, 1, 2018, 79304, 25200, 'EURO6', '3.0 TDI, 160kW, 4x4', 'Adaptivní tempomat,Autorádio,Bezklíčkové startování,Centrální zamykání,Elektrická okna,Elektrická parkovací brzda,Elektrické dovírání dveří,Kožený volant,Multifunkční volant,Nezávislé topení,Palubní počítač,Polokožené sedačky,Posilovač řízení,Stop Start system,Tónovaná skla,Ukotvení pro dětské sedačky,Vyhřívané sedačky', NULL, '2023-10-20 22:20:15', 1),
(5, 5, 5, 6, 2015, 232532, 5000, 'EURO5, 08/2025', '1.6 TDI, 77kW', '11Autorádio,CD měnič,Centrální zamykání,Elektrická okna,Kožený volant,Multifunkční volant,Palubní počítač,Posilovač řízení,Tónovaná skla,Ukotvení pro dětské sedačky', NULL, '2023-11-09 11:18:32', 1);

-- --------------------------------------------------------

--
-- Table structure for table `vehicle_properties`
--

CREATE TABLE `vehicle_properties` (
  `id` int(11) NOT NULL,
  `vehicle` int(11) NOT NULL,
  `property_name` varchar(255) DEFAULT NULL,
  `property` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `vehicle_properties`
--

INSERT INTO `vehicle_properties` (`id`, `vehicle`, `property_name`, `property`) VALUES
(1, 1, 'Caroserie', '2'),
(2, 1, 'Transmission', '5'),
(3, 1, 'Carbourant', '8'),
(4, 1, 'Couleur', '12'),
(5, 1, 'Portes', '16'),
(6, 1, 'Options', 'Climatisation Automatique, Régulateur Vitesse, Capteurs de stationnement, Connectivité Smartphone, Détecteur de pluie, ABS, Navigation GPS'),
(13, 2, 'Caroserie', '2'),
(14, 2, 'Transmission', '6'),
(15, 2, 'Carbourant', '9'),
(16, 2, 'Couleur', '13'),
(17, 2, 'Portes', '16'),
(18, 2, 'Options', 'Climatisation Automatique, Régulateur Vitesse, Capteurs de stationnement, Connectivité Smartphone, Détecteur de pluie, Système d\'Assistance à la Conduite, ABS, Navigation GPS'),
(25, 3, 'Caroserie', '3'),
(26, 3, 'Transmission', '5'),
(27, 3, 'Carbourant', '9'),
(28, 3, 'Couleur', '13'),
(29, 3, 'Portes', '17'),
(30, 3, 'Options', 'Climatisation Manuelle, Régulateur Vitesse, Connectivité Smartphone, ABS'),
(34, 4, 'Caroserie', '1'),
(35, 4, 'Transmission', '7'),
(36, 4, 'Carbourant', '8'),
(37, 4, 'Couleur', '14'),
(38, 4, 'Portes', '16'),
(39, 4, 'Options', 'Climatisation Automatique, Régulateur Vitesse, Capteurs de stationnement, Caméra de recul, Connectivité Smartphone, Sieges chauffants, Détecteur de pluie, Système d\'Assistance à la Conduite, ABS, Navigation GPS'),
(48, 5, 'Caroserie', '3'),
(49, 5, 'Transmission', '5'),
(50, 5, 'Carbourant', '8'),
(51, 5, 'Couleur', '15'),
(52, 5, 'Portes', '18'),
(53, 5, 'Options', 'Climatisation Automatique, Régulateur Vitesse, Capteurs de stationnement, Connectivité Smartphone, Sieges chauffants, Détecteur de pluie, ABS');

-- --------------------------------------------------------

--
-- Table structure for table `web_page_info`
--

CREATE TABLE `web_page_info` (
  `id_info` int(11) NOT NULL,
  `type` int(11) NOT NULL,
  `order` int(11) DEFAULT NULL,
  `text` text,
  `category` varchar(24) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `web_page_info`
--

INSERT INTO `web_page_info` (`id_info`, `type`, `order`, `text`, `category`) VALUES
(1, 1, 4, 'Réparation de la carrosserie', 'heading'),
(2, 1, 2, 'Réparation de la mécanique', 'heading'),
(3, 1, 3, 'entretien régulier', 'heading'),
(4, 1, 1, 'Vente des véhicules', 'heading'),
(5, 2, 1, '33 Rue Smith', 'street'),
(6, 2, 2, 'Lyon', 'city'),
(7, 2, 3, '69002', 'postal_code'),
(8, 3, 5, '+33612345678', 'phone'),
(9, 3, 2, 'info@parrot.fr', 'email'),
(10, 4, 1, 'Lundi : 9h30 - 17h00', NULL),
(11, 4, 2, 'Mardi : 9h00 - 17h00', NULL),
(12, 4, 3, 'Mercredi : 9h00 - 17h00', NULL),
(13, 4, 4, 'Jeudi : 9h00 - 17h00', NULL),
(14, 4, 5, 'Vendredi : 9h00 - 18h00', NULL),
(15, 4, 6, 'Samedi : 10h00 - 14h00', NULL),
(16, 4, 7, 'Dimanche : Fermé', NULL),
(33, 5, 1, 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.', NULL),
(34, 7, 1, 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.', 'text'),
(35, 7, 1, 'reason one modified', 'heading'),
(36, 7, 2, 'reason two', 'heading'),
(37, 7, 3, 'reason three', 'heading'),
(38, 7, 4, 'reason four', 'heading'),
(41, 1, 3, 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. ', 'text'),
(46, 1, 1, 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. ', 'text'),
(48, 1, 2, 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. ', 'text'),
(50, 1, 4, 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. ', 'text'),
(58, 2, 1, '45.744879648184956', 'latitude'),
(59, 2, 2, '4.824251020749312', 'longitude');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `brands`
--
ALTER TABLE `brands`
  ADD PRIMARY KEY (`id_brand`);

--
-- Indexes for table `feedbacks`
--
ALTER TABLE `feedbacks`
  ADD PRIMARY KEY (`id_feedback`),
  ADD KEY `modified_by` (`modified_by`),
  ADD KEY `status` (`status`);

--
-- Indexes for table `images`
--
ALTER TABLE `images`
  ADD PRIMARY KEY (`id_img`),
  ADD KEY `type` (`type`),
  ADD KEY `associated_to_vehicle` (`associated_to_vehicle`),
  ADD KEY `images_ibfk_4` (`associated_to_info`);

--
-- Indexes for table `image_types`
--
ALTER TABLE `image_types`
  ADD PRIMARY KEY (`id_img_type`);

--
-- Indexes for table `info_types`
--
ALTER TABLE `info_types`
  ADD PRIMARY KEY (`id_info_type`);

--
-- Indexes for table `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`id_message`),
  ADD KEY `modified_by` (`modified_by`),
  ADD KEY `status` (`status`);

--
-- Indexes for table `models`
--
ALTER TABLE `models`
  ADD PRIMARY KEY (`id_model`),
  ADD KEY `brand` (`brand`);

--
-- Indexes for table `properties_meta`
--
ALTER TABLE `properties_meta`
  ADD PRIMARY KEY (`id_meta`);

--
-- Indexes for table `sections_meta`
--
ALTER TABLE `sections_meta`
  ADD PRIMARY KEY (`id_section_meta`);

--
-- Indexes for table `statuses`
--
ALTER TABLE `statuses`
  ADD PRIMARY KEY (`id_status`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id_user`),
  ADD KEY `role` (`role`),
  ADD KEY `status` (`status`);

--
-- Indexes for table `user_roles`
--
ALTER TABLE `user_roles`
  ADD PRIMARY KEY (`id_role`);

--
-- Indexes for table `vehicles`
--
ALTER TABLE `vehicles`
  ADD PRIMARY KEY (`id_vehicle`),
  ADD KEY `brand` (`brand`),
  ADD KEY `model` (`model`),
  ADD KEY `status` (`status`);

--
-- Indexes for table `vehicle_properties`
--
ALTER TABLE `vehicle_properties`
  ADD PRIMARY KEY (`id`),
  ADD KEY `vehicle` (`vehicle`);

--
-- Indexes for table `web_page_info`
--
ALTER TABLE `web_page_info`
  ADD PRIMARY KEY (`id_info`),
  ADD KEY `type` (`type`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `brands`
--
ALTER TABLE `brands`
  MODIFY `id_brand` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `feedbacks`
--
ALTER TABLE `feedbacks`
  MODIFY `id_feedback` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `images`
--
ALTER TABLE `images`
  MODIFY `id_img` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=82;

--
-- AUTO_INCREMENT for table `image_types`
--
ALTER TABLE `image_types`
  MODIFY `id_img_type` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `info_types`
--
ALTER TABLE `info_types`
  MODIFY `id_info_type` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `messages`
--
ALTER TABLE `messages`
  MODIFY `id_message` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- AUTO_INCREMENT for table `models`
--
ALTER TABLE `models`
  MODIFY `id_model` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `properties_meta`
--
ALTER TABLE `properties_meta`
  MODIFY `id_meta` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=79;

--
-- AUTO_INCREMENT for table `sections_meta`
--
ALTER TABLE `sections_meta`
  MODIFY `id_section_meta` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `statuses`
--
ALTER TABLE `statuses`
  MODIFY `id_status` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id_user` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `user_roles`
--
ALTER TABLE `user_roles`
  MODIFY `id_role` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `vehicles`
--
ALTER TABLE `vehicles`
  MODIFY `id_vehicle` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `vehicle_properties`
--
ALTER TABLE `vehicle_properties`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=54;

--
-- AUTO_INCREMENT for table `web_page_info`
--
ALTER TABLE `web_page_info`
  MODIFY `id_info` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=60;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `feedbacks`
--
ALTER TABLE `feedbacks`
  ADD CONSTRAINT `feedbacks_ibfk_1` FOREIGN KEY (`modified_by`) REFERENCES `users` (`id_user`),
  ADD CONSTRAINT `feedbacks_ibfk_2` FOREIGN KEY (`status`) REFERENCES `statuses` (`id_status`);

--
-- Constraints for table `images`
--
ALTER TABLE `images`
  ADD CONSTRAINT `images_ibfk_1` FOREIGN KEY (`type`) REFERENCES `image_types` (`id_img_type`),
  ADD CONSTRAINT `images_ibfk_2` FOREIGN KEY (`associated_to_vehicle`) REFERENCES `vehicles` (`id_vehicle`),
  ADD CONSTRAINT `images_ibfk_4` FOREIGN KEY (`associated_to_info`) REFERENCES `web_page_info` (`id_info`);

--
-- Constraints for table `messages`
--
ALTER TABLE `messages`
  ADD CONSTRAINT `messages_ibfk_1` FOREIGN KEY (`modified_by`) REFERENCES `users` (`id_user`),
  ADD CONSTRAINT `messages_ibfk_2` FOREIGN KEY (`status`) REFERENCES `statuses` (`id_status`);

--
-- Constraints for table `models`
--
ALTER TABLE `models`
  ADD CONSTRAINT `models_ibfk_1` FOREIGN KEY (`brand`) REFERENCES `brands` (`id_brand`);

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_ibfk_1` FOREIGN KEY (`role`) REFERENCES `user_roles` (`id_role`),
  ADD CONSTRAINT `users_ibfk_2` FOREIGN KEY (`status`) REFERENCES `statuses` (`id_status`);

--
-- Constraints for table `vehicles`
--
ALTER TABLE `vehicles`
  ADD CONSTRAINT `vehicles_ibfk_1` FOREIGN KEY (`brand`) REFERENCES `brands` (`id_brand`),
  ADD CONSTRAINT `vehicles_ibfk_2` FOREIGN KEY (`model`) REFERENCES `models` (`id_model`),
  ADD CONSTRAINT `vehicles_ibfk_3` FOREIGN KEY (`status`) REFERENCES `statuses` (`id_status`);

--
-- Constraints for table `vehicle_properties`
--
ALTER TABLE `vehicle_properties`
  ADD CONSTRAINT `vehicle_properties_ibfk_1` FOREIGN KEY (`vehicle`) REFERENCES `vehicles` (`id_vehicle`);

--
-- Constraints for table `web_page_info`
--
ALTER TABLE `web_page_info`
  ADD CONSTRAINT `web_page_info_ibfk_1` FOREIGN KEY (`type`) REFERENCES `info_types` (`id_info_type`);
