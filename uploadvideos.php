<?php
if(isset($_POST["submit"])) {
    $targetDir = "uploads/";
    $thumbnailDir = "thumbnails/";
    $videoTitle = $_POST["title"]; // Get the title from the form and sanitize it
    // Sanitize the title to allow only alphanumeric characters, spaces, exclamation marks, question marks, commas, and periods
    $videoTitle = preg_replace("/[^a-zA-Z0-9\s!?.,]/", "", $videoTitle);
    $videoFile = $_FILES["video"];
    $videoName = basename($videoFile["name"]);
    $targetFile = $targetDir . $videoTitle . ".mp4"; // Rename the file with the provided title and change the extension to .mp4
    $thumbnailFile = $thumbnailDir . $videoTitle . ".jpg"; // Use the same title for the thumbnail

    $uploadOk = 1;
    $videoFileType = strtolower(pathinfo($videoName, PATHINFO_EXTENSION));

    // Check if file already exists
    if (file_exists($targetFile)) {
        echo "Sorry, a file with the same title already exists.";
        $uploadOk = 0;
    }

    // Check file size
    if ($videoFile["size"] > 50000000) { // 50 MB
        echo "Sorry, your file is too large.";
        $uploadOk = 0;
    }

    // Allow certain file formats
    $allowedFormats = ["mp4", "avi", "mov", "wmv", "webm"];
    if(!in_array($videoFileType, $allowedFormats)) {
        echo "Sorry, only MP4, AVI, MOV, WMV, and WEBM files are allowed.";
        $uploadOk = 0;
    }

    // Check if $uploadOk is set to 0 by an error
    if ($uploadOk == 0) {
        echo "Sorry, your file was not uploaded.";
    } else {
        if (move_uploaded_file($videoFile["tmp_name"], $targetFile)) {
            echo "The file ". $videoTitle . " has been uploaded.";

            // Generate thumbnail using ffmpeg
            $ffmpegPath = "C:\\Users\\eugene van linsangan\\Downloads\\ffmpeg\\ffmpeg-master-latest-win64-gpl\\ffmpeg-master-latest-win64-gpl\\bin\\ffmpeg.exe";
            $cmd = "\"$ffmpegPath\" -i " . escapeshellarg($targetFile) . " -ss 00:00:01 -vframes 1 " . escapeshellarg($thumbnailFile);
            $ret = shell_exec($cmd);
            // if (!file_exists($thumbnailFile) || empty($ret)) {

            // }
        } else {
            echo "Sorry, there was an error uploading your file.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Video Upload</title>
</head>
<body>
    <h2>Upload Video</h2>
    <form action="" method="post" enctype="multipart/form-data">
        <label for="title">Title:</label>
        <input type="text" id="title" name="title" required><br><br>
        <input type="file" name="video" accept="video/*" required><br><br>
        <button type="submit" name="submit">Upload</button>
    </form>
</body>
</html>
