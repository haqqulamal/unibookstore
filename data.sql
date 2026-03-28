-- Create Database
CREATE DATABASE IF NOT EXISTS `unibookstore`;
USE `unibookstore`;

-- Table: penerbit (Publisher)
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
('SP01', 'Penerbit Informatika', 'Jl. Buah Batu No. 121', 'Bandung', '0813-2220-1946'),
('SP02', 'Andi Offset', 'Jl. Suryalaya IX No. 3', 'Bandung', '0878-3903-0688'),
('SP03', 'Danendra', 'Jl Moch. Toha 44', 'Bandung', '022-5201215');

-- Table: buku (Book)
CREATE TABLE IF NOT EXISTS `buku` (
  `id_buku` varchar(10) NOT NULL,
  `kategori` varchar(50) NOT NULL,
  `nama_buku` varchar(150) NOT NULL,
  `harga` int(11) NOT NULL,
  `stok` int(11) NOT NULL,
  `id_penerbit` varchar(10) NOT NULL,
  `gambar` varchar(255) NULL,
  PRIMARY KEY (`id_buku`),
  KEY `id_penerbit` (`id_penerbit`),
  CONSTRAINT `buku_ibfk_1` FOREIGN KEY (`id_penerbit`) REFERENCES `penerbit` (`id_penerbit`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Insert data into buku table
INSERT INTO `buku` (`id_buku`, `kategori`, `nama_buku`, `harga`, `stok`, `id_penerbit`, `gambar`) VALUES
('K1001', 'Buku Teks', 'Pengantar Teknologi Informasi', 55000, 60, 'SP01', 'analisis & perancangan sistem informasi.jpeg'),
('K1002', 'Keilmuan', 'Artificial Intelligence', 45000, 60, 'SP01', 'artifical intelligence.jpeg'),
('K2003', 'Keilmuan', 'Autocad 3 Dimensi', 40000, 35, 'SP01', 'autocad.jpeg'),
('B1001', 'Bisnis', 'Bisnis Online', 75000, 9, 'SP01', 'bisnis online.jpeg'),
('K3004', 'Keilmuan', 'Cloud Computing Technology', 85000, 15, 'SP01', 'cloud computing.jpeg'),
('B1002', 'Bisnis', 'Etika Bisnis dan Tanggung Jawab Sosial', 67500, 20, 'SP01', 'etika bisnis.jpeg'),
('N1001', 'Novel', 'Cahaya Di Penjuru Hati', 68000, 10, 'SP02', 'cahaya di penjuru hati.jpeg'),
('N1002', 'Novel', 'Aku Ingin Cerita', 48000, 12, 'SP03', 'aku ingin cerita.jpeg');

-- ============================================
-- Foreign Key Relationships Check
-- ============================================
-- ALTER TABLE `buku` ADD CONSTRAINT `buku_ibfk_1` 
-- FOREIGN KEY (`id_penerbit`) REFERENCES `penerbit` (`id_penerbit`) 
-- ON DELETE CASCADE;
