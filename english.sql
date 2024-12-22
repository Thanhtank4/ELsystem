-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Máy chủ: 127.0.0.1
-- Thời gian đã tạo: Th12 21, 2024 lúc 09:37 AM
-- Phiên bản máy phục vụ: 10.4.32-MariaDB
-- Phiên bản PHP: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Cơ sở dữ liệu: `english`
--

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `conver`
--

CREATE TABLE `conver` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `type` enum('conversation','other') DEFAULT 'conversation',
  `duration` varchar(50) DEFAULT NULL,
  `difficulty` enum('Easy','Medium','Hard') DEFAULT 'Easy'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `conver`
--

INSERT INTO `conver` (`id`, `title`, `description`, `type`, `duration`, `difficulty`) VALUES
(1, 'At the Restaurant', 'Learn how to order food and interact with restaurant staff', 'conversation', '10 minutes', 'Easy'),
(2, 'Job Interview', 'Practice common job interview questions and responses', 'conversation', '15 minutes', 'Medium'),
(3, 'Shopping', 'Learn phrases for shopping in English', 'conversation', '8 minutes', 'Easy'),
(4, 'Travel', 'Learn how to ask for directions while traveling', 'conversation', '12 minutes', 'Medium');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `conversation_texts`
--

CREATE TABLE `conversation_texts` (
  `id` int(11) NOT NULL,
  `conversation_id` int(11) NOT NULL,
  `text` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `conversation_texts`
--

INSERT INTO `conversation_texts` (`id`, `conversation_id`, `text`) VALUES
(1, 1, 'Waiter: Welcome to our restaurant. May I take your order?\nCustomer: Yes, I would like a cheeseburger and a soda, please.\nWaiter: Sure. Anything else?\nCustomer: No, that will be all. Thank you.'),
(2, 2, 'Interviewer: Can you tell me about yourself?\nCandidate: Yes, I am a software developer with 3 years of experience. I specialize in web applications.\nInterviewer: That’s great. What are your strengths?\nCandidate: I am good at problem-solving and teamwork.');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `courses`
--

CREATE TABLE `courses` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `lesson_count` int(11) NOT NULL,
  `features` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`features`)),
  `type` enum('premium','regular') DEFAULT 'regular',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `image` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `duration` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `courses`
--

INSERT INTO `courses` (`id`, `name`, `price`, `lesson_count`, `features`, `type`, `created_at`, `updated_at`, `image`, `description`, `duration`) VALUES
(10, 'English for Communication', 5.00, 0, NULL, 'regular', '2024-12-20 18:37:53', '2024-12-20 18:56:54', '6765bbf03ff11.png', 'dsfgsdfg', 3),
(26, 'Basic PHP Programming', 500.00, 0, NULL, 'regular', '2024-12-20 19:41:55', '2024-12-20 19:41:55', '6765c8837af8e.png', 'This course will introduce you to PHP programming, from basics to advanced. You will learn how to build dynamic web applications and interact with databases.', 3);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `example_sentences`
--

CREATE TABLE `example_sentences` (
  `id` int(11) NOT NULL,
  `vocabulary_id` int(11) DEFAULT NULL,
  `english_sentence` text NOT NULL,
  `vietnamese_sentence` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `example_sentences`
--

INSERT INTO `example_sentences` (`id`, `vocabulary_id`, `english_sentence`, `vietnamese_sentence`, `created_at`) VALUES
(27, 7, 'She eats an apple every day.', 'Cô ấy ăn một quả táo mỗi ngày.', '2024-12-18 18:52:46'),
(28, 8, 'He can run very fast.', 'Anh ấy có thể chạy rất nhanh.', '2024-12-18 18:52:46'),
(29, 9, 'She is a beautiful girl.', 'Cô ấy là một cô gái xinh đẹp.', '2024-12-18 18:52:46'),
(30, 10, 'I study English at night.', 'Tôi học tiếng Anh vào ban đêm.', '2024-12-18 18:52:46'),
(31, 11, 'This book is very interesting.', 'Quyển sách này rất thú vị.', '2024-12-18 18:52:46'),
(32, 12, 'He runs quickly to catch the bus.', 'Anh ấy chạy nhanh để bắt kịp xe buýt.', '2024-12-18 18:52:46');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `lessons`
--

CREATE TABLE `lessons` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `level` int(11) NOT NULL,
  `lesson_order` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `lessons`
--

INSERT INTO `lessons` (`id`, `title`, `description`, `level`, `lesson_order`, `created_at`) VALUES
(1, 'Present Simple Tense', 'Learn when and how to use the present simple tense', 1, 1, '2024-11-29 04:28:53'),
(2, 'Present Continuous Tense', 'Understanding ongoing actions in the present', 1, 2, '2024-11-29 04:28:53');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `practice`
--

CREATE TABLE `practice` (
  `id` int(11) NOT NULL,
  `question` text NOT NULL,
  `options` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`options`)),
  `correct_answer` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `practice`
--

INSERT INTO `practice` (`id`, `question`, `options`, `correct_answer`, `created_at`, `updated_at`) VALUES
(1, 'What is the capital of France?', '[\"Paris\", \"London\", \"Berlin\", \"Rome\"]', 'Paris', '2024-12-16 16:14:54', '2024-12-16 16:14:54'),
(2, 'Which planet is known as the Red Planet?', '[\"Earth\", \"Mars\", \"Jupiter\", \"Saturn\"]', 'Mars', '2024-12-16 16:14:54', '2024-12-16 16:14:54'),
(3, 'What is 2 + 2?', '[\"3\", \"4\", \"5\", \"6\"]', '4', '2024-12-16 16:14:54', '2024-12-16 16:14:54');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `results`
--

CREATE TABLE `results` (
  `id` int(11) NOT NULL,
  `username` varchar(255) DEFAULT NULL,
  `score` int(11) DEFAULT NULL,
  `total_questions` int(11) DEFAULT NULL,
  `percentage` float DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `results`
--

INSERT INTO `results` (`id`, `username`, `score`, `total_questions`, `percentage`, `created_at`) VALUES
(1, 'thanhtan', 3, 3, 100, '2024-12-18 16:00:54'),
(2, 'thanhtan', 2, 3, 66.6667, '2024-12-18 16:01:04'),
(3, 'thanhtan', 1, 3, 33.3333, '2024-12-18 16:03:14'),
(4, 'thanhtan', 2, 3, 66.6667, '2024-12-19 08:16:18'),
(5, 'thanhtan', 2, 3, 66.6667, '2024-12-20 17:51:46');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','user') DEFAULT 'user',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `last_login` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password`, `role`, `created_at`, `last_login`) VALUES
(1, 'thanhtan', 'thanhtan2092k4@gmail.com', '$2y$10$tl3gaS8sdBkymbzFMM5JwuH1TpPi0zXhSWfBjlIo3MA1wQw2.l0g.', 'admin', '2024-11-29 04:30:06', '2024-12-20 18:33:39'),
(2, 'admin', 'tanngalove@gmail.com', '$2y$10$33I8JLJRyJS.PA0L.0.Qee6ZdQmoktBX/aSqD38bMCJsak0qODNve', 'user', '2024-12-20 18:23:01', '2024-12-20 18:23:05');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `vocabulary`
--

CREATE TABLE `vocabulary` (
  `id` int(11) NOT NULL,
  `word` varchar(255) NOT NULL,
  `pronunciation` varchar(255) DEFAULT NULL,
  `meaning` text NOT NULL,
  `word_type` varchar(50) DEFAULT NULL,
  `lesson_id` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `vocabulary`
--

INSERT INTO `vocabulary` (`id`, `word`, `pronunciation`, `meaning`, `word_type`, `lesson_id`, `created_at`) VALUES
(7, 'apple', 'ˈæp.l̩', 'quả táo', 'noun', 1, '2024-12-18 18:49:29'),
(8, 'run', 'rʌn', 'chạy', 'verb', 1, '2024-12-18 18:49:29'),
(9, 'beautiful', 'ˈbjuː.tɪ.fəl', 'xinh đẹp', 'adjective', 1, '2024-12-18 18:49:29'),
(10, 'study', 'ˈstʌd.i', 'học', 'verb', 2, '2024-12-18 18:49:29'),
(11, 'book', 'bʊk', 'sách', 'noun', 2, '2024-12-18 18:49:29'),
(12, 'quickly', 'ˈkwɪk.li', 'nhanh chóng', 'adverb', 2, '2024-12-18 18:49:29'),
(13, 'permanent', 'ˈpɜː.mə.nənt', 'lâu dài, cố định, thường xuyên', 'adjective', 1, '2024-12-19 08:00:40'),
(14, 'competitive', 'kəmˈpɛt.ɪ.tɪv', 'cạnh tranh, ganh đua', 'adjective', 1, '2024-12-19 08:00:40'),
(15, 'profitable', 'ˈprɒf.ɪ.tə.bəl', 'có lợi, sinh lãi', 'adjective', 1, '2024-12-19 08:00:40'),
(16, 'attentive', 'əˈtɛn.tɪv', 'chăm chú, ân cần', 'adjective', 1, '2024-12-19 08:00:40'),
(17, 'creative', 'kriˈeɪ.tɪv', 'sáng tạo', 'adjective', 1, '2024-12-19 08:00:40'),
(18, 'superior', 'suːˈpɪə.ri.ər', 'vượt trội, cấp cao hơn', 'adjective', 1, '2024-12-19 08:00:40'),
(19, 'costly', 'ˈkɒst.li', 'đắt tiền, tốn kém', 'adjective', 1, '2024-12-19 08:00:40'),
(20, 'associated', 'əˈsəʊ.si.eɪ.tɪd', 'liên kết, liên quan', 'adjective', 1, '2024-12-19 08:00:40'),
(21, 'extensive', 'ɪkˈstɛn.sɪv', 'rộng, bao quát', 'adjective', 1, '2024-12-19 08:00:40'),
(22, 'rare', 'reə', 'hiếm khi', 'adjective', 1, '2024-12-19 08:00:40'),
(23, 'initial', 'ɪˈnɪʃ.əl', 'ban đầu', 'adjective', 1, '2024-12-19 08:00:40'),
(24, 'fragile', 'ˈfrædʒ.aɪl', 'dễ vỡ, yếu ớt', 'adjective', 1, '2024-12-19 08:00:40'),
(25, 'multiple', 'ˈmʌl.tɪ.pəl', 'nhiều', 'adjective', 1, '2024-12-19 08:00:40'),
(26, 'beneficial', 'ˌbɛn.ɪˈfɪʃ.əl', 'giúp ích, có lợi', 'adjective', 1, '2024-12-19 08:00:40'),
(27, 'constant', 'ˈkɒn.stənt', 'liên tục, không đổi', 'adjective', 1, '2024-12-19 08:00:40'),
(28, 'numerous', 'ˈnjuː.mə.rəs', 'nhiều, đông đảo', 'adjective', 1, '2024-12-19 08:00:40'),
(29, 'reluctant', 'rɪˈlʌk.tənt', 'miễn cưỡng, lưỡng lự', 'adjective', 1, '2024-12-19 08:00:40'),
(30, 'introductory', 'ˌɪn.trəˈdʌk.tər.i', '(để) giới thiệu, mở đầu', 'adjective', 1, '2024-12-19 08:00:40'),
(31, 'ongoing', 'ˈɒnˌɡəʊ.ɪŋ', 'đang diễn ra, liên tục', 'adjective', 1, '2024-12-19 08:00:40'),
(32, 'tailored', 'ˈteɪ.ləd', 'được may, thiết kế riêng', 'adjective', 1, '2024-12-19 08:00:40'),
(33, 'unexpected', 'ˌʌn.ɪkˈspɛk.tɪd', 'bất ngờ', 'adjective', 1, '2024-12-19 08:00:40'),
(34, 'risky', 'ˈrɪs.ki', 'mạo hiểm', 'adjective', 1, '2024-12-19 08:00:40'),
(35, 'reliable', 'rɪˈlaɪə.bəl', 'đáng tin cậy', 'adjective', 1, '2024-12-19 08:00:40'),
(36, 'overdue', 'ˌəʊ.vəˈdjuː', 'chậm, quá hạn', 'adjective', 1, '2024-12-19 08:00:40'),
(37, 'efficient', 'ɪˈfɪʃ.ənt', 'có hiệu quả, năng suất cao', 'adjective', 1, '2024-12-19 08:00:40'),
(38, 'courteous', 'ˈkɜː.ti.əs', 'lịch sự, nhã nhặn', 'adjective', 1, '2024-12-19 08:00:40'),
(39, 'ideal', 'aɪˈdɪəl', 'lý tưởng', 'adjective', 1, '2024-12-19 08:00:40'),
(40, 'stable', 'ˈsteɪ.bəl', 'ổn định', 'adjective', 1, '2024-12-19 08:00:40'),
(41, 'satisfactory', 'ˌsæt.ɪsˈfæk.tər.i', 'vừa ý, thỏa đáng', 'adjective', 1, '2024-12-19 08:00:40'),
(42, 'automated', 'ˈɔː.tə.meɪ.tɪd', '(được) tự động hóa', 'adjective', 1, '2024-12-19 08:00:40'),
(43, 'willing', 'ˈwɪl.ɪŋ', 'sẵn lòng, tình nguyện', 'adjective', 1, '2024-12-19 08:00:40'),
(44, 'persuasive', 'pəˈsweɪ.sɪv', 'có sức thuyết phục', 'adjective', 1, '2024-12-19 08:00:40'),
(45, 'commercial', 'kəˈmɜː.ʃəl', '(thuộc về) thương mại', 'adjective', 1, '2024-12-19 08:00:40'),
(46, 'conscious', 'ˈkɒn.ʃəs', 'biết rõ, nhận thức', 'adjective', 1, '2024-12-19 08:00:40'),
(47, 'exclusive', 'ɪkˈskluː.sɪv', 'độc quyền', 'adjective', 1, '2024-12-19 08:00:40'),
(48, 'unprecedented', 'ʌnˈprɛs.ɪ.dɛn.tɪd', 'chưa từng có', 'adjective', 1, '2024-12-19 08:00:40'),
(49, 'prevalent', 'ˈprɛv.əl.ənt', 'thịnh hành, phổ biến', 'adjective', 1, '2024-12-19 08:00:40'),
(50, 'demanding', 'dɪˈmɑːn.dɪŋ', 'đòi hỏi cao, khắt khe', 'adjective', 1, '2024-12-19 08:00:40'),
(51, 'optimal', 'ˈɒp.tɪ.məl', 'tốt nhất, tối ưu', 'adjective', 1, '2024-12-19 08:00:40'),
(52, 'receptive', 'rɪˈsɛp.tɪv', '(dễ) tiếp nhận, lĩnh hội', 'adjective', 1, '2024-12-19 08:00:40'),
(53, 'discretionary', 'dɪˈskrɛʃ.ən.ər.i', 'tùy ý, linh hoạt', 'adjective', 1, '2024-12-19 08:00:40'),
(54, 'exempt', 'ɪɡˈzɛmpt', 'được miễn', 'adjective', 1, '2024-12-19 08:00:40'),
(55, 'considerate', 'kənˈsɪd.ər.ət', 'chu đáo, ân cần, để ý', 'adjective', 1, '2024-12-19 08:00:40'),
(56, 'adjacent', 'əˈdʒeɪ.sənt', 'gần, sát bên', 'adjective', 1, '2024-12-19 08:00:40'),
(57, 'authentic', 'ɔːˈθɛn.tɪk', 'xác thực, nguyên bản, thật', 'adjective', 1, '2024-12-19 08:00:40'),
(58, 'dedicated', 'ˈdɛd.ɪ.keɪ.tɪd', 'tận tụy, dành riêng cho', 'adjective', 1, '2024-12-19 08:00:40'),
(59, 'predictable', 'prɪˈdɪk.tə.bəl', 'có thể dự đoán', 'adjective', 1, '2024-12-19 08:00:40'),
(60, 'accountable', 'əˈkaʊn.tə.bəl', 'chịu trách nhiệm', 'adjective', 1, '2024-12-19 08:00:40'),
(61, 'inclement', 'ɪnˈklɛm.ənt', 'khắc nghiệt', 'adjective', 1, '2024-12-19 08:00:40'),
(62, 'obsolete', 'ˈɒb.səl.iːt', 'cổ xưa, lỗi thời', 'adjective', 1, '2024-12-19 08:00:40');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `vocabulary_progress`
--

CREATE TABLE `vocabulary_progress` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `lesson_id` int(11) NOT NULL,
  `learned_count` int(11) NOT NULL DEFAULT 0,
  `progress_date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `vocabulary_progress`
--

INSERT INTO `vocabulary_progress` (`id`, `username`, `lesson_id`, `learned_count`, `progress_date`) VALUES
(1, 'thanhtan', 1, 2, '2024-12-21 01:07:11');

--
-- Chỉ mục cho các bảng đã đổ
--

--
-- Chỉ mục cho bảng `conver`
--
ALTER TABLE `conver`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `conversation_texts`
--
ALTER TABLE `conversation_texts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `conversation_id` (`conversation_id`);

--
-- Chỉ mục cho bảng `courses`
--
ALTER TABLE `courses`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `example_sentences`
--
ALTER TABLE `example_sentences`
  ADD PRIMARY KEY (`id`),
  ADD KEY `vocabulary_id` (`vocabulary_id`);

--
-- Chỉ mục cho bảng `lessons`
--
ALTER TABLE `lessons`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `practice`
--
ALTER TABLE `practice`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `results`
--
ALTER TABLE `results`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Chỉ mục cho bảng `vocabulary`
--
ALTER TABLE `vocabulary`
  ADD PRIMARY KEY (`id`),
  ADD KEY `lesson_id` (`lesson_id`);

--
-- Chỉ mục cho bảng `vocabulary_progress`
--
ALTER TABLE `vocabulary_progress`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`,`lesson_id`);

--
-- AUTO_INCREMENT cho các bảng đã đổ
--

--
-- AUTO_INCREMENT cho bảng `conversation_texts`
--
ALTER TABLE `conversation_texts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT cho bảng `courses`
--
ALTER TABLE `courses`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT cho bảng `example_sentences`
--
ALTER TABLE `example_sentences`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT cho bảng `lessons`
--
ALTER TABLE `lessons`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT cho bảng `practice`
--
ALTER TABLE `practice`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT cho bảng `results`
--
ALTER TABLE `results`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT cho bảng `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT cho bảng `vocabulary`
--
ALTER TABLE `vocabulary`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=63;

--
-- AUTO_INCREMENT cho bảng `vocabulary_progress`
--
ALTER TABLE `vocabulary_progress`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Các ràng buộc cho các bảng đã đổ
--

--
-- Các ràng buộc cho bảng `conversation_texts`
--
ALTER TABLE `conversation_texts`
  ADD CONSTRAINT `conversation_texts_ibfk_1` FOREIGN KEY (`conversation_id`) REFERENCES `conver` (`id`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `example_sentences`
--
ALTER TABLE `example_sentences`
  ADD CONSTRAINT `example_sentences_ibfk_1` FOREIGN KEY (`vocabulary_id`) REFERENCES `vocabulary` (`id`);

--
-- Các ràng buộc cho bảng `vocabulary`
--
ALTER TABLE `vocabulary`
  ADD CONSTRAINT `vocabulary_ibfk_1` FOREIGN KEY (`lesson_id`) REFERENCES `lessons` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
