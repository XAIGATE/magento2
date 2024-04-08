XaiGate - The Global Crypto Payment Gateway for Magento2
-----------------------------------------------------------

XaiGate – The Best Crypto Payment Gateway Processor. We offer to you a possibility to accept payments on Magento2 worldwide in the most popular cryptocurrencies USDT / ETH / BTC / LTC and many others.

**What Is Crypto Payment Processor?**

There are many reasons why businesses choose XAIGate as their cryptocurrency payment gateway. Here are just a few:

- To increase sales: Accepting cryptocurrency payments can help businesses increase sales by attracting new customers and making it easier for existing customers to pay for goods and services.
- To reduce costs: Cryptocurrency payments are processed much faster than traditional payments, which can save businesses money on processing fees.
- To expand their global reach: Cryptocurrency payments can be accepted from customers all over the world, which can help businesses expand their global reach and reach new customers.
- To protect their business from fraud: XAIGate uses advanced security measures to protect businesses from fraud and theft.


**What kind of services we are providing on our plugin?**

Supported almost all cryptocurrencies on: Ethereum, Binance Smart Chain and TRON network. XAIGATE supports over 9.866 cryptocurrencies on the Ethereum, Binance Smart Chain, and TRON networks. This includes all of the most popular cryptocurrencies, such as Bitcoin, Link, Ethereum, Litecoin, BCH, Dogecoin, and Tether.

- Easy and convenient checkout
- 9.866+ Supported Coins
- No Monthly Cost
- No hidden fees
- Detailed statistics
- Reliable data protection
- 0.2% withdrawal fee
- Fast funds withdrawal (within 30 minutes)
- Help with integration and provide fast online support

**Installation**
* Create a folder structure in Magento root as app/code/Xaigate/PaymentGateway.

* Download and extract the zip folder from the Magento Marketplace and upload the extension files to app/code/Xaigate/PaymentGateway.

* Login to your SSH and run below commands:

```bash
    php bin/magento setup:upgrade
  
    // For Magento version 2.0.x to 2.1.x
    php bin/magento setup:static-content:deploy
  
    // For Magento version 2.2.x & above
    php bin/magento setup:static-content:deploy –f
   
    php bin/magento cache:flush
    
    rm -rf var/cache var/generation var/di var/page_cache generated/*
  
    ```


**Configuration of Plugin**
System Settings->Sales->Payment methods->XaiGate
* APIKey: You'll need to obtain an API Key from your Xaigate project settings. Access the Credential page of your Xaigate dashboard to retrieve your API Key: https://wallet.xaigate.com/merchant/credential
* Shop name: Enter your shop name.
* That’s all, save the setting.

**Support and Feedback**
Magento 2.4.4
