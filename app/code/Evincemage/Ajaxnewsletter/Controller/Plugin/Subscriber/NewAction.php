<?php

namespace Evincemage\Ajaxnewsletter\Controller\Plugin\Subscriber;

use Magento\Customer\Api\AccountManagementInterface as CustomerAccountManagement;

use Magento\Customer\Model\Session;

use Magento\Customer\Model\Url as CustomerUrl;

use Magento\Framework\App\Action\Context;

use Magento\Store\Model\StoreManagerInterface;

use Magento\Newsletter\Model\SubscriberFactory;

 
class NewAction extends \Magento\Newsletter\Controller\Subscriber\NewAction {

    /**

     * @var CustomerAccountManagement

     */

    protected $customerAccountManagement;
 

    protected $resultJsonFactory;

    protected $ajaxnewsletterhelper;

 

    /**

     * Initialize dependencies.

     *

     * @param Context $context

     * @param SubscriberFactory $subscriberFactory

     * @param Session $customerSession

     * @param StoreManagerInterface $storeManager

     * @param CustomerUrl $customerUrl

     * @param CustomerAccountManagement $customerAccountManagement

     */

    public function __construct(

        Context $context,

        SubscriberFactory $subscriberFactory,

        Session $customerSession,

        StoreManagerInterface $storeManager,

        CustomerUrl $customerUrl,

        CustomerAccountManagement $customerAccountManagement,

        \Evincemage\Ajaxnewsletter\Helper\Data $ajaxnewsletterhelper,

        \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory

    ) {

        $this->customerAccountManagement = $customerAccountManagement;

        $this->resultJsonFactory = $resultJsonFactory;

        $this->ajaxnewsletterhelper = $ajaxnewsletterhelper;

        parent::__construct(

            $context,

            $subscriberFactory,

            $customerSession,

            $storeManager,

            $customerUrl,

            $customerAccountManagement

        );

    }

    public function aroundExecute($subject, callable $procede) {        
        if($this->ajaxnewsletterhelper->getEnable()){
        $response = [];

        if ($this->getRequest()->isPost() && $this->getRequest()->getPost('email')) {

            $email = (string)$this->getRequest()->getPost('email');

            try {

                $this->validateEmailFormat($email);

                $this->validateGuestSubscription();

                $this->validateEmailAvailable($email);

                $status = $this->_subscriberFactory->create()->subscribe($email);

                if ($status == \Magento\Newsletter\Model\Subscriber::STATUS_NOT_ACTIVE) {

                    $response = [

                        'status' => 'OK',

                        'msg' => __('The confirmation request has been sent.'),

                    ];

                } else {

                    $response = [

                        'status' => 'OK',

                        'msg' => __('Thank you for your subscription.'),

                    ];

                }
               
            } catch (\Magento\Framework\Exception\LocalizedException $e) {

                $response = [

                    'status' => 'ERROR',

                    'msg' => __($e->getMessage()),

                ];

            } catch (\Exception $e) {

                $response = [

                    'status' => 'ERROR',

                    'msg' => __('Something went wrong with the subscription.'),

                ];

            }

        }

        return $this->resultJsonFactory->create()->setData($response);
    } else {
        $result = $procede();
        return $result;
    }
}

}