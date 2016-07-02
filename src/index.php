<?php
require_once('resources/Includes.php');

$args = $argv;
$args['box'] = 1;
$args[3] = isset($argv[3]) ? $argv[3] : null;
$args[4] = isset($args[4]) ? $args[4] : null;
if (count($argv) === 1) {
    $wizard = new InstallationWizard();
    $args = $wizard->projectWizard($args);
} else {
    echo "Suggested is to use the wizard, the direct method is now deprecated\n\n";
}
$selectedBox = array_key_exists('box', $args) ? DataHelper::$boxName[$args['box']] : 'TwistedBoxInstaller';
/** @var BetterBriefBoxInstaller|TwistedBoxInstaller $boxHelper */
$boxHelper = new $selectedBox();
if ($args[1] === 'help') {
    VagrantrunnerHelp::help();
} elseif ($args[1] === 'destroy' && isset($args[2])) {
    VagrantWorker::tearDown($args[2]);
} elseif ($args[1] === 'init') {
    $boxHelper->setUp($args[2], $args[3], $args[4]);
} elseif (count($args) > 1 && $args[1] !== 'destroy') {
    echo 'Command definition not found, assuming init a new project';
    $boxHelper->setUp($args[1], $args['box'], $args[3]);
} else {
    throw new LogicException('Unknown or incomplete command');
}