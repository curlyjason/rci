# Prepared Statements

```php
    public function testPreparedStatements()
    {
        $t = osdTime();

        // each one is a new Query
        $t->start();
        foreach (range(1, 100) as $i) {
            $q = $this->Orders->find()
                ->where(['Orders.id' => 1100 + $i])
                ->contain(['Tenant'/* => ['Items']*/, 'OrderLines'])
                ->first();
        }
//        osd($q);
$t->end();
osd($t->result());

        // put data into file caches
        foreach (range(1, 100) as $i) {
            $q = $this->Orders->find()
                ->where(['Orders.id' => 1100 + $i])
                ->contain(['Tenant'/* => ['Items']*/, 'OrderLines'])
                ->first();
            Cache::write("r$i", $q);
        }

        // each one is read from cache
        $t->start(3);
        foreach (range(1, 100) as $i) {
            $q = Cache::read("r$i");
        }

        $t->end(3);
        osd($t->result(3));

        $t->start(2);
        $t->start(4);

        // prepare vars for a prepared statement
        $q = $this->Orders->find()
            ->where(['Orders.id' => 5])
            ->contain(['Tenant'/* => ['Items']*/, 'OrderLines']);
        $c = $this->Orders->getConnection();
        $p1 = $c->prepare($q);

        $t->end(4);

        // each one is a prepared statement
        foreach (range(1, 100) as $i) {
            $p1->bindValue(':c0', 1100 + $i, 'integer');
            $p1->execute();
            $r = (new ResultSet($q, $p1))->first();
//            debug($r->id);
}
$t->end(2);
osd($t->result(4));
osd($t->result(2));

        $t->start(3);
        $s = 'SELECT Orders.id AS Orders__id, Orders.order_number AS Orders__order_number, Orders.status AS Orders__status, Orders.name AS Orders__name, Orders.email AS Orders__email, Orders.phone AS Orders__phone, Orders.order_reference AS Orders__order_reference, Orders.tenant_name AS Orders__tenant_name, Orders.person_id AS Orders__person_id, Orders.tenant_id AS Orders__tenant_id, Orders.store_id AS Orders__store_id, Orders.destination_hash AS Orders__destination_hash, Orders.created AS Orders__created, Orders.modified AS Orders__modified, Orders.old_order_id AS Orders__old_order_id, Tenant.id AS Tenant__id, Tenant.name AS Tenant__name, Tenant.warehouse_id AS Tenant__warehouse_id, Tenant.customer_code AS Tenant__customer_code, Tenant.token AS Tenant__token, Tenant.active AS Tenant__active, Tenant.created AS Tenant__created, Tenant.modified AS Tenant__modified, Tenant.old_user_id AS Tenant__old_user_id FROM orders Orders INNER JOIN tenants Tenant ON Tenant.id = (Orders.tenant_id) WHERE Orders.id = ?';

//        foreach (range(1, 100) as $i) {
$q1 = $c->execute($s, [12163]);
$r = new ResultSet(new Query($c, $this->Orders), $q1);
osd($r);
osd($r->first());
//            $q1->fetch('assoc');
//        }
$t->end(3);
osd($t->result(3));
//        debug($s);
die;

    }
```

- customers
- customers_items
- items
- items_vendors
- vendors
- orders
- order_lines

