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

    protected function parse(array $parameters, $resource)
    {
        if (!isset($parameters['imports'])) {
            return $parameters;
        }

        $imports = (array) $parameters['imports'];
        $inherited = array();

        unset($parameters['imports']);

        foreach ($imports as $import) {
            $this->setCurrentDir(dirname($import));

            $inherited = array_replace($inherited, $this->import($import, null, false, $resource));
        }

        return array_replace($inherited, $parameters);
    }
}
