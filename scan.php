<?php
require 'koneksi.php';

// Fungsi: Menyediakan antarmuka scan QR untuk siswa dan menghubungkannya dengan proses pencatatan absensi.
// Parameter input:
// - $_SESSION['id_siswa_232410']: identitas siswa yang sudah login dan berhak mengakses halaman scan.
// - Input QR dari kamera perangkat atau dari URL gambar yang ditempelkan pengguna.
// Return value:
// - Tidak mengembalikan nilai; menampilkan status absensi di halaman berdasarkan respon dari proses_scan.php.
// Contoh penggunaan:
// - Diakses setelah siswa login dan menekan tombol "Scan QR untuk Absen" dari dashboard.
// Catatan penting:
// - Menggunakan library html5-qrcode untuk membaca QR dari kamera serta memanfaatkan fetch API untuk mengirim data ke server.
if (!isset($_SESSION['id_siswa_232410'])) {
  echo "<script>
            alert('Anda belum login!');
            window.location.href='login_siswa.php';
          </script>";
}

include 'includes/header.php';
include 'includes/navbar.php';
?>

<div class="container mt-5 text-center">
  <h3 class="fw-bold mb-4">Scan QR Code untuk Absensi</h3>

  <div class="card mx-auto p-4 shadow" style="max-width:450px;">

    <!-- Scanner Kamera -->
    <div id="reader" style="width:100%;"></div>
    <div id="status" class="mt-3 fw-bold"></div>

    <hr>

    <!-- Input URL QR -->
    <h5 class="fw-bold mb-3">Atau Tempel URL Gambar QR</h5>

    <input type="text" id="qrUrlInput" class="form-control mb-2" placeholder="Tempel URL gambar QR di sini">

    <button class="btn btn-primary w-100" onclick="submitQrUrl()">
      Gunakan QR dari URL
    </button>

    <div id="urlStatus" class="mt-3 fw-bold"></div>

  </div>
</div>

<!-- Library QR scanner -->
<script src="https://unpkg.com/html5-qrcode"></script>

<script>
  let isProcessing = false;

  // Fungsi: Callback yang dijalankan ketika QR berhasil terbaca dari kamera, lalu mengirimnya ke server.
  // Parameter input:
  // - decodedText: teks hasil decode QR yang dibaca oleh html5-qrcode.
  // Return value:
  // - Tidak mengembalikan nilai; hanya memanggil processQR jika belum ada proses yang berjalan.
  // Contoh penggunaan:
  // - Didaftarkan sebagai handler sukses pada Html5QrcodeScanner.render(onScanSuccess, onScanError).
  // Catatan penting:
  // - Menggunakan flag isProcessing untuk mencegah pengiriman berulang saat kamera terus membaca QR.
  function onScanSuccess(decodedText) {
    if (isProcessing) return;
    processQR(decodedText);
  }

  // Fungsi: Callback ketika terjadi error pembacaan QR dari kamera.
  // Parameter input:
  // - errorMessage: pesan kesalahan dari library html5-qrcode.
  // Return value:
  // - Tidak mengembalikan nilai; error diabaikan agar scanner tetap berjalan.
  // Contoh penggunaan:
  // - Didaftarkan sebagai handler error pada Html5QrcodeScanner.render.
  // Catatan penting:
  // - Kesalahan pembacaan sporadis dianggap normal sehingga tidak ditampilkan ke pengguna.
  function onScanError(errorMessage) {
    // abaikan error
  }

  let html5QrcodeScanner = new Html5QrcodeScanner(
    "reader", {
      fps: 10,
      qrbox: {
        width: 250,
        height: 250
      }
    },
    false
  );
  html5QrcodeScanner.render(onScanSuccess, onScanError);

  navigator.mediaDevices.getUserMedia({
    video: {
      facingMode: "environment"
    }
  });

  // =====================================
  //  KIRIM QR KE SERVER DAN TAMPILKAN STATUS
  // =====================================
  // Fungsi: Mengonversi response dari server menjadi pesan status yang mudah dipahami pengguna.
  // Parameter input:
  // - response: string status yang dikirim proses_scan.php (misalnya Hadir, Terlambat, ALREADY, dsb.).
  // - target: ID elemen HTML yang akan diisi pesan status (misalnya "status" atau "urlStatus").
  // Return value:
  // - Tidak mengembalikan nilai; hanya memodifikasi innerHTML elemen target dengan komponen alert Bootstrap.
  // Contoh penggunaan:
  // - Dipanggil dari processQR dan submitQrUrl setelah menerima respon teks dari server.
  // Catatan penting:
  // - Penambahan case baru di proses_scan.php perlu diselaraskan dengan percabangan di fungsi ini.
  function handleResponse(response, target) {

    let box = document.getElementById(target);

    if (response === "Hadir") {
      box.innerHTML = `<div class='alert alert-success'>Hadir — Absensi berhasil!</div>`;
    } else if (response === "Terlambat") {
      box.innerHTML = `<div class='alert alert-warning'>Terlambat — Tapi absensi tetap direkam.</div>`;
    } else if (response === "Alfa") {
      box.innerHTML = `<div class='alert alert-danger'>Alfa — Anda absen di luar waktu pelajaran.</div>`;
    } else if (response === "ALREADY") {
      box.innerHTML = `<div class='alert alert-warning'>Anda sudah absen hari ini!</div>`;
    } else if (response === "QR_INVALID") {
      box.innerHTML = `<div class='alert alert-danger'>QR tidak cocok dengan akun Anda!</div>`;
    } else if (response.startsWith("NOT_STARTED")) {
      let parts = response.split("|");
      let mapel = parts[1];
      let jam = parts[2];
      box.innerHTML = `<div class='alert alert-info'>Jadwal ${mapel} belum dimulai! Mulai jam ${jam}.</div>`;
    } else if (response === "NO_CLASS") {
      box.innerHTML = `<div class='alert alert-danger'>Tidak ada mata pelajaran untuk jam ini.</div>`;
    } else if (response === "NOT_LOGIN") {
      box.innerHTML = `<div class='alert alert-danger'>Sesi login Anda berakhir. Silakan login kembali.</div>`;
    } else {
      box.innerHTML = `<div class='alert alert-danger'>Terjadi kesalahan: ${response}</div>`;
    }
  }

  // =====================================
  //  PROSES QR DARI KAMERA
  // =====================================
  // Fungsi: Mengirim nilai QR hasil scan kamera ke proses_scan.php dan menampilkan status kehadiran.
  // Parameter input:
  // - qrValue: string QR yang diterima dari onScanSuccess.
  // Return value:
  // - Tidak mengembalikan nilai; menampilkan pesan loading dan hasil absensi pada elemen #status.
  // Contoh penggunaan:
  // - Dipanggil secara internal oleh onScanSuccess ketika pengguna berhasil menscan QR.
  // Catatan penting:
  // - Setelah respon diterima, flag isProcessing akan dikembalikan ke false agar scan berikutnya bisa diproses.
  function processQR(qrValue) {
    isProcessing = true;

    const statusBox = document.getElementById("status");
    statusBox.innerHTML = "<div class='alert alert-primary'>Memproses absensi...</div>";

    fetch("proses_scan.php", {
        method: "POST",
        headers: {
          "Content-Type": "application/x-www-form-urlencoded"
        },
        body: "qr=" + encodeURIComponent(qrValue)
      })
      .then(res => res.text())
      .then(response => {

        handleResponse(response, "status");

        setTimeout(() => {
          isProcessing = false;
        }, 2500);
      });
  }

  // =====================================
  //  PROSES QR DARI URL YANG DIPASTE
  // =====================================
  // Fungsi: Mengambil nama file QR dari URL gambar yang dipaste, lalu mengirimkannya ke server untuk diproses sebagai absensi.
  // Parameter input:
  // - Tidak menerima parameter langsung; membaca nilai dari input text#qrUrlInput.
  // Return value:
  // - Tidak mengembalikan nilai; menampilkan pesan status pada elemen #urlStatus dan mengosongkannya setelah beberapa detik.
  // Contoh penggunaan:
  // - Dipicu ketika tombol "Gunakan QR dari URL" diklik oleh pengguna.
  // Catatan penting:
  // - Hanya menerima URL yang berakhiran .png, .jpg, atau .jpeg untuk meminimalkan input yang tidak relevan.
  function submitQrUrl() {
    let url = document.getElementById("qrUrlInput").value.trim();
    let box = document.getElementById("urlStatus");

    box.innerHTML = "";

    // VALIDASI KOSONG
    if (url === "") {
      box.innerHTML = "<div class='alert alert-danger'>URL tidak boleh kosong!</div>";
      return;
    }

    // VALIDASI FORMAT HARUS GAMBAR
    if (!url.match(/\.(png|jpg|jpeg)$/i)) {
      box.innerHTML = "<div class='alert alert-danger'>URL harus berupa gambar (.png/.jpg/.jpeg)</div>";
      return;
    }

    let qrValue = url.split('/').pop();

    box.innerHTML = "<div class='alert alert-primary'>Memproses QR dari URL...</div>";

    fetch("proses_scan.php", {
        method: "POST",
        headers: {
          "Content-Type": "application/x-www-form-urlencoded"
        },
        body: "qr=" + encodeURIComponent(qrValue)
      })
      .then(res => res.text())
      .then(response => {

        handleResponse(response, "urlStatus");

        setTimeout(() => {
          box.innerHTML = "";
        }, 3500);
      });
  }
</script>

<?php include 'includes/footer.php'; ?>
