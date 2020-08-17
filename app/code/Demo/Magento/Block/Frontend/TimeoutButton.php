<?php

namespace Demo\Magento\Block\Frontend;

use Demo\Magento\Model\PostFactory;
use Demo\Magento\Model\ResourceModel\Post\CollectionFactory as DealCollection;
use Magento\Catalog\Model\ResourceModel\Product\CollectionFactory as CoreCollection;
use Magento\Framework\Registry;
use Magento\Framework\View\Element\Template;

class TimeoutButton extends Template
{
    protected $DealCollection;
    protected $CoreCollection;
    protected $ProductRepository;
    protected $registry;

    public function __construct(
        Template\Context $context,
        PostFactory $PostFactory,
        DealCollection $DealCollection,
        CoreCollection $CoreCollection,
        Registry $registry
    )
    {
        $this->CoreCollection = $CoreCollection;
        $this->DealCollection = $DealCollection;
        $this->registry = $registry;
        parent::__construct($context);
    }

    public function GetProduct()
    {
        return $this->DealCollection->create();
    }

    public function GetDeal($sku, $key)
    {
        $deal = $this->DealCollection->create();
        $dealSku = [];

        foreach ($deal as $dealItem) {
            $dealSku[] = $dealItem->GetData('product');
        }

        if (in_array($sku, $dealSku)) {
            return $deal->getItemByColumnValue('product', $sku)->getData($key);
        } else {
            return null;
        }
    }

    public function GetCurrentSku()
    {
        return $this->registry->registry('current_product');
    }
}

