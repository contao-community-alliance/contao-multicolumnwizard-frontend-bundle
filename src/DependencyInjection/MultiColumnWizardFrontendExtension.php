<?php

/**
 * This file is part of contao-community-alliance/contao-multicolumnwizard-frontend-bundle.
 *
 * (c) 2020 Contao Community Alliance.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * This project is provided in good faith and hope to be usable by anyone.
 *
 * @package    contao-community-alliance/contao-multicolumnwizard-frontend
 * @author     Richard Henkenjohann <richardhenkenjohann@googlemail.com>
 * @author     Stefan Heimes <heimes@men-at-work.de>
 * @author     Ingolf Steinhardt <info@e-spin.de>
 * @copyright  2020 Contao Community Alliance.
 * @license    https://github.com/contao-community-alliance/contao-multicolumnwizard-frontend-bundle/blob/master/LICENSE
 *             LGPL-3.0-or-later
 * @filesource
 */

namespace ContaoCommunityAlliance\MultiColumnWizardFrontendBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Contao\CoreBundle\DependencyInjection\Configuration;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\HttpKernel\DependencyInjection\ConfigurableExtension;

class MultiColumnWizardFrontendExtension extends ConfigurableExtension
{
    /**
     * The config files.
     *
     * @var array
     */
    private $files = [
        // 'listener.yml',
        // 'services.yml',
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
