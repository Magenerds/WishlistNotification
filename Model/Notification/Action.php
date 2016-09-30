<?php
/**
 * Magenerds\WishlistNotification\Model\Notification\Action
 *
 * Copyright (c) 2016 TechDivision GmbH
 * All rights reserved
 *
 * This product includes proprietary software developed at TechDivision GmbH, Germany
 * For more information see http://www.techdivision.com/
 *
 * To obtain a valid license for using this software please contact us at
 * license@techdivision.com
 */

/**
 * @category   Magenerds
 * @package    Magenerds_WishlistNotification
 * @subpackage Model
 * @copyright  Copyright (c) 2016 TechDivision GmbH (http://www.techdivision.com)
 * @version    ${release.version}
 * @link       http://www.techdivision.com/
 * @author     Florian Sydekum <f.sydekum@techdivision.com>
 */
namespace Magenerds\WishlistNotification\Model\Notification;

/**
 * Class Action
 * @package Magenerds\WishlistNotification\Model\Notification
 */
class Action extends \Magento\Framework\Model\AbstractModel
{
    /**
     * Initialize resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('Magenerds\WishlistNotification\Model\Resource\Notification');
    }

    /**
     * Retrieve resource instance wrapper
     *
     * @return \Magenerds\WishlistNotification\Model\Resource\Notification
     */
    protected function _getResource()
    {
        return parent::_getResource();
    }

    /**
     * Update attribute values for entity list per store
     *
     * @param array $notificationIds
     * @param array $attrData
     * @return $this
     */
    public function updateAttributes($notificationIds, $attrData)
    {
        $this->_getResource()->updateAttributes($notificationIds, $attrData);

        return $this;
    }
}