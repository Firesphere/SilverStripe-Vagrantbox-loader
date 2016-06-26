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
        $deleteGitDir = false;
        if($version === null && $gitSource !== null) {
            echo "\nNo version constraint found, falling back to the latest stable version\n";
            $version = '3.4.0';
        }
        if ($gitSource === null) {
            echo "\nCreating Silverstripe Base in docroot";
            $gitSource = 'git@github.com:silverstripe/silverstripe-installer.git -b ' . $version;
            $deleteGitDir = true;
        } else {
            echo "\nCloning your base project in docroot\n";
        }
        echo "\nThis shouldn't take too long\n";
        shell_exec('cd ' . $projectName . ';git clone ' . $gitSource . ' docroot');
        copy(__DIR__ . '/resources/_ss_environment.php', $projectName . '/docroot/_ss_environment.php');
        if ($deleteGitDir) {
            echo "\nNew empty project, Deleting git root from silverstripe-installer";
            shell_exec("cd $projectName/docroot;git init");
        }
        echo "\nCreating SilverStripe Cache folder\n";
        if (!@mkdir("$projectName/docroot/silverstripe-cache") && !is_dir("$projectName/docroot/silverstripe-cache")) {
            echo "\nFailed creating silverstripe cache folder";
        }
        echo "\nSilverStripe base installation created\n";
    }


    /**
     * @param string $projectName
     */
    private function runComposer($projectName)
    {
        echo "\nRunning composer";
        //copy(__DIR__ . '/../resources/composer.json', $projectName . '/docroot/composer.json');
        shell_exec('cd ' . $projectName . '/docroot;composer update');
        echo "\nSystem ready to run now, visit http://localhost:8080 to see your website\n\n";
    }

}