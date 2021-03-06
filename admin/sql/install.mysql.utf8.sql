CREATE TABLE IF NOT EXISTS  `#__bfdownloadmanager` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `asset_id` int(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT 'FK to the #__assets table.',
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `alias` varchar(400) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL DEFAULT '',
  `introtext` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `fulltext` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `downloadfile_name` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `downloadfile_size` int(11) NOT NULL,
  `downloadfile` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `state` tinyint(3) NOT NULL DEFAULT '0',
  `catid` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `created` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `created_by` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `created_by_alias` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `modified` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modified_by` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `checked_out` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `checked_out_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `publish_up` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `publish_down` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `images` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `urls` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `attribs` varchar(5120) COLLATE utf8mb4_unicode_ci NOT NULL,
  `version` int(10) UNSIGNED NOT NULL DEFAULT '1',
  `ordering` int(11) NOT NULL DEFAULT '0',
  `metakey` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `metadesc` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `access` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `hits` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `metadata` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `featured` tinyint(3) UNSIGNED NOT NULL DEFAULT '0' COMMENT 'Set if article is featured.',
  `language` char(7) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'The language code for the article.',
  `xreference` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `idx_access` (`access`),
  KEY `idx_checkout` (`checked_out`),
  KEY `idx_state` (`state`),
  KEY `idx_catid` (`catid`),
  KEY `idx_createdby` (`created_by`),
  KEY `idx_featured_catid` (`featured`,`catid`),
  KEY `idx_language` (`language`),
  KEY `idx_xreference` (`xreference`),
  KEY `alias` (`alias`(191)),
  KEY `alias_2` (`alias`(191)),
  KEY `alias_3` (`alias`(191)),
  KEY `alias_4` (`alias`(191)),
  KEY `alias_5` (`alias`(191)),
  KEY `idx_alias` (`alias`(191))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `#__bfdownloadmanager_frontpage` (
  `bfdownloadmanager_id` int(11) NOT NULL DEFAULT '0',
  `ordering` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`bfdownloadmanager_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `#__bfdownloadmanager_rating` (
  `bfdownloadmanager_id` int(11) NOT NULL DEFAULT '0',
  `rating_sum` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `rating_count` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `lastip` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  PRIMARY KEY (`bfdownloadmanager_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `#__bfdownloadmanager_tag_map` (
  `tag_alias` varchar(255) NOT NULL DEFAULT '',
  `core_bfdownloadmanager_id` integer NOT NULL,
  `bfdownloadmanager_item_id` integer NOT NULL,
  `tag_id` integer NOT NULL,
  `tag_date` timestamp NOT NULL,
 UNIQUE KEY `uc_ItemnameTagid` (`tag_alias`, `bfdownloadmanager_item_id`, `tag_id`),
 KEY `idx_tag_alias` (`tag_id`, `tag_alias`),
 KEY `idx_tag_date` (`tag_date`, `tag_id`),
 KEY `idx_tag_id` (`tag_id`),
 KEY `idx_core_bfdownloadmanager_id` (`core_bfdownloadmanager_id`)
);