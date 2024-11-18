<?php
// upload.php
header('Content-Type: application/json');
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    die(json_encode(['error' => 'Unauthorized']));
}

$target_dir = "../quizgambar/";
if (!file_exists($target_dir)) {
    mkdir($target_dir, 0777, true);
}

if (!empty($_FILES['file']['name'])) {
    $file = $_FILES['file'];
    $originalName = basename($file['name']);
    
    // Membuat prefix guestm_ dan membersihkan nama file
    $cleanFileName = preg_replace('/[^a-zA-Z0-9.]/', '_', $originalName);
    $newFileName = 'guestm_' . $cleanFileName;
    $targetPath = $target_dir . $newFileName;
    
    // Cek apakah file dengan nama yang sama sudah ada
    if (file_exists($targetPath)) {
        // Jika file sudah ada, hapus file lama
        unlink($targetPath);
    }
    
    $allowedTypes = [
        'image/jpeg',
        'image/png',
        'image/gif',
        'video/mp4',
        'video/webm',
        'video/ogg',
        'audio/mpeg',
        'audio/ogg',
        'audio/wav'
    ];
    
    $fileType = mime_content_type($file['tmp_name']);
    
    if (in_array($fileType, $allowedTypes)) {
        if (move_uploaded_file($file['tmp_name'], $targetPath)) {
            $relativePath = str_replace('../', '', $targetPath);
            echo json_encode([
                'location' => '../' . $relativePath,
                'success' => true
            ]);
            exit;
        }
    }
}

echo json_encode(['error' => 'Upload failed']);
error_log('Upload attempt: ' . print_r($_FILES, true));