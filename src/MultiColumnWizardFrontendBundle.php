<?php

namespace Richardhj\MultiColumnWizardFrontendBundle;

use MenAtWork\MultiColumnWizardBundle\DependencyInjection\MultiColumnWizardExtension;
use Richardhj\MultiColumnWizardFrontendBundle\DependencyInjection\MultiColumnWizardFrontendExtension;
use Symfony\Component\Console\Application;
use Symfony\Component\HttpKernel\Bundle\Bundle;

/**
 * Class MultiColumnWizardBundle
 *
 * @package MenAtWork\MultiColumnWizardBundle
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
