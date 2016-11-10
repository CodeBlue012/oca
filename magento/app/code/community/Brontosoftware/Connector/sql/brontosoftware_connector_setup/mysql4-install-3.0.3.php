<?php

$installer = $this;
$installer->startSetup();

$registrations = $installer->getTable('brontosoftware_connector/registration');
try {
    $table = $installer->getConnection()->newTable($registrations);
    $table->addColumn(
        'entity_id',
        Varien_Db_Ddl_Table::TYPE_INTEGER,
        11,
        array(
            'unsigned' => true,
            'nullable' => false,
            'auto_increment' => true,
            'primary' => true,
        )
    );
    $table->addColumn(
        'name',
        Varien_Db_Ddl_Table::TYPE_VARCHAR,
        255,
        array(
            'nullable' => false,
            'default' => 'Platform Registration',
        )
    );
    $table->addColumn(
        'environment',
        Varien_Db_Ddl_Table::TYPE_VARCHAR,
        50,
        array(
            'nullable' => false,
            'default' => 'Development',
        )
    );
    $table->addColumn(
        'connector_key',
        Varien_Db_Ddl_Table::TYPE_VARCHAR,
        255,
        array(
            'nullable' => false
        )
    );
    $table->addColumn(
        'scope',
        Varien_Db_Ddl_Table::TYPE_VARCHAR,
        8,
        array(
            'nullable' => false,
            'default' => 'default'
        )
    );
    $table->addColumn(
        'scope_id',
        Varien_Db_Ddl_Table::TYPE_INTEGER,
        11,
        array(
            'unsigned' => true,
            'nullable' => false,
            'default' => 0
        )
    );
    $table->addColumn(
        'scope_code',
        Varien_Db_Ddl_Table::TYPE_VARCHAR,
        32,
        array(
            'nullable' => false,
            'default' => '',
        )
    );
    $table->addColumn(
        'is_active',
        Varien_Db_Ddl_Table::TYPE_SMALLINT,
        1,
        array(
            'unsigned' => true,
            'nullable' => false,
            'default' => 0,
        )
    );
    $table->addColumn(
        'is_protected',
        Varien_Db_Ddl_Table::TYPE_SMALLINT,
        1,
        array(
            'unsigned' => true,
            'nullable' => false,
            'default' => 0,
        )
    );
    $table->addColumn(
        'username',
        Varien_Db_Ddl_Table::TYPE_VARCHAR,
        255,
        array(
            'default' => ''
        )
    );
    $table->addColumn(
        'password',
        Varien_Db_Ddl_Table::TYPE_VARCHAR,
        255,
        array(
            'default' => ''
        )
    );
    $table->addColumn(
        'updated_at',
        Varien_Db_Ddl_Table::TYPE_TIMESTAMP,
        null,
        array(
            'nullable' => false,
            'default' => Varien_Db_Ddl_Table::TIMESTAMP_INIT
        )
    );
    $table->addIndex(
        'IDX_BRONTO_REGISTRATION_ADD',
        array(
            'is_active'
        )
    );
    $table->addIndex(
        'IDX_BRONTO_ENVIRONMENT',
        array(
            'environment'
        )
    );
    $table->addIndex(
        'UNQ_BRONTO_SCOPE_SCOPEID',
        array(
            'scope',
            'scope_id',
        ),
        array(
            'type' => Varien_Db_Adapter_Interface::INDEX_TYPE_UNIQUE,
        )
    );
    $installer->getConnection()->createTable($table);
} catch (Exception $e) {
    Mage::log("Failed to create {$registrations}: {$e->getMessage()}", Zend_Log::ERR, 'brontosoftware_connector.log', true);
}

$queue = $installer->getTable('brontosoftware_connector/queue');
try {
    $table = $installer->getConnection()->newTable($queue);
    $table->addColumn(
        'queue_id',
        Varien_Db_Ddl_Table::TYPE_INTEGER,
        11,
        array(
            'unsigned' => true,
            'nullable' => false,
            'auto_increment' => true,
            'primary' => true,
        )
    );
    $table->addColumn(
        'site_id',
        Varien_Db_Ddl_Table::TYPE_VARCHAR,
        120,
        array(
            'nullable' => false,
        )
    );
    $table->addColumn(
        'event_type',
        Varien_Db_Ddl_Table::TYPE_VARCHAR,
        32,
        array(
            'nullable' => false,
        )
    );
    $table->addColumn(
        'event_data',
        Varien_Db_Ddl_Table::TYPE_TEXT,
        null,
        array(
            'nullable' => true,
        )
    );
    $table->addColumn(
        'created_at',
        Varien_Db_Ddl_Table::TYPE_TIMESTAMP,
        null,
        array(
            'nullable' => false,
            'default' => Varien_Db_Ddl_Table::TIMESTAMP_INIT,
        )
    );
    $table->addIndex(
        'IDX_BRONTO_SCRIPT_CREATED',
        array(
            'site_id',
            'created_at',
        )
    );
    $table->addIndex(
        'IDX_BRONTO_SCRIPT_EVENT_TYPE',
        array(
            'site_id',
            'event_type',
        )
    );
    $installer->getConnection()->createTable($table);
} catch (Exception $e) {
    Mage::log("Failed to create {$queue}: {$e->getMessage()}", Zend_Log::ERR, 'brontosoftware_connector.log', true);
}

$installer->endSetup();
