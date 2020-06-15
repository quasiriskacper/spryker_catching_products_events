<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace KacperSenderPlugin\Communication\Plugins\Event\Listener;

use Orm\Zed\ProductBundle\Persistence\Map\SpyProductBundleTableMap;
use Orm\Zed\Category\Persistence\Map\SpyCategoryAttributeTableMap;
use Spryker\Zed\Event\Dependency\Plugin\EventHandlerInterface;
use Spryker\Zed\Event\Dependency\Plugin\EventBulkHandlerInterface;
use Generated\Shared\Transfer\ProductAbstractTransfer;

use Spryker\Shared\Kernel\Transfer\TransferInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use KacperSenderPlugin\KacperSenderPluginDependencyProvider;
use KacperSenderPlugin\Communication\Controllers\SenderController;
use KacperSenderPlugin\Communication\KacperSenderPluginCommunicationFactory;
use Spryker\Zed\PropelOrm\Business\Transaction\DatabaseTransactionHandlerTrait;
use Spryker\Zed\Product\Business\ProductFacade;

/**
 * @method \Spryker\Zed\ProductRelation\Business\ProductRelationFacade getFacade()
 * @method \Spryker\Zed\ProductRelation\Communication\ProductRelationCommunicationFactory getFactory()
 */
class KacperProductCategoryListener extends AbstractPlugin implements EventBulkHandlerInterface
{
    use DatabaseTransactionHandlerTrait;
    private $sender;
    private $URL_TO_API;

    public function __construct($URL_TO_API) {
        $this->sender = new SenderController();
        $this->URL_TO_API = $URL_TO_API;
    }

    /**
     * @param \Spryker\Shared\Kernel\Transfer\TransferInterface|\Generated\Shared\Transfer\ProductAbstractTransfer $eventTransfer
     *
     * @return void
     */
    public function handleBulk(array $eventTransfers, $eventName)
    {
        $productInfo = [];

        $categoryIds = $this->getFactory()->getEventBehaviorFacade()->getEventTransferIds($eventTransfers);
        $categoryNodeIds = $this->getQueryContainer()->queryCategoryNodeIdsByCategoryIds($categoryIds)->find()->getData();
        $categoryNodeIds = $this->getQueryContainer()->queryCategoryNodeByIds($categoryNodeIds)->find()->getData();

        $data = array(
            'date' => date('d.m.y G:i:s'),
            'listenerName' => 'KacperProductCategoryListener',
            'eventName' =>  $eventName,
            'eventTransfers' => $categoryNodeIds,
        );
        $this->sender->getDataFromApi($data, $this->URL_TO_API);
    }

}
