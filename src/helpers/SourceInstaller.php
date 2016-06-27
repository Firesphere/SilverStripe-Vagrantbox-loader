<?php

/**
 * Created by IntelliJ IDEA.
 * User: simon
 * Date: 27/06/16
 * Time: 18:22
 */
class SourceInstaller
{

    /**
     * @param string $projectName
     * @param null|string $gitSource
     * @param null|string $version
     */
    public static function installBase($projectName, $boxType = 1, $gitSource = null, $version = null)
    {
        $boxLocation = DataHelper::$baseLocations[$boxType];
        if($version === null && $gitSource !== null) {
            echo "\nNo version constraint detected, falling back to the latest stable version\n";
            $version = '3.4.0';
        }
        if ($gitSource === null) {
            echo "\nCreating Silverstripe Base in docroot";
            $gitSource = 'git@github.com:silverstripe/silverstripe-installer.git -b ' . $version;
        }
        echo "\nThis shouldn't take too long\n";
        shell_exec('cd ' . $projectName . ';git clone ' . $gitSource . ' ' . $boxLocation);
        if($boxType === 1) {
            copy(__DIR__ . '/../resources/_ss_environment.php', $projectName . '/'.$boxLocation.'/_ss_environment.php');
        }
        echo "\nCreating SilverStripe Cache folder\n";
        if (!@mkdir("$projectName/docroot/silverstripe-cache") && !is_dir("$projectName/$boxLocation/silverstripe-cache")) {
            echo 'Failed creating silverstripe cache folder';
        }
        if ($gitSource === null) {
            echo "SilverStripe base installation created\nBe aware the git repository is still pointing to the SilverStripe Installer!";
        }
        else {
            echo 'Your repository has been installed and is ready for use.';
        }
    }


    /**
     * @param string $projectName
     * @param $boxType
     */
    public static function runComposer($projectName, $boxType)
    {
        // Using prefer-dist because it's usually faster.
        shell_exec('cd ' . $projectName . '/'.DataHelper::$baseLocations[$boxType].';composer install --prefer-dist');
        echo "\nSystem ready to run now, visit http://localhost:8080 to see your website\n\n";
    }

}