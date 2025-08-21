-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 21, 2025 at 06:27 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `expert_system`
--

-- --------------------------------------------------------

--
-- Table structure for table `conditions`
--

CREATE TABLE `conditions` (
  `id` int(11) NOT NULL,
  `condition_name` varchar(20) DEFAULT NULL,
  `condition_value` float DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `conditions`
--

INSERT INTO `conditions` (`id`, `condition_name`, `condition_value`) VALUES
(1, 'Tidak Tahu', 0),
(2, 'Tidak Yakin', 0.2),
(3, 'Mungkin Ya', 0.4),
(4, 'Kemungkinan Besar Ya', 0.6),
(5, 'Hampir Pasti Ya', 0.8),
(6, 'Pasti Ya', 1);

-- --------------------------------------------------------

--
-- Table structure for table `consultations`
--

CREATE TABLE `consultations` (
  `id` int(11) NOT NULL,
  `consultation_date` datetime DEFAULT NULL,
  `disease` text DEFAULT NULL,
  `symptom` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `consultations`
--

INSERT INTO `consultations` (`id`, `consultation_date`, `disease`, `symptom`) VALUES
(8, '2025-08-21 20:42:57', '[{\"disease_id\":1,\"confidence\":0.73,\"disease_name\":\"Anoreksia Nervosa Restrictive\"},{\"disease_id\":2,\"confidence\":0.55,\"disease_name\":\"Anoreksia Nervosa Binge-purge\"}]', '[{\"symptom_id\":1,\"condition_value\":\"0.2\"},{\"symptom_id\":2,\"condition_value\":\"0.6\"},{\"symptom_id\":3,\"condition_value\":\"0.4\"},{\"symptom_id\":4,\"condition_value\":\"0.4\"},{\"symptom_id\":5,\"condition_value\":\"0\"},{\"symptom_id\":6,\"condition_value\":\"0\"},{\"symptom_id\":7,\"condition_value\":\"0.4\"},{\"symptom_id\":8,\"condition_value\":\"0.8\"},{\"symptom_id\":9,\"condition_value\":\"0.4\"},{\"symptom_id\":10,\"condition_value\":\"0.8\"},{\"symptom_id\":11,\"condition_value\":\"0\"},{\"symptom_id\":12,\"condition_value\":\"0\"},{\"symptom_id\":13,\"condition_value\":\"0\"}]'),
(9, '2025-08-21 23:23:06', '[{\"disease_id\":1,\"confidence\":0.4,\"disease_name\":\"Anoreksia Nervosa Restrictive\"},{\"disease_id\":2,\"confidence\":0.34,\"disease_name\":\"Anoreksia Nervosa Binge-Purge\"}]', '[{\"symptom_id\":1,\"condition_value\":\"0.8\"},{\"symptom_id\":13,\"condition_value\":\"0.8\"}]');

-- --------------------------------------------------------

--
-- Table structure for table `diseases`
--

CREATE TABLE `diseases` (
  `id` int(11) NOT NULL,
  `disease_name` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `img` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `diseases`
--

INSERT INTO `diseases` (`id`, `disease_name`, `description`, `img`) VALUES
(1, 'Anoreksia Nervosa Restrictive', '<p style=\"margin-left:0px;\">\r\nAnoreksia Nervosa Restrictive adalah salah satu jenis gangguan makan yang ditandai dengan pembatasan asupan makanan secara ekstrem tanpa disertai perilaku kompensasi seperti muntah atau penggunaan obat pencahar. Penderita secara sengaja mengurangi jumlah kalori yang masuk dengan tujuan menurunkan atau mempertahankan berat badan pada tingkat yang sangat rendah. Kondisi ini sering terjadi pada remaja maupun dewasa muda, dengan gejala seperti penurunan berat badan drastis, rasa takut berlebihan terhadap kenaikan berat badan, kulit kering, mudah lelah, gangguan menstruasi, hingga risiko kerusakan organ jika tidak ditangani.\r\n</p>\r\n\r\n<h5>Pencegahan:</h5>\r\n<ul>\r\n  <li><strong>Pendidikan tentang citra tubuh positif</strong>: Membantu individu memahami bahwa bentuk tubuh ideal bukan satu-satunya tolok ukur kesehatan atau kecantikan.</li>\r\n  <li><strong>Pola makan seimbang</strong>: Membiasakan konsumsi makanan bergizi dengan porsi yang tepat dan teratur.</li>\r\n  <li><strong>Dukungan sosial</strong>: Menciptakan lingkungan keluarga dan pertemanan yang suportif serta bebas dari komentar negatif mengenai berat badan.</li>\r\n  <li><strong>Deteksi dini</strong>: Orang tua, guru, atau tenaga kesehatan sebaiknya peka terhadap tanda-tanda awal gangguan makan pada remaja.</li>\r\n</ul>\r\n\r\n<h5>Saran:</h5>\r\n<ul>\r\n  <li><strong>Konsultasi dengan puskesmas terdekat</strong>: Segera lakukan pemeriksaan awal dan dapatkan rujukan ke tenaga ahli seperti psikolog, psikiater, atau ahli gizi.</li>\r\n  <li><strong>Terapi psikologis</strong>: Mengikuti terapi kognitif perilaku (CBT) atau terapi keluarga untuk mengatasi pikiran dan perilaku terkait makan.</li>\r\n  <li><strong>Pendampingan gizi</strong>: Bekerja sama dengan ahli gizi untuk menyusun rencana makan yang sehat dan bertahap memulihkan berat badan.</li>\r\n  <li><strong>Pemantauan medis rutin</strong>: Melakukan pemeriksaan kesehatan berkala untuk mencegah komplikasi serius pada organ tubuh.</li>\r\n  <li><strong>Dukungan keluarga</strong>: Keluarga berperan penting dalam memberikan motivasi, pengawasan, serta perhatian selama proses pemulihan.</li>\r\n</ul>\r\n', 'Anoreksia.png'),
(2, 'Anoreksia Nervosa Binge-Purge', '<p style=\"margin-left:0px;\">\r\nAnoreksia Nervosa Binge-Purge adalah salah satu jenis gangguan makan yang ditandai dengan episode makan dalam jumlah banyak (binge) yang kemudian diikuti dengan perilaku kompensasi berlebihan seperti memuntahkan makanan secara sengaja, menggunakan obat pencahar, atau olahraga ekstrem untuk mencegah kenaikan berat badan. Penderita sering mengalami distorsi citra tubuh, rasa takut berlebihan terhadap kenaikan berat badan, dan kontrol diri yang ketat terhadap makanan. Kondisi ini dapat menyebabkan dehidrasi, ketidakseimbangan elektrolit, kerusakan organ pencernaan, serta gangguan jantung jika tidak ditangani.\r\n</p>\r\n\r\n<h5>Pencegahan:</h5>\r\n<ul>\r\n  <li><strong>Pendidikan tentang pola makan sehat</strong>: Memberikan pemahaman mengenai pentingnya pola makan teratur tanpa perilaku kompensasi berbahaya.</li>\r\n  <li><strong>Peningkatan citra tubuh positif</strong>: Membantu individu menerima bentuk tubuhnya tanpa tekanan sosial yang berlebihan.</li>\r\n  <li><strong>Dukungan keluarga dan teman</strong>: Menciptakan lingkungan yang suportif serta bebas dari komentar negatif mengenai berat badan.</li>\r\n  <li><strong>Deteksi dini</strong>: Orang tua, guru, atau tenaga kesehatan perlu peka terhadap tanda-tanda awal perilaku binge dan purging.</li>\r\n</ul>\r\n\r\n<h5>Saran:</h5>\r\n<ul>\r\n  <li><strong>Konsultasi dengan puskesmas terdekat</strong>: Segera lakukan pemeriksaan awal dan dapatkan rujukan ke psikolog, psikiater, atau ahli gizi.</li>\r\n  <li><strong>Terapi psikologis</strong>: Mengikuti terapi kognitif perilaku (CBT) atau terapi keluarga untuk mengubah pola pikir dan perilaku binge-purge.</li>\r\n  <li><strong>Pendampingan gizi</strong>: Bekerja sama dengan ahli gizi untuk menyusun pola makan sehat dan mencegah perilaku kompensasi berlebihan.</li>\r\n  <li><strong>Pemantauan medis rutin</strong>: Melakukan pemeriksaan kesehatan berkala untuk memantau kondisi jantung, kadar elektrolit, dan organ pencernaan.</li>\r\n  <li><strong>Dukungan keluarga</strong>: Melibatkan keluarga untuk memberikan motivasi, pengawasan, serta dukungan emosional dalam proses pemulihan.</li>\r\n</ul>\r\n', 'Binge.png');

-- --------------------------------------------------------

--
-- Table structure for table `histories`
--

CREATE TABLE `histories` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `consultation_id` int(11) DEFAULT NULL,
  `disease_id` int(11) DEFAULT NULL,
  `accuracy` float DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `histories`
--

INSERT INTO `histories` (`id`, `user_id`, `consultation_id`, `disease_id`, `accuracy`) VALUES
(1, 2, 1, 2, 1),
(2, 2, 1, 10, 1),
(3, 2, 1, 8, 0.36),
(4, 2, 1, 11, 0.36),
(5, 2, 1, 4, 0.12),
(6, 2, 1, 5, 0),
(7, 2, 1, 7, 0),
(8, 2, 1, 9, 0),
(9, 2, 1, 13, 0),
(10, 2, 1, 12, 0),
(11, 2, 2, 5, 0.24),
(12, 2, 2, 2, 0.12),
(13, 2, 2, 4, 0.12),
(14, 2, 2, 7, 0.12),
(15, 2, 2, 9, 0.12),
(16, 2, 2, 10, 0.12),
(17, 2, 2, 12, 0.12),
(18, 2, 3, 2, 0.9),
(19, 2, 3, 6, 0.84),
(20, 2, 3, 9, 0.68),
(21, 2, 3, 3, 0.4),
(22, 2, 3, 5, 0.4),
(23, 2, 3, 4, 0.36),
(24, 2, 3, 7, 0.2),
(26, 2, 3, 12, 0.2),
(27, 2, 4, 9, 1),
(28, 2, 4, 10, 1),
(29, 2, 4, 11, 1),
(30, 2, 4, 12, 1),
(31, 2, 5, 7, 0.93),
(32, 2, 5, 9, 0.92),
(33, 2, 5, 10, 0.8),
(34, 2, 5, 6, 0.79),
(35, 2, 5, 2, 0.6),
(36, 2, 5, 11, 0.52),
(37, 2, 5, 4, 0.46),
(38, 2, 5, 12, 0.4),
(39, 2, 5, 3, 0.26),
(40, 2, 5, 1, 0.16),
(60, 42, 8, 1, 0.73),
(61, 42, 8, 2, 0.55),
(62, 42, 9, 1, 0.4),
(63, 42, 9, 2, 0.34);

-- --------------------------------------------------------

--
-- Table structure for table `knowledge_bases`
--

CREATE TABLE `knowledge_bases` (
  `id` int(11) NOT NULL,
  `disease_id` int(11) DEFAULT NULL,
  `symptom_id` int(11) DEFAULT NULL,
  `mb_value` float DEFAULT NULL,
  `md_value` float DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `knowledge_bases`
--

INSERT INTO `knowledge_bases` (`id`, `disease_id`, `symptom_id`, `mb_value`, `md_value`) VALUES
(1, 1, 1, 0.5, 0),
(2, 1, 2, 0.37, 0),
(3, 1, 3, 0.12, 0),
(4, 1, 4, 0.25, 0),
(5, 1, 5, 0.12, 0),
(6, 1, 6, 0.12, 0),
(7, 1, 7, 0.25, 0),
(8, 1, 8, 0.62, 0),
(9, 2, 5, 0.33, 0),
(10, 2, 6, 0.43, 0),
(11, 2, 9, 0.1, 0),
(12, 2, 10, 0.66, 0),
(13, 2, 11, 0.55, 0),
(14, 2, 12, 0.21, 0),
(15, 2, 13, 0.43, 0);

-- --------------------------------------------------------

--
-- Table structure for table `messages`
--

CREATE TABLE `messages` (
  `id` int(11) NOT NULL,
  `email` varchar(50) DEFAULT NULL,
  `fullname` varchar(50) DEFAULT NULL,
  `subject` varchar(50) DEFAULT NULL,
  `message` text DEFAULT NULL,
  `message_date` datetime DEFAULT NULL,
  `status` enum('Belum Dibaca','Dibaca') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `messages`
--

INSERT INTO `messages` (`id`, `email`, `fullname`, `subject`, `message`, `message_date`, `status`) VALUES
(9, 'sofi@gmail', 'sofi', 'Konsultasi lebih lanjut', 'kapan saya bisa konsultasi lenih lanjut dengan dokter?', '2025-08-21 00:41:18', 'Dibaca'),
(10, 'sofi@gmail', 'sofi', 'konsultttasi', 'cobaaa bisa nyambung ga ?\r\n', '2025-08-21 23:21:15', 'Dibaca');

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `id` int(11) NOT NULL,
  `consultation_id` int(11) DEFAULT NULL,
  `message_id` int(11) DEFAULT NULL,
  `testimonial_id` int(11) DEFAULT NULL,
  `status` enum('Dilihat','Belum Dilihat') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `notifications`
--

INSERT INTO `notifications` (`id`, `consultation_id`, `message_id`, `testimonial_id`, `status`) VALUES
(1, 1, NULL, NULL, 'Dilihat'),
(2, NULL, NULL, 1, 'Dilihat'),
(3, NULL, 1, NULL, 'Dilihat'),
(4, NULL, NULL, 2, 'Dilihat'),
(5, NULL, NULL, 3, 'Dilihat'),
(6, NULL, NULL, 4, 'Dilihat'),
(7, NULL, NULL, 5, 'Dilihat'),
(8, NULL, NULL, 6, 'Dilihat'),
(9, NULL, NULL, 7, 'Dilihat'),
(31, NULL, 10, NULL, 'Dilihat'),
(32, 9, NULL, NULL, 'Belum Dilihat');

-- --------------------------------------------------------

--
-- Table structure for table `site_contacts`
--

CREATE TABLE `site_contacts` (
  `id` int(11) NOT NULL,
  `owner` varchar(50) DEFAULT NULL,
  `email` varchar(50) DEFAULT NULL,
  `phone` varchar(15) DEFAULT NULL,
  `instagram` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `site_contacts`
--

INSERT INTO `site_contacts` (`id`, `owner`, `email`, `phone`, `instagram`) VALUES
(1, 'Sofia Safitri', 'sofiasafitria@gmail.com', '+62812345671819', 'https://www.instagram.com');

-- --------------------------------------------------------

--
-- Table structure for table `symptoms`
--

CREATE TABLE `symptoms` (
  `id` int(11) NOT NULL,
  `symptom_name` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `symptoms`
--

INSERT INTO `symptoms` (`id`, `symptom_name`) VALUES
(1, 'Berat badan menurun drastis'),
(2, 'Sering memuntahkan makanan setelah makan banyak'),
(3, 'Takut berat badan naik'),
(4, 'Terobsesi dengan jumlah kalori'),
(5, 'Menghindari makan di hadapan orang lain'),
(6, 'Mengolong-golongkan makanan yang baik dan yang tidak baik bagi tubuhnya'),
(7, 'Gangguan Mood'),
(8, 'Citra tubuh terdistorsi'),
(9, 'Makan secara sembunyi hingga kenyang'),
(10, 'Tidak dapat mengontrol episode binge'),
(11, 'Merasa tertekan, seperti malu, jijik, atau merasa bersalah'),
(12, 'tidak melakukan pembatasan makanan'),
(13, 'Makan cepat dan banyak hingga terlalu kenyang dan membuat perut tidak nyaman');

-- --------------------------------------------------------

--
-- Table structure for table `testimonials`
--

CREATE TABLE `testimonials` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `review_date` datetime DEFAULT NULL,
  `rating` int(11) DEFAULT NULL,
  `review` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `testimonials`
--

INSERT INTO `testimonials` (`id`, `user_id`, `review_date`, `rating`, `review`) VALUES
(1, 2, '2025-03-29 23:38:43', 5, 'Sistem pakar ini sangat membantu dalam pengambilan keputusan. Hasil yang diberikan sangat akurat dan relevan, membuat proses analisis lebih efisien dan efektif.'),
(2, 10, '2025-03-29 23:38:43', 5, 'Dengan menggunakan sistem pakar ini, keputusan menjadi lebih terinformasi dan tepat. Hasil analisis berdasarkan data yang jelas, memberikan kepercayaan lebih dalam membuat keputusan.'),
(3, 11, '2025-03-29 23:38:43', 5, 'Sangat puas dengan sistem pakar ini! Memberikan hasil yang sangat akurat dan relevan, serta sangat membantu dalam menyelesaikan masalah dengan cara yang lebih efisien.'),
(4, 12, '2025-03-29 23:38:43', 5, 'Sistem pakar ini memudahkan dalam menganalisis masalah dan memberikan solusi tepat. Proses pengambilan keputusan menjadi lebih cepat, efisien, dan berbasis data yang akurat.'),
(5, 13, '2025-03-29 23:38:43', 5, 'Penggunaan sistem pakar ini sangat menguntungkan. Keputusan yang diambil menjadi lebih tepat dan berbasis data, memberikan hasil yang cepat dan sesuai dengan kebutuhan.'),
(6, 14, '2025-03-29 23:38:43', 5, 'Sistem ini sangat membantu dalam pengambilan keputusan yang lebih baik. Akurasi hasil yang diberikan sangat tinggi dan relevansi solusinya sesuai dengan kebutuhan kami.'),
(7, 15, '2025-03-29 23:38:43', 5, 'Sistem pakar ini luar biasa, sangat berguna dalam menganalisis data dan memberikan solusi tepat. Membantu kami membuat keputusan lebih cepat dan lebih akurat.'),
(8, 16, '2025-03-29 23:38:43', 5, 'Sangat puas dengan hasil yang diberikan. Sistem pakar ini memberikan rekomendasi yang sangat akurat, efisien, dan memudahkan dalam proses pengambilan keputusan penting.'),
(9, 17, '2025-03-29 23:38:43', 4, 'Sistem pakar ini cukup membantu, meski terkadang hasil yang diberikan kurang sesuai dengan ekspektasi. Namun, cukup efisien dalam memberi solusi dan memudahkan analisis.'),
(10, 18, '2025-03-29 23:38:43', 4, 'Secara keseluruhan, sistem pakar ini cukup bagus. Beberapa kali hasilnya kurang akurat, tetapi sangat membantu dalam mempercepat pengambilan keputusan dan menghemat waktu.'),
(11, 19, '2025-03-29 23:38:43', 3, 'Sistem pakar ini memudahkan analisis, namun terkadang hasil yang diberikan tidak sepenuhnya akurat. Perlu beberapa peningkatan untuk hasil yang lebih memuaskan.'),
(12, 20, '2025-03-29 23:38:43', 3, 'Sistem ini memberikan gambaran yang baik, meskipun hasilnya kadang tidak sepenuhnya tepat. Namun, masih cukup membantu dalam pengambilan keputusan yang lebih cepat.'),
(13, 42, '2025-08-22 00:23:35', 5, 'Bagus, Kembangkan lagi');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `email` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `fullname` varchar(50) NOT NULL,
  `role` enum('Admin','Pakar','Pengguna') NOT NULL,
  `status` enum('Aktif','Tidak Aktif') NOT NULL,
  `phone` varchar(15) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `email`, `password`, `fullname`, `role`, `status`, `phone`) VALUES
(1, 'admin@gmail.com', '$2y$10$Zao1FouUyeDZtWIUWLyHQu5BfWM6pl8vDW9UBYvt3EscjnOQcg/M2', 'Admin', 'Admin', 'Aktif', '+6281234567890'),
(2, 'user@gmail.com', '$2y$10$OirgpJqHenPnyntQxgivKu4K/mBouuhjnBESXU5FYcJNNVaWVNjnG', 'Keyla Shafira', 'Pengguna', 'Aktif', '+6281234567890'),
(42, 'sofi@gmail', '$2y$10$moqe2dgWVDl9ZZWv1KgTau8zBjWRiWxuuLvccLUvr6nA.VLS2Gjcq', 'sofi', 'Pengguna', 'Aktif', '+628745123689');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `conditions`
--
ALTER TABLE `conditions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `consultations`
--
ALTER TABLE `consultations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `diseases`
--
ALTER TABLE `diseases`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `histories`
--
ALTER TABLE `histories`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`,`consultation_id`,`disease_id`),
  ADD KEY `consultation_id` (`consultation_id`),
  ADD KEY `disease_id` (`disease_id`);

--
-- Indexes for table `knowledge_bases`
--
ALTER TABLE `knowledge_bases`
  ADD PRIMARY KEY (`id`),
  ADD KEY `disease_id` (`disease_id`,`symptom_id`),
  ADD KEY `symptom_id` (`symptom_id`);

--
-- Indexes for table `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `consultation_id` (`consultation_id`,`message_id`,`testimonial_id`),
  ADD KEY `testimonial_id` (`testimonial_id`),
  ADD KEY `message_id` (`message_id`);

--
-- Indexes for table `site_contacts`
--
ALTER TABLE `site_contacts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `symptoms`
--
ALTER TABLE `symptoms`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `testimonials`
--
ALTER TABLE `testimonials`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `conditions`
--
ALTER TABLE `conditions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `consultations`
--
ALTER TABLE `consultations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `diseases`
--
ALTER TABLE `diseases`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `histories`
--
ALTER TABLE `histories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=64;

--
-- AUTO_INCREMENT for table `knowledge_bases`
--
ALTER TABLE `knowledge_bases`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=56;

--
-- AUTO_INCREMENT for table `messages`
--
ALTER TABLE `messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT for table `site_contacts`
--
ALTER TABLE `site_contacts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `symptoms`
--
ALTER TABLE `symptoms`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=44;

--
-- AUTO_INCREMENT for table `testimonials`
--
ALTER TABLE `testimonials`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=43;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
