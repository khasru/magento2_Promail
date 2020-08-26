<?php
/**
 *
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Scriptlodge\Promail\Model;

use Scriptlodge\Promail\Api\Data\PromailTrackingInterface;

/**
 * Class PromailTracking
 *
 * @codeCoverageIgnore
 */
class PromailTracking extends \Magento\Framework\Model\AbstractModel implements
    PromailTrackingInterface
{

    /**
     * @return void
     */
    protected function _construct()
    {
        $this->_init(\Scriptlodge\Promail\Model\ResourceModel\PromailTracking::class);
    }


    /**
     * @inheritDoc
     */
    public function getEntityId()
    {
        return $this->_getData(PromailTrackingInterface::ENTITY_ID);
    }

    /**
     * @inheritDoc
     */
    public function setEntityId($entityId)
    {
        $this->setData(PromailTrackingInterface::ENTITY_ID, $entityId);

        return $this;
    }


    /**
     * @return mixed
     */
    public function getOrderId()
    {
        return $this->_getData(PromailTrackingInterface::ORDER_ID);
    }

    /**
     * @param int $orderId
     * @return mixed
     */
    public function setOrderId($orderId)
    {
        $this->setData(PromailTrackingInterface::ORDER_ID, $orderId);

        return $this;
    }

    /**
     * @return mixed
     */
    public function getShippingMethod()
    {
        return $this->_getData(PromailTrackingInterface::SHIPPING_METHOD);
    }

    /**
     * @param string $shippingMethod
     * @return mixed
     */
    public function setShippingMethod($shippingMethod)
    {
        $this->setData(PromailTrackingInterface::SHIPPING_METHOD, $shippingMethod);

        return $this;
    }

    /**
     * @return mixed
     */
    public function getTracking()
    {
        return $this->_getData(PromailTrackingInterface::TRACKING);
    }

    /**
     * @param string $tracking
     * @return mixed
     */
    public function setTracking($tracking)
    {
        $this->setData(PromailTrackingInterface::TRACKING, $tracking);

        return $this;
    }

    /**
     * @return mixed
     */
    public function getTotalAmount()
    {
        return $this->_getData(PromailTrackingInterface::TOTAL_AMOUNT);
    }

    /**
     * @param string $totalAmount
     * @return mixed
     */
    public function setTotalAmount($totalAmount)
    {
        $this->setData(PromailTrackingInterface::TOTAL_AMOUNT, $totalAmount);

        return $this;
    }

    /**
     * @return mixed
     */
    public function getStatus()
    {
        return $this->_getData(PromailTrackingInterface::STATUS);
    }

    /**
     * @param string $status
     * @return mixed
     */
    public function setStatus($status)
    {
        $this->setData(PromailTrackingInterface::STATUS, $status);

        return $this;
    }

    /**
     * @return mixed
     */
    public function getCreatedAt()
    {
        return $this->_getData(PromailTrackingInterface::CREATED_AT);
    }

    /**
     * @param string $createdAt
     * @return mixed
     */
    public function setCreatedAt($createdAt)
    {
        $this->setData(PromailTrackingInterface::CREATED_AT, $createdAt);

        return $this;
    }

    /**
     * @return mixed
     */
    public function getUpdatedAt()
    {
        return $this->_getData(PromailTrackingInterface::UPDATED_AT);
    }

    /**
     * @param string $updatedAt
     * @return mixed
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->setData(PromailTrackingInterface::UPDATED_AT, $updatedAt);

        return $this;
    }

    /**
     * @return mixed
     */
    public function getStoreId()
    {
        return $this->_getData(PromailTrackingInterface::STORE_ID);
    }

    /**
     * @param string $storeId
     * @return mixed
     */
    public function setStoreId($storeId)
    {
        $this->setData(PromailTrackingInterface::STORE_ID, $storeId);

        return $this;
    }
}
