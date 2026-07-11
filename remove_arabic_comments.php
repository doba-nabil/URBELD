<?php

$directories = [
    __DIR__ . '/app',
    __DIR__ . '/database',
    __DIR__ . '/routes',
    __DIR__ . '/resources/views',
];

function containsArabic($string) {
    return preg_match('/\p{Arabic}/u', $string);
}

function processDirectory($dir) {
    $files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($dir));
    foreach ($files as $file) {
        if ($file->isDir()) continue;
        $ext = pathinfo($file->getFilename(), PATHINFO_EXTENSION);
        if (!in_array($ext, ['php'])) continue;

        $content = file_get_contents($file->getRealPath());
        $lines = explode("\n", $content);
        $modified = false;

        foreach ($lines as $i => $line) {
            // Match `//` comments that contain Arabic.
            if (preg_match('#//(.*)#', $line, $matches)) {
                if (containsArabic($matches[1])) {
                    $lines[$i] = preg_replace('#\s*//.*#', '', $line);
                    $modified = true;
                }
            }
            // Also match `<!-- ... -->` containing Arabic, if it's purely a comment line
            if (preg_match('#<!--(.*)-->#', $line, $matches)) {
                if (containsArabic($matches[1])) {
                    $lines[$i] = preg_replace('#\s*<!--.*-->#', '', $line);
                    $modified = true;
                }
            }
        }

        if ($modified) {
            // Remove empty lines created by comment removal
            $lines = array_filter($lines, function($l) {
                return trim($l) !== '';
            });
            file_put_contents($file->getRealPath(), implode("\n", $lines) . "\n");
            echo "Modified: " . $file->getRealPath() . "\n";
        }
    }
}

foreach ($directories as $dir) {
    if (is_dir($dir)) {
        processDirectory($dir);
    }
}
echo "Done.\n";
