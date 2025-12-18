<!DOCTYPE html>
<html lang="id">

<?php
require 'koneksi.php';
require 'layouts/header.php';
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

                    <div class="col-md-3 d-flex gap-2">
                        <button type="button" class="btn btn-primary flex-grow-1" onclick="loadData()">
                            Terapkan Filter
                        </button>
                        <button type="button" class="btn btn-danger" onclick="exportPDF()">
                            Export PDF
                        </button>
                    </div>
                </div>

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
function loadData() {
    let tipe = document.getElementById('filterType').value;
    let tanggal = document.getElementById('filterDate').value;
    let idKelas = document.getElementById('filterKelas').value;

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
                 "&id_kelas=" + encodeURIComponent(idKelas);

    xhr.send(params);
}

function exportPDF() {
    let tipe = document.getElementById('filterType').value;
    let tanggal = document.getElementById('filterDate').value;
    let idKelas = document.getElementById('filterKelas').value;

    let url = "export_pdf.php?tipe=" + encodeURIComponent(tipe) +
              "&tanggal=" + encodeURIComponent(tanggal) +
              "&id_kelas=" + encodeURIComponent(idKelas);

    window.open(url, "_blank");
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

loadData();
</script>

</html>
