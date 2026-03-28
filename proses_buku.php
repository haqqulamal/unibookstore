<?php

include 'config/koneksi.php';

// Get action from URL/POST
$action = isset($_GET['action']) ? $_GET['action'] : (isset($_POST['action']) ? $_POST['action'] : '');

// Initialize variables
$success_message = '';
$error_message = '';

// Function to handle file upload
function handleGambarUpload() {
    if (!isset($_FILES['gambar']) || $_FILES['gambar']['error'] == UPLOAD_ERR_NO_FILE) {
        return null; // No file uploaded
    }

    if ($_FILES['gambar']['error'] != UPLOAD_ERR_OK) {
        throw new Exception("Error uploading file: " . $_FILES['gambar']['error']);
    }

    // Validation
    $allowed_types = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif'];
    if (!in_array($_FILES['gambar']['type'], $allowed_types)) {
        throw new Exception("Format gambar harus JPG, JPEG, PNG, atau GIF!");
    }

    if ($_FILES['gambar']['size'] > 2 * 1024 * 1024) { // 2MB max
        throw new Exception("Ukuran gambar tidak boleh lebih dari 2MB!");
    }

    // Get filename
    $filename = $_FILES['gambar']['name'];
    $target_dir = "assets/pictures/";
    $target_file = $target_dir . $filename;

    // Move uploaded file
    if (!move_uploaded_file($_FILES['gambar']['tmp_name'], $target_file)) {
        throw new Exception("Gagal menyimpan gambar!");
    }

    return $filename;
}

try {
    //  ADD NEW BOOK 
    if ($action == 'add') {
        // Get POST data
        $id_buku = mysqli_real_escape_string($koneksi, $_POST['id_buku']);
        $kategori = mysqli_real_escape_string($koneksi, $_POST['kategori']);
        $nama_buku = mysqli_real_escape_string($koneksi, $_POST['nama_buku']);
        $harga = mysqli_real_escape_string($koneksi, $_POST['harga']);
        $stok = mysqli_real_escape_string($koneksi, $_POST['stok']);
        $id_penerbit = mysqli_real_escape_string($koneksi, $_POST['id_penerbit']);
        $gambar = handleGambarUpload();
        $gambar_escaped = !empty($gambar) ? mysqli_real_escape_string($koneksi, $gambar) : null;

        // Validate input
        if (empty($id_buku) || empty($kategori) || empty($nama_buku) || empty($harga) || empty($stok) || empty($id_penerbit)) {
            throw new Exception("Semua field harus diisi!");
        }

        // Check if book ID already exists
        $check = mysqli_query($koneksi, "SELECT * FROM buku WHERE id_buku = '$id_buku'");
        if (mysqli_num_rows($check) > 0) {
            throw new Exception("ID Buku sudah ada!");
        }

        // Insert query with gambar
        if ($gambar_escaped) {
            $query = "INSERT INTO buku (id_buku, kategori, nama_buku, harga, stok, id_penerbit, gambar) 
                      VALUES ('$id_buku', '$kategori', '$nama_buku', '$harga', '$stok', '$id_penerbit', '$gambar_escaped')";
        } else {
            $query = "INSERT INTO buku (id_buku, kategori, nama_buku, harga, stok, id_penerbit) 
                      VALUES ('$id_buku', '$kategori', '$nama_buku', '$harga', '$stok', '$id_penerbit')";
        }

        if (!mysqli_query($koneksi, $query)) {
            throw new Exception("Error: " . mysqli_error($koneksi));
        }

        $success_message = "Buku berhasil ditambahkan!";

    //  EDIT BOOK 
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

        // Handle gambar upload if provided
        $gambar_new = handleGambarUpload();
        
        if ($gambar_new) {
            // If new gambar uploaded, update with new gambar
            $gambar_escaped = mysqli_real_escape_string($koneksi, $gambar_new);
            $query = "UPDATE buku SET kategori = '$kategori', nama_buku = '$nama_buku', harga = '$harga', 
                      stok = '$stok', id_penerbit = '$id_penerbit', gambar = '$gambar_escaped' WHERE id_buku = '$id_buku'";
        } else {
            // If no new gambar, keep existing gambar
            $query = "UPDATE buku SET kategori = '$kategori', nama_buku = '$nama_buku', harga = '$harga', 
                      stok = '$stok', id_penerbit = '$id_penerbit' WHERE id_buku = '$id_buku'";
        }

        if (!mysqli_query($koneksi, $query)) {
            throw new Exception("Error: " . mysqli_error($koneksi));
        }

        $success_message = "Buku berhasil diperbarui!";

    //  DELETE BOOK 
    } elseif ($action == 'delete') {
        // Get ID from URL
        $id_buku = mysqli_real_escape_string($koneksi, $_GET['id']);

        // Check if book exists and get gambar filename
        $check = mysqli_query($koneksi, "SELECT gambar FROM buku WHERE id_buku = '$id_buku'");
        if (mysqli_num_rows($check) == 0) {
            throw new Exception("Buku tidak ditemukan!");
        }

        $book = mysqli_fetch_assoc($check);
        
        // Delete the image file if exists
        if (!empty($book['gambar'])) {
            $image_path = "assets/pictures/" . $book['gambar'];
            if (file_exists($image_path)) {
                unlink($image_path);
            }
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
