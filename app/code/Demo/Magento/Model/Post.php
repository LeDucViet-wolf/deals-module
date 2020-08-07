<?php

namespace Demo\Magento\Model;

use Magento\Framework\Model\AbstractModel;

class Post extends AbstractModel
{
    protected function _construct()
    {
        $this->_init('Demo\Magento\Model\ResourceModel\Post');
    }
}
