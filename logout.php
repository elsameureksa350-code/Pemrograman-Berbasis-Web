<?php
session_start();
require_once "koneksi.php";

// Ambil nama nasabah sebelum logout (optional, biar bisa sapa pamit)
$nama_nasabah = "Nasabah";

if (isset($_SESSION['nasabah_id'])) {
    $nasabah_id = $_SESSION['nasabah_id'];
    $qNasabah   = $koneksi->query("SELECT nama FROM nasabah WHERE id = '$nasabah_id' LIMIT 1");
    if ($qNasabah && $row = $qNasabah->fetch_assoc()) {
        $nama_nasabah = $row['nama'];
    }
}

// Hapus semua session (logout beneran)
session_unset();
session_destroy();
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Logout - RECYCLEAN</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.js"></script>
</head>

<body class="bg-gradient-to-br from-green-50 to-emerald-100 min-h-screen flex flex-col">

  <!-- Header sederhana -->
  <header class="bg-white shadow-md">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="flex justify-between items-center py-4">
        <div class="flex items-center space-x-3">
          <div class="bg-green-600 p-2 rounded-full">
            <i data-lucide="recycle" class="w-8 h-8 text-white"></i>
          </div>
          <h1 class="text-2xl font-bold text-green-700">RECYCLEAN</h1>
        </div>
      </div>
    </div>
  </header>

  <!-- Main Content -->
  <main class="flex-1 flex items-center justify-center px-4">
    <div class="bg-white rounded-2xl shadow-xl max-w-lg w-full p-8 text-center">
      <div class="mx-auto mb-4 w-16 h-16 rounded-full bg-green-100 flex items-center justify-center">
        <i data-lucide="log-out" class="w-8 h-8 text-green-600"></i>
      </div>

      <h2 class="text-2xl font-bold text-gray-800 mb-2">
        Sampai jumpa, <?= htmlspecialchars($nama_nasabah) ?> 👋
      </h2>
      <p class="text-gray-600 mb-6">
        Kamu telah berhasil keluar dari akun RECYCLEAN.<br>
        Terima kasih sudah ikut menjaga lingkungan 🌿
      </p>

      <a href="login.php"
         class="inline-flex items-center justify-center px-5 py-2.5 rounded-lg bg-green-600 text-white font-semibold hover:bg-green-700 transition-colors">
        <i data-lucide="log-in" class="w-4 h-4 mr-2"></i>
        Masuk Kembali
      </a>

      <div class="mt-4">
        <a href="index.php" class="text-sm text-gray-500 hover:text-gray-700 underline">
          Kembali ke halaman utama
        </a>
      </div>
    </div>
  </main>

  <script>
    lucide.createIcons();
  </script>
</body>
</html>
