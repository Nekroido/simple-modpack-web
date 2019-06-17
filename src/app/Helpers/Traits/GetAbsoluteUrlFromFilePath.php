<?php

namespace SimpleModpack\Helpers\Traits;

trait GetAbsoluteUrlFromFilePath
{
    public function getAbsoluteUrlFromFilePath(string $filePath): string
    {
        $prefix = 'http' . (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 's' : '') . '://'
            . $_SERVER['SERVER_NAME']
            . ($_SERVER['SERVER_PORT'] !== '80' ? ':' . $_SERVER['SERVER_PORT'] : '');
        $url = str_replace(DIRECTORY_SEPARATOR, '/', $prefix . str_replace($_SERVER["DOCUMENT_ROOT"], '', realpath($filePath)));

        return $url;
    }
}
