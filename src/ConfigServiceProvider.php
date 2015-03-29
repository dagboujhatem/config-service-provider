<?php

namespace Dafiti\Silex;

use Dafiti\Silex\Loader\YamlFileLoader;
use Tacker\Loader\CacheLoader;
use Tacker\Loader\JsonFileLoader;
use Tacker\Loader\IniFileLoader;
use Tacker\Loader\NormalizerLoader;
use Tacker\Loader\PhpFileLoader;
use Tacker\Normalizer\ChainNormalizer;
use Tacker\Normalizer\EnvironmentNormalizer;
use Tacker\Normalizer\PimpleNormalizer;
use Tacker\ResourceCollection;
use Silex\Application;
use Silex\ServiceProviderInterface;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\Config\Loader\DelegatingLoader;
use Symfony\Component\Config\Loader\LoaderResolver;

class ConfigServiceProvider implements ServiceProviderInterface
{
    /**
     * @var string
     */
    private $prefix = 'config';

    public function register(Application $app)
    {
        $cacheKey = sprintf('%s.cache_dir', $this->prefix);
        $loaderKey = sprintf('%s.loader', $this->prefix);
        $locatorKey = sprintf('%s.locator', $this->prefix);
        $pathsKey = sprintf('%s.paths', $this->prefix);
        $resolverKey = sprintf('%s.resolver', $this->prefix);
        $resourcesKey = sprintf('%s.resources', $this->prefix);

        if (!isset($app[$pathsKey])) {
            $app[$pathsKey] = [];
        }

        if (!isset($app[$cacheKey])) {
            $app[$cacheKey] = null;
        }

        $app[$locatorKey] = $app->share(
            function (Application $app) use ($pathsKey) {
                return new FileLocator($app[$pathsKey]);
            }
        );

        $app[$resourcesKey] = $app->share(
            function () {
                return new ResourceCollection();
            }
        );

        $app[$resolverKey] = $app->share(
            function (Application $app) use ($locatorKey, $resourcesKey) {
                return new LoaderResolver([
                    new JsonFileLoader($app[$locatorKey], $app[$resourcesKey]),
                    new IniFileLoader($app[$locatorKey], $app[$resourcesKey]),
                    new PhpFileLoader($app[$locatorKey], $app[$resourcesKey]),
                    new YamlFileLoader($app[$locatorKey], $app[$resourcesKey]),
                ]);
            }
        );

        $app[$loaderKey] = $app->share(
            function (Application $app) use ($cacheKey, $resolverKey, $resourcesKey) {
                $normalizer = new ChainNormalizer();
                $normalizer->add(new PimpleNormalizer($app));
                $normalizer->add(new EnvironmentNormalizer());

                $loader = new CacheLoader(
                    new NormalizerLoader(
                        new DelegatingLoader($app[$resolverKey]),
                        $normalizer
                    ),
                    $app[$resourcesKey]
                );

                $loader->setDebug($app['debug']);
                $loader->setCacheDir($app[$cacheKey]);

                return $loader;
            }
        );

        $app[$this->prefix] = $app->share(
            function (Application $app) use ($loaderKey) {
                $configurator = new Configurator($app[$loaderKey]);

                return $configurator;
            }
        );
    }

    public function boot(Application $app)
    {
    }
}
