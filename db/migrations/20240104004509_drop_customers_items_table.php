<?php
declare(strict_types=1);

use App\Utilities\Phinx\PhinxHelperTrait;
use Phinx\Migration\AbstractMigration;

final class DropCustomersItemsTable extends AbstractMigration
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

    public function up(): void
    {
        $this->table('customers_items')->drop()->save();
    }

    public function down(): void
    {
        $this->execute("create table customers_items
        (
            id              int unsigned auto_increment
                primary key,
            quantity        smallint default 0 null,
            target_quantity smallint default 0 null,
            next_inventory  datetime           null,
            customer_id     int unsigned       not null,
            item_id         int unsigned       not null,
            created         datetime           null,
            modified        datetime           null,
            constraint customers_items_ibfk_1
                foreign key (item_id) references items (id)
                    on delete cascade,
            constraint customers_items_ibfk_2
                foreign key (customer_id) references customers (id)
                    on delete cascade
        )
            collate = utf8mb4_unicode_ci;

        create index customer_id
            on customers_items (customer_id);

        create index item_id
            on customers_items (item_id);

        ");
    }
}
