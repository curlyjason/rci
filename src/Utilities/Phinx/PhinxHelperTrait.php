<?php
declare(strict_types=1);

namespace App\Utilities\Phinx;

use Cake\Utility\Inflector;
use Phinx\Db\Adapter\MysqlAdapter;
use Phinx\Db\Table;

trait PhinxHelperTrait
{
    /**
     * Make 'created' and 'modified' for the table
     *
     * Does not update
     *
     * @param \Phinx\Db\Table $table
     * @return \Phinx\Db\Table
     */
    public function requiredCakeNormColumns(Table $table): Table
    {
        $table
            ->addColumn(
                'created',
                'datetime',
                ['default' => null, 'null' => true,]
            )
            ->addColumn(
                'modified',
                'datetime',
                ['default' => null, 'null' => true,]
            );

            return $table;
    }

    /**
     * Make a link field and make it a required foreign-key
     *
     * Does not update
     *
     * @param \Phinx\Db\Table $table the table to modify
     * @param string $foreign_table_name name of the table to link
     * @param ?string $after insert the new foreign key after this column
     * @param ?string $columnName override the default 'tablename_id' if desired
     * @return \Phinx\Db\Table $table
     */
    public function requiredForeignKey(
        Table $table,
        string $foreign_table_name,
        ?string $after = null,
        ?string $columnName = null
    ): Table {
        $options = [
            'limit' => MysqlAdapter::INT_REGULAR,
            'null' => false,
            'signed' => false,
        ];
        if (!is_null($after)) {
            $options['after'] = $after;
        }
        $columnName = $columnName ?? Inflector::singularize($foreign_table_name) . '_id';

        $table
            ->addColumn($columnName, 'integer', $options)
            ->addForeignKey(
                $columnName,
                $foreign_table_name,
                'id',
                ['delete' => 'CASCADE',]
            );

        return $table;
    }

    /**
     * Make a link field and make it a nullable foreign-key
     *
     * Does not update
     *
     * @param \Phinx\Db\Table $table the table to modify
     * @param string $foreign_table_name name of the table to link
     * @param ?string $after insert the new foreign key after this column
     * @param ?string $columnName override the default 'tablename_id' if desired
     * @return \Phinx\Db\Table $table
     */
    public function optionalForeignKey(Table $table, string $foreign_table_name, ?string $after = null): Table
    {
        $options = [
            'limit' => MysqlAdapter::INT_REGULAR,
            'null' => true,
            'default' => null,
            'signed' => false,
        ];
        if (!is_null($after)) {
            $options['after'] = $after;
        }
        $columnName = $columnName ?? Inflector::singularize($foreign_table_name) . '_id';

        $table
            ->addColumn($columnName, 'integer', $options)
            ->addForeignKey(
                $columnName,
                $foreign_table_name,
                'id',
                ['delete' => 'SET_NULL', 'update' => 'NO_ACTION']
            );

        return $table;
    }
}
