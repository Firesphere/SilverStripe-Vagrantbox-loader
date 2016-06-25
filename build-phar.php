<?php
$srcRoot = "./src";
$buildRoot = "./build";

$phar = new Phar(
    $buildRoot . "/vagrantrunner.phar",
    FilesystemIterator::CURRENT_AS_FILEINFO | FilesystemIterator::KEY_AS_FILENAME,
    "vagrantrunner.phar"
);
$phar->buildFromDirectory(__DIR__ . '/src');
$phar->setStub($phar->createDefaultStub('index.php'));
