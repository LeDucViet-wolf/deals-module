<?php

namespace Demo\Deal\Setup;

use Magento\Framework\DB\Ddl\Table;
use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;

class InstallSchema implements InstallSchemaInterface
{
    public function install(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $setup->startSetup();

        $tableName = $setup->getTable('demo_deal');

        if ($setup->getConnection()->isTableExists($tableName) != true) {
            $table = $setup->getConnection()->newTable($tableName)
                ->addColumn(
                    'id',
                    Table::TYPE_INTEGER,
                    null,
                    [
                        'identity' => true,
                        'unsigned' => true,
                        'nullable' => false,
                        'primary' => true
                    ],
                    'Id'
                )
                ->addColumn(
                    'product',
                    Table::TYPE_TEXT,
                    null,
                    [
                        'nullable' => false,
                        'default' => ''
                    ],
                    'Product'
                )
                ->addColumn(
                    'deal_price',
                    Table::TYPE_FLOAT,
                    null,
                    [
                        'nullable' => false,
                        'default' => '0'
                    ],
                    'Deal price'
                )
                ->addColumn(
                    'deal_qty',
                    Table::TYPE_INTEGER,
                    null,
                    [
                        'nullable' => false,
                        'default' => ''
                    ],
                    'Deal quantity'
                )
                ->addColumn(
                    'status',
                    Table::TYPE_SMALLINT,
                    null,
                    [
                        'nullable' => false,
                        'default' => '1'
                    ],
                    'Status'
                )
                ->addColumn(
                    'time_start',
                    Table::TYPE_DATETIME,
                    null,
                    [
                        'nullable' => false,
                        'default' => 'CURRENT_TIMESTAMP'
                    ],
                    'Time start'
                )
                ->addColumn(
                    'time_end',
                    Table::TYPE_DATETIME,
                    null,
                    [
                        'nullable' => false,
                        'default' => 'CURRENT_TIMESTAMP'
                    ],
                    'Time end'
                )
                ->setComment('Demo Deal')
                ->setOption('type', 'InnoDB')
                ->setOption('charset', 'utf8');
            $setup->getConnection()->createTable($table);
        }
        $setup->endSetup();
    }
}
