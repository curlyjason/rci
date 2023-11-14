<?php
declare(strict_types=1);

use App\Utilities\Phinx\PhinxHelperTrait;
use Phinx\Migration\AbstractMigration;

final class DefineItemVendorMetadata extends AbstractMigration
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
        $ivs = $this->table('items_vendors');
        $ivs->addColumn(
            'sku',
            'char',
            [
                'after' => 'id',
                'default' => null,
                'limit' => 255,
                'null' => true,
            ])
            ->update();
    }

//    public function up(): void {}
//
//    public function down(): void {}

}
