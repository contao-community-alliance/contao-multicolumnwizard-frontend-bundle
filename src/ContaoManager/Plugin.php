<?php

namespace Richardhj\MultiColumnWizardFrontendBundle\ContaoManager;

use Contao\CoreBundle\ContaoCoreBundle;
use Contao\ManagerBundle\ContaoManagerBundle;
use Contao\ManagerPlugin\Bundle\BundlePluginInterface;
use Contao\ManagerPlugin\Bundle\Config\BundleConfig;
use Contao\ManagerPlugin\Bundle\Parser\ParserInterface;
use MenAtWork\MultiColumnWizardBundle\MultiColumnWizardBundle;
use Richardhj\MultiColumnWizardFrontendBundle\MultiColumnWizardFrontendBundle;

/**
 * Class Plugin
 */
class Plugin implements BundlePluginInterface
{
    /**
     * {@inheritdoc}
     */
    public function getBundles(ParserInterface $parser)
    {
        return [
            BundleConfig::create(MultiColumnWizardFrontendBundle::class)
                ->setLoadAfter(
                    [
                        ContaoCoreBundle::class,
                        ContaoManagerBundle::class,
                        MultiColumnWizardBundle::class,
                    ]
                ),
        ];
    }
}
