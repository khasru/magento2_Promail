<?php

namespace Scriptlodge\Promail\Api\Data;

use Magento\Framework\Api\SearchResultsInterface;

interface PromailTrackingResultsInterface extends SearchResultsInterface
{

    /**
     * Get PromailTracking list.
     * @return \Scriptlodge\Promail\Api\Data\PromailTrackingInterface[]
     */
    public function getItems();

    /**
     * Set PromailTracking list.
     * @param \Scriptlodge\Promail\Api\Data\PromailTrackingInterface[] $items
     * @return $this
     */
    public function setItems(array $items);
}
