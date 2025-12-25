-- EventHub Database Export
-- Database: event_mgt_system
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";
-- --------------------------------------------------------
-- Table structure for table `admin`
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `admin` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `username` varchar(50) NOT NULL,
    `password` varchar(255) NOT NULL,
    `is_approved` tinyint(1) DEFAULT 0,
    PRIMARY KEY (`id`)
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4;
-- --------------------------------------------------------
-- Table structure for table `events`
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `events` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `title` varchar(100) NOT NULL,
    `description` text DEFAULT NULL,
    `location` varchar(100) DEFAULT NULL,
    `category` varchar(50) DEFAULT NULL,
    `event_date` datetime NOT NULL,
    `capacity` int(11) DEFAULT 0,
    `created_at` timestamp DEFAULT current_timestamp(),
    PRIMARY KEY (`id`)
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4;
-- --------------------------------------------------------
-- Table structure for table `registrations`
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `registrations` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `event_id` int(11) NOT NULL,
    `fullname` varchar(100) NOT NULL,
    `email` varchar(100) NOT NULL,
    `created_at` timestamp DEFAULT current_timestamp(),
    PRIMARY KEY (`id`),
    KEY `event_id` (`event_id`),
    CONSTRAINT `registrations_ibfk_1` FOREIGN KEY (`event_id`) REFERENCES `events` (`id`) ON DELETE CASCADE
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4;
COMMIT;