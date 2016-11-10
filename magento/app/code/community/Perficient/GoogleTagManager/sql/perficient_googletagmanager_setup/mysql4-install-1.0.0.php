<?php 
/**
 * PERFICIENT INDIA PVT LTD.
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the EULA
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://shop.perficient.com/license-community.txt
 *
 * =================================================================
 *                 MAGENTO EDITION USAGE NOTICE
 * This package designed for Magento COMMUNITY edition
 * =================================================================
 * Perficient does not guarantee correct work of this extension
 * on any other Magento edition except Magento COMMUNITY edition.
 * Perficient does not provide extension support in case of
 * incorrect edition usage.
 * =================================================================
 *
 * PHP version 5.x
 *
 * @category  Perficient
 * @package   Perficient_GoogleTagManager
 * @author    Perficient <mukesh.soni@perficient.com>
 * @copyright 2016 PERFICIENT INDIA PVT LTD
 * @license   OSL http://shop.perficient.com/license-community.txt
 * @version   GIT:0.0.2.1
 * @link      http://shop.perficient.com/extensions/google-tag-manager-by-perficient
 */
$installer = $this;
$installer->startSetup();
$table = $installer->getConnection()->newTable($installer->getTable('perficient_googletagmanager/sales'))
        ->addColumn(
            'analytics_id', 
            Varien_Db_Ddl_Table::TYPE_INTEGER, 
            '10', 
            array(
                'unsigned' => true,
                'nullable' => false,
                'primary' => true,
                'identity' => true,
            ), 
            'Analytics ID'
        )
        ->addColumn(
            'order_id', 
            Varien_Db_Ddl_Table::TYPE_INTEGER, 
            '10', 
            array(
                'nullable' => false,
                'unsigned' => true,
                'nullable' => false,
            ), 
            'Order Id'
        )
        ->addColumn(
            'order_increment_id', 
            Varien_Db_Ddl_Table::TYPE_VARCHAR, 
            '50', 
            array(
                'nullable' => false,
                'unsigned' => true,
                'nullable' => false,
            ), 
            'Order Increment Id'
        )
        ->addColumn(
            'customer_email', 
            Varien_Db_Ddl_Table::TYPE_VARCHAR, 
            '255', 
            array(
                'nullable' => true,
            ), 
            'Customer Email'
        )
        ->addColumn(
            'customer_name', 
            Varien_Db_Ddl_Table::TYPE_VARCHAR, 
            '255', 
            array(
                'nullable' => true,
            ), 
            'Customer Name'
        )
        ->addColumn(
            'remote_ip', 
            Varien_Db_Ddl_Table::TYPE_VARCHAR, 
            '255', 
            array(
                'nullable' => true,
            ), 
            'Customer IP'
        )
        ->addColumn(
            'utma', 
            Varien_Db_Ddl_Table::TYPE_VARCHAR, 
            '255', 
            array(
                'nullable' => true,
            ), 
            '__utma'
        )
        ->addColumn(
            'utmb', 
            Varien_Db_Ddl_Table::TYPE_VARCHAR, 
            '255', 
            array(
                'nullable' => true,
            ), 
            '__utmb'
        )
        ->addColumn(
            'utmc', 
            Varien_Db_Ddl_Table::TYPE_VARCHAR, 
            '255', 
            array(
                'nullable' => true,
            ), 
            '__utmc'
        )
        ->addColumn(
            'utmz', 
            Varien_Db_Ddl_Table::TYPE_VARCHAR, 
            '255', 
            array(
                'nullable' => true,
            ), 
            '__utmz'
        )
        ->addColumn(
            'utmv', 
            Varien_Db_Ddl_Table::TYPE_VARCHAR, 
            '255', 
            array(
                'nullable' => true,
            ), 
            '__utmv'
        )
        ->addColumn(
            'additionalcookies', 
            Varien_Db_Ddl_Table::TYPE_TEXT, 
            null, 
            array(
                'nullable' => true,
            ), 
            'Additional Cookies'
        )
        ->addIndex(
            $installer->getIdxName(
                'perficient_googletagmanager/sales', 
                array('order_id'), true
            ),
            array('order_id'), 
            array('type' => 'unique')
        )
        ->setComment('Perficient order analytics table');
$installer->getConnection()->createTable($table);

$installer->endSetup();