<?php

namespace Dafiti\Silex\Loader;

use Symfony\Component\Yaml\Yaml;

class YamlFileLoader extends \Tacker\Loader\YamlFileLoader
{
    /**
     * @param  $resource
     *
     * @return array
     */
    protected function read($resource)
    {
        return Yaml::parse(file_get_contents($resource));
    }
}
