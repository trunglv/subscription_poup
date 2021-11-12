<?php 
namespace Magenable\SubscriptionPopup\Model;

use Magento\Store\Model\ScopeInterface;

use Magento\Framework\App\Config\ScopeConfigInterface;

class Config {

    const XML_CONFIG_MODULE_ENABLE = 'magenable_subscriptionpoupup/general/enable';

    const XML_CONFIG_DESIGN_POPUP_TITLE = 'magenable_subscriptionpoupup/general/popup_title';

    const XML_CONFIG_DESIGN_POPUP_DESCRIPTION = 'magenable_subscriptionpoupup/general/popup_description';

    const XML_CONFIG_DESIGN_BUTTON_COLOR = 'magenable_subscriptionpoupup/general/button_color';

    const XML_CONFIG_DESIGN_BUTTON_BACKGROUND = 'magenable_subscriptionpoupup/general/button_background';

    const XML_CONFIG_DESIGN_POPUP_DELAY = 'magenable_subscriptionpoupup/general/popup_delay';

    /**
     * @var ScopeConfigInterface
     */
    protected $scopeConfig;
    
    /**
     * @param ScopeConfigInterface $scopeConfig
     */
    public function __construct(
        ScopeConfigInterface $scopeConfig
    )
    {
        $this->scopeConfig = $scopeConfig;
    }

    public function isEnabled(){
        return $this->scopeConfig->getValue(self::XML_CONFIG_MODULE_ENABLE, ScopeInterface::SCOPE_STORE);
        
    }
    public function getHeaderText(){
        return $this->scopeConfig->getValue(self::XML_CONFIG_DESIGN_POPUP_TITLE, ScopeInterface::SCOPE_STORE);
    }

    public function getDescription(){
        return $this->scopeConfig->getValue(self::XML_CONFIG_DESIGN_POPUP_DESCRIPTION, ScopeInterface::SCOPE_STORE);
    }

    public function getPopupDelay(){
        return $this->scopeConfig->getValue(self::XML_CONFIG_DESIGN_POPUP_DELAY, ScopeInterface::SCOPE_STORE);
    }


    

    /*
    public function getButtonColor(){
        return $this->scopeConfig->getValue(self::XML_CONFIG_DESIGN_BUTTON_COLOR, ScopeInterface::SCOPE_STORE);
    }
    public function getButtonBackground(){
        return $this->scopeConfig->getValue(self::XML_CONFIG_DESIGN_BUTTON_BACKGROUND, ScopeInterface::SCOPE_STORE);
    }
    */


}
