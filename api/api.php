<?php
header('Content-Type: application/json');

$directory = 'website/';

if (!is_dir($directory)) {
    echo json_encode(['error' => 'Directory not found']);
    exit();
}

$files = array_diff(scandir($directory), array('..', '.'));

$fileList = [];
foreach ($files as $file) {
    $filePath = $directory . $file;
    $fileList[] = [
        'name' => $file,
        'size' => filesize($filePath),
        'modified' => filemtime($filePath),
        'url' => 'http://URL/api/' . $filePath
    ];
}

echo json_encode($fileList);
