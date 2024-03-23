-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 23, 2024 at 11:35 PM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `blogs_app`
--

DELIMITER $$
--
-- Procedures
--
DROP PROCEDURE IF EXISTS `GetPostsIndexSearch`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `GetPostsIndexSearch` (IN `offset_count` INT, IN `limit_count` INT, IN `search_value` VARCHAR(255))   BEGIN
    SET @offset_count = offset_count;
    SET @limit_count = limit_count;
    SET @search_value = search_value;

    SET @sql_query = CONCAT(
        'SELECT 
            P.id,
            P.title,
            P.description,
            P.post_img,
            P.created_at,
            COUNT(L.user_id) as likes_number,
            GROUP_CONCAT(L.user_id) as liked_by_user_ids,
            U.name as author_name,
            GROUP_CONCAT(C.name) as category_names,
            COUNT(DISTINCT CMT.id) as comments_number
        FROM posts as P
        LEFT JOIN likes as L ON P.id = L.post_id
        LEFT JOIN users as U ON P.user_id = U.id
        LEFT JOIN post_categories as PC ON P.id = PC.post_id
        LEFT JOIN categories as C ON PC.category_id = C.id
        LEFT JOIN comments as CMT ON P.id = CMT.post_id
        WHERE P.isarchived = 0
          AND P.status = "accepted"
          AND P.title COLLATE utf8mb4_unicode_ci LIKE CONCAT("%", @search_value, "%") COLLATE utf8mb4_unicode_ci
        GROUP BY P.id, P.title, P.description, P.post_img, P.created_at, U.name
        ORDER BY P.created_at DESC
        LIMIT ', @offset_count, ', ', @limit_count
    );

    -- Prepare and execute the dynamic SQL query
    PREPARE dynamic_query FROM @sql_query;
    EXECUTE dynamic_query;
    DEALLOCATE PREPARE dynamic_query;
END$$

DROP PROCEDURE IF EXISTS `GetPostsJson`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `GetPostsJson` ()   BEGIN
    SET @sql_query = '
        SELECT 
            P.id,
            P.title,
            P.description,
            P.post_img,
            P.created_at,
            COUNT(L.user_id) as likes_number,
            GROUP_CONCAT(L.user_id) as liked_by_user_ids,
            U.name as author_name,
            GROUP_CONCAT(DISTINCT C.name) as category_names,
            COUNT(DISTINCT CMT.id) as comments_number
        FROM posts as P
        LEFT JOIN likes as L ON P.id = L.post_id
        LEFT JOIN users as U ON P.user_id = U.id
        LEFT JOIN post_categories as PC ON P.id = PC.post_id
        LEFT JOIN categories as C ON PC.category_id = C.id
        LEFT JOIN comments as CMT ON P.id = CMT.post_id
        WHERE P.isarchived = 0
        AND P.status = "accepted"
        GROUP BY P.id, P.title, P.description, P.post_img, P.created_at, U.name
        ORDER BY P.created_at DESC';

    PREPARE dynamic_query FROM @sql_query;
    EXECUTE dynamic_query;
    DEALLOCATE PREPARE dynamic_query;
END$$

DROP PROCEDURE IF EXISTS `GetRelatedPosts`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `GetRelatedPosts` (IN `postId` INT)   BEGIN
    CREATE TEMPORARY TABLE temp_related_posts AS
    SELECT pc.category_id
    FROM post_categories pc
    WHERE pc.post_id = postId;

    CREATE TEMPORARY TABLE temp_posts AS
    SELECT p.id, p.title, p.post_img
    FROM posts p
    WHERE p.isarchived = 0 AND p.id != postId AND p.status = "accepted";

    CREATE TEMPORARY TABLE final_result AS
    SELECT p.id, p.title, p.post_img
    FROM temp_posts p
    JOIN post_categories pc ON p.id = pc.post_id
    JOIN temp_related_posts trp ON pc.category_id = trp.category_id
    LIMIT 6;

    CREATE TEMPORARY TABLE final_result_random AS
    SELECT *
    FROM final_result
    ORDER BY RAND()
    LIMIT 3;

    SELECT *
    FROM final_result_random;

    DROP TEMPORARY TABLE IF EXISTS temp_related_posts;
    DROP TEMPORARY TABLE IF EXISTS temp_posts;
    DROP TEMPORARY TABLE IF EXISTS final_result;
    DROP TEMPORARY TABLE IF EXISTS final_result_random;
END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

DROP TABLE IF EXISTS `categories`;
CREATE TABLE `categories` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `status` enum('pending','accepted') NOT NULL DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `comments`
--

DROP TABLE IF EXISTS `comments`;
CREATE TABLE `comments` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `post_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `content` text NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `likes`
--

DROP TABLE IF EXISTS `likes`;
CREATE TABLE `likes` (
  `post_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `messages`
--

DROP TABLE IF EXISTS `messages`;
CREATE TABLE `messages` (
  `id` bigint(20) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `subject` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `seen` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `posts`
--

DROP TABLE IF EXISTS `posts`;
CREATE TABLE `posts` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `post_img` varchar(2048) DEFAULT NULL,
  `text` mediumtext NOT NULL,
  `isarchived` tinyint(1) NOT NULL DEFAULT 0,
  `status` enum('pending','accepted') NOT NULL DEFAULT 'pending',
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Triggers `posts`
--
DROP TRIGGER IF EXISTS `before_insert_posts`;
DELIMITER $$
CREATE TRIGGER `before_insert_posts` BEFORE INSERT ON `posts` FOR EACH ROW BEGIN
    DECLARE user_is_admin INT;

    -- Check if the user is an admin
    SELECT isadmin INTO user_is_admin
    FROM users
    WHERE id = NEW.user_id;

    -- Set status to 'accepted' if the user is an admin
    IF user_is_admin = 1 THEN
        SET NEW.status = 'accepted';
    END IF;
END
$$
DELIMITER ;
DROP TRIGGER IF EXISTS `before_update_posts`;
DELIMITER $$
CREATE TRIGGER `before_update_posts` BEFORE UPDATE ON `posts` FOR EACH ROW BEGIN
    DECLARE user_is_admin INT;

    -- Check if the user is an admin
    SELECT isadmin INTO user_is_admin
    FROM users
    WHERE id = NEW.user_id;

    -- Set status to 'accepted' if the user is an admin
    IF user_is_admin = 1 THEN
        SET NEW.status = 'accepted';
    END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `post_categories`
--

DROP TABLE IF EXISTS `post_categories`;
CREATE TABLE `post_categories` (
  `post_id` bigint(20) UNSIGNED NOT NULL,
  `category_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `username` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `profile_photo_path` varchar(2048) DEFAULT 'profile-img.png',
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `verification_sent_at` timestamp NULL DEFAULT NULL,
  `isadmin` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `post_id` (`post_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `likes`
--
ALTER TABLE `likes`
  ADD PRIMARY KEY (`post_id`,`user_id`),
  ADD KEY `likes_ibfk_1` (`post_id`),
  ADD KEY `likes_ibfk_2` (`user_id`);

--
-- Indexes for table `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `posts`
--
ALTER TABLE `posts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `post_categories`
--
ALTER TABLE `post_categories`
  ADD PRIMARY KEY (`post_id`,`category_id`),
  ADD KEY `post_categories_ibfk_2` (`category_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email_2` (`email`),
  ADD UNIQUE KEY `remember_token_2` (`remember_token`),
  ADD KEY `remember_token` (`remember_token`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `comments`
--
ALTER TABLE `comments`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `messages`
--
ALTER TABLE `messages`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `posts`
--
ALTER TABLE `posts`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `comments`
--
ALTER TABLE `comments`
  ADD CONSTRAINT `comments_ibfk_1` FOREIGN KEY (`post_id`) REFERENCES `posts` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `comments_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `likes`
--
ALTER TABLE `likes`
  ADD CONSTRAINT `likes_ibfk_1` FOREIGN KEY (`post_id`) REFERENCES `posts` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `likes_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `posts`
--
ALTER TABLE `posts`
  ADD CONSTRAINT `posts_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `post_categories`
--
ALTER TABLE `post_categories`
  ADD CONSTRAINT `post_categories_ibfk_1` FOREIGN KEY (`post_id`) REFERENCES `posts` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `post_categories_ibfk_2` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
