<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace KacperSenderPlugin\Communication\Plugins\Event\Listener;

use Orm\Zed\ProductBundle\Persistence\Map\SpyProductBundleTableMap;
use Spryker\Zed\Event\Dependency\Plugin\EventHandlerInterface;
use Spryker\Zed\Event\Dependency\Plugin\EventBulkHandlerInterface;
use Generated\Shared\Transfer\ProductAbstractTransfer;

use Spryker\Shared\Kernel\Transfer\TransferInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use KacperSenderPlugin\KacperSenderPluginDependencyProvider;
use KacperSenderPlugin\Communication\Controllers\SenderController;
use KacperSenderPlugin\Communication\Controllers\MixedController;
use KacperSenderPlugin\Business\KacperSenderPluginFacede;

use Spryker\Zed\PropelOrm\Business\Transaction\DatabaseTransactionHandlerTrait;


/**
 * @method \Spryker\Zed\ProductRelation\Business\ProductRelationFacade getFacade()
 * @method \Spryker\Zed\ProductRelation\Communication\ProductRelationCommunicationFactory getFactory()
 */
class KacperListener extends AbstractPlugin implements EventBulkHandlerInterface {
    use DatabaseTransactionHandlerTrait;
    private $sender;
    private $mixed;
    private $URL_TO_API;

    public function __construct($URL_TO_API) {
        $this->sender = new SenderController();
        $this->mixed = new MixedController();
        $this->URL_TO_API = $URL_TO_API;
    }

    /**
     * @param \Spryker\Shared\Kernel\Transfer\TransferInterface|\Generated\Shared\Transfer\ProductAbstractTransfer $eventTransfer
     *
     * @return void
     */
    public function handleBulk(array $eventTransfers, $eventName): void
    {   
        $productInfo = [];
        $concrete = [];

        foreach ($eventTransfers as $eventTransfer) {
            $productInfo[] = $eventTransfer->toArray();
        }

        $id = $this->mixed->getCorrectAbstractConcreteProductIds($productInfo, true)['id'];
       
        foreach($id as $i) {
            $abstract = $this->getFactory()->getProductFacede()->findProductAbstractById($i)->toArray();
            $getConcreteProductsByAbstractProductId = $this->getFactory()->getProductFacede()->getConcreteProductsByAbstractProductId($i);
                    
            foreach ($getConcreteProductsByAbstractProductId as $eventTransfer) {
                if($eventTransfer->toArray() !== null && $eventTransfer->toArray() !== '') {
                    $concrete[] = $eventTransfer->toArray();
                }
            }
            $locale = $this->getFactory()->getLocaleFacede()->getCurrentLocale();
            $categories = $this->mixed->getProductCategories($i, $locale);
    
            $data = $this->mixed->createArrayToSend(
                'KacperListener',
                $eventName,
                $abstract,
                $concrete,
                $categories,
                $i
            );
            $this->sender->getDataFromApi($data, $this->URL_TO_API);
        }        
    }
}
