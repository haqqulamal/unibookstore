<?php

include 'config/koneksi.php';

// Get search parameter
$search = isset($_GET['search']) ? mysqli_real_escape_string($koneksi, $_GET['search']) : '';

// Build query with JOIN to get publisher name
$query = "SELECT b.id_buku, b.kategori, b.nama_buku, b.harga, b.stok, p.nama as nama_penerbit, b.gambar 
          FROM buku b 
          JOIN penerbit p ON b.id_penerbit = p.id_penerbit";

// Add search filter if search term exists
if (!empty($search)) {
    $query .= " WHERE b.nama_buku LIKE '%$search%' OR b.kategori LIKE '%$search%'";
}

$query .= " ORDER BY b.id_buku ASC";

// Execute query
$result = mysqli_query($koneksi, $query);

// Check if query was successful
if (!$result) {
    die("Query Error: " . mysqli_error($koneksi));
}

// Get total records
$total_records = mysqli_num_rows($result);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UNIBOOKSTORE - Home</title>
    
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
                        <a class="nav-link active" href="index.php">HOME</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="admin.php">ADMIN</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="pengadaan.php">PENGADAAN</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Main Container -->
    <div class="container container-main">
        <h1 class="page-title">📖 Daftar Buku</h1>

        <!-- Search Form -->
        <div class="row mb-4">
            <div class="col-md-8">
                <form method="GET" class="search-box">
                    <div class="input-group">
                        <input 
                            type="text" 
                            name="search" 
                            class="form-control" 
                            placeholder="Cari berdasarkan nama buku atau kategori..."
                            value="<?php echo htmlspecialchars($search); ?>"
                        >
                        <button class="btn btn-search" type="submit">Cari</button>
                        <?php if (!empty($search)): ?>
                            <a href="index.php" class="btn btn-secondary">Reset</a>
                        <?php endif; ?>
                    </div>
                </form>
            </div>
            <div class="col-md-4">
                <p class="text-muted">
                    <small>Total: <?php echo $total_records; ?> buku ditemukan</small>
                </p>
            </div>
        </div>

        <!-- Table -->
        <?php if ($total_records > 0): ?>
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>ID Buku</th>
                            <th>Gambar</th>
                            <th>Kategori</th>
                            <th>Nama Buku</th>
                            <th>Harga</th>
                            <th>Stok</th>
                            <th>Penerbit</th>
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
                                    <?php 
                                        // Color coding for stock
                                        if ($row['stok'] <= 2) {
                                            echo "<span class='badge bg-danger'>" . $row['stok'] . "</span>";
                                        } elseif ($row['stok'] <= 5) {
                                            echo "<span class='badge bg-warning'>" . $row['stok'] . "</span>";
                                        } else {
                                            echo "<span class='badge bg-success'>" . $row['stok'] . "</span>";
                                        }
                                    ?>
                                </td>
                                <td><?php echo htmlspecialchars($row['nama_penerbit']); ?></td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <div class="alert alert-warning" role="alert">
                <strong>Tidak ada data!</strong> 
                <?php 
                    if (!empty($search)) {
                        echo "Buku dengan pencarian \"" . htmlspecialchars($search) . "\" tidak ditemukan.";
                    }
                ?>
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
// Close database connection
mysqli_close($koneksi);
?>
