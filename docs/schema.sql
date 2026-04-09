-- ============================================================
-- Preeti Amble Blog — MySQL Schema
-- Run this in phpMyAdmin via cPanel
-- ============================================================

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ------------------------------------------------------------
-- Table: categories
-- ------------------------------------------------------------
CREATE TABLE `categories` (
    `id`         INT UNSIGNED    NOT NULL AUTO_INCREMENT,
    `name`       VARCHAR(100)    NOT NULL,
    `slug`       VARCHAR(110)    NOT NULL,
    `created_at` TIMESTAMP       NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    UNIQUE KEY `uq_categories_slug` (`slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Seed categories from existing data
INSERT INTO `categories` (`name`, `slug`) VALUES
    ('Mindfulness', 'mindfulness'),
    ('Stories',     'stories'),
    ('Lifestyle',   'lifestyle');

-- ------------------------------------------------------------
-- Table: posts
-- ------------------------------------------------------------
CREATE TABLE `posts` (
    `id`           INT UNSIGNED    NOT NULL AUTO_INCREMENT,
    `title`        VARCHAR(500)    NOT NULL,
    `slug`         VARCHAR(600)    NOT NULL,
    `excerpt`      LONGTEXT        NOT NULL COMMENT 'Block editor JSON string',
    `content`      LONGTEXT        NOT NULL COMMENT 'Block editor JSON string',
    `cover_image`  VARCHAR(2048)   NOT NULL DEFAULT '',
    `category_id`  INT UNSIGNED    NOT NULL,
    `reading_time` TINYINT UNSIGNED NOT NULL DEFAULT 1 COMMENT 'Minutes',
    `featured`     TINYINT(1)      NOT NULL DEFAULT 0,
    `status`       ENUM('draft','published') NOT NULL DEFAULT 'draft',
    `views`        INT UNSIGNED    NOT NULL DEFAULT 0,
    `published_at` TIMESTAMP       NULL DEFAULT NULL,
    `created_at`   TIMESTAMP       NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at`   TIMESTAMP       NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    UNIQUE KEY `uq_posts_slug` (`slug`),
    KEY `idx_posts_status`      (`status`),
    KEY `idx_posts_category`    (`category_id`),
    KEY `idx_posts_published`   (`published_at`),
    KEY `idx_posts_featured`    (`featured`),
    CONSTRAINT `fk_posts_category`
        FOREIGN KEY (`category_id`)
        REFERENCES `categories` (`id`)
        ON UPDATE CASCADE
        ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------------------------------------
-- Table: tags
-- ------------------------------------------------------------
CREATE TABLE `tags` (
    `id`         INT UNSIGNED    NOT NULL AUTO_INCREMENT,
    `name`       VARCHAR(100)    NOT NULL,
    `slug`       VARCHAR(110)    NOT NULL,
    `created_at` TIMESTAMP       NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    UNIQUE KEY `uq_tags_slug` (`slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------------------------------------
-- Table: post_tags  (many-to-many pivot)
-- ------------------------------------------------------------
CREATE TABLE `post_tags` (
    `post_id` INT UNSIGNED NOT NULL,
    `tag_id`  INT UNSIGNED NOT NULL,
    PRIMARY KEY (`post_id`, `tag_id`),
    CONSTRAINT `fk_pt_post`
        FOREIGN KEY (`post_id`)
        REFERENCES `posts` (`id`)
        ON DELETE CASCADE,
    CONSTRAINT `fk_pt_tag`
        FOREIGN KEY (`tag_id`)
        REFERENCES `tags` (`id`)
        ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------------------------------------
-- Table: newsletter_subscribers
-- ------------------------------------------------------------
CREATE TABLE `newsletter_subscribers` (
    `id`            INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `email`         VARCHAR(320) NOT NULL,
    `subscribed_at` TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    UNIQUE KEY `uq_subscribers_email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------------------------------------
-- Table: admin_users
-- ------------------------------------------------------------
CREATE TABLE `admin_users` (
    `id`         INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `name`       VARCHAR(150) NOT NULL,
    `email`      VARCHAR(320) NOT NULL,
    `password`   VARCHAR(255) NOT NULL COMMENT 'bcrypt hash',
    `created_at` TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    UNIQUE KEY `uq_admin_email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

SET FOREIGN_KEY_CHECKS = 1;
