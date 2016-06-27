<?php

/**
 * Class TwistedBoxInstaller
 *
 * Base install system to create a new TwistedBytes Vagrantbox.
 *
 * @author Simon `Sphere` Erkelens
 */
class BetterBriefBoxInstaller
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
            echo "WARNING: BetterBrief box needs configuration. It will boot with default configuration.\n";
            echo "WARNING: The default config might not suit you, you might need to update and reprovision\n";
            echo "WARNING: BetterBrief Box takes a long time to boot the first time!\n";
            VagrantWorker::startVagrant($projectName, 2);
            SourceInstaller::installBase($projectName, $gitSource, $version);
            SourceInstaller::runComposer($projectName, 2);
        } else {
            throw new LogicException("ERROR: Project ' . $projectName . ' already exists!\n");
        }
    }

}