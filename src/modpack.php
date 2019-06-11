<?php

require_once "./app/bootstrap.php";

use SimpleModpack\Models\Modpack;
use SimpleModpack\Helpers\Zipper;
use SimpleModpack\Exceptions\Zipper\ZipperException;

$type = $_GET['type'] == 'server' ? Modpack::SERVER_ONLY : Modpack::CLIENT_ONLY;

$modpack = Modpack::fromFolder(__DIR__ . DIRECTORY_SEPARATOR . 'mods', $type);

try {
    $zip = Zipper::fromModpack($modpack);

    $zip->getZip();
} catch (ZipperException $e) {
    print $e->getMessage();
    exit;
}
