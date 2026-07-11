<?php

$directories = [
    __DIR__ . '/app',
    __DIR__ . '/database',
    __DIR__ . '/routes',
    __DIR__ . '/resources',
];

function containsArabic($string) {
    return preg_match('/\p{Arabic}/u', $string);
}

function processPhpFile($filePath) {
    $content = file_get_contents($filePath);
    $tokens = token_get_all($content);
    $newContent = '';
    $modified = false;

    foreach ($tokens as $token) {
        if (is_array($token)) {
            $tokenId = $token[0];
            $tokenText = $token[1];
            if ($tokenId === T_COMMENT || $tokenId === T_DOC_COMMENT) {
                if (containsArabic($tokenText)) {
                    $modified = true;
                    continue; // Skip this comment
                }
            }
            $newContent .= $tokenText;
        } else {
            $newContent .= $token;
        }
    }

    if ($modified) {
        // Clean up empty lines that might have been left
        $newContent = preg_replace('/^\h*\v+/m', '', $newContent);
        file_put_contents($filePath, $newContent);
        echo "Modified PHP: " . $filePath . "\n";
    }
}

function processBladeFile($filePath) {
    $content = file_get_contents($filePath);
    $modified = false;

    // Remove blade comments {{-- ... --}} containing arabic
    $content = preg_replace_callback('/\{\{--.*?--\}\}/s', function($matches) use (&$modified) {
        if (containsArabic($matches[0])) {
            $modified = true;
            return '';
        }
        return $matches[0];
    }, $content);

    // Remove HTML comments <!-- ... --> containing arabic
    $content = preg_replace_callback('/<!--.*?-->/s', function($matches) use (&$modified) {
        if (containsArabic($matches[0])) {
            $modified = true;
            return '';
        }
        return $matches[0];
    }, $content);

    if ($modified) {
        file_put_contents($filePath, $content);
        echo "Modified Blade: " . $filePath . "\n";
    }
}

function processDirectory($dir) {
    $files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($dir));
    foreach ($files as $file) {
        if ($file->isDir()) continue;
        
        $filename = $file->getFilename();
        $path = $file->getRealPath();
        
        if (str_ends_with($filename, '.blade.php')) {
            processBladeFile($path);
        } elseif (str_ends_with($filename, '.php')) {
            processPhpFile($path);
        }
    }
}

foreach ($directories as $dir) {
    if (is_dir($dir)) {
        processDirectory($dir);
    }
}
echo "Done.\n";
