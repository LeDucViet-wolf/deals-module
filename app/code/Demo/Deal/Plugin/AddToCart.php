<?php
namespace Demo\Deal\Plugin;

use Magento\Checkout\Model\Cart;
use Magento\Framework\Exception\LocalizedException;
use Demo\Deal\Model\ResourceModel\Post\CollectionFactory as DealCollection;
use Magento\Framework\App\Config\ScopeConfigInterface;

class AddToCart
{
    protected $DealCollection;
    protected $ScopeConfig;

    public function __construct(
        DealCollection $DealCollection,
        ScopeConfigInterface $ScopeConfig
    )
    {
        $this->DealCollection = $DealCollection;
        $this->ScopeConfig = $ScopeConfig;
    }

    public function aroundAddProduct(
        Cart $subject,
        callable $proceed,
        $productInfo,
        $requestInfo = null
    )
    {
        date_default_timezone_set('Asia/Ho_Chi_Minh');
        $config = $this->ScopeConfig->getValue('helloworld_section_id/general/enable');
        $deals = $this->DealCollection->create();
        $dealSku = [];
        foreach ($deals as $deal) {
            $dealSku[] = $deal->getData('product');
        }
        $productSku = $productInfo->getSku();

        if (isset($requestInfo['qty'])) {
            $addQty = $requestInfo['qty'];
        } else {
            $addQty = 1;
        }

        if (in_array($productSku, $dealSku)) {
            $dealQty = $deals->getItemByColumnValue('product', $productSku)->getData('deal_qty');
            $startTime = $deals->getItemByColumnValue('product', $productSku)->getData('time_start');
            $endTime = $deals->getItemByColumnValue('product', $productSku)->getData('time_end');
            $status = $deals->getItemByColumnValue('product', $productSku)->getData('status');
            $now = date('Y-m-d H:i:s');

            $items = $subject->getQuote()->getAllItems();

            $productQty = 0;
            foreach ($items as $item) {
                $product = [
                    'sku' => $item->getSku(),
                    'quantity' => $item->getQty()
                ];
                if ($product['sku'] == $productSku) {
                    $productQty = $product['quantity'];
                }
            }
            if ($now <= $endTime && $status == 1 && $now >= $startTime && $config != 0 && $dealQty < ($addQty + $productQty)) {
                throw new LocalizedException(__('The number of Deal products is limited'));
            } else {
                return $proceed($productInfo, $requestInfo);
            }
        } else {
            return $proceed($productInfo, $requestInfo);
        }
    }
}
