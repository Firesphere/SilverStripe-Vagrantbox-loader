<?php

/**
 * Created by IntelliJ IDEA.
 * User: simon
 * Date: 25/06/16
 * Time: 18:01
 */
class TwistedBoxHelp
{


    public static function help()
    {
        echo "TwistedBox. Create a new TwistedBytes based Vagrant box for development.\n\nUsage:\n";
        foreach(self::getActions() as $action => $info) {
            echo "php vagrantrunner.phar $action";
            if(!empty($info['unnamedArgs'])) {
                foreach($info['unnamedArgs'] as $arg) echo " ($arg)";
            }
            if(!empty($info['namedFlags'])) {
                foreach($info['namedFlags'] as $arg) echo " (--$arg)";
            }
            if(!empty($info['namedArgs'])) {
                foreach($info['namedArgs'] as $arg) echo " --$arg=\"$arg value\"";
            }
            echo "\n  {$info['description']}\n\n";
        }
    }
    
    public static function getActions()
    {
        return array(
            'help'    => array(
                'description' => 'Show this help message.',
                'method'      => 'help',
            ),
            'init'    => array(
                'description' => 'Start a new system with this projectname. Git source is optional. Without it, a bare SilverStripe installation will be created',
                'unnamedArgs' => array('projectname', 'Git source URL/SSH'),
            ),
            'destroy' => [
                'description' => "Destroy a project's Vagrant box",
                'unnamedArgs' => array('projectname'),
            ],
        );
    }
}