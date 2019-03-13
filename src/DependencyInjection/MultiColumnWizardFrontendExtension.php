<?php

/**
 * This file is part of richardhj/contao-multicolumnwizard-frontend.
 *
 * Copyright (c) 2016-2017 Richard Henkenjohann
 *
 * @package   richardhj/contao-multicolumnwizard-frontend
 * @author    Stefan Heimes <heimes@men-at-work.de>
 * @copyright 2016-2017 Richard Henkenjohann
 * @license   https://github.com/richardhj/contao-multicolumnwizard-frontend/blob/master/LICENSE LGPL-3.0
 */

namespace Richardhj\MultiColumnWizardFrontendBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Contao\CoreBundle\DependencyInjection\Configuration;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\HttpKernel\DependencyInjection\ConfigurableExtension;

/**
 * Class MultiColumnWizardFrontendExtension
 */
class MultiColumnWizardFrontendExtension extends ConfigurableExtension
{
    /**
     * The config files.
     *
     * @var array
     */
    private $files = [
//        'listener.yml',
//        'services.yml',
    ];

    /**
     * {@inheritdoc}
     */
    public function getAlias()
    {
        return 'multicolumnwizard-frontend-bundle';
    }

    /**
     * {@inheritdoc}
     */
    public function getConfiguration(array $config, ContainerBuilder $container)
    {
        // Add the resource to the container
        parent::getConfiguration($config, $container);

        return new Configuration(
            $container->getParameter('kernel.debug'),
            $container->getParameter('kernel.project_dir'),
            $container->getParameter('kernel.root_dir'),
            $container->getParameter('kernel.default_locale')
        );
    }

    /**
     * {@inheritdoc}
     */
    protected function loadInternal(array $mergedConfig, ContainerBuilder $container)
    {
        $loader = new YamlFileLoader(
            $container,
            new FileLocator(__DIR__ . '/../Resources/config')
        );

        foreach ($this->files as $file) {
            $loader->load($file);
        }
    }
}
