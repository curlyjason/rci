<?php
declare(strict_types=1);

use App\Utilities\Phinx\PhinxHelperTrait;
use Phinx\Migration\AbstractMigration;

final class AddInventoryToItems extends AbstractMigration
{
    use PhinxHelperTrait;

    /**
     * Typical trait usage:
     * <pre>
     *   $graphs = $this->table('graphs');
     *     $graphs
     *       ->addColumn('name', 'char', ['limit' => 255])
     *       ->create();
     *     $this->requiredCakeNormColumns($graphs)
     *       ->update();
     *
     * In a separate file (usually)
     *
     *   $table = $this->table('nodes');
     *   $this->requiredForeignKey($table, 'graphs');
     *   $this->optionalForeignKey($table, 'edges')->save();
     * </pre>
     *
     * https://book.cakephp.org/phinx/0/en/migrations.html#the-change-method
     *
     * Valid keys for ->addColumn()
     * https://book.cakephp.org/phinx/0/en/migrations.html#working-with-columns
     *
     * Remember to call "create()" or "update()" and NOT "save()" when working
     * with the Table class.
     */
    public function change(): void
    {
        $table = $this->table('items');
        $table->addColumn(
            'quantity',
            'integer',
            [
                'default' => 0,
                'limit' => \Phinx\Db\Adapter\MysqlAdapter::INT_SMALL,
                'after' => 'name'
            ])
            ->addColumn(
                'target_quantity',
                'integer',
                [
                    'default' => 0,
                    'limit' => \Phinx\Db\Adapter\MysqlAdapter::INT_SMALL,
                    'after' => 'quantity'
                ])
            ->addColumn(
                'next_inventory',
                'datetime',
                ['after' => 'target_quantity'])
            ->update();
    }

//    public function up(): void {}
//
//    public function down(): void {}

}
