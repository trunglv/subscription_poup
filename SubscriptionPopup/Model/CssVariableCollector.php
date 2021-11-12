<?php 
namespace Magenable\SubscriptionPopup\Model;

use Magenable\SubscriptionPopup\Model\Config as TheModuleConfig;
use Magento\Store\Model\ScopeInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;

class CssVariableCollector {

        /**
         * @var ScopeConfigInterface
         */
        
        protected $scopeConfig;

        const BTN_COLOR_NAME_VARIABLE_NAMWE = 'mmagenable-subscription-button-color';
        const BTN_COLOR_BACKGROUND_VARIABLE_NAME = 'mmagenable-subscription-button-background';

        const CONFIG_MAPS = [
                self::BTN_COLOR_NAME_VARIABLE_NAMWE => TheModuleConfig::XML_CONFIG_DESIGN_BUTTON_COLOR,
                self::BTN_COLOR_BACKGROUND_VARIABLE_NAME => TheModuleConfig::XML_CONFIG_DESIGN_BUTTON_BACKGROUND
        ];
        
        /**
         * Constructor 
         *
         * @param ScopeConfigInterface $scopeConfig
         */
        public function __construct(
                ScopeConfigInterface $scopeConfig
        )
        {
              $this->scopeConfig = $scopeConfig;  
        }
    
        public function execute() : array{
                $variables = [];
                $map = self::CONFIG_MAPS;
                
                $config  = $this->scopeConfig;
                array_walk($map, function($value, $key) use (&$variables, $config){
                        $variables[] = [
                                'name' => $key, 
                                'value' => $config->getValue($value, ScopeInterface::SCOPE_STORE)
                        ];
                });
                return $variables;
        }
}
