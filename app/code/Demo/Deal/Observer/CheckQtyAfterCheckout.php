<?php

namespace Demo\Deal\Observer;

use Demo\Deal\Model\ResourceModel\Post\CollectionFactory;
use Demo\Deal\Model\PostFactory;
use Magento\Framework\Event\Observer;
use Magento\Framework\App\Config\ScopeConfigInterface;

class CheckQtyAfterCheckout implements \Magento\Framework\Event\ObserverInterface
{
    protected $deal_factory;
    protected $deal_collection;
    protected $scope_config;

    public function __construct(
        CollectionFactory $deal_collection,
        PostFactory $deal_factory,
        ScopeConfigInterface $scope_config

    )
    {
        $this->deal_collection = $deal_collection;
        $this->deal_factory = $deal_factory;
        $this->scope_config = $scope_config;
    }

    public function execute(Observer $observer)
    {
        date_default_timezone_set('Asia/Ho_Chi_Minh');
        $now = date('Y-m-d H:i:s');
        $config = $this->scope_config->getValue('helloworld_section_id/general/enable');
        $deals = $this->deal_collection->create();
        $deal_sku = [];
        $values = $observer->getEvent()->getOrder()->getAllItems();

        foreach ($deals as $deal) {
            $deal_sku[] = $deal->getData('product');
        }

        foreach ($values as $value) {
            $value_sku = $value->getSku();
            $value_qty = $value->getQtyOrdered();
            $start = $deals->getItemByColumnValue('product',$value_sku)->getData('time_start');
            $end = $deals->getItemByColumnValue('product',$value_sku)->getData('time_end');
            $status = $deals->getItemByColumnValue('product',$value_sku)->getData('status');
            $deal_id = $deals->getItemByColumnValue('product',$value_sku)->getData('id');
            $deal_qty = $deals->getItemByColumnValue('product',$value_sku)->getData('deal_qty');
            $deal = $this->deal_factory->create()->load($deal_id);
            $deal_qty = $deal_qty - $value_qty;

            if (in_array($value_sku,$deal_sku)){
                if ($start <= $now && $now <= $end && $status == 1 && $config == 1) {
                    $deal->setData('deal_qty',$deal_qty);
                    $deal->save();
                }
            }
        }
        return $this;
    }
}
