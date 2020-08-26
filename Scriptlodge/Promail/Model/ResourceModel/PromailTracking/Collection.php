<?php

namespace Scriptlodge\Promail\Model\ResourceModel\PromailTracking;


/**
 * PromailTracking Collection
 *
 * @author  Magento Core Team <khasru96@gamail.com>
 */

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
    /**
     * Initialize resource collection
     *
     * @return void
     */
    public function _construct()
    {
        $this->_init('Scriptlodge\Promail\Model\PromailTracking', 'Scriptlodge\Promail\Model\ResourceModel\PromailTracking');
    }
}
