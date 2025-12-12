<?php
session_start();
require_once "koneksi.php";

$error = "";

// Kalau sudah login, langsung arahkan ke halaman masing-masing
if (isset($_SESSION['admin_id'])) {
    header("Location: index.php");
    exit;
}
if (isset($_SESSION['nasabah_id'])) {
    header("Location: dashboard.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = trim($_POST['password'] ?? '');
    $role = $_POST['role'] ?? '';

    if ($email === '' || $password === '' || $role === '') {
        $error = "Email, password, dan role wajib diisi.";
    } else {
        // Amankan input sederhana
        $email = $koneksi->real_escape_string($email);
        $password = $koneksi->real_escape_string($password);

        if ($role === 'admin') {

            $sql = "SELECT id, nama, email, password FROM admin 
                    WHERE email = '$email' LIMIT 1";
            $q = $koneksi->query($sql);

            if ($q && $q->num_rows === 1) {
                $admin = $q->fetch_assoc();

                // Sementara: cek password plain text
                // Kalau nanti pakai hash: ganti ke password_verify($password, $admin['password'])
                if ($admin['password'] === $password) {
                    $_SESSION['admin_id'] = $admin['id'];
                    $_SESSION['role']     = 'admin';

                    header("Location: index.php"); // halaman admin utama
                    exit;
                } else {
                    $error = "Password admin salah.";
                }
            } else {
                $error = "Admin dengan email tersebut tidak ditemukan.";
            }

        } elseif ($role === 'nasabah') {

            $sql = "SELECT id, nama, email, password FROM nasabah 
                    WHERE email = '$email' LIMIT 1";
            $q = $koneksi->query($sql);

            if ($q && $q->num_rows === 1) {
                $nasabah = $q->fetch_assoc();

                // Sementara: cek password plain text
                if ($nasabah['password'] === $password) {
                    $_SESSION['nasabah_id'] = $nasabah['id'];
                    $_SESSION['role']       = 'nasabah';

                    header("Location: dashboard.php"); // halaman dashboard nasabah
                    exit;
                } else {
                    $error = "Password nasabah salah.";
                }
            } else {
                $error = "Nasabah dengan email tersebut tidak ditemukan.";
            }

        } else {
            $error = "Role tidak dikenali.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Login - RECYCLEAN</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gradient-to-br from-green-50 to-emerald-100 min-h-screen flex items-center justify-center">

  <div class="bg-white shadow-2xl rounded-2xl max-w-md w-full p-8">
    <div class="flex items-center justify-center mb-6">
      <div class="bg-green-600 p-2 rounded-full mr-2">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor">
          <path d="M7 21a7 7 0 0 1 0-14h1" />
          <path d="M11 3a7 7 0 0 1 0 14h-1" />
          <path d="M14 21a7 7 0 0 0 0-14h-1" />
        </svg>
      </div>
      <h1 class="text-2xl font-bold text-green-700">RECYCLEAN</h1>
    </div>

    <h2 class="text-xl font-semibold text-gray-800 mb-2 text-center">
      Masuk ke Akun Anda
    </h2>
    <p class="text-sm text-gray-500 mb-6 text-center">
      Pilih role sebagai <span class="font-semibold">Admin</span> atau <span class="font-semibold">Nasabah</span>.
    </p>

    <?php if ($error): ?>
      <div class="mb-4 px-4 py-3 rounded bg-red-100 text-red-700 text-sm">
        <?= htmlspecialchars($error) ?>
      </div>
    <?php endif; ?>

    <form method="post" class="space-y-4">

      <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
        <input type="email" name="email" required
               class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500"
               placeholder="nama@email.com"
               value="<?= isset($_POST['email']) ? htmlspecialchars($_POST['email']) : '' ?>">
      </div>

      <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Password</label>
        <input type="password" name="password" required
               class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500"
               placeholder="••••••••">
      </div>

      <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Masuk sebagai</label>
        <div class="flex items-center gap-4 mt-1">
          <label class="inline-flex items-center">
            <input type="radio" name="role" value="nasabah"
                   class="text-green-600 focus:ring-green-500"
                   <?= (($_POST['role'] ?? '') === 'nasabah') ? 'checked' : '' ?>>
            <span class="ml-2 text-sm text-gray-700">Nasabah</span>
          </label>
          <label class="inline-flex items-center">
            <input type="radio" name="role" value="admin"
                   class="text-green-600 focus:ring-green-500"
                   <?= (($_POST['role'] ?? '') === 'admin') ? 'checked' : '' ?>>
            <span class="ml-2 text-sm text-gray-700">Admin</span>
          </label>
        </div>
      </div>

      <button type="submit"
              class="w-full bg-green-600 hover:bg-green-700 text-white font-semibold py-2.5 rounded-lg mt-4 transition-colors">
        Masuk
      </button>
    </form>

    <p class="mt-6 text-xs text-center text-gray-400">
      &copy; <?= date('Y') ?> RECYCLEAN. Semua hak dilindungi.
    </p>
  </div>

</body>
</html>
