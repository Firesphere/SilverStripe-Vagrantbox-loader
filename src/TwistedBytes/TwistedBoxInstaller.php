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
            SourceInstaller::installBase($projectName, 1, $gitSource, $version);
            VagrantWorker::startVagrant($projectName, 1);
            SourceInstaller::runComposer($projectName, 1);
        } else {
            throw new LogicException("ERROR: Project ' . $projectName . ' already exists!\n");
        }
    }
}