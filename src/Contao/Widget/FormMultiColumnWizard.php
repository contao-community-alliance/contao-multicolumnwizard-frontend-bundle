<?php

/**
 * This file is part of contao-community-alliance/contao-multicolumnwizard-frontend-bundle.
 *
 * (c) 2020-2023 Contao Community Alliance.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * This project is provided in good faith and hope to be usable by anyone.
 *
 * @package    contao-community-alliance/contao-multicolumnwizard-frontend
 * @author     Richard Henkenjohann <richardhenkenjohann@googlemail.com>
 * @author     Stefan Heimes <stefan_heimes@hotmail.com>
 * @author     Ingolf Steinhardt <info@e-spin.de>
 * @copyright  2020-2023 Contao Community Alliance.
 * @license    https://github.com/contao-community-alliance/contao-multicolumnwizard-frontend-bundle/blob/master/LICENSE
 *             LGPL-3.0-or-later
 * @filesource
 */

namespace ContaoCommunityAlliance\MultiColumnWizardFrontendBundle\Contao\Widget;

use Contao\Controller;
use Contao\CoreBundle\Exception\ResponseException;
use Contao\Input;
use Contao\Widget;
use MenAtWork\MultiColumnWizardBundle\Contao\Widgets\MultiColumnWizard;
use Symfony\Component\HttpFoundation\Response;

class FormMultiColumnWizard extends MultiColumnWizard
{
    /**
     * The html id of the element.
     *
     * @var int
     */
    protected $strId = 0;

    /**
     * The field name in the form.
     *
     * @var string
     */
    protected $strName = '';

    /**
     * The label.
     *
     * @var string
     */
    protected $strLabel = '';

    /**
     * The input callback.
     *
     * @var callable
     */
    protected $inputCallback;

    /**
     * The CSS class.
     *
     * @var string
     */
    protected $strClass = '';

    /**
     * The wizard name.
     *
     * @var string
     */
    protected $strWizard = '';

    /**
     * The Data container
     *
     * @var object
     */
    protected $objDca;

    /**
     * The parent name.
     *
     * @var string
     */
    protected $strParent = '';

    /**
     * The default name.
     *
     * @var string
     */
    protected $strDefault = '';

    /**
     * The list fo fields.
     *
     * @var array
     */
    protected $columnFields = [];

    /**
     * Don't use parent's but parent parent's __construct
     *
     * @param array $arrAttributes The attributes for the widget.
     *
     * @noinspection PhpMissingParentConstructorInspection
     * @SuppressWarnings(PHPMD.Superglobals)
     */
    public function __construct($arrAttributes = [])
    {
        parent::__construct($arrAttributes);

        $this->strPrefix   = 'widget widget-mcw';
        $this->strTemplate = 'form_mcw';

        $GLOBALS['TL_BODY']['mcw_sortable_js'] =
            '<script type="text/javascript" src="bundles/multicolumnwizardfrontend/js/Sortable.min.js"></script>';
        $GLOBALS['TL_BODY']['mcw_fe_js']       =
            '<script type="text/javascript"
                     src="bundles/multicolumnwizardfrontend/js/multicolumnwizard_fe.min.js"></script>';
    }

    /**
     * Convert a string to a response object
     * Copy from ajax.
     *
     * @param string $str The string to convert.
     *
     * @return Response
     */
    protected function convertToResponse(string $str): Response
    {
        return new Response(Controller::replaceOldBePaths($str));
    }

    /**
     * @inheritdoc
     *
     * @SuppressWarnings(PHPMD.LongVariable)
     */
    public function generate($overwriteRowCurrentRow = null, $onlyRows = false): string
    {
        // 'action=mcwCreateNewRow&name=' + fieldName + '&maxRowId=' + maxRowId;
        $action      = Input::post('action');
        $name        = Input::post('name');
        $maxRowCount = Input::post('maxRowId');

        if ('mcwCreateNewRow' === $action && $name === $this->strName) {
            // Rewrite the values.
            $newRowCount = ($maxRowCount + 1);
            foreach (\array_keys($this->columnFields) as $strKey) {
                $this->varValue[$newRowCount][$strKey] = '';
            }

            // Generate the data.
            $result = parent::generate($newRowCount, true);
            throw new ResponseException($this->convertToResponse($result));
        } else {
            return parent::generate($overwriteRowCurrentRow, $onlyRows);
        }
    }

    /**
     * @inheritdoc
     */
    protected function initializeWidget(&$arrField, $intRow, $strKey, $varValue): ?Widget
    {
        // If null, replace it with an empty string.
        if ($varValue === '' && isset($arrField['default']) && $arrField['default'] === null) {
            $arrField['default'] = '';
        }

        return parent::initializeWidget($arrField, $intRow, $strKey, $varValue);
    }

    /**
     * @inheritDoc
     *
     * @throws \JsonException
     */
    protected function generateScriptBlock($strId, $maxCount, $minCount): string
    {
        $script = <<<SCRIPT

<script>
window.addEventListener('DOMContentLoaded', function(e){

    new MultiColmTableName({
        selector: "ctrl_" + %s,
        maxRow: %s,
        minRow: %s
    });
});
</script>
SCRIPT;
        return sprintf(
            $script,
            \json_encode($strId, JSON_THROW_ON_ERROR),
            $maxCount,
            $minCount
        );
    }
}
