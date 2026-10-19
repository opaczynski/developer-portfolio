<?php
header("Cache-Control: no-cache, no-store, must-revalidate");
header("Pragma: no-cache");
header("Expires: 0");
header("Content-Type: application/json; charset=utf-8");


$requestedFile = isset($_GET['file']) ? $_GET['file'] : 'translations';
$fileName = preg_replace('/[^a-zA-Z0-9_\-]/', '', $requestedFile) . '.json';
$filePath = __DIR__ . '/' . $fileName;

if (!file_exists($filePath)) {
    http_response_code(404);
    echo json_encode(
        ["error" => "File not found: " . $fileName], 
        JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE
    );
    exit;
}

$jsonContent = file_get_contents($filePath);
$decodedData = json_decode($jsonContent, true);

if (json_last_error() !== JSON_ERROR_NONE) {
    http_response_code(500);
    echo json_encode(
        ["error" => "Invalid JSON structure in server file"], 
        JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE
    );
    exit;
}
echo json_encode(
    $decodedData, 
    JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE
);