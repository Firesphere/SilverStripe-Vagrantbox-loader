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
    public static function startVagrant($projectName, $boxID = 1)
    {
        echo "\nBooting Vagrant machine, please have a bit of patience!\n";
        self::prepareBox($projectName, $boxID);
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

    private static function prepareBox($projectName, $boxID) {
        switch ($boxID) {
            case 1:
                copy(__DIR__ . '/../resources/Vagrantfile', $projectName . '/Vagrantfile');
                break;
            case 2:
                echo "\nCloning BetterBrief base installation files to project root\n";
                shell_exec("cd $projectName;git clone git@github.com:BetterBrief/vagrant-skeleton.git .");
                echo "Base installation created. Booting machine. This might take a while\n";
                break;
        }

    }

}