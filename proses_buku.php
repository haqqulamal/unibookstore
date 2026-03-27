<?php
/**
 * UNIBOOKSTORE - Book Processing
 * File: proses_buku.php
 * 
 * Fitur:
 * - ADD: Tambah buku baru
 * - EDIT: Edit data buku
 * - DELETE: Hapus buku
 */

include 'config/koneksi.php';

// Get action from URL/POST
$action = isset($_GET['action']) ? $_GET['action'] : (isset($_POST['action']) ? $_POST['action'] : '');

// Initialize variables
$success_message = '';
$error_message = '';

try {
    // ========== ADD NEW BOOK ==========
    if ($action == 'add') {
        // Get POST data
        $id_buku = mysqli_real_escape_string($koneksi, $_POST['id_buku']);
        $kategori = mysqli_real_escape_string($koneksi, $_POST['kategori']);
        $nama_buku = mysqli_real_escape_string($koneksi, $_POST['nama_buku']);
        $harga = mysqli_real_escape_string($koneksi, $_POST['harga']);
        $stok = mysqli_real_escape_string($koneksi, $_POST['stok']);
        $id_penerbit = mysqli_real_escape_string($koneksi, $_POST['id_penerbit']);

        // Validate input
        if (empty($id_buku) || empty($kategori) || empty($nama_buku) || empty($harga) || empty($stok) || empty($id_penerbit)) {
            throw new Exception("Semua field harus diisi!");
        }

        // Check if book ID already exists
        $check = mysqli_query($koneksi, "SELECT * FROM buku WHERE id_buku = '$id_buku'");
        if (mysqli_num_rows($check) > 0) {
            throw new Exception("ID Buku sudah ada!");
        }

        // Insert query
        $query = "INSERT INTO buku (id_buku, kategori, nama_buku, harga, stok, id_penerbit) 
                  VALUES ('$id_buku', '$kategori', '$nama_buku', '$harga', '$stok', '$id_penerbit')";

        if (!mysqli_query($koneksi, $query)) {
            throw new Exception("Error: " . mysqli_error($koneksi));
        }

        $success_message = "Buku berhasil ditambahkan!";

    // ========== EDIT BOOK ==========
    } elseif ($action == 'edit') {
        // Get POST data
        $id_buku = mysqli_real_escape_string($koneksi, $_POST['id_buku']);
        $kategori = mysqli_real_escape_string($koneksi, $_POST['kategori']);
        $nama_buku = mysqli_real_escape_string($koneksi, $_POST['nama_buku']);
        $harga = mysqli_real_escape_string($koneksi, $_POST['harga']);
        $stok = mysqli_real_escape_string($koneksi, $_POST['stok']);
        $id_penerbit = mysqli_real_escape_string($koneksi, $_POST['id_penerbit']);

        // Validate input
        if (empty($id_buku) || empty($kategori) || empty($nama_buku) || empty($harga) || empty($stok) || empty($id_penerbit)) {
            throw new Exception("Semua field harus diisi!");
        }

        // Update query
        $query = "UPDATE buku SET kategori = '$kategori', nama_buku = '$nama_buku', harga = '$harga', 
                  stok = '$stok', id_penerbit = '$id_penerbit' WHERE id_buku = '$id_buku'";

        if (!mysqli_query($koneksi, $query)) {
            throw new Exception("Error: " . mysqli_error($koneksi));
        }

        $success_message = "Buku berhasil diperbarui!";

    // ========== DELETE BOOK ==========
    } elseif ($action == 'delete') {
        // Get ID from URL
        $id_buku = mysqli_real_escape_string($koneksi, $_GET['id']);

        // Check if book exists
        $check = mysqli_query($koneksi, "SELECT * FROM buku WHERE id_buku = '$id_buku'");
        if (mysqli_num_rows($check) == 0) {
            throw new Exception("Buku tidak ditemukan!");
        }

        // Delete query
        $query = "DELETE FROM buku WHERE id_buku = '$id_buku'";

        if (!mysqli_query($koneksi, $query)) {
            throw new Exception("Error: " . mysqli_error($koneksi));
        }

        $success_message = "Buku berhasil dihapus!";

    } else {
        throw new Exception("Action tidak valid!");
    }

} catch (Exception $e) {
    $error_message = $e->getMessage();
}

// Redirect back to admin page with message
if (!empty($success_message)) {
    header("Location: admin.php?tab=buku&success=" . urlencode($success_message));
} else {
    header("Location: admin.php?tab=buku&error=" . urlencode($error_message));
}

mysqli_close($koneksi);
exit;
?>
