-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 11, 2026 at 06:53 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.3.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `pvaphzrw_main`
--

-- --------------------------------------------------------

--
-- Table structure for table `abouts`
--

CREATE TABLE `abouts` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `about_image` varchar(255) NOT NULL,
  `desctiption` longtext NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `abouts`
--

INSERT INTO `abouts` (`id`, `title`, `about_image`, `desctiption`, `created_at`, `updated_at`) VALUES
(4, 'Your Trusted Source for Real Digital Accounts', 'about_image/01KA6ZT4C2DZKKY94TR38X8RC2.png', '<p>PVA ProSeller is the industry-leading, premium online marketplace dedicated to supplying marketers, agencies, businesses, and individuals with real, verified, and instantly-ready digital accounts. We understand the critical need for secure, reliable digital identities in today\'s fast-paced online environment. Our platform is built on a foundation of trust, technical precision, and unwavering security.<br><br>Unlike conventional sources, every account offered on our platform is meticulously PVA (Phone Verified Account) and thoroughly vetted for authenticity and immediate usability. Our inventory spans essential services—from high-value social media accounts and robust email platforms to critical communication tools and streaming access. We eliminate the friction and risk associated with account creation and verification, allowing you to focus purely on your operations.<br><br>We leverage advanced, proprietary technology to ensure a seamless customer experience. Our core promise is instant, automated delivery upon purchase, guaranteeing that your verified account credentials are secure and available when you need them most. Security is paramount: our systems use robust encryption and follow strict protocols to protect every transaction and customer data point.<br><br>PVA ProSeller: Your trusted partner for quality digital assets—instantly and securely delivered.</p>', '2025-11-10 10:32:49', '2025-11-16 23:57:29');

-- --------------------------------------------------------

--
-- Table structure for table `banners`
--

CREATE TABLE `banners` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `main_title` varchar(255) NOT NULL,
  `sub_title` varchar(255) NOT NULL,
  `title_details` text NOT NULL,
  `banner_image` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `banners`
--

INSERT INTO `banners` (`id`, `main_title`, `sub_title`, `title_details`, `banner_image`, `created_at`, `updated_at`) VALUES
(2, 'One-Stop Marketplace for Verified Digital Accounts', 'Buy Social, Email, Messaging, and Streaming Accounts Instantly —Verified Accounts, Instant Access, Zero Risk. 100% Working, Secure & Fast Delivery..', 'Welcome to Pva ProSeller, your trusted source for ready-to-use digital accounts.\nWe provide verified accounts for platforms like Gmail, Facebook, Instagram, Netflix, Spotify, and more.\nAll accounts are manually checked, safe to use, and delivered instantly after payment via crypto (NOWPayments).', 'banner/01KA70VH2P9X9G80H7KNCG0JAK.png', '2025-11-12 15:53:24', '2025-11-17 00:10:34');

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `cache`
--

INSERT INTO `cache` (`key`, `value`, `expiration`) VALUES
('9033c197b92110a1e8531c6e1baa5e36', 'i:1;', 1768073662),
('9033c197b92110a1e8531c6e1baa5e36:timer', 'i:1768073662;', 1768073662),
('livewire-rate-limiter:a17961fa74e9275d529f489537f179c05d50c2f3', 'i:1;', 1768072647),
('livewire-rate-limiter:a17961fa74e9275d529f489537f179c05d50c2f3:timer', 'i:1768072647;', 1768072647);

-- --------------------------------------------------------

--
-- Table structure for table `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `keywords` longtext DEFAULT NULL,
  `description` longtext DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `order` int(11) NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`, `slug`, `keywords`, `description`, `image`, `order`, `is_active`, `created_at`, `updated_at`) VALUES
(3, 'Messaging Account', 'messaging-account', 'user profiles,login credentials,two-factor authentication,end-to-end encryption,contact lists,message syncing,cloud backups,privacy settings,spam protection,media sharing,chat history,read receipts,status updates', 'Buy verified messaging accounts including WhatsApp, Telegram, Signal, Viber, WeChat, Line, and Kik from Pva ProSeller.\n\nuser profiles, login credentials, two-factor authentication, end-to-end encryption, contact lists, message syncing, cloud backups, privacy settings, spam protection, media sharing, chat history, read receipts, status updates,user profiles, login credentials, two-factor authentication, end-to-end encryption, contact lists, message syncing, cloud backups, privacy settings, spam protection, media sharing, chat history, read receipts, status updates,user profiles, login credentials, two-factor authentication, end-to-end encryption, contact lists, message syncing, cloud backups, privacy settings, spam protection, media sharing, chat history, read receipts, status updates,user profiles, login credentials, two-factor authentication, end-to-end encryption, contact lists, message syncing, cloud backups, privacy settings, spam protection, media sharing, chat history, read receipts, status updates,', 'categories/01K9Y8HNB75P3PK6XTPKTA37SM.jpg', 3, 1, '2025-11-08 12:43:04', '2025-11-15 10:53:55'),
(5, 'Social Media Account', 'social-media-account', 'multimedia accounts,digital content,video editing,graphic design,motion graphics,photography,audio production,visual storytelling,creative media,reels,shorts,animations,branding,social media content,post production,content creation,digital visuals,media design,creative agency,online media,engagement,audience growth,visual identity,cross-platform content,storytelling', NULL, 'categories/01K9Y855BM1PZFZBE722RMFGK0.jpg', 1, 1, '2025-11-10 23:40:39', '2025-12-13 19:44:04'),
(6, 'Streaming Account', 'streaming-account', '', NULL, 'categories/01K9YC05ZA3D08JZZGSXYR4R7Y.jpg', 4, 1, '2025-11-10 23:41:29', '2025-11-13 15:50:59'),
(7, 'Premium Custom Packages', 'premium-custom-packages', '', NULL, 'categories/01K9YD8TA0QSPN9AZKBN68PDDY.jpg', 6, 1, '2025-11-10 23:43:54', '2025-11-13 15:51:29'),
(8, 'Cloud & Tools Accounts', 'cloud-and-tools-account', '', NULL, 'categories/01K9YCJ5PBSH4AM5CGNQZYMSC5.jpg', 5, 1, '2025-11-10 23:44:35', '2025-11-13 15:51:14'),
(9, 'Email Account', 'email-account', '', 'We are Provide all good account.', 'categories/01K9WPEEM7MX3GH80N48C68EC2.jpg', 2, 1, '2025-11-12 23:49:59', '2025-11-13 15:49:59');

-- --------------------------------------------------------

--
-- Table structure for table `deliveries`
--

CREATE TABLE `deliveries` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `order_item_id` bigint(20) UNSIGNED NOT NULL,
  `status` enum('pending','delivered') NOT NULL DEFAULT 'pending',
  `delivered_at` timestamp NULL DEFAULT NULL,
  `accounts` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
  `question` longtext NOT NULL,
  `answer` longtext NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `faqs`
--

INSERT INTO `faqs` (`id`, `question`, `answer`, `created_at`, `updated_at`) VALUES
(7, 'What is PVA ProSeller?', 'PVA ProSeller is a trusted digital marketplace where you can buy fully verified, secure, and ready-to-use online accounts across multiple platforms. We focus on reliability, premium quality, and smooth instant delivery, making us a safe and dependable choice for verified accounts.', '2025-11-19 23:21:58', '2025-11-19 23:21:58'),
(8, 'Are your accounts real and verified?', 'Yes — all our accounts are 100% real, fully verified, and created with proper validation to ensure安全、防 protected and ready for immediate use. Each account passes strict quality checks before delivery so you always receive a genuine and reliable product.', '2025-11-19 23:23:03', '2025-11-19 23:23:03'),
(9, 'How long does delivery take after placing an order?', 'Our delivery system is fully automated, so you will receive your accounts instantly. In most cases, it takes only 2–3 minutes for your order to be processed and delivered to your dashboard.', '2025-11-19 23:25:05', '2025-11-19 23:25:05'),
(10, 'Is it safe to use the accounts purchased from you?', 'Yes — absolutely. All our accounts are created and verified using secure methods, making them safe for personal, business, and marketing use. We ensure every account is clean, stable, and ready for risk-free usage.', '2025-11-19 23:26:19', '2025-11-19 23:26:19'),
(11, 'Are the accounts PVA (Phone Verified)?', 'Many of our accounts are fully Phone Verified (PVA), but some categories do not require phone verification. Each product page clearly mentions whether the account is PVA or non-PVA, so you can choose exactly what you need before ordering.', '2025-11-19 23:28:44', '2025-11-19 23:28:44'),
(12, 'Are the accounts created using unique IPs?', 'Yes — all accounts are created using high-quality, unique IPs to ensure maximum safety, authenticity, and long-term stability. This helps reduce verification triggers and makes the accounts more secure for your use.', '2025-11-19 23:29:50', '2025-11-19 23:29:50'),
(13, 'Are the accounts fresh or aged?', 'We offer both fresh and aged accounts as separate product categories. You can easily choose the type you need from our listings. Simply order the option that fits your requirement, and you’ll receive exactly that.', '2025-11-19 23:32:09', '2025-11-19 23:32:09'),
(14, 'Can I place an order for custom accounts?', 'Yes — custom account orders are available. If you need a specific type of account with custom details, simply contact our support team. They will guide you through the process and prepare the exact account you need.', '2025-11-19 23:34:23', '2025-11-19 23:34:23'),
(15, 'How do you deliver the accounts?', 'We deliver all accounts instantly through our automated system. After completing your order, the account file will be sent directly to your email inbox in Excel format, so you can download and access it right away.', '2025-11-19 23:37:20', '2025-11-19 23:37:20'),
(16, 'Do you provide a replacement if an account doesn’t work?', 'Yes — if any account fails to work, you will receive a free replacement. Just contact our support team with the issue, and we will provide a working account as soon as possible.', '2025-11-19 23:38:58', '2025-11-19 23:38:58'),
(17, 'Are the accounts safe to use for marketing or business purposes?', 'Yes — our accounts are completely safe for personal, marketing, or business use. For extra security and peace of mind, it’s recommended to change the password after receiving the account. This ensures full control and protection.', '2025-11-19 23:41:39', '2025-11-19 23:41:39'),
(18, 'Why might an account sometimes ask for verification?', 'Most accounts are ready to use without extra verification. However, in rare cases, a platform may request verification. If this happens, you can use the email and password provided to complete the verification. For Phone Verified accounts, you can use your own number if needed.', '2025-11-19 23:45:29', '2025-11-19 23:45:29'),
(19, 'How can I keep the accounts secure?', 'To keep your accounts safe, we recommend regularly changing the password and updating any authenticator codes. Also, make sure to follow our security guidelines provided with each account for maximum protection.', '2025-11-19 23:48:37', '2025-11-19 23:48:37'),
(20, 'Do I need to use proxies or RDP?', 'For using one or two accounts, you usually don’t need proxies or RDP. However, if you plan to manage multiple accounts at the same time, using proxies or an RDP is recommended to keep everything safe and stable. You can also check our Guideline Page for detailed best practices.', '2025-11-19 23:51:43', '2025-11-19 23:51:43'),
(21, 'What payment methods do you accept?', 'We support over 300 different cryptocurrencies for payments. If you face any issues while making a payment, simply contact our support team, and they will assist you immediately.', '2025-11-19 23:53:57', '2025-11-19 23:53:57'),
(22, 'Do you offer discounts for bulk orders?', 'Yes — we provide regular discounts on all orders, and for large or bulk orders, special discounts are available. Contact our support team to learn about the best rates for your purchase.', '2025-11-19 23:55:20', '2025-11-19 23:55:20'),
(23, 'Do social media accounts include email access?', 'Yes — all our social media accounts come with full email access. You will receive the email, password, and all necessary details so you can manage and secure the account completely.', '2025-11-19 23:56:25', '2025-11-19 23:56:25'),
(24, 'Do email accounts include recovery email or phone details?', 'Some accounts come with a recovery email or phone number when it is required for security or account management. If a specific product does not need these details, it may not include them. Each product description clearly mentions what is provided, so you always know exactly what you’re receiving.', '2025-11-19 23:59:07', '2025-11-19 23:59:07'),
(25, 'How do WhatsApp and Google Voice numbers work?', 'WhatsApp and Google Voice accounts work just like regular accounts. You can use them for messaging, calling, and verification depending on the features supported by each platform. Each account includes the necessary login details so you can start using it normally and securely.', '2025-11-20 00:01:01', '2025-11-20 00:01:01'),
(26, 'Are the streaming accounts private or shared?', 'All our streaming accounts are private by default. If any account is shared, it will be clearly mentioned on the product page, so you always know what type of account you are purchasing.', '2025-11-20 00:02:18', '2025-11-20 00:02:18'),
(27, 'How can I contact support?', 'You can reach our support team via Email, Telegram, or WhatsApp. Our team is ready to assist you with any questions, issues, or special requests to ensure a smooth experience.', '2025-11-20 00:03:44', '2025-11-20 00:03:44'),
(28, 'What is your refund policy?', 'We do not offer direct refunds. However, if any account you purchase does not work, we provide a free replacement. Simply contact our support team, and they will ensure you receive a fully working account.', '2025-11-20 00:05:07', '2025-11-20 00:05:07'),
(29, 'Do you provide 24/7 support?', 'Yes — our support team is available 24/7 to assist you. In some cases, it may take a little longer to respond, but we always ensure that every query is handled as quickly as possible.', '2025-11-20 00:06:31', '2025-11-20 00:06:31'),
(30, 'Can I get bulk accounts in one order?', 'Yes — bulk orders are available, and you can contact support for special pricing or packages.', '2025-11-20 00:09:30', '2025-11-20 00:09:30'),
(31, 'Is my payment safe?', 'Yes — all payments are processed securely. We support over 300 cryptocurrencies, and any issues can be resolved via support.', '2025-11-20 00:10:01', '2025-11-20 00:10:01'),
(32, 'Can I change the email or phone linked to the account?', 'Yes — for most accounts, you can update the email, phone number, or password after purchase. We recommend doing this immediately to secure your account fully.', '2025-11-20 00:11:05', '2025-11-20 00:11:05'),
(33, 'Will the accounts get banned or blocked?', 'Accounts are created with high-quality IPs and verified properly, minimizing the risk. Following our security guidelines will help keep them safe.', '2025-11-20 00:11:18', '2025-11-20 00:11:18');

-- --------------------------------------------------------

--
-- Table structure for table `guidelines`
--

CREATE TABLE `guidelines` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `youtube_link` varchar(255) NOT NULL,
  `details` longtext NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `guidelines`
--

INSERT INTO `guidelines` (`id`, `title`, `youtube_link`, `details`, `created_at`, `updated_at`) VALUES
(1, 'How to safely phone verify social media accounts?', 'https://youtu.be/ochMl5cxBvY?si=oVl7d56WrXGzKE8p', '<ul><li><strong>PV solution 1:</strong> The best and safest method to phone verify social accounts are real sim numbers, you could either buy them in your country or off Ebay, at rates between $0.2 – $1 / sim card. If the sim cards are from a foreign country, you should make sure the sim has area coverage in your country and it doesn’t charge extra tax for receiving SMS’s locally. Advantages: Real numbers are considered to be the most trusted by social networks, also by being the single owner of the number it will ensure no further use for other accounts, which could put the accounts at risk. Downsides: It can get expensive and time consuming.</li><li><strong>PV solution 2:</strong> Using free SMS apps like Textnow, Textplus, Nextplus. Advantages: No costs. Downsides: Valid numbers (working area codes) are hard to find so it will take some time to successfully PV accounts; Because these are virtual numbers, sometimes IG can see them as suspicious.</li><li><strong>PV solution 3:</strong> Using online SMS verify services, but avoid Chinese and Russian numbers. I can’t vouch for any mainstream providers right now. – Advantages: You can easily verify accounts from a provided dashboard, or if you have plenty of accounts to verify, you can automate the process with the site API. Downsides: Numbers might be reused at some point, or they can be flagged as spammy by social networks, thus, you might get a re-PV or even instant bans after PV! So please treat this method with caution when verifying accounts (verify only 1-2 accs at first, and wait for a while to see if the accounts are in good standing).</li></ul><p><br></p>', '2025-11-09 00:40:56', '2025-11-09 00:40:56'),
(2, 'How to make secure account', 'https://youtu.be/BAT5wHVNrRU?si=ffe1QNK-r5a93F3z', '<p>hello dostow,<br>How are you?</p><p><br></p>', '2025-11-09 00:42:20', '2025-11-09 00:42:20');

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) UNSIGNED NOT NULL,
  `reserved_at` int(10) UNSIGNED DEFAULT NULL,
  `available_at` int(10) UNSIGNED NOT NULL,
  `created_at` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `total_jobs` int(11) NOT NULL,
  `pending_jobs` int(11) NOT NULL,
  `failed_jobs` int(11) NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` mediumtext DEFAULT NULL,
  `cancelled_at` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `finished_at` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2024_12_24_155824_add_two_factor_columns_to_users_table', 1),
(5, '2024_12_24_155921_create_personal_access_tokens_table', 1),
(6, '2024_12_24_162420_create_categories_table', 1),
(7, '2024_12_24_162421_create_sub_categories_table', 1),
(8, '2024_12_24_162422_create_products_table', 1),
(9, '2024_12_24_162928_create_orders_table', 1),
(10, '2024_12_24_163026_create_order_items_table', 1),
(11, '2024_12_24_181501_create_deliveries_table', 1),
(12, '2024_12_24_183653_create_payments_table', 1),
(13, '2024_12_24_190127_create_posts_table', 1),
(14, '2025_10_04_170220_create_product_features_table', 1),
(15, '2025_10_04_180345_create_faqs_table', 1),
(16, '2025_10_04_202217_create_abouts_table', 1),
(17, '2025_10_05_070604_create_settings_table', 1),
(18, '2025_10_07_163925_create_guidelines_table', 1),
(19, '2025_10_10_134657_create_offers_table', 1),
(20, '2025_10_17_120335_create_product_accounts_table', 1),
(21, '2025_11_04_173025_create_privacies_table', 2),
(22, '2025_11_04_180931_create_terms_table', 3),
(24, '2025_11_04_182359_create_banners_table', 4);

-- --------------------------------------------------------

--
-- Table structure for table `offers`
--

CREATE TABLE `offers` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `discount_type` enum('percentage','fixed') NOT NULL DEFAULT 'percentage',
  `discount_value` decimal(8,2) NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `status` enum('active','inactive') NOT NULL DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `offers`
--

INSERT INTO `offers` (`id`, `title`, `description`, `discount_type`, `discount_value`, `start_date`, `end_date`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Eid e Milad Un Nabi Offer', 'It\'s a great day of Muslim Ummah.', 'percentage', 10.00, '2025-11-08', '2025-12-31', 'active', '2025-11-10 10:17:45', '2025-12-10 21:23:45');

-- --------------------------------------------------------

--
-- Table structure for table `offer_category`
--

CREATE TABLE `offer_category` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `offer_id` bigint(20) UNSIGNED NOT NULL,
  `category_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `offer_category`
--

INSERT INTO `offer_category` (`id`, `offer_id`, `category_id`) VALUES
(1, 1, 3);

-- --------------------------------------------------------

--
-- Table structure for table `offer_product`
--

CREATE TABLE `offer_product` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `offer_id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `order_number` varchar(255) NOT NULL,
  `total_price` decimal(10,2) NOT NULL,
  `payment_status` enum('paid','unpaid','partially_paid','failed') NOT NULL DEFAULT 'unpaid',
  `order_status` enum('pending','processing','completed','cancelled') NOT NULL DEFAULT 'pending',
  `download_file` varchar(255) DEFAULT NULL,
  `ordered_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `completed_at` timestamp NULL DEFAULT NULL,
  `transaction_reference` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `user_id`, `order_number`, `total_price`, `payment_status`, `order_status`, `download_file`, `ordered_at`, `completed_at`, `transaction_reference`, `created_at`, `updated_at`) VALUES
(239, 12, 'ORD-6962B02F66FE3', 24.00, 'unpaid', 'pending', 'orders/order_item_206.xlsx', '2026-01-10 20:01:51', NULL, NULL, '2026-01-10 20:01:51', '2026-01-10 20:01:51');

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `order_id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `quantity` int(11) NOT NULL,
  `unit_price` decimal(10,2) NOT NULL,
  `total_price` decimal(10,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `order_items`
--

INSERT INTO `order_items` (`id`, `order_id`, `product_id`, `quantity`, `unit_price`, `total_price`, `created_at`, `updated_at`) VALUES
(206, 239, 30, 2, 12.00, 24.00, '2026-01-10 20:01:51', '2026-01-10 20:01:51');

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
-- Table structure for table `payments`
--

CREATE TABLE `payments` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `order_id` bigint(20) UNSIGNED NOT NULL,
  `payment_method` enum('credit_card','debit_card','paypal','apple_pay','google_pay','bank_transfer','cryptocurrency','cash_on_delivery','other') NOT NULL DEFAULT 'cryptocurrency',
  `amount` decimal(10,2) NOT NULL,
  `currency` varchar(3) NOT NULL DEFAULT 'USD',
  `status` enum('pending','completed','failed','refunded') NOT NULL DEFAULT 'pending',
  `transaction_id` varchar(255) DEFAULT NULL,
  `paid_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
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
-- Table structure for table `posts`
--

CREATE TABLE `posts` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `description` text DEFAULT NULL,
  `keywords` text DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `author_id` bigint(20) UNSIGNED NOT NULL,
  `published` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `posts`
--

INSERT INTO `posts` (`id`, `title`, `slug`, `content`, `description`, `keywords`, `image`, `author_id`, `published`, `created_at`, `updated_at`) VALUES
(1, 'How to Secure Your Online Accounts in 2025', 'How-to-secure-your-online-accounts-in-2025', '<h1><strong>How to Secure Your Account: Complete Guide</strong></h1><h3><strong>1️⃣ Use Strong &amp; Unique Passwords</strong></h3><ul><li>Always use <strong>complex passwords</strong> (letters, numbers, symbols).</li><li>Avoid using the same password for multiple platforms.</li><li>Change passwords regularly.</li></ul><h3><strong>2️⃣ Enable Two-Factor Authentication (2FA)</strong></h3><p>2FA adds an extra layer of protection.<br>&nbsp;You can use:</p><ul><li><strong>Authenticator apps</strong> (Google Authenticator, Authy)</li><li><strong>SMS 2FA</strong></li><li><strong>Backup codes</strong></li></ul><p>Even if someone gets your password, they <strong>cannot access your account</strong> without the 2FA code.</p><h3><strong>3️⃣ Avoid Using Public or Shared Devices</strong></h3><p>Logging in from cyber cafés, friend’s devices, or public computers increases risk.<br>&nbsp;If you must use a shared device:</p><ul><li>Use incognito/private mode</li><li>Always log out</li><li>Do not save passwords</li></ul><h3><strong>4️⃣ Beware of Phishing Links</strong></h3><p>Hackers often send fake login pages via:</p><ul><li>Email</li><li>SMS</li><li>Social media inbox</li><li>Fake “security alerts”</li></ul><p>Always double-check the URL before entering your login details.</p><h3><strong>5️⃣ Keep Recovery Information Updated</strong></h3><p>Make sure:</p><ul><li>Your <strong>recovery email</strong> works</li><li>Your <strong>phone number</strong> is correct</li><li>You have <strong>backup codes</strong> saved in a safe place</li></ul><p>This helps you recover your account if you lose access.</p><h3><strong>6️⃣ Limit Third-Party App Permissions</strong></h3><p>Many apps request access to your social media.<br>&nbsp;Remove apps you don’t trust or no longer use.</p><h3><strong>7️⃣ Monitor Login Activity</strong></h3><p>Most platforms show:</p><ul><li>Logged-in devices</li><li>Location</li><li>Browser</li><li>Last active time</li></ul><p>If you see anything suspicious, log out all devices and reset your password immediately.</p><h3><strong>8️⃣ Keep Your Device Secure</strong></h3><ul><li>Use antivirus</li><li>Keep your phone and PC updated</li><li>Lock your device with PIN/fingerprint/Face ID</li></ul><h3><strong>9️⃣ Don’t Share Your Login Details with Anyone</strong></h3><p>Even trusted people can accidentally compromise security.<br>&nbsp;Never share your:</p><ul><li>Password</li><li>Backup codes</li><li>2FA codes</li></ul><h3><strong>🔚 Conclusion</strong></h3><p>Online security is not optional—it\'s essential. By following these simple and effective steps, you can protect your digital identity and keep your online accounts safe from any kind of unauthorized access.</p><p><br></p>', 'Why Account Security Matters\n\nIn today’s digital world, online accounts contain personal information, financial data, and communication history. Whether you manage social media accounts, business accounts, or personal profiles, keeping them secure is essential. A strong and secure account protects you from hacking, phishing, data loss, and unauthorized access.', NULL, 'post-images/01KA3H3J7DSQVH1C250HYMYRG2.webp', 1, 1, '2025-10-18 19:17:58', '2025-11-15 15:31:21'),
(2, 'How to add 2fa in reddit Account', 'how-to-add-2fa-in-reddit-account', '<p>Hii,<br>all guys is this your follow this step.</p>', 'Follow this step.', NULL, 'post-images/01KA31A39TBW64KXVDGHZ29TR8.webp', 1, 1, '2025-11-08 15:30:50', '2025-11-15 10:55:29');

-- --------------------------------------------------------

--
-- Table structure for table `privacies`
--

CREATE TABLE `privacies` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `desctiption` longtext NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `privacies`
--

INSERT INTO `privacies` (`id`, `desctiption`, `created_at`, `updated_at`) VALUES
(1, '<p><br></p><p>&nbsp;<strong>Privacy Policy – PVA ProSeller</strong></p><p>At <strong>PVA ProSeller</strong>, your privacy is important to us. We are committed to protecting your personal information and ensuring a safe experience while using our website and services.</p><p><strong>1. Information We Collect</strong></p><ul><li>We may collect:</li><li><strong>Personal Information:</strong> Email address and order details.</li><li><strong>Account Information:</strong> Login credentials for purchased accounts (temporarily for delivery).</li><li><strong>Technical Information:</strong> IP address, browser type, and device information for site functionality.</li><li><strong>Usage Information:</strong> How you interact with our website for analytics and improvements.</li></ul><p><strong>2. How We Use Your Information</strong></p><ul><li>We use your information to:</li><li>Process and deliver your orders quickly and securely.</li><li>Send updates, order confirmations, and support responses.</li><li>Improve our services and website experience.</li><li>Prevent fraud, abuse, or unauthorized access.</li></ul><p><strong>3. Payment &amp; Security</strong></p><ul><li>Payments are processed securely. We do not store full payment card or crypto wallet details.</li><li>Transaction information is used only to verify orders and prevent fraud.</li></ul><p><strong>4. Cookies &amp; Tracking</strong></p><ul><li>Cookies help improve website functionality and remember preferences.</li><li>Cookies do not store sensitive information like passwords.</li><li>You can disable cookies in your browser, but some features may not work properly.</li></ul><p><strong>5. Data Sharing</strong></p><ul><li>We <strong>never sell or trade</strong> your personal information.</li><li>Data may be shared with trusted service providers when necessary to deliver services.</li><li>We may disclose information if required by law to protect our rights.</li></ul><p><strong>6. Account Security</strong></p><ul><li>Follow our security guidelines: change passwords, enable 2FA, and store credentials safely.</li><li>This keeps accounts safe and reduces the risk of unauthorized access.</li></ul><p><strong>7. Data Retention</strong></p><ul><li>We retain personal information only as long as needed to provide services or meet legal obligations.</li><li>You can request deletion of your data by contacting us.</li></ul><p><strong>8. Children’s Privacy</strong></p><ul><li>Our services are <strong>not for children under 13 years old</strong>.</li><li>We do not knowingly collect information from children.</li></ul><p><strong>9. Your Rights</strong></p><ul><li><strong>Access:</strong> Request a copy of your personal information.</li><li><strong>Correction:</strong> Request corrections to your data.</li><li><strong>Deletion:</strong> Request deletion of your information.</li></ul><p><strong>10. Changes to This Privacy Policy</strong></p><ul><li>We may update this policy to reflect changes in services, laws, or practices. The latest version is always available on our website.</li></ul><p><strong>11. Contact Us</strong></p><ul><li>If you have any questions about your privacy or this policy, contact us at:</li><li>&nbsp;<strong>support@pvaproseller.com</strong></li></ul>', '2025-11-04 23:04:58', '2025-12-10 19:24:13');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `category_id` bigint(20) UNSIGNED NOT NULL,
  `subcategory_id` bigint(20) UNSIGNED NOT NULL,
  `feature_ids` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`feature_ids`)),
  `purchase_price` float NOT NULL,
  `selling_price` float NOT NULL,
  `stock` int(11) NOT NULL DEFAULT 0,
  `min_order_qty` int(11) NOT NULL DEFAULT 10,
  `product_image` varchar(255) DEFAULT NULL,
  `keywords` text DEFAULT NULL,
  `description` text DEFAULT NULL,
  `content` longtext DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `google_sheet_url` varchar(255) DEFAULT NULL,
  `google_sheet_id` varchar(255) DEFAULT NULL,
  `sheet_edit_url` varchar(255) DEFAULT NULL,
  `sheet_meta` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`sheet_meta`)),
  `sheet_hash` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `slug`, `category_id`, `subcategory_id`, `feature_ids`, `purchase_price`, `selling_price`, `stock`, `min_order_qty`, `product_image`, `keywords`, `description`, `content`, `created_at`, `updated_at`, `google_sheet_url`, `google_sheet_id`, `sheet_edit_url`, `sheet_meta`, `sheet_hash`) VALUES
(30, 'tictok ban', 'tictok', 5, 11, '[\"3\"]', 10, 12, 5, 2, NULL, 'social media,digital marketing,online presence,content creation,influencer marketing,brand awareness,social networking,viral content,audience engagement,social growth,online branding,content strategy,social promotion,marketing campaigns', 'social media, digital marketing, online presence, content creation, influencer marketing, brand awareness, social networking, viral content, audience engagement, social growth, online branding, content strategy, social promotion, marketing campaigns,social media, digital marketing, online presence, content creation, influencer marketing, brand awareness, social networking, viral content, audience engagement, social growth, online branding, content strategy, social promotion, marketing campaigns,', '<p>&nbsp;social media, digital marketing, online presence, content creation, influencer marketing, brand awareness, social networking, viral content, audience engagement, social growth, online branding, content strategy, social promotion, marketing campaigns,&nbsp; social media, digital marketing, online presence, content creation, influencer marketing, brand awareness, social networking, viral content, audience engagement, social growth, online branding, content strategy, social promotion, marketing campaigns,&nbsp; social media, digital marketing, online presence, content creation, influencer marketing, brand awareness, social networking, viral content, audience engagement, social growth, online branding, content strategy, social promotion, marketing campaigns,&nbsp; social media, digital marketing, online presence, content creation, influencer marketing, brand awareness, social networking, viral content, audience engagement, social growth, online branding, content strategy, social promotion, marketing campaigns,&nbsp; social media, digital marketing, online presence, content creation, influencer marketing, brand awareness, social networking, viral content, audience engagement, social growth, online branding, content strategy, social promotion, marketing campaigns,&nbsp;</p>', '2025-12-23 18:14:41', '2026-01-10 20:01:51', 'https://docs.google.com/spreadsheets/d/e/2PACX-1vQv5wCblR0u45r6kESCwQ0DxkPSvuoRPbPnF6lY8Zco9uL7wYdcACTP-IQqA4T5KI4gIleNWeVjv_7g/pub?output=csv', '1GEvserxVEJKJScwfOGILvxxF1JhGrPCaqUl4dGXMMVU', NULL, '{\"headers\":[\"email\",\"mail pawssord\",\"neme\",\"uid\",\"Tiktok pawssord\",\"Profail link\"],\"row_count\":54,\"synced_at\":\"2026-01-05T18:25:35.273026Z\"}', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `product_accounts`
--

CREATE TABLE `product_accounts` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `email` varchar(255) NOT NULL,
  `meta` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`meta`)),
  `status` varchar(255) NOT NULL DEFAULT 'unsold',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `product_accounts`
--

INSERT INTO `product_accounts` (`id`, `product_id`, `email`, `meta`, `status`, `created_at`, `updated_at`) VALUES
(419, 30, 'ameliawilliams5332@mail.com', '{\"email\":\"AmeliaWilliams5332@mail.com\",\"mail pawssord\":\"RfeZwmVpTyOSg\",\"neme\":\"Jeremy Quinn\",\"uid\":\"jeremyquinn55\",\"Tiktok pawssord\":\"nQ2VzuXAu9mLgJpi@\",\"Profail link\":\"https:\\/\\/www.tiktok.com\\/@jeremyquinn55\"}', 'sold', '2026-01-10 19:51:28', '2026-01-10 20:01:51'),
(420, 30, 'ellahernandez5632@mail.com', '{\"email\":\"EllaHernandez5632@mail.com\",\"mail pawssord\":\"dnFrNrEcYgcMR\",\"neme\":\"Heather Johnson\",\"uid\":\"heatherjohnson05\",\"Tiktok pawssord\":\"hsBvEV04ERKIluCQ@\",\"Profail link\":\"https:\\/\\/www.tiktok.com\\/@heatherjohnson05\"}', 'unsold', '2026-01-10 19:51:28', '2026-01-10 19:51:28'),
(421, 30, 'avajones445@mail.com', '{\"email\":\"AvaJones445@mail.com\",\"mail pawssord\":\"bDuhxmhtEqnoR\",\"neme\":\"Kenneth Garcia\",\"uid\":\"kennethgarcia85\",\"Tiktok pawssord\":\"6BKqBx6jkLxa7rZA@\",\"Profail link\":\"https:\\/\\/www.tiktok.com\\/@kennethgarcia85\"}', 'sold', '2026-01-10 19:51:28', '2026-01-10 20:01:51'),
(422, 30, 'charlottemoore622@mail.com', '{\"email\":\"CharlotteMoore622@mail.com\",\"mail pawssord\":\"rTDZXTPMHfDuU\",\"neme\":\"Heather Ibarra\",\"uid\":\"heatheribarra28\",\"Tiktok pawssord\":\"uCZC15Ws8bsLAB5j@\",\"Profail link\":\"https:\\/\\/www.tiktok.com\\/@heatheribarra28\"}', 'unsold', '2026-01-10 19:51:28', '2026-01-10 19:51:28'),
(423, 30, 'charlottegonzalez7345@mail.com', '{\"email\":\"CharlotteGonzalez7345@mail.com\",\"mail pawssord\":\"MHlLlbqpqDyBz\",\"neme\":\"Martin Garcia\",\"uid\":\"marting7arcia4\",\"Tiktok pawssord\":\"ilEea5tpRCX73E3a@\",\"Profail link\":\"https:\\/\\/www.tiktok.com\\/@marting7arcia4\"}', 'unsold', '2026-01-10 19:51:28', '2026-01-10 19:51:28'),
(424, 30, 'noraanderson562@mail.com', '{\"email\":\"NoraAnderson562@mail.com\",\"mail pawssord\":\"zbeVpzovpwmXk\",\"neme\":\"Brianna Collins\",\"uid\":\"briannacollins04\",\"Tiktok pawssord\":\"DKlEXBTdMiSPeVaG5@\",\"Profail link\":\"https:\\/\\/www.tiktok.com\\/@briannacollins04\"}', 'unsold', '2026-01-10 19:51:28', '2026-01-10 19:51:28'),
(425, 30, 'avasmith5632@mail.com', '{\"email\":\"AvaSmith5632@mail.com\",\"mail pawssord\":\"awRtlXkjIDcRs\",\"neme\":\"Jeffrey Hensley\",\"uid\":\"jeffreyhensley5\",\"Tiktok pawssord\":\"KjGEgUfoRA1mqaN4@\",\"Profail link\":\"https:\\/\\/www.tiktok.com\\/@jeffreyhensley5\"}', 'unsold', '2026-01-10 19:51:28', '2026-01-10 19:51:28');

-- --------------------------------------------------------

--
-- Table structure for table `product_features`
--

CREATE TABLE `product_features` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `product_features`
--

INSERT INTO `product_features` (`id`, `name`, `created_at`, `updated_at`) VALUES
(2, 'Phone Varified', '2025-11-11 00:22:44', '2025-11-11 00:22:44'),
(3, 'Email VArified', '2025-11-11 00:23:04', '2025-11-11 00:23:04'),
(4, 'Premium Quality', '2025-11-11 00:23:19', '2025-11-11 00:23:19'),
(5, '2FA', '2025-11-11 00:27:12', '2025-11-11 00:27:12'),
(6, 'Cookie', '2025-11-11 00:29:01', '2025-11-11 00:29:01');

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('DCaxwXMdsbZiJZc7qDN5wdyXWAVqdFToAyCwTeCx', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoicXQwQWI3R2Z3YTd0aU1xTlNHR1Z0Y3U4TDFDcGp6R2ZaUzBGY0V0dSI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mzk6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC90ZXN0LWdvb2dsZS1zaGVldCI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1768068319),
('fUCN55NcV5Ega1V2yujWsQdu9oGAQyQflLrcDWGY', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'YTo3OntzOjY6Il90b2tlbiI7czo0MDoieEhlWVBPNEpKT216WW85UXNQaVY2bXNETE91cXlWUjFsMDhMR0hWaCI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6NDQ6Imh0dHA6Ly9idWxrYWNjb3VudHNlbGxlci50ZXN0L2FkbWluL3Byb2R1Y3RzIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czozOiJ1cmwiO2E6MDp7fXM6NTA6ImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjtpOjE7czoxNzoicGFzc3dvcmRfaGFzaF93ZWIiO3M6NjA6IiQyeSQxMiRXd2doVmJyZy84b01Way9mWWxwcmN1N0VYSUhKM0suckhPN0svVWJBb0o5VEc4MnVxNkFMRyI7czo4OiJmaWxhbWVudCI7YTowOnt9fQ==', 1768072500),
('IQ5ODzgfXe2IWYDGhuyb7dUdy1YfcGDIQFvtNzMb', 12, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'YTo2OntzOjY6Il90b2tlbiI7czo0MDoiYjBmdENuc0d2aW9CMWpGamE5QVhad01uVnFzbEh3SDJyekpQQjNzRSI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6NDQ6Imh0dHA6Ly9idWxrYWNjb3VudHNlbGxlci50ZXN0L2FkbWluL3Byb2R1Y3RzIjt9czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6MTI7czoxNzoicGFzc3dvcmRfaGFzaF93ZWIiO3M6NjA6IiQyeSQxMiRBejM0WXNyeW1IQ1M2ZTExOEhtSjguc2ZhT0lGSUtydGpra1AzekNLemE3aHRndXdtLmdKcSI7czo4OiJmaWxhbWVudCI7YTowOnt9fQ==', 1768075321);

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE `settings` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `website_name` varchar(255) NOT NULL,
  `phone` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `favicon` varchar(255) DEFAULT NULL,
  `logo` varchar(255) DEFAULT NULL,
  `f_link` varchar(255) DEFAULT NULL,
  `i_link` varchar(255) DEFAULT NULL,
  `t_link` varchar(255) DEFAULT NULL,
  `y_link` varchar(255) DEFAULT NULL,
  `tw_link` varchar(255) DEFAULT NULL,
  `lnkd_link` varchar(255) DEFAULT NULL,
  `pre_order_link` varchar(255) DEFAULT NULL,
  `sup_wa_link` varchar(255) DEFAULT NULL,
  `sup_tele_link` varchar(255) DEFAULT NULL,
  `manual_pay_qr` varchar(255) DEFAULT NULL,
  `manual_pay_wallet` varchar(255) DEFAULT NULL,
  `address` text NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `settings`
--

INSERT INTO `settings` (`id`, `website_name`, `phone`, `email`, `favicon`, `logo`, `f_link`, `i_link`, `t_link`, `y_link`, `tw_link`, `lnkd_link`, `pre_order_link`, `sup_wa_link`, `sup_tele_link`, `manual_pay_qr`, `manual_pay_wallet`, `address`, `created_at`, `updated_at`) VALUES
(1, 'PVA ProSeller', '+1 539 323 0185', 'contact@pvaproseller.com', 'fabicon/01KA6Z3TW309VXYNGECMYTECZ6.png', 'logo/01KA81BJ11WX71YD7R8W4SSQZY.png', 'https://www.facebook.com/Pvaproseller/', 'https://www.instagram.com/mr.thank.you/', 'https://t.me/Pvaproseller', 'https://www.youtube.com/@PVAProSeller', 'https://twitter.com/fsrfsdrgdsf/', 'https://ng.linkedin.com/company/hos-consult-ltd', 'https://t.me/Pvaproseller', NULL, NULL, 'pay_qr/01KCCK8VS7ANB118KX81E2AJYH.jpeg', NULL, '125 Remington Valley Dr,\nHouston, TX 77073,\nUnited States', '2025-10-17 17:23:46', '2025-12-13 19:33:45');

-- --------------------------------------------------------

--
-- Table structure for table `sub_categories`
--

CREATE TABLE `sub_categories` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `category_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `keywords` text DEFAULT NULL,
  `description` text DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `order` int(11) NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sub_categories`
--

INSERT INTO `sub_categories` (`id`, `category_id`, `name`, `slug`, `keywords`, `description`, `image`, `order`, `is_active`, `created_at`, `updated_at`) VALUES
(4, 3, 'Telegram Account', 'messaging-account', 'messaging account,buy messaging account,whatsapp account,telegram account,signal account,viber account,wechat account,line account,kik account,discord account,google voice account,textnow account,sideline account,skype account,verified messaging account,pva messaging account,bulk messaging account,buy verified messaging,cheap messaging account', 'Buy verified messaging accounts from Pva ProSeller including WhatsApp, Google Voice, Telegram, TextNow, Discord, Sideline, Signal, WeChat, and Skype. All accounts are phone-verified, secure, and ready to use for personal or business purposes. Fast delivery and trusted service guaranteed.', 'subcategories/01K9WK9QNB28WAJQM58S9TT5T4.png', 3, 1, '2025-11-08 12:51:13', '2025-11-12 22:54:59'),
(5, 3, 'Whatsapp Account', 'whatsapp-account', 'whatsapp account,buy whatsapp account,verified whatsapp account,pva whatsapp account,bulk whatsapp account,phone verified whatsapp,cheap whatsapp account,business whatsapp account,whatsapp pva account,buy verified whatsapp', 'Buy verified WhatsApp accounts from Pva ProSeller. All accounts are phone-verified, secure, and ready to use for personal or business purposes. Get high-quality PVA WhatsApp accounts instantly with fast delivery and trusted service.', 'subcategories/01K9WKKK6T3SA2YKSC5T28V4RY.png', 2, 1, '2025-11-08 12:53:12', '2025-11-12 23:00:22'),
(6, 5, 'Facebook Account', 'facebook-account', 'buy verified facebook,social media,digital marketing,content creation,online branding,engagement,trending topics,viral content,audience growth,creative strategy,visual storytelling,reels,shorts,posts,analytics,reach,impressions,community building,brand awareness,consistency,innovation', 'Buy verified Facebook accounts from Pva ProSeller. All accounts are phone-verified, secure, and ready to use for personal or business purposes. Get high-quality PVA Facebook accounts instantly with fast delivery and trusted service.\n', 'subcategories/01K9WKTSE5DW9EA5VDXGWS4HZM.png', 0, 1, '2025-11-10 23:53:21', '2025-12-13 19:52:50'),
(7, 5, 'Instagram Account', 'instagram-account', 'instagram account,buy instagram account,verified instagram account,pva instagram account,bulk instagram account,cheap instagram account,instagram business account,instagram profile account,buy verified instagram,instagram account seller', 'Buy verified Instagram accounts from Pva ProSeller. All accounts are phone-verified, secure, and ready to use for personal or business purposes. Get high-quality PVA Instagram accounts instantly with fast delivery and trusted service.\n', 'subcategories/01K9WKZDCJ314FA1X9FQ68A6VV.png', 5, 1, '2025-11-10 23:53:52', '2025-11-12 23:06:49'),
(8, 5, 'Threads Account', 'threads-account', 'threads account,buy threads account,verified threads account,pva threads account,bulk threads account,cheap threads account,threads business account,threads profile account,buy verified threads,threads account seller', 'Buy verified Threads accounts from Pva ProSeller. All accounts are phone-verified, secure, and ready to use for personal or business purposes. Get high-quality PVA Threads accounts instantly with fast delivery and trusted service.\n', 'subcategories/01K9WM37NTBKPHZ2VXG0RV5V9K.png', 4, 1, '2025-11-10 23:54:25', '2025-11-12 23:08:54'),
(9, 5, 'Snapchat Account', 'snapchat-account', 'snapchat account,buy snapchat account,verified snapchat account,pva snapchat account,bulk snapchat account,cheap snapchat account,snapchat business account,snapchat profile account,buy verified snapchat,snapchat account seller', 'Buy verified Snapchat accounts from Pva ProSeller. All accounts are phone-verified, secure, and ready to use for personal or business purposes. Get high-quality PVA Snapchat accounts instantly with fast delivery and trusted service.\n', 'subcategories/01K9WM6Z0NSQJKA85JECQQWCTG.png', 5, 1, '2025-11-10 23:54:51', '2025-11-12 23:10:57'),
(10, 5, 'Twitter Account', 'twitter-account', 'twitter account,buy twitter account,verified twitter account,pva twitter account,bulk twitter account,cheap twitter account,twitter business account,twitter profile account,buy verified twitter,twitter account seller', 'Buy verified Twitter accounts from Pva ProSeller. All accounts are phone-verified, secure, and ready to use for personal or business purposes. Get high-quality PVA Twitter accounts instantly with fast delivery and trusted service.\n', 'subcategories/01K9WM9PPKRQD6VM8QN7KZ2GQY.png', 0, 1, '2025-11-10 23:55:18', '2025-11-12 23:12:26'),
(11, 5, 'TikTok Account', 'tiktok-account', 'tiktok account,buy tiktok account,verified tiktok account,pva tiktok account,bulk tiktok account,cheap tiktok account,tiktok business account,tiktok profile account,buy verified tiktok,tiktok account seller', 'Buy verified TikTok accounts from Pva ProSeller. All accounts are phone-verified, secure, and ready to use for personal or business purposes. Get high-quality PVA TikTok accounts instantly with fast delivery and trusted service.\n', 'subcategories/01K9WMC9YTT6DXZA5T9RNPH9Z7.png', 9, 1, '2025-11-10 23:55:46', '2025-11-12 23:13:52'),
(12, 5, 'LinkedIn Account', 'linkedin-account', 'linkedin account,buy linkedin account,verified linkedin account,pva linkedin account,bulk linkedin account,cheap linkedin account,linkedin business account,linkedin profile account,buy verified linkedin,linkedin account seller', 'Buy verified LinkedIn accounts from Pva ProSeller. All accounts are phone-verified, secure, and ready to use for personal or business purposes. Get high-quality PVA LinkedIn accounts instantly with fast delivery and trusted service.\n', 'subcategories/01K9WMEE88TM4E8KW68794YA28.png', 6, 1, '2025-11-10 23:56:15', '2025-11-12 23:15:02'),
(13, 5, 'Reddit Account', 'reddit-account', 'reddit account,buy reddit account,verified reddit account,pva reddit account,bulk reddit account,cheap reddit account,reddit business account,reddit profile account,buy verified reddit,reddit account seller', 'Buy verified Reddit accounts from Pva ProSeller. All accounts are phone-verified, secure, and ready to use for personal or business purposes. Get high-quality PVA Reddit accounts instantly with fast delivery and trusted service.\n', 'subcategories/01K9WMGHS5M23KENYF0M8GFRXA.png', 7, 1, '2025-11-10 23:56:42', '2025-11-12 23:16:11'),
(14, 5, 'Quora Account', 'quora-account', 'quora account,buy quora account,verified quora account,pva quora account,bulk quora account,cheap quora account,quora business account,quora profile account,buy verified quora,quora account seller', 'Buy verified Quora accounts from Pva ProSeller. All accounts are phone-verified, secure, and ready to use for personal or business purposes. Get high-quality PVA Quora accounts instantly with fast delivery and trusted service.\n', 'subcategories/01K9WMM39WBFMM6Z998RE26JZH.png', 8, 1, '2025-11-10 23:57:13', '2025-11-12 23:18:07'),
(15, 5, 'Pinterest Account', 'pinterest-account', 'pinterest account,buy pinterest account,verified pinterest account,pva pinterest account,bulk pinterest account,cheap pinterest account,pinterest business account,pinterest profile account,buy verified pinterest,pinterest account seller', 'Buy verified Pinterest accounts from Pva ProSeller. All accounts are phone-verified, secure, and ready to use for personal or business purposes. Get high-quality PVA Pinterest accounts instantly with fast delivery and trusted service.\n', 'subcategories/01K9WMPM9HPDVWS3YCMZ4R324P.png', 0, 1, '2025-11-10 23:57:41', '2025-11-12 23:19:30'),
(16, 5, 'Yelp Account', 'yelp-account', 'yelp account,buy yelp account,verified yelp account,pva yelp account,bulk yelp account,cheap yelp account,yelp business account,yelp profile account,buy verified yelp,yelp account seller', 'Buy verified Yelp accounts from Pva ProSeller. All accounts are phone-verified, secure, and ready to use for personal or business purposes. Get high-quality PVA Yelp accounts instantly with fast delivery and trusted service.\n', 'subcategories/01K9WMTR4Y1VQHT6VR4GND4HPH.png', 9, 1, '2025-11-10 23:58:15', '2025-11-12 23:21:45'),
(17, 5, 'Vkontake', 'vkontake', 'vkontake', 'vkontake', NULL, 0, 1, '2025-11-10 23:58:49', '2025-11-10 23:58:49'),
(18, 5, 'Classmate', 'classmate', 'classmate', 'classmate', NULL, 0, 1, '2025-11-10 23:59:15', '2025-11-10 23:59:15'),
(19, 5, 'Twitch TV', 'twitch-tv', 'twitchtv', 'twitchtv', NULL, 0, 1, '2025-11-11 00:00:05', '2025-11-11 00:00:05'),
(27, 3, 'Google Voice', 'google-voice', 'Google Voice', 'Google Voice', NULL, 0, 1, '2025-11-11 00:07:22', '2025-11-11 00:07:22'),
(28, 3, 'TextNow', 'textnow', 'TextNow', 'TextNow', NULL, 0, 1, '2025-11-11 00:07:47', '2025-11-11 00:07:47'),
(29, 3, 'Discord', 'discord', 'Discord', 'Discord', NULL, 0, 1, '2025-11-11 00:08:11', '2025-11-11 00:08:11'),
(30, 3, 'Sideline', 'sideline', 'Sideline', 'Sideline', NULL, 0, 1, '2025-11-11 00:08:29', '2025-11-11 00:08:29'),
(31, 3, 'Signal', 'signal', 'Signal', 'Signal', NULL, 0, 1, '2025-11-11 00:08:47', '2025-11-11 00:08:47'),
(32, 3, 'WeChat', 'wechat', 'WeChat', 'WeChat', NULL, 0, 1, '2025-11-11 00:09:08', '2025-11-11 00:09:08'),
(33, 3, 'Skype', 'skype', 'Skype', 'Skype', NULL, 0, 1, '2025-11-11 00:09:24', '2025-11-11 00:09:24'),
(34, 8, 'TeraBox', 'teraBox', 'TeraBox', 'TeraBox', NULL, 0, 1, '2025-11-11 00:10:31', '2025-11-11 00:10:31'),
(35, 8, 'Dropbox Plus / Professional', 'dropbox-plus-professional', 'Dropbox Plus / Professional', 'Dropbox Plus / Professional', NULL, 0, 1, '2025-11-11 00:11:02', '2025-11-11 00:11:02'),
(36, 8, 'Microsoft OneDrive 365', 'microsoft-onedrive365', 'Microsoft OneDrive 365', 'Microsoft OneDrive 365', NULL, 0, 1, '2025-11-11 00:11:44', '2025-11-11 00:11:44'),
(37, 8, 'Apple iCloud+', 'apple-icloud+', 'Apple iCloud+', 'Apple iCloud+', NULL, 0, 1, '2025-11-11 00:12:12', '2025-11-11 00:12:12'),
(38, 8, 'Canva', 'canva', 'Canva', 'Canva', NULL, 0, 1, '2025-11-11 00:12:41', '2025-11-11 00:12:41'),
(39, 8, 'ChatGPT', 'chatgpt', 'ChatGPT', 'ChatGPT', NULL, 0, 1, '2025-11-11 00:13:03', '2025-11-11 00:13:03'),
(40, 8, 'Grammarly', 'grammarly', 'Grammarly', 'Grammarly', NULL, 0, 1, '2025-11-11 00:13:22', '2025-11-11 00:13:22'),
(41, 8, 'Quillbot', 'quillbot', 'Quillbot', 'Quillbot', NULL, 0, 1, '2025-11-11 00:13:38', '2025-11-11 00:13:38'),
(42, 6, 'Netflix', 'netflix', 'Netflix', 'Netflix', NULL, 0, 1, '2025-11-11 00:14:09', '2025-11-11 00:14:09'),
(43, 6, 'Amazon Prime Video', 'amazon-prime-video', 'Amazon Prime Video', 'Amazon Prime Video', NULL, 0, 1, '2025-11-11 00:14:39', '2025-11-11 00:14:39'),
(44, 6, 'Disney+', 'disney+', 'Disney+', 'Disney+', NULL, 0, 1, '2025-11-11 00:15:07', '2025-11-11 00:15:07'),
(45, 6, 'Spotify', 'spotify', 'Spotify', 'Spotify', NULL, 0, 1, '2025-11-11 00:15:24', '2025-11-11 00:15:24'),
(46, 6, 'YouTube Premium', 'youtube-premium', 'YouTube Premium', 'YouTube Premium', NULL, 0, 1, '2025-11-11 00:15:59', '2025-11-11 00:15:59'),
(47, 6, 'Hulu', 'hulu', 'Hulu', 'Hulu', NULL, 0, 1, '2025-11-11 00:16:16', '2025-11-11 00:16:16'),
(48, 7, 'Amazone.com', 'amazone.com', 'Amazone.com', 'Amazone.com', NULL, 0, 1, '2025-11-11 00:17:30', '2025-11-11 00:17:30'),
(49, 7, 'Ebay.com', 'ebay.com', 'Ebay.com', 'Ebay.com', NULL, 0, 1, '2025-11-11 00:17:49', '2025-11-11 00:17:49'),
(50, 7, 'Aliexpress', 'aliexpress', 'Aliexpress', 'Aliexpress', NULL, 0, 1, '2025-11-11 00:18:15', '2025-11-11 00:18:15'),
(51, 9, 'Gmail Account', 'gmail-account', 'gmail account,buy gmail account,verified gmail account,pva gmail account,bulk gmail account,cheap gmail account,old gmail account,fresh gmail account,gmail business account,gmail account seller,buy pva gmail', 'Buy verified Gmail accounts from Pva ProSeller. All accounts are phone-verified, secure, and ready to use for personal, marketing, or business purposes. Get high-quality PVA Gmail accounts instantly with fast delivery and trusted service.\n', 'subcategories/01K9WPMVN3839C68624FYCKWJZ.png', 2, 1, '2025-11-12 23:53:29', '2025-11-12 23:53:29');

-- --------------------------------------------------------

--
-- Table structure for table `terms`
--

CREATE TABLE `terms` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `terms` longtext NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `terms`
--

INSERT INTO `terms` (`id`, `terms`, `created_at`, `updated_at`) VALUES
(2, '<h1><strong>Terms &amp; Conditions – PVA ProSeller</strong></h1><p>Welcome to <strong>PVA ProSeller</strong>. By using our website and services, you agree to follow these Terms &amp; Conditions. These rules are designed to ensure a safe, secure, and reliable experience for everyone.</p><h2><strong>1. Eligibility</strong></h2><ul><li>You must be <strong>13 years or older</strong> (or meet your country’s minimum age) to use our services.</li><li>By using our platform, you confirm that you are legally allowed to buy and manage digital accounts.</li></ul><h2><strong>2. Services Provided</strong></h2><ul><li>PVA ProSeller provides <strong>verified digital accounts</strong> for social media, email, communication apps, streaming platforms, software tools, cloud storage, and other digital services.</li><li>All accounts are delivered <strong>instantly via email</strong> in Excel format.</li><li>Custom orders are available by contacting our support team.</li></ul><h2><strong>3. User Responsibilities</strong></h2><ul><li>Keep your account login details safe and private.</li><li>Do not share your account with unauthorized users.</li><li>Use accounts responsibly and ethically for personal or business purposes.</li><li>Follow the rules of the platforms associated with the accounts.</li></ul><h2><strong>4. Payment &amp; Pricing</strong></h2><ul><li>Payments are processed securely via supported cryptocurrencies.</li><li>Prices are listed per account or package and may vary depending on type (PVA, non-PVA, fresh, aged, etc.).</li><li>Bulk orders may qualify for <strong>special discounts</strong> — contact support for details.</li></ul><h2><strong>5. Delivery &amp; Automation</strong></h2><ul><li>Orders are delivered <strong>automatically and instantly</strong> after payment.</li><li>Delivery is done via <strong>email in Excel format</strong>.</li><li>PVA ProSeller is not responsible for delays caused by email providers or network issues.</li></ul><h2><strong>6. Refund &amp; Replacement Policy</strong></h2><ul><li><strong>Refunds are not provided.</strong></li><li>If an account does not work, we provide a <strong>free replacement</strong>.</li><li>Contact support immediately to receive a replacement account.</li></ul><h2><strong>7. Account Security &amp; Usage</strong></h2><ul><li>Change passwords and enable 2FA for maximum security.</li><li>For multiple accounts, using <strong>proxies or RDP</strong> is recommended.</li><li>PVA ProSeller is not responsible for bans or restrictions caused by violating platform rules or improper usage.</li></ul><h2><strong>8. Limitation of Liability</strong></h2><ul><li>PVA ProSeller is not liable for any direct or indirect losses from using the accounts.</li><li>Users agree to use accounts at their own risk while following our security guidelines.</li><li>We are not responsible for content, actions, or restrictions imposed by third-party platforms.</li></ul><h2><strong>9. Intellectual Property</strong></h2><ul><li>All website content, including logos, text, and graphics, is owned by PVA ProSeller or its partners.</li><li>Users may not reproduce or redistribute our content without permission.</li></ul><h2><strong>10. Changes to Terms</strong></h2><ul><li>We may update these Terms &amp; Conditions at any time.</li><li>Updated terms will be posted on the website. Continued use of services indicates acceptance.</li></ul><h2><strong>11. Governing Law</strong></h2><ul><li>These Terms &amp; Conditions are governed by the laws of the country where PVA ProSeller operates.</li><li>Any disputes will be handled under local jurisdiction.</li></ul><h2><strong>12. Legal Disclaimer</strong></h2><ul><li>Some platforms may restrict account transfers or sales. PVA ProSeller provides accounts <strong>for legal, personal, or business purposes only</strong>.</li><li>Users are responsible for following the terms of the platforms from which accounts are purchased.</li><li>Using our services implies acceptance of these responsibilities.</li></ul><h2><strong>13. Contact Information</strong></h2><ul><li>For any questions regarding these Terms &amp; Conditions, contact us at:</li><li>&nbsp;<strong>support@pvaproseller.com</strong></li></ul><p><br></p>', '2025-11-10 23:45:55', '2025-12-10 21:20:01');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `role_id` int(20) NOT NULL DEFAULT 2,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `two_factor_secret` text DEFAULT NULL,
  `two_factor_recovery_codes` text DEFAULT NULL,
  `two_factor_confirmed_at` timestamp NULL DEFAULT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `current_team_id` bigint(20) UNSIGNED DEFAULT NULL,
  `profile_photo_path` varchar(2048) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `role_id`, `email`, `email_verified_at`, `password`, `two_factor_secret`, `two_factor_recovery_codes`, `two_factor_confirmed_at`, `remember_token`, `current_team_id`, `profile_photo_path`, `created_at`, `updated_at`) VALUES
(1, 'Test User', 1, 'test@example.com', '2025-10-17 16:14:30', '$2y$12$WwghVbrg/8oMVk/fYlprcu7EXIHJ3K.rHO7K/UbAoJ9TG82uq6ALG', NULL, NULL, NULL, 'W7gRvzhNOJE6bQuzTJusLhv7JIU7NrFpg2GmUohxg9QqHeMdt5RDbp1uIVgG', NULL, NULL, '2025-10-17 16:14:30', '2025-10-17 16:14:30'),
(12, 'rafsan', 2, 'rafsan@gmail.com', '2025-11-26 12:48:07', '$2y$12$Az34YsrymHCS6e118HmJ8.sfaOIFIKrtjkkP3zCKza7htguwm.gJq', NULL, NULL, NULL, NULL, NULL, NULL, '2025-11-26 12:48:07', '2025-11-26 12:48:07'),
(14, 'demo', 2, 'demo@gmail.com', '2025-12-10 10:11:58', '$2y$12$LBpV3tYuwes9bHJ40drB6uKaiq40t.rj1EgRKKtaofVkDR/pl9tOS', NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-10 10:11:58', '2025-12-10 10:11:58');

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
-- Indexes for table `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `categories_slug_unique` (`slug`);

--
-- Indexes for table `deliveries`
--
ALTER TABLE `deliveries`
  ADD PRIMARY KEY (`id`),
  ADD KEY `deliveries_order_item_id_foreign` (`order_item_id`);

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
-- Indexes for table `guidelines`
--
ALTER TABLE `guidelines`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Indexes for table `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `offers`
--
ALTER TABLE `offers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `offer_category`
--
ALTER TABLE `offer_category`
  ADD PRIMARY KEY (`id`),
  ADD KEY `offer_category_offer_id_foreign` (`offer_id`),
  ADD KEY `offer_category_category_id_foreign` (`category_id`);

--
-- Indexes for table `offer_product`
--
ALTER TABLE `offer_product`
  ADD PRIMARY KEY (`id`),
  ADD KEY `offer_product_offer_id_foreign` (`offer_id`),
  ADD KEY `offer_product_product_id_foreign` (`product_id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `orders_order_number_unique` (`order_number`),
  ADD KEY `orders_user_id_foreign` (`user_id`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_items_order_id_foreign` (`order_id`),
  ADD KEY `order_items_product_id_foreign` (`product_id`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `payments_transaction_id_unique` (`transaction_id`),
  ADD KEY `payments_order_id_foreign` (`order_id`);

--
-- Indexes for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`);

--
-- Indexes for table `posts`
--
ALTER TABLE `posts`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `posts_slug_unique` (`slug`),
  ADD KEY `posts_author_id_foreign` (`author_id`);

--
-- Indexes for table `privacies`
--
ALTER TABLE `privacies`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `products_slug_unique` (`slug`),
  ADD KEY `products_category_id_foreign` (`category_id`),
  ADD KEY `products_subcategory_id_foreign` (`subcategory_id`);

--
-- Indexes for table `product_accounts`
--
ALTER TABLE `product_accounts`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `product_accounts_product_id_email_unique` (`product_id`,`email`);

--
-- Indexes for table `product_features`
--
ALTER TABLE `product_features`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sub_categories`
--
ALTER TABLE `sub_categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `sub_categories_slug_unique` (`slug`),
  ADD KEY `sub_categories_category_id_foreign` (`category_id`);

--
-- Indexes for table `terms`
--
ALTER TABLE `terms`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `abouts`
--
ALTER TABLE `abouts`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `banners`
--
ALTER TABLE `banners`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `deliveries`
--
ALTER TABLE `deliveries`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `faqs`
--
ALTER TABLE `faqs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- AUTO_INCREMENT for table `guidelines`
--
ALTER TABLE `guidelines`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `offers`
--
ALTER TABLE `offers`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `offer_category`
--
ALTER TABLE `offer_category`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `offer_product`
--
ALTER TABLE `offer_product`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=240;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=207;

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `posts`
--
ALTER TABLE `posts`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `privacies`
--
ALTER TABLE `privacies`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT for table `product_accounts`
--
ALTER TABLE `product_accounts`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=426;

--
-- AUTO_INCREMENT for table `product_features`
--
ALTER TABLE `product_features`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `settings`
--
ALTER TABLE `settings`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `sub_categories`
--
ALTER TABLE `sub_categories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=52;

--
-- AUTO_INCREMENT for table `terms`
--
ALTER TABLE `terms`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `deliveries`
--
ALTER TABLE `deliveries`
  ADD CONSTRAINT `deliveries_order_item_id_foreign` FOREIGN KEY (`order_item_id`) REFERENCES `order_items` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `offer_category`
--
ALTER TABLE `offer_category`
  ADD CONSTRAINT `offer_category_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `offer_category_offer_id_foreign` FOREIGN KEY (`offer_id`) REFERENCES `offers` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `offer_product`
--
ALTER TABLE `offer_product`
  ADD CONSTRAINT `offer_product_offer_id_foreign` FOREIGN KEY (`offer_id`) REFERENCES `offers` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `offer_product_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `order_items_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `payments`
--
ALTER TABLE `payments`
  ADD CONSTRAINT `payments_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `posts`
--
ALTER TABLE `posts`
  ADD CONSTRAINT `posts_author_id_foreign` FOREIGN KEY (`author_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `products_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `products_subcategory_id_foreign` FOREIGN KEY (`subcategory_id`) REFERENCES `sub_categories` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `product_accounts`
--
ALTER TABLE `product_accounts`
  ADD CONSTRAINT `product_accounts_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `sub_categories`
--
ALTER TABLE `sub_categories`
  ADD CONSTRAINT `sub_categories_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
