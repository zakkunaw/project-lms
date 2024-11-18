<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'instructor') {
    echo json_encode(['status' => 'error', 'message' => 'Unauthorized']);
    exit();
}

$targetDir = "../quizgambar/";
if (!is_dir($targetDir)) {
    mkdir($targetDir, 0755, true);
}

$chapterName = isset($_POST['chapter_name']) ? preg_replace("/[^a-zA-Z0-9]+/", "", $_POST['chapter_name']) : 'general';

if (isset($_FILES['file'])) {
    $file = $_FILES['file'];
    $originalName = pathinfo($file['name'], PATHINFO_FILENAME);
    $extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    $sanitizedFilename = preg_replace("/[^a-zA-Z0-9_-]/", "-", strtolower($originalName));
    
    // Nama file tanpa timestamp
    $fileName = $chapterName . "_" . $sanitizedFilename . "." . $extension;
    $targetFilePath = $targetDir . $fileName;

    // Definisikan ekstensi yang diizinkan
    $validImageExtensions = ['jpg', 'jpeg', 'png', 'gif'];
    $validAudioExtensions = ['mp3', 'wav', 'ogg'];
    $validVideoExtensions = ['mp4', 'webm', 'ogv'];
    $validExtensions = array_merge($validImageExtensions, $validAudioExtensions, $validVideoExtensions);

    if (in_array($extension, $validExtensions)) {
        // Hapus file jika sudah ada untuk menghindari redundansi
        if (file_exists($targetFilePath)) {
            unlink($targetFilePath);
        }

        if (move_uploaded_file($file['tmp_name'], $targetFilePath)) {
            $response = [
                'status' => 'success',
                'url' => $targetFilePath,
                'type' => in_array($extension, $validImageExtensions) ? 'image' : 
                         (in_array($extension, $validAudioExtensions) ? 'audio' : 'video')
            ];
            echo json_encode($response);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Failed to upload file']);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Invalid file type']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'No file uploaded']);
}
?>
