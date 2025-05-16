<?php
require_once __DIR__ . '/config/firebase-config.php';

try {
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Validate form data
        $nama = htmlspecialchars($_POST['nama'] ?? '');
        $ucapan = htmlspecialchars($_POST['ucapan'] ?? '');
        $foto = $_FILES['foto'] ?? null;

        if (empty($nama) || empty($ucapan) || empty($foto)) {
            throw new Exception("Semua field harus diisi");
        }

        // File upload handling
        $target_dir = "uploads/";
        if (!file_exists($target_dir)) {
            mkdir($target_dir, 0777, true);
        }

        $imageFileType = strtolower(pathinfo($foto["name"], PATHINFO_EXTENSION));
        $target_file = $target_dir . uniqid() . '.' . $imageFileType;
        
        // Valid file extensions
        $valid_extensions = array("jpg", "jpeg", "png", "gif");

        // Validate file type
        if (!in_array($imageFileType, $valid_extensions)) {
            throw new Exception("Hanya file JPG, JPEG, PNG & GIF yang diperbolehkan.");
        }

        // Validate file size (max 5MB)
        if ($foto["size"] > 5000000) {
            throw new Exception("File terlalu besar. Maksimal 5MB.");
        }

        if (move_uploaded_file($foto["tmp_name"], $target_file)) {
            // Save to Firebase Realtime Database
            $newPost = $database
                ->getReference('ucapan')
                ->push([
                    'nama' => $nama,
                    'ucapan' => $ucapan,
                    'foto_url' => $target_file,
                    'timestamp' => time()
                ]);

            header("Location: tambah-ucapan.php");
            exit();
        } else {
            throw new Exception("Gagal mengupload file.");
        }
    }
} catch (Exception $e) {
    error_log($e->getMessage());
    echo "Error: " . $e->getMessage();
}
?>
