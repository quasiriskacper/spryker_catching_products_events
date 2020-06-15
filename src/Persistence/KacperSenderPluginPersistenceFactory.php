<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace KacperSenderPlugin\Persistence;

use Orm\Zed\ProductStorage\Persistence\SpyProductAbstractStorageQuery;
use Orm\Zed\ProductStorage\Persistence\SpyProductConcreteStorageQuery;
use Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory;
use Pyz\Zed\KacperSenderPlugin\KacperSenderPluginDependencyProvider;


/**
 * @method \Spryker\Zed\ProductStorage\ProductStorageConfig getConfig()
 * @method \Spryker\Zed\ProductStorage\Persistence\ProductStorageQueryContainerInterface getQueryContainer()
 */
class KacperSenderPluginPersistenceFactory extends AbstractPersistenceFactory
{
    /**
     * @return \Orm\Zed\ProductStorage\Persistence\SpyProductAbstractStorageQuery
     */
    public function createSpyProductAbstractStorageQuery()
    {
        return SpyProductAbstractStorageQuery::create();
    }

    /**
     * @return \Orm\Zed\ProductStorage\Persistence\SpyProductConcreteStorageQuery
     */
    public function createSpyProductConcreteStorageQuery()
    {
        return SpyProductConcreteStorageQuery::create();
    }

    /**
     * @return \Spryker\Zed\ProductStorage\Dependency\QueryContainer\ProductStorageToProductQueryContainerInterface
     */
    public function getProductQueryContainer()
    {
        return $this->getProvidedDependency(KacperSenderPluginDependencyProvider::QUERY_CONTAINER_PRODUCT);
    }
}