<?php

namespace Demo\Deal\Model;

use Magento\Framework\Model\AbstractModel;

class Post extends AbstractModel
{
    protected function _construct()
    {
        $this->_init('Demo\Deal\Model\ResourceModel\Post');
    }
}
