<?php
require_once('resources/Includes.php');

$args = $argv;
$boxID = 1;
$args[3] = isset($argv[3]) ? $argv[3] : null;
$args[4] = isset($args[4]) ? $args[4] : null;
if (count($argv) === 1) {
    $wizard = new InstallationWizard();
    $args = $wizard->projectWizard($args);
}
$selectedBox = array_key_exists('box', $args) ? DataHelper::$boxName[$args['box']] : 'TwistedBoxInstaller';
$boxHelper = new $selectedBox();
print_r($args);
if ($args[1] === 'help') {
    VagrantrunnerHelp::help();
} elseif ($args[1] === 'destroy' && isset($args[2])) {
    VagrantWorker::tearDown($args[2]);
} elseif ($args[1] === 'init') {
    $boxHelper->setUp($args[2], $args[3], $args[4]);
} elseif (count($args) > 1 && $args[1] !== 'destroy') {
    echo 'Command definition not found, assuming init a new project';
    $boxHelper->setUp($args[1], $args[3]);
} else {
    throw new LogicException('Unknown or incomplete command');
}