<?php

namespace Scriptlodge\Promail\Api\Data;

/**
 * Interface PromailTrackingInterface
 *
 * @package Scriptlodge\Promail\Api\Data
 */
interface PromailTrackingInterface
{
    /**#@+
     * Constants defined for keys of data array
     */

    const ENTITY_ID = 'entity_id';
    const ORDER_ID = 'order_id';
    const TRACKING = 'tracking';
    const SHIPPING_METHOD = 'shipping_method';
    const TOTAL_AMOUNT = 'total_amount';
    const STORE_ID = 'store_id';
    const STATUS = 'status';
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    const TABLE_NAME='promail_order_tracking';

    /**#@-*/

    /**
     * Get id
     * @return int|null
     */
    public function getEntityId();

    /**
     * Set id
     * @param int $entityId
     * @return \Scriptlodge\Promail\Api\Data\PromailTrackingInterface
     */
    public function setEntityId($entityId);

    /**
     * @return int
     */
    public function getOrderId();

    /**
     * @param int $orderId
     *
     * @return \Scriptlodge\Promail\Api\Data\PromailTrackingInterface
     */
    public function setOrderId($orderId);

    /**
     * @return string
     */
    public function getShippingMethod();

    /**
     * @param string $shippingMethod
     *
     * @return $this
     */
    public function setShippingMethod($shippingMethod);

    /**
     * @return string
     */
    public function getTracking();

    /**
     * @param string $tracking
     * @return $this
     */
    public function setTracking($tracking);

    /**
     * @return string
     */
    public function getTotalAmount();

    /**
     * @param string $totalAmount
     * @return $this
     */
    public function setTotalAmount($totalAmount);

    /**
     * @return string
     */
    public function getStoreId();

    /**
     * @param string $storeId
     * @return $this
     */
    public function setStoreId($storeId);

    /**
     * @return string
     */
    public function getStatus();

    /**
     * @param string $status
     * @return $this
     */
    public function setStatus($status);

    /**
     * @return string
     */
    public function getCreatedAt();

    /**
     * @param string $createdAt
     *
     * @return \Scriptlodge\Promail\Api\Data\PromailTrackingInterface
     */
    public function setCreatedAt($createdAt);

    /**
     * @return string
     */
    public function getUpdatedAt();

    /**
     * @param string $updatedAt
     *
     * @return \Scriptlodge\Promail\Api\Data\PromailTrackingInterface
     */
    public function setUpdatedAt($updatedAt);

}
