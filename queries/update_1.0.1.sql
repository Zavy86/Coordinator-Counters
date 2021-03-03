--
-- Counters - Update (1.0.1)
--
-- @package Coordinator\Modules\Counters
-- @author  Manuel Zavatta <manuel.zavatta@gmail.com>
-- @link    http://www.coordinator.it
--

-- --------------------------------------------------------

SET FOREIGN_KEY_CHECKS = 0;
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";

-- --------------------------------------------------------

ALTER TABLE `counters__counters` ADD `order` int(11) UNSIGNED NOT NULL AFTER `deleted`;

-- --------------------------------------------------------

SET @pos := 0;
UPDATE `counters__counters` SET `deleted` = ( SELECT @pos := @pos + 1 ) ORDER BY id ASC;

-- --------------------------------------------------------

SET FOREIGN_KEY_CHECKS = 1;

-- --------------------------------------------------------
