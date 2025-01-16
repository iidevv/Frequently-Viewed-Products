<?php

namespace Iidev\FrequentlyViewedProducts\View;

use XCart\Extender\Mapping\Extender;

/**
 * @Extender\Mixin
 */
abstract class Details extends \XLite\View\Product\Details\Customer\Page\APage
{
    /**
     * Register CSS files
     *
     * @return array
     */
    public function getCSSFiles()
    {
        $list   = parent::getCSSFiles();
        $list[] = 'modules/Iidev/FrequentlyViewedProducts/style.less';

        return $list;
    }
}
