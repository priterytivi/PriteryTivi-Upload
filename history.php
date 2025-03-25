<?php
// Define the path for video history
$historyFile = 'uploads.json';
$uploadDir = 'uploads/';

// Read existing history if it exists
$history = [];
if (file_exists($historyFile)) {
    $history = json_decode(file_get_contents($historyFile), true);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['deleteFile'])) {
    $fileToDelete = $_POST['deleteFile'];

    // Find and delete video from directory and history
    foreach ($history as $index => $video) {
        if ($video['fileName'] === $fileToDelete) {
            unlink($video['filePath']); // Delete file
            unset($history[$index]);    // Remove from history
            file_put_contents($historyFile, json_encode(array_values($history))); // Update history
            break;
        }
    }
    header('Location: history.php'); // Refresh page after deletion
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Video Upload History</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            padding: 20px;
        }

        h1 {
            text-align: center;
            color: #333;
        }

        table {
            width: 100%;
            max-width: 800px;
            margin: 20px auto;
            border-collapse: collapse;
            background-color: #fff;
        }

        th, td {
            padding: 12px;
            text-align: center;
            border: 1px solid #ddd;
        }

        th {
            background-color: #333;
            color: white;
        }

        td a {
            color: #007BFF;
            text-decoration: none;
        }

        td a:hover {
            text-decoration: underline;
        }

        .action-btns {
            display: flex;
            justify-content: center;
            gap: 10px;
        }

        .action-btns a, .action-btns button {
            padding: 6px 12px;
            background-color: #007BFF;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            text-decoration: none;
        }

        .action-btns a:hover, .action-btns button:hover {
            background-color: #0056b3;
        }
		
body {
    font-family: Arial, sans-serif;
    background-color: #f4f4f4;
    padding: 20px;
    text-align: center; /* Ensures all content inside body is centered */
}

h1 {
    color: #333;
    font-size: 2.5em;
    text-transform: uppercase;
    letter-spacing: 2px;
    position: relative;
    display: inline-block;
    padding: 10px 20px;
    background-color: #fff;
    box-shadow: 2px 2px 10px rgba(0, 0, 0, 0.2);
    border-radius: 5px;
    transition: all 0.3s ease;
}

h1::before {
    content: '';
    position: absolute;
    top: 5px;
    left: 5px;
    right: 5px;
    bottom: 5px;
    background-color: rgba(0, 0, 0, 0.1);
    z-index: -1;
    border-radius: 5px;
    transform: scale(1.1);
}

h1:hover {
    transform: translateY(-5px);
    box-shadow: 5px 5px 20px rgba(0, 0, 0, 0.3);
}

a {
    text-decoration: none;
    font-size: 1.2em;
    color: #007BFF;
    text-transform: uppercase;
    letter-spacing: 1px;
    position: relative;
    display: inline-block;
    padding: 10px 20px;
    margin-top: 20px;
    border-radius: 5px;
    background-color: #f0f0f0;
    transition: all 0.3s ease;
}

a::before {
    content: '';
    position: absolute;
    top: 50%;
    left: 50%;
    width: 100%;
    height: 100%;
    background-color: #007BFF;
    z-index: -1;
    border-radius: 5px;
    transition: all 0.3s ease;
    transform: translate(-50%, -50%) scaleX(0);
}

a:hover {
    color: white;
    transform: translateY(-3px);
}

a:hover::before {
    transform: translate(-50%, -50%) scaleX(1);
}



    </style>
</head>
<body>
    <h1>Upload History</h1>
    <table>
        <thead>
            <tr>
                <th>Video File</th>
                <th>Watch Link</th>
                <th>Download Link</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if (count($history) > 0): ?>
                <?php foreach ($history as $video): ?>
                    <tr>
                        <td>
                            <a href="video_player.php?file=<?php echo urlencode($video['filePath']); ?>" target="_blank">
                                <?php echo htmlspecialchars($video['fileName']); ?>
                            </a>
                        </td>
                        <td>
                            <a href="video_player.php?file=<?php echo urlencode($video['filePath']); ?>" target="_blank">Watch</a>
                        </td>
                        <td>
                            <a href="<?php echo $uploadDir . urlencode($video['fileName']); ?>" download>Download</a>
                        </td>
                        <td>
                            <div class="action-btns">
                                <form method="POST" style="display:inline;">
                                    <input type="hidden" name="deleteFile" value="<?php echo htmlspecialchars($video['fileName']); ?>">
                                    <button type="submit" onclick="return confirm('Are you sure you want to delete this video?');">Delete</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="4">No videos uploaded yet.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
    <br>
    <a href="index.html">Back to Upload</a>
</body>
</html>
