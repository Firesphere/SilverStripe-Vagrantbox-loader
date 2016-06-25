<?php

/**
 * Class TwistedBoxInstaller
 * 
 * Base install system to create a new TwistedBytes box system.
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
        echo "\nCreating project $projectName\n";
        if (!file_exists($projectName)) {
            if(!mkdir($projectName) && !is_dir($projectName)) {
                throw new RuntimeException("\nError creating project $projectName");
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
            $gitSource = 'git@github.com:silverstripe/silverstripe-installer.git -b 3.4';
            $deleteGitDir = true;
        } else {
            echo "\nCloning your base project in docroot\nRemove the .git directory before running git init\n";
        }
        echo "\nThis shouldn't take too long\n";
        shell_exec('cd ' . $projectName . ';git clone ' . $gitSource . ' docroot');
        copy(__DIR__ . '/docroot/_ss_environment.php', $projectName . '/docroot/_ss_environment.php');
        if($deleteGitDir) {
            echo "\nNew empty project, Deleting git root from silverstripe-installer\n";
            shell_exec("rm -r $projectName/docroot/.git");
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
        echo shell_exec('cd ' . $projectName . ';vagrant up');
        echo "\nVagrant is running\n";
    }


    /**
     * @param string $projectName
     */
    private static function runComposer($projectName)
    {
        echo "\nRunning composer\n";
        echo shell_exec('cd ' . $projectName . '/docroot;composer update');
        echo "\nSystem ready to run now, visit http://localhost:8080 to see your website\n\n";
    }

    /**
     * @param string $projectName
     */
    public static function tearDown($projectName)
    {
        echo "\nDestroying vagrant box in $projectName\n";
        echo shell_exec('cd ' . $projectName . ';vagrant halt;vagrant destroy');
        echo "\nVagrantbox in $projectName destroyed\nYour project is still safe, don't worry\n\n";
    }
}