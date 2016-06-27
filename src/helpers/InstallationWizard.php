<?php

class InstallationWizard
{

    /**
     * @param array $args
     *
     * @return array
     * @throws LogicException
     */
    public function projectWizard($args)
    {
        $handle = fopen('php://stdin', 'r');
        $args[1] = 'init';
        // @todo This is for the future, to support box-selection
        echo "Please select your preferred box.\n";
        echo "1) Twisted Bytes\n"
            . "2) Better Brief\n";
//        . "3) SilverStripe Platform\n";
        $boxID = trim(fgets($handle));
        $args['box'] = $boxID;
        echo "Selected " . DataHelper::$boxes[(int)$boxID];
        echo "\nEnter your project name: ";
        $args[2] = trim(fgets($handle));
        echo "\nIs this a new project [Y/n]: ";
        $clean = trim(fgets($handle));
        if (strtolower($clean) !== 'y' && $clean !== '') {
            echo 'Please enter your git repository URL: ';
            $repositoryURL = fgets($handle);
            $repoTest = shell_exec('git ls-remote ' . $repositoryURL . '> /dev/null 2>&1');
            if ($repoTest === null) {
                throw new LogicException('Not a valid repository');
            }
            $repositoryType = 'an existing repository from ' . $repositoryURL;
            $args[3] = trim($repositoryURL);
        } else {
            echo "Fetching versions\n";
            $list = $this->listTags();
            echo 'Please select a version [1]: ';
            $versionChoice = trim(fgets($handle));
            $versionChoice = $versionChoice ?: 1;
            $args[4] = $list[$versionChoice];
            $repositoryType = 'a clean repository with SilverStripe ' . $args[4];
        }
        echo "\nCreating Vagrant box with " . $repositoryType;

        return $args;
    }

    /**
     * List the available tags for installation
     *
     * @return array
     */
    private function listTags()
    {
        $tags = shell_exec('git ls-remote --heads git@github.com:silverstripe/silverstripe-installer.git');
        $tags = array_reverse(explode("\n", $tags));
        $tags = array_splice($tags, 0, 10);
        $i = 1;
        foreach ($tags as $key => $tag) {
            list($sha, $tagName) = explode('refs/heads/', $tag);
            if (strlen($tagName) < 6 && $tagName != '') {
                $return[$i++] = $tagName;
            }
        }
        $return[$i] = 'master';
        foreach ($return as $key => $value) {
            echo $key . ') ' . $value . "\n";
        }

        return $return;
    }

}