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
     * @param null|string $version
     * @throws RuntimeException
     * @throws LogicException
     */
    public function setUp($projectName, $gitSource = null, $version = null)
    {

        echo "\nCreating project $projectName\n";
        if (!file_exists($projectName)) {
            if (!mkdir($projectName) && !is_dir($projectName)) {
                throw new RuntimeException("Error creating project $projectName\n");
            }
            $this->installBase($projectName, $gitSource, $version);
            VagrantWorker::startVagrant($projectName);
            $this->runComposer($projectName);
        } else {
            throw new LogicException("ERROR: Project ' . $projectName . ' already exists!\n");
        }
    }

    /**
     * @param string $projectName
     * @param null|string $gitSource
     * @param null|string $version
     */
    private function installBase($projectName, $gitSource = null, $version = null)
    {
        if($version === null && $gitSource !== null) {
            echo "\nNo version constraint detected, falling back to the latest stable version\n";
            $version = '3.4.0';
        }
        if ($gitSource === null) {
            echo "\nCreating Silverstripe Base in docroot";
            $gitSource = 'git@github.com:silverstripe/silverstripe-installer.git -b ' . $version;
        }
        echo "\nThis shouldn't take too long\n";
        shell_exec('cd ' . $projectName . ';git clone ' . $gitSource . ' docroot');
        copy(__DIR__ . '/../resources/_ss_environment.php', $projectName . '/docroot/_ss_environment.php');
        echo "\nCreating SilverStripe Cache folder\n";
        if (!@mkdir("$projectName/docroot/silverstripe-cache") && !is_dir("$projectName/docroot/silverstripe-cache")) {
            echo "Failed creating silverstripe cache folder";
        }
        echo "SilverStripe base installation created\nBe aware the git repository is still pointing to the SilverStripe Installer!";
    }


    /**
     * @param string $projectName
     */
    private function runComposer($projectName)
    {
        shell_exec('cd ' . $projectName . '/docroot;composer install');
        echo "\nSystem ready to run now, visit http://localhost:8080 to see your website\n\n";
    }

}