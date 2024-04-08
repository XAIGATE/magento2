define(
    [
        'jquery',
        'Magento_Checkout/js/view/payment/default',
        'mage/url',
        'Magento_Customer/js/customer-data',
        'Magento_Checkout/js/model/error-processor',
        'Magento_Checkout/js/model/full-screen-loader'
    ],
    function ($, Component, url, customerData, errorProcessor, fullScreenLoader) {
        'use strict';
        return Component.extend({
            redirectAfterPlaceOrder: false,
            defaults: {
                template: 'Xaigate_PaymentGateway/payment/xaigate-form'
            },
            getCode: function() {
                return 'xaigate_paymentgateway';
            },
            isActive: function() {
                return true;
            },
            afterPlaceOrder: function () {
                var custom_controller_url = url.build('xaigate/payment/xaigatecreate');

                $.post(custom_controller_url, 'json')
                    .done(function (response) {
                        window.location.href = response.redirectUrl;
                    })
                    .fail(function (response) {
                        errorProcessor.process(response, this.messageContainer);
                    })
                    .always(function () {
                        fullScreenLoader.stopLoader();
                    });
            },
            getXaigateMessage: function () {
                return window.checkoutConfig.payment.xaigate_message[this.item.method];
            }
        });
    }
);
