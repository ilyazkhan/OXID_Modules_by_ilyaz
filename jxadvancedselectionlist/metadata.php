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
    'id' => 'jxadvancedselectionlist',
    'title' => 'Advanced Selection List Module',
    'description' => [
        'de' => 'Stellen Sie zusätzliche Funktionen für die Auswahlliste bereit',
        'en' => 'Provide additional features for Selection List',
    ],
    'thumbnail' => 'logo_padermeditech.png',
    'version' => '1.0.0',
    'author' => 'Physio Express',
    'email' => 'ilyaskhan900@gmail.com',
    'url' => 'https://www.physioexpress.de',
    'blocks' => [
        [
            'template' => 'page/details/inc/productmain.tpl',
            'block' => 'details_productmain_selectlists',
            'file' => '/Application/views/blocks/details_productmain_selectlists.tpl',
        ],
        [
            'template' => 'page/details/inc/productmain.tpl',
            'block' => 'details_productmain_variantselections',
            'file' => '/Application/views/blocks/details_productmain_variantselections.tpl',
        ],
        [
            'template' => 'article_variant.tpl',
            'block' => 'admin_article_variant_extended',
            'file' => '/Application/views/admin/blocks/admin_article_variant_extended.tpl',
        ],
    ],
    'templates'   => [
        'jxadvancedselectionlist_options.tpl' => 'jidexstudio/jxadvancedselectionlist/Application/views/admin/tpl/selectionlist_options.tpl',
        'jxadvancedselectionlist_optionsimagesets.tpl' => 'jidexstudio/jxadvancedselectionlist/Application/views/admin/tpl/selectionlist_optionsimagesets.tpl',
        'jxadvancedselectionlist_hiddenoptions.tpl' => 'jidexstudio/jxadvancedselectionlist/Application/views/admin/tpl/selectionlist_hiddenoptions.tpl',
        'jxadvancedselectionlist_variantsdata.tpl' => 'jidexstudio/jxadvancedselectionlist/Application/views/admin/tpl/article_variantsdata.tpl',
    ],
    'extend' => [
        \OxidEsales\Eshop\Application\Model\SelectList::class => \JidexStudio\JxAdvancedSelectionList\Application\Model\SelectList::class,
        \OxidEsales\Eshop\Application\Model\Article::class => \JidexStudio\JxAdvancedSelectionList\Application\Model\Article::class,
        \OxidEsales\Eshop\Application\Controller\Admin\ArticleVariant::class => \JidexStudio\JxAdvancedSelectionList\Application\Controller\Admin\ArticleVariant::class,
        \OxidEsales\Eshop\Application\Controller\Admin\ArticleStock::class => \JidexStudio\JxAdvancedSelectionList\Application\Controller\Admin\ArticleStock::class
    ],
    'controllers' => [
        'jxadvancedselectionlist_options' => \JidexStudio\JxAdvancedSelectionList\Application\Controller\Admin\SelectListOptionsController::class,
        'jxadvancedselectionlist_optionsimagesets' => \JidexStudio\JxAdvancedSelectionList\Application\Controller\Admin\SelectListOptionsImageSetsController::class,
        'jxadvancedselectionlist_hiddenoptions' => \JidexStudio\JxAdvancedSelectionList\Application\Controller\Admin\SelectListHiddenOptionsController::class,
        'jxadvancedselectionlist_variantsdata' => \JidexStudio\JxAdvancedSelectionList\Application\Controller\Admin\VariantsDataController::class
    ],
    'events'      => [
        'onActivate'   => '\JidexStudio\JxAdvancedSelectionList\Core\Module::onActivate',
        'onDeactivate' => '\JidexStudio\JxAdvancedSelectionList\Core\Module::onDeactivate',
    ],
    'settings' => [
        [
            'group'    => 'jxadvancedselectionlist_main',
            'name'     => 'jxadvancedselectionlist_main_showexpandbutton',
            'type'     => 'bool',
            'value'    => true,
            'position' => 0
        ],
        [
            'group'    => 'jxadvancedselectionlist_main',
            'name'     => 'jxadvancedselectionlist_main_hidden_options_bilateral_relations',
            'type'     => 'bool',
            'value'    => false,
            'position' => 0
        ],
    ],
];
