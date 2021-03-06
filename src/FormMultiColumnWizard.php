<?php

/**
 * This file is part of richardhj/contao-multicolumnwizard-frontend.
 *
 * Copyright (c) 2016-2017 Richard Henkenjohann
 *
 * @package   richardhj/contao-multicolumnwizard-frontend
 * @author    Richard Henkenjohann <richardhenkenjohann@googlemail.com>
 * @copyright 2016-2017 Richard Henkenjohann
 * @license   https://github.com/richardhj/contao-multicolumnwizard-frontend/blob/master/LICENSE LGPL-3.0
 */

namespace Richardhj\Contao;

use Contao\Controller;
use Contao\Environment;
use Contao\Widget;
use MultiColumnWizard;


/**
 * Class FormMultiColumnWizard
 *
 * @package Richardhj\Contao
 */
class FormMultiColumnWizard extends MultiColumnWizard
{

    /**
     * Template
     *
     * @var string
     */
    protected $strTemplate = 'form_mcw';


    /**
     * The CSS class prefix
     *
     * @var string
     */
    protected $strPrefix = 'widget widget-mcw';


    /** @noinspection PhpMissingParentConstructorInspection
     * Don't use parent's but parent parent's __construct
     *
     * @param array|null $arrAttributes
     */
    public function __construct($arrAttributes = null)
    {
        /** @noinspection PhpDynamicAsStaticMethodCallInspection */
        Widget::__construct($arrAttributes);
    }


    /**
     * Generate button string
     *
     * @param int $level
     *
     * @return string
     */
    protected function generateButtonString($level = 0)
    {
        $return = '';

        // Add buttons
        foreach ($this->arrButtons as $button => $image) {
            if (false === $image) {
                continue;
            }

            $return .= sprintf(
                '<a data-operations="%s" href="%s" class="widgetImage" title="%s">%s</a> ',
                $button,
                str_replace(
                    'index.php',
                    strtok(Environment::get('requestUri'), '?'),
                    Controller::addToUrl(
                        http_build_query(
                            [
                                $this->strCommand => $button,
                                'cid'             => $level,
                                'id'              => $this->currentRecord,
                            ]
                        ),
                        false
                    )
                ),
                $GLOBALS['TL_LANG']['MSC']['tw_r' . specialchars($button)],
                $this->getButtonContent($button) # We don't want to output an image and don't provide $image
            );
        }

        return $return;
    }


    /**
     * Get the content of the button, either text or image
     *
     * @param string $button The button name
     *
     * @return string
     */
    protected function getButtonContent($button)
    {
        return '<span class="button ' . $button . '"></span>';
    }

    /**
     * Disable the date picker because it is not designed for front end
     *
     * @param string $strId
     * @param string $strKey
     * @param string $rgxp
     *
     * @return string
     */
    protected function getMcWDatePickerString($strId, $strKey, $rgxp)
    {
        return '';
    }
}
