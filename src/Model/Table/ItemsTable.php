<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\Query\SelectQuery;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Items Model
 *
 * @property \App\Model\Table\CustomersTable&\Cake\ORM\Association\BelongsToMany $Customers
 *
 * @method \App\Model\Entity\Item newEmptyEntity()
 * @method \App\Model\Entity\Item newEntity(array $data, array $options = [])
 * @method \App\Model\Entity\Item[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Item get($primaryKey, $options = [], $contain = [])
 * @method \App\Model\Entity\Item findOrCreate($search, ?callable $callback = null, $options = [])
 * @method \App\Model\Entity\Item patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Item[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\Item|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Item saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Item[]|\Cake\Datasource\ResultSetInterface|false saveMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\Item[]|\Cake\Datasource\ResultSetInterface saveManyOrFail(iterable $entities, $options = [])
 * @method \App\Model\Entity\Item[]|\Cake\Datasource\ResultSetInterface|false deleteMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\Item[]|\Cake\Datasource\ResultSetInterface deleteManyOrFail(iterable $entities, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class ItemsTable extends Table
{
    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config): void
    {
        parent::initialize($config);

        $this->setTable('items');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsToMany('Customers', [
            'foreignKey' => 'item_id',
            'targetForeignKey' => 'customer_id',
            'through' => 'CustomersItems',
        ]);

        $this->hasMany('Joins', [
            'foreignKey' => 'item_id',
            'className' => 'CustomersItems',
        ]);
    }

    /**
     * Default validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator): Validator
    {
        $validator
            ->scalar('name')
            ->maxLength('name', 255)
//            ->allowEmptyString('name')
            ->requirePresence('name');

        $validator
            ->scalar('qb_code')
            ->maxLength('name', 255)
            ->minLength('qb_code', 5)
            ->requirePresence('qb_code');

        return $validator;
    }

    public function findExistingCustomerItem($customer_id, $qb_code): SelectQuery
    {
//        $qb_code = 'path:to:Dolores dolorum amet iste laborum eius est dolor.';
//        $customer_id = 2035935068;
        return $this->find()
            ->matching('Customers', function($q) use ($customer_id) {
                return $q->where(['Customers.id' => $customer_id]);
            })
            ->where(['qb_code' => $qb_code,])
            ->select([
                'Items.id',
                'Items.qb_code',
                'Items.name',
                'Customers.id',
                'CustomersItems.id',
                'CustomersItems.target_quantity',
                'CustomersItems.next_inventory',
                'CustomersItems.item_id',
                'CustomersItems.customer_id',
            ]);
    }
}
