<?php
require 'koneksi.php';

// Fungsi: Menyediakan antarmuka scan QR untuk siswa dan menghubungkannya dengan proses pencatatan absensi.
// Parameter input:
// - $_SESSION['id_siswa']: identitas siswa yang sudah login dan berhak mengakses halaman scan.
// - Input QR dari kamera perangkat atau dari URL gambar yang ditempelkan pengguna.
// Return value:
// - Tidak mengembalikan nilai; menampilkan status absensi di halaman berdasarkan respon dari proses_scan.php.
// Contoh penggunaan:
// - Diakses setelah siswa login dan menekan tombol "Scan QR untuk Absen" dari dashboard.
// Catatan penting:
// - Menggunakan library html5-qrcode untuk membaca QR dari kamera serta memanfaatkan fetch API untuk mengirim data ke server.
if (!isset($_SESSION['id_siswa'])) {
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
    <div id="scanInfo" class="mt-2 text-muted small">Mempersiapkan kamera...</div>
    <div id="status" class="mt-3 fw-bold"></div>
    <div class="alert alert-light text-start small mt-3 mb-0">
      <b>Keterangan realtime scan:</b><br>
      1. Arahkan kamera ke QR dari layar HP sampai fokus.<br>
      2. Tampilan kamera dibuat normal (tidak mirror/terbalik).<br>
      3. Area scan otomatis difokuskan ke tengah untuk bantu kondisi redup/silau.<br>
      4. QR yang terlalu kecil akan difilter agar tidak salah baca.<br>
      5. Sistem otomatis membaca QR tanpa klik tombol.<br>
      6. Jika QR sama terbaca berulang, sistem memberi jeda singkat agar data tidak dobel.<br>
      7. Hasil absensi tampil langsung di bawah scanner.
    </div>

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

<style>
  /* Paksa preview kamera tidak mirror/terbalik */
  #reader video,
  #reader canvas,
  #reader__scan_region video,
  #reader__scan_region canvas {
    transform: scaleX(1) !important;
    -webkit-transform: scaleX(1) !important;
    object-fit: cover;
    /* Sedikit tingkatkan kontras preview untuk bantu mata saat layar redup/silau */
    filter: contrast(1.08) saturate(1.05);
  }
</style>

<!-- Library QR scanner -->
<script src="https://unpkg.com/html5-qrcode"></script>

<script>
  let isProcessing = false;
  let lastQrValue = "";
  let lastScanAt = 0;
  let scanErrorCount = 0;
  let strictSizeFilter = true;
  const SCAN_COOLDOWN_MS = 2000;
  const MIN_QR_EDGE_PX = 38;
  const MIN_QR_AREA_PX = 1300;

  // Menampilkan info realtime scanner agar pengguna tahu status kamera/proses.
  function setScanInfo(message, colorClass = "text-muted") {
    const info = document.getElementById("scanInfo");
    info.className = "mt-2 small " + colorClass;
    info.textContent = message;
  }

  // Beberapa browser/library menyuntik style mirror secara dinamis, paksa normal berulang.
  function forceNoMirrorPreview() {
    const reader = document.getElementById("reader");
    if (!reader) return;

    const targets = reader.querySelectorAll("video, canvas, #reader__scan_region, #reader__scan_region *");
    targets.forEach((el) => {
      el.style.setProperty("transform", "scaleX(1)", "important");
      el.style.setProperty("-webkit-transform", "scaleX(1)", "important");
    });
  }

  // Area scan adaptif (auto-zoom ke tengah) agar deteksi lebih stabil di layar HP.
  function getAdaptiveQrBox() {
    const viewportMin = Math.min(window.innerWidth || 360, window.innerHeight || 640);
    const size = Math.max(170, Math.min(360, Math.floor(viewportMin * 0.78)));
    return {
      width: size,
      height: size
    };
  }

  // Ambil titik sudut hasil deteksi QR dari berbagai bentuk object yang mungkin dikembalikan library.
  function extractResultPoints(decodedResult) {
    if (!decodedResult) return [];
    if (Array.isArray(decodedResult.resultPoints)) return decodedResult.resultPoints;
    if (decodedResult.result && Array.isArray(decodedResult.result.resultPoints)) {
      return decodedResult.result.resultPoints;
    }
    if (Array.isArray(decodedResult.cornerPoints)) return decodedResult.cornerPoints;
    return [];
  }

  // Filter QR minimum size supaya QR yang terlalu kecil/blur tidak diproses ke server.
  function isQrSizeValid(decodedResult) {
    const points = extractResultPoints(decodedResult);
    if (points.length < 3) return true;

    const xs = points.map(p => Number(p.x)).filter(Number.isFinite);
    const ys = points.map(p => Number(p.y)).filter(Number.isFinite);
    if (xs.length === 0 || ys.length === 0) return true;

    const width = Math.max(...xs) - Math.min(...xs);
    const height = Math.max(...ys) - Math.min(...ys);
    const minEdge = Math.min(width, height);
    const area = width * height;

    return minEdge >= MIN_QR_EDGE_PX && area >= MIN_QR_AREA_PX;
  }

  // Fungsi: Callback yang dijalankan ketika QR berhasil terbaca dari kamera, lalu mengirimnya ke server.
  // Parameter input:
  // - decodedText: teks hasil decode QR yang dibaca oleh html5-qrcode.
  // Return value:
  // - Tidak mengembalikan nilai; hanya memanggil processQR jika belum ada proses yang berjalan.
  // Contoh penggunaan:
  // - Didaftarkan sebagai handler sukses pada Html5QrcodeScanner.render(onScanSuccess, onScanError).
  // Catatan penting:
  // - Menggunakan flag isProcessing untuk mencegah pengiriman berulang saat kamera terus membaca QR.
  function onScanSuccess(decodedText, decodedResult) {
    const qrText = (decodedText || "").trim();
    const now = Date.now();

    if (!qrText) return;
    if (isProcessing) return;
    if (strictSizeFilter && !isQrSizeValid(decodedResult)) {
      setScanInfo("QR terdeteksi tapi kecil. Dekatkan HP sedikit atau naikkan brightness layar.", "text-warning");
      return;
    }

    // Hindari kirim berulang saat QR yang sama terbaca sangat cepat.
    if (qrText === lastQrValue && now - lastScanAt < SCAN_COOLDOWN_MS) return;

    lastQrValue = qrText;
    lastScanAt = now;
    processQR(qrText);
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
    // Error scan sesekali normal; tampilkan hint berkala agar pengguna terbantu.
    scanErrorCount++;
    if (scanErrorCount >= 80 && strictSizeFilter) {
      strictSizeFilter = false;
      setScanInfo("Mode sensitif diaktifkan: filter ukuran QR dilonggarkan agar lebih mudah terbaca.", "text-info");
    }
    if (scanErrorCount % 25 === 0) {
      setScanInfo("Sulit terbaca? Naikkan brightness HP, kurangi pantulan cahaya, lalu dekatkan QR.", "text-warning");
    }
  }

  let html5QrcodeScanner = new Html5QrcodeScanner(
    "reader", {
      fps: 20,
      qrbox: getAdaptiveQrBox(),
      videoConstraints: {
        facingMode: {
          ideal: "environment"
        }
      },
      disableFlip: true,
      rememberLastUsedCamera: false
    },
    false
  );
  html5QrcodeScanner.render(onScanSuccess, onScanError);
  forceNoMirrorPreview();

  // Re-apply beberapa kali di awal karena elemen scanner dibuat bertahap.
  let mirrorFixCount = 0;
  const mirrorFixTimer = setInterval(() => {
    forceNoMirrorPreview();
    mirrorFixCount++;
    if (mirrorFixCount >= 20) clearInterval(mirrorFixTimer);
  }, 250);

  // Jika DOM scanner berubah, paksa ulang non-mirror.
  const readerNode = document.getElementById("reader");
  if (readerNode && window.MutationObserver) {
    const mirrorObserver = new MutationObserver(() => forceNoMirrorPreview());
    mirrorObserver.observe(readerNode, {
      childList: true,
      subtree: true,
      attributes: true,
      attributeFilter: ["style", "class"]
    });
  }

  setScanInfo("Mode realtime aktif. Arahkan QR dari HP ke kamera.", "text-success");

  // Cek dukungan dan izin kamera agar pesan error lebih jelas.
  if (navigator.mediaDevices && navigator.mediaDevices.getUserMedia) {
    navigator.mediaDevices.getUserMedia({
        video: {
          facingMode: "environment"
        }
      })
      .then((stream) => {
        setScanInfo("Kamera siap. Menunggu QR...", "text-success");
        stream.getTracks().forEach(track => track.stop());
      })
      .catch(() => {
        setScanInfo("Kamera ditolak/tidak tersedia. Izinkan kamera lalu refresh halaman.", "text-danger");
      });
  } else {
    setScanInfo("Browser tidak mendukung akses kamera.", "text-danger");
  }

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
    setScanInfo("QR terdeteksi. Mengirim data ke server...", "text-primary");

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
      })
      .catch(() => {
        statusBox.innerHTML = "<div class='alert alert-danger'>Gagal terhubung ke server. Coba lagi.</div>";
      })
      .finally(() => {
        setTimeout(() => {
          isProcessing = false;
          setScanInfo("Mode realtime aktif. Siap scan QR berikutnya.", "text-success");
        }, 1200);
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
  // - Hanya menerima URL yang berakhiran .png, .jpg, .jpeg, atau .svg untuk meminimalkan input yang tidak relevan.
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
    if (!url.match(/\.(png|jpg|jpeg|svg)$/i)) {
      box.innerHTML = "<div class='alert alert-danger'>URL harus berupa gambar (.png/.jpg/.jpeg/.svg)</div>";
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
