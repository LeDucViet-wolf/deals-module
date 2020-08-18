<?php

namespace Demo\Deal\Plugin;

use Magento\Framework\Exception\LocalizedException;
use Demo\Deal\Model\ResourceModel\Post\CollectionFactory as DealCollection;
use Magento\Framework\App\Config\ScopeConfigInterface;

class UpdateQtyToCart
{
    protected $DealCollection;
    protected $ScopeConfig;

    function __construct(
        DealCollection $DealCollection,
        ScopeConfigInterface $ScopeConfig
    )
    {
        $this->DealCollection = $DealCollection;
        $this->ScopeConfig = $ScopeConfig;
    }

    public function afterUpdateItems($subject, $result, $data)
    {
        date_default_timezone_set('Asia/Ho_Chi_Minh');
        $config = $this->ScopeConfig->getValue('helloworld_section_id/general/enable');
        $now = date('Y-m-d H:i:s');
        $deals = $this->DealCollection->create();
        $dealSku = [];

        foreach ($deals as $deal) {
            $dealSku[] = $deal->getData('product');
        }

        foreach ($data as $productId => $productInfo) {
            $product = $subject->getQuote()->getItemById($productId);
            $productSku = $product['sku'];
            $newQty = $productInfo['qty'];

            if (in_array($productSku, $dealSku)) {
                $endTime = $deals->getItemByColumnValue('product', $productSku)->getData('time_end');
                $startTime = $deals->getItemByColumnValue('product', $productSku)->getData('time_start');
                $status = $deals->getItemByColumnValue('product', $productSku)->getData('status');
                $dealQty = $deals->getItemByColumnValue('product', $productSku)->getData('deal_qty');

                if ($now <= $endTime && $status == 1 && $now >= $startTime && $config != 0 && $newQty > $dealQty) {
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
