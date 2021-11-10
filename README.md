# Magento2 - Simple Extension - Using Require-CSS and LESS Preproccessor to approach Headless architect for CSS and avoid CSS inlines.  

#### A Short description about extension features: 
It's just a simple extension to show a subscription form as a popup. Some CSS variables will be got from Magento Backend Configuration.

#### What I do to try approach Headless architect for CSS 

## 1. Use Require-CSS to load CSS asynchromously for a specific UI component 

I implement a popup as UI Component. In this component, I load CSS asynchromously from it. You can have a look here https://github.com/trunglv/subscription_poup/blob/main/SubscriptionPopup/view/frontend/web/js/view/popup.js#L43

Require-CSS can be got from here https://www.npmjs.com/package/require-css

##### How to import into Magento2 requirejs-configs
```
var config = {
    map: {
        '*': {
                
                'css': 'Magenable_SubscriptionPopup/js/require-css/css' 
            }
        }
};

```
##### How to call it :
```
require(['css!./'+ your_css_path  ], function(){}{ } )
```

## 2. LESS Preprocessor - To inject/replace some code-lines/variables depends on Backend Configurations 

###### Due to get some Stylesheet variables from Magento configuration (likes background-color, text-color) to pass into a LESS file. I make a Less preprocessor(PHP code) to inject them.
See here : https://github.com/trunglv/subscription_poup/blob/main/SubscriptionPopup/Css/PreProcessor/Instruction/Variables.php#L32

Define a new preproccessor from di.xml
```
<virtualType name="AssetPreProcessorPool" type="Magento\Framework\View\Asset\PreProcessor\Pool">
        <arguments>
            <argument name="preprocessors" xsi:type="array">
                <item name="less" xsi:type="array">
                     <item name="mageable_subscription_css_import" xsi:type="array">
                        <item name="before" xsi:type="string">magento_import</item>
                        <item name="class" xsi:type="string">Magenable\SubscriptionPopup\Css\PreProcessor\Instruction\Variables</item>
                    </item>
                </item>
            </argument>
        </arguments>
    </virtualType>
```

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
