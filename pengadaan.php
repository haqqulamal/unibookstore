<?php

include 'config/koneksi.php';

// Query untuk mendapatkan buku dengan stok terendah
$query = "SELECT b.id_buku, b.kategori, b.nama_buku, b.harga, b.stok, p.nama as nama_penerbit, b.gambar 
          FROM buku b 
          JOIN penerbit p ON b.id_penerbit = p.id_penerbit 
          ORDER BY b.stok ASC 
          LIMIT 10";

$result = mysqli_query($koneksi, $query);

if (!$result) {
    die("Query Error: " . mysqli_error($koneksi));
}

$total_rendah = mysqli_num_rows($result);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UNIBOOKSTORE - Pengadaan</title>
    
    <!-- Bootstrap CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="index.php">📚 UNIBOOKSTORE</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="index.php">HOME</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="admin.php">ADMIN</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="pengadaan.php">PENGADAAN</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Main Container -->
    <div class="container container-main">
        <h1 class="page-title">📦 Laporan Pengadaan - Stok Terendah</h1>

        <!-- Info Card -->
        <div class="info-card">
            <p>
                <strong>Informasi:</strong> Halaman ini menampilkan daftar buku dengan stok paling sedikit. 
                Gunakan laporan ini untuk merencanakan pengadaan buku baru.
            </p>
            <p class="mb-0">
                <span class="badge bg-info">Total Buku Dengan Stok Rendah: <?php echo $total_rendah; ?></span>
            </p>
        </div>

        <!-- Table -->
        <?php if ($total_rendah > 0): ?>
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>ID Buku</th>
                            <th>Gambar</th>
                            <th>Kategori</th>
                            <th>Nama Buku</th>
                            <th>Harga</th>
                            <th>Stok Saat Ini</th>
                            <th>Penerbit</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = mysqli_fetch_assoc($result)): ?>
                            <tr>
                                <td><strong><?php echo htmlspecialchars($row['id_buku']); ?></strong></td>
                                <td>
                                    <?php if (!empty($row['gambar'])): ?>
                                        <img src="assets/pictures/<?php echo htmlspecialchars($row['gambar']); ?>" 
                                             alt="<?php echo htmlspecialchars($row['nama_buku']); ?>" 
                                             style="height: 60px; width: auto; border-radius: 4px;">
                                    <?php else: ?>
                                        <span class="text-muted">-</span>
                                    <?php endif; ?>
                                </td>
                                <td><?php echo htmlspecialchars($row['kategori']); ?></td>
                                <td><?php echo htmlspecialchars($row['nama_buku']); ?></td>
                                <td>Rp <?php echo number_format($row['harga'], 0, ',', '.'); ?></td>
                                <td>
                                    <strong>
                                        <?php 
                                            // Color and status based on stock
                                            if ($row['stok'] == 0) {
                                                echo "<span class='badge bg-danger'>HABIS (" . $row['stok'] . ")</span>";
                                            } elseif ($row['stok'] <= 2) {
                                                echo "<span class='badge bg-danger'>KRITIS (" . $row['stok'] . ")</span>";
                                            } elseif ($row['stok'] <= 5) {
                                                echo "<span class='badge bg-warning'>RENDAH (" . $row['stok'] . ")</span>";
                                            } else {
                                                echo "<span class='badge bg-info'>NORMAL (" . $row['stok'] . ")</span>";
                                            }
                                        ?>
                                    </strong>
                                </td>
                                <td><?php echo htmlspecialchars($row['nama_penerbit']); ?></td>
                                <td>
                                    <?php 
                                        if ($row['stok'] == 0) {
                                            echo "<span class='badge bg-danger'>PERLU SEGERA DIISI</span>";
                                        } elseif ($row['stok'] <= 2) {
                                            echo "<span class='badge bg-danger'>URGENT</span>";
                                        } elseif ($row['stok'] <= 5) {
                                            echo "<span class='badge bg-warning'>PESAN SEGERA</span>";
                                        } else {
                                            echo "<span class='badge bg-success'>OK</span>";
                                        }
                                    ?>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>

            <!-- Summary -->
            <div class="row mt-4">
                <div class="col-md-4">
                    <?php 
                    $critical = mysqli_query($koneksi, "SELECT COUNT(*) as total FROM buku WHERE stok <= 2");
                    $c_row = mysqli_fetch_assoc($critical);
                    ?>
                    <div class="info-card">
                        <h5>🔴 Stok Kritis</h5>
                        <p class="mb-0"><strong><?php echo $c_row['total']; ?> buku</strong> (stok ≤ 2)</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <?php 
                    $warning = mysqli_query($koneksi, "SELECT COUNT(*) as total FROM buku WHERE stok > 2 AND stok <= 5");
                    $w_row = mysqli_fetch_assoc($warning);
                    ?>
                    <div class="info-card">
                        <h5>🟡 Stok Rendah</h5>
                        <p class="mb-0"><strong><?php echo $w_row['total']; ?> buku</strong> (stok 3-5)</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <?php 
                    $normal = mysqli_query($koneksi, "SELECT COUNT(*) as total FROM buku WHERE stok > 5");
                    $n_row = mysqli_fetch_assoc($normal);
                    ?>
                    <div class="info-card">
                        <h5>🟢 Stok Normal</h5>
                        <p class="mb-0"><strong><?php echo $n_row['total']; ?> buku</strong> (stok > 5)</p>
                    </div>
                </div>
            </div>

        <?php else: ?>
            <div class="alert alert-success">
                ✓ Semua buku memiliki stok yang cukup. Tidak ada pengadaan yang perlu dilakukan saat ini.
            </div>
        <?php endif; ?>

    </div>

    <!-- Footer -->
    <footer>
        <p>&copy; 2024 UNIBOOKSTORE. Semua hak dilindungi.</p>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php
mysqli_close($koneksi);
?>
