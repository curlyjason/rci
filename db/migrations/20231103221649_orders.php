<?php
declare(strict_types=1);

use App\Utilities\Phinx\PhinxHelperTrait;
use Phinx\Migration\AbstractMigration;

final class Orders extends AbstractMigration
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
        $orders = $this->table('orders');
        $orders
            ->addColumn('order_number', 'char', ['limit' => 32])
            ->addColumn('ordered_by', 'char', ['limit' => 255, 'comment' => 'Name of ordering person'])
            ->addColumn('ordered_by_email', 'char', ['limit' => 255, 'comment' => 'Email of ordering person'])
            ->addColumn('status', 'char', ['limit' => 32])
            ->addColumn('order_date', 'date')
            ->addColumn('due_date', 'date')
            ->addColumn('ship_date', 'date')
            ->create();
        $this->requiredCakeNormColumns($orders)
            ->update();
    }

//    public function up(): void {}
//
//    public function down(): void {}

}
