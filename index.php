<?php

$apiUrl = 'http://URL/api/api.php';

$localDirectory = __DIR__ . DIRECTORY_SEPARATOR . 'website' . DIRECTORY_SEPARATOR;

if (!is_dir($localDirectory)) {
    mkdir($localDirectory, 0777, true);
}

$ch = curl_init($apiUrl);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

$response = curl_exec($ch);

if (curl_errno($ch)) {
    echo 'Error:' . curl_error($ch);
} else {
    $files = json_decode($response, true);

    foreach ($files as $file) {
        $localFile = $localDirectory . $file['name'];
        if (file_exists($localFile) && filemtime($localFile) >= $file['modified']) {
            echo "File '{$file['name']}' is already up to date.
";
        } else {
            $fileContents = file_get_contents($file['url']);
            if ($fileContents === false) {
                echo "Failed to load contents of file '{$file['name']}'.
";
                continue;
            }

            $result = file_put_contents($localFile, $fileContents);
            if ($result === false) {
                echo "Failed to save file '{$file['name']}'.
";
            } else {
                echo "File '{$file['name']}' has been uploaded or updated.
";
            }
        }
    }
}

curl_close($ch);
?>
