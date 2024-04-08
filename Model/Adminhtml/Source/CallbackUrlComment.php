<?php

namespace Xaigate\PaymentGateway\Model\Adminhtml\Source;

use Magento\Framework\UrlInterface;

class CallbackUrlComment implements \Magento\Config\Model\Config\CommentInterface
{
    protected $urlInterface;

    public function __construct(
        UrlInterface $urlInterface
    ) {
        $this->urlInterface = $urlInterface;
    }

    public function getCommentText($elementValue)
    {
        $webhook = $this->urlInterface->getBaseUrl() . 'rest/all/V1/order/status/update';
        $pointOne = '1. <a href="https://business.xaigate.com" target="_blank">Log in</a> to your account on business.xaigate.me';
        $pointTwo = __('2. Then go to <a href="https://business.xaigate.com/app/settings/api" target="_blank"> the Settings -&gt; API page </a> and save %1 in the Callback URL field', $webhook);
        return "$pointOne <br/> $pointTwo";
    }
}
