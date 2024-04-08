<?php

namespace Xaigate\PaymentGateway\Model\Api;

use Xaigate\PaymentGateway\Api\UpdateStatusOrderInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Sales\Api\OrderRepositoryInterface;
use Magento\Framework\Webapi\Rest\Request;
use Magento\Store\Model\ScopeInterface;

class UpdateStatusOrder implements UpdateStatusOrderInterface
{

    /** @var OrderRepositoryInterface */
    private $_orderRepository;

    /** @var Request */
    protected $_request;

    /** @var ScopeConfigInterface */
    private $_scopeConfig;

    public function __construct(
        OrderRepositoryInterface $orderRepository,
        ScopeConfigInterface     $scopeConfig,
        Request                  $request
    )
    {
        $this->_orderRepository = $orderRepository;
        $this->_scopeConfig = $scopeConfig;
        $this->_request = $request;
    }

    /**
     * Postback Xaigate
     * @return string
     */
    public function doUpdate()
    {
        try {
            $request = file_get_contents('php://input');

            $body = json_decode($request, true);

            if ($body['type'] != 'Invoice') {
                return 'It is not Invoice';
            }

            $data = $body['data'];
            $order_id = str_replace('magento_order_', "", 'magento_order_' . $data['custom_id']);

            $order = $this->getOrder($order_id);
            $this->updateOrderStatus($order, $data);

            return 'success';
        } catch (\Exception $e) {
            return 'Webhook receive error.';
        }
    }

    public function updateOrderStatus($order, $data)
    {
        if ($data['status'] == 'new') {
            $pendingStatus = $this->_scopeConfig->getValue(
                'payment/xaigate_paymentgateway/status_pending',
                ScopeInterface::SCOPE_STORE);
            $order->setStatus($pendingStatus)->save();
            return;
        }

        if ($data['status'] == 'completed' || $data['status'] == 'unresolved' && $data['status_context'] == 'overpaid') {
            $successStatus = $this->_scopeConfig->getValue(
                'payment/xaigate_paymentgateway/status_completed',
                ScopeInterface::SCOPE_STORE);
            $order->setStatus($successStatus)->save();
            return;
        }

        if ($data['status'] == 'cancelled' || $data['status'] == 'refunded' || $data['status'] == 'unresolved') {
            $failedStatus = $this->_scopeConfig->getValue(
                'payment/xaigate_paymentgateway/status_failed',
                ScopeInterface::SCOPE_STORE
            );
            $order->setStatus($failedStatus)->save();
            $order->save();
        }
    }

    private function getOrder($id): \Magento\Sales\Api\Data\OrderInterface
    {
        return $this->_orderRepository->get($id);
    }
}
