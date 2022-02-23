<?php

namespace JidexStudio\JxAdvancedSelectionList\Application\Model;

use OxidEsales\Eshop\Core\DatabaseProvider;
use OxidEsales\Eshop\Core\Registry;

/**
 * Class DynamicImageGenerator
 * @package JidexStudio\JxAdvancedSelectionList\Application\Model
 */
class DynamicImageGenerator extends \OxidEsales\Eshop\Core\DynamicImageGenerator
{
    protected $targetWidth;
    protected $targetHeight;
    protected $targetQuality;

    /**
     * @param $imageSource
     * @param $imageTarget
     * @param $targetWidth
     * @param $targetHeight
     * @param $targetQuality
     * @return bool|string
     */
    public function generateImageWithSizes($imageSource, $imageTarget, $targetWidth, $targetHeight, $targetQuality)
    {
        $this->targetWidth = $targetWidth;
        $this->targetHeight = $targetHeight;
        $this->targetQuality = $targetQuality;

        $imageSource = $this->_getShopBasePath() . str_replace('/', DIRECTORY_SEPARATOR, $imageSource);
        $imageTarget = $this->_getShopBasePath() . str_replace('/', DIRECTORY_SEPARATOR, $imageTarget);

        return $this->_generateImage($imageSource, $imageTarget);
    }

    /**
     * @return array
     */
    protected function _getImageInfo()
    {
        return [$this->targetWidth, $this->targetHeight, $this->targetQuality];
    }

    /**
     * @param string $path
     * @return bool
     */
    protected function _isTargetPathValid($path)
    {
        if (!is_dir(dirname($path))) {
            mkdir(dirname($path), 0744, true);
        }

        return true;
    }
}
