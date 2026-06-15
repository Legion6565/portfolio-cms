-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Хост: MySQL-8.4:3306
-- Время создания: Июн 15 2026 г., 17:55
-- Версия сервера: 8.4.7
-- Версия PHP: 8.5.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `portfolio_cms`
--

-- --------------------------------------------------------

--
-- Структура таблицы `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` bigint NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` bigint NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` smallint UNSIGNED NOT NULL,
  `reserved_at` int UNSIGNED DEFAULT NULL,
  `available_at` int UNSIGNED NOT NULL,
  `created_at` int UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_jobs` int NOT NULL,
  `pending_jobs` int NOT NULL,
  `failed_jobs` int NOT NULL,
  `failed_job_ids` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `options` mediumtext COLLATE utf8mb4_unicode_ci,
  `cancelled_at` int DEFAULT NULL,
  `created_at` int NOT NULL,
  `finished_at` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `migrations`
--

CREATE TABLE `migrations` (
  `id` int UNSIGNED NOT NULL,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2026_06_08_062805_create_projects_table', 1),
(5, '2026_06_08_072623_add_fields_to_projects_table', 2),
(6, '2026_06_08_124751_add_short_description_to_projects_table', 3),
(7, '2026_06_11_120514_create_project_sections_table', 4),
(8, '2026_06_11_124901_add_meta_to_project_sections_table', 5),
(9, '2026_06_12_000000_make_description_nullable_on_projects_table', 6),
(10, '2026_06_12_000001_drop_description_from_projects_table', 6);

-- --------------------------------------------------------

--
-- Структура таблицы `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `password_reset_tokens`
--

INSERT INTO `password_reset_tokens` (`email`, `token`, `created_at`) VALUES
('slava.corobeinickov@yandex.ru', '$2y$12$tWy0b5SMrIhFo7nOoeaKmeaepMYMLlWMFM35Dy3qkqpGtCFCEyhGi', '2026-06-11 01:57:56');

-- --------------------------------------------------------

--
-- Структура таблицы `projects`
--

CREATE TABLE `projects` (
  `id` bigint UNSIGNED NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `short_description` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `github_link` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `technologies` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `project_date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `projects`
--

INSERT INTO `projects` (`id`, `title`, `short_description`, `image`, `github_link`, `created_at`, `updated_at`, `technologies`, `project_date`) VALUES
(21, 'Система управления контентом сайта-портфолио', 'Лёгкая CMS на Laravel: блочный редактор кейсов и предпросмотр в реальном времени без правки кода', 'projects/c8Yf4bGji28PitnwAymjtbkIDvZc6oa29iUNdHUt.png', NULL, '2026-06-15 07:34:48', '2026-06-15 07:34:48', 'Laravel, PHP, MySQL, Tailwind CSS, Alpine.js, TipTap, SortableJS, Vite', '2026-06-01');

-- --------------------------------------------------------

--
-- Структура таблицы `project_sections`
--

CREATE TABLE `project_sections` (
  `id` bigint UNSIGNED NOT NULL,
  `project_id` bigint UNSIGNED NOT NULL,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `content` longtext COLLATE utf8mb4_unicode_ci,
  `position` int NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `meta` json DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `project_sections`
--

INSERT INTO `project_sections` (`id`, `project_id`, `type`, `content`, `position`, `created_at`, `updated_at`, `meta`) VALUES
(104, 21, 'title', 'О проекте', 0, '2026-06-15 07:37:04', '2026-06-15 07:37:04', '{\"group\": \"A\", \"theme\": \"light\", \"width\": \"normal\"}'),
(105, 21, 'text', '<p>Система позволяет владельцу портфолио самостоятельно создавать и редактировать проекты, наполнять их разнородными блоками и управлять компоновкой — <strong>без правки исходного кода</strong> и повторного развёртывания. Кейс собирается из независимых блоков: заголовков, форматированного текста, отдельных изображений и галерей.</p>', 1, '2026-06-15 07:37:04', '2026-06-15 07:37:04', '{\"group\": \"A\", \"theme\": \"light\", \"width\": \"normal\"}'),
(106, 21, 'image', 'projects/sections/9gilJjzN7s8lL3QjGLPbpJxJFroGisMmwbYZnLyJ.png', 2, '2026-06-15 07:37:04', '2026-06-15 07:37:04', '{\"group\": \"B\", \"theme\": \"light\", \"width\": \"normal\", \"layout\": \"left\"}'),
(107, 21, 'text', '<p>Блочный редактор — основа админ-панели. Каждый блок хранит собственные настройки оформления, а порядок блоков меняется простым перетаскиванием. Изображение слева, текст справа — связка собирается автоматически по общей группе.</p>', 3, '2026-06-15 07:37:04', '2026-06-15 07:37:04', '{\"group\": \"B\", \"theme\": \"light\", \"width\": \"normal\"}'),
(108, 21, 'text', '<p>Главная идея системы — достоверный предпросмотр: то, что автор видит при редактировании, в точности совпадает с опубликованной страницей, потому что и там, и там работает один и тот же набор правил композиции.</p>', 4, '2026-06-15 07:37:04', '2026-06-15 07:37:04', '{\"theme\": \"dark\", \"width\": \"wide\"}'),
(109, 21, 'image', 'projects/sections/DbKe0B7hUEHlPbKwOoProhlhyGsG3kp60rWqFkyP.png', 5, '2026-06-15 07:37:04', '2026-06-15 07:37:04', '{\"group\": \"C\", \"theme\": \"light\", \"width\": \"normal\", \"layout\": \"right\"}'),
(110, 21, 'text', '<p>Данные о проектах и блоках хранятся в реляционной базе, а гибкие настройки оформления каждого блока — в поле формата JSON. Это позволяет добавлять новые параметры отображения, не меняя структуру таблиц. Здесь изображение справа, текст слева — сторона задана вручную.</p>', 6, '2026-06-15 07:37:04', '2026-06-15 07:37:04', '{\"group\": \"C\", \"theme\": \"light\", \"width\": \"normal\"}'),
(111, 21, 'title', 'Возможности', 7, '2026-06-15 07:37:04', '2026-06-15 07:37:04', '{\"theme\": \"light\", \"width\": \"normal\"}'),
(112, 21, 'text', '<p>Создание, редактирование и удаление проектов с подтверждением. Четыре типа блоков: заголовок, форматированный текст, изображение и галерея. Настройка ширины и темы каждого блока, выбор стороны размещения медиа и объединение блоков в композиции. Защищённая паролем административная панель и публичная часть только для чтения.</p>', 8, '2026-06-15 07:37:04', '2026-06-15 07:37:04', '{\"theme\": \"light\", \"width\": \"normal\"}'),
(113, 21, 'gallery', NULL, 9, '2026-06-15 07:37:04', '2026-06-15 07:37:04', '{\"theme\": \"light\", \"width\": \"full\", \"images\": [\"projects/gallery/wR3g84tSOFtc84Bph2v6xCnLaFfNSBRedYfQM8FD.png\", \"projects/gallery/OMH3pRzyMOw0Mg20XeXDz3FOdcs5uGxIUPoakjl3.png\", \"projects/gallery/TEYhuq27RddS1FGnY3OooYCztJjaGQLdMNx2LAnZ.png\"], \"layout\": \"left\"}'),
(114, 21, 'title', 'Результат', 10, '2026-06-15 07:37:04', '2026-06-15 07:37:04', '{\"theme\": \"light\", \"width\": \"normal\"}'),
(115, 21, 'text', '<p>Готовый инструмент, пригодный для ведения собственного портфолио без участия разработчика. Подход с блочным редактором и достоверным предпросмотром можно переиспользовать и в других проектах.</p>', 11, '2026-06-15 07:37:04', '2026-06-15 07:37:04', '{\"theme\": \"light\", \"width\": \"normal\"}');

-- --------------------------------------------------------

--
-- Структура таблицы `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('3tb1PSCy8KGCrrli4emhBdsVGMys9h0iUO3RmAEt', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 YaBrowser/26.4.0.0 Safari/537.36', 'eyJfdG9rZW4iOiJsOXgwbVoyaG9IcjBhZ2sxZm5kdVgyT2l5OGx0eFdZOG1mejhOZk1OIiwiX2ZsYXNoIjp7Im9sZCI6W10sIm5ldyI6W119LCJfcHJldmlvdXMiOnsidXJsIjoiaHR0cDpcL1wvMTI3LjAuMC4xOjgwMDBcL3Byb2plY3RcLzIwIiwicm91dGUiOm51bGx9LCJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI6MX0=', 1781271311),
('64bdsZONbtjZHRV4k7BwmL2DdjG38iy8UaGnfrGQ', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 YaBrowser/26.4.0.0 Safari/537.36', 'eyJfdG9rZW4iOiJCeWYyS2tIRHNBeUoyMVR2TkRRajlCV0s4NGU0UVE1QlhzbXE5RXdnIiwiX2ZsYXNoIjp7Im9sZCI6W10sIm5ldyI6W119LCJfcHJldmlvdXMiOnsidXJsIjoiaHR0cDpcL1wvMTI3LjAuMC4xOjgwMDBcL3Byb2plY3RcLzIxIiwicm91dGUiOm51bGx9LCJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI6MX0=', 1781527290);

-- --------------------------------------------------------

--
-- Структура таблицы `users`
--

CREATE TABLE `users` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Boobert', 'slava.corobeinickov@yandex.ru', NULL, '$2y$12$SMu2A5t/4FQ96KN/acrq7ej9.tBTJjdY5kPd00SoYLZrPjbKfTmae', 'DYhDScAN585tIKoytzo1vjMxGWw5uyD2yU6FahXHWWZVPpkYRIXq3n8EYT8A', '2026-06-11 01:57:14', '2026-06-11 01:57:14');

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`),
  ADD KEY `cache_expiration_index` (`expiration`);

--
-- Индексы таблицы `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`),
  ADD KEY `cache_locks_expiration_index` (`expiration`);

--
-- Индексы таблицы `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`),
  ADD KEY `failed_jobs_connection_queue_failed_at_index` (`connection`,`queue`,`failed_at`);

--
-- Индексы таблицы `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Индексы таблицы `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Индексы таблицы `projects`
--
ALTER TABLE `projects`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `project_sections`
--
ALTER TABLE `project_sections`
  ADD PRIMARY KEY (`id`),
  ADD KEY `project_sections_project_id_foreign` (`project_id`);

--
-- Индексы таблицы `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Индексы таблицы `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT для таблицы `projects`
--
ALTER TABLE `projects`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT для таблицы `project_sections`
--
ALTER TABLE `project_sections`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=116;

--
-- AUTO_INCREMENT для таблицы `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Ограничения внешнего ключа сохраненных таблиц
--

--
-- Ограничения внешнего ключа таблицы `project_sections`
--
ALTER TABLE `project_sections`
  ADD CONSTRAINT `project_sections_project_id_foreign` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
