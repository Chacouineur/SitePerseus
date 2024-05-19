<?php
function createZipFromDirectory($sourceDir, $zipFilePath)
{
    // Initialize the ZipArchive object
    $zip = new ZipArchive();

    // Try to open the zip file
    if ($zip->open($zipFilePath, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== true) {
        throw new Exception("Could not create ZIP file: $zipFilePath");
    }

    // Ensure the source directory has a trailing slash
    $sourceDir = rtrim($sourceDir, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;

    // Recursive function to add files to the zip
    $files = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator($sourceDir),
        RecursiveIteratorIterator::LEAVES_ONLY
    );

    foreach ($files as $file) {
        // Skip directories (they will be added automatically)
        if (!$file->isDir()) {
            // Get the real and relative path for the current file
            $filePath = $file->getRealPath();
            $relativePath = substr($filePath, strlen($sourceDir));

            // Replace backslashes with forward slashes for ZIP format
            $relativePath = str_replace(DIRECTORY_SEPARATOR, '/', $relativePath);

            // Add the file to the zip archive
            $zip->addFile($filePath, $relativePath);
        }
    }

    // Close the zip file
    $zip->close();
}

try {
    // Define the source directory and the output zip file path
    $sourceDir = __DIR__ . '/Configurations'; // Change this to your source directory
    $zipFilePath = __DIR__ . '/compressed.zip'; // Change this to your desired output zip file path

    // Create the ZIP file
    createZipFromDirectory($sourceDir, $zipFilePath);

    echo "ZIP file created successfully: $zipFilePath";
} catch (Exception $e) {
    echo 'Caught exception: ', $e->getMessage(), "\n";
}
?>
