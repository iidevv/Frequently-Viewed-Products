<?php

namespace Iidev\FrequentlyViewedProducts\View;

use QSL\ProductsCarousel\View\CarouselDataAttributesTrait;

use XCart\Extender\Mapping\ListChild;

/**
 * @ListChild (list="product.details.page", weight="30")
 * @Extender\Depend ({"CDev\ProductAdvisor","QSL\ProductsCarousel"})
 */
class FrequentlyViewedProducts extends \CDev\ProductAdvisor\View\ABought
{
    use CarouselDataAttributesTrait;

    protected function getBlockClasses()
    {
        $classes = parent::getBlockClasses();

        if ($this->isCarousel()) {
            $classes .= ' block-carousel-products frequently-viewed-products';
        }

        return $classes;
    }

    /**
     * @return string
     */
    protected function getBlockCode()
    {
        return "vb_carousel";
    }

    /**
     * Return search parameters.
     *
     * @return array
     */
    public static function getSearchParams()
    {
        return [
            \XLite\Model\Repo\Product::P_VIEWED_CATEGORY_PRODUCT_ID => self::PARAM_PRODUCT_ID,
        ];
    }

    /**
     * Returns CSS classes for the container element
     *
     * @return string
     */
    public function getListCSSClasses()
    {
        return parent::getListCSSClasses() . ' viewed-bought-products';
    }

    /**
     * Get title
     *
     * @return string
     */
    protected function getHead()
    {
        return static::t('Customers frequently viewed');
    }

    /**
     * Define widget parameters
     *
     * @return integer
     */
    protected function getMaxCount()
    {
        return (int) \XLite\Core\Config::getInstance()->CDev->ProductAdvisor->cvb_max_count_in_block;
    }

    /**
     * Returns true if block is enabled
     *
     * @return boolean
     */
    protected function isBlockEnabled()
    {
        return \XLite\Core\Config::getInstance()->Iidev->FrequentlyViewedProducts->is_active;
    }

    /**
     * @return \XLite\Core\CommonCell
     */
    protected function getLimitCondition()
    {
        $cnd = $this->getSearchCondition();
        if (!$this->getParam(\XLite\View\Pager\APager::PARAM_SHOW_ITEMS_PER_PAGE_SELECTOR)) {
            return $this->getPager()->getLimitCondition(
                0,
                $this->getParam(\XLite\View\Pager\APager::PARAM_MAX_ITEMS_COUNT),
                $cnd
            );
        }

        return $cnd;
    }

    /**
     * Return 'Order by' array.
     * array(<Field to order>, <Sort direction>)
     *
     * @return array|null
     */
    protected function getOrderBy()
    {
        return ['bp.count', static::SORT_ORDER_DESC];
    }
}
