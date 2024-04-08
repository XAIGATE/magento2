<?php

declare(strict_types=1);

namespace Xaigate\PaymentGateway\Api;

interface UpdateStatusOrderInterface
{
    /**
     * Postback Xaigate
     * @return string
     */
    public function doUpdate();
}
