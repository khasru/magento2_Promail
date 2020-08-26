<?php


namespace Scriptlodge\Promail\Controller\Index;

use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;
use Magento\Sales\Api\OrderRepositoryInterface;

class Index extends Action
{
    /**
     * @param Context     $context
     * @param PageFactory $resultPageFactory
     */
    public function __construct(
        Context $context,
        OrderRepositoryInterface $orderRepository,
        \Scriptlodge\Promail\Model\ProceesOrder $proceesOrder,
        \Scriptlodge\Promail\Cron\SyncOrders $syncOrders,
        PageFactory $resultPageFactory
    ) {
        $this->_resultPageFactory = $resultPageFactory;
        $this->_orderRepository = $orderRepository;
        $this->proceesOrder=$proceesOrder;
        $this->syncOrders=$syncOrders;
        parent::__construct($context);
    }

    /**

     * @return \Magento\Framework\View\Result\Page
     */
    public function execute()
    {
     //   $lastOrderId=3955;
        //$order = $this->_orderRepository->get($lastOrderId);
       // $this->proceesOrder->sendOrderToPromail($lastOrderId);
        $this->syncOrders->execute();
       // echo $order->getId();
        exit(' here');

    }
}
