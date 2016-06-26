<?php
require_once('TwistedBytes/TwistedBoxInstaller.php');
require_once('TwistedBytes/TwistedBoxHelp.php');
require_once('helpers/VagrantWorker.php');
$args = $argv;
$boxID = 1;
$boxHelper = new TwistedBoxInstaller();
if (count($argv) === 1) {
    $args = $boxHelper->projectWizard($args);
}
$args[3] = isset($argv[3]) ? $argv[3] : null;
if ($args[1] === 'help') {
    TwistedBoxHelp::help();
} elseif ($args[1] === 'destroy' && isset($args[2])) {
    VagrantWorker::tearDown($args[2]);
} elseif ($args[1] === 'init') {
    $boxHelper->setUp($args[2], $args[3]);
} elseif (count($args) > 1) {
    echo 'Command definition not found, assuming init a new project';
    $boxHelper->setUp($args[1], $args[3]);
}