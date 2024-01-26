-- Adminer 4.8.1 MySQL 5.5.5-10.3.11-MariaDB-1:10.3.11+maria~bionic dump

SET NAMES utf8;
SET time_zone = '+00:00';
SET foreign_key_checks = 0;
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `email` varchar(128) NOT NULL,
  `password` varchar(256) NOT NULL,
  `active` tinyint(4) NOT NULL DEFAULT 0,
  `activation_token` varchar(64) DEFAULT NULL,
  `activation_token_expiration_date` timestamp NULL DEFAULT NULL,
  `refresh_token` varchar(64) DEFAULT NULL,
  `refresh_token_expiration_date` timestamp NULL DEFAULT NULL,
  `reset_passwd_token` varchar(64) DEFAULT NULL,
  `reset_passwd_token_expiration_date` timestamp NULL DEFAULT NULL,
  `username` varchar(64) DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Adminer 4.8.1 MySQL 5.5.5-10.3.11-MariaDB-1:10.3.11+maria~bionic dump

SET NAMES utf8;
SET time_zone = '+00:00';
SET foreign_key_checks = 0;
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

INSERT INTO `users` (`email`, `password`, `active`, `activation_token`, `activation_token_expiration_date`, `refresh_token`, `refresh_token_expiration_date`, `reset_passwd_token`, `reset_passwd_token_expiration_date`, `username`) VALUES
                                                                                                                                                                                                                                          ('lbo@gmail.com',	'$2y$10$p4rlc0LqkKNK.dKRX/v1W.KObQbiYhMlxfdps6Ilj/0O5B2D35bbi',	1,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	'lbo'),
                                                                                                                                                                                                                                          ('test@mail.com',	'$2y$10$G.OiI1dTQsINniK9QRmsb.YgBV9x6S6.rsrCzm2j2YOpSQi0KJ.1m',	1,	NULL,	NULL,	'010036d27ef02f990b27430e61046a475ea67ddc06999c72556fab778e12d32f',	'2025-01-23 18:23:38',	NULL,	NULL,	'username');

-- 2023-10-03 13:52:23

-- 2023-10-03 13:52:01
