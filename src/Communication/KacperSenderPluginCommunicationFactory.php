<?php

namespace KacperSenderPlugin\Communication;

use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use KacperSenderPlugin\KacperSenderPluginDependencyProvider;


class KacperSenderPluginCommunicationFactory extends AbstractCommunicationFactory
{
    public function getProductFacede() {
        return $this->getProvidedDependency(KacperSenderPluginDependencyProvider::FACADE_PRODUCT);
    }

    public function getProductCategoryFacede() {
        return $this->getProvidedDependency(KacperSenderPluginDependencyProvider::FACADE_PRODUCT_CATEGORY);
    }

    public function getLocaleFacede() {
        return $this->getProvidedDependency(KacperSenderPluginDependencyProvider::FACADE_LOCALE);
    }

    public function getEventBehaviorFacade()
    {
        return $this->getProvidedDependency(KacperSenderPluginDependencyProvider::FACADE_EVENT_BEHAVIOR);
    }
}
