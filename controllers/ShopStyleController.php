<?php
/**
 * ShopStyle plugin for Craft CMS
 *
 * ShopStyle Controller
 *
 * @author    Superbig
 * @copyright Copyright (c) 2017 Superbig
 * @link      https://superbig.co
 * @package   ShopStyle
 * @since     1.0.0
 */

namespace Craft;

class ShopStyleController extends BaseController
{

    /**
     * @var    bool|array Allows anonymous access to this controller's actions.
     * @access protected
     */
    protected $allowAnonymous = array(
        'actionIndex',
    );

    /**
     */
    public function actionIndex ()
    {
        craft()->config->maxPowerCaptain();

        $results = craft()->shopStyle->getBatchResults([
            'filters'       => craft()->request->getParam('filter'),
            'category'      => craft()->request->getParam('category'),
            'sort'          => craft()->request->getParam('sort'),
            'priceDropDate' => craft()->request->getParam('priceDropDate'),
        ]);

        $this->returnJson([ 'products' => $results, 'total' => count($results) ]);
    }
}