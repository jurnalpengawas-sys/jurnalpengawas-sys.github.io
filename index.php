<?php
// ---------- KONFIGURASI ----------
$DB_HOST = "localhost";
$DB_USER = "root";
$DB_PASS = "";
$DB_NAME = "jurnal_pengawasan";

// ---------- KONEKSI DB ----------
$conn = new mysqli($DB_HOST, $DB_USER, $DB_PASS, $DB_NAME);
if ($conn->connect_errno) {
    die("Koneksi DB gagal: " . $conn->connect_error);
}
$conn->set_charset("utf8mb4");

// ---------- PASTIKAN TABEL ADA ----------
$createTableSQL = "
CREATE TABLE IF NOT EXISTS identitas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama VARCHAR(100) NOT NULL,
    nip VARCHAR(50) NOT NULL,
    daerah VARCHAR(100) NOT NULL,
    jumlah_sekolah INT NOT NULL,
    foto VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
";
$conn->query($createTableSQL);

// ---------- PROSES SIMPAN ----------
$alert = "";
if (isset($_POST['simpan'])) {
    $nama = trim($_POST['nama'] ?? "");
    $nip = trim($_POST['nip'] ?? "");
    $daerah = trim($_POST['daerah'] ?? "");
    $jumlah_sekolah = (int)($_POST['jumlah_sekolah'] ?? 0);

    if ($nama && $nip && $daerah && $jumlah_sekolah > 0) {
        $fotoPath = "";
        if (!empty($_FILES['foto']['name'])) {
            $allowed = ['jpg','jpeg','png','webp','gif'];
            $ext = strtolower(pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION));
            if (!in_array($ext, $allowed)) {
                $alert = '<div class="alert alert-warning">Format foto harus jpg/jpeg/png/webp/gif.</div>';
            } else {
                $dir = __DIR__ . "/uploads/";
                if (!is_dir($dir)) mkdir($dir, 0775, true);
                $newName = time() . "_" . bin2hex(random_bytes(4)) . "." . $ext;
                $serverPath = $dir . $newName;
                if (move_uploaded_file($_FILES['foto']['tmp_name'], $serverPath)) {
                    $fotoPath = "uploads/" . $newName;
                } else {
                    $alert = '<div class="alert alert-warning">Gagal mengunggah foto.</div>';
                }
            }
        }
        if ($alert === "") {
            $stmt = $conn->prepare("INSERT INTO identitas (nama, nip, daerah, jumlah_sekolah, foto) VALUES (?,?,?,?,?)");
            $stmt->bind_param("sssis", $nama, $nip, $daerah, $jumlah_sekolah, $fotoPath);
            if ($stmt->execute()) {
                $alert = '<div class="alert alert-success">✅ Data berhasil disimpan.</div>';
            } else {
                $alert = '<div class="alert alert-danger">❌ Gagal: '.$conn->error.'</div>';
            }
            $stmt->close();
        }
    } else {
        $alert = '<div class="alert alert-warning">⚠ Lengkapi semua field.</div>';
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Jurnal Kepengawasan</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body{ background:#f7f7fb; }
    .card{ border:0; border-radius:18px; box-shadow:0 8px 24px rgba(0,0,0,.08); }
    .table img{ border-radius:10px; object-fit:cover; }
    .btn-export { font-weight:600 }
  </style>
</head>
<body class="bg-light">

<div class="container py-4">
  <h2 class="text-center mb-4">📘 Jurnal Kepengawasan</h2>
  <?php if ($alert) echo $alert; ?>

  <!-- Form Input -->
  <div class="card p-4 mb-4">
    <h4 class="mb-3">Form Identitas Pengawas</h4>
    <form method="POST" enctype="multipart/form-data">
      <div class="row g-3">
        <div class="col-md-6">
          <label class="form-label">Nama Pengawas</label>
          <input type="text" name="nama" class="form-control" required>
        </div>
        <div class="col-md-6">
          <label class="form-label">NIP</label>
          <input type="text" name="nip" class="form-control" required>
        </div>
        <div class="col-md-6">
          <label class="form-label">Daerah Kepengawasan</label>
          <input type="text" name="daerah" class="form-control" required>
        </div>
        <div class="col-md-3">
          <label class="form-label">Jumlah Sekolah Binaan</label>
          <input type="number" name="jumlah_sekolah" class="form-control" min="1" required>
        </div>
        <div class="col-md-3">
          <label class="form-label">Foto Dokumentasi (opsional)</label>
          <input type="file" name="foto" class="form-control" accept="image/*">
        </div>
      </div>
      <div class="mt-3">
        <button type="submit" name="simpan" class="btn btn-primary">Simpan</button>
      </div>
    </form>
  </div>

  <!-- Rekap Data -->
  <div class="card p-4">
    <div class="d-flex flex-wrap gap-2 mb-3">
      <a href="export_excel.php" class="btn btn-success btn-sm btn-export">📊 Export Excel</a>
      <a href="export_word.php" class="btn btn-primary btn-sm btn-export">📄 Export Word</a>
      <a href="export_pdf.php" class="btn btn-danger btn-sm btn-export" target="_blank">📕 Export / Cetak PDF</a>
    </div>
    <h4 class="mb-3">📑 Rekap Data Jurnal</h4>
    <div class="table-responsive">
      <table class="table table-bordered align-middle">
        <thead class="table-dark">
          <tr>
            <th>No</th>
            <th>Nama</th>
            <th>NIP</th>
            <th>Daerah</th>
            <th>Jumlah Sekolah</th>
            <th>Foto</th>
            <th>Tanggal Input</th>
          </tr>
        </thead>
        <tbody>
          <?php
          $result = $conn->query("SELECT * FROM identitas ORDER BY id DESC");
          if ($result && $result->num_rows > 0) {
              $no = 1;
              while ($row = $result->fetch_assoc()) {
                  echo "<tr>
                    <td>".$no++."</td>
                    <td>".htmlspecialchars($row['nama'])."</td>
                    <td>".htmlspecialchars($row['nip'])."</td>
                    <td>".htmlspecialchars($row['daerah'])."</td>
                    <td>".(int)$row['jumlah_sekolah']."</td>
                    <td>".($row['foto'] ? "<img src='".htmlspecialchars($row['foto'])."' width='100' height='75'>" : "-")."</td>
                    <td>".htmlspecialchars($row['created_at'])."</td>
                  </tr>";
              }
          } else {
              echo "<tr><td colspan='7' class='text-center'>Belum ada data</td></tr>";
          }
          ?>
        </tbody>
      </table>
    </div>
  </div>
</div>
</body>
</html>
