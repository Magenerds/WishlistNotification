<?php
/**
 * Magenerds\WishlistNotification\Setup\InstallSchema
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
 * @subpackage Setup
 * @copyright  Copyright (c) 2016 TechDivision GmbH (http://www.techdivision.com)
 * @version    ${release.version}
 * @link       http://www.techdivision.com/
 * @author     Florian Sydekum <f.sydekum@techdivision.com>
 */
namespace Magenerds\WishlistNotification\Setup;

use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;

/**
 * Class InstallSchema
 * @package Magenerds\WishlistNotification\Setup
 */
class InstallSchema implements InstallSchemaInterface
{
    /**
     * Install script.
     *
     * @param SchemaSetupInterface $setup
     * @param ModuleContextInterface $context
     */
    public function install(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $installer = $setup;
        $installer->startSetup();
        $table = $installer->getConnection()->newTable(
            $installer->getTable('magenerds_wishlistnotification_notification')
        )->addColumn(
                'id',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                null,
                array('identity' => true, 'nullable' => false, 'primary' => true),
                'Id'
            )->addColumn(
                'customer_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                null,
                array('nullable' => false),
                'Customer Id'
            )->addColumn(
                'customer_mail',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                '255',
                array(),
                'Customer mail'
            )->addColumn(
                'customer_name',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                '255',
                array(),
                'Customer name'
            )->addColumn(
                'sku',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                '255',
                array(),
                'Sku'
            )->addColumn(
                'price',
                \Magento\Framework\DB\Ddl\Table::TYPE_NUMERIC,
                null,
                array('precision' => 15, 'scale' => 6),
                'Price'
            )->addColumn(
                'special_price',
                \Magento\Framework\DB\Ddl\Table::TYPE_NUMERIC,
                null,
                array('precision' => 15, 'scale' => 6),
                'Special price'
            )->addColumn(
                'image_url',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                '2048',
                array(),
                'Image url'
            )->addColumn(
                'product_name',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                '255',
                array(),
                'Product name'
            )->addColumn(
                'product_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                null,
                array('nullable' => false),
                'Product Id'
            )->addColumn(
                'added_at',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                '255',
                array(),
                'Added at'
            )->addColumn(
                'status',
                \Magento\Framework\DB\Ddl\Table::TYPE_BOOLEAN,
                null,
                ['nullable' => false, 'default' => 0],
                'Status'
            );
        $installer->getConnection()->createTable($table);
        $installer->endSetup();
    }
}