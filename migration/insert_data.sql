INSERT INTO `brands` (`name`) VALUES
('Škoda'),
('Ford'),
('Citroen'),
('Audi'),
('Volkswagen'),
('test');

INSERT INTO `feedbacks` (`client_name`, `rating`, `comment`, `created`, `modified`, `modified_by`, `status`) VALUES
('Marie Dupont', 5, 'Exceptional service! I purchased a second-hand car from this dealership, and I couldn\'t be happier. The staff was friendly, and the car was in perfect condition. Highly recommended!', '2023-10-05 10:35:50', '2023-10-31 22:45:47', 1, 1),
('Jean Leclerc', 4, 'Great selection of vehicles and competitive prices. I found the perfect car for my needs. The only reason I didn\'t give it 5 stars is because the paperwork process took a bit longer than expected.', '2023-10-05 10:35:50', '2023-10-20 15:24:16', 1, 6),
('Sophie Martin', 5, 'I had a fantastic experience buying my car here. The team was professional and knowledgeable. The car runs smoothly, and I got a great deal. I would definitely buy from them again.', '2023-10-05 10:35:50', '2023-10-20 15:24:53', 1, 6),
('Pierre Lefebvre', 4, 'Overall, a positive experience. The staff was helpful, and I got a good deal on my vehicle. The only minor issue was that the car needed a bit of cleaning on the inside.', '2023-10-05 10:35:50', '2023-10-31 10:08:44', 1, 1),
('Isabelle Tremblay', 5, 'I was impressed with the customer service at this dealership. They answered all my questions, and the car I bought is in excellent condition. I feel like I made the right choice.', '2023-10-05 10:35:50', '2023-10-31 10:08:50', 1, 6);

INSERT INTO `images` (`name`, `link`, `type`, `associated_to_vehicle`, `associated_to_info`) VALUES
('skoda_octavia_main', '../images_voiture/1.webp', 2, 1, NULL),
('skoda_octavia_gallery', '../images_voiture/1_1.webp', 1, 1, NULL),
('skoda_octavia_gallery', '../images_voiture/1_2.webp', 1, 1, NULL),
('skoda_octavia_gallery', '../images_voiture/1_3.webp', 1, 1, NULL),
('ford_kuga_main', '../images_voiture/2.webp', 2, 2, NULL),
('ford_kuga_gallery', '../images_voiture/2_1.webp', 1, 2, NULL),
('ford_kuga_gallery', '../images_voiture/2_2.webp', 1, 2, NULL),
('ford_kuga_gallery', '../images_voiture/2_3.webp', 1, 2, NULL),
('citroen_jumper_main', '../images_voiture/3.webp', 2, 3, NULL),
('citroen_jumper_gallery', '../images_voiture/3_1.webp', 1, 3, NULL),
('citroen_jumper_gallery', '../images_voiture/3_2.webp', 1, 3, NULL),
('citroen_jumper_gallery', '../images_voiture/3_3.webp', 1, 3, NULL),
('audi_A6_main', '../images_voiture/4.webp', 2, 4, NULL),
('audi_A6_gallery', '../images_voiture/4_1.webp', 1, 4, NULL),
('audi_A6_gallery', '../images_voiture/4_2.webp', 1, 4, NULL),
('audi_A6_gallery', '../images_voiture/4_3.webp', 1, 4, NULL),
('volkswagen_beetle_main', '../images_voiture/5.webp', 2, 5, NULL),
('volkswagen_beetle_gallery', '../images_voiture/5_1.webp', 1, 5, NULL),
('volkswagen_beetle_gallery', '../images_voiture/5_2.webp', 1, 5, NULL),
('volkswagen_beetle_gallery', '../images_voiture/5_3.webp', 1, 5, NULL),
('repair.png', '../images_voiture/repair.png', 3, NULL, 2),
('car.png', '../images_voiture/car.png', 3, NULL, 4),
('hours.png', '../images_voiture/hours.png', 3, 3, 3),
('car-repair.png', '../images_voiture/car-repair.png', 3, NULL, 1),
('car test.webp', '../images_voiture/car test.webp', 2, NULL, NULL),
('car2.jpeg', '../images_voiture/car2.jpeg', 1, NULL, NULL),
('car3.jpeg', '../images_voiture/car3.jpeg', 1, NULL, NULL);

INSERT INTO `image_types` (`name`) VALUES
('Gallery'),
('Main_img'),
('icon'),
('undefined');

INSERT INTO `info_types` (`name`) VALUES
('Services'),
('Address'),
('Contact'),
('Hours'),
('About_us'),
('Services'),
('Reasons');


DELETE FROM `info_types` WHERE `id_info_type` = 6;

INSERT INTO `messages` (`client_first_name`, `client_last_name`, `client_email`, `client_phone`, `message`, `created`, `modified`, `modified_by`, `status`, `subject`) VALUES
('Jean', 'Dupont', 'jean.dupont@email.com', '+33 6 12 34 56 78', 'Bonjour, je suis intéressé par votre annonce pour la voiture d\'occasion. Pouvez-vous me fournir plus d\'informations sur le véhicule ?', '2023-10-05 00:00:00', '2023-10-18 12:38:24', 1, 6, 'Demande d\'informations sur une voiture d\'occasion'),
('Marie', 'Martin', 'marie.martin@email.com', '+33 6 98 76 54 32', 'Bonjour, je souhaiterais prendre rendez-vous pour une réparation de ma voiture. Pouvez-vous me donner vos disponibilités ?', '2023-09-13 00:00:00', '2023-10-20 10:43:15', 1, 6, 'Prise de rendez-vous pour réparation'),
('Pierre', 'Lambert', 'pierre.lambert@email.com', '+33 7 11 22 33 44', 'Bonjour, j\'aimerais vendre ma voiture d\'occasion. Pourriez-vous me donner des détails sur le processus de reprise et l\'estimation de la valeur de ma voiture ?', '2023-10-05 00:00:00', '2023-10-31 10:07:42', 1, 6, 'Vente de ma voiture d\'occasion'),
('Sophie', 'Dubois', 'sophie.dubois@email.com', '+33 6 55 44 33 22', 'Bonjour, je recherche une voiture d\'occasion avec faible kilométrage et bon état. Avez-vous des véhicules correspondant à mes critères ?', '2023-08-14 00:00:00', '2023-10-20 16:40:20', 1, 6, 'Recherche de voiture d\'occasion'),
('Thomas', 'Renaud', 'thomas.renaud@email.com', '+33 6 77 88 99 00', 'Bonjour, ma voiture a besoin d\'une révision. Pouvez-vous me fournir un devis pour l\'entretien ?', '2023-10-05 00:00:00', '2023-11-02 11:37:40', 1, 8, 'Devis pour l\'entretien de ma voiture'),
('Jean', 'Dupont', 'jean.dupont@email.com', '+33 6 12 34 56 78', 'Bonjour, je suis intéressé par votre annonce pour la voiture d\'occasion. Pouvez-vous me fournir plus d\'informations sur le véhicule ?', '2023-10-05 09:51:08', '2023-11-02 11:37:47', 1, 8, 'Demande d\'informations sur une voiture d\'occasion'),
('Marie', 'Martin', 'marie.martin@email.com', '+33 6 98 76 54 32', 'Bonjour, je souhaiterais prendre rendez-vous pour une réparation de ma voiture. Pouvez-vous me donner vos disponibilités ?', '2023-10-05 09:51:08', '2023-11-01 16:25:17', 1, 6, 'Prise de rendez-vous pour réparation'),
('Pierre', 'Lambert', 'pierre.lambert@email.com', '+33 7 11 22 33 44', 'Bonjour, j\'aimerais vendre ma voiture d\'occasion. Pourriez-vous me donner des détails sur le processus de reprise et l\'estimation de la valeur de ma voiture ?', '2023-10-05 09:51:08', '2023-10-20 22:13:55', 1, 6, 'Vente de ma voiture d\'occasion'),
('Sophie', 'Dubois', 'sophie.dubois@email.com', '+33 6 55 44 33 22', 'Bonjour, je recherche une voiture d\'occasion avec faible kilométrage et bon état. Avez-vous des véhicules correspondant à mes critères ?', '2023-10-05 09:51:08', '2023-10-10 13:14:53', NULL, 8, 'Recherche de voiture d\'occasion'),
('Thomas', 'Renaud', 'thomas.renaud@email.com', '+33 6 77 88 99 00', 'Bonjour, ma voiture a besoin d\'une révision. Pouvez-vous me fournir un devis pour l\'entretien ?', '2023-10-05 09:51:08', NULL, NULL, 2, 'Devis pour l\'entretien de ma voiture'),
('Sebastien', 'sss', 'seb7394@hotmail.fr', '0670565572', 'sss', '2023-10-22 22:08:49', '2023-11-02 12:23:33', 1, 6, 'standard message');

INSERT INTO `models` (`brand`, `name`) VALUES
(1, 'Octavia'),
(2, 'Kuga'),
(3, 'Jumper'),
(4, 'A6 Allroad'),
(5, 'Beetle'),
(4, 'test');


INSERT INTO `properties_meta` (`name`, `value`) VALUES
('Caroserie', 'Combi'),
('Caroserie', 'SUV'),
('Caroserie', 'Van'),
('Caroserie', 'coupe'),
('Transmission', 'Manuelle - 5 vitesses'),
('Transmission', 'Manuelle - 6 vitesses'),
('Transmission', 'Automatique - 7 vitesses'),
('Carbourant', 'Diesel'),
('Carbourant', 'Essence'),
('Carbourant', 'Hybrid'),
('Carbourant', 'Electrique'),
('Couleur', 'Blanc/#ffffff'),
('Couleur', 'Rouge-métalique/#ff0000'),
('Couleur', 'Noir-métalique/#000000'),
('Couleur', 'Noir/#000000'),
('Portes', '5'),
('Portes', '4'),
('Portes', '3'),
('Options', 'Climatisation Manuelle, Climatisation Automatique, Régulateur Vitesse, Capteurs de stationnement, Caméra de recul, Connectivité Smartphone, Sièges chauffants, Détecteur de pluie, Système d\'Assistance à la Conduite ABS, Navigation GPS');

INSERT INTO `statuses` (`name`, `description`) VALUES
('Active', 'vehicles,feedbacks,users'),
('New', 'vehicles,feedbacks,messages'),
('Pending', 'vehicles'),
('Reserved', 'vehicles'),
('Sold', 'vehicles'),
('Archived', 'vehicles,feedbacks,messages'),
('Done', 'messages');


INSERT INTO `users` (`last_name`, `first_name`, `email`, `password`, `active_since`, `role`, `status`) VALUES
('Parrot', 'Jean', 'parrot@parrot.fr', '$2y$12$7asORAKeO.qLVu7fu8O8ge.49h6WIBHbZbBu/C.P6RvcR8amUPnHy', '2023-10-01 00:00:00', 1, 2),
('Lanneree', 'Seb', 'seb@gmail.com', '$2y$12$k/rB8Flc05U.lReh57S/gerz7/SoIEKAOoi4OXdtGrrhBtR8jtK2m', '2023-10-31 10:25:06', 1, 1);

INSERT INTO `user_roles` (`name`, `description`) VALUES
('Admin', 'all rights.......'),
('Employee', 'messages, feedback, cars');



INSERT INTO `vehicles` (`brand`, `model`, `status`, `year`, `km`, `price`, `conformity`, `consumption`, `other_equipment`, `created`, `modified`, `modified_by`) VALUES
(1, 1, 6, 2014, 221408, 9394, 'EURO5', '1.6 TDI, 77kW', 'Autorádio,Centrální zamykání,Elektrická okna,Kožený volant,Loketní opěrka,Multifunkční volant,Palubní počítač,Tónovaná skla,Ukotvení pro dětské sedačky', NULL, '2023-10-31 20:34:08', 1),
(2, 2, 6, 2020, 8560, 680003, 'EURO6, 10/2026', '1.5 EcoBoost, 110kW', 'Autorádio,Bezklíčkové startování,Centrální zamykání,Elektrická okna,Elektrická parkovací brzda,Kožený volant,Multifunkční volant,Palubní počítač,Posilovač řízení,Stop Start system,Tónovaná skla,Ukotvení pro dětské sedačky,Volba jízdního režimu,Vyhřívané čelní sklo,Vyhřívaný volant', '2023-10-01 21:51:06', '2023-10-31 10:10:24', 1),
(3, 3, 1, 2020, 198591, 13600, 'EURO6, 06/2025', '2.0 BlueHDi, 120kW', 'Autorádio,Centrální zamykání,Elektrická okna,Multifunkční volant,Palubní počítač,Posilovač řízení', NULL, '2023-10-31 21:48:41', NULL),
(4, 4, 1, 2018, 79304, 25200, 'EURO6', '3.0 TDI, 160kW, 4x4', 'Adaptivní tempomat,Autorádio,Bezklíčkové startování,Centrální zamykání,Elektrická okna,Elektrická parkovací brzda,Elektrické dovírání dveří,Kožený volant,Multifunkční volant,Nezávislé topení,Palubní počítač,Polokožené sedačky,Posilovač řízení,Stop Start system,Tónovaná skla,Ukotvení pro dětské sedačky,Vyhřívané sedačky', NULL, '2023-10-20 22:20:15', 1),
(5, 5, 6, 2015, 232532, 5000, 'EURO5, 08/2025', '1.6 TDI, 77kW', 'Autorádio,CD měnič,Centrální zamykání,Elektrická okna,Kožený volant,Multifunkční volant,Palubní počítač,Posilovač řízení,Tónovaná skla,Ukotvení pro dětské sedačky', NULL, '2023-10-31 21:14:53', 1);

INSERT INTO `vehicle_properties` (`vehicle`, `property_name`, `property`) VALUES
(1, 'Caroserie', '1'),
(2, 'Transmission', '5'),
(3, 'Carbourant', '8'),
(4, 'Couleur', '12'),
(5, 'Portes', '16'),
(6, 'Options', 'Climatisation Automatique, Régulateur Vitesse, Capteurs de stationnement, Connectivité Smartphone, Détecteur de pluie, ABS, Navigation GPS'),
(2, 'Caroserie', '2'),
(2, 'Transmission', '6'),
(2, 'Carbourant', '9'),
(2, 'Couleur', '13'),
(2, 'Portes', '16'),
(2, 'Options', 'Climatisation Automatique, Régulateur Vitesse, Capteurs de stationnement, Connectivité Smartphone, Détecteur de pluie, Système d\'Assistance à la Conduite, ABS, Navigation GPS'),
(3, 'Caroserie', '3'),
(3, 'Transmission', '5'),
(3, 'Carbourant', '9'),
(3, 'Couleur', '13'),
(3, 'Portes', '17'),
(3, 'Options', 'Climatisation Manuelle, Régulateur Vitesse, Connectivité Smartphone, ABS'),
(4, 'Caroserie', '1'),
(4, 'Transmission', '7'),
(4, 'Carbourant', '8'),
(4, 'Couleur', '14'),
(4, 'Portes', '16'),
(4, 'Options', 'Climatisation Automatique, Régulateur Vitesse, Capteurs de stationnement, Caméra de recul, Connectivité Smartphone, Sieges chauffants, Détecteur de pluie, Système d\'Assistance à la Conduite, ABS, Navigation GPS'),
(5, 'Caroserie', '3'),
(5, 'Transmission', '5'),
(5, 'Carbourant', '8'),
(5, 'Couleur', '15'),
(5, 'Portes', '18'),
(5, 'Options', 'Climatisation Automatique, Régulateur Vitesse, Capteurs de stationnement, Connectivité Smartphone, Sieges chauffants, Détecteur de pluie, Système d\'Assistance à la Conduite, ABS');


INSERT INTO `web_page_info` (`type`, `order`, `text`, `category`) VALUES
(1, 2, 'Réparation de la carrosserie', 'heading'),
(1, 1, 'Réparation de la mécanique', 'heading'),
(1, 3, 'entretien régulier', 'heading'),
(1, 4, 'Vente des véhicules', 'heading'),
(2, 1, '33 Rue Smith modified', 'street'),
(2, 2, 'Lyon', 'city'),
(2, 3, '69002', 'postal_code'),
(3, 5, '+33612345678', 'phone'),
(3, 2, 'info@parrot.fr', 'email'),
(4, 1, 'Lundi : 9h30 - 17h30', NULL),
(4, 2, 'Mardi : 9h00 - 17h00', NULL),
(4, 3, 'Mercredi : 9h00 - 17h00', NULL),
(4, 4, 'Jeudi : 9h00 - 17h00', NULL),
(4, 5, 'Vendredi : 9h00 - 18h00', NULL),
(4, 6, 'Samedi : 10h00 - 14h00', NULL),
(4, 7, 'Dimanche : Fermé', NULL),
(1, 1, 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.', 'text'),
(1, 2, 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.', 'text'),
(5, 1, 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.', NULL),
(7, 1, 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.', 'text');(7, 1, 'reason one modified', 'heading'),
(7, 2, 'reason two', 'heading'),
(7, 3, 'reason three', 'heading'),
(7, 4, 'reason four', 'heading'),
(1, 3, 'ůkluzfdgchvjiouztdfgkjhuztr', 'text'),
(1, 4, 'ljkhuzftdgcvhjklhgfd', 'text');
