<?php

/**
 * Class VagrantWorker
 *
 * Vagrant methods to boot/destroy the box.
 *
 * @author Simon `Firesphere` Erkelens
 */
class VagrantWorker
{


    /**
     * @param string $projectName
     */
    public static function startVagrant($projectName)
    {
        echo "\nBooting Vagrant machine, please have a bit of patience!";
        copy(__DIR__ . '/../resources/Vagrantfile', $projectName . '/Vagrantfile');
        shell_exec('cd ' . $projectName . ';vagrant up');
        echo shell_exec('cd '. $projectName . ';vagrant status');
    }

    /**
     * @param string $projectName
     */
    public static function tearDown($projectName)
    {
        echo "\nDestroying vagrant box in $projectName\nYou might be asked to confirm destruction of this box.";
        echo shell_exec('cd ' . $projectName . ';vagrant halt;vagrant destroy -f');
        echo "\nVagrantbox in $projectName destroyed\nYour project is still safe, don't worry\n\n";
    }

}