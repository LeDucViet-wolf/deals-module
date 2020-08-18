<?php
namespace Demo\Deal\Plugin;

use Magento\Checkout\Model\Cart;
use Magento\Framework\Exception\LocalizedException;
use Demo\Deal\Model\ResourceModel\Post\CollectionFactory as deal_collection;
use Magento\Framework\App\Config\ScopeConfigInterface;

class AddToCart
{
    protected $deal_collection;
    protected $scope_config;

    public function __construct(
        deal_collection $deal_collection,
        ScopeConfigInterface $scope_config
    )
    {
        $this->deal_collection = $deal_collection;
        $this->scope_config = $scope_config;
    }

    public function beforeAddProduct(
        Cart $subject,
        $productInfo,
        $requestInfo = null
    )
    {
        date_default_timezone_set('Asia/Ho_Chi_Minh');
        $config = $this->scope_config->getValue('helloworld_section_id/general/enable');
        $deals = $this->deal_collection->create();
        $deal_sku = [];
        foreach ($deals as $deal) {
            $deal_sku[] = $deal->getData('product');
        }
        $product_sku = $productInfo->getSku();

        if (isset($requestInfo['qty'])) {
            $add_qty = $requestInfo['qty'];
        } else {
            $add_qty = 1;
        }

        if (in_array($product_sku, $deal_sku)) {
            $deal_qty = $deals->getItemByColumnValue('product', $product_sku)->getData('deal_qty');
            $start_time = $deals->getItemByColumnValue('product', $product_sku)->getData('time_start');
            $end_time = $deals->getItemByColumnValue('product', $product_sku)->getData('time_end');
            $status = $deals->getItemByColumnValue('product', $product_sku)->getData('status');
            $now = date('Y-m-d H:i:s');

            $items = $subject->getQuote()->getAllItems();

            $product_qty = 0;
            foreach ($items as $item) {
                $product = [
                    'sku' => $item->getSku(),
                    'quantity' => $item->getQty()
                ];
                if ($product['sku'] == $product_sku) {
                    $product_qty = $product['quantity'];
                }
            }
            if ($now <= $end_time && $status == 1 && $now >= $start_time && $config != 0 && $deal_qty < ($add_qty + $product_qty)) {
                throw new LocalizedException(__('The number of Deal products is limited'));
            } else {
                return array($productInfo, $requestInfo);
            }
        } else {
            return array($productInfo, $requestInfo);
        }
    }
}
