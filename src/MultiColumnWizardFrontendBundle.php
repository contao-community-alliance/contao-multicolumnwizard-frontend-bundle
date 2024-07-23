<?php

/**
 * This file is part of contao-community-alliance/contao-multicolumnwizard-frontend-bundle.
 *
 * (c) 2020-2024 Contao Community Alliance.
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
 * @copyright  2020-2024 Contao Community Alliance.
 * @license    https://github.com/contao-community-alliance/contao-multicolumnwizard-frontend-bundle/blob/master/LICENSE
 *             LGPL-3.0-or-later
 * @filesource
 */

namespace ContaoCommunityAlliance\MultiColumnWizardFrontendBundle;

use ContaoCommunityAlliance\MultiColumnWizardFrontendBundle\DependencyInjection\MultiColumnWizardFrontendExtension;
use Symfony\Component\Console\Application;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class MultiColumnWizardFrontendBundle extends Bundle
{
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
    public function registerCommands(Application $application): void
    {
        // Disable automatic command registration.
    }
}
