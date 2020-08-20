<?php

namespace Demo\Deal\Block\Frontend;

use Demo\Deal\Model\ResourceModel\Post\CollectionFactory as deal_collection;
use Magento\Framework\Registry;
use Magento\Framework\View\Element\Template;

class TimeoutButton extends Template
{
    protected $deal_collection;
    protected $registry;

    public function __construct(
        Template\Context $context,
        deal_collection $deal_collection,
        Registry $registry
    )
    {
        $this->deal_collection = $deal_collection;
        $this->registry = $registry;
        parent::__construct($context);
    }

    public function GetProduct()
    {
        return $this->deal_collection->create();
    }

    public function GetDeal($sku, $key)
    {
        $deals = $this->deal_collection->create();
        $deal_sku = [];

        foreach ($deals as $deal) {
            $deal_sku[] = $deal->GetData('product');
        }

        if (in_array($sku, $deal_sku)) {
            return $deals->getItemByColumnValue('product', $sku)->getData($key);
        } else {
            return null;
        }
    }

    public function GetCurrentSku()
    {
        return $this->registry->registry('current_product');
    }
}

