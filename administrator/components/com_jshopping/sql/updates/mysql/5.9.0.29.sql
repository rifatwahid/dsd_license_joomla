CREATE TABLE `#__jshopping_return_status` (
  `status_id` SERIAL ,
  `name_en-GB` varchar(100) NOT NULL DEFAULT '',
  `name_de-DE` varchar(100) NOT NULL DEFAULT '',
  `name_es-ES` varchar(100) NOT NULL DEFAULT '',
  `name_it-IT` varchar(100) NOT NULL DEFAULT '',
  `name_fr-FR` varchar(100) NOT NULL DEFAULT '',
  `name_nl-NL` varchar(100) NOT NULL DEFAULT '',
  `name_pl-PL` varchar(100) NOT NULL DEFAULT '',
  `name_ru-RU` varchar(100) NOT NULL DEFAULT '',
  `name_sv-SE` varchar(100) NOT NULL DEFAULT '',
 PRIMARY KEY (status_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `#__jshopping_return_status` (`status_id`, `name_en-GB`, `name_de-DE`, `name_es-ES`, `name_it-IT`, `name_fr-FR`, `name_nl-NL`, `name_pl-PL`, `name_ru-RU`, `name_sv-SE`) VALUES
(1, 'Accidental order', 'Accidental order', 'Accidental order', 'Accidental order', 'Accidental order', 'Accidental order', 'Accidental order', 'Accidental order', 'Accidental order'),
(2, 'Better price available', 'Better price available', 'Better price available', 'Better price available', 'Better price available', 'Better price available', 'Better price available', 'Better price available', 'Better price available'),
(3, 'The shipping box or envelope isn’t damaged, but the item is damaged', 'The shipping box or envelope isn’t damaged, but the item is damaged', 'The shipping box or envelope isn’t damaged, but the item is damaged', 'The shipping box or envelope isn’t damaged, but the item is damaged', 'The shipping box or envelope isn’t damaged, but the item is damaged', 'The shipping box or envelope isn’t damaged, but the item is damaged', 'The shipping box or envelope isn’t damaged, but the item is damaged', 'The shipping box or envelope isn’t damaged, but the item is damaged', 'The shipping box or envelope isn’t damaged, but the item is damaged'),
(4, 'Missed estimated delivery date', 'Missed estimated delivery date', 'Missed estimated delivery date', 'Missed estimated delivery date', 'Missed estimated delivery date', 'Missed estimated delivery date', 'Missed estimated delivery date', 'Missed estimated delivery date', 'Missed estimated delivery date'),
(5, 'Missing parts or accessories', 'Missing parts or accessories', 'Missing parts or accessories', 'Missing parts or accessories', 'Missing parts or accessories', 'Missing parts or accessories', 'Missing parts or accessories', 'Missing parts or accessories', 'Missing parts or accessories'),
(6, 'The shipping box or envelope and item are both damaged', 'The shipping box or envelope and item are both damaged', 'The shipping box or envelope and item are both damaged', 'The shipping box or envelope and item are both damaged', 'The shipping box or envelope and item are both damaged', 'The shipping box or envelope and item are both damaged', 'The shipping box or envelope and item are both damaged', 'The shipping box or envelope and item are both damaged', 'The shipping box or envelope and item are both damaged'),
(7, 'Wrong item sent', 'Wrong item sent', 'Wrong item sent', 'Wrong item sent', 'Wrong item sent', 'Wrong item sent', 'Wrong item sent', 'Wrong item sent', 'Wrong item sent'),
(8, 'Defective or does not work properly', 'Defective or does not work properly', 'Defective or does not work properly', 'Defective or does not work properly', 'Defective or does not work properly', 'Defective or does not work properly', 'Defective or does not work properly', 'Defective or does not work properly', 'Defective or does not work properly'),
(9, 'Arrived in addition to what was ordered', 'Arrived in addition to what was ordered', 'Arrived in addition to what was ordered', 'Arrived in addition to what was ordered', 'Arrived in addition to what was ordered', 'Arrived in addition to what was ordered', 'Arrived in addition to what was ordered', 'Arrived in addition to what was ordered', 'Arrived in addition to what was ordered'),
(10, 'No longer needed or wanted', 'No longer needed or wanted', 'No longer needed or wanted', 'No longer needed or wanted', 'No longer needed or wanted', 'No longer needed or wanted', 'No longer needed or wanted', 'No longer needed or wanted', 'No longer needed or wanted'),
(11, 'Unauthorised purchase', 'Unauthorised purchase', 'Unauthorised purchase', 'Unauthorised purchase', 'Unauthorised purchase', 'Unauthorised purchase', 'Unauthorised purchase', 'Unauthorised purchase', 'Unauthorised purchase'),
(12, 'Description on the website was not accurate', 'Description on the website was not accurate', 'Description on the website was not accurate', 'Description on the website was not accurate', 'Description on the website was not accurate', 'Description on the website was not accurate', 'Description on the website was not accurate', 'Description on the website was not accurate', 'Description on the website was not accurate'),
(13, 'Damaged during delivery', 'Damaged during delivery', 'Damaged during delivery', 'Damaged during delivery', 'Damaged during delivery', 'Damaged during delivery', 'Damaged during delivery', 'Damaged during delivery', 'Damaged during delivery'),
(14, 'Performance or quality not adequate', 'Performance or quality not adequate', 'Performance or quality not adequate', 'Performance or quality not adequate', 'Performance or quality not adequate', 'Performance or quality not adequate', 'Performance or quality not adequate', 'Performance or quality not adequate', 'Performance or quality not adequate'),
(15, 'Incompatible or not useful for intended purpose', 'Incompatible or not useful for intended purpose', 'Incompatible or not useful for intended purpose', 'Incompatible or not useful for intended purpose', 'Incompatible or not useful for intended purpose', 'Incompatible or not useful for intended purpose', 'Incompatible or not useful for intended purpose', 'Incompatible or not useful for intended purpose', 'Incompatible or not useful for intended purpose'),
(16, 'Damaged due to inappropriate packaging', 'Damaged due to inappropriate packaging', 'Damaged due to inappropriate packaging', 'Damaged due to inappropriate packaging', 'Damaged due to inappropriate packaging', 'Damaged due to inappropriate packaging', 'Damaged due to inappropriate packaging', 'Damaged due to inappropriate packaging', 'Damaged due to inappropriate packaging'),
(17, 'Part not compatible with the existing system', 'Part not compatible with the existing system', 'Part not compatible with the existing system', 'Part not compatible with the existing system', 'Part not compatible with the existing system', 'Part not compatible with the existing system', 'Part not compatible with the existing system', 'Part not compatible with the existing system', 'Part not compatible with the existing system'),
(18, 'Excessive installation or did not install', 'Excessive installation or did not install', 'Excessive installation or did not install', 'Excessive installation or did not install', 'Excessive installation or did not install', 'Excessive installation or did not install', 'Excessive installation or did not install', 'Excessive installation or did not install', 'Excessive installation or did not install'),
(19, 'No reason given', 'No reason given', 'No reason given', 'No reason given', 'No reason given', 'No reason given', 'No reason given', 'No reason given', 'No reason given');
