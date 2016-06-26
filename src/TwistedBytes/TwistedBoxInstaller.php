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

    public function projectWizard($args)
    {
        $handle = fopen("php://stdin", "r");
        $args[1] = 'init';
        // @todo This is for the future, to support box-selection
//    echo "Please select your preferred box.\n";
//    echo "1) Twisted Bytes\n"
//        . "2) Better Brief\n"
//        . "3) SilverStripe Platform\n";
//    $boxID = fgets($handle);
//    echo "Selected " . $boxes[(int)$boxID];
        echo "\nEnter your project name: ";
        $args[2] = trim(fgets($handle));
        echo "\nIs this a clean project [Y/n]: ";
        $clean = fgets($handle);
        if (strtolower(trim($clean)) === 'y') {
            $repositoryType = "a clean repository";
        } else {
            echo "Please enter your git repository URL: ";
            $repositoryURL = fgets($handle);
            $repoTest = shell_exec('git ls-remote ' . $repositoryURL . '> /dev/null 2>&1');
            if ($repoTest === null) {
                throw new LogicException("Not a valid repository");
            }
            $repositoryType = "an existing repository from " . $repositoryURL;
            $args[3] = trim($repositoryURL);
        }
        echo "\nCreating Vagrant box with " . $repositoryType;

        return $args;
    }

    /**
     * @param string $projectName
     * @param null|string $gitSource
     */
    public function setUp($projectName, $gitSource = null)
    {

        echo "\nCreating project $projectName\n";
        if (!file_exists($projectName)) {
            if (!mkdir($projectName) && !is_dir($projectName)) {
                throw new RuntimeException("Error creating project $projectName\n");
            }
            $this->installBase($projectName, $gitSource);
            VagrantWorker::startVagrant($projectName);
            $this->runComposer($projectName);
        } else {
            throw new LogicException("ERROR: Project ' . $projectName . ' already exists!\n");
        }
    }

    /**
     * @param string $projectName
     * @param null|string $gitSource
     */
    private function installBase($projectName, $gitSource = null)
    {
        $deleteGitDir = false;
        if ($gitSource === null) {
            echo "\nCreating Silverstripe Base in docroot";
            $gitSource = 'git@github.com:silverstripe/silverstripe-installer.git -b master';
            $deleteGitDir = true;
        } else {
            echo "\nCloning your base project in docroot\n";
        }
        echo "\nThis shouldn't take too long\n";
        shell_exec('cd ' . $projectName . ';git clone ' . $gitSource . ' docroot');
        copy(__DIR__ . '/resources/_ss_environment.php', $projectName . '/docroot/_ss_environment.php');
        if ($deleteGitDir) {
            echo "\nNew empty project, Deleting git root from silverstripe-installer";
            shell_exec("rm -rf $projectName/docroot/.git");
            echo "\nInitialising empty repository in docroot";
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
        copy(__DIR__ . '/resources/composer.json', $projectName . '/docroot/composer.json');
        shell_exec('cd ' . $projectName . '/docroot;composer update');
        echo "\nSystem ready to run now, visit http://localhost:8080 to see your website\n\n";
    }

}