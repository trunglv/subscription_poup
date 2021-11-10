<?php 
namespace Magenable\SubscriptionPopup\Css\PreProcessor\Instruction;

use Magento\Framework\View\Asset\LocalInterface;
use Magento\Framework\View\Asset\NotationResolver;
use Magento\Framework\View\Asset\PreProcessorInterface;
use Magento\Framework\Css\PreProcessor\FileGenerator\RelatedGenerator;
use Magento\Framework\View\Asset\PreProcessor\Chain;
use Magenable\SubscriptionPopup\Model\CssVariableCollector;

class Variables implements PreProcessorInterface {

    /**
     * @var CssVariableCollector
     */
    protected $cssVariableCollector;
    /**
     * construtor
     *
     * @param CssVariableCollector $cssVariableCollector
     */
    public function __construct(

        CssVariableCollector $cssVariableCollector
    )
    {
        $this->cssVariableCollector = $cssVariableCollector;
    }
    /**
     * {@inheritdoc}
     */
    public function process(\Magento\Framework\View\Asset\PreProcessor\Chain $chain)
    {
        $asset = $chain->getAsset();
        
        if($asset->getModule() == 'Magenable_SubscriptionPopup'){
            
            /*
            Example for values returned by cssVariableCollector
            $moduleVariables = [
                [ "name" => "mmagenable-subscription-button-color", 'value'  =>  "#428bca" ],
                [ "name" => "mmagenable-subscription-button-background", 'value'  =>  "#428bca" ]
            ];
            */
            
            $moduleVariables = $this->cssVariableCollector->execute();
           
            $lessCodeLines = array_reduce($moduleVariables, function($codeLine, $variable){
                $codeLine .= sprintf("@%s: %s;", $variable['name'], $variable['value']) . PHP_EOL;
                return $codeLine;
            }, '' ); 
            $processedContent = str_replace("//@subscription_popup_variables_import", $lessCodeLines, $chain->getContent());
            
            $chain->setContent($processedContent);

        }
        
    }
    
}