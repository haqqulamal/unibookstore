-- UNIBOOKSTORE Database Schema
-- Database: unibookstore
-- Created for PHP Native Project

-- Create Database
CREATE DATABASE IF NOT EXISTS `unibookstore`;
USE `unibookstore`;

-- ============================================
-- Table: penerbit (Publisher)
-- ============================================
CREATE TABLE IF NOT EXISTS `penerbit` (
  `id_penerbit` varchar(10) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `alamat` text NOT NULL,
  `kota` varchar(50) NOT NULL,
  `telepon` varchar(15) NOT NULL,
  PRIMARY KEY (`id_penerbit`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Insert data into penerbit table
INSERT INTO `penerbit` (`id_penerbit`, `nama`, `alamat`, `kota`, `telepon`) VALUES
('P001', 'Gramedia', 'Jl. Ahmad Yani No. 50', 'Jakarta', '021-123456'),
('P002', 'Erlangga', 'Jl. H. Samanhudi No. 45', 'Jakarta', '021-234567'),
('P003', 'Mizan', 'Jl. Permata No. 12', 'Bandung', '022-345678'),
('P004', 'Kompas Gramedia', 'Jl. Palmerah Barat 33', 'Jakarta', '021-456789'),
('P005', 'Gajah Mada University Press', 'Jl. Sekip Kalasan', 'Yogyakarta', '0274-567890');

-- ============================================
-- Table: buku (Book)
-- ============================================
CREATE TABLE IF NOT EXISTS `buku` (
  `id_buku` varchar(10) NOT NULL,
  `kategori` varchar(50) NOT NULL,
  `nama_buku` varchar(150) NOT NULL,
  `harga` int(11) NOT NULL,
  `stok` int(11) NOT NULL,
  `id_penerbit` varchar(10) NOT NULL,
  PRIMARY KEY (`id_buku`),
  KEY `id_penerbit` (`id_penerbit`),
  CONSTRAINT `buku_ibfk_1` FOREIGN KEY (`id_penerbit`) REFERENCES `penerbit` (`id_penerbit`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Insert data into buku table
INSERT INTO `buku` (`id_buku`, `kategori`, `nama_buku`, `harga`, `stok`, `id_penerbit`) VALUES
('B001', 'Programming', 'Learn PHP from Basic to Advanced', 125000, 15, 'P001'),
('B002', 'Programming', 'Web Development with HTML5 & CSS3', 95000, 8, 'P002'),
('B003', 'Database', 'MySQL Complete Guide', 110000, 12, 'P001'),
('B004', 'Programming', 'JavaScript: The Good Parts', 85000, 5, 'P003'),
('B005', 'Design', 'UI/UX Design Principles', 99000, 3, 'P002'),
('B006', 'Database', 'Advanced Database Design', 145000, 7, 'P004'),
('B007', 'Programming', 'Python for Data Science', 130000, 2, 'P005'),
('B008', 'Business', 'Digital Marketing Strategy', 87000, 9, 'P001'),
('B009', 'Programming', 'Responsive Web Design', 105000, 6, 'P003'),
('B010', 'Security', 'Web Security Essentials', 120000, 4, 'P002'),
('B011', 'Framework', 'Laravel Framework Mastery', 128000, 10, 'P004'),
('B012', 'Mobile', 'Android Development Guide', 135000, 1, 'P005');

-- ============================================
-- Foreign Key Relationships Check
-- ============================================
-- ALTER TABLE `buku` ADD CONSTRAINT `buku_ibfk_1` 
-- FOREIGN KEY (`id_penerbit`) REFERENCES `penerbit` (`id_penerbit`) 
-- ON DELETE CASCADE;
