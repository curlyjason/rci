<?php
declare(strict_types=1);

use App\Utilities\Phinx\PhinxHelperTrait;
use Phinx\Migration\AbstractMigration;

final class OrderLines extends AbstractMigration
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
     * Remember to call "create()" or "update()" and NOT "save()" when working
     * with the Table class.
     */
    public function change(): void
    {
        $orderLines = $this->table('order_lines');
        $orderLines
            ->addColumn('name', 'char', ['limit' => 255])
            ->addColumn('sku', 'char', ['limit' => 255, 'comment' => 'QuickBook match item'])
            ->addColumn('vendor_sku', 'char', ['limit' => 255])
            ->addColumn('uom', 'char', ['limit' => 32])
            ->addColumn('quantity', 'integer')
            ->create();
        $this->requiredCakeNormColumns($orderLines)
            ->update();
    }

//    public function up(): void {}
//
//    public function down(): void {}

}
