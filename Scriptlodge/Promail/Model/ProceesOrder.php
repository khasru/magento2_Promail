<?php
/**
 *
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Scriptlodge\Promail\Model;

use Magento\Sales\Api\OrderRepositoryInterface;
use Magento\Setup\Exception;
use Magento\Framework\Encryption\EncryptorInterface;
use Scriptlodge\Promail\Model\PromailTrackingFactory;

/**
 * Class ProceesOrder
 *
 * @codeCoverageIgnore
 */
class ProceesOrder extends \Magento\Framework\Model\AbstractModel
{
    protected $orderUrl = "https://www.deal1.ch/PromailRESTorderV1.php";
    protected $statusUrl = "https://www.deal1.ch/PromailRESTstatusV1.php";

    protected $_orderCollectionFactory;
    /**
     * Destination constructor.
     * @param \Magento\Framework\Model\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param \Magento\Framework\Model\ResourceModel\AbstractResource|null $resource
     * @param \Magento\Framework\Data\Collection\AbstractDb|null $resourceCollection
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\Model\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Model\ResourceModel\AbstractResource $resource = null,
        \Magento\Framework\Data\Collection\AbstractDb $resourceCollection = null,
        OrderRepositoryInterface $orderRepository,
        \Magento\Sales\Model\ResourceModel\Order\CollectionFactory $orderCollectionFactory,
        \Scriptlodge\Promail\Api\PromailTrackingRepositoryInterface $promailTrackingRepository,
        \Scriptlodge\Promail\Api\Data\PromailTrackingInterface $promailTrackingInterface,
        PromailTrackingFactory $promailTrackingFactory,
        \Magento\Framework\Api\SearchCriteriaBuilder $searchCriteriaBuilder,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        EncryptorInterface $encryptorInterface,
        array $data = []
    )
    {
        parent::__construct($context, $registry, $resource, $resourceCollection, $data);
        $this->_orderRepository = $orderRepository;
        $this->promailTrackingRepository = $promailTrackingRepository;
        $this->_orderCollectionFactory = $orderCollectionFactory;
        $this->promailTrackingInterface = $promailTrackingInterface;
        $this->promailTrackingFactory=$promailTrackingFactory;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->scopeConfig = $scopeConfig;
        $this->encryptorInterface = $encryptorInterface;
    }

    public function getConfigValue($path, $storeScope = 0, $decrypt = false)
    {
        $storeScope = \Magento\Store\Model\ScopeInterface::SCOPE_STORE;
        if ($decrypt) {
            return $this->encryptorInterface->decrypt($this->scopeConfig->getValue($path, $storeScope));
        } else {
            return $this->scopeConfig->getValue($path, $storeScope);
        }
    }

    public function getConfiguration()
    {
        $apiConfig['enabled'] = $this->getConfigValue('promail/general/enabled');
        $apiConfig['key'] = $this->getConfigValue('promail/general/api_key');
        $apiConfig['privatkey'] = $this->getConfigValue('promail/general/privat_key');
        $apiConfig['your_email'] = $this->getConfigValue('promail/general/email');
        $apiConfig['api_name'] = "order";
        return $apiConfig;
    }

    public function sendOrderToPromail($orderId = "")
    {
        $orderRequestData = [];
        $orders = [];

        $url = $this->orderUrl;
        $apiConfig = $this->getConfiguration();
        if(isset($apiConfig['enabled']) && $apiConfig['enabled']!=1) return true;

        $orderRequestData['partner'] = $apiConfig;

        if ($orderId) {
            $order = $this->_orderRepository->get($orderId);
            if($this->makeOrderData($order)){
                $orders[] = $this->makeOrderData($order);
            }
        } else {
            $to = date("Y-m-d h:i:s");
            $from = strtotime('-3 day', strtotime($to));
            $from = date('Y-m-d h:i:s', $from);

            $this->searchCriteriaBuilder->addFilter('status', array('canceled', 'closed', 'complete'), 'nin');
            $this->searchCriteriaBuilder->addFilter('created_at', $from, 'gt');
            $this->searchCriteriaBuilder->addFilter('store_id', array(1,2,3), 'in');
            $this->searchCriteriaBuilder->setPageSize(200)->setCurrentPage(1);
            $searchCriteria = $this->searchCriteriaBuilder->create();
            $_orderItems = $this->_orderRepository->getList($searchCriteria)->getItems();

            foreach ($_orderItems as $order){
                if($this->makeOrderData($order)){
                    $orders[] =   $this->makeOrderData($order);
                }
            }
        }

        if (empty($orders[0])) {
            return;
        }

        $orderRequestData['partner']['orders'] = $orders;
       // print_r($orderRequestData);

        $body = json_encode($orderRequestData);

        $response = $this->sendTransaction($url, $body, 'POST');

        if ($response != false) {
            $this->saveRequest($orders);
        }
        return true;
    }

    protected function makeOrderData($order)
    {
        $requestData = [];

        $trackingRepository = $this->promailTrackingRepository;
        $orderIncrementedId = $order->getIncrementId();

        $orderStatus = array('canceled', 'closed', 'complete');
        if (in_array($order->getState(), $orderStatus)) {
            return;
        }


        try {
            $tracking = $trackingRepository->getByOrderId($orderIncrementedId);
            if (count($tracking->getItems()) > 0) {
                return;
            }
        } catch (Exception $e) {
            //exit('exit');
        }

        if(!in_array($order->getStoreId(),array(1,2,3))){
            return $requestData;
        }

        $paymentMethod = $order->getPayment()->getMethodInstance()->getTitle();
        $orderAmount = sprintf('%.2F', $order->getGrandTotal());

        $billingAddress = $order->getBillingAddress();
        $streets = $billingAddress->getStreet();

        $_street = "";
        foreach ($streets as $str) {
            $_street .= $str . " ";
        }

        $requestData['lieferschein_nr'] = $orderIncrementedId;
        $requestData['rechnungs_nr'] = "";
        $requestData['emp_firma'] = $billingAddress->getCompany();
        $requestData['emp_name'] = $billingAddress->getName();
        $requestData['emp_strasse'] = $_street;
        $requestData['emp_hausnr'] = $_street;
        $requestData['emp_plz'] = $billingAddress->getPostcode();
        $requestData['emp_ort'] = $billingAddress->getRegion();
        $requestData['emp_land'] = $billingAddress->getCountryId();
        $requestData['emp_email'] = $billingAddress->getEmail();
        $requestData['emp_tel'] = $billingAddress->getTelephone();
        $requestData['versandart'] = $order->getShippingDescription();
        $requestData['rechnungsart'] = $paymentMethod;
        $requestData['total_betrag'] = $orderAmount;
        $requestData['currency'] = "CHF";

        $items = $order->getAllVisibleItems();
        $itemArray = [];
        foreach ($items as $item) {
            $itemPrice = sprintf('%.2F', $item->getPrice());
            $itemData['artikel_name'] = $item->getName();
            $itemData['artikel_nr'] = $item->getSku();
            $itemData['anzahl'] = (int)$item->getQtyOrdered();
            $itemData['preis'] = $itemPrice;
            $itemData['lot'] = "";
            $itemData['mhd'] = "";
            $itemArray[] = $itemData;
        }
        $requestData['basket'] = $itemArray;

        return $requestData;
    }

    public function getStatusTracking($apiConfig, $orderId)
    {
        $url = $this->statusUrl;
        $apiConfig = $this->getConfiguration();

        $key = $apiConfig['key'];
        $privatkey = $apiConfig['privatkey'];
        $your_email = $apiConfig['your_email'];
        $lieferschein_nr = $orderId;

        //Making GET-url
        $url = $url . '?partner=' . $key . '*' . $privatkey . ':' . $your_email . '&lieferschein_nr=' . $lieferschein_nr;

        $response = $this->sendTransaction($url, "", "GET", "");

        $this->updateTracking($response, $orderId);
    }

    public function sendTransaction($url, $body = "", $method = 'POST', $config = "")
    {
        $headers = [
            "Content-Type: application/json"
        ];

        $curl = curl_init($url);
        // curl_setopt($post, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_HEADER, 0);
        if ($method == 'POST') {
            curl_setopt($curl, CURLOPT_POST, 1);
        } else {
            curl_setopt($curl, CURLOPT_POST, 0);
        }
        curl_setopt($curl, CURLOPT_TIMEOUT, 45);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
        if ($body) {
            curl_setopt($curl, CURLOPT_POSTFIELDS, $body);
        }
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);

        $result = curl_exec($curl);

        //$info = curl_getinfo($curl);
        $response = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        /*print_r($result);
        print_r($response);*/
        /*print_r($response);
        exit();*/

        curl_close($curl);
        if (200 === $response || 202 == $response) {
            return $result;
        } else {
            /* throw new CommandException('Error: "' . curl_error($curl) . '" - Code: ' . curl_errno($curl));
           die('Error: "' . curl_error($curl) . '" - Code: ' . curl_errno($curl));
             //$errors = curl_error($curl);*/
            return false;
        }
    }

    protected function saveRequest($orders)
    {
        $trackingRepository = $this->promailTrackingRepository;
        $promailTracking = $this->promailTrackingInterface;

        foreach ($orders as $order) {
            if ($order['lieferschein_nr'] == "") continue;
          //  $promailTracking = $this->promailTrackingInterface;
            $promailTracking=  $this->promailTrackingFactory->create();
            $promailTracking->setOrderId($order['lieferschein_nr']);
            $promailTracking->setShippingMethod($order['versandart']);
            $promailTracking->setTotalAmount($order['total_betrag']);
            $promailTracking->save();
        }
        foreach ($orders as $order) {
            $trackingStatus = $this->getStatusTracking($apiConfig = "", $order['lieferschein_nr']);
        }
    }

    protected function updateTracking($response, $orderId)
    {
        $str = str_replace('order', "", $response);
        $str = trim(substr($str, 2, -2));
        $array = json_decode($str, true);
        $status = $array['status'];
        $_trackingStatus = $array['tracking'];
        if ($_trackingStatus) {
            $trackingObj = $this->promailTrackingRepository->getByOrderId($orderId);
            if ($trackingObj->getItems()) {
                foreach ($trackingObj->getItems() as $tracking) {
                    $promailTracking=  $this->promailTrackingFactory->create();
                    $id = $tracking->getData('entity_id');
                    $promailTracking->setEntityId($id);
                    $promailTracking->setStatus($status);
                    $promailTracking->setTracking($_trackingStatus);
                    $promailTracking->save();
                    unset($promailTracking);
                }
            }
        }
    }

}
