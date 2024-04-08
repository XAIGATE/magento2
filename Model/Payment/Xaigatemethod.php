<?php

namespace Xaigate\PaymentGateway\Model\Payment;

class Xaigatemethod extends \Magento\Payment\Model\Method\AbstractMethod
{
    const CODE = 'xaigate_paymentgateway';

    protected $_code = 'xaigate_paymentgateway';

    public function isAvailable(
        \Magento\Quote\Api\Data\CartInterface $quote = null
    ) {

        $apikey = $this->_scopeConfig->getValue(
            'payment/xaigate_paymentgateway/apikey',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );

        if (!$apikey) {
            return false;
        }
        return parent::isAvailable($quote);
    }
}
