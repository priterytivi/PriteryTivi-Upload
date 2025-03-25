<?php
header('Content-Type: application/json');

// Define the directory to store uploaded videos
$uploadDir = 'uploads/';
$historyFile = 'uploads.json';
if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0777, true);
}

// Read existing history if it exists
$history = [];
if (file_exists($historyFile)) {
    $history = json_decode(file_get_contents($historyFile), true);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_FILES['video']) && $_FILES['video']['error'] === UPLOAD_ERR_OK) {
        $fileTmpPath = $_FILES['video']['tmp_name'];
        $fileName = basename($_FILES['video']['name']);
        $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
        $allowedExtensions = ['mp4', 'mkv'];

        if (in_array($fileExt, $allowedExtensions)) {
            $newFileName = uniqid() . '.' . $fileExt;
            $destPath = $uploadDir . $newFileName;

            if (move_uploaded_file($fileTmpPath, $destPath)) {
                // Add video to history
                $history[] = [
                    'fileName' => $newFileName,
                    'filePath' => $destPath
                ];

                // Save updated history
                file_put_contents($historyFile, json_encode($history));

                echo json_encode([
                    'success' => true,
                    'fileUrl' => $destPath
                ]);
            } else {
                echo json_encode([
                    'success' => false,
                    'message' => 'Error moving the uploaded file.'
                ]);
            }
        } else {
            echo json_encode([
                'success' => false,
                'message' => 'Invalid file format. Only MP4 and MKV are allowed.'
            ]);
        }
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'No file uploaded or an error occurred.'
        ]);
    }
} else {
    echo json_encode([
        'success' => false,
        'message' => 'Invalid request method.'
    ]);
}
?>
