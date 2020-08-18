<?php

namespace Demo\Deal\Plugin;

use Magento\Framework\Exception\LocalizedException;
use Demo\Deal\Model\ResourceModel\Post\CollectionFactory as deal_collection;
use Magento\Framework\App\Config\ScopeConfigInterface;

class UpdateQtyToCart
{
    protected $deal_collection;
    protected $scope_config;

    function __construct(
        deal_collection $deal_collection,
        ScopeConfigInterface $scope_config
    )
    {
        $this->deal_collection = $deal_collection;
        $this->scope_config = $scope_config;
    }

    public function afterUpdateItems($subject, $result, $data)
    {
        date_default_timezone_set('Asia/Ho_Chi_Minh');
        $config = $this->scope_config->getValue('helloworld_section_id/general/enable');
        $now = date('Y-m-d H:i:s');
        $deals = $this->deal_collection->create();
        $deal_sku = [];

        foreach ($deals as $deal) {
            $deal_sku[] = $deal->getData('product');
        }

        foreach ($data as $product_id => $product_info) {
            $product = $subject->getQuote()->getItemById($product_id);
            $product_sku = $product['sku'];
            $newQty = $product_info['qty'];

            if (in_array($product_sku, $deal_sku)) {
                $end = $deals->getItemByColumnValue('product', $product_sku)->getData('time_end');
                $start = $deals->getItemByColumnValue('product', $product_sku)->getData('time_start');
                $status = $deals->getItemByColumnValue('product', $product_sku)->getData('status');
                $deal_qty = $deals->getItemByColumnValue('product', $product_sku)->getData('deal_qty');

                if ($now <= $end && $status == 1 && $now >= $start && $config != 0 && $newQty > $deal_qty) {
                    throw new LocalizedException(__('You can not update cart because deal product quantity limited'));
                } else {
                    $product->setQty($newQty);
                }
            } else {
                $product->setQty($newQty);
            }
        }
        return $result;
    }
}
