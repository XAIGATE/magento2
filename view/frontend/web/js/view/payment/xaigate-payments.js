define(
    [
        'uiComponent',
        'Magento_Checkout/js/model/payment/renderer-list'
    ],
    function (
        Component,
        rendererList
    ) {
        'use strict';
        rendererList.push(
            {
                type: 'xaigate_paymentgateway',
                component: 'Xaigate_PaymentGateway/js/view/payment/method-renderer/xaigate-method'
            }
        );
        return Component.extend({});
    }
);
