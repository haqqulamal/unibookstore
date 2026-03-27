<?php
/**
 * UNIBOOKSTORE - Admin Panel
 * File: admin.php
 * 
 * Fitur:
 * - Kelola data buku (CRUD)
 * - Kelola data penerbit (CRUD)
 * - Tampilkan dalam tabel
 * - Tombol: Tambah, Edit, Delete
 */

include 'config/koneksi.php';

// Get active tab from URL
$tab = isset($_GET['tab']) ? $_GET['tab'] : 'buku';

// Get success/error messages
$success = isset($_GET['success']) ? $_GET['success'] : '';
$error = isset($_GET['error']) ? $_GET['error'] : '';
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UNIBOOKSTORE - Admin Panel</title>
    
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
                        <a class="nav-link active" href="admin.php">ADMIN</a>
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
        <h1 class="page-title">🔧 Panel Admin</h1>

        <!-- Alert Messages -->
        <?php if (!empty($success)): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                ✓ <?php echo htmlspecialchars($success); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <?php if (!empty($error)): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                ✗ <?php echo htmlspecialchars($error); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <!-- Tabs -->
        <ul class="nav nav-tabs mb-4" role="tablist">
            <li class="nav-item">
                <a class="nav-link <?php echo ($tab == 'buku') ? 'active' : ''; ?>" 
                   href="admin.php?tab=buku">
                   📖 Kelola Buku
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?php echo ($tab == 'penerbit') ? 'active' : ''; ?>" 
                   href="admin.php?tab=penerbit">
                   🏢 Kelola Penerbit
                </a>
            </li>
        </ul>

        <!-- ========== TAB 1: Kelola Buku ========== -->
        <?php if ($tab == 'buku'): ?>
            <div class="tab-pane active">
                <h2 class="mb-3">Daftar Buku</h2>

                <!-- Add Button -->
                <div class="add-button-section">
                    <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modalTambahBuku">
                        ➕ Tambah Buku
                    </button>
                </div>

                <!-- Table -->
                <?php
                $query_buku = "SELECT b.id_buku, b.kategori, b.nama_buku, b.harga, b.stok, p.nama as nama_penerbit, b.id_penerbit 
                              FROM buku b 
                              JOIN penerbit p ON b.id_penerbit = p.id_penerbit 
                              ORDER BY b.id_buku ASC";
                $result_buku = mysqli_query($koneksi, $query_buku);
                $total_buku = mysqli_num_rows($result_buku);
                ?>

                <?php if ($total_buku > 0): ?>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Kategori</th>
                                    <th>Nama Buku</th>
                                    <th>Harga</th>
                                    <th>Stok</th>
                                    <th>Penerbit</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while ($row = mysqli_fetch_assoc($result_buku)): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($row['id_buku']); ?></td>
                                        <td><?php echo htmlspecialchars($row['kategori']); ?></td>
                                        <td><?php echo htmlspecialchars($row['nama_buku']); ?></td>
                                        <td>Rp <?php echo number_format($row['harga'], 0, ',', '.'); ?></td>
                                        <td><?php echo $row['stok']; ?></td>
                                        <td><?php echo htmlspecialchars($row['nama_penerbit']); ?></td>
                                        <td>
                                            <div class="action-buttons">
                                                <button class="btn btn-warning btn-sm" 
                                                    data-bs-toggle="modal" 
                                                    data-bs-target="#modalEditBuku"
                                                    onclick="editBuku('<?php echo $row['id_buku']; ?>', '<?php echo addslashes($row['kategori']); ?>', '<?php echo addslashes($row['nama_buku']); ?>', '<?php echo $row['harga']; ?>', '<?php echo $row['stok']; ?>', '<?php echo $row['id_penerbit']; ?>')">
                                                    ✏️ Edit
                                                </button>
                                                <a href="proses_buku.php?action=delete&id=<?php echo $row['id_buku']; ?>" 
                                                   class="btn btn-danger btn-sm"
                                                   onclick="return confirm('Yakin ingin menghapus buku ini?')">
                                                    🗑️ Hapus
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <div class="alert alert-info">Belum ada data buku.</div>
                <?php endif; ?>
            </div>

        <!-- ========== TAB 2: Kelola Penerbit ========== -->
        <?php elseif ($tab == 'penerbit'): ?>
            <div class="tab-pane active">
                <h2 class="mb-3">Daftar Penerbit</h2>

                <!-- Add Button -->
                <div class="add-button-section">
                    <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modalTambahPenerbit">
                        ➕ Tambah Penerbit
                    </button>
                </div>

                <!-- Table -->
                <?php
                $query_penerbit = "SELECT * FROM penerbit ORDER BY id_penerbit ASC";
                $result_penerbit = mysqli_query($koneksi, $query_penerbit);
                $total_penerbit = mysqli_num_rows($result_penerbit);
                ?>

                <?php if ($total_penerbit > 0): ?>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Nama</th>
                                    <th>Alamat</th>
                                    <th>Kota</th>
                                    <th>Telepon</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while ($row = mysqli_fetch_assoc($result_penerbit)): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($row['id_penerbit']); ?></td>
                                        <td><?php echo htmlspecialchars($row['nama']); ?></td>
                                        <td><?php echo htmlspecialchars($row['alamat']); ?></td>
                                        <td><?php echo htmlspecialchars($row['kota']); ?></td>
                                        <td><?php echo htmlspecialchars($row['telepon']); ?></td>
                                        <td>
                                            <div class="action-buttons">
                                                <button class="btn btn-warning btn-sm" 
                                                    data-bs-toggle="modal" 
                                                    data-bs-target="#modalEditPenerbit"
                                                    onclick="editPenerbit('<?php echo $row['id_penerbit']; ?>', '<?php echo addslashes($row['nama']); ?>', '<?php echo addslashes($row['alamat']); ?>', '<?php echo addslashes($row['kota']); ?>', '<?php echo addslashes($row['telepon']); ?>')">
                                                    ✏️ Edit
                                                </button>
                                                <a href="proses_penerbit.php?action=delete&id=<?php echo $row['id_penerbit']; ?>" 
                                                   class="btn btn-danger btn-sm"
                                                   onclick="return confirm('Yakin ingin menghapus penerbit ini?')">
                                                    🗑️ Hapus
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <div class="alert alert-info">Belum ada data penerbit.</div>
                <?php endif; ?>
            </div>
        <?php endif; ?>

    </div>

    <!-- ========== MODAL: Tambah Buku ========== -->
    <div class="modal fade" id="modalTambahBuku" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Buku Baru</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form method="POST" action="proses_buku.php?action=add">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">ID Buku</label>
                            <input type="text" class="form-control" name="id_buku" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Kategori</label>
                            <input type="text" class="form-control" name="kategori" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Nama Buku</label>
                            <input type="text" class="form-control" name="nama_buku" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Harga</label>
                            <input type="number" class="form-control" name="harga" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Stok</label>
                            <input type="number" class="form-control" name="stok" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Penerbit</label>
                            <select class="form-control" name="id_penerbit" required>
                                <option value="">-- Pilih Penerbit --</option>
                                <?php
                                $q = mysqli_query($koneksi, "SELECT * FROM penerbit");
                                while ($r = mysqli_fetch_assoc($q)) {
                                    echo "<option value='" . $r['id_penerbit'] . "'>" . htmlspecialchars($r['nama']) . "</option>";
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- ========== MODAL: Edit Buku ========== -->
    <div class="modal fade" id="modalEditBuku" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Buku</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form method="POST" action="proses_buku.php?action=edit">
                    <div class="modal-body">
                        <input type="hidden" name="id_buku" id="edit_id_buku">
                        <div class="mb-3">
                            <label class="form-label">Kategori</label>
                            <input type="text" class="form-control" name="kategori" id="edit_kategori" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Nama Buku</label>
                            <input type="text" class="form-control" name="nama_buku" id="edit_nama_buku" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Harga</label>
                            <input type="number" class="form-control" name="harga" id="edit_harga" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Stok</label>
                            <input type="number" class="form-control" name="stok" id="edit_stok" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Penerbit</label>
                            <select class="form-control" name="id_penerbit" id="edit_id_penerbit" required>
                                <?php
                                $q = mysqli_query($koneksi, "SELECT * FROM penerbit");
                                while ($r = mysqli_fetch_assoc($q)) {
                                    echo "<option value='" . $r['id_penerbit'] . "'>" . htmlspecialchars($r['nama']) . "</option>";
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- ========== MODAL: Tambah Penerbit ========== -->
    <div class="modal fade" id="modalTambahPenerbit" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Penerbit Baru</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form method="POST" action="proses_penerbit.php?action=add">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">ID Penerbit</label>
                            <input type="text" class="form-control" name="id_penerbit" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Nama Penerbit</label>
                            <input type="text" class="form-control" name="nama" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Alamat</label>
                            <textarea class="form-control" name="alamat" rows="3" required></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Kota</label>
                            <input type="text" class="form-control" name="kota" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Telepon</label>
                            <input type="text" class="form-control" name="telepon" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- ========== MODAL: Edit Penerbit ========== -->
    <div class="modal fade" id="modalEditPenerbit" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Penerbit</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form method="POST" action="proses_penerbit.php?action=edit">
                    <div class="modal-body">
                        <input type="hidden" name="id_penerbit" id="edit_id_penerbit_hidden">
                        <div class="mb-3">
                            <label class="form-label">Nama Penerbit</label>
                            <input type="text" class="form-control" name="nama" id="edit_nama_penerbit" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Alamat</label>
                            <textarea class="form-control" name="alamat" id="edit_alamat" rows="3" required></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Kota</label>
                            <input type="text" class="form-control" name="kota" id="edit_kota" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Telepon</label>
                            <input type="text" class="form-control" name="telepon" id="edit_telepon" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer>
        <p>&copy; 2024 UNIBOOKSTORE. Semua hak dilindungi.</p>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // Function to populate edit buku modal
        function editBuku(id, kategori, nama, harga, stok, id_penerbit) {
            document.getElementById('edit_id_buku').value = id;
            document.getElementById('edit_kategori').value = kategori;
            document.getElementById('edit_nama_buku').value = nama;
            document.getElementById('edit_harga').value = harga;
            document.getElementById('edit_stok').value = stok;
            document.getElementById('edit_id_penerbit').value = id_penerbit;
        }

        // Function to populate edit penerbit modal
        function editPenerbit(id, nama, alamat, kota, telepon) {
            document.getElementById('edit_id_penerbit_hidden').value = id;
            document.getElementById('edit_nama_penerbit').value = nama;
            document.getElementById('edit_alamat').value = alamat;
            document.getElementById('edit_kota').value = kota;
            document.getElementById('edit_telepon').value = telepon;
        }
    </script>
</body>
</html>

<?php
mysqli_close($koneksi);
?>
