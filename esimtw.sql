SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;


CREATE TABLE `account` (
  `id` int(11) NOT NULL,
  `username` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(192) COLLATE utf8_unicode_ci NOT NULL,
  `web_login` tinyint(1) NOT NULL,
  `web_group` smallint(6) NOT NULL,
  `web_admin` int(1) NOT NULL,
  `last_login_ip` varchar(15) COLLATE utf8_unicode_ci NOT NULL,
  `last_login_time_unix` varchar(11) COLLATE utf8_unicode_ci NOT NULL,
  `register_ip` varchar(15) COLLATE utf8_unicode_ci NOT NULL,
  `register_time_unix` varchar(11) COLLATE utf8_unicode_ci NOT NULL,
  `last_update_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE `auction_data` (
  `id` int(11) NOT NULL,
  `action_id` int(11) NOT NULL,
  `type` varchar(16) COLLATE utf8_unicode_ci NOT NULL,
  `seller_id` int(11) NOT NULL,
  `seller_name` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
  `bidder_id` int(11) DEFAULT NULL,
  `bidder_name` varchar(32) COLLATE utf8_unicode_ci DEFAULT NULL,
  `item_id` int(11) DEFAULT NULL,
  `item_quality` varchar(2) COLLATE utf8_unicode_ci DEFAULT NULL,
  `item_slot` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
  `item_property_1` varchar(32) COLLATE utf8_unicode_ci DEFAULT NULL,
  `item_property_1_quality` varchar(8) COLLATE utf8_unicode_ci DEFAULT NULL,
  `item_property_2` varchar(32) COLLATE utf8_unicode_ci DEFAULT NULL,
  `item_property_2_quality` varchar(8) COLLATE utf8_unicode_ci DEFAULT NULL,
  `item_price` varchar(8) COLLATE utf8_unicode_ci NOT NULL,
  `bidders` tinyint(4) NOT NULL,
  `time` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE `battles` (
  `id` int(10) UNSIGNED NOT NULL,
  `server` tinyint(4) NOT NULL,
  `battle_id` mediumint(8) UNSIGNED NOT NULL,
  `round` tinyint(3) UNSIGNED NOT NULL,
  `damage` mediumint(8) UNSIGNED NOT NULL,
  `weapon` tinyint(3) UNSIGNED NOT NULL,
  `berserk` tinyint(1) NOT NULL,
  `defender_side` tinyint(1) NOT NULL,
  `citizen_id` mediumint(8) UNSIGNED NOT NULL,
  `citizenship` tinyint(3) UNSIGNED NOT NULL,
  `military_unit` smallint(5) UNSIGNED NOT NULL,
  `time` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE `battle_list` (
  `id` int(10) UNSIGNED NOT NULL,
  `server` tinyint(4) NOT NULL,
  `battle_id` mediumint(8) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE `citizens` (
  `id` int(10) NOT NULL,
  `server` tinyint(4) NOT NULL,
  `name` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
  `citizen_id` mediumint(8) UNSIGNED NOT NULL,
  `organization` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE `game_citizen_name` (
  `id` int(11) NOT NULL,
  `citizen_name` varchar(33) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE `information` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `value` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE `ip_banned_list` (
  `id` int(11) NOT NULL,
  `ip` varchar(15) NOT NULL,
  `reason` varchar(32) NOT NULL,
  `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `time_unix` varchar(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `queue` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `payload` text COLLATE utf8_unicode_ci NOT NULL,
  `attempts` tinyint(3) UNSIGNED NOT NULL,
  `reserved` tinyint(3) UNSIGNED NOT NULL,
  `reserved_at` int(10) UNSIGNED DEFAULT NULL,
  `available_at` int(10) UNSIGNED NOT NULL,
  `created_at` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE `migrations` (
  `migration` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE `news_data` (
  `id` int(11) NOT NULL,
  `news_title` varchar(768) COLLATE utf8_unicode_ci NOT NULL,
  `news_group` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `news_author` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
  `enable` tinyint(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE `tools_battle_info_chat` (
  `id` int(11) NOT NULL,
  `hash` varchar(40) COLLATE utf8_unicode_ci NOT NULL,
  `cid` int(11) NOT NULL,
  `citizen_name` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
  `chat_content` varchar(512) COLLATE utf8_unicode_ci NOT NULL,
  `ip` varchar(15) COLLATE utf8_unicode_ci NOT NULL,
  `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `time_unix` varchar(11) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE `tools_bidding_detail` (
  `id` int(11) NOT NULL,
  `bidding_id` int(11) NOT NULL,
  `bidder_cid` int(11) NOT NULL,
  `bidder_citizen_name` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
  `bidder_citizen_link` varchar(7) COLLATE utf8_unicode_ci NOT NULL,
  `bidding_price` int(11) NOT NULL,
  `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `ip` varchar(15) COLLATE utf8_unicode_ci NOT NULL,
  `time_unix` varchar(11) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE `tools_bidding_list` (
  `id` int(11) NOT NULL,
  `originator_cid` int(11) NOT NULL,
  `originator_citizen_name` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
  `bidding_title` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
  `bidding_content` varchar(256) COLLATE utf8_unicode_ci NOT NULL,
  `bidding_end_time` varchar(11) COLLATE utf8_unicode_ci NOT NULL,
  `ip` varchar(15) COLLATE utf8_unicode_ci NOT NULL,
  `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE `user_data` (
  `id` int(11) NOT NULL,
  `cid` int(11) NOT NULL,
  `citizen_name` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
  `citizen_link` varchar(7) COLLATE utf8_unicode_ci NOT NULL,
  `citizen_country_id` varchar(3) COLLATE utf8_unicode_ci NOT NULL,
  `citizen_age` int(11) NOT NULL,
  `citizen_level` int(11) NOT NULL,
  `citizen_experience` int(11) NOT NULL,
  `citizen_strength` int(11) NOT NULL,
  `citizen_economy_skill` varchar(16) COLLATE utf8_unicode_ci NOT NULL,
  `citizen_rank_name` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
  `citizen_rank_damage` varchar(16) COLLATE utf8_unicode_ci NOT NULL,
  `citizen_organization` tinyint(1) NOT NULL,
  `citizen_alive` tinyint(1) NOT NULL,
  `citizen_ban` tinyint(1) NOT NULL,
  `citizen_verify` tinyint(1) NOT NULL,
  `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `time_unix` varchar(11) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE `web_announcement` (
  `id` int(11) NOT NULL,
  `type` int(11) NOT NULL,
  `content` varchar(256) COLLATE utf8_unicode_ci NOT NULL,
  `display` tinyint(1) NOT NULL,
  `ip` varchar(15) COLLATE utf8_unicode_ci NOT NULL,
  `time_unix` varchar(11) COLLATE utf8_unicode_ci NOT NULL,
  `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE `web_login_log` (
  `id` int(11) NOT NULL,
  `cid` int(3) NOT NULL,
  `ip` varchar(15) NOT NULL,
  `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `time_unix` varchar(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `web_login_remember` (
  `id` int(11) NOT NULL,
  `cid` int(11) NOT NULL,
  `username` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
  `hash` varchar(42) COLLATE utf8_unicode_ci NOT NULL,
  `time_from` varchar(11) COLLATE utf8_unicode_ci NOT NULL,
  `time_to` varchar(11) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


ALTER TABLE `account`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `auction_data`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `battles`
  ADD PRIMARY KEY (`id`),
  ADD KEY `battles_server_index` (`server`),
  ADD KEY `battles_battle_id_index` (`battle_id`),
  ADD KEY `battles_round_index` (`round`);

ALTER TABLE `battle_list`
  ADD PRIMARY KEY (`id`),
  ADD KEY `battle_list_server_index` (`server`),
  ADD KEY `battle_list_battle_id_index` (`battle_id`);

ALTER TABLE `citizens`
  ADD PRIMARY KEY (`id`),
  ADD KEY `server` (`server`,`citizen_id`),
  ADD KEY `citizen_id` (`citizen_id`),
  ADD KEY `server_2` (`server`);

ALTER TABLE `game_citizen_name`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `information`
  ADD PRIMARY KEY (`id`),
  ADD KEY `name` (`name`);

ALTER TABLE `ip_banned_list`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `news_data`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `tools_battle_info_chat`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `tools_bidding_detail`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `tools_bidding_list`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `user_data`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `web_announcement`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `web_login_log`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `web_login_remember`
  ADD PRIMARY KEY (`id`);


ALTER TABLE `account`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `auction_data`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `battles`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
ALTER TABLE `battle_list`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
ALTER TABLE `citizens`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;
ALTER TABLE `game_citizen_name`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `information`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
ALTER TABLE `ip_banned_list`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
ALTER TABLE `news_data`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `tools_battle_info_chat`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `tools_bidding_detail`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `tools_bidding_list`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `user_data`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `web_announcement`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `web_login_log`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `web_login_remember`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
