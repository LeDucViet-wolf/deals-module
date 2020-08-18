<?php

namespace Demo\Deal\Ui\Component\Listing\Column;

use Demo\Deal\Block\Adminhtml\Module\Grid\Renderer\Action\UrlBuilder;
use Magento\Framework\UrlInterface;
use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Ui\Component\Listing\Columns\Column;

class Actions extends Column
{
    const URL_PATH_EDIT = 'max/post/edit';
    const URL_PATH_DELETE = 'max/post/delete';

    protected $actionUrlBuilder;
    protected $urlBuilder;

    public function __construct(
        ContextInterface $context,
        UiComponentFactory $uiComponentFactory,
        UrlBuilder $actionUrlBuilder,
        UrlInterface $urlBuilder,
        array $components = [],
        array $data = []
    )
    {
        $this->urlBuilder = $urlBuilder;
        $this->actionUrlBuilder = $actionUrlBuilder;
        parent::__construct($context, $uiComponentFactory, $components, $data);
    }

    public function prepareDataSource(array $dataSource)
    {
        if (isset($dataSource['data']['items'])) {
            foreach ($dataSource['data']['items'] as & $item) {
                $name = $this->getData('name');
                if (isset($item['id'])) {
                    $item[$name]['edit'] = ['href' => $this->urlBuilder->getUrl(self::URL_PATH_EDIT, ['id' => $item['id']]), 'label' => __('Edit')];
                    $item[$name]['delete'] = ['href' => $this->urlBuilder->getUrl(self::URL_PATH_DELETE, ['id' => $item['id']]), 'label' => __('Delete')];
                }
            }
        }
        return $dataSource;
    }
}
