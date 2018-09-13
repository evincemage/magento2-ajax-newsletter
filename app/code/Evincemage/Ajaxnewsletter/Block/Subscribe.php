<?php

namespace Evincemage\Ajaxnewsletter\Block;

use Magento\Framework\View\Element\Template;

class Subscribe extends \Magento\Newsletter\Block\Subscribe
{
    protected $ajaxnewsletterhelper;

    public function __construct(
        \Evincemage\Ajaxnewsletter\Helper\Data $ajaxnewsletterhelper,
        Template\Context $context,
        array $data = []
    ){

        $this->ajaxnewsletterhelper = $ajaxnewsletterhelper;
        parent::__construct($context, $data);

    }

    public function getEnable(){
    	return $this->ajaxnewsletterhelper->getEnable();
    }

}