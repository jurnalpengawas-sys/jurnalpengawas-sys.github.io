<?php
$conn = new mysqli("localhost", "root", "", "jurnal_pengawasan");
if ($conn->connect_errno) die("DB Error");

header("Content-Type: application/vnd.ms-excel; charset=utf-8");
header("Content-Disposition: attachment; filename=jurnal_pengawasan.xls");

echo "<table border='1'>";
echo "<tr>
<th>No</th><th>Nama</th><th>NIP</th><th>Daerah</th>
<th>Jumlah Sekolah</th><th>Tanggal Input</th>
</tr>";

$result = $conn->query("SELECT * FROM identitas ORDER BY id DESC");
$no=1;
while($r = $result->fetch_assoc()){
    echo "<tr>";
    echo "<td>".$no++."</td>";
    echo "<td>".htmlspecialchars($r['nama'])."</td>";
    echo "<td>".htmlspecialchars($r['nip'])."</td>";
    echo "<td>".htmlspecialchars($r['daerah'])."</td>";
    echo "<td>".(int)$r['jumlah_sekolah']."</td>";
    echo "<td>".htmlspecialchars($r['created_at'])."</td>";
    echo "</tr>";
}
echo "</table>";
