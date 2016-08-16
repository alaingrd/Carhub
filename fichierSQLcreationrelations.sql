ALTER TABLE `trajets` DROP FOREIGN KEY `premContr`;
ALTER TABLE `trajets` ADD CONSTRAINT `premContr` FOREIGN KEY (`ID_conducteur`) REFERENCES `carhub`.`membres`(`ID`) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `messagerie` ADD CONSTRAINT `deuxContr` FOREIGN KEY (`ID_conversation`) REFERENCES `carhub`.`conversations`(`ID_conversation`) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `reservations` ADD CONSTRAINT `quatAContr` FOREIGN KEY (`ID_conducteur`) REFERENCES `carhub`.`membres`(`ID`) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE `reservations` ADD CONSTRAINT `quatBContr` FOREIGN KEY (`ID_passager`) REFERENCES `carhub`.`membres`(`ID`) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE `reservations` ADD  CONSTRAINT `quatCContr` FOREIGN KEY (`ID_trajet`) REFERENCES `carhub`.`trajets`(`ID`) ON DELETE CASCADE ON UPDATE CASCADE;


ALTER TABLE `notifications` ADD CONSTRAINT `cinqContr` FOREIGN KEY (`ID_concerne`) REFERENCES `carhub`.`membres`(`ID`) ON DELETE CASCADE ON UPDATE CASCADE;
