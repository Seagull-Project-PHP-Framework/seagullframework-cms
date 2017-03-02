<?php

$rootDir = dirname(__FILE__) . '/../../..';
$varDir = dirname(__FILE__) . '/../../../var';

$configFile = "$varDir/localhost.conf.php";
require_once $configFile;

$arrPath = split('/', $_REQUEST['path']);
if (in_array("thumbs", $arrPath)) {
    list($hash, $userId, $albumId,,$imageName) = $arrPath;
    $imageName = "thumbs/$imageName";
} else {
    list($hash, $userId,$albumId,$imageName) = $arrPath;
}

$savePath = !empty($conf['path']['tmpDirOverride'])
    ? $conf['path']['tmpDirOverride']
    : $varDir . '/tmp';

$sessionName = $conf['cookie']['name'];

$missingFilePath = $rootDir . '/www/images/Image/icons/image-missing.png';
$errorFilePath =  $rootDir . '/www/images/Image/icons/emblem-unreadable.png';

session_save_path($savePath);
session_name($sessionName);
session_start();

$fid = $_SESSION['fid'];
if ($hash != md5($fid)) {
    error_log("bad entity id = $fid\n" , 3, "/var/tmp/my-errors.log");
    $path =  $errorFilePath;
    $fp = fopen($path, 'rb');
} else {
    $path = "$varDir/albums/$userId/$albumId/$imageName";

    if (is_file($path)) {
       $fp = fopen($path, 'rb');
    } else {
        $path = $missingFilePath;
        $fp = fopen($path, 'rb');
    }
}

// send the right headers
header("Content-Type: image/png");
header("Content-Length: " . filesize($path));
// dump the picture and stop the script
fpassthru($fp);

?>