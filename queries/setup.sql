--
-- Counters - Setup (1.0.0)
--
-- @package Coordinator\Modules\Counters
-- @author  Manuel Zavatta <manuel.zavatta@gmail.com>
-- @link    http://www.coordinator.it
--

-- --------------------------------------------------------

SET FOREIGN_KEY_CHECKS = 0;
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";

-- --------------------------------------------------------

--
-- Table structure for table `counters__counters`
--

CREATE TABLE IF NOT EXISTS `counters__counters` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `deleted` tinyint(1) UNSIGNED NOT NULL DEFAULT '0',
  `name` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
  `description` text COLLATE utf8_unicode_ci,
  `identifier` varchar(32) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `counters__counters__measurements`
--

CREATE TABLE IF NOT EXISTS `counters__counters__measurements` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `deleted` tinyint(1) UNSIGNED NOT NULL DEFAULT '0',
  `fkCounter` int(11) UNSIGNED NOT NULL,
  `period` int(11) UNSIGNED NOT NULL,
  `current` double UNSIGNED NOT NULL,
  `previous` double UNSIGNED NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fkCounter` (`fkCounter`),
  CONSTRAINT `counters__counters__measurements_ibfk_1` FOREIGN KEY (`fkCounter`) REFERENCES `counters__counters` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `counters__counters__logs`
--

CREATE TABLE IF NOT EXISTS `counters__counters__logs` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `fkObject` int(11) UNSIGNED NOT NULL,
  `fkUser` int(11) UNSIGNED DEFAULT NULL,
  `timestamp` int(11) UNSIGNED NOT NULL,
  `alert` tinyint(1) UNSIGNED NOT NULL DEFAULT '0',
  `event` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
  `properties_json` text COLLATE utf8_unicode_ci,
  PRIMARY KEY (`id`),
  KEY `fkObject` (`fkObject`),
  CONSTRAINT `counters__counters__logs_ibfk_1` FOREIGN KEY (`fkObject`) REFERENCES `counters__counters` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Authorizations
--

INSERT IGNORE INTO `framework__modules__authorizations` (`id`,`fkModule`,`order`) VALUES
('counters-manage','counters',1),
('counters-usage','counters',2);

-- --------------------------------------------------------

SET FOREIGN_KEY_CHECKS = 1;

-- --------------------------------------------------------
