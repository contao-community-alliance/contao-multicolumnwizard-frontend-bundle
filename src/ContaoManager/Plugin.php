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
