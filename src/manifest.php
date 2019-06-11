<?php

require_once "./app/bootstrap.php";

use SimpleModpack\Models\Modpack;

$type = $_GET['type'] == 'server' ? Modpack::SERVER_ONLY : ($_GET['type'] == 'client' ? Modpack::CLIENT_ONLY : Modpack::INCLUDE_ALL);

$modpack = Modpack::fromFolder(__DIR__ . DIRECTORY_SEPARATOR . 'mods', $type);

header('Content-type: text/json');
print json_encode($modpack->toArray());
