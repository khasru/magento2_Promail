<?php

namespace Scriptlodge\Promail\Model\ResourceModel;

use Scriptlodge\Promail\Api\Data\PromailTrackingInterface;

/**
 * PromailTracking resource
 */
class PromailTracking extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{

    /**
     * Date model
     *
     * @var \Magento\Framework\Stdlib\DateTime\DateTime
     */
    protected $_date;

    /**
     * PromailTracking Table
     * @var string
     */
    private $promailTrackingTable = PromailTrackingInterface::TABLE_NAME;

    /**
     * @param \Magento\Framework\Stdlib\DateTime\DateTime $date
     * @param \Magento\Framework\Model\ResourceModel\Db\Context $context
     */
    public function __construct(
        \Magento\Framework\Model\ResourceModel\Db\Context $context,
        \Magento\Framework\Stdlib\DateTime\DateTime $date
    )
    {
        $this->_date = $date;
        parent::__construct($context);
    }

    /**
     * Initialize resource
     *
     * @return void
     */
    public function _construct()
    {
        $this->_init($this->promailTrackingTable, PromailTrackingInterface::ENTITY_ID);
    }
}
