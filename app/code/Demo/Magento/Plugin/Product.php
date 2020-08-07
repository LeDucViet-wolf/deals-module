<?php
namespace Demo\Magento\Plugin;

use Magento\Framework\View\Element\Template;
use Demo\Magento\Model\ResourceModel\Post\CollectionFactory as DealCollection;
use \Magento\Catalog\Model\ProductRepository;

class Product extends Template
{
    protected $DealCollection;
    protected $registry;
    protected $collection;

    public function __construct(
        Template\Context $context,
        DealCollection $DealCollection,
        \Magento\Framework\Registry $registry,
        ProductRepository $collection
    )
    {
        $this->DealCollection = $DealCollection;
        $this->registry = $registry;
        $this->collection = $collection;
        parent::__construct($context);
    }

    public function afterGetPrice(\Magento\Catalog\Model\Product $subject, $result)
    {
        $deal = $this->DealCollection->create();
        $dealSku = [];
        foreach ($deal as $dealItem) {
            $dealSku[] = $dealItem->GetData('product');
        }

        $id = $subject->getId();
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $parentId = $objectManager->create('Magento\ConfigurableProduct\Model\ResourceModel\Product\Type\Configurable')->getParentIdsByChild($id);

        if(isset($parentId[0])){
            $productSku = $this->collection->getById($parentId[0])->getSku();

        } else {
            $productSku = $subject->getSku();
        }
        if (in_array($productSku,$dealSku)){
            return $result = $deal->getItemByColumnValue('product', $productSku)->getData('deal_price');
        } else {
            return $result;
        }
    }
}
