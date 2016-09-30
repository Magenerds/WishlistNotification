<?php
/**
 * Magenerds\WishlistNotification\Controller\Adminhtml\Notification\MassStatus
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
 * @subpackage Controller
 * @copyright  Copyright (c) 2016 TechDivision GmbH (http://www.techdivision.com)
 * @version    ${release.version}
 * @link       http://www.techdivision.com/
 * @author     Florian Sydekum <f.sydekum@techdivision.com>
 */
namespace Magenerds\WishlistNotification\Controller\Adminhtml\Notification;

use Magento\Backend\App\Action;
use Magento\Catalog\Controller\Adminhtml\Product;
use Magento\Framework\Controller\ResultFactory;

/**
 * Class MassStatus
 * @package Magenerds\WishlistNotification\Controller\Adminhtml\Notification
 */
class MassStatus extends \Magento\Backend\App\Action
{
    /**
     * @var \Magenerds\WishlistNotification\Logger\Logger
     */
    protected $_logger;

    /**
     * @var \Magenerds\WishlistNotification\Model\Notification\Action
     */
    protected $_action;

    /**
     * @param \Magento\Backend\App\Action\Context $context
     * @param \Magento\Catalog\Api\CategoryRepositoryInterface $categoryRepository
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magenerds\WishlistNotification\Logger\Logger $logger,
        \Magenerds\WishlistNotification\Model\Notification\Action $action
    ) {
        parent::__construct($context);
        $this->_logger = $logger;
        $this->_action = $action;
    }

    /**
     * Update notification status action
     *
     * @return \Magento\Backend\Model\View\Result\Redirect
     */
    public function execute()
    {
        $notificationIds = (array) $this->getRequest()->getParam('selected');
        $status = (int) $this->getRequest()->getParam('status');

        try {
            $this->_action->updateAttributes($notificationIds, ['status' => $status]);
            $this->messageManager->addSuccess(__('A total of %1 record(s) have been updated.', count($notificationIds)));
        } catch (\Magento\Framework\Exception\LocalizedException $e) {
            $this->_logger->critical($e);
            $this->messageManager->addError($e->getMessage());
        } catch (\Exception $e) {
            $this->_logger->critical($e);
            $this->_getSession()->addException($e, __('Something went wrong while updating the notification(s) status.'));
        }

        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
        return $resultRedirect->setPath('notification/*/', ['_current' => true]);
    }

    /**
     * Check for is allowed
     *
     * @return boolean
     */
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Magenerds_WishlistNotification::save');
    }
}