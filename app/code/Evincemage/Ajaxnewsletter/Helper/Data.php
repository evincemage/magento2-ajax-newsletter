<?php

namespace Evincemage\Ajaxnewsletter\Helper;

class Data extends \Magento\Framework\App\Helper\AbstractHelper
{
   const ENABLE  = 'ajaxnewsletter/general/enable_in_frontend';

    public function __construct(
        \Magento\Framework\App\Helper\Context $context
    ) {
        parent::__construct($context);
    }

    public function getEnable()
    {
        return $this->scopeConfig->getValue(self::ENABLE);
    }

}