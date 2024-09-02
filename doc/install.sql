CREATE TABLE `lhc_mbird_channel` (
                                           `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
                                           `channel_id` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
                                           `name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
                                           `dep_id` bigint(20) unsigned NOT NULL,
                                           `created_at` bigint(20) unsigned NOT NULL,
                                           `updated_at` bigint(20) unsigned NOT NULL,
                                           PRIMARY KEY (`id`),
                                           KEY `channel_id` (`channel_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;