<?php

namespace Xaigate\PaymentGateway\Controller\Payment;

use Magento\Framework\App\ActionInterface;
use Magento\Checkout\Model\Session;
use Magento\Framework\Controller\Result\JsonFactory;
use Magento\Store\Model\ScopeInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\UrlInterface;

class XaigateCreate implements ActionInterface
{
    private $checkoutSession;
    private $resultJsonFactory;
    private $scopeConfig;
    protected $urlBuilder;
    protected $apikey;

    public function __construct(
        Session $checkoutSession,
        JsonFactory $resultJsonFactory,
        UrlInterface $urlBuilder,
        ScopeConfigInterface $scopeConfig
    ) {
        $this->checkoutSession = $checkoutSession;
        $this->resultJsonFactory = $resultJsonFactory;
        $this->urlBuilder = $urlBuilder;
        $this->scopeConfig = $scopeConfig;
        $this->apikey = $this->scopeConfig->getValue('payment/xaigate_paymentgateway/apikey', ScopeInterface::SCOPE_STORE);
    }

    public function execute()
    {
        $order = $this->getOrder();
        $pendingStatus = $this->scopeConfig->getValue(
            'payment/xaigate_paymentgateway/status_pending',
            ScopeInterface::SCOPE_STORE); // \Magento\Sales\Model\Order::STATE_PENDING_PAYMENT
        $order->setStatus($pendingStatus)->save();

        $customId = $order->getId();
        $orderAmount = $order->getGrandTotal();
        $orderCurrency = $order->getOrderCurrencyCode();
        $apikey = $this->apikey;
        $shopperEmail = $order->getCustomerEmail();
        $shopname = $this->scopeConfig->getValue('payment/xaigate_paymentgateway/shopname', ScopeInterface::SCOPE_STORE);
        
        $description = array();
        foreach ($order->getItems() as $item) {
            $description[] = $item->getQtyOrdered() . ' × ' . $item->getName();
        }

        $successUrl = $this->urlBuilder->getUrl(
            'xaigate/payment/xaigatesuccess',
            ['_query' => ['order_id' => $order->getId()]]
        );
        $failUrl = $this->urlBuilder->getUrl(
            'xaigate/payment/xaigatecancel',
            ['_query' => ['order_id' => $order->getId()]]
        );

        $data_request = [
			'shopName'	=> $shopname,
			'amount'	=> $orderAmount,
			'currency'	=> $orderCurrency,
			'orderId'	=> intval($customId),
			'email'		=> $shopperEmail,
			'apiKey'	=> $apikey,
            "successUrl" => $successUrl ,
            "failUrl" => $failUrl,
            "notifyUrl" => "",
            "description" => implode( ', ', $description )
		];

		$headers = [
			'Content-Type: application/json'
		];
       
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_POST, 1);
		curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($data_request));
		curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($curl, CURLOPT_URL, 'https://wallet-api.xaigate.com/api/v1/invoice/create');
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		$result = curl_exec($curl);

		curl_close($curl);

		$json_data = json_decode($result, true);
		$url = $json_data['payUrl'];

        $result = $this->resultJsonFactory->create();
        return $result->setData(['redirectUrl' => $url]);
    }

    private function getOrder(): \Magento\Sales\Model\Order
    {
        return $this->checkoutSession->getLastRealOrder();
    }
}
