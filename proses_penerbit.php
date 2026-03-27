<?php
/**
 * UNIBOOKSTORE - Publisher Processing
 * File: proses_penerbit.php
 * 
 * Fitur:
 * - ADD: Tambah penerbit baru
 * - EDIT: Edit data penerbit
 * - DELETE: Hapus penerbit
 */

include 'config/koneksi.php';

// Get action from URL/POST
$action = isset($_GET['action']) ? $_GET['action'] : (isset($_POST['action']) ? $_POST['action'] : '');

// Initialize variables
$success_message = '';
$error_message = '';

try {
    // ========== ADD NEW PUBLISHER ==========
    if ($action == 'add') {
        // Get POST data
        $id_penerbit = mysqli_real_escape_string($koneksi, $_POST['id_penerbit']);
        $nama = mysqli_real_escape_string($koneksi, $_POST['nama']);
        $alamat = mysqli_real_escape_string($koneksi, $_POST['alamat']);
        $kota = mysqli_real_escape_string($koneksi, $_POST['kota']);
        $telepon = mysqli_real_escape_string($koneksi, $_POST['telepon']);

        // Validate input
        if (empty($id_penerbit) || empty($nama) || empty($alamat) || empty($kota) || empty($telepon)) {
            throw new Exception("Semua field harus diisi!");
        }

        // Check if publisher ID already exists
        $check = mysqli_query($koneksi, "SELECT * FROM penerbit WHERE id_penerbit = '$id_penerbit'");
        if (mysqli_num_rows($check) > 0) {
            throw new Exception("ID Penerbit sudah ada!");
        }

        // Insert query
        $query = "INSERT INTO penerbit (id_penerbit, nama, alamat, kota, telepon) 
                  VALUES ('$id_penerbit', '$nama', '$alamat', '$kota', '$telepon')";

        if (!mysqli_query($koneksi, $query)) {
            throw new Exception("Error: " . mysqli_error($koneksi));
        }

        $success_message = "Penerbit berhasil ditambahkan!";

    // ========== EDIT PUBLISHER ==========
    } elseif ($action == 'edit') {
        // Get POST data
        $id_penerbit = mysqli_real_escape_string($koneksi, $_POST['id_penerbit']);
        $nama = mysqli_real_escape_string($koneksi, $_POST['nama']);
        $alamat = mysqli_real_escape_string($koneksi, $_POST['alamat']);
        $kota = mysqli_real_escape_string($koneksi, $_POST['kota']);
        $telepon = mysqli_real_escape_string($koneksi, $_POST['telepon']);

        // Validate input
        if (empty($id_penerbit) || empty($nama) || empty($alamat) || empty($kota) || empty($telepon)) {
            throw new Exception("Semua field harus diisi!");
        }

        // Update query
        $query = "UPDATE penerbit SET nama = '$nama', alamat = '$alamat', kota = '$kota', 
                  telepon = '$telepon' WHERE id_penerbit = '$id_penerbit'";

        if (!mysqli_query($koneksi, $query)) {
            throw new Exception("Error: " . mysqli_error($koneksi));
        }

        $success_message = "Penerbit berhasil diperbarui!";

    // ========== DELETE PUBLISHER ==========
    } elseif ($action == 'delete') {
        // Get ID from URL
        $id_penerbit = mysqli_real_escape_string($koneksi, $_GET['id']);

        // Check if publisher exists
        $check = mysqli_query($koneksi, "SELECT * FROM penerbit WHERE id_penerbit = '$id_penerbit'");
        if (mysqli_num_rows($check) == 0) {
            throw new Exception("Penerbit tidak ditemukan!");
        }

        // Check if publisher has books (due to FK constraint)
        $books = mysqli_query($koneksi, "SELECT COUNT(*) as total FROM buku WHERE id_penerbit = '$id_penerbit'");
        $b_row = mysqli_fetch_assoc($books);
        if ($b_row['total'] > 0) {
            throw new Exception("Tidak dapat menghapus penerbit yang masih memiliki buku (" . $b_row['total'] . " buku)!");
        }

        // Delete query
        $query = "DELETE FROM penerbit WHERE id_penerbit = '$id_penerbit'";

        if (!mysqli_query($koneksi, $query)) {
            throw new Exception("Error: " . mysqli_error($koneksi));
        }

        $success_message = "Penerbit berhasil dihapus!";

    } else {
        throw new Exception("Action tidak valid!");
    }

} catch (Exception $e) {
    $error_message = $e->getMessage();
}

// Redirect back to admin page with message
if (!empty($success_message)) {
    header("Location: admin.php?tab=penerbit&success=" . urlencode($success_message));
} else {
    header("Location: admin.php?tab=penerbit&error=" . urlencode($error_message));
}

mysqli_close($koneksi);
exit;
?>
