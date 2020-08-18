<?php

namespace Demo\Deal\Block\Adminhtml\Module\Grid\Renderer\Action;

use Magento\Framework\UrlInterface;

class UrlBuilder
{
    protected $frontendUrlBuilder;

    public function __construct(
        UrlInterface $frontendUrlBuilder
    )
    {
        $this->frontendUrlBuilder = $frontendUrlBuilder;
    }

    public function getUrl(
        $routePath,
        $scope,
        $store
    )
    {
        $this->frontendUrlBuilder->setScope($scope);
        return $this->frontendUrlBuilder->getUrl($routePath, ['_current' => false, '_query' => '___store=' . $store]);
    }
}
