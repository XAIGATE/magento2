<?php

namespace Xaigate\PaymentGateway\Model\Payment;

use Magento\Checkout\Model\ConfigProviderInterface;
use Magento\Framework\Escaper;
use Magento\Payment\Helper\Data as PaymentHelper;
use Magento\Store\Model\ScopeInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;

class CustomPaymentConfigProvider implements ConfigProviderInterface
{
    protected $methodCodes = [
        Xaigatemethod::CODE
    ];

    protected $methods = [];
    protected $escaper;
    private $scopeConfig;

    public function __construct(
        PaymentHelper $paymentHelper,
        Escaper $escaper,
        ScopeConfigInterface $scopeConfig
    ) {
        $this->escaper = $escaper;
        $this->scopeConfig = $scopeConfig;

        foreach ($this->methodCodes as $code) {
            $this->methods[$code] = $paymentHelper->getMethodInstance($code);
        }
    }

    public function getConfig()
    {
        $config = [];
        foreach ($this->methodCodes as $code) {
            if ($this->methods[$code]->isAvailable()) {
                $config['payment']['xaigate_logo'][$code] = $this->getXaigateLogo();
                $config['payment']['xaigate_message'][$code] = $this->getXaigateMessage();
            }
        }
        return $config;
    }

    protected function getXaigateLogo()
    {
        return $this->scopeConfig->getValue('payment/xaigate_paymentgateway/show_logo', ScopeInterface::SCOPE_STORE);
    }

    protected function getXaigateMessage()
    {
        $message = $this->scopeConfig->getValue('payment/xaigate_paymentgateway/checkout_page_message', ScopeInterface::SCOPE_STORE);
        $output = ($message == NULL) ? false : '<p class="payment-method-redirect-message">' . nl2br($this->escaper->escapeHtml($message)) . '</p>';
        return $output;
    }
}
