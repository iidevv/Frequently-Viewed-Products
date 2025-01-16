<?php

namespace Iidev\FrequentlyViewedProducts\Model\Repo;

use XCart\Extender\Mapping\Extender;

/**
 * @Extender\Mixin
 */
class Product extends \XLite\Model\Repo\Product
{
    /**
     * Allowable search params
     */
    public const P_VIEWED_CATEGORY_PRODUCT_ID = 'viewedCategoryProductId';

    /**
     * Prepare certain search condition
     *
     * @param \Doctrine\ORM\QueryBuilder $queryBuilder Query builder to prepare
     * @param array                      $value        Condition data
     */
    protected function prepareCndViewedCategoryProductId(\Doctrine\ORM\QueryBuilder $queryBuilder, $value)
    {
        $queryBuilder->innerJoin('p.purchase_stats', 'bp');

        $queryBuilder->innerJoin('p.categoryProducts', 'cp')
            ->innerJoin('cp.category', 'c');

        if (is_array($value) && 1 < count($value)) {
            $queryBuilder->innerJoin(
                'bp.viewed_product',
                'vp',
                'WITH',
                'vp.product_id IN (' . implode(',', $value) . ')'
            );
        } else {
            $queryBuilder->innerJoin('bp.viewed_product', 'vp', 'WITH', 'vp.product_id = :productId')
                ->setParameter('productId', is_array($value) ? array_pop($value) : $value);
        }

        $categoryId = $this->getProductCategory($value);

        if ($categoryId) {
            $queryBuilder->andWhere('c.category_id = :categoryId')
                ->setParameter('categoryId', $categoryId);
        }
    }

    protected function getProductCategory($value)
    {
        $product = \XLite\Core\Database::getRepo('XLite\Model\Product')->find($value);

        $categories = $product->getCategories();

        if (!empty($categories)) {
            $category = $categories[0];

            return $category->getCategoryId();
        }

        return null;
    }
}
