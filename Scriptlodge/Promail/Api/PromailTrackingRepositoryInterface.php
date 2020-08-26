<?php

namespace Scriptlodge\Promail\Api;

use Magento\Framework\Exception\LocalizedException;

use Scriptlodge\Promail\Api\Data\PromailTrackingInterface;

/**
 * Interface PromailTrackingRepositoryInterface'
 *
 * @package Scriptlodge\Promail\Api
 */
interface PromailTrackingRepositoryInterface
{

    /**
     * Save PromailTrackingInterface
     * @param PromailTrackingInterface $promailTracking
     * @return PromailTrackingInterface
     * @throws LocalizedException
     */
    public function save(\Scriptlodge\Promail\Api\Data\PromailTrackingInterface $promailTracking);

    /**
     * Save PromailTrackingRepositoryInterface
     * @param PromailTrackingInterface $promailTracking
     * @return PromailTrackingInterface
     * @throws LocalizedException
     */
    public function update(\Scriptlodge\Promail\Api\Data\PromailTrackingInterface $promailTracking);

    /**
     * Retrieve $promailTracking data
     * @param int $promailTrackingId
     * @return PromailTrackingInterface
     * @throws LocalizedException
     */
    public function get($promailTrackingId);

    /**
     * Retrieve $promailTracking data
     * @param int $orderId
     * @return PromailTrackingInterface
     * @throws LocalizedException
     */
    public function getByOrderId($orderId);


    /**
     * Retrieve $promailTracking matching the specified criteria.
     * @param \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
     * @return \Scriptlodge\Promail\Api\Data\PromailTrackingSearchResultsInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getList(\Magento\Framework\Api\SearchCriteriaInterface $searchCriteria);


    /**
     * Delete PromailTracking Data
     * @param PromailTrackingInterface $promailTracking
     * @return bool true on success
     * @throws LocalizedException
     */
    public function delete(PromailTrackingInterface $promailTracking);

    /**
     * Delete PromailTracking data by Id
     * @param string $promailTrackingId
     * @return bool true on success
     * @throws NoSuchEntityException
     * @throws LocalizedException
     */
    public function deleteById($promailTrackingId);

}
