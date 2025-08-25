<?php
$conn = new mysqli("localhost", "root", "", "jurnal_pengawasan");
if ($conn->connect_errno) die("DB Error");

header("Content-Type: application/vnd.ms-word; charset=utf-8");
header("Content-Disposition: attachment; filename=jurnal_pengawasan.doc");

echo "<html><head><meta charset='utf-8'><style>
table{border-collapse:collapse;width:100%} th,td{border:1px solid #000;padding:6px}
h2{text-align:center}
</style></head><body>";
echo "<h2>Jurnal Kepengawasan</h2>";
echo "<table>";
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
echo "</table></body></html>";
