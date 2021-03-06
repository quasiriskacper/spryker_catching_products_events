<?php

namespace KacperSenderPlugin\Communication\Table;

use Orm\Zed\Product\Persistence\Map\SpyProductTableMap;
use Orm\Zed\Product\Persistence\SpyProductQuery;
use Spryker\Zed\Gui\Communication\Table\AbstractTable;
use Spryker\Zed\Gui\Communication\Table\TableConfiguration;

class ProductTable extends AbstractTable
{
    /**
     * @var \Orm\Zed\Product\Persistence\SpyProductQuery
     */
    protected $spyProductPropelQuery;

    /**
     * ProductTable constructor.
     *
     * @param \Orm\Zed\Product\Persistence\SpyProductQuery $spyProductPropelQuery
     */
    public function __construct(SpyProductQuery $spyProductPropelQuery)
    {
        $this->spyProductPropelQuery = $spyProductPropelQuery;
    }

    /**
     * @param TableConfiguration $config
     *
     * @return TableConfiguration
     */
    protected function configure(TableConfiguration $config): TableConfiguration
    {
        $config->setHeader([
            SpyProductTableMap::COL_ID_PRODUCT => 'Product ID',
            SpyProductTableMap::COL_SKU => 'Product Sku',
        ]);

        return $config;
    }

    /**
     * @param TableConfiguration $config
     *
     * @return array
     */
    protected function prepareData(TableConfiguration $config): array
    {
        $queryResult = $this->runQuery($this->spyProductPropelQuery, $config);

        $results = [];
        foreach ($queryResult as $resultItem) {
            $results[] = [
                SpyProductTableMap::COL_ID_PRODUCT => $resultItem[SpyProductTableMap::COL_ID_PRODUCT],
                SpyProductTableMap::COL_SKU => $resultItem[SpyProductTableMap::COL_SKU],
            ];
        }

        return $results;
    }
}
