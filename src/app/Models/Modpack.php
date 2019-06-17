<?php

namespace SimpleModpack\Models;

use SimpleModpack\Models\Mod;

class Modpack
{
    const INCLUDE_ALL = 'full';
    const CLIENT_ONLY = 'client';
    const SERVER_ONLY = 'server';

    public $updated = 0;

    /**
     * @var array[string]Mod[]
     */
    public $mods = [
        'common' => [],
        'server' => [],
        'client' => [],
    ];

    /**
     * Just some meta-properties.
     * 
     * @var string $folder
     * @var string $mode
     */
    public $folder, $type;

    /**
     * Converts the \SimpleModpack\Models\Modpack object to array.
     * 
     * @return array
     */
    public function toArray(): array
    {
        $array = [
            'type' => $this->type,
            'updated' => $this->updated,
            'mods' => []
        ];

        foreach ($this->mods as $category => $mods) {
            foreach ($mods as $mod) {
                $array['mods'][$category][] = $mod->toArray();
            }
        }

        return $array;
    }

    /**
     * Builds the \SimpleModpack\Models\Modpack object from a given folder
     * 
     * @param string $folder Path to the folder where the modpack resides.
     * @param string $include What should be included in the modpack.
     * @return \SimpleModpack\Models\Modpack
     */
    public static function fromFolder(string $folder = './mods', string $include = self::INCLUDE_ALL): Modpack
    {
        $modpack = new self();
        $modpack->updated = 0;

        $modpack->folder = realpath($folder);
        $modpack->type = $include;

        if (file_exists($modpack->folder) && is_dir($modpack->folder)) {
            $modpack->mods['common'] = Mod::modsListFromFolder($modpack->folder . DIRECTORY_SEPARATOR . 'common');

            if ($include == self::INCLUDE_ALL || $include == self::SERVER_ONLY) {
                $modpack->mods['server'] = Mod::modsListFromFolder($modpack->folder . DIRECTORY_SEPARATOR . 'server');
            }

            if ($include == self::INCLUDE_ALL || $include == self::CLIENT_ONLY) {
                $modpack->mods['client'] = Mod::modsListFromFolder($modpack->folder . DIRECTORY_SEPARATOR . 'client');
            }

            $modpack->updated = max(array_map(function ($o) {
                return $o->updated;
            }, $modpack->mods['common'] + $modpack->mods['server'] + $modpack->mods['client']));
        }

        return $modpack;
    }
}
