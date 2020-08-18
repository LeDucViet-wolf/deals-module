<?php

namespace Demo\Deal\Model\ResourceModel\Post;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

class Collection extends AbstractCollection
{
    protected $_idFieldName = 'id';

    protected function _construct()
    {
        $this->_init(
            'Demo\Deal\Model\Post',
            'Demo\Deal\Model\ResourceModel\Post'
        );
    }
}
