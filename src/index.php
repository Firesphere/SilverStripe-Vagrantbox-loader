<?php
/**
 * Created by IntelliJ IDEA.
 * User: simon
 * Date: 25/06/16
 * Time: 16:00
 */
require_once('TwistedBoxInstaller.php');
require_once('TwistedBoxHelp.php');
if($argv[1] === 'help') {
    TwistedBoxHelp::help(); // @todo make it do things
}
elseif($argv[1] === 'destroy') {
    TwistedBoxInstaller::tearDown($argv[2]);
}
elseif($argv[1] === 'init' && isset($argv[3])) {
    TwistedBoxInstaller::setUp($argv[2], $argv[3]);
}
else {
    TwistedBoxInstaller::setUp($argv[2]);
}
