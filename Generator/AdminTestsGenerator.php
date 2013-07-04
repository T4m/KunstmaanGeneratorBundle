<?php

namespace Kunstmaan\GeneratorBundle\Generator;

use Kunstmaan\GeneratorBundle\Helper\GeneratorUtils;
use Sensio\Bundle\GeneratorBundle\Generator\Generator;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\DependencyInjection\Container;

/**
 * Generates tests to test the admin backend generated by the default-site generator
 */
class AdminTestsGenerator extends  Generator
{

    /**
     * @var Filesystem
     */
    private $filesystem;

    /**
     * @var string
     */
    private $skeletonDir;

    private $fullSkeletonDir;

    /**
     * @param Filesystem $filesystem  The filesytem
     * @param string     $skeletonDir The skeleton directory
     */
    public function __construct(Filesystem $filesystem, $skeletonDir)
    {
        $this->filesystem = $filesystem;
        $this->skeletonDir = $skeletonDir;
        $this->fullSkeletonDir = GeneratorUtils::getFullSkeletonPath($skeletonDir);
    }

    /**
     * @param Bundle $bundle  The bundle
     * @param string $rootDir The root directory
     */
    public function generate(Bundle $bundle, OutputInterface $output)
    {
        $parameters = array(
            'namespace'         => $bundle->getNamespace(),
            'bundle'            => $bundle
        );

        $this->generateBehatTests($bundle, $output, $parameters);
        $this->generateUnitTests($bundle, $output);
    }

    /**
     * @param Bundle          $bundle     The bundle
     * @param array           $parameters The template parameters
     * @param OutputInterface $output
     */
    public function generateUnitTests(Bundle $bundle, OutputInterface $output)
    {
        $dirPath = $bundle->getPath();
        $fullSkeletonDir = $this->skeletonDir . '/Tests';

        $output->writeln('Generating Unit Tests : <info>OK</info>');
    }

    /**
     * @param Bundle          $bundle
     * @param OutputInterface $output
     */
    public function generateBehatTests(Bundle $bundle, OutputInterface $output, array $parameters)
    {
        $dirPath = sprintf("%s/Features", $bundle->getPath());
        $skeletonDir = sprintf("%s/Features", $this->fullSkeletonDir);


        $this->filesystem->copy($skeletonDir . '/AdminLoginLogout.feature', $dirPath . '/Features/AdminLoginLogout.feature', true);
        $this->filesystem->copy($skeletonDir . '/AdminSettingsGroup.feature', $dirPath . '/Features/AdminSettingsGroup.feature', true);
        $this->filesystem->copy($skeletonDir . '/AdminSettingsRole.feature', $dirPath . '/Features/AdminSettingsRole.feature', true);
        $this->filesystem->copy($skeletonDir . '/AdminSettingsUser.feature', $dirPath . '/Features/AdminSettingsUser.feature', true);
        $this->renderFile('/admintests/Features/Context/FeatureContext.php', $dirPath . '/Features/Context/FeatureContext.php', $parameters);
        $this->renderFile('/admintests/Features/Context/GroupContext.php', $dirPath . '/Features/Context/GroupContext.php', $parameters);
        $this->renderFile('/admintests/Features/Context/RoleContext.php', $dirPath . '/Features/Context/RoleContext.php', $parameters);
        $this->renderFile('/admintests/Features/Context/UserContext.php', $dirPath . '/Features/Context/UserContext.php', $parameters);

        $output->writeln('Generating Behat Tests : <info>OK</info>');
    }
}
