-- phpMyAdmin SQL Dump
-- version 4.9.0.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 28 Nov 2019 pada 09.46
-- Versi server: 10.4.6-MariaDB
-- Versi PHP: 7.2.21

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";

--
-- Database: `client_consultant`
--
CREATE DATABASE IF NOT EXISTS `client_consultant` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
USE `client_consultant`;

-- --------------------------------------------------------

--
-- Struktur dari tabel `admin_user`
--

DROP TABLE IF EXISTS `admin_user`;
CREATE TABLE `admin_user` (
  `admin_id` varchar(7) NOT NULL,
  `admin_fullname` varchar(64) NOT NULL,
  `admin_email` varchar(64) NOT NULL,
  `admin_password` varchar(60) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `seqid` smallint(4) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data untuk tabel `admin_user`
--

INSERT INTO `admin_user` (`admin_id`, `admin_fullname`, `admin_email`, `admin_password`, `created_at`, `updated_at`, `seqid`) VALUES
('ADM0001', 'Administrator Edited', 'admin@admin.com', '$2y$10$XyZ6I/Ze5zt6Zur6zzBfHOXGQknqRyP0Ky1lhzzOdg64tXK4oZRfy', '2019-11-10 13:29:48', '2019-11-22 01:32:42', 1),
('ADM0002', 'Sony Darmawan', 'sonypiay@mail.com', '$2y$10$aFrHfnphYcGd3mICfX.E8uPj0pk3xiRMSo/YrcFp9NrbDwR.K6/3i', '2019-11-20 20:28:03', '2019-11-20 20:28:03', 2),
('ADM0003', 'John Doe', 'johndoe@example.com', '$2y$10$zGhb5EcgfjNvi99gC.b5JuvbPq3EXt8/8osUBGDkser4u83b67uce', '2019-11-20 20:40:08', '2019-11-20 20:54:57', 3),
('ADM0005', 'Admin Testing', 'admin@testing.com', '$2y$10$2QyBK3hpe3S7M5jTIe7slejM6b8Q0e80ti9oRX46t/IrxOFxQ7oem', '2019-11-20 21:00:04', '2019-11-20 21:00:04', 6);

-- --------------------------------------------------------

--
-- Struktur dari tabel `appointment_request`
--

DROP TABLE IF EXISTS `appointment_request`;
CREATE TABLE `appointment_request` (
  `apt_id` varchar(13) NOT NULL,
  `client_id` varchar(7) NOT NULL,
  `consultant_id` varchar(7) DEFAULT NULL,
  `created_by` enum('consultant','client') NOT NULL DEFAULT 'consultant',
  `request_to` enum('consultant','client') NOT NULL DEFAULT 'consultant',
  `schedule_date` datetime NOT NULL,
  `location` text DEFAULT NULL,
  `service_topic` varchar(7) NOT NULL,
  `status_request` enum('accept','waiting','decline','cancel','done') NOT NULL DEFAULT 'waiting',
  `is_solved` enum('N','Y','P') NOT NULL DEFAULT 'P',
  `notes` text DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `seqid` smallint(4) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data untuk tabel `appointment_request`
--

INSERT INTO `appointment_request` (`apt_id`, `client_id`, `consultant_id`, `created_by`, `request_to`, `schedule_date`, `location`, `service_topic`, `status_request`, `is_solved`, `notes`, `created_at`, `updated_at`, `seqid`) VALUES
('APT1911180001', 'CL0001', 'CLT0002', 'client', 'consultant', '2019-11-19 10:00:00', 'Tanah Abang, Jakarta Pusat', 'TPC005', 'done', 'Y', '', '2019-11-18 15:44:58', '2019-11-18 20:12:18', 1),
('APT1911180002', 'CL0001', 'CLT0001', 'client', 'consultant', '2019-11-20 10:00:00', 'Tanah Abang, Jakarta Pusat', 'TPC001', 'done', 'Y', '', '2019-11-18 17:09:16', '2019-11-18 20:01:36', 2),
('APT1911180003', 'CL0001', 'CLT0001', 'client', 'consultant', '2019-11-21 15:00:00', 'Senayan City, Jakarta Pusat', 'TPC004', 'done', 'Y', '', '2019-11-18 17:10:03', '2019-11-18 20:01:46', 3),
('APT1911180004', 'CL0001', 'CLT0001', 'consultant', 'client', '2019-11-21 08:00:00', 'Senayan City', 'TPC001', 'done', 'Y', '', '2019-11-18 20:48:46', '2019-11-18 20:58:04', 5),
('APT1911190006', 'CL0001', 'CLT0001', 'client', 'consultant', '2019-11-19 17:00:00', 'Mall Emporium Pluit, Jakarta Utara', 'TPC003', 'accept', 'P', NULL, '2019-11-19 15:01:37', '2019-11-19 15:02:43', 6),
('APT1911190007', 'CL0001', 'CLT0002', 'consultant', 'client', '2019-11-19 19:00:00', 'Tanah Abang, Jakarta Pusat', 'TPC001', 'done', 'Y', 'Banyak hal yang dibahas pada pertemuan ini.', '2019-11-19 16:40:13', '2019-11-20 02:22:23', 7),
('APT1911230008', 'CL0001', 'CLT0001', 'consultant', 'client', '2019-11-25 11:00:00', 'Plaza Indonesia', 'TPC004', 'waiting', 'P', NULL, '2019-11-23 19:32:38', '2019-11-23 19:32:38', 9);

-- --------------------------------------------------------

--
-- Struktur dari tabel `city`
--

DROP TABLE IF EXISTS `city`;
CREATE TABLE `city` (
  `city_id` char(3) NOT NULL,
  `city_name` varchar(32) NOT NULL,
  `province_id` char(2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data untuk tabel `city`
--

INSERT INTO `city` (`city_id`, `city_name`, `province_id`) VALUES
('BGD', 'Bandung', 'JB'),
('BGR', 'Bogor', 'JB'),
('BKS', 'Bekasi', 'JB'),
('CBN', 'Cirebon', 'JB'),
('DPK', 'Depok', 'JB'),
('JKB', 'Jakarta Barat', 'JK'),
('JKP', 'Jakarta Pusat', 'JK'),
('KWG', 'Karawang', 'JB'),
('SRG', 'Serang', 'BT'),
('TNG', 'Tangerang', 'BT'),
('TSM', 'Tasikmalaya', 'JB');

-- --------------------------------------------------------

--
-- Struktur dari tabel `client_user`
--

DROP TABLE IF EXISTS `client_user`;
CREATE TABLE `client_user` (
  `client_id` varchar(7) NOT NULL,
  `client_fullname` varchar(64) NOT NULL,
  `client_email` varchar(64) NOT NULL,
  `client_phone_number` varchar(17) DEFAULT NULL,
  `client_password` varchar(60) NOT NULL,
  `client_type` enum('pt','perorangan') NOT NULL,
  `client_address` text DEFAULT NULL,
  `client_npwp` varchar(30) DEFAULT NULL,
  `city` varchar(32) DEFAULT NULL,
  `seqid` smallint(4) UNSIGNED NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data untuk tabel `client_user`
--

INSERT INTO `client_user` (`client_id`, `client_fullname`, `client_email`, `client_phone_number`, `client_password`, `client_type`, `client_address`, `client_npwp`, `city`, `seqid`, `created_at`, `updated_at`) VALUES
('CL0001', 'Sony Darmawan', 'sonypiay@mail.com', '08129898398', '$2y$10$ppWmj7rK/WCMcMY8DzD8R.q1PIgW6ZVhpCyBXg9sOU3oWK3d5n/Be', 'perorangan', 'Jl. Jendral Sudirman Kavling 10 - 11, Midplaza 2 lantai 8', '66.422.976.2-405.000', 'Jakarta Pusat', 1, '2019-11-10 13:21:52', '2019-11-22 02:06:26'),
('CL0004', 'Biznet Networks', 'support@biznetnetworks.com', NULL, '$2y$10$ZuEwG00mplOZ/..FmwhkdeYXt40KiHWyTNVH8AV.byuk1DAyGnmXO', 'pt', NULL, NULL, NULL, 4, '2019-11-21 15:35:00', '2019-11-21 15:35:00');

-- --------------------------------------------------------

--
-- Struktur dari tabel `consultant_user`
--

DROP TABLE IF EXISTS `consultant_user`;
CREATE TABLE `consultant_user` (
  `consultant_id` varchar(7) NOT NULL,
  `consultant_fullname` varchar(64) NOT NULL,
  `consultant_email` varchar(64) NOT NULL,
  `consultant_password` varchar(60) NOT NULL,
  `consultant_phone_number` varchar(64) DEFAULT NULL,
  `consultant_address` text DEFAULT NULL,
  `city` varchar(32) DEFAULT NULL,
  `seqid` smallint(5) UNSIGNED NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data untuk tabel `consultant_user`
--

INSERT INTO `consultant_user` (`consultant_id`, `consultant_fullname`, `consultant_email`, `consultant_password`, `consultant_phone_number`, `consultant_address`, `city`, `seqid`, `created_at`, `updated_at`) VALUES
('CLT0001', 'John Doe', 'johndoe@example.com', '$2y$10$6ZIgjzGs5wObTrQttcMFQu8ptjmCbJJD62JgHD9BG7sdxoA.OMZtK', '0813939273879', 'Midplaza 2 lantai 8', NULL, 1, '2019-11-10 21:46:35', '2019-11-10 22:47:45'),
('CLT0002', 'Jane Doe', 'janedoe@example.com', '$2y$10$6ZIgjzGs5wObTrQttcMFQu8ptjmCbJJD62JgHD9BG7sdxoA.OMZtK', '0813131382379', 'Testing', NULL, 2, '2019-11-18 12:16:54', '2019-11-20 02:14:59'),
('CLT0003', 'Joe Doe', 'joedoe@example.com', '$2y$10$6ZIgjzGs5wObTrQttcMFQu8ptjmCbJJD62JgHD9BG7sdxoA.OMZtK', '0123928372983', NULL, NULL, 3, '2019-11-18 12:16:54', '2019-11-21 22:31:05'),
('CLT0004', 'Takeshi Maekawa', 'takeshi@takeshi.com', '$2y$10$MG2zcL3kD6DIMA9EODI7UeCeEzD8iCLujWo1DWUnWRmjTWiopyVBm', '0813949384', 'Jakarta', 'Jakarta Pusat', 4, '2019-11-21 01:57:13', '2019-11-22 02:10:08');

-- --------------------------------------------------------

--
-- Struktur dari tabel `conversation_chat`
--

DROP TABLE IF EXISTS `conversation_chat`;
CREATE TABLE `conversation_chat` (
  `chat_id` varchar(60) NOT NULL,
  `client_id` varchar(7) NOT NULL,
  `consultant_id` varchar(7) NOT NULL,
  `chat_date` datetime NOT NULL,
  `seqid` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Struktur dari tabel `event_schedule`
--

DROP TABLE IF EXISTS `event_schedule`;
CREATE TABLE `event_schedule` (
  `evt_id` varchar(7) NOT NULL,
  `evt_title` varchar(255) NOT NULL,
  `evt_schedule` datetime NOT NULL,
  `evt_location` text NOT NULL,
  `evt_note` text DEFAULT NULL,
  `consultant_id` varchar(7) NOT NULL,
  `seqid` smallint(4) UNSIGNED NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data untuk tabel `event_schedule`
--

INSERT INTO `event_schedule` (`evt_id`, `evt_title`, `evt_schedule`, `evt_location`, `evt_note`, `consultant_id`, `seqid`, `created_at`, `updated_at`) VALUES
('APT0001', 'Meeting Dinas', '2019-11-16 10:00:00', 'Senayan City, Jakarta', 'Jawa Ipsum gelung kalung ayam manah jawah, luh bebed kancing. Sapu sima peksi susu piring tuwi enjing mucal dipun pendhet wos? Peksi cariyos, sakit suku epek-epek ngulemi pedhang, untu manah nyukani maesa. Bidal minggat supena ngulemi? Peksi dhateng, kangge; kinten nyepeng ayam toya peksi, \"sima wilujeng maos sirah kuping ngadeg dolan makarya.\" Jejeran wawarat embok saweg mantun ajrih benang ningali gujeng pedhang tumut swanten awon.', 'CLT0001', 1, '2019-11-14 17:12:23', '2019-11-14 17:34:02');

-- --------------------------------------------------------

--
-- Struktur dari tabel `feedbacks`
--

DROP TABLE IF EXISTS `feedbacks`;
CREATE TABLE `feedbacks` (
  `fd_id` varchar(7) NOT NULL,
  `review_description` text NOT NULL,
  `feedback` enum('excellent','good','neutral','poor','disappointed') NOT NULL,
  `rateindex` tinyint(1) NOT NULL DEFAULT 0,
  `apt_id` varchar(13) NOT NULL,
  `seqid` smallint(4) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data untuk tabel `feedbacks`
--

INSERT INTO `feedbacks` (`fd_id`, `review_description`, `feedback`, `rateindex`, `apt_id`, `seqid`, `created_at`, `updated_at`) VALUES
('FD0001', 'Mantapp!!', 'excellent', 5, 'APT1911110005', 1, '2019-11-13 16:32:24', '2019-11-13 16:32:24'),
('FD0002', 'Penjelasannya mudah dimengerti', 'good', 4, 'APT1911130009', 2, '2019-11-14 19:36:31', '2019-11-14 19:36:31'),
('FD0003', 'Nicee!', 'good', 4, 'APT1911140010', 3, '2019-11-14 20:05:54', '2019-11-14 20:05:54'),
('FD0004', 'Hebat', 'excellent', 5, 'APT1911180002', 4, '2019-11-18 20:04:31', '2019-11-18 20:04:31'),
('FD0005', 'Hebat', 'excellent', 5, 'APT1911180003', 5, '2019-11-18 20:04:41', '2019-11-18 20:04:41'),
('FD0006', 'Hebat', 'excellent', 5, 'APT1911180001', 6, '2019-11-18 20:12:28', '2019-11-18 20:12:28'),
('FD0007', 'Hmm, okelah', 'neutral', 3, 'APT1911180004', 7, '2019-11-18 20:58:21', '2019-11-18 20:58:21'),
('FD0008', 'Penjelasannya mudah dimengerti', 'good', 4, 'APT1911190007', 8, '2019-11-20 02:22:49', '2019-11-20 02:22:49');

-- --------------------------------------------------------

--
-- Struktur dari tabel `messages`
--

DROP TABLE IF EXISTS `messages`;
CREATE TABLE `messages` (
  `id` int(10) UNSIGNED NOT NULL,
  `sender` varchar(7) NOT NULL,
  `rcpt` varchar(7) NOT NULL,
  `msg` text NOT NULL,
  `msg_date` datetime NOT NULL,
  `is_read` enum('Y','N') NOT NULL DEFAULT 'N',
  `chat_id` varchar(64) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Struktur dari tabel `notification`
--

DROP TABLE IF EXISTS `notification`;
CREATE TABLE `notification` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` varchar(7) DEFAULT NULL,
  `notif_message` text NOT NULL,
  `parent_id` varchar(13) NOT NULL,
  `notif_read` enum('R','N') DEFAULT NULL,
  `notif_date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data untuk tabel `notification`
--

INSERT INTO `notification` (`id`, `user_id`, `notif_message`, `parent_id`, `notif_read`, `notif_date`) VALUES
(1, 'CLT0001', 'Klien mengajukan permintaan konsultasi dengan nomor ID APT1911180001', 'APT1911180001', 'R', '2019-11-18 15:44:58'),
(2, 'CLT0002', 'Klien mengajukan permintaan konsultasi dengan nomor ID APT1911180001', 'APT1911180001', 'R', '2019-11-18 15:44:58'),
(3, 'CLT0003', 'Klien mengajukan permintaan konsultasi dengan nomor ID APT1911180001', 'APT1911180001', 'N', '2019-11-18 15:44:58'),
(4, NULL, 'Konsultasi dengan nomor APT1911180001 diterima', 'APT1911180001', 'N', '2019-11-18 16:16:14'),
(5, 'CLT0001', 'Klien mengajukan permintaan konsultasi dengan nomor ID APT1911180002', 'APT1911180002', 'R', '2019-11-18 17:09:16'),
(6, 'CLT0002', 'Klien mengajukan permintaan konsultasi dengan nomor ID APT1911180002', 'APT1911180002', 'R', '2019-11-18 17:09:16'),
(7, 'CLT0003', 'Klien mengajukan permintaan konsultasi dengan nomor ID APT1911180002', 'APT1911180002', 'N', '2019-11-18 17:09:16'),
(8, 'CLT0001', 'Klien mengajukan permintaan konsultasi dengan nomor ID APT1911180003', 'APT1911180003', 'R', '2019-11-18 17:10:03'),
(9, 'CLT0002', 'Klien mengajukan permintaan konsultasi dengan nomor ID APT1911180003', 'APT1911180003', 'R', '2019-11-18 17:10:03'),
(10, 'CLT0003', 'Klien mengajukan permintaan konsultasi dengan nomor ID APT1911180003', 'APT1911180003', 'N', '2019-11-18 17:10:03'),
(11, 'CL0001', 'Konsultasi dengan nomor APT1911180003 diterima', 'APT1911180003', 'R', '2019-11-18 17:13:10'),
(12, 'CL0001', 'Konsultasi dengan nomor APT1911180002 diterima', 'APT1911180002', 'R', '2019-11-18 17:13:15'),
(13, 'CLT0001', 'Konsultasi dengan nomor APT1911180002 sudah terpecahkan', 'APT1911180002', 'R', '2019-11-18 19:53:45'),
(14, 'CLT0001', 'Konsultasi dengan nomor APT1911180003 sudah terpecahkan', 'APT1911180003', 'R', '2019-11-18 19:53:51'),
(15, 'CLT0001', 'Konsultasi dengan nomor APT1911180002 sudah selesai', 'APT1911180002', 'R', '2019-11-18 20:01:36'),
(16, 'CL0001', 'Konsultasi dengan nomor APT1911180002 sudah selesai', 'APT1911180002', 'R', '2019-11-18 20:01:36'),
(17, 'CLT0001', 'Konsultasi dengan nomor APT1911180003 sudah selesai', 'APT1911180003', 'R', '2019-11-18 20:01:46'),
(18, 'CL0001', 'Konsultasi dengan nomor APT1911180003 sudah selesai', 'APT1911180003', 'R', '2019-11-18 20:01:46'),
(19, 'CLT0002', 'Konsultasi dengan nomor APT1911180001 sudah terpecahkan', 'APT1911180001', 'R', '2019-11-18 20:11:32'),
(20, 'CL0001', 'Konsultasi dengan nomor APT1911180001 sudah terpecahkan', 'APT1911180001', 'R', '2019-11-18 20:11:32'),
(21, 'CLT0002', 'Konsultasi dengan nomor APT1911180001 sudah selesai', 'APT1911180001', 'R', '2019-11-18 20:12:18'),
(22, 'CL0001', 'Konsultasi dengan nomor APT1911180001 sudah selesai', 'APT1911180001', 'R', '2019-11-18 20:12:18'),
(23, 'CL0001', 'Anda menerima jadwal undangan konsultasi dengan nomor ID APT1911180004', 'APT1911180004', 'R', '2019-11-18 20:13:34'),
(24, 'CLT0001', 'Konsultasi dengan nomor APT1911180004 ditolak', 'APT1911180004', 'R', '2019-11-18 20:23:54'),
(25, 'CL0001', 'Konsultasi dengan nomor APT1911180004 ditolak', 'APT1911180004', 'R', '2019-11-18 20:23:54'),
(26, 'CL0001', 'Jadwal konsultasi APT1911180004 telah diganti.', 'APT1911180004', 'R', '2019-11-18 20:24:15'),
(27, 'CLT0001', 'Konsultasi dengan nomor APT1911180004 ditolak', 'APT1911180004', 'R', '2019-11-18 20:29:53'),
(28, 'CL0001', 'Konsultasi dengan nomor APT1911180004 ditolak', 'APT1911180004', 'R', '2019-11-18 20:29:53'),
(29, 'CLT0001', 'Jadwal konsultasi APT1911180004 telah diganti.', 'APT1911180004', 'R', '2019-11-18 20:37:42'),
(30, 'CLT0001', 'Konsultasi dengan nomor APT1911180004 ditolak', 'APT1911180004', 'R', '2019-11-18 20:39:28'),
(31, 'CL0001', 'Konsultasi dengan nomor APT1911180004 ditolak', 'APT1911180004', 'R', '2019-11-18 20:39:28'),
(32, 'CL0001', 'Jadwal konsultasi APT1911180004 telah diganti.', 'APT1911180004', 'R', '2019-11-18 20:39:50'),
(33, 'CLT0001', 'Konsultasi dengan nomor APT1911180004 ditolak', 'APT1911180004', 'R', '2019-11-18 20:46:03'),
(34, 'CL0001', 'Konsultasi dengan nomor APT1911180004 ditolak', 'APT1911180004', 'R', '2019-11-18 20:46:03'),
(35, 'CL0001', 'Jadwal konsultasi APT1911180004 telah diganti.', 'APT1911180004', 'R', '2019-11-18 20:47:06'),
(36, 'CLT0001', 'Klien mengajukan permintaan konsultasi dengan nomor ID APT1911180004', 'APT1911180004', 'R', '2019-11-18 20:48:46'),
(37, 'CLT0002', 'Klien mengajukan permintaan konsultasi dengan nomor ID APT1911180004', 'APT1911180004', 'R', '2019-11-18 20:48:46'),
(38, 'CLT0003', 'Klien mengajukan permintaan konsultasi dengan nomor ID APT1911180004', 'APT1911180004', 'N', '2019-11-18 20:48:46'),
(39, 'CLT0001', 'Konsultasi dengan nomor APT1911180004 diterima', 'APT1911180004', 'R', '2019-11-18 20:53:35'),
(40, 'CL0001', 'Konsultasi dengan nomor APT1911180004 diterima', 'APT1911180004', 'R', '2019-11-18 20:53:35'),
(41, 'CLT0001', 'Konsultasi dengan nomor APT1911180004 belum terpecahkan', 'APT1911180004', 'R', '2019-11-18 20:54:16'),
(42, 'CL0001', 'Konsultasi dengan nomor APT1911180004 belum terpecahkan', 'APT1911180004', 'R', '2019-11-18 20:54:16'),
(43, 'CL0001', 'Jadwal konsultasi APT1911180004 telah diganti.', 'APT1911180004', 'R', '2019-11-18 20:54:28'),
(44, 'CLT0001', 'Konsultasi dengan nomor APT1911180004 diterima', 'APT1911180004', 'R', '2019-11-18 20:55:42'),
(45, 'CL0001', 'Konsultasi dengan nomor APT1911180004 diterima', 'APT1911180004', 'R', '2019-11-18 20:55:42'),
(46, 'CLT0001', 'Konsultasi dengan nomor APT1911180004 sudah terpecahkan', 'APT1911180004', 'R', '2019-11-18 20:57:14'),
(47, 'CL0001', 'Konsultasi dengan nomor APT1911180004 sudah terpecahkan', 'APT1911180004', 'R', '2019-11-18 20:57:14'),
(48, 'CLT0001', 'Konsultasi dengan nomor APT1911180004 sudah selesai', 'APT1911180004', 'R', '2019-11-18 20:58:04'),
(49, 'CL0001', 'Konsultasi dengan nomor APT1911180004 sudah selesai', 'APT1911180004', 'R', '2019-11-18 20:58:04'),
(50, 'CLT0001', 'Klien mengajukan permintaan konsultasi dengan nomor ID APT1911190006', 'APT1911190006', 'R', '2019-11-19 15:01:37'),
(51, 'CLT0002', 'Klien mengajukan permintaan konsultasi dengan nomor ID APT1911190006', 'APT1911190006', 'R', '2019-11-19 15:01:37'),
(52, 'CLT0003', 'Klien mengajukan permintaan konsultasi dengan nomor ID APT1911190006', 'APT1911190006', 'N', '2019-11-19 15:01:37'),
(53, 'CLT0001', 'Konsultasi dengan nomor APT1911190006 diterima', 'APT1911190006', 'R', '2019-11-19 15:02:43'),
(54, 'CL0001', 'Konsultasi dengan nomor APT1911190006 diterima', 'APT1911190006', 'R', '2019-11-19 15:02:43'),
(55, 'CL0001', 'Anda menerima jadwal undangan konsultasi dengan nomor ID APT1911190007', 'APT1911190007', 'R', '2019-11-19 16:40:13'),
(56, 'CLT0002', 'Konsultasi dengan nomor APT1911190007 diterima', 'APT1911190007', 'R', '2019-11-19 16:48:59'),
(57, 'CL0001', 'Konsultasi dengan nomor APT1911190007 diterima', 'APT1911190007', 'R', '2019-11-19 16:48:59'),
(58, 'CLT0002', 'Konsultasi dengan nomor APT1911190007 sudah terpecahkan', 'APT1911190007', 'R', '2019-11-20 02:21:15'),
(59, 'CL0001', 'Konsultasi dengan nomor APT1911190007 sudah terpecahkan', 'APT1911190007', 'R', '2019-11-20 02:21:15'),
(60, 'CLT0002', 'Konsultasi dengan nomor APT1911190007 sudah selesai', 'APT1911190007', 'R', '2019-11-20 02:22:23'),
(61, 'CL0001', 'Konsultasi dengan nomor APT1911190007 sudah selesai', 'APT1911190007', 'R', '2019-11-20 02:22:23'),
(62, 'CL0001', 'Anda menerima jadwal undangan konsultasi dengan nomor ID APT1911200008', 'APT1911200008', 'R', '2019-11-20 03:11:26'),
(63, 'CL0001', 'Anda menerima jadwal undangan konsultasi dengan nomor ID APT1911230008', 'APT1911230008', 'R', '2019-11-23 19:32:38');

-- --------------------------------------------------------

--
-- Struktur dari tabel `province`
--

DROP TABLE IF EXISTS `province`;
CREATE TABLE `province` (
  `province_id` char(2) NOT NULL,
  `province_name` varchar(128) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data untuk tabel `province`
--

INSERT INTO `province` (`province_id`, `province_name`) VALUES
('BT', 'Banten'),
('JB', 'Jawa Barat'),
('JK', 'DKI Jakarta'),
('JT', 'Jawa Tengah'),
('TS', 'TESTING');

-- --------------------------------------------------------

--
-- Struktur dari tabel `service_topic`
--

DROP TABLE IF EXISTS `service_topic`;
CREATE TABLE `service_topic` (
  `topic_id` varchar(6) NOT NULL,
  `topic_name` varchar(64) NOT NULL,
  `seqid` tinyint(3) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data untuk tabel `service_topic`
--

INSERT INTO `service_topic` (`topic_id`, `topic_name`, `seqid`) VALUES
('TPC001', 'Audit Pajak', 1),
('TPC002', 'Pengaduan Pajak', 2),
('TPC003', 'Perencanaan Pajak', 3),
('TPC004', 'Penghindaran Pajak', 4),
('TPC005', 'Pengampunan Pajak', 5);

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `admin_user`
--
ALTER TABLE `admin_user`
  ADD PRIMARY KEY (`admin_id`),
  ADD UNIQUE KEY `admin_email` (`admin_email`),
  ADD UNIQUE KEY `seqid` (`seqid`);

--
-- Indeks untuk tabel `appointment_request`
--
ALTER TABLE `appointment_request`
  ADD PRIMARY KEY (`apt_id`),
  ADD UNIQUE KEY `seqid` (`seqid`),
  ADD KEY `fk_req_consultant_idx` (`consultant_id`),
  ADD KEY `fk_req_client_idx` (`client_id`);

--
-- Indeks untuk tabel `city`
--
ALTER TABLE `city`
  ADD PRIMARY KEY (`city_id`),
  ADD KEY `fk_city_province_idx` (`province_id`);

--
-- Indeks untuk tabel `client_user`
--
ALTER TABLE `client_user`
  ADD PRIMARY KEY (`client_id`),
  ADD UNIQUE KEY `client_email` (`client_email`),
  ADD UNIQUE KEY `seqid` (`seqid`);

--
-- Indeks untuk tabel `consultant_user`
--
ALTER TABLE `consultant_user`
  ADD PRIMARY KEY (`consultant_id`),
  ADD UNIQUE KEY `consultant_email` (`consultant_email`),
  ADD UNIQUE KEY `seqid` (`seqid`);

--
-- Indeks untuk tabel `conversation_chat`
--
ALTER TABLE `conversation_chat`
  ADD PRIMARY KEY (`chat_id`),
  ADD UNIQUE KEY `seqid` (`seqid`),
  ADD KEY `fk_chat_client_id` (`client_id`),
  ADD KEY `fk_chat_consultant_id` (`consultant_id`);

--
-- Indeks untuk tabel `event_schedule`
--
ALTER TABLE `event_schedule`
  ADD PRIMARY KEY (`evt_id`),
  ADD UNIQUE KEY `seqid` (`seqid`),
  ADD KEY `fk_evt_consultant_idx` (`consultant_id`);

--
-- Indeks untuk tabel `feedbacks`
--
ALTER TABLE `feedbacks`
  ADD PRIMARY KEY (`fd_id`),
  ADD UNIQUE KEY `seqid` (`seqid`),
  ADD KEY `fk_fd_aptidx` (`apt_id`);

--
-- Indeks untuk tabel `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_chat_id` (`chat_id`);

--
-- Indeks untuk tabel `notification`
--
ALTER TABLE `notification`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `province`
--
ALTER TABLE `province`
  ADD PRIMARY KEY (`province_id`);

--
-- Indeks untuk tabel `service_topic`
--
ALTER TABLE `service_topic`
  ADD PRIMARY KEY (`topic_id`),
  ADD UNIQUE KEY `seqid` (`seqid`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `admin_user`
--
ALTER TABLE `admin_user`
  MODIFY `seqid` smallint(4) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT untuk tabel `appointment_request`
--
ALTER TABLE `appointment_request`
  MODIFY `seqid` smallint(4) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT untuk tabel `client_user`
--
ALTER TABLE `client_user`
  MODIFY `seqid` smallint(4) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT untuk tabel `consultant_user`
--
ALTER TABLE `consultant_user`
  MODIFY `seqid` smallint(5) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT untuk tabel `conversation_chat`
--
ALTER TABLE `conversation_chat`
  MODIFY `seqid` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT untuk tabel `event_schedule`
--
ALTER TABLE `event_schedule`
  MODIFY `seqid` smallint(4) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT untuk tabel `feedbacks`
--
ALTER TABLE `feedbacks`
  MODIFY `seqid` smallint(4) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT untuk tabel `messages`
--
ALTER TABLE `messages`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT untuk tabel `notification`
--
ALTER TABLE `notification`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=64;

--
-- AUTO_INCREMENT untuk tabel `service_topic`
--
ALTER TABLE `service_topic`
  MODIFY `seqid` tinyint(3) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `appointment_request`
--
ALTER TABLE `appointment_request`
  ADD CONSTRAINT `fk_req_client_idx` FOREIGN KEY (`client_id`) REFERENCES `client_user` (`client_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_req_consultant_idx` FOREIGN KEY (`consultant_id`) REFERENCES `consultant_user` (`consultant_id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `city`
--
ALTER TABLE `city`
  ADD CONSTRAINT `fk_city_province_idx` FOREIGN KEY (`province_id`) REFERENCES `province` (`province_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `conversation_chat`
--
ALTER TABLE `conversation_chat`
  ADD CONSTRAINT `fk_chat_client_id` FOREIGN KEY (`client_id`) REFERENCES `client_user` (`client_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_chat_consultant_id` FOREIGN KEY (`consultant_id`) REFERENCES `consultant_user` (`consultant_id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `event_schedule`
--
ALTER TABLE `event_schedule`
  ADD CONSTRAINT `fk_evt_consultant_idx` FOREIGN KEY (`consultant_id`) REFERENCES `consultant_user` (`consultant_id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `feedbacks`
--
ALTER TABLE `feedbacks`
  ADD CONSTRAINT `fk_fd_aptidx` FOREIGN KEY (`apt_id`) REFERENCES `appointment_request` (`apt_id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `messages`
--
ALTER TABLE `messages`
  ADD CONSTRAINT `fk_chat_id` FOREIGN KEY (`chat_id`) REFERENCES `conversation_chat` (`chat_id`) ON DELETE CASCADE;
COMMIT;
