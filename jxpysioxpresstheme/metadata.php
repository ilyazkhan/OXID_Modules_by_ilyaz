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
    'id' => 'jxpysioxpresstheme',
    'title' => 'Adavcned theme modifications for Category dropdown and Discount',
    'description' => [
        'de' => 'Stellen Sie zusätzliche Themenänderungen für Kategorieliste und Rabatt- / Flow- / Wave-Thema bereit',
        'en' => 'Provide additional theme modifications for Categorylist and discount / Flow / Wave Theme',
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
            'template' => 'layout/base.tpl',
            'block' => 'base_js',
            'file' => '/Application/views/blocks/base_js.tpl',
        ],
        [
            'template' => 'page/details/inc/productmain.tpl',
            'block' => 'details_productmain_price_value',
            'file' => '/Application/views/blocks/productmain_price_value.tpl',
        ],
        [
            'template' => 'page/details/inc/productmain.tpl',
            'block' => 'details_productmain_zoom',
            'file' => '/Application/views/blocks/productmain_zoom.tpl',
        ],
        [
            'template' => 'page/details/inc/productmain.tpl',
            'block' => 'details_productmain_stockstatus',
            'file' => '/Application/views/blocks/productmain_stockstatus.tpl',
        ],
        [
            'template' => 'page/details/inc/productmain.tpl',
            'block' => 'details_productmain_priceperunit',
            'file' => '/Application/views/blocks/productmain_priceperunit.tpl',
        ],
        [
            'template' => 'widget/product/listitem_grid.tpl',
            'block' => 'widget_product_listitem_infogrid_titlebox            ',
            'file' => '/Application/views/blocks/widget_product_listitem_infogrid_titlebox.tpl',
        ],
        [
            'template' => 'widget/product/listitem_infogrid.tpl',
            'block' => 'widget_product_listitem_infogrid_titlebox            ',
            'file' => '/Application/views/blocks/widget_product_listitem_infogrid_titlebox.tpl',
        ],
        [
            'template' => 'widget/product/listitem_grid.tpl',
            'block' => 'widget_product_listitem_infogrid_gridpicture            ',
            'file' => '/Application/views/blocks/widget_product_listitem_infogrid_gridpicture.tpl',
        ],
        [
            'template' => 'widget/product/listitem_infogrid.tpl',
            'block' => 'widget_product_listitem_infogrid_gridpicture',
            'file' => '/Application/views/blocks/widget_product_listitem_infogrid_gridpicture.tpl',
        ],
        
        [
            'template' => 'page/checkout/inc/basketcontents_table.tpl',
            'block' => 'checkout_basketcontents_basketitem_totalprice',
            'file' => '/Application/views/blocks/checkout_basketcontents_basketitem_totalprice.tpl',
        ],
		[
            'template' => 'page/checkout/inc/basketcontents_table.tpl',
            'block' => 'checkout_basketcontents_basketitem_quantity',
            'file' => '/Application/views/blocks/checkout_basketcontents_basketitem_quantity.tpl',
        ],
        [
            'template' => 'page/checkout/inc/basketcontents.tpl',
            'block' => 'checkout_basketcontents_summary',
            'file' => '/Application/views/blocks/checkout_basketcontents_summary.tpl',
        ],
        [
            'template' => 'widget/promoslider.tpl',
            'block' => 'dd_widget_promoslider',
            'file' => '/Application/views/blocks/promoslider.tpl',
        ],
        [
            'template' => 'article_extend.tpl',
            'block' => 'admin_article_extend_form',
            'file' => '/Application/views/admin/blocks/admin_article_extend_form.tpl',
        ],
        [
            'template' => 'article_stock.tpl',
            'block' => 'admin_article_stock_form',
            'file' => '/Application/views/admin/blocks/admin_article_stock_form.tpl',
        ],
    ],
    'extend' => [
        \OxidEsales\Eshop\Application\Component\Widget\ArticleDetails::class => \JidexStudio\JxPysioXpressTheme\Application\Component\Widget\ArticleDetails::class,
        \OxidEsales\Eshop\Application\Component\Widget\ArticleBox::class => \JidexStudio\JxPysioXpressTheme\Application\Component\Widget\ArticleBox::class,
        \OxidEsales\Eshop\Application\Model\Rating::class => \JidexStudio\JxPysioXpressTheme\Application\Model\Rating::class,
        \OxidEsales\Eshop\Application\Model\BasketItem::class => \JidexStudio\JxPysioXpressTheme\Application\Model\BasketItem::class,
        \OxidEsales\Eshop\Application\Model\Article::class => \JidexStudio\JxPysioXpressTheme\Application\Model\Article::class,
        \OxidEsales\Eshop\Application\Model\AmountPriceList::class => \JidexStudio\JxPysioXpressTheme\Application\Model\AmountPriceList::class,
        \OxidEsales\Eshop\Application\Controller\ArticleDetailsController::class => \JidexStudio\JxPysioXpressTheme\Application\Controller\ArticleDetailsController::class,
        \OxidEsales\Eshop\Application\Controller\Admin\ArticleList::class => \JidexStudio\JxPysioXpressTheme\Application\Controller\Admin\ArticleList::class
    ],
    'events'      => [
        'onActivate'   => '\JidexStudio\JxPysioXpressTheme\Core\Module::onActivate',
        'onDeactivate' => '\JidexStudio\JxPysioXpressTheme\Core\Module::onDeactivate',
    ],
    'settings' => [
        [
            'group'    => 'jxpysioxpresstheme_checkout',
            'name'     => 'jxpysioxpresstheme_checkout_freedelivery_price_total',
            'type'     => 'str',
            'value'    => '0',
            'position' => 0
        ],
    ],
];

