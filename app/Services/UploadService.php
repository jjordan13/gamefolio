<?php
declare(strict_types=1);

namespace App\Services;

class UploadService
{
    private string $uploadDir;
    private array $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
    private int $maxSize = 5 * 1024 * 1024; // 5 MB

    public function __construct()
    {
        $this->uploadDir = dirname(__DIR__, 2) . '/public/uploads/';

        // Создаём папку, если её нет
        if (!is_dir($this->uploadDir)) {
            mkdir($this->uploadDir, 0755, true);
        }
    }

    public function uploadFile(array $file, string $subdir = ''): ?string
    {
        if ($file['error'] !== UPLOAD_ERR_OK) {
            return null;
        }

        if ($file['size'] > $this->maxSize) {
            return null;
        }

        $finfo = \finfo_open(FILEINFO_MIME_TYPE);
        $mimeType = \finfo_file($finfo, $file['tmp_name']);
        \finfo_close($finfo);

        if (!in_array($mimeType, $this->allowedTypes)) {
            return null;
        }

        $targetDir = $this->uploadDir . $subdir;
        if (!is_dir($targetDir)) {
            mkdir($targetDir, 0755, true);
        }

        $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
        $filename = uniqid('img_', true) . '.' . $extension;
        $targetPath = $targetDir . '/' . $filename;

        if (move_uploaded_file($file['tmp_name'], $targetPath)) {
            return '/uploads/' . $subdir . '/' . $filename;
        }

        return null;
    }

    public function deleteFile(string $path): void
    {
        $fullPath = dirname(__DIR__, 2) . $path;
        if (file_exists($fullPath)) {
            unlink($fullPath);
        }
    }

    public function uploadMultipleFiles(array $files, string $subdir = ''): array
    {
        $uploadedPaths = [];

        foreach ($files['tmp_name'] as $index => $tmpName) {
            $file = [
                'name' => $files['name'][$index],
                'type' => $files['type'][$index],
                'tmp_name' => $tmpName,
                'error' => $files['error'][$index],
                'size' => $files['size'][$index],
            ];

            $path = $this->uploadFile($file, $subdir);
            if ($path) {
                $uploadedPaths[] = $path;
            }
        }

        return $uploadedPaths;
    }
}