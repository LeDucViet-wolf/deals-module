<?php

namespace Demo\Deal\Observer;

use Magento\Framework\Event\Observer;
use Magento\Checkout\Model\Cart;
use Magento\Framework\Exception\LocalizedException;
use Demo\Deal\Model\ResourceModel\Post\CollectionFactory as deal_collection;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Quote\Model\Quote\Item;

class CheckoutPriceInCart implements \Magento\Framework\Event\ObserverInterface
{
    protected $deal_collection;
    protected $scope_config;
    protected $collection;
    protected $cart;
    protected $product_factory;

    public function __construct(
        deal_collection $deal_collection,
        ScopeConfigInterface $scope_config,
        Cart $cart,
        \Magento\Catalog\Model\ProductFactory $product_factory
    )
    {
        $this->deal_collection = $deal_collection;
        $this->scope_config = $scope_config;
        $this->product_factory = $product_factory;
        $this->cart = $cart;
    }

    public function execute(Observer $observer)
    {
        date_default_timezone_set('Asia/Ho_Chi_Minh');
        $config = $this->scope_config->getValue('helloworld_section_id/general/enable');
        $now = date('Y-m-d H:i:s');
        $product = $this->product_factory->create();
        $deals = $this->deal_collection->create();
        $deal_sku = [];

        foreach ($deals as $deal) {
            $deal_sku[] = $deal->GetData('product');
        }

        $cart_product = $this->cart->getQuote()->getAllItems();

        foreach ($cart_product as $value) {
            $product_sku = $value->getSku();
            $product_price = $product->loadByAttribute('sku',$product_sku)->GetPrice();

            if (in_array($product_sku,$deal_sku)){
                $start = $deals->getItemByColumnValue('product', $product_sku)->getData('time_start');
                $end = $deals->getItemByColumnValue('product', $product_sku)->getData('time_end');
                $status = $deals->getItemByColumnValue('product', $product_sku)->getData('status');
                $deal_price = $deals->getItemByColumnValue('product',$product_sku)->getData('deal_price');

                if ($now <= $end || $status == 1 || $now >= $start || $config != 0 ) {
                    $value->setCustomPrice($product_price);
                    $value->setOrginalCustomPrice($product_price);
                } else{
                    $value->setCustomPrice($deal_price);
                    $value->setOrginalCustomPrice($deal_price);
                }

                $value->getProduct()->setIsSuperModel(true);
                $value->save();
            }
        }
        return $this->cart->getQuote()->collectTotals()->save();
    }
}
