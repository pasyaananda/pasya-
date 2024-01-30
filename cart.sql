-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 27 Nov 2023 pada 04.54
-- Versi server: 10.4.28-MariaDB
-- Versi PHP: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `cart`
--

DELIMITER $$
--
-- Prosedur
--
CREATE DEFINER=`root`@`localhost` PROCEDURE `AddBarang` (IN `nama_brg` VARCHAR(11), IN `harga` INT(11), IN `stok` INT(11))   BEGIN 
 INSERT INTO barang VALUES (null, nama_brg, stok, harga);
 END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `getbarang` (IN `jml_stok` INT(11), IN `cek_harga` INT(11))   BEGIN
 SELECT * FROM barang WHERE stok < jml_stok AND harga < cek_harga; 
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `LuasSegitiga` (IN `alas` INT, IN `tinggi` INT)   BEGIN
DECLARE hasil double;

set hasil = (alas * tinggi)/2;
SELECT hasil as luas;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `trans1` ()   BEGIN
     START TRANSACTION;
     INSERT into barang VALUES ('3','sosis','15000','50');
     SAVEPOINT point1;
     
     insert into barang VALUES('8','taro','25000','70');
     ROLLBACK to SAVEPOINT point1;
     
     insert into barang values('9','milky','30000','80');
     COMMIT;
     
     SELECT * FROM barang;
     
END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Struktur dari tabel `barang`
--

CREATE TABLE `barang` (
  `id_brg` int(10) NOT NULL,
  `nama_brg` varchar(255) NOT NULL,
  `harga` int(255) NOT NULL,
  `stok` int(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `barang`
--

INSERT INTO `barang` (`id_brg`, `nama_brg`, `harga`, `stok`) VALUES
(3, 'sosis', 15000, 50),
(5, 'eskrim', 5000, 1898),
(6, 'macaron', 20000, 850),
(7, 'sosis', 15000, 500),
(9, 'milky', 30000, 80),
(11, 'bengbeng', 5000, 30);

-- --------------------------------------------------------

--
-- Struktur dari tabel `log_pembelian`
--

CREATE TABLE `log_pembelian` (
  `id_op` int(10) NOT NULL,
  `operasi` varchar(25) NOT NULL,
  `waktu` date NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `log_pembelian`
--

INSERT INTO `log_pembelian` (`id_op`, `operasi`, `waktu`) VALUES
(2, 'Insert', '2023-10-26'),
(3, 'Insert', '2023-10-26'),
(4, 'Insert', '2023-10-26');

-- --------------------------------------------------------

--
-- Struktur dari tabel `pembayaran`
--

CREATE TABLE `pembayaran` (
  `id_pem` int(10) NOT NULL,
  `jumlah_pem` int(15) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `pembayaran`
--

INSERT INTO `pembayaran` (`id_pem`, `jumlah_pem`) VALUES
(9, 2800000),
(10, 475000),
(11, 10000);

-- --------------------------------------------------------

--
-- Struktur dari tabel `restock`
--

CREATE TABLE `restock` (
  `id_restock` int(20) NOT NULL,
  `id_brg` int(10) NOT NULL,
  `tgl` date NOT NULL DEFAULT current_timestamp(),
  `jml` int(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Trigger `restock`
--
DELIMITER $$
CREATE TRIGGER `restock` AFTER INSERT ON `restock` FOR EACH ROW BEGIN
UPDATE barang SET stok = stok + new.jml
WHERE id_brg = new.id_brg;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Struktur dari tabel `transaksi`
--

CREATE TABLE `transaksi` (
  `id_transaksi` int(20) NOT NULL,
  `id_brg` int(10) NOT NULL,
  `jml` int(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `transaksi`
--

INSERT INTO `transaksi` (`id_transaksi`, `id_brg`, `jml`) VALUES
(19, 6, 140),
(20, 5, 95),
(21, 5, 2);

--
-- Trigger `transaksi`
--
DELIMITER $$
CREATE TRIGGER `log_delete` BEFORE DELETE ON `transaksi` FOR EACH ROW BEGIN
    INSERT INTO log_pembelian (operasi, waktu) VALUES ('Delete', NOW());
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `log_insert` AFTER INSERT ON `transaksi` FOR EACH ROW BEGIN
INSERT INTO log_pembelian (operasi, waktu) VALUES ('Insert', NOW());
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `log_update` AFTER UPDATE ON `transaksi` FOR EACH ROW BEGIN
    INSERT INTO log_pembelian (operasi, waktu) VALUES ('Update', NOW());
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `nota` AFTER INSERT ON `transaksi` FOR EACH ROW BEGIN
DECLARE totalharga INT;
DECLARE hargatotal INT;

SELECT harga INTO totalharga FROM barang WHERE id_brg = new.id_brg;
SET hargatotal = new.jml * totalharga;

INSERT INTO pembayaran (id_pem,jumlah_pem) VALUES (NULL,hargatotal);
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `updatestok` AFTER INSERT ON `transaksi` FOR EACH ROW BEGIN
    DECLARE bonus INT;

    -- Calculate the bonus based on new.jml
    IF new.jml BETWEEN 50 AND 99 THEN
        SET bonus = new.jml + 5;
    ELSEIF new.jml BETWEEN 100 AND 149 THEN
        SET bonus = new.jml + 10;
    ELSEIF new.jml >= 150 THEN
        SET bonus = new.jml + 20;
    ELSE
        SET bonus = new.jml;
    END IF;

    -- Update the stock in the "barang" table
    UPDATE barang
    SET stok = stok - bonus
    WHERE id_brg = new.id_brg;

END
$$
DELIMITER ;

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `barang`
--
ALTER TABLE `barang`
  ADD PRIMARY KEY (`id_brg`);

--
-- Indeks untuk tabel `log_pembelian`
--
ALTER TABLE `log_pembelian`
  ADD PRIMARY KEY (`id_op`);

--
-- Indeks untuk tabel `pembayaran`
--
ALTER TABLE `pembayaran`
  ADD PRIMARY KEY (`id_pem`);

--
-- Indeks untuk tabel `restock`
--
ALTER TABLE `restock`
  ADD PRIMARY KEY (`id_restock`),
  ADD KEY `id_brg` (`id_brg`);

--
-- Indeks untuk tabel `transaksi`
--
ALTER TABLE `transaksi`
  ADD PRIMARY KEY (`id_transaksi`),
  ADD KEY `id_brg` (`id_brg`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `barang`
--
ALTER TABLE `barang`
  MODIFY `id_brg` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT untuk tabel `log_pembelian`
--
ALTER TABLE `log_pembelian`
  MODIFY `id_op` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT untuk tabel `pembayaran`
--
ALTER TABLE `pembayaran`
  MODIFY `id_pem` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT untuk tabel `restock`
--
ALTER TABLE `restock`
  MODIFY `id_restock` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT untuk tabel `transaksi`
--
ALTER TABLE `transaksi`
  MODIFY `id_transaksi` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `restock`
--
ALTER TABLE `restock`
  ADD CONSTRAINT `restock_ibfk_1` FOREIGN KEY (`id_brg`) REFERENCES `barang` (`id_brg`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `transaksi`
--
ALTER TABLE `transaksi`
  ADD CONSTRAINT `transaksi_ibfk_1` FOREIGN KEY (`id_brg`) REFERENCES `barang` (`id_brg`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
