<?php
session_start();
session_unset();  // hapus semua data session
session_destroy(); // hancurkan session

// Kembali ke halaman login
echo "<script>
    alert('Anda telah logout.');
    window.location.href = '../login.php';
</script>";
exit;
?>
