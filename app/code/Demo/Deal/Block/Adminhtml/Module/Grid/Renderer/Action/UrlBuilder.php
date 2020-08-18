<?php

namespace Demo\Deal\Block\Adminhtml\Module\Grid\Renderer\Action;

use Magento\Framework\UrlInterface;

class UrlBuilder
{
    protected $frontend_url_builder;

    public function __construct(
        UrlInterface $frontend_url_builder
    )
    {
        $this->frontend_url_builder = $frontend_url_builder;
    }

    public function getUrl(
        $route_path,
        $scope,
        $store
    )
    {
        $this->frontend_url_builder->setScope($scope);
        return $this->frontend_url_builder->getUrl($route_path, ['_current' => false, '_query' => '___store=' . $store]);
    }
}
