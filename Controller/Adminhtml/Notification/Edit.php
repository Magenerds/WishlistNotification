<?php
/**
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
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

/**
 * Class Edit
 * @package Magenerds\WishlistNotification\Controller\Adminhtml\Notification
 */
class Edit extends \Magento\Backend\App\Action
{
    /**
     * @var \Magenerds\WishlistNotification\Model\NotificationFactory
     */
    protected $_notificationFactory;

    /**
     * @var \Magenerds\WishlistNotification\Logger\Logger
     */
    protected $_logger;

    /**
     * @var \Magento\Framework\View\Result\PageFactory
     */
    protected $_resultPageFactory;

    /**
     * @param \Magento\Backend\App\Action\Context $context
     * @param \Magento\Catalog\Controller\Adminhtml\Product\Builder $productBuilder
     * @param \Magento\Framework\View\Result\PageFactory $resultPageFactory
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        \Magenerds\WishlistNotification\Model\NotificationFactory $notificationFactory,
        \Magenerds\WishlistNotification\Logger\Logger $logger
    ) {
        parent::__construct($context);
        $this->_resultPageFactory = $resultPageFactory;
        $this->_notificationFactory = $notificationFactory;
        $this->_logger = $logger;
    }

    /**
     * Check for is allowed
     *
     * @return boolean
     */
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Magenerds_WishlistNotification::edit');
    }

    /**
     * Product edit form
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        $notificationId = (int) $this->getRequest()->getParam('id');

        /** @var $notification \Magenerds\WishlistNotification\Model\Notification */
        $notification = $this->_notificationFactory->create();

        if ($notificationId) {
            try {
                $notification->load($notificationId);
            } catch (\Exception $e) {
                $this->_logger->critical($e);
            }
        }

        if ($notificationId && !$notification->getId()) {
            $this->messageManager->addError(__('This notification no longer exists.'));
            /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
            $resultRedirect = $this->resultRedirectFactory->create();
            return $resultRedirect->setPath('notification/*/');
        }

        /** @var \Magento\Backend\Model\View\Result\Page $resultPage */
        $resultPage = $this->_resultPageFactory->create();
        $resultPage->setActiveMenu('Magenerds_WishlistNotification::notification');
        $resultPage->getConfig()->getTitle()->prepend(__('Notification'));
        $resultPage->getConfig()->getTitle()->prepend($notification->getName());

        return $resultPage;
    }
}