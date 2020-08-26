<?php

namespace Scriptlodge\Promail\Observer;

use Magento\Framework\Event\ObserverInterface;

/**
 * Scriptlodge Promail SalesOrderSaveCommitAfterObserver Observer Model.
 */
class SalesOrderSaveCommitAfterObserver implements ObserverInterface
{
    /**
     * @param \Magento\Framework\Event\Observer $observer
     */
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        /** @var $orderInstance Order */
        print_r(get_class_methods($observer));
        $order = $observer->getOrder();
        $lastOrderId = $observer->getOrder()->getId();

        print_r($lastOrderId);

        exit('Sales Order');
        return;
    }

}
