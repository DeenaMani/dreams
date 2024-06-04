-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 07, 2024 at 08:34 AM
-- Server version: 10.4.27-MariaDB
-- PHP Version: 8.2.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `children`
--

-- --------------------------------------------------------

--
-- Table structure for table `abouts`
--

CREATE TABLE `abouts` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `image` varchar(255) NOT NULL,
  `who_we_are` text NOT NULL,
  `what_we_do` text NOT NULL,
  `about_description` longtext NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `abouts`
--

INSERT INTO `abouts` (`id`, `title`, `image`, `who_we_are`, `what_we_do`, `about_description`, `created_at`, `updated_at`) VALUES
(1, 'Welcome to Childerns Medha', '1706614197.jpg', '<div class=\"content\">\r\n						<p> We have started this Childrens Medha Academy aiming to support the parents in mentoring their children enhance children skills and abilities mainly who are in age group between 10 to 15 years.&nbsp; We make them ready to face multiple competetive exams at national and state level.&nbsp; Our course curriculum helps children definitely to secure good rank \r\n						</p>\r\n						<p>\r\n						in competative exams and Perticularly who are aiming for strong performnace and secure good ranks at national and state level competetive exams like SAINIK, RMS, RIMS and NAVODAYA etc in India</p>\r\n					</div>', '<div class=\"content\">\r\n						<p> We have started this Childrens Medha Academy aiming to support the parents in mentoring their children enhance children skills and abilities mainly who are in age group between 10 to 15 years.&nbsp; We make them ready to face multiple competetive exams at national and state level.&nbsp; Our course curriculum helps children definitely to secure good rank \r\n						</p>\r\n						<p>\r\n						in competative exams and Perticularly who are aiming for strong performnace and secure good ranks at national and state level competetive exams like SAINIK, RMS, RIMS and NAVODAYA etc in India</p>\r\n					</div>', '<div>&lt;div class=\" about-short-description\"&gt;</div><div><span style=\"white-space: normal;\"><span style=\"white-space:pre\">						</span>&lt;p&gt; We have started this Childrens Medha Academy aiming to support the parents in mentoring their children enhance children skills and abilities mainly who are in age group between 10 to 15 years.&amp;nbsp; We make them ready to face multiple competetive exams at national and state level.&amp;nbsp; Our course curriculum helps children definitely to secure good rank&nbsp;</span></div><div><span style=\"white-space: normal;\"><span style=\"white-space:pre\">							</span>&lt;!-- &lt;span&gt;...read more&lt;/span&gt; &lt;/p&gt; --&gt;</span></div><div><span style=\"white-space: normal;\"><span style=\"white-space:pre\">						</span>&lt;!-- &lt;p&gt; --&gt;</span></div><div><span style=\"white-space: normal;\"><span style=\"white-space:pre\">						</span>in competative exams and Perticularly who are aiming for strong performnace and secure good ranks at national and state level competetive exams like SAINIK, RMS, RIMS and NAVODAYA etc in India&lt;/p&gt;</span></div><div><br></div><div><span style=\"white-space: normal;\"><span style=\"white-space:pre\">						</span>&lt;h5 class=\"mb-4\"&gt;To achive this we have three principles&lt;/h5&gt;</span></div><div><span style=\"white-space: normal;\"><span style=\"white-space:pre\">						</span>&lt;ul class=\" list-unstyled\"&gt;</span></div><div><span style=\"white-space: normal;\"><span style=\"white-space:pre\">							</span>&lt;li&gt;Boosting string fundamental concepts&lt;/li&gt;</span></div><div><span style=\"white-space: normal;\"><span style=\"white-space:pre\">							</span>&lt;li&gt;Adpting regular practice&lt;/li&gt;</span></div><div><span style=\"white-space: normal;\"><span style=\"white-space:pre\">							</span>&lt;li&gt;Implemnting effective monitoring techniques&lt;/li&gt;</span></div><div><span style=\"white-space: normal;\"><span style=\"white-space:pre\">						</span>&lt;/ul&gt;</span></div><div><br></div><div><span style=\"white-space: normal;\"><span style=\"white-space:pre\">						</span>&lt;div&gt;Undoubtedly available courses enhances every childs long term possibilities and encourages competetive spirit.&lt;/div&gt;</span></div><div><br></div><div><span style=\"white-space: normal;\"><span style=\"white-space:pre\">					</span>&lt;/div&gt;</span></div>', '2024-01-30 05:59:57', '2024-01-30 05:59:57');

-- --------------------------------------------------------

--
-- Table structure for table `banners`
--

CREATE TABLE `banners` (
  `id` bigint(20) NOT NULL,
  `banner_image` varchar(255) NOT NULL,
  `banner_title` text NOT NULL,
  `banner_description` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `banners`
--

INSERT INTO `banners` (`id`, `banner_image`, `banner_title`, `banner_description`, `created_at`, `updated_at`) VALUES
(1, '1706607721.jpg', 'Your Child\'s Future is Our Priority', 'Childrens Medha Academy – Best online education platform to prepare multiple competitive exams like SAINIK,RMS,RIMS and NAVODAYA at state and national level in India', '2023-04-20 23:39:18', '2024-01-31 00:11:04'),
(2, '1706607988.jpg', 'Your Child\'s Future is Our Priority', 'Childrens Medha Academy – Best online education platform to prepare multiple competitive exams like SAINIK,RMS,RIMS and NAVODAYA at state and national level in India', '2024-01-30 04:16:28', '2024-01-31 00:11:10');

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `image` varchar(225) NOT NULL,
  `category_name` varchar(255) NOT NULL,
  `category_description` longtext DEFAULT NULL,
  `category_slug` varchar(255) DEFAULT NULL,
  `price` double DEFAULT NULL,
  `is_mutiple` int(11) NOT NULL DEFAULT 0,
  `status` varchar(255) DEFAULT '1',
  `updated_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `image`, `category_name`, `category_description`, `category_slug`, `price`, `is_mutiple`, `status`, `updated_at`, `created_at`) VALUES
(2, '1707016696.png', '6th Grade(SAINIK/RMS/RIMS)', NULL, '6th-gradesainikrmsrims', 30000, 1, '1', '2024-02-06 22:36:25', '2024-02-03 21:43:34'),
(3, '1707016743.png', '9th Grade SAINIK/RMS/RIMS', NULL, '9th-grade-sainikrmsrims', 40000, 1, '1', '2024-02-06 22:36:35', '2024-02-03 21:49:03'),
(4, '1707016776.png', 'Academic Skills', NULL, 'academic-skills', NULL, 0, '1', '2024-02-06 23:13:21', '2024-02-03 21:49:36'),
(5, '1707016790.png', 'Analytical skills/Mathematics', NULL, 'analytical-skillsmathematics', NULL, 0, '1', '2024-02-06 23:13:36', '2024-02-03 21:49:50'),
(6, '1707016856.png', 'Intelligence/Reasoning(Verbal and Non-Verbal)', NULL, 'intelligencereasoningverbal-and-non-verbal', NULL, 0, '1', '2024-02-06 23:13:32', '2024-02-03 21:50:56'),
(7, '1707057057.jpg', 'TEST', NULL, 'test', 3000, 1, '1', '2024-02-06 22:37:12', '2024-02-04 09:00:57');

-- --------------------------------------------------------

--
-- Table structure for table `competitite_exams`
--

CREATE TABLE `competitite_exams` (
  `id` int(11) NOT NULL,
  `exam_name` varchar(255) NOT NULL,
  `description` longtext NOT NULL,
  `date` varchar(255) NOT NULL,
  `time` varchar(255) NOT NULL,
  `status` tinyint(4) DEFAULT 1,
  `updated_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `competitite_exams`
--

INSERT INTO `competitite_exams` (`id`, `exam_name`, `description`, `date`, `time`, `status`, `updated_at`, `created_at`) VALUES
(1, 'Numerical Mathematics', '<p>Numerical Mathematics<br></p>', '2024-03-01', '18:06', 1, '2024-02-05 07:01:50', '2024-02-05 07:01:23');

-- --------------------------------------------------------

--
-- Table structure for table `courses`
--

CREATE TABLE `courses` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `category_id` varchar(255) NOT NULL,
  `course_name` varchar(255) NOT NULL,
  `course_image` varchar(255) NOT NULL,
  `price` double NOT NULL,
  `offered_price` double NOT NULL,
  `description` text NOT NULL,
  `full_description` longtext DEFAULT NULL,
  `feedback_rating` varchar(255) DEFAULT NULL,
  `instructor_id` varchar(255) NOT NULL,
  `duration` varchar(255) NOT NULL,
  `language` varchar(255) NOT NULL,
  `live_session` varchar(255) NOT NULL,
  `topic_count` smallint(6) NOT NULL,
  `recording_count` smallint(6) NOT NULL,
  `material_count` smallint(6) NOT NULL,
  `practice_test_count` smallint(6) NOT NULL,
  `grand_test_chapter` smallint(6) NOT NULL,
  `grand_test_combine` smallint(6) NOT NULL,
  `grand_test_syllabus` smallint(6) NOT NULL,
  `validity` varchar(255) NOT NULL,
  `slug` varchar(255) DEFAULT NULL,
  `seo_keyword` varchar(255) DEFAULT NULL,
  `seo_meta_description` text DEFAULT NULL,
  `status` tinyint(4) DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `courses`
--

INSERT INTO `courses` (`id`, `category_id`, `course_name`, `course_image`, `price`, `offered_price`, `description`, `full_description`, `feedback_rating`, `instructor_id`, `duration`, `language`, `live_session`, `topic_count`, `recording_count`, `material_count`, `practice_test_count`, `grand_test_chapter`, `grand_test_combine`, `grand_test_syllabus`, `validity`, `slug`, `seo_keyword`, `seo_meta_description`, `status`, `created_at`, `updated_at`) VALUES
(1, '3', 'English', '1706985381.jpg', 5000, 7000, 'Englsh', NULL, NULL, '[\"1\",\"2\"]', '45 hours', 'English', 'Weekly 1 hr', 25, 50, 25, 200, 50, 25, 50, '12', 'english', 'Englsh', 'Englsh', 1, '2024-02-03 13:06:21', '2024-02-06 11:29:57'),
(2, '4', 'Mathematics', '1706985844.jpg', 5000, 7000, 'Mathematics', NULL, NULL, '[\"2\"]', '80 hours', 'English', '2-3 weeks 2h', 0, 0, 0, 0, 0, 0, 0, '1 Year', 'mathematics', 'Englsh', 'Mathematics', 1, '2024-02-03 13:14:04', '2024-02-05 01:05:43'),
(3, '6', 'INTELLIGENCE/REASONING', '1706987550.jpg', 5000, 7000, 'INTELLIGENCE/REASONING', NULL, NULL, '[\"1\",\"2\"]', '80 hours', 'INTELLIGENCE/REASONING', '2-3 weeks 2h', 0, 0, 0, 0, 0, 0, 0, '1 Year', 'intelligencereasoning', 'INTELLIGENCE/REASONING', 'INTELLIGENCE/REASONING', 1, '2024-02-03 13:42:30', '2024-02-03 13:42:30'),
(4, '2', 'Social Science', '1707144014.jpg', 5000, 7000, 'Social Science', NULL, NULL, '[\"2\"]', '80 hours', 'English', '2-3 weeks 2h', 0, 0, 0, 0, 0, 0, 0, '1 Year', 'social-science', 'social Science', NULL, 1, '2024-02-05 09:10:14', '2024-02-05 09:10:14');

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `faqs`
--

CREATE TABLE `faqs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `faq_title` varchar(255) NOT NULL,
  `faq_description` longtext NOT NULL,
  `status` tinyint(4) DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `faqs`
--

INSERT INTO `faqs` (`id`, `faq_title`, `faq_description`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Why won\'t my payment go through?', '<p>&lt;div class=\"\"&gt;</p><p>&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &lt;h4&gt;Course Description&lt;/h4&gt;</p><p>&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &lt;p&gt;Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged.</p><p><br></p><p>It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.&lt;/p&gt;</p><p>&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &lt;/div&gt;</p>', 1, '2024-01-30 06:21:30', '2024-01-30 06:26:21');

-- --------------------------------------------------------

--
-- Table structure for table `instructors`
--

CREATE TABLE `instructors` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `instructor_type` tinyint(4) NOT NULL,
  `instructor_image` varchar(255) NOT NULL,
  `instructor_name` varchar(255) NOT NULL,
  `about` text DEFAULT NULL,
  `subject` varchar(255) NOT NULL,
  `skills` text DEFAULT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `status` tinyint(4) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `instructors`
--

INSERT INTO `instructors` (`id`, `instructor_type`, `instructor_image`, `instructor_name`, `about`, `subject`, `skills`, `email`, `phone`, `status`, `created_at`, `updated_at`) VALUES
(1, 1, '1706964131.jpg', 'Emma Harper', NULL, 'English', NULL, 'harper@gmail.com', '1234567890', 1, '2024-02-03 06:58:06', '2024-02-03 07:12:11'),
(2, 2, '1706964369.jpg', 'Ioan Drozd', NULL, 'Mathematics', NULL, 'drozd@gmail.com', '1234567899', 1, '2024-02-03 07:16:09', '2024-02-06 06:41:03');

-- --------------------------------------------------------

--
-- Table structure for table `liveclasses`
--

CREATE TABLE `liveclasses` (
  `id` int(11) NOT NULL,
  `exam_type` varchar(255) NOT NULL,
  `category_id` int(11) NOT NULL,
  `course_id` varchar(255) NOT NULL,
  `topic` varchar(255) NOT NULL,
  `meeting_link` varchar(255) NOT NULL,
  `date` varchar(255) NOT NULL,
  `time` varchar(255) NOT NULL,
  `additional_information` text DEFAULT NULL,
  `status` tinyint(4) DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `liveclasses`
--

INSERT INTO `liveclasses` (`id`, `exam_type`, `category_id`, `course_id`, `topic`, `meeting_link`, `date`, `time`, `additional_information`, `status`, `updated_at`, `created_at`) VALUES
(1, 'Online', 4, '2', '1', 'https://youtu.be/b1t41Q3xRM8?si=jRC439VQ97DNUN-3', '2024-02-14', '16:54', 'fasdf', 1, '2024-02-05 13:26:34', '2024-02-05 10:24:51');

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2014_10_12_000000_create_users_table', 1),
(2, '2014_10_12_100000_create_password_reset_tokens_table', 1),
(3, '2019_08_19_000000_create_failed_jobs_table', 1),
(4, '2019_12_14_000001_create_personal_access_tokens_table', 1),
(5, '2024_01_30_081147_create_abouts_table', 2),
(6, '2024_01_30_111117_create_abouts_table', 3),
(7, '2024_01_30_114136_create_faqs_table', 4),
(8, '2024_01_30_120052_create_whychooses_table', 5),
(9, '2024_01_31_054402_create_ourvaluses_table', 6),
(10, '2024_02_02_094832_create_resources_table', 7),
(11, '2024_02_03_073021_create_topics_table', 8),
(12, '2024_02_03_073849_create_courses_table', 9),
(13, '2024_02_03_115259_create_instructors_table', 9);

-- --------------------------------------------------------

--
-- Table structure for table `ourvalues`
--

CREATE TABLE `ourvalues` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `image` varchar(255) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` longtext NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `ourvalues`
--

INSERT INTO `ourvalues` (`id`, `image`, `title`, `description`, `created_at`, `updated_at`) VALUES
(1, '1706863740.png', 'Our Goal', '<ul class=\" list-unstyled\">\r\n								<li class=\"mb-2\">Providing comprehensive coaching</li>\r\n								<li class=\"mb-2\">Excellence in Education to empower students with the knowledge and skills required </li>\r\n								<li class=\"mb-2\">Holistic Development – Along with exam specific content also nurturing critical thinking, problem-solving abilities, and overall personality development</li>\r\n							</ul>', '2024-02-02 03:19:00', '2024-02-02 03:25:24'),
(2, '1706863866.png', 'Our Mission', '<ul class=\" list-unstyled\">\r\n								<li class=\"mb-2\">Provide Quality Education Access through online platforms, reaching students across geographical boundaries</li>\r\n								<li class=\"mb-2\">Develop and implement customized Curriculum for each competitive exam</li>\r\n								<li class=\"mb-2\">Committing to continuous improvement by regularly updating course content, incorporating feedback, and staying abreast of evolving educational trends to remain at the forefront of online education for competitive exams</li>\r\n							</ul>', '2024-02-02 03:21:06', '2024-02-02 03:21:06'),
(3, '1706864276.png', 'Our Vision', '<ul class=\"list-unstyled\" >\r\n   <li> To be a pioneering online education institute recognized for its unparalleled \r\n           commitment to preparing students for multiple competitive exams at state      \r\n           and national levels, creating future leaders and achievers\r\n   </li>\r\n</ul>', '2024-02-02 03:27:56', '2024-02-02 03:27:56');

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tokenable_type` varchar(255) NOT NULL,
  `tokenable_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `token` varchar(64) NOT NULL,
  `abilities` text DEFAULT NULL,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `resources`
--

CREATE TABLE `resources` (
  `id` int(11) NOT NULL,
  `topic_id` smallint(6) NOT NULL,
  `title` varchar(255) NOT NULL,
  `video_link` varchar(255) DEFAULT NULL,
  `pdf` varchar(255) DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `resources`
--

INSERT INTO `resources` (`id`, `topic_id`, `title`, `video_link`, `pdf`, `updated_at`, `created_at`) VALUES
(2, 1, 'Video 2', 'https://www.youtube.com/', NULL, '2024-02-07 01:48:34', '2024-02-07 01:48:34');

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE `settings` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `company_name` varchar(255) NOT NULL,
  `logo` varchar(255) NOT NULL,
  `fav_icon` varchar(255) NOT NULL,
  `phone` varchar(255) NOT NULL,
  `phone_2` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_2` varchar(255) NOT NULL,
  `email_3` varchar(255) NOT NULL,
  `address` varchar(255) NOT NULL,
  `google_map_link` text NOT NULL,
  `youtube` varchar(255) NOT NULL,
  `twitter` varchar(255) NOT NULL,
  `insta` varchar(255) NOT NULL,
  `facebook` varchar(255) NOT NULL,
  `linked_in` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `settings`
--

INSERT INTO `settings` (`id`, `company_name`, `logo`, `fav_icon`, `phone`, `phone_2`, `email`, `email_2`, `email_3`, `address`, `google_map_link`, `youtube`, `twitter`, `insta`, `facebook`, `linked_in`, `created_at`, `updated_at`) VALUES
(1, 'Childers Medha Academy', 'logo-1707012514.png', 'fav-icon-1707012514.png', '9030353300', '1234567890', 'info@childrensmedha.com', 'sales@childrensmedha.com', 'carrer@childrensmedha.com', 'B-503, Mantri Webcity<br>\r\nHennur Road,\r\nNarayampura<br>\r\nBengaluru, Karnataka - 560077<br>\r\nINDIA', 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d1663021.9764678692!2d138.450442887185!3d35.50205774557601!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x605d1b87f02e57e7%3A0x2e01618b22571b89!2sTokyo%2C%20Japan!5e0!3m2!1sen!2sin!4v1684835658239!5m2!1sen!2sin\"', 'https://twitter.com/login?lang=en', 'https://twitter.com/login?lang=en', 'https://twitter.com/login?lang=en', 'https://twitter.com/login?lang=en', 'https://twitter.com/login?lang=en', NULL, '2024-02-03 20:39:27');

-- --------------------------------------------------------

--
-- Table structure for table `students`
--

CREATE TABLE `students` (
  `id` int(11) NOT NULL,
  `first_name` varchar(255) NOT NULL,
  `last_name` varchar(255) DEFAULT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `status` tinyint(4) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `terms`
--

CREATE TABLE `terms` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `full_description` longtext DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `terms`
--

INSERT INTO `terms` (`id`, `title`, `full_description`, `updated_at`, `created_at`) VALUES
(1, 'Terms And Conditions', '<p>Terms And Conditions<br></p>', '2024-02-05 05:34:23', '2024-02-05 00:04:23'),
(2, 'Privacy And Policy', '<p>Privacy And Policy<br></p>', '2024-02-05 05:37:06', '2024-02-05 00:07:06');

-- --------------------------------------------------------

--
-- Table structure for table `topics`
--

CREATE TABLE `topics` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `category_id` int(11) NOT NULL,
  `course_id` int(11) NOT NULL,
  `topic` varchar(255) NOT NULL,
  `duration` varchar(255) NOT NULL,
  `type` tinyint(4) NOT NULL,
  `video_link` varchar(255) DEFAULT NULL,
  `pdf` varchar(255) DEFAULT NULL,
  `status` tinyint(4) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `topics`
--

INSERT INTO `topics` (`id`, `category_id`, `course_id`, `topic`, `duration`, `type`, `video_link`, `pdf`, `status`, `created_at`, `updated_at`) VALUES
(1, 4, 2, 'Natural Numbers', '3 hours', 2, NULL, '1707021711.pdf', 1, '2024-02-03 23:05:04', '2024-02-07 02:04:28');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Admin', 'admin@gmail.com', '2023-04-23 02:06:47', '$2y$10$CM9nwFXFpVv83S7imVBJc.liJEvDhJqvxra5b4CID7r4Uw5Ylw3D.', NULL, NULL, '2024-02-04 23:36:43');

-- --------------------------------------------------------

--
-- Table structure for table `whychooses`
--

CREATE TABLE `whychooses` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `whychoose_image` varchar(255) NOT NULL,
  `whychoose_title` varchar(255) DEFAULT NULL,
  `whychoose_description` text NOT NULL,
  `status` tinyint(4) DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `whychooses`
--

INSERT INTO `whychooses` (`id`, `whychoose_image`, `whychoose_title`, `whychoose_description`, `status`, `created_at`, `updated_at`) VALUES
(2, '1707027069.png', NULL, 'Hellow', 1, '2024-02-02 03:42:45', '2024-02-04 00:41:09'),
(3, '1706865234.png', NULL, 'Weekly Interactive Live Classes with the best experienced teachers', 1, '2024-02-02 03:43:54', '2024-02-02 03:43:54');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `abouts`
--
ALTER TABLE `abouts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `banners`
--
ALTER TABLE `banners`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `competitite_exams`
--
ALTER TABLE `competitite_exams`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `courses`
--
ALTER TABLE `courses`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `faqs`
--
ALTER TABLE `faqs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `instructors`
--
ALTER TABLE `instructors`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `instructors_email_unique` (`email`),
  ADD UNIQUE KEY `instructors_phone_unique` (`phone`);

--
-- Indexes for table `liveclasses`
--
ALTER TABLE `liveclasses`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ourvalues`
--
ALTER TABLE `ourvalues`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`);

--
-- Indexes for table `resources`
--
ALTER TABLE `resources`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `students`
--
ALTER TABLE `students`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `terms`
--
ALTER TABLE `terms`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `topics`
--
ALTER TABLE `topics`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- Indexes for table `whychooses`
--
ALTER TABLE `whychooses`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `abouts`
--
ALTER TABLE `abouts`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `banners`
--
ALTER TABLE `banners`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `competitite_exams`
--
ALTER TABLE `competitite_exams`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `courses`
--
ALTER TABLE `courses`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `faqs`
--
ALTER TABLE `faqs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `instructors`
--
ALTER TABLE `instructors`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `liveclasses`
--
ALTER TABLE `liveclasses`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `ourvalues`
--
ALTER TABLE `ourvalues`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `resources`
--
ALTER TABLE `resources`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `settings`
--
ALTER TABLE `settings`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `students`
--
ALTER TABLE `students`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `terms`
--
ALTER TABLE `terms`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `topics`
--
ALTER TABLE `topics`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `whychooses`
--
ALTER TABLE `whychooses`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
