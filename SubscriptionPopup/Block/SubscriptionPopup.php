<?php
namespace Magenable\SubscriptionPopup\Block;

use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;
use Magenable\SubscriptionPopup\Model\Config as TheModuleConfig;



class SubscriptionPopup extends Template {


    public function __construct(
        TheModuleConfig $moduleConfig,
        Context $context,  
        array $data = []
    )
    {
        $this->moduleConfig = $moduleConfig;
        parent::__construct($context, $data);   
    }

    public function getPopupJsConfig(){

        return json_encode(
            [
                'module_css_path' => $this->getViewFileUrl("/Magenable_SubscriptionPopup/css/popup.css"),
                'popup_header' => $this->moduleConfig->getHeaderText(),
                'popup_description' => $this->moduleConfig->getDescription(),
                'delay' =>  $this->moduleConfig->getPopupDelay()
            ]
        );
    }

    public function isDisplay(){
        return $this->moduleConfig->isEnabled();
    }



}