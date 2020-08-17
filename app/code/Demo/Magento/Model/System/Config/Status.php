<?php
namespace Demo\Magento\Model\System\Config;

use Magento\Framework\Option\ArrayInterface;

class Status implements ArrayInterface
{
    const ENABLE  = 1;
    const DISABLE = 0;

    public function toOptionArray()
    {
         return $options = [
            self::ENABLE => __('Enable'),
            self::DISABLE => __('Disable'),
        ];
    }
}
