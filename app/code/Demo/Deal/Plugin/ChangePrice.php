<?php

namespace Demo\Deal\Plugin;

use Magento\Checkout\Model\Cart;
use Magento\Framework\DataObject;
use Magento\Framework\View\Element\Template;
use Demo\Deal\Model\ResourceModel\Post\CollectionFactory as DealCollection;
use \Magento\Catalog\Model\ProductRepository;
use Magento\TestFramework\Eav\Model\Attribute\DataProvider\Date;
use Magento\Catalog\Model\Product as Products;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\App\Config\ScopeConfigInterface;

class ChangePrice extends Template
{
    protected $DealCollection;
    protected $registry;
    protected $collection;
    protected $products;
    protected $scopeConfig;

    public function __construct(
        Template\Context $context,
        DealCollection $DealCollection,
        \Magento\Framework\Registry $registry,
        Products $products,
        ProductRepository $collection,
        ScopeConfigInterface $scopeConfig
    )
    {
        $this->DealCollection = $DealCollection;
        $this->registry = $registry;
        $this->collection = $collection;
        $this->products = $products;
        $this->scopeConfig = $scopeConfig;
        parent::__construct($context);
    }

    public function afterGetPrice(\Magento\Catalog\Model\Product $subject, $result)
    {
        date_default_timezone_set('Asia/Ho_Chi_Minh');
        $config = $this->scopeConfig->getValue('helloworld_section_id/general/enable');
        $now = date('Y-m-d H:i:s');
        $deal = $this->DealCollection->create();
        $dealSku = [];

        foreach ($deal as $dealItem) {
            $dealSku[] = $dealItem->GetData('product');
        }
        $id = $subject->getId();
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $parentId = $objectManager->create('Magento\ConfigurableProduct\Model\ResourceModel\Product\Type\Configurable')->getParentIdsByChild($id);

        if (isset($parentId[0])) {
            $productSku = $this->collection->getById($parentId[0])->getSku();
        } else {
            $productSku = $subject->getSku();
        }

        if (in_array($productSku, $dealSku)) {
            $start = $deal->getItemByColumnValue('product', $productSku)->getData('time_start');
            $end = $deal->getItemByColumnValue('product', $productSku)->getData('time_end');
            $status = $deal->getItemByColumnValue('product', $productSku)->getData('status');

            if ($now <= $end && $now >= $start && $config != 0 && $status == 1) {
                return $result = $deal->getItemByColumnValue('product', $productSku)->getData('deal_price');
            } else {
                return $result;
            }
        } else {
            return $result;
        }
    }
}
