<?php
namespace Demo\Magento\Block\Frontend;

use Magento\Framework\View\Element\Template;
use Demo\Magento\Model\ResourceModel\Post\CollectionFactory as DealCollection;
use Demo\Magento\Model\PostFactory;
use Magento\Catalog\Model\ResourceModel\Product\CollectionFactory as CoreCollection;

class TimeoutButton extends Template
{
    protected $DealCollection;
    protected $CoreCollection;
    protected $ProductRepository;
    protected $registry;

    public function __construct(
        Template\Context $context,
        PostFactory $PostFactory,
        DealCollection $DealCollection,
        CoreCollection $CoreCollection,
        \Magento\Framework\Registry $registry
    )
    {
        $this->CoreCollection = $CoreCollection;
        $this->DealCollection = $DealCollection;
        $this->registry = $registry;
        parent::__construct($context);
    }

    public function GetProduct()
    {
        return $this->DealCollection->create();
    }

    public function GetDeal($sku, $key)
    {
        $deal = $this->DealCollection->create();
        $dealSku = [];
        foreach ($deal as $dealItem) {
            $dealSku[] = $dealItem->GetData('product');
        }
        if (in_array($sku,$dealSku)){
            return $deal->getItemByColumnValue('product', $sku)->getData($key);
        } else {
            return null;
        }
    }

    public function GetCurrentSku(){
        return $this->registry->registry('current_product');
    }
//    public function GetProductBySku($sku)
//    {
//        return $this->ProductRepository->get($sku);
//    }
//    public function getId()
//    {
//        return $this->_request->getParams();
//    }
//    public function get($id)
//    {
//        $customModel = $this->_postsLoader->create();
//        $customModel->load($id);
//        if (!$customModel->getId()) {
//            echo 'CustomModel with id "%1" does not exist.';
//            echo $id;
//            throw new NoSuchEntityException(__('CustomModel with id "%1" does not exist.', $id));
//        }
//        return $customModel;
//    }
}

