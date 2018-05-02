CREATE DATABASE `coreelec`;

CREATE USER 'coreelec'@'localhost' IDENTIFIED BY 'somerandompassw0rd';

GRANT SELECT, INSERT, UPDATE ON `coreelec`.* TO 'coreelec'@'localhost';

USE `coreelec`;

CREATE TABLE `coreelec` (
  `id` int(11) NOT NULL,
  `system` varchar(32) NOT NULL,
  `arch` varchar(255) NOT NULL,
  `version` varchar(15) NOT NULL,
  `unixtime` varchar(10) NOT NULL,
  `country` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

ALTER TABLE `coreelec`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `system` (`system`);

ALTER TABLE `coreelec`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
