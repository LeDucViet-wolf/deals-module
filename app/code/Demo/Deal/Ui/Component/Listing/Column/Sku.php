<?php

namespace Demo\Deal\Ui\Component\Listing\Column;

use Magento\Framework\Escaper;
use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Store\Model\System\Store as SystemStore;
use Magento\Catalog\Api\ProductRepositoryInterface;

class Sku extends \Magento\Store\Ui\Component\Listing\Column\Store
{
    protected $productRepository;

    public function __construct(
        ContextInterface $context,
        UiComponentFactory $uiComponentFactory,
        SystemStore $systemStore,
        Escaper $escaper,
        ProductRepositoryInterface $productRepository,
        array $components = [],
        array $data = [],
        $store_key = 'store_id'
    )
    {
        $this->productRepository = $productRepository;
        parent::__construct($context, $uiComponentFactory, $systemStore, $escaper, $components, $data, $store_key);
    }


    public function prepareDataSource(array $dataSource)
    {
        if (isset($dataSource['data']['items'])) {
            foreach ($dataSource['data']['items'] as &$item) {
                $product = $this->productRepository->get($item['product']);
                $item['sku'] = $product->getName();
            }
        }
        return $dataSource;
    }
}
