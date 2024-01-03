<?php
declare(strict_types=1);

use App\Utilities\Phinx\PhinxHelperTrait;
use Phinx\Migration\AbstractMigration;

final class CleanUpOrderLinesTable extends AbstractMigration
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
//
//    }

    public function up(): void {
        $orderLines = $this->table('order_lines');
        $orderLines
            ->removeColumn('sku')
            ->removeColumn('vendor_sku')
            ->removeColumn('uom')
            ->update();
        $orderLines->addColumn(
            'qb_encoded',
            'char',
            [
                'limit' => 255,
                'after' => 'id',
                'comment' => 'The delimited list-member string from QuickBooks, unedited',
            ])
            ->update();

    }

    public function down(): void {
        $orderLines = $this->table('order_lines');
        $orderLines->removeColumn('qb_encoded')
            ->update();
        $orderLines
            ->addColumn('uom', 'char', ['limit' => 32, 'after' => 'name'])
            ->addColumn('vendor_sku', 'char', ['limit' => 255, 'after' => 'name'])
            ->addColumn('sku', 'char', ['limit' => 255, 'comment' => 'QuickBook match item', 'after' => 'name'])
            ->update();

    }

}
