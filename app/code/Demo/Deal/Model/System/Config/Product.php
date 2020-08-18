<?php

namespace Demo\Deal\Model\System\Config;

use Demo\Deal\Model\ResourceModel\Post\CollectionFactory as DealsCollection;
use Magento\Catalog\Model\ProductRepository;
use Magento\Catalog\Model\ResourceModel\Product\CollectionFactory as ProductCollection;
use Magento\Framework\App\ObjectManager;
use Magento\Framework\Option\ArrayInterface;

class Product implements ArrayInterface
{
    protected $options;
    protected $respository;
    protected $productFactory;
    protected $dealFactory;

    public function __construct(
        ProductCollection $productFactory,
        ProductRepository $productRepository,
        DealsCollection $dealFactory
    )
    {
        $this->productFactory = $productFactory;
        $this->respository = $productRepository;
        $this->dealFactory = $dealFactory;
    }

    public function getProductBySku($sku)
    {
        return $this->respository->get($sku);
    }

    public function toOptionArray()
    {
        $urlInterface = ObjectManager::getInstance()->get('Magento\Framework\UrlInterface');
        $url = $urlInterface->getCurrentUrl();
        $products = $this->productFactory->create();
        $deals = $this->dealFactory->create();
        $dealId = [];
        foreach ($deals as $deal) {
            $dealId[] = $deal->getData('product');
        }
        $options = [];
        if ($products->getSize()) {
            foreach ($products as $product) {
                $options = array_merge($options, [
                    $product->getData('sku') => __($this->getProductBySku($product->getData('sku'))->getName())
                ]);
                if (in_array($product->getData('sku'), $dealId) && (strpos($url, '/max/post/create') !== false)) {
                    unset($options[$product->getData('sku')]);
                }
            }
        }
        return $options;
    }

}
