<?php

namespace Demo\Deal\Model\System\Config;

use Demo\Deal\Model\ResourceModel\Post\CollectionFactory as deal_factory;
use Magento\Catalog\Model\ProductRepository as product_repository;
use Magento\Catalog\Model\ResourceModel\Product\CollectionFactory as product_factory;
use Magento\Framework\App\ObjectManager;
use Magento\Framework\Option\ArrayInterface;

class Product implements ArrayInterface
{
    protected $options;
    protected $product_repository;
    protected $product_factory;
    protected $deal_factory;

    public function __construct(
        product_factory $product_factory,
        product_repository $product_repository,
        deal_factory $deal_factory
    )
    {
        $this->product_factory = $product_factory;
        $this->product_repository = $product_repository;
        $this->deal_factory = $deal_factory;
    }

    public function getProductBySku($sku)
    {
        return $this->product_repository->get($sku);
    }

    public function toOptionArray()
    {
        $url_interface = ObjectManager::getInstance()->get('Magento\Framework\UrlInterface');
        $url = $url_interface->getCurrentUrl();
        $products = $this->product_factory->create();
        $deals = $this->deal_factory->create();
        $deal_id = [];
        foreach ($deals as $deal) {
            $deal_id[] = $deal->getData('product');
        }
        $options = [];
        if ($products->getSize()) {
            foreach ($products as $product) {
                $options = array_merge($options, [
                    $product->getData('sku') => __($this->getProductBySku($product->getData('sku'))->getName())
                ]);
                if (in_array($product->getData('sku'), $deal_id) && (strpos($url, '/max/post/create') !== false)) {
                    unset($options[$product->getData('sku')]);
                }
            }
        }
        return $options;
    }

}
