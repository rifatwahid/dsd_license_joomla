START TRANSACTION;
/*
CREATE TABLE IF NOT EXISTS `#__jshopping_content` (
  `id` int(11) NOT NULL,
  `lang` text NOT NULL,
  `content` text NOT NULL,
  `link` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;  

ALTER TABLE `#__jshopping_content`
  ADD PRIMARY KEY (`id`);
ALTER TABLE `#__jshopping_content`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;
*/

CREATE TABLE IF NOT EXISTS `#__jshopping_content` (
  `id` SERIAL NOT NULL,
  `lang` text NOT NULL,
  `content` text NOT NULL,
  `link` text NOT NULL,
  PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
COMMIT;