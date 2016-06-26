<?php
require_once('TwistedBoxInstaller.php');
require_once('TwistedBoxHelp.php');
$args = $argv;
$args[3] = isset($argv[3]) ? $argv[3] : null;
if (count($argv) === 1) {
    throw new LogicException("No task or projectname specified");
}
if ($args[1] === 'help') {
    TwistedBoxHelp::help(); // @todo make it do things
} elseif ($args[1] === 'destroy') {
    TwistedBoxInstaller::tearDown($args[2]);
} elseif ($args[1] === 'init') {
    TwistedBoxInstaller::setUp($args[2], $args[3]);
} elseif (count($args) > 1) {
    echo "Command definition not found, assuming init a new project";
    TwistedBoxInstaller::setUp($args[1], $args[3]);
}