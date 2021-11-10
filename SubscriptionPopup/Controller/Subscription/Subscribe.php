<?php
namespace Magenable\SubscriptionPopup\Controller\Subscription;

use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\Controller\Result\Json;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Validator\EmailAddress as EmailAddressValidator;
use Magento\Newsletter\Model\GuestSubscriptionChecker;
use Psr\Log\LoggerInterface;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Newsletter\Model\SubscriberFactory;
use Magento\Newsletter\Model\Subscriber;
use Magento\Customer\Model\Session;
use Magento\Newsletter\Model\SubscriptionManagerInterface;
use Magento\Framework\Phrase;
use Magento\Framework\Data\Form\FormKey;
use Magento\Framework\Encryption\Helper\Security;
/**
 * Newsletter subscription status verification controller.
 */
class Subscribe extends Action implements HttpPostActionInterface
{
    
    /**
     * All properties are private
     */

    /**
     * Constructor
     *
     * @param Context $context
     * @param EmailAddressValidator $emailAddressValidator
     * @param LoggerInterface $logger
     * @param StoreManagerInterface $storeManager
     * @param SubscriberFactory $subscriberFactory
     * @param Session $customerSession
     * @param SubscriptionManagerInterface $subscriptionManager
     * @param FormKey $formKeyCheck
     */
    public function __construct(
        Context $context,
        EmailAddressValidator $emailAddressValidator,
        LoggerInterface $logger,
        StoreManagerInterface $storeManager,
        SubscriberFactory $subscriberFactory,
        Session $customerSession,
        SubscriptionManagerInterface $subscriptionManager,
        FormKey $formKeyCheck
    ) {
        parent::__construct($context);
        $this->emailAddressValidator = $emailAddressValidator;
        $this->logger = $logger;
        $this->storeManager = $storeManager;
        $this->subscriberFactory = $subscriberFactory;
        $this->customerSession = $customerSession;
        $this->subscriptionManager = $subscriptionManager;
        $this->formKeyCheck = $formKeyCheck;
    }

    

    /**
     * @inheritdoc
     */
    public function execute()
    {
        
        $response = [
            'error_messages' => [],
            'sucess_messages' => [],
            'success' => false,
            'error' => false
        ];
        try {

            $jsonPayload = $this->getRequest()->getContent();
            $params = json_decode($jsonPayload,true);
            $email = $params['email'] ? $params['email'] : '';
            $formKey = $params['form_key'] ? $params['form_key'] : '';
            if($this->validateFormKey($formKey)){
                if (!empty($email) && $this->emailAddressValidator->isValid($email)) {
                
                   
                        $websiteId = (int)$this->storeManager->getStore()->getWebsiteId();
                        /** @var Subscriber $subscriber */
                        $subscriber = $this->subscriberFactory->create()->loadBySubscriberEmail($email, $websiteId);

                        if ($subscriber->getId()
                            && (int)$subscriber->getSubscriberStatus() === Subscriber::STATUS_SUBSCRIBED) {
                                $response['error_messages'][] = __('This email address is already subscribed.');
                                $response['error'] = true;   
                            
                        }else{
                            $storeId = (int)$this->storeManager->getStore()->getId();
                        
                            $currentCustomerId = $this->getSessionCustomerId($email);
                            $subscriber = $currentCustomerId
                                ? $this->subscriptionManager->subscribeCustomer($currentCustomerId, $storeId)
                                : $this->subscriptionManager->subscribe($email, $storeId);

                            $response['sucess_messages'][] = $this->getSuccessMessage($subscriber->getSubscriberStatus());  
                            $response['success'] = true;   
                        }

                        
                        
                }else{
                    $response['error_messages'][] = __('Please check your email again.');
                    $response['error'] = true;   
                } 
            }else{
                $response['error_messages'][] = __('Looks like a robot.');
            }
            
            

        } catch (LocalizedException | \DomainException $exception) {
            $this->logger->error($exception->getMessage());
            $response['error_messages'][] = __('Something happened, pls try again!');
            $response['error'] = true;   
            
        }

        /** @var Json $resultJson */
        $resultJson = $this->resultFactory->create(ResultFactory::TYPE_JSON);

        return $resultJson->setData($response);
    }

    /**
     * Get customer id from session if he is owner of the email
     *
     * @param string $email
     * @return int|null
     */
    private function getSessionCustomerId(string $email): ?int
    {
        if (!$this->customerSession->isLoggedIn()) {
            return null;
        }

        $customer = $this->customerSession->getCustomerDataObject();
        if ($customer->getEmail() !== $email) {
            return null;
        }

        return (int)$this->_customerSession->getId();
    }


    /**
     * Get success message
     *
     * @param int $status
     * @return Phrase
     */
    private function getSuccessMessage(int $status): Phrase
    {
        if ($status === Subscriber::STATUS_NOT_ACTIVE) {
            return __('The confirmation request has been sent.');
        }

        return __('Thank you for your subscription.');
    }

    protected function validateFormKey($formKey)
    {
        return $formKey && Security::compareStrings($formKey, $this->formKeyCheck->getFormKey());
    }
}



