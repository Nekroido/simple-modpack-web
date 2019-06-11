<?php

namespace SimpleModpack\Helpers;

use \ZipArchive;
use SimpleModpack\Exceptions\Zipper\InvalidModpackException;
use SimpleModpack\Exceptions\Zipper\EmptyModpackException;

class Zipper
{
    const ZIP_ERROR = [
        \ZipArchive::ER_EXISTS => 'File already exists.',
        \ZipArchive::ER_INCONS => 'Zip archive inconsistent.',
        \ZipArchive::ER_INVAL => 'Invalid argument.',
        \ZipArchive::ER_MEMORY => 'Malloc failure.',
        \ZipArchive::ER_NOENT => 'No such file.',
        \ZipArchive::ER_NOZIP => 'Not a zip archive.',
        \ZipArchive::ER_OPEN => "Can't open file.",
        \ZipArchive::ER_READ => 'Read error.',
        \ZipArchive::ER_SEEK => 'Seek error.',
    ];

    const SAVE_FOLDER = '/tmp/simple-modpack';

    /**
     * @var string[] $files
     */
    private $files = [];

    /**
     * @var string $filename
     */
    private $filename;

    /**
     * @var \SimpleModpack\Models\Modpack $modpack
     */
    private $modpack;

    private $writemode = \ZipArchive::CREATE | \ZipArchive::OVERWRITE;

    public function __construct(string $name, string $suffix = null)
    {
        $this->filename = self::SAVE_FOLDER . DIRECTORY_SEPARATOR . basename($name) . ($suffix ?? '') . '.zip';
    }

    /**
     * Creates \SimpleModpack\Helpers\Zipper object for a given \SimpleModpack\Models\Modpack.
     * 
     * @throws \SimpleModpack\Exceptions\Zipper\ZipperException
     * @return \SimpleModpack\Helpers\Zipper
     */
    public static function fromModpack(\SimpleModpack\Models\Modpack $modpack): Zipper
    {
        if ($modpack == null || $modpack->updated == 0) {
            throw new InvalidModpackException("Invalid modpack provided!");
        } else if (count($modpack->mods) == 0) {
            throw new EmptyModpackException("Modpack has no valid mod files!");
        }

        $zip = new Zipper($modpack->folder, "-{$modpack->type}");
        $zip->modpack = $modpack;

        foreach ($modpack->mods as $category => $mods) {
            foreach ($mods as $mod) {
                $zip->addFile($modpack->folder . DIRECTORY_SEPARATOR . $category . DIRECTORY_SEPARATOR . $mod->name . DIRECTORY_SEPARATOR . $mod->filename);
            }
        }

        return $zip;
    }

    /**
     * Adds a file for packing.
     * 
     * @param string $file Path to a file.
     * @return \SimpleModpack\Helpers\Zipper
     */
    public function addFile(string $file): Zipper
    {
        $this->files[] = $file;

        return $this;
    }


    /**
     * Outputs a zipped \SimpleModpack\Models\Modpack.
     * 
     * @throws \SimpleModpack\Exceptions\Zipper\ZipperException
     */
    public function getZip()
    {
        if (count($this->files) == 0) {
            throw new EmptyModpackException("Modpack has no valid mod files!");
        }
        if (!file_exists($this->filename) || filemtime($this->filename) <= $this->modpack->updated) {
            $zip = new \ZipArchive;
            $result = $zip->open($this->filename, ZipArchive::CREATE | ZIPARCHIVE::OVERWRITE);
            if ($result !== true) {
                $msg = isset(self::ZIP_ERROR[$result]) ? self::ZIP_ERROR[$result] : 'Unknown error.';
                throw \Exception($msg);
            }

            foreach ($this->files as $mod) {
                $zip->addFile($mod, basename($mod));
            }

            $zip->close();

            touch($this->filename, $this->modpack->updated);
        }

        header('Content-Type: application/zip');
        header('Content-Length: ' . filesize($this->filename));
        readfile($this->filename);
    }
}
