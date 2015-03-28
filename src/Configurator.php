<?php

namespace Dafiti\Silex;

use Pimple;
use Symfony\Component\Config\Loader\LoaderInterface;

class Configurator extends Pimple
{
    /**
     * @Symfony\Component\Config\Loader\LoaderInterface;
     */
    private $loader;

    public function __construct(LoaderInterface $loader)
    {
        $this->loader = $loader;
    }

    public function load($resource)
    {
        $params = $this->loader->load($resource);

        foreach ($params as $key => $value) {
            $this->offsetSet($key, $value);
        }
    }
}
