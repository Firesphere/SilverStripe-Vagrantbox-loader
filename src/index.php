<?php
require_once('helpers/VagrantWorker.php');
require_once('helpers/InstallationWizard.php');
require_once('TwistedBytes/TwistedBoxInstaller.php');
require_once('TwistedBytes/TwistedBoxHelp.php');
$args = $argv;
$boxID = 1;
$wizard = new InstallationWizard();
if (count($argv) === 1) {
    $args = $wizard->projectWizard($args);
}
$boxHelper = new TwistedBoxInstaller();
$args[3] = isset($argv[3]) ? $argv[3] : null;
$args[4] = isset($args[4]) ? $args[4] : null;
// @todo Make this dynamic. It's too strict atm.
if ($args[1] === 'help') {
    TwistedBoxHelp::help();
} elseif ($args[1] === 'destroy' && isset($args[2])) {
    VagrantWorker::tearDown($args[2]);
} elseif ($args[1] === 'init') {
    $boxHelper->setUp($args[2], $args[3], $args[4]);
} elseif (count($args) > 1 && $args[1] !== 'destroy') {
    echo 'Command definition not found, assuming init a new project';
    $boxHelper->setUp($args[1], $args[3]);
} else {
    throw new LogicException('Unknown command');
}