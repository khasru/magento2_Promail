<?php

namespace Scriptlodge\Promail\Cron;

use Magento\Sales\Api\OrderRepositoryInterface;

class SyncOrders
{

    protected $_logger;
    protected $orderRepository;
    protected $proceesOrder;


    public function __construct(
        \Psr\Log\LoggerInterface $logger,
        OrderRepositoryInterface $orderRepository,
        \Scriptlodge\Promail\Model\ProceesOrder $proceesOrder

    )
    {
        $this->_logger = $logger;
        $this->_orderRepository = $orderRepository;
        $this->proceesOrder = $proceesOrder;

    }

    /**
     * Sync Order to promail.
     * @return void
     */
    public function execute()
    {
        $this->proceesOrder->sendOrderToPromail();
    }

}
