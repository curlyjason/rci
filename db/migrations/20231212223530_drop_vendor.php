<?php
declare(strict_types=1);

use App\Utilities\Phinx\PhinxHelperTrait;
use Phinx\Migration\AbstractMigration;

final class DropVendor extends AbstractMigration
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

    public function up(): void
    {
        $this->table('items_vendors')->drop()->save();
        $this->table('vendors')->drop()->save();
    }

    public function down(): void
    {
        $vendors = $this->table('vendors');
        $vendors
            ->addColumn('name', 'char', ['limit' => 255])
            ->create();
        $this->requiredCakeNormColumns($vendors)
            ->update();

        $ivs = $this->table('items_vendors');
        $this->requiredCakeNormColumns($ivs)
            ->create();
        $ivs->addColumn(
            'sku',
            'char',
            [
                'after' => 'id',
                'default' => null,
                'limit' => 255,
                'null' => true,
            ]
        )
        ->update();

        $this->requiredForeignKey($ivs, 'vendors', 'id');
        $this->requiredForeignKey($ivs, 'items', 'id');

        $ivs->update();
    }
}
