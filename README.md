# subscription_poup
Magento2 - Simple Extension - Using Require-CSS and LESS Preproccessor 

## Use Require-CSS to load css code for specific component 

## I implemented a popup as UI Component. In this component, I load CSS asynchromously from it. You can have a look here
https://github.com/trunglv/subscription_poup/blob/main/SubscriptionPopup/view/frontend/web/js/view/popup.js#L43

Require-CSS can be got from here https://www.npmjs.com/package/require-css



## Installation: 
1. Create a folder app/code/Magenable.
2. Download a source-code then copy a folder SubscriptionPopup into such below folder.
3. Enable Module 
```
bin/magento module:enable Magenable_SubscriptionPopup
```
5. Run DI Compile
```
bin/magento setup:di:compile
```
7. Deploy Static Content 
```
bin/magento setup:static-content:deploy
```
