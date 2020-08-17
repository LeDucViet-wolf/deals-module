<?php

namespace Demo\Magento\Observer;

use Magento\Framework\Event\Observer;
use Magento\Checkout\Model\Cart;
use Magento\Framework\Exception\LocalizedException;
use Demo\Magento\Model\ResourceModel\Post\CollectionFactory as DealCollection;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Quote\Model\Quote\Item;

class CheckoutPriceInCart implements \Magento\Framework\Event\ObserverInterface
{
    protected $DealCollection;
    protected $ScopeConfig;
    protected $Collection;
    protected $Cart;
    protected $ProductFactory;

    public function __construct(
        DealCollection $DealCollection,
        ScopeConfigInterface $ScopeConfig,
        Cart $Cart,
        \Magento\Catalog\Model\ProductFactory $ProductFactory
    )
    {
        $this->DealCollection = $DealCollection;
        $this->ScopeConfig = $ScopeConfig;
        $this->ProductFactory = $ProductFactory;
        $this->Cart = $Cart;
    }

    public function execute(Observer $observer)
    {
        date_default_timezone_set('Asia/Ho_Chi_Minh');
        $config = $this->ScopeConfig->getValue('helloworld_section_id/general/enable');
        $now = date('Y-m-d H:i:s');
        $product = $this->ProductFactory->create();
        $deals = $this->DealCollection->create();
        $dealSku = [];

        foreach ($deals as $deal) {
            $dealSku[] = $deal->GetData('product');
        }

        $cart_product = $this->Cart->getQuote()->getAllItems();

        foreach ($cart_product as $value) {
            $productSku = $value->getSku();
            $productPrice = $product->loadByAttribute('sku',$productSku)->GetPrice();

            if (in_array($productSku,$dealSku)){
                $start = $deals->getItemByColumnValue('product', $productSku)->getData('time_start');
                $end = $deals->getItemByColumnValue('product', $productSku)->getData('time_end');
                $status = $deals->getItemByColumnValue('product', $productSku)->getData('status');
                $dealPrice = $deals->getItemByColumnValue('product',$productSku)->getData('deal_price');

                if ($now <= $end || $status == 1 || $now >= $start || $config != 0 ) {
                    $value->setCustomPrice($productPrice);
                    $value->setOrginalCustomPrice($productPrice);
                } else{
                    $value->setCustomPrice($dealPrice);
                    $value->setOrginalCustomPrice($dealPrice);
                }

                $value->getProduct()->setIsSuperModel(true);
                $value->save();
            }
        }
        $this->Cart->getQuote()->collectTotals()->save();
        return $this;
    }
}
