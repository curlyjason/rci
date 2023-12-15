<?php
declare(strict_types=1);

use App\Utilities\Phinx\PhinxHelperTrait;
use Phinx\Db\Adapter\MysqlAdapter;
use Phinx\Migration\AbstractMigration;

final class RemoveOrderItemColumns extends AbstractMigration
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
//    public function change(): void
//    {
//    }

    public function up(): void {
        $ol = $this->table('order_lines');
        $ol->removeColumn('sku')
            ->removeColumn('vendor_sku')
            ->removeColumn('uom')
            ->update();
        $ol->addColumn('qb_code', 'char', ['limit' => 255, 'after' => 'name'])
            ->addColumn('item_id', 'integer', ['limit' => MysqlAdapter::INT_REGULAR, 'after' => 'id'])
            ->update();

    }

    public function down(): void {
        $ol = $this->table('order_lines');
        $ol->addColumn('sku', 'char', ['limit' => 255, 'comment' => 'QuickBook match item'])
            ->addColumn('vendor_sku', 'char', ['limit' => 255])
            ->addColumn('uom', 'char', ['limit' => 32])
            ->update();
        $ol->removeColumn('qb_code')
            ->removeColumn('item_id')
            ->update();
    }

}
