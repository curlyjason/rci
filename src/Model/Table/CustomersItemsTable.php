<?php
declare(strict_types=1);

namespace App\Model\Table;

use App\Utilities\DateUtilityTrait;
use ArrayObject;
use Cake\Datasource\EntityInterface;
use Cake\Event\EventInterface;
use Cake\ORM\Query\SelectQuery;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * CustomersItems Model
 *
 * @property \App\Model\Table\CustomersTable&\Cake\ORM\Association\BelongsTo $Customers
 * @property \App\Model\Table\ItemsTable&\Cake\ORM\Association\BelongsTo $Items
 *
 * @method \App\Model\Entity\CustomersItem newEmptyEntity()
 * @method \App\Model\Entity\CustomersItem newEntity(array $data, array $options = [])
 * @method \App\Model\Entity\CustomersItem[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\CustomersItem get($primaryKey, $options = [], $contain = [])
 * @method \App\Model\Entity\CustomersItem findOrCreate($search, ?callable $callback = null, $options = [])
 * @method \App\Model\Entity\CustomersItem patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\CustomersItem[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\CustomersItem|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\CustomersItem saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\CustomersItem[]|\Cake\Datasource\ResultSetInterface|false saveMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\CustomersItem[]|\Cake\Datasource\ResultSetInterface saveManyOrFail(iterable $entities, $options = [])
 * @method \App\Model\Entity\CustomersItem[]|\Cake\Datasource\ResultSetInterface|false deleteMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\CustomersItem[]|\Cake\Datasource\ResultSetInterface deleteManyOrFail(iterable $entities, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class CustomersItemsTable extends Table
{
    use DateUtilityTrait;
    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config): void
    {
        parent::initialize($config);

        $this->setTable('customers_items');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('Customers', [
            'foreignKey' => 'customer_id',
            'joinType' => 'INNER',
        ]);
        $this->belongsTo('Items', [
            'foreignKey' => 'item_id',
            'joinType' => 'INNER',
        ]);
    }

    public function beforeSave(EventInterface $event, EntityInterface $entity, ArrayObject $options)
    {
        if(empty($entity->next_inventory)) {
            $entity->set('next_inventory', $this->thisMonthsInventoryDate());
        }
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
            ->allowEmptyString('quantity');

        $validator
            ->allowEmptyString('target_quantity');

        $validator
            ->dateTime('next_inventory')
            ->allowEmptyDateTime('next_inventory');

        $validator
            ->nonNegativeInteger('customer_id')
            ->notEmptyString('customer_id');

        $validator
            ->nonNegativeInteger('item_id')
            ->notEmptyString('item_id');

        return $validator;
    }

    /**
     * Returns a rules checker object that will be used for validating
     * application integrity.
     *
     * @param \Cake\ORM\RulesChecker $rules The rules object to be modified.
     * @return \Cake\ORM\RulesChecker
     */
    public function buildRules(RulesChecker $rules): RulesChecker
    {
        $rules->add($rules->existsIn('customer_id', 'Customers'), ['errorField' => 'customer_id']);
        $rules->add($rules->existsIn('item_id', 'Items'), ['errorField' => 'item_id']);

        return $rules;
    }

    public function findInventoryForCustomer($customer_id): SelectQuery
    {
        return $this->find()
            ->where(['customer_id' => $customer_id])
            ->contain(['Customers', 'Items']);
    }
}
