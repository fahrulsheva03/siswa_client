<!DOCTYPE html>
<html lang="id">

<?php
require 'koneksi.php';
require 'layouts/header.php';

$selectedMapel = isset($_GET['mapel']) ? $_GET['mapel'] : '';
?>

<body>

    <!-- Sidebar -->
    <?php require 'layouts/sidebar.php'; ?>

    <!-- Main Content -->
    <div class="main-content">
        <div class="container-fluid">

            <!-- Title -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h3 class="fw-bold">
                    <i class="bi bi-clipboard-check me-2 text-primary"></i>Data Absensi Siswa
                </h3>
                <button class="btn btn-outline-primary">
                    <i class="bi bi-person-circle me-2"></i>Admin
                </button>
            </div>

            <!-- Card Section -->
            <div class="card p-4">
                <h5 class="fw-bold mb-3">
                    <i class="bi bi-table me-2 text-primary"></i>Daftar Absensi
                </h5>

                <!-- FILTER -->
                <div class="row mb-3">
                    <div class="col-md-3">
                        <select id="filterType" class="form-select">
                            <option value="hari">Per Hari</option>
                            <option value="minggu">Per Minggu</option>
                            <option value="bulan">Per Bulan</option>
                        </select>
                    </div>

                    <div class="col-md-3">
                        <input type="date" id="filterDate" class="form-control">
                    </div>

                    <div class="col-md-3">
                        <select id="filterKelas" class="form-select">
                            <option value="">Semua Kelas</option>
                            <?php
                            $kelas = mysqli_query($koneksi, "SELECT id_kelas_232410, nama_kelas_232410 FROM kelas_232410 ORDER BY nama_kelas_232410 ASC");
                            while ($k = mysqli_fetch_assoc($kelas)) {
                                echo "<option value='{$k['id_kelas_232410']}'>" . htmlspecialchars($k['nama_kelas_232410']) . "</option>";
                            }
                            ?>
                        </select>
                    </div>

                    <div class="col-md-3">
                        <select id="filterMapel" class="form-select">
                            <option value="">Semua Mata Pelajaran</option>
                            <?php
                            $mapelResult = mysqli_query($koneksi, "SELECT DISTINCT mata_pelajaran_232410 FROM jadwal_232410 ORDER BY mata_pelajaran_232410 ASC");
                            while ($m = mysqli_fetch_assoc($mapelResult)) {
                                if ($m['mata_pelajaran_232410'] === null || $m['mata_pelajaran_232410'] === '') {
                                    continue;
                                }
                                $mapelVal = $m['mata_pelajaran_232410'];
                                $selected = ($selectedMapel === $mapelVal) ? 'selected' : '';
                                echo "<option value=\"" . htmlspecialchars($mapelVal) . "\" {$selected}>" . htmlspecialchars($mapelVal) . "</option>";
                            }
                            ?>
                        </select>
                    </div>

                    <div class="col-md-3 d-flex gap-2 mt-2 ">
                        <button type="button" class="btn btn-primary flex-grow-1" onclick="loadData()">
                            Terapkan Filter
                        </button>
                        <button type="button" class="btn btn-danger" onclick="exportPDF()">
                            Export PDF
                        </button>
                    </div>
                </div>

                <p id="filterInfo" class="mb-2 text-muted"></p>

                <div class="table-responsive">
                    <table class="table table-bordered table-hover align-middle">
                        <thead class="table-primary text-center">
                            <tr>
                                <th>No</th>
                                <th>Nama Siswa</th>
                                <th>NISN</th>
                                <th>Kelas</th>
                                <th>Tanggal</th>
                                <th>Waktu Scan</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>

                        <tbody id="dataAbsensi">
                            <!-- Data AJAX akan muncul di sini -->
                        </tbody>

                    </table>
                </div>
            </div>

        </div>
    </div>

</body>
<script>
// Fungsi: Mengambil data absensi dari server sesuai filter yang dipilih dan menampilkannya di tabel.
// Parameter input:
// - Mengambil langsung nilai dari elemen DOM: #filterType, #filterDate, #filterKelas, dan #filterMapel.
// Return value:
// - Tidak mengembalikan nilai; mengubah isi elemen tbody#dataAbsensi dan teks informasi filter di halaman.
// Contoh penggunaan:
// - Dipanggil saat tombol "Terapkan Filter" diklik atau saat halaman pertama kali dimuat.
// Catatan penting:
// - Mengirim request AJAX ke filter_absensi.php dengan metode POST dan memanfaatkan innerHTML untuk merender baris tabel.
function loadData() {
    let tipe = document.getElementById('filterType').value;
    let tanggal = document.getElementById('filterDate').value;
    let idKelas = document.getElementById('filterKelas').value;
    let mapel = document.getElementById('filterMapel').value;

    let xhr = new XMLHttpRequest();
    xhr.open("POST", "filter_absensi.php", true);
    xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");

    xhr.onload = function () {
        if (this.status == 200) {
            document.getElementById("dataAbsensi").innerHTML = this.responseText;
        }
    };

    let params = "tipe=" + encodeURIComponent(tipe) +
                 "&tanggal=" + encodeURIComponent(tanggal) +
                 "&id_kelas=" + encodeURIComponent(idKelas) +
                 "&mapel=" + encodeURIComponent(mapel);

    xhr.send(params);

    updateFilterInfo();
}

// Fungsi: Membuka jendela baru yang menampilkan laporan PDF absensi berdasarkan filter yang dipilih.
// Parameter input:
// - Mengambil nilai dari elemen DOM: #filterType, #filterDate, #filterKelas, dan #filterMapel.
// Return value:
// - Tidak mengembalikan nilai; memanggil window.open() ke URL export_pdf.php dengan query string yang dibutuhkan.
// Contoh penggunaan:
// - Dipicu ketika pengguna menekan tombol "Export PDF" pada halaman absensi admin.
// Catatan penting:
// - PDF akan terbuka di tab baru sehingga pengguna dapat menyimpannya atau mencetak langsung dari browser.
function exportPDF() {
    let tipe = document.getElementById('filterType').value;
    let tanggal = document.getElementById('filterDate').value;
    let idKelas = document.getElementById('filterKelas').value;
    let mapel = document.getElementById('filterMapel').value;

    let url = "export_pdf.php?tipe=" + encodeURIComponent(tipe) +
              "&tanggal=" + encodeURIComponent(tanggal) +
              "&id_kelas=" + encodeURIComponent(idKelas) +
              "&mapel=" + encodeURIComponent(mapel);

    window.open(url, "_blank");
}

// Fungsi: Menampilkan teks ringkasan filter mata pelajaran yang sedang aktif di bawah area filter.
// Parameter input:
// - Membaca nilai terpilih dari elemen select#filterMapel dan memanipulasi elemen #filterInfo.
// Return value:
// - Tidak mengembalikan nilai; hanya memperbarui innerHTML elemen #filterInfo di DOM.
// Contoh penggunaan:
// - Dipanggil setelah loadData() dan ketika nilai filterMapel berubah.
// Catatan penting:
// - Menampilkan badge "Semua" jika tidak ada mata pelajaran spesifik yang dipilih.
function updateFilterInfo() {
    let mapelSelect = document.getElementById('filterMapel');
    let info = document.getElementById('filterInfo');
    if (!mapelSelect || !info) return;

    let text = mapelSelect.options[mapelSelect.selectedIndex].text;
    if (mapelSelect.value) {
        info.innerHTML = "Filter mata pelajaran: <span class='badge bg-primary'>" + text + "</span>";
    } else {
        info.innerHTML = "Filter mata pelajaran: <span class='badge bg-secondary'>Semua</span>";
    }
}

document.getElementById("filterType").addEventListener("change", function () {
    let type = this.value;
    let input = document.getElementById("filterDate");

    if (type === "bulan") {
        input.type = "month";
    } else {
        input.type = "date";
    }
});

let mapelSelect = document.getElementById('filterMapel');
if (mapelSelect) {
    mapelSelect.addEventListener('change', function () {
        let params = new URLSearchParams(window.location.search);
        params.set('mapel', this.value);
        let newUrl = window.location.pathname + '?' + params.toString();
        window.history.replaceState({}, '', newUrl);
        updateFilterInfo();
    });
}

loadData();
</script>

</html>
