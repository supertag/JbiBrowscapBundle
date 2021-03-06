<?php

/*
 * Copyright 2012 Jonathan Ingram <jonathan.b.ingram@gmail.com>
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

namespace Jbi\BrowscapBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

/**
 * @author Jonathan Ingram <jonathan.b.ingram@gmail.com>
 */
class JbiBrowscapExtension extends Extension
{
    /**
     * {@inheritDoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new Loader\XmlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.xml');

        $cacheDir = $container->getParameterBag()->resolveValue($config['cache_dir']);

        if (!is_dir($cacheDir) && false === @mkdir($cacheDir, 0777, true)) {
            throw new \RuntimeException(sprintf('Could not create cache directory "%s".', $cacheDir));
        }

        $container->setParameter('jbi_browscap.cache_dir', $cacheDir);
        
        $container->setParameter('jbi_browscap.remote_ini_url', isset($config['remote_ini_url'])?$config['remote_ini_url']:null);
        $container->setParameter('jbi_browscap.remote_ver_url', isset($config['remote_ver_url'])?$config['remote_ver_url']:null);
        $container->setParameter('jbi_browscap.do_auto_update', isset($config['do_auto_update'])?$config['do_auto_update']:null);
    }
}
