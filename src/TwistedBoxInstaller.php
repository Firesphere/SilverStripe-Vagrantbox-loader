<?php

/**
 * Class TwistedBoxInstaller
 *
 * Base install system to create a new TwistedBytes Vagrantbox.
 *
 * @author Simon `Sphere` Erkelens
 */
class TwistedBoxInstaller
{

    /**
     * @param string $projectName
     * @param null|string $gitSource
     */
    public static function setUp($projectName, $gitSource = null)
    {
        echo "\nShutting down all VirtualBox vagrant machines\nDon't worry, no data is lost\n";
        shell_exec('vagrant global-status | grep virtualbox | cut -c 1-9 | while read line; do echo $line; vagrant halt $line; done;');
        echo "\nCreating project $projectName\n";
        if (!file_exists($projectName)) {
            if (!mkdir($projectName) && !is_dir($projectName)) {
                throw new RuntimeException("Error creating project $projectName");
            }
            self::installBase($projectName, $gitSource);
            self::startVagrant($projectName);
            self::runComposer($projectName);
        } else {
            throw new LogicException("\nERROR: Project ' . $projectName . ' already exists!\n");
        }
    }

    /**
     * @param string $projectName
     * @param null|string $gitSource
     */
    private static function installBase($projectName, $gitSource = null)
    {
        $deleteGitDir = false;
        if ($gitSource === null) {
            echo "\nCreating Silverstripe Base in docroot\n";
            $gitSource = 'git@github.com:silverstripe/silverstripe-installer.git -b master';
            $deleteGitDir = true;
        } else {
            echo "\nCloning your base project in docroot\n";
        }
        echo "\nThis shouldn't take too long\n";
        shell_exec('cd ' . $projectName . ';git clone ' . $gitSource . ' docroot');
        copy(__DIR__ . '/docroot/_ss_environment.php', $projectName . '/docroot/_ss_environment.php');
        if ($deleteGitDir) {
            echo "\nNew empty project, Deleting git root from silverstripe-installer\n";
            shell_exec("rm -rf $projectName/docroot/.git");
            echo "\nInitialising empty repository in docroot\n";
            shell_exec("cd $projectName/docroot;git init");
        }
        echo "\nCreating SilverStripe Cache folder\n";
        if (!@mkdir("$projectName/docroot/silverstripe-cache") && !is_dir("$projectName/docroot/silverstripe-cache")) {
            echo "\nFailed creating silverstripe cache folder\n";
        }
        echo "\nSilverStripe base installation created\n";
    }


    /**
     * @param string $projectName
     */
    private static function startVagrant($projectName)
    {
        echo "\nBooting Vagrant machine, please have a bit of patience!\n";
        copy(__DIR__ . '/Vagrantfile', $projectName . '/Vagrantfile');
        shell_exec('cd ' . $projectName . ';vagrant up');
        echo "\nVagrant is running\n";
    }


    /**
     * @param string $projectName
     */
    private static function runComposer($projectName)
    {
        echo "\nRunning composer\n";
        copy(__DIR__ . '/docroot/composer.json', $projectName . '/docroot/composer.json');
        shell_exec('cd ' . $projectName . '/docroot;composer update');
        echo "\nSystem ready to run now, visit http://localhost:8080 to see your website\n\n";
    }

    /**
     * @param string $projectName
     */
    public static function tearDown($projectName)
    {
        echo "\nDestroying vagrant box in $projectName\nYou might be asked to confirm destruction of this box.\n";
        echo shell_exec('cd ' . $projectName . ';vagrant halt;vagrant destroy -f');
        echo "\nVagrantbox in $projectName destroyed\nYour project is still safe, don't worry\n\n";
    }

    /** Below are experimental methos to improve useability. Not yet live */
    /**
     * @param string $projectName
     */
    public static function halt($projectName)
    {
        echo "\nHalting $projectName's Vagrantmachine";
        shell_exec('cd ' . $projectName . ';vagrant halt');
        echo "\nMachine halted\n";
    }

    /**
     * @param string $projectName
     */
    public static function reload($projectName)
    {
        echo "\nHalting $projectName's Vagrantmachine";
        shell_exec('cd ' . $projectName . ';vagrant halt');
        echo "\nMachine halted\n";
        echo "\nStarting $projectName's Vagrantmachine";
        shell_exec('cd ' . $projectName . ';vagrant up');
        echo "\nMachine started\n";
    }
}