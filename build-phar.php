#!/usr/bin/env php
<?php
$srcRoot = './src';
$buildRoot = './build';

$phar = new Phar(
    $buildRoot . '/vagrantrunner.phar',
    FilesystemIterator::CURRENT_AS_FILEINFO | FilesystemIterator::KEY_AS_FILENAME,
    'vagrantrunner.phar'
);
// start buffering. Mandatory to modify stub.
$phar->startBuffering();

// Get the default stub. You can create your own if you have specific needs
$defaultStub = $phar->createDefaultStub('index.php');

// Adding files
$phar->buildFromDirectory(__DIR__ . '/src');

// Create a custom stub to add the shebang
$stub = "#!/usr/bin/php \n".$defaultStub;

// Add the stub
$phar->setStub($stub);

$phar->stopBuffering();