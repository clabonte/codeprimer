-- Create the schema if it does not already exist
CREATE DATABASE IF NOT EXISTS code_primer_tests_functional_test
    DEFAULT CHARACTER SET utf8mb4
    DEFAULT COLLATE utf8mb4_unicode_ci;

USE code_primer_tests_functional_test;

-- Creates a table to hold UserStatus entries
CREATE TABLE IF NOT EXISTS `user_statuses` (
  `name` VARCHAR(255) NOT NULL COMMENT 'The name of the status',
  `description` VARCHAR(255) NOT NULL COMMENT 'A description of what this status means',
  `login_allowed` TINYINT(1) NOT NULL COMMENT 'Whether this status allows the user to log on our application',
  PRIMARY KEY (`name`)
) ENGINE InnoDB DEFAULT CHARSET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- Insert the UserStatus elements
INSERT IGNORE INTO `user_statuses`
  (`name`, `description`, `login_allowed`)
VALUES
  ('registered', 'User is registered but has not confirmed his email address yet', TRUE),
  ('active', 'User is fully registered and allowed to user our application', TRUE),
  ('canceled', 'User has canceled his subscription with our application', FALSE),
  ('locked', 'User has been locked due to too many failed login attempts', FALSE);

-- Creates a table to hold Plan entries
CREATE TABLE IF NOT EXISTS `plans` (
  `id` BIGINT NOT NULL AUTO_INCREMENT COMMENT 'Unique ID to use for this plan',
  `name` VARCHAR(255) NOT NULL COMMENT 'The name associated with this plan, as presented to users and prospects',
  `description` VARCHAR(255) NOT NULL COMMENT 'A description of the plan, as presented to users and prospects',
  `internal` TINYINT(1) NOT NULL COMMENT 'Whether this plan can only be used internally or available for purchase',
  `active` TINYINT(1) NOT NULL COMMENT 'Whether this plan can still be used for new/upgraded accounts',
  `monthly_price` DECIMAL(12,2) NOT NULL COMMENT 'The selling price for a contract renewable on a monthly basis',
  `annual_price` DECIMAL(12,2) NOT NULL COMMENT 'The selling price for a contract renewable on a yearly basis',
  `premium_access` TINYINT(1) NOT NULL COMMENT 'Whether this plan provides access to premium content',
  `editing_access` TINYINT(1) NOT NULL COMMENT 'Whether this plan provides access to editing content',
  `admin_access` TINYINT(1) NOT NULL COMMENT 'Whether this plan provides access to admin functionality',
  PRIMARY KEY (`id`)
) ENGINE InnoDB DEFAULT CHARSET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- Insert the Plan elements
INSERT IGNORE INTO `plans`
  (`id`, `name`, `description`, `internal`, `active`, `monthly_price`, `annual_price`, `premium_access`, `editing_access`, `admin_access`)
VALUES
  (1, 'Admin', 'Internal plan used to manage the application', TRUE, TRUE, 0, 0, TRUE, TRUE, TRUE),
  (2, 'Free', 'Free plan giving access to basic functionality to registered users', FALSE, TRUE, 0, 0, FALSE, FALSE, FALSE),
  (3, 'Premium', 'Premium plan giving access to premium functionality to registered users', FALSE, TRUE, 5, 50, TRUE, FALSE, FALSE),
  (4, 'Author', 'Premium plan giving access to premium and editing functionality to registered users', FALSE, TRUE, 10, 100, TRUE, TRUE, FALSE);


-- Creates a table to hold User entries
CREATE TABLE IF NOT EXISTS `users` (
  `id` CHAR(36) NOT NULL COMMENT 'The user''s unique ID in our system' COLLATE ascii_general_ci,
  `first_name` VARCHAR(255) NULL DEFAULT NULL COMMENT 'User first name',
  `last_name` VARCHAR(255) NULL DEFAULT NULL COMMENT 'User last name',
  `nickname` VARCHAR(255) NULL DEFAULT NULL COMMENT 'The name used to identify this user publicly on the site',
  `email` VARCHAR(255) NOT NULL COMMENT 'User email address' COLLATE ascii_general_ci,
  `password` VARCHAR(255) NOT NULL COMMENT 'User password',
  `created` DATETIME NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'The date and time at which this user was created',
  `updated` DATETIME NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'The date and time at which this user was updated',
  `crm_id` VARCHAR(255) NULL DEFAULT NULL COMMENT 'The ID of this user in our external CRM',
  `activation_code` VARCHAR(255) NULL DEFAULT NULL COMMENT 'The code required to validate the user''s account',
  `stats_id` CHAR(36) NULL DEFAULT NULL COMMENT 'User login statistics' COLLATE ascii_general_ci,
  `subscription_id` CHAR(36) NULL DEFAULT NULL COMMENT 'The plan to which the user is subscribed' COLLATE ascii_general_ci,
  `status` VARCHAR(255) NULL DEFAULT NULL COMMENT 'Current status of the user',
  INDEX first_name_idx (first_name(20)) COMMENT 'To optimize search queries',
  INDEX last_name_idx (last_name(20)) COMMENT 'To optimize search queries',
  INDEX nickname_idx (nickname(20)) COMMENT 'To optimize search queries',
  INDEX email_idx (email(20)) COMMENT 'To optimize search queries',
  INDEX stats_id_idx (stats_id) COMMENT 'UserStats foreign key',
  INDEX subscription_id_idx (subscription_id) COMMENT 'Subscription foreign key',
  PRIMARY KEY (`id`)
) ENGINE InnoDB DEFAULT CHARSET utf8mb4 COLLATE utf8mb4_unicode_ci;


-- Create a table to hold User audits
CREATE TABLE IF NOT EXISTS `users_logs` (
  `audit_id` CHAR(36) NOT NULL COLLATE ascii_general_ci,
  `audit_operation` CHAR(1) NOT NULL COMMENT 'The operation performed, one of: i (insert), u (update), d (delete)' COLLATE ascii_general_ci,
  `id` CHAR(36) NOT NULL COMMENT 'The user''s unique ID in our system' COLLATE ascii_general_ci,
  `first_name` VARCHAR(255) NULL DEFAULT NULL COMMENT 'User first name',
  `last_name` VARCHAR(255) NULL DEFAULT NULL COMMENT 'User last name',
  `nickname` VARCHAR(255) NULL DEFAULT NULL COMMENT 'The name used to identify this user publicly on the site',
  `email` VARCHAR(255) NOT NULL COMMENT 'User email address' COLLATE ascii_general_ci,
  `password` VARCHAR(255) NOT NULL COMMENT 'User password',
  `crm_id` VARCHAR(255) NULL DEFAULT NULL COMMENT 'The ID of this user in our external CRM',
  `activation_code` VARCHAR(255) NULL DEFAULT NULL COMMENT 'The code required to validate the user''s account',
  `stats_id` CHAR(36) NULL DEFAULT NULL COMMENT 'User login statistics' COLLATE ascii_general_ci,
  `subscription_id` CHAR(36) NULL DEFAULT NULL COMMENT 'The plan to which the user is subscribed' COLLATE ascii_general_ci,
  `status` VARCHAR(255) NULL DEFAULT NULL COMMENT 'Current status of the user',
  `created` DATETIME NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'The date and time at which this audit log was created',
  INDEX id_idx (`id`),
  PRIMARY KEY (`audit_id`)
) ENGINE InnoDB DEFAULT CHARSET utf8mb4 COLLATE utf8mb4_unicode_ci;



-- Creates a table to hold UserStats entries
CREATE TABLE IF NOT EXISTS `user_stats` (
  `first_login` DATETIME NULL DEFAULT NULL COMMENT 'First time the user logged in the system',
  `last_login` DATETIME NULL DEFAULT NULL COMMENT 'Last time the user logged in the system',
  `login_count` BIGINT NULL DEFAULT NULL COMMENT 'Number of time the user logged in the system',
  `id` CHAR(36) NOT NULL COMMENT 'DB unique identifier field' COLLATE ascii_general_ci,
  PRIMARY KEY (`id`)
) ENGINE InnoDB DEFAULT CHARSET utf8mb4 COLLATE utf8mb4_unicode_ci;


-- Creates a table to hold Metadata entries
CREATE TABLE IF NOT EXISTS `metadata` (
  `name` VARCHAR(255) NOT NULL COMMENT 'The name to uniquely identify this metadata',
  `value` LONGTEXT NOT NULL COMMENT 'The value associated with this metadata',
  `id` CHAR(36) NOT NULL COMMENT 'DB unique identifier field' COLLATE ascii_general_ci,
  `user_id` CHAR(36) NULL DEFAULT NULL COMMENT 'Foreign relationship field' COLLATE ascii_general_ci,
  INDEX user_id_idx (user_id) COMMENT 'User foreign key',
  PRIMARY KEY (`id`)
) ENGINE InnoDB DEFAULT CHARSET utf8mb4 COLLATE utf8mb4_unicode_ci;


-- Creates a table to hold Post entries
CREATE TABLE IF NOT EXISTS `posts` (
  `id` CHAR(36) NOT NULL COMMENT 'The post''s unique ID in our system' COLLATE ascii_general_ci,
  `title` VARCHAR(255) NOT NULL COMMENT 'The post title',
  `body` LONGTEXT NOT NULL COMMENT 'The post body',
  `scheduled` DATETIME NULL DEFAULT NULL COMMENT 'The time at which this post must be published',
  `author_id` CHAR(36) NOT NULL COMMENT 'The user who created this post' COLLATE ascii_general_ci,
  `topic_id` CHAR(36) NOT NULL COMMENT 'The topic to which this post belongs' COLLATE ascii_general_ci,
  `created` DATETIME NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Time at which the post was created',
  `updated` DATETIME NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Last time at which the post was updated',
  INDEX author_id_idx (author_id) COMMENT 'User foreign key',
  INDEX topic_id_idx (topic_id) COMMENT 'Topic foreign key',
  PRIMARY KEY (`id`)
) ENGINE InnoDB DEFAULT CHARSET utf8mb4 COLLATE utf8mb4_unicode_ci;


-- Creates a table to hold Topic entries
CREATE TABLE IF NOT EXISTS `topics` (
  `title` VARCHAR(255) NOT NULL COMMENT 'The topic title',
  `description` LONGTEXT NULL DEFAULT NULL COMMENT 'The topic description',
  `created` DATETIME NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Time at which the post was created',
  `updated` DATETIME NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Last time at which the post was updated',
  `id` CHAR(36) NOT NULL COMMENT 'DB unique identifier field' COLLATE ascii_general_ci,
  PRIMARY KEY (`id`)
) ENGINE InnoDB DEFAULT CHARSET utf8mb4 COLLATE utf8mb4_unicode_ci;


-- Creates a table to hold Subscription entries
CREATE TABLE IF NOT EXISTS `subscriptions` (
  `user_id` CHAR(36) NOT NULL COMMENT 'The user to which this subscription belongs' COLLATE ascii_general_ci,
  `plan` BIGINT NOT NULL COMMENT 'The plan subscribed by this user in our billing system',
  `renewal` DATE NOT NULL COMMENT 'The date at which the subscription must be renewed',
  `created` DATETIME NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Time at which the post was created',
  `updated` DATETIME NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Last time at which the post was updated',
  `id` CHAR(36) NOT NULL COMMENT 'DB unique identifier field' COLLATE ascii_general_ci,
  INDEX user_id_idx (user_id) COMMENT 'User foreign key',
  PRIMARY KEY (`id`)
) ENGINE InnoDB DEFAULT CHARSET utf8mb4 COLLATE utf8mb4_unicode_ci;


-- Creates a table to link the User and Topic entities in a many to many relation
CREATE TABLE IF NOT EXISTS `users_topics` (
  `user_id` CHAR(36) NOT NULL COMMENT 'The user''s unique ID in our system' COLLATE ascii_general_ci,
  `topic_id` CHAR(36) NOT NULL COMMENT 'DB unique identifier field' COLLATE ascii_general_ci,
  INDEX user_id_idx (`user_id`),
  INDEX topic_id_idx (`topic_id`),
  PRIMARY KEY (`user_id`, `topic_id`)
) ENGINE InnoDB;

-- Creates a foreign key to link User with UserStatus
ALTER TABLE users ADD CONSTRAINT fk_users_user_statuses_status FOREIGN KEY (status) REFERENCES user_statuses (name) ON DELETE SET NULL ON UPDATE CASCADE;

-- Creates a foreign key to link User with UserStats
ALTER TABLE users ADD CONSTRAINT fk_users_user_stats_stats_id FOREIGN KEY (stats_id) REFERENCES user_stats (id) ON DELETE SET NULL ON UPDATE CASCADE;

-- Creates a foreign key to link User with Subscription
ALTER TABLE users ADD CONSTRAINT fk_users_subscriptions_subscription_id FOREIGN KEY (subscription_id) REFERENCES subscriptions (id) ON DELETE SET NULL ON UPDATE CASCADE;

-- Creates a foreign key to link Metadata with User
ALTER TABLE metadata ADD CONSTRAINT fk_metadata_users_user_id FOREIGN KEY (user_id) REFERENCES users (id) ON DELETE SET NULL ON UPDATE CASCADE;

-- Creates a foreign key to link Post with User
ALTER TABLE posts ADD CONSTRAINT fk_posts_users_author_id FOREIGN KEY (author_id) REFERENCES users (id) ON DELETE RESTRICT ON UPDATE CASCADE;

-- Creates a foreign key to link Post with Topic
ALTER TABLE posts ADD CONSTRAINT fk_posts_topics_topic_id FOREIGN KEY (topic_id) REFERENCES topics (id) ON DELETE RESTRICT ON UPDATE CASCADE;

-- Creates a foreign key to link Subscription with Plan
ALTER TABLE subscriptions ADD CONSTRAINT fk_subscriptions_plans_plan FOREIGN KEY (plan) REFERENCES plans (id) ON DELETE RESTRICT ON UPDATE CASCADE;

-- Creates a foreign key to link Subscription with User
ALTER TABLE subscriptions ADD CONSTRAINT fk_subscriptions_users_user_id FOREIGN KEY (user_id) REFERENCES users (id) ON DELETE RESTRICT ON UPDATE CASCADE;



-- TRIGGERS START
DELIMITER //

-- Create the trigger to set the UUID primary key automatically
DROP TRIGGER IF EXISTS `users_BEFORE_INSERT`//

CREATE TRIGGER `users_BEFORE_INSERT` BEFORE INSERT ON `users`
FOR EACH ROW BEGIN
  IF NEW.`id` IS NULL THEN
      SET NEW.`id` = UUID();
  END IF;
END//

-- Create the triggers to populate the audit table automatically
DROP TRIGGER IF EXISTS `audit_users_AFTER_INSERT`//

CREATE TRIGGER `audit_users_AFTER_INSERT` AFTER INSERT ON `users`
FOR EACH ROW BEGIN
  INSERT INTO users_logs(`audit_id`, `audit_operation`, `id`, `first_name`, `last_name`, `nickname`, `email`, `password`, `crm_id`, `activation_code`, `stats_id`, `subscription_id`, `status`, created)
    VALUES (UUID(), 'i', NEW.`id`, NEW.`first_name`, NEW.`last_name`, NEW.`nickname`, NEW.`email`, NEW.`password`, NEW.`crm_id`, NEW.`activation_code`, NEW.`stats_id`, NEW.`subscription_id`, NEW.`status`, NOW());
END//

DROP TRIGGER IF EXISTS `audit_users_AFTER_UPDATE`//

CREATE TRIGGER `audit_users_AFTER_UPDATE` AFTER UPDATE ON `users`
FOR EACH ROW BEGIN
  IF (NEW.`first_name` <> OLD.`first_name`
    OR NEW.`last_name` <> OLD.`last_name`
    OR NEW.`nickname` <> OLD.`nickname`
    OR NEW.`email` <> OLD.`email`
    OR NEW.`password` <> OLD.`password`
    OR NEW.`crm_id` <> OLD.`crm_id`
    OR NEW.`activation_code` <> OLD.`activation_code`
    OR NEW.`stats_id` <> OLD.`stats_id`
    OR NEW.`subscription_id` <> OLD.`subscription_id`
    OR NEW.`status` <> OLD.`status`)
  THEN
    INSERT INTO users_logs(`audit_id`, `audit_operation`, `id`, `first_name`, `last_name`, `nickname`, `email`, `password`, `crm_id`, `activation_code`, `stats_id`, `subscription_id`, `status`, created)
        VALUES (UUID(), 'u', NEW.`id`, NEW.`first_name`, NEW.`last_name`, NEW.`nickname`, NEW.`email`, NEW.`password`, NEW.`crm_id`, NEW.`activation_code`, NEW.`stats_id`, NEW.`subscription_id`, NEW.`status`, NOW());
  END IF;
END//

DROP TRIGGER IF EXISTS `audit_users_AFTER_DELETE`//

CREATE TRIGGER `audit_users_AFTER_DELETE` AFTER DELETE ON `users`
FOR EACH ROW BEGIN
  INSERT INTO users_logs(`audit_id`, `audit_operation`, `id`, `first_name`, `last_name`, `nickname`, `email`, `password`, `crm_id`, `activation_code`, `stats_id`, `subscription_id`, `status`, created)
    VALUES (UUID(), 'd', OLD.`id`, OLD.`first_name`, OLD.`last_name`, OLD.`nickname`, OLD.`email`, OLD.`password`, OLD.`crm_id`, OLD.`activation_code`, OLD.`stats_id`, OLD.`subscription_id`, OLD.`status`, NOW());
END//

-- Create the trigger to set the UUID primary key automatically
DROP TRIGGER IF EXISTS `user_stats_BEFORE_INSERT`//

CREATE TRIGGER `user_stats_BEFORE_INSERT` BEFORE INSERT ON `user_stats`
FOR EACH ROW BEGIN
  IF NEW.`id` IS NULL THEN
      SET NEW.`id` = UUID();
  END IF;
END//

-- Create the trigger to set the UUID primary key automatically
DROP TRIGGER IF EXISTS `metadata_BEFORE_INSERT`//

CREATE TRIGGER `metadata_BEFORE_INSERT` BEFORE INSERT ON `metadata`
FOR EACH ROW BEGIN
  IF NEW.`id` IS NULL THEN
      SET NEW.`id` = UUID();
  END IF;
END//

-- Create the trigger to set the UUID primary key automatically
DROP TRIGGER IF EXISTS `posts_BEFORE_INSERT`//

CREATE TRIGGER `posts_BEFORE_INSERT` BEFORE INSERT ON `posts`
FOR EACH ROW BEGIN
  IF NEW.`id` IS NULL THEN
      SET NEW.`id` = UUID();
  END IF;
END//

-- Create the trigger to set the UUID primary key automatically
DROP TRIGGER IF EXISTS `topics_BEFORE_INSERT`//

CREATE TRIGGER `topics_BEFORE_INSERT` BEFORE INSERT ON `topics`
FOR EACH ROW BEGIN
  IF NEW.`id` IS NULL THEN
      SET NEW.`id` = UUID();
  END IF;
END//

-- Create the trigger to set the UUID primary key automatically
DROP TRIGGER IF EXISTS `subscriptions_BEFORE_INSERT`//

CREATE TRIGGER `subscriptions_BEFORE_INSERT` BEFORE INSERT ON `subscriptions`
FOR EACH ROW BEGIN
  IF NEW.`id` IS NULL THEN
      SET NEW.`id` = UUID();
  END IF;
END//

DELIMITER ;
-- TRIGGERS END
