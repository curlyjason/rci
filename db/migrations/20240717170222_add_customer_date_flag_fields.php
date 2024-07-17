<?php
declare(strict_types=1);

use App\Utilities\Phinx\PhinxHelperTrait;
use Phinx\Migration\AbstractMigration;

final class AddCustomerDateFlagFields extends AbstractMigration
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
        $c = $this->table('customers');
        $c->addColumn('last_inventory_notification', 'datetime', ['after' => 'name'])
            ->addColumn('next_inventory', 'datetime', ['after' => 'name'])
            ->update();
    }

//    public function up(): void {}
//
//    public function down(): void {}

}
