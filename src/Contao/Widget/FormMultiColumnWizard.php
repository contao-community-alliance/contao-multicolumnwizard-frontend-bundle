<?php

/**
 * This file is part of richardhj/contao-multicolumnwizard-frontend.
 *
 * Copyright (c) 2016-2017 Richard Henkenjohann
 *
 * @package   richardhj/contao-multicolumnwizard-frontend
 * @author    Richard Henkenjohann <richardhenkenjohann@googlemail.com>
 * @author    Stefan Heimes <heimes@men-at-work.de>
 * @copyright 2016-2017 Richard Henkenjohann
 * @license   https://github.com/richardhj/contao-multicolumnwizard-frontend/blob/master/LICENSE LGPL-3.0
 */

namespace Richardhj\MultiColumnWizardFrontendBundle\Contao\Widget;

use Contao\Controller;
use Contao\Environment;
use Contao\Widget;
use Contao\CoreBundle\Exception\ResponseException;
use Symfony\Component\HttpFoundation\Response;


/**
 * Class FormMultiColumnWizard
 *
 * @package Richardhj\Contao
 */
class FormMultiColumnWizard extends \MenAtWork\MultiColumnWizardBundle\Contao\Widgets\MultiColumnWizard
{
    /**
     * Don't use parent's but parent parent's __construct
     *
     * @param array|null $arrAttributes
     *
     * @noinspection PhpMissingParentConstructorInspection
     */
    public function __construct($arrAttributes = null)
    {
        parent::__construct($arrAttributes);

        $this->strPrefix   = 'widget widget-mcw';
        $this->strTemplate = 'form_mcw';

        $GLOBALS['TL_JAVASCRIPT']['mcw_fe_js'] = 'bundles/multicolumnwizardfrontend/js/multicolumnwizard_fe_src.js';
    }

    /**
     * Convert a string to a response object
     * Copy from ajax.
     *
     * @param string $str The string to convert.
     *
     * @return Response
     */
    protected function convertToResponse($str)
    {
        return new Response(\Controller::replaceOldBePaths($str));
    }

    /**
     * @inheritdoc
     */
    public function generate($overwriteRowCurrentRow = null, $onlyRows = false)
    {
        // 'action=mcwCreateNewRow&name=' + fieldName + '&maxRowId=' + maxRowId;
        $action      = \Contao\Input::post('action');
        $name        = \Contao\Input::post('name');
        $maxRowCount = \Contao\Input::post('maxRowId');

        if ('mcwCreateNewRow' == $action && $name == $this->strName) {
            // Rewrite the values.
            $newRowCount = ($maxRowCount + 1);
            foreach ($this->columnFields as $strKey => $arrField) {
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
    protected function initializeWidget(&$arrField, $intRow, $strKey, $varValue)
    {
        // If null replace it with an empty string.
        if ($varValue == '' && $arrField['default'] == null) {
            $arrField['default'] = '';
        }

        return parent::initializeWidget($arrField, $intRow, $strKey, $varValue);
    }
}
