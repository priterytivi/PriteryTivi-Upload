<?php
// Get the video file from the query parameter
$file = isset($_GET['file']) ? $_GET['file'] : '';
$file = htmlspecialchars($file);

if (empty($file) || !file_exists($file)) {
    die("File not found.");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Video Player</title>

    <!-- JW Player Script -->
    <script src="https://content.jwplatform.com/libraries/SAHhwvZq.js"></script>

    <!-- Custom CSS -->
    <style>
        /* Styling for the player container */
        #jwplayerDiv {
            width: 100%;  /* Full width */
            max-width: 800px; /* Max width for responsiveness */
            margin: 0 auto;  /* Center the player */
            border: 2px solid #000; /* Border around the player */
            border-radius: 10px; /* Rounded corners */
            background-color: #000; /* Background color */
        }

        /* Optional: Styling for the player title */
        h1 {
            text-align: center;
            color: #333;
            font-family: Arial, sans-serif;
        }

        /* Optional: Styling for the container */
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            padding: 20px;
            margin: 0;
        }
    </style>
</head>
<body>
    <h1>Video Player</h1>

    <!-- JW Player Container -->
    <div id="jwplayerDiv"></div>

    <!-- JW Player Setup -->
    <script>
        jwplayer("jwplayerDiv").setup({
            file: "<?php echo $file; ?>",  // Dynamically set the video file
            width: "100%",
            height: "500px",
            type: "<?php echo strtolower(pathinfo($file, PATHINFO_EXTENSION)); ?>" // Set video type based on file extension
        });
    </script>
</body>
</html>
