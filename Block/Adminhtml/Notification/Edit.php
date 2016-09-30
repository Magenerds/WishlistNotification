<?php
/**
 * Magenerds\WishlistNotification\Block\Adminhtml\Notification\Edit
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
 * @subpackage Block
 * @copyright  Copyright (c) 2016 TechDivision GmbH (http://www.techdivision.com)
 * @version    ${release.version}
 * @link       http://www.techdivision.com/
 * @author     Florian Sydekum <f.sydekum@techdivision.com>
 */
namespace Magenerds\WishlistNotification\Block\Adminhtml\Notification;

use Magenerds\WishlistNotification\Model\NotificationFactory;
use Magento\Customer\Model\CustomerFactory;
use Magento\Catalog\Model\ProductFactory;
use Magento\Customer\Api\AccountManagementInterface;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Customer\Model\Address\Mapper;

/**
 * Class Edit
 * @package Magenerds\WishlistNotification\Block\Adminhtml\Notification
 */
class Edit extends \Magento\Backend\Block\Widget
{
    /**
     * @var \Magento\Customer\Model\Customer
     */
    protected $_customer;

    /**
     * @var \Magento\Customer\Model\CustomerFactory
     */
    protected $_customerFactory;

    /**
     * @var \Magento\Catalog\Model\Product
     */
    protected $_product;

    /**
     * @var \Magento\Catalog\Model\ProductFactory
     */
    protected $_productFactory;

    /**
     * @var \Magenerds\WishlistNotification\Model\Notification
     */
    protected $_notification;

    /**
     * @var \Magenerds\WishlistNotification\Model\NotificationFactory
     */
    protected $_notificationFactory;

    /**
     * Account management
     *
     * @var AccountManagementInterface
     */
    protected $_accountManagement;

    /**
     * Address helper
     *
     * @var \Magento\Customer\Helper\Address
     */
    protected $_addressHelper;

    /**
     * @var \Magento\CatalogInventory\Api\StockRegistryInterface
     */
    protected $_stockRegistry;

    /**
     * @var \Magento\CatalogInventory\Api\Data\StockItemInterface
     */
    protected $_stockItem;

    /**
     * @var \Magento\Wishlist\Model\WishlistFactory
     */
    protected $_wishlistFactory;

    /**
     * @var \Magento\Framework\Json\EncoderInterface
     */
    protected $_jsonEncoder;

    /**
     * @param \Magento\Backend\Block\Template\Context $context
     * @param array $data
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        NotificationFactory $notificationFactory,
        CustomerFactory $customerFactory,
        ProductFactory $productFactory,
        AccountManagementInterface $accountManagement,
        \Magento\Customer\Helper\Address $addressHelper,
        Mapper $addressMapper,
        \Magento\CatalogInventory\Api\StockRegistryInterface $stockRegistry,
        \Magento\Wishlist\Model\WishlistFactory $wishlistFactory,
        \Magento\Framework\Json\EncoderInterface $jsonEncoder,
        array $data = []
    ) {
        $this->_notificationFactory = $notificationFactory;
        $this->_customerFactory = $customerFactory;
        $this->_productFactory = $productFactory;
        $this->_accountManagement = $accountManagement;
        $this->_addressHelper = $addressHelper;
        $this->_addressMapper = $addressMapper;
        $this->_stockRegistry = $stockRegistry;
        $this->_wishlistFactory = $wishlistFactory;
        $this->_jsonEncoder = $jsonEncoder;

        parent::__construct($context, $data);
    }

    /**
     * @return void
     */
    protected function _construct()
    {
        parent::_construct();
        $this->setId('notification_edit');
        $this->setUseContainer(true);

        if ($id = $this->getRequest()->getParam('id')) {
            /** @var $notification \Magenerds\WishlistNotification\Model\Notification */
            $this->_notification = $this->_notificationFactory->create()->load($id);

            $customerId = $this->_notification->getData('customer_id');
            $this->_customer = $this->_customerFactory->create()->load($customerId);

            $productId = $this->_notification->getData('product_id');
            $this->_product = $this->_productFactory->create()->load($productId);

            $this->_stockItem = $this->_stockRegistry->getStockItem(
                $this->getProduct()->getId(),
                $this->getProduct()->getStore()->getWebsiteId()
            );
        }
    }

    /**
     * Add elements in layout
     *
     * @return $this
     */
    protected function _prepareLayout()
    {
        if (!$this->getRequest()->getParam('popup')) {
            if ($this->getToolbar()) {
                $this->getToolbar()->addChild(
                    'back_button',
                    'Magento\Backend\Block\Widget\Button',
                    [
                        'label' => __('Back'),
                        'title' => __('Back'),
                        'onclick' => 'setLocation(\'' . $this->getUrl(
                                'notification/*/',
                                ['store' => $this->getRequest()->getParam('store', 0)]
                            ) . '\')',
                        'class' => 'action-back'
                    ]
                );
            }
        } else {
            $this->addChild(
                'back_button',
                'Magento\Backend\Block\Widget\Button',
                ['label' => __('Close Window'), 'onclick' => 'window.close()', 'class' => 'cancel']
            );
        }

        if ($this->getToolbar()) {
            $this->getToolbar()->addChild(
                'save_button',
                'Magento\Backend\Block\Widget\Button',
                [
                    'id' => 'save-button',
                    'label' => __('Save'),
                    'class_name' => 'Magento\Backend\Block\Widget\Button',
                    'name' => 'save-button',
                    'title' => __('Save'),
                    'class' => 'save primary',
                    'data_attribute' => [
                        'mage-init' => ['button' => ['event' => 'save', 'target' => '#edit-notification']],
                    ]
                ]
            );
        }

        return parent::_prepareLayout();
    }

    /**
     * @return string
     */
    public function getSaveUrl()
    {
        return $this->getUrl('notification/*/save', ['_current' => true, 'back' => null]);
    }

    /**
     * Returns the customer.
     *
     * @return \Magento\Customer\Model\Customer
     */
    public function getCustomer()
    {
        return $this->_customer;
    }

    /**
     * Retrieve billing address html
     *
     * @return \Magento\Framework\Phrase|string
     */
    public function getBillingAddressHtml()
    {
        try {
            $address = $this->_accountManagement->getDefaultBillingAddress($this->getCustomer()->getId());
        } catch (NoSuchEntityException $e) {
            return __('The customer does not have default billing address.');
        }

        if ($address === null) {
            return __('The customer does not have default billing address.');
        }

        return $this->_addressHelper->getFormatTypeRenderer(
            'html'
        )->renderArray(
            $this->_addressMapper->toFlatArray($address)
        );
    }

    /**
     * Returns the product.
     *
     * @return \Magento\Catalog\Model\Product
     */
    public function getProduct()
    {
        return $this->_product;
    }

    /**
     * Returns the label for stock.
     *
     * @return string
     */
    public function getIsInStockLabel()
    {
        if ($this->_stockItem->getItemId()) {
            if ($this->_stockItem->getIsInStock()) {
                return __('In stock');
            } else {
                return __('Out of stock');
            }
        }
    }

    /**
     * Returns the product's qty.
     *
     * @return float
     */
    public function getQty()
    {
        if ($this->_stockItem->getItemId()) {
            return $this->_stockItem->getQty();
        }
    }

    /**
     * Returns the notification.
     *
     * @return \Magenerds\WishlistNotification\Model\Notification
     */
    public function getNotification()
    {
        return $this->_notification;
    }

    /**
     * Returns the notification's status
     *
     * @return mixed
     */
    public function getStatus()
    {
        return $this->_notification->getData('status');
    }

    /**
     * Returns the customer's wishlist.
     *
     * @return \Magento\Wishlist\Model\Wishlist
     */
    public function getWishlist()
    {
        $customerId = $this->getCustomer()->getId();
        return $this->_wishlistFactory->create()->loadByCustomerId($customerId);
    }
}