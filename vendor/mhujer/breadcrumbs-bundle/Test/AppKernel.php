<?php
namespace WhiteOctober\BreadcrumbsBundle\Test;

use Symfony\Bundle\FrameworkBundle\FrameworkBundle;
use Symfony\Bundle\TwigBundle\TwigBundle;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Kernel;
use WhiteOctober\BreadcrumbsBundle\WhiteOctoberBreadcrumbsBundle;

/**
 * Class AppKernel
 * It is needed to simulate an application to make some functional tests
 */
class AppKernel extends Kernel
{
    /**
     * @var string[]
     */
    private $bundlesToRegister = [];

    /**
     * @var array
     */
    private $configFiles = [];

    /**
     * @var string
     */
    private $cachePrefix = '';

    public function __construct($cachePrefix)
    {
        parent::__construct($cachePrefix, true);
        $this->cachePrefix = $cachePrefix;
        $this->addBundle(FrameworkBundle::class);
        $this->addBundle(TwigBundle::class);
        $this->addBundle(WhiteOctoberBreadcrumbsBundle::class);
        $this->addConfigFile(__DIR__.'/config.xml');
    }

    public function addBundle($bundleClassName)
    {
        $this->bundlesToRegister[] = $bundleClassName;
    }

    public function registerBundles(): iterable
    {
        $this->bundlesToRegister = array_unique($this->bundlesToRegister);
        $bundles = [];
        foreach ($this->bundlesToRegister as $bundle) {
            $bundles[] = new $bundle();
        }

        return $bundles;
    }
    /**
     * {@inheritdoc}
     */
    public function registerContainerConfiguration(LoaderInterface $loader)
    {
        $loader->load(function (ContainerBuilder $container) use ($loader) {
            $this->configFiles = array_unique($this->configFiles);
            foreach ($this->configFiles as $path) {
                $loader->load($path);
            }

            $container->addObjectResource($this);
        });
    }
    /**
     * @param string $configFile path to config file
     */
    public function addConfigFile($configFile)
    {
        $this->configFiles[] = $configFile;
    }
}
