<?php

namespace Scriptlodge\Promail\Model;

use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Api\DataObjectHelper;
use Magento\Framework\Exception\NoSuchEntityException;
use Scriptlodge\Promail\Api\PromailTrackingRepositoryInterface;
use Scriptlodge\Promail\Api\Data\PromailTrackingInterface;
use Scriptlodge\Promail\Api\Data\PromailTrackingInterfaceFactory;
use Scriptlodge\Promail\Model\PromailTrackingFactory as PromailTrackingFactory;
use Scriptlodge\Promail\Model\ResourceModel\PromailTracking as ResourcePromailTracking;
use Scriptlodge\Promail\Model\ResourceModel\PromailTracking\CollectionFactory as CollectionFactory;


class PromailTrackingRepository implements PromailTrackingRepositoryInterface
{

    /**
     * @var PromailTrackingInterface[]
     */
    private $instances = [];

    /**
     * @var \Scriptlodge\Promail\Model\ResourceModel\PromailTracking
     */
    protected $resourcePromailTracking;


    /**
     * @var \Scriptlodge\Promail\Model\PromailTrackingFactory
     */
    protected $promailTrackingFactory;

    /**
     * @var DataObjectHelper
     */
    protected $dataObjectHelper;


    /**
     * PromailTrackingRepository constructor.
     * @param ResourcePromailTracking $resourcePromailTracking
     * @param PromailTrackingInterfaceFactory $promailTrackingInterfaceFactory
     * @param CollectionFactory $collectionFactory
     * @param DataObjectHelper $dataObjectHelper
     * @param PromailTrackingFactory $promailTrackingFactory
     * @param \Magento\Framework\Api\SearchResultsInterfaceFactory $searchResultsFactory
     * @param \Magento\Framework\Api\ExtensionAttribute\JoinProcessorInterface $extensionAttributesJoinProcessor
     * @param \Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface $collectionProcessor
     */


    public function __construct(
        ResourcePromailTracking $resourcePromailTracking,
        PromailTrackingInterfaceFactory $promailTrackingInterfaceFactory,
        CollectionFactory $collectionFactory,
        DataObjectHelper $dataObjectHelper,
        PromailTrackingFactory $promailTrackingFactory,
        \Magento\Framework\Api\SearchResultsInterfaceFactory $searchResultsFactory,
        \Magento\Framework\Api\ExtensionAttribute\JoinProcessorInterface $extensionAttributesJoinProcessor,
        \Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface $collectionProcessor
    )
    {
        $this->resourcePromailTracking = $resourcePromailTracking;
        $this->promailTrackingInterfaceFactory = $promailTrackingInterfaceFactory;
        $this->collectionFactory = $collectionFactory;
        $this->promailTrackingFactory = $promailTrackingFactory;
        $this->dataObjectHelper = $dataObjectHelper;
        $this->searchResultsFactory = $searchResultsFactory;
        $this->extensionAttributesJoinProcessor = $extensionAttributesJoinProcessor;
        $this->collectionProcessor = $collectionProcessor;
    }


    /**
     * {@inheritdoc}
     */
    public function save(\Scriptlodge\Promail\Api\Data\PromailTrackingInterface $promailTracking)
    {
        try {
            $this->resourcePromailTracking->save($promailTracking);
            return $promailTracking;
        } catch (\Exception $exception) {
            throw new CouldNotSaveException(__(
                'Could not save the Promail Tracking: %1',
                $exception->getMessage()
            ));
        }
    }

    /**
     * {@inheritdoc}
     */
    public function update(\Scriptlodge\Promail\Api\Data\PromailTrackingInterface $promailTracking)
    {
        try {

            $this->resourcePromailTracking->save($promailTracking);
            return $promailTracking;
        } catch (\Exception $exception) {
            throw new CouldNotSaveException(__(
                'Could not save the Promail Tracking: %1',
                $exception->getMessage()
            ));
        }
    }

    /**
     * {@inheritdoc}
     */
    public function get($promailTrackingId)
    {
        if (!isset($this->instances[$promailTrackingId])) {
            $promailTracking = $this->promailTrackingFactory->create();
            $promailTracking->load($promailTrackingId);
            if (!$promailTracking->getId()) {
                throw NoSuchEntityException::singleField('id', $promailTrackingId);
            }
            $this->instances[$promailTrackingId] = $promailTracking;
        }
        return $this->instances[$promailTrackingId];
    }

    /**
     * {@inheritdoc}
     */
    public function getByOrderId($orderId)
    {
        /** @var SearchResultsInterface $searchResults */
        $searchResults = $this->searchResultsFactory->create();

        $collection = $this->collectionFactory->create();
        $collection->addFieldToFilter(PromailTrackingInterface::ORDER_ID, $orderId);
        print_r(count($collection->getItems()));
        if (empty($collection->getItems())) {
            return $searchResults;
          //  throw new NoSuchEntityException(__('Promail Tracking Items with Order ID "%1" does not exist.', $orderId));
        }

        $searchResults->setItems($collection->getItems());
        return $searchResults;
    }


    /**
     * Delete PromailTracking
     * @param \Scriptlodge\Promail\Api\Data\PromailTrackingInterface $promailTracking
     * @return bool true on success
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function delete(\Scriptlodge\Promail\Api\Data\PromailTrackingInterface $promailTracking)
    {
        try {
            $promailTrackingId = $promailTracking->getId();
            $this->resourcePromailTracking->delete($promailTracking);
        } catch (\Exception $exception) {
            throw new CouldNotDeleteException(__(
                'Could not delete the %2 Promail Tracking: %1',
                $exception->getMessage(), $promailTracking->getId()
            ));
        }
        unset($this->instances[$promailTracking->getId()]);
        return true;
    }

    /**
     * Delete PromailTracking by ID
     * @param string $promailTrackingId
     * @return bool true on success
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function deleteById($promailTrackingId)
    {
        return $this->delete($this->get($promailTrackingId));
    }


    /**
     * {@inheritdoc}
     */
    public function getList(\Magento\Framework\Api\SearchCriteriaInterface $searchCriteria)
    {
        /** @var SearchResultsInterface $searchResults */
        $searchResults = $this->searchResultsFactory->create();
        $searchResults->setSearchCriteria($searchCriteria);

        /** @var Collection $collection */
        $collection = $this->collectionFactory->create();
        //   $this->extensionAttributesJoinProcessor->process($collection);

        $this->collectionProcessor->process($searchCriteria, $collection);

        $searchResults->setTotalCount($collection->getSize());
        $searchResults->setItems($collection->getItems());

        return $searchResults;
    }


}
