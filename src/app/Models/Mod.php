<?php

namespace SimpleModpack\Models;

class Mod
{
    const IGNORED_FILES = ['.', '..', '.gitkeep', '.htaccess'];

    /**
     * @var string $name Name of the mod.
     */
    public $name;

    /**
     * @var string $filename Path to the most recent jar file.
     */
    public $filename;

    /**
     * @var int $size File size of the mod's jar file.
     */
    public $size;

    /**
     * @var int $updated jar file's update time in Unix timestamp.
     */
    public $updated;

    /**
     * @var string $checksum MD5 checksum of the mod's jar file.
     */
    public $checksum;

    /**
     * Converts the \SimpleModpack\Models\Mod object to array.
     * 
     * @return array
     */
    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'filename' => $this->filename,
            'size' => $this->size,
            'updated' => $this->updated,
            'checksum' => $this->checksum
        ];
    }

    /**
     * Builds the \SimpleModpack\Models\Mod object from a given folder path.
     * 
     * @param string $folder Path to the modpack's folder.
     * @return \SimpleModpack\Models\Mod|null
     */
    public static function fromFolder(string $folder): ?Mod
    {
        if (file_exists($folder) && is_dir($folder)) {
            $modJarFiles = preg_grep('#\.jar$#i', array_diff(scandir($folder), self::IGNORED_FILES));

            if (count($modJarFiles) > 0) {
                $mod = new self();
                $mod->name = basename($folder);

                $mod->filename = array_pop($modJarFiles);
                $mod->updated = filemtime($folder . DIRECTORY_SEPARATOR . $mod->filename);
                foreach ($modJarFiles as $file) {
                    $changed = filemtime($folder . DIRECTORY_SEPARATOR . $file);
                    if ($mod->updated < $changed) {
                        $mod->filename = $file;
                        $mod->updated = $changed;
                    }
                }

                $mod->size = filesize($folder . DIRECTORY_SEPARATOR . $mod->filename);
                $mod->checksum = md5_file($folder . DIRECTORY_SEPARATOR . $mod->filename);
            }
        }

        return $mod ?? null;
    }

    /**
     * Builds the collection of mods from a given folder path.
     * 
     * @param string $folder Path to the folder with mods.
     * @return \SimpleModpack\Models\Mod[]
     */
    public static function modsListFromFolder(string $folder): array
    {
        $mods = [];

        if (file_exists($folder) && is_dir($folder)) {
            foreach (array_diff(scandir($folder), self::IGNORED_FILES) as $modFolder) {
                $mod = self::fromFolder($folder . DIRECTORY_SEPARATOR . $modFolder);

                if ($mod != null) {
                    $mods[] = $mod;
                }
            }
        }

        return $mods;
    }
}
