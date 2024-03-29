<?php
declare(strict_types=1);

use App\Utilities\Phinx\PhinxHelperTrait;
use Phinx\Migration\AbstractMigration;

final class ExpandItemsTable extends AbstractMigration
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
        $table
            ->addColumn(
                'qb_code',
                'char',
                [
                    'limit' => 255,
                    'after' => 'id',
                    'comment' => 'The delimited list-member string from QuickBooks, unedited',
                ])
            ->update();
        $table->addIndex('qb_code')->update();
    }

//    public function up(): void {}
//
//    public function down(): void {}

}
