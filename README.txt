JURNAL KEPENGAWASAN (XAMPP READY, NO-DEPENDENCY EXPORTS)

ISI FOLDER
- index.php                  : Aplikasi utama (form + rekap)
- jurnal_pengawasan.sql      : File SQL untuk import database
- export_excel.php           : Export Excel (tanpa library, format .xls kompatibel)
- export_word.php            : Export Word (tanpa library, format .doc kompatibel)
- export_pdf.php             : Halaman siap cetak -> gunakan CTRL+P, pilih 'Save as PDF'
- uploads/                   : Folder upload foto (otomatis terisi)

LANGKAH INSTALASI
1) Jalankan XAMPP (Apache & MySQL).
2) Buka phpMyAdmin → Import → pilih jurnal_pengawasan.sql (database & tabel akan dibuat).
3) Copy folder 'jurnal' ini ke: C:\xampp\htdocs\
4) Akses di browser: http://localhost/jurnal/

CATATAN EXPORT
- Excel: hasil .xls kompatibel dibuka di Microsoft Excel / WPS / LibreOffice.
- Word : hasil .doc kompatibel dibuka di Microsoft Word.
- PDF  : buka export_pdf.php → tekan tombol 'Cetak' → pilih 'Save as PDF' (semua browser modern mendukung).
