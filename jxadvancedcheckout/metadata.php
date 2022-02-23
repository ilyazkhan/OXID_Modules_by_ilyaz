<?php
/**
 * Metadata file for module
 *
 * @file          metadata.php
 * @link          https://www.physioexpress.de
 * @copyright (C) Physio Express
 * @package       modules
 * @addtogroup    modules
 */
/**
 * Metadata version
 */
$sMetadataVersion = '2.0';

/**
 * Module information
 */
$aModule = [
    'id' => 'jxadvancedcheckout',
    'title' => 'Advanced Checkout',
    'description' => [
        'de' => '',
        'en' => '',
    ],
    'thumbnail' => 'logo_padermeditech.png',
    'version' => '1.0.0',
    'author' => 'Physio Express',
    'email' => 'ilyaskhan900@gmail.com',
    'url' => 'https://www.physioexpress.de',
    'blocks' => [
        [
            'template' => 'layout/base.tpl',
            'block' => 'base_style',
            'file' => '/Application/views/blocks/base_style.tpl',
        ],
        [
            'template' => 'widget/minibasket/minibasket.tpl',
            'block' => 'widget_minibasket',
            'file' => '/Application/views/blocks/widget_minibasket.tpl',
        ],
        [
            'template' => 'widget/product/listitem_grid.tpl',
            'block' => 'widget_product_listitem_grid_price            ',
            'file' => '/Application/views/blocks/widget_product_listitem_grid_price.tpl',
        ],
        [
            'template' => 'page/checkout/basket.tpl',
            'block' => 'checkout_basket_next_step_top',
            'file' => '/Application/views/blocks/checkout_basket_next_step_top.tpl',
        ],
    ],
    'extend' => [
        \OxidEsales\Eshop\Application\Controller\BasketController::class => \JidexStudio\JxAdvancedCheckout\Application\Controller\BasketController::class,
        \OxidEsales\Eshop\Application\Model\BasketItem::class => \JidexStudio\JxAdvancedCheckout\Application\Model\BasketItem::class,
        \OxidEsales\Eshop\Application\Model\Basket::class => \JidexStudio\JxAdvancedCheckout\Application\Model\Basket::class,
    ],
    'events'      => [
        'onActivate'   => '\JidexStudio\JxAdvancedCheckout\Core\Module::onActivate',
        'onDeactivate' => '\JidexStudio\JxAdvancedCheckout\Core\Module::onDeactivate',
    ],
];
