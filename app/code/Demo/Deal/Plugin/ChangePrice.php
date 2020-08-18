<?php

namespace Demo\Deal\Plugin;

use Magento\Checkout\Model\Cart;
use Magento\Framework\DataObject;
use Magento\Framework\View\Element\Template;
use Demo\Deal\Model\ResourceModel\Post\CollectionFactory as deal_collection;
use \Magento\Catalog\Model\ProductRepository;
use Magento\TestFramework\Eav\Model\Attribute\DataProvider\Date;
use Magento\Catalog\Model\Product as Products;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\App\Config\ScopeConfigInterface;

class ChangePrice extends Template
{
    protected $deal_collection;
    protected $registry;
    protected $collection;
    protected $products;
    protected $scope_config;

    public function __construct(
        Template\Context $context,
        deal_collection $deal_collection,
        \Magento\Framework\Registry $registry,
        Products $products,
        ProductRepository $collection,
        ScopeConfigInterface $scope_config
    )
    {
        $this->deal_collection = $deal_collection;
        $this->registry = $registry;
        $this->collection = $collection;
        $this->products = $products;
        $this->scope_config = $scope_config;
        parent::__construct($context);
    }

    public function afterGetPrice(\Magento\Catalog\Model\Product $subject, $result)
    {
        date_default_timezone_set('Asia/Ho_Chi_Minh');
        $config = $this->scope_config->getValue('helloworld_section_id/general/enable');
        $now = date('Y-m-d H:i:s');
        $deal = $this->deal_collection->create();
        $deal_sku = [];

        foreach ($deal as $deal_item) {
            $deal_sku[] = $deal_item->GetData('product');
        }
        $id = $subject->getId();
        $object_manager = \Magento\Framework\App\ObjectManager::getInstance();
        $parentId = $object_manager->create('Magento\ConfigurableProduct\Model\ResourceModel\Product\Type\Configurable')->getParentIdsByChild($id);

        if (isset($parentId[0])) {
            $product_sku = $this->collection->getById($parentId[0])->getSku();
        } else {
            $product_sku = $subject->getSku();
        }

        if (in_array($product_sku, $deal_sku)) {
            $start = $deal->getItemByColumnValue('product', $product_sku)->getData('time_start');
            $end = $deal->getItemByColumnValue('product', $product_sku)->getData('time_end');
            $status = $deal->getItemByColumnValue('product', $product_sku)->getData('status');

            if ($now <= $end && $now >= $start && $config != 0 && $status == 1) {
                return $result = $deal->getItemByColumnValue('product', $product_sku)->getData('deal_price');
            } else {
                return $result;
            }
        } else {
            return $result;
        }
    }
}
