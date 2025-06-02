<?php
// Mulai session
session_start();

// Hapus semua data session
session_unset();
session_destroy();

// Redirect ke halaman login (ubah ke halaman yang sesuai)
header("Location: 2_login.php");
exit();
?>