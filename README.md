# readybloom-shipping

___

## Summary

The following instructions will enable **Ready Bloom** as a shipping method for your WooCommerce store. Customers will see **Ready Bloom** as a shipping method at checkout, along with our calculated shipping rates. Keep in mind, we only ship regionally within a 30KM radius of your store's address. If the customer's shipping address is out of range, **Ready Bloom** will not be an option.

### Steps

There are 2 main steps:

- Install the **readybloom-shipping** plugin provided to you by {someone}@readybloom.ca
- Subscribe to the WooCommerce webhook ***Order Update***

## Install Plugin

### What it Does

This plugin will add **Ready Bloom** as a shipping method to your WooCommerce store. Mainly calculating shipping rates at checkout. You should have received the plugin from **Ready Blooom** with the file name ***readybloom-shipping.zip***. Save this somewhere on your computer where you can access it in the following steps.

### Install

In your WordPress dashboard, navigate to ***Plugins***.

In the top left corner of the ***Plugins*** page, click ***Add New***.

You should be at the ***Add Plugins*** page now. In the top left corner, click ***Upload Plugin***.

Click ***Choose File*** and choose the ***readybloom-shipping.zip*** you saved previously.

Once uploaded, click ***Install Now***.

You should be redirected to a page with the title ***Installing Plugin from uploaded file: readybloom-shipping.zip***. On this page, click ***Activate Plugin***.

To see the newly installed plugin, navigate to:

`WooCommerce --> Settings --> Shipping --> Ready Bloom Shipping`

From here, you will be able to change what your customers see at checkout, defualt is **Ready Bloom Shipping**. You also have the option to disable the shipping method. If you do this, please let us know.  

## Subscribe to WooCommerce Webhook

### What it Does

You will need to subscribe to the WooCommerce webhook `Order updated`. This allows **Ready Bloom** check if the order is fulfilled on your end. If the status of the order is changed to `Completed`, **Ready Bloom** will start the delivery process.

### Instructions

In your WooCommerce dashboard, navigate to:

`Settings --> Advanced`

Click on ***Webhooks*** and then ***Add webhook***.

Add the folling information in the form fields:

`Name` --> `Ready Bloom Order`  
`Status` --> `Active`  
`Topic` --> `Order updated`  
`Delivery URL` --> `https://ready-bloom.firebaseapp.com/api/v1/order/woocommerce/create?access_token={YOUR_ACCESS_TOKEN}`  
`Secret` --> Leave Empty  
`API Version` --> `WP REST API Integration v3`  
