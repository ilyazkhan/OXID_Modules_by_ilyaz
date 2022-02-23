<?php

namespace JidexStudio\JxAdvancedSelectionList\Application\Model;

use OxidEsales\Eshop\Core\Field;

/**
 * Class Article
 * @package JidexStudio\JxAdvancedSelectionList\Application\Model
 */
class Article extends Article_parent
{
    /**
     * @return string
     */
    public function getVariantsIconDirPath()
    {
        return SelectionListOption::ICONSDIR;
    }

    /**
     * @return array
     */
    public function getVariantsAdditionalData()
    {
        if ($this->isVariant()) {
            return [];
        }

        try {
            $additionalData = \json_decode($this->oxarticles__jxadvancedselectionlistvariantsdata->rawValue, true);
        } catch (\Exception $e) {
            $additionalData = [];
        }

        return $additionalData;
    }

    /**
     * @param array $additionalData
     * @return Field|void
     */
    public function setVariantsAdditionalData(array $additionalData)
    {
        if ($this->isVariant()) {
            return;
        }

        try {
            $additionalData = \json_encode($additionalData, JSON_UNESCAPED_UNICODE);
        } catch (\Exception $e) {
            $additionalData = '';
        }

        return $this->oxarticles__jxadvancedselectionlistvariantsdata = new Field($additionalData, Field::T_RAW);
    }
}
