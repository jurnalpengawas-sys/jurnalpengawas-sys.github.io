<?php
$conn = new mysqli("localhost", "root", "", "jurnal_pengawasan");
if ($conn->connect_errno) die("DB Error");
$result = $conn->query("SELECT * FROM identitas ORDER BY id DESC");
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="utf-8">
<title>Cetak PDF - Jurnal Kepengawasan</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<style>
  body{ font-family: Arial, Helvetica, sans-serif; margin:24px; }
  h2{ text-align:center; margin:0 0 16px 0; }
  table{ border-collapse:collapse; width:100%; }
  th, td{ border:1px solid #333; padding:6px; font-size:12px; }
  th{ background:#f0f0f0; }
  @media print {
    .noprint{ display:none; }
  }
</style>
</head>
<body>
<div class="noprint" style="margin-bottom:12px; text-align:right;">
  <button onclick="window.print()">🖨 Cetak / Simpan sebagai PDF</button>
</div>
<h2>Jurnal Kepengawasan</h2>
<table>
  <thead>
    <tr>
      <th>No</th>
      <th>Nama</th>
      <th>NIP</th>
      <th>Daerah</th>
      <th>Jumlah Sekolah</th>
      <th>Tanggal Input</th>
    </tr>
  </thead>
  <tbody>
    <?php
    $no=1;
    if ($result && $result->num_rows){
        while($r=$result->fetch_assoc()){
            echo "<tr>";
            echo "<td>".$no++."</td>";
            echo "<td>".htmlspecialchars($r['nama'])."</td>";
            echo "<td>".htmlspecialchars($r['nip'])."</td>";
            echo "<td>".htmlspecialchars($r['daerah'])."</td>";
            echo "<td>".(int)$r['jumlah_sekolah']."</td>";
            echo "<td>".htmlspecialchars($r['created_at'])."</td>";
            echo "</tr>";
        }
    } else {
        echo "<tr><td colspan='6' style='text-align:center'>Belum ada data</td></tr>";
    }
    ?>
  </tbody>
</table>
</body>
</html>
