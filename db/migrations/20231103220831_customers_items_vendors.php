<?php
declare(strict_types=1);

use App\Utilities\Phinx\PhinxHelperTrait;
use Phinx\Migration\AbstractMigration;

final class CustomersItemsVendors extends AbstractMigration
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
        $tables = [
            'customers',
            'items',
            'vendors',
        ];
        foreach ($tables as $table) {
            $target = $this->table($table);
            $target
                ->addColumn('name', 'char', ['limit' => 255])
                ->create();
            $this->requiredCakeNormColumns($target)
                ->update();
        }
    }

//    public function up(): void {}
//
//    public function down(): void {}

}
