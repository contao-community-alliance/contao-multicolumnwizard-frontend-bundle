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

namespace Richardhj\MultiColumnWizardFrontendBundle;

use MenAtWork\MultiColumnWizardBundle\DependencyInjection\MultiColumnWizardExtension;
use Richardhj\MultiColumnWizardFrontendBundle\DependencyInjection\MultiColumnWizardFrontendExtension;
use Symfony\Component\Console\Application;
use Symfony\Component\HttpKernel\Bundle\Bundle;

/**
 * Class MultiColumnWizardBundle
 */
class MultiColumnWizardFrontendBundle extends Bundle
{
    const SCOPE_BACKEND = 'backend';
    const SCOPE_FRONTEND = 'frontend';

    /**
     * {@inheritdoc}
     */
    public function getContainerExtension()
    {
        return new MultiColumnWizardFrontendExtension();
    }

    /**
     * {@inheritdoc}
     */
    public function registerCommands(Application $application)
    {
        // disable automatic command registration
    }
}
