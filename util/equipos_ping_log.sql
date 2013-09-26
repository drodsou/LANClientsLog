delimiter $$

CREATE TABLE `equipos_ping_log` (
  `ip` varchar(15) DEFAULT NULL,
  `host` varchar(50) DEFAULT NULL,
  `mac` varchar(45) DEFAULT NULL,
  `marca` varchar(45) DEFAULT NULL,
  `fecha` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='Log de pings a maquinas de la red'$$
