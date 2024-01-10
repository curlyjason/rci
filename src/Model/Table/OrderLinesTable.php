<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query\SelectQuery;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * OrderLines Model
 *
 * @method \App\Model\Entity\OrderLine newEmptyEntity()
 * @method \App\Model\Entity\OrderLine newEntity(array $data, array $options = [])
 * @method \App\Model\Entity\OrderLine[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\OrderLine get($primaryKey, $options = [], $contain = [])
 * @method \App\Model\Entity\OrderLine findOrCreate($search, ?callable $callback = null, $options = [])
 * @method \App\Model\Entity\OrderLine patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\OrderLine[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\OrderLine|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\OrderLine saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\OrderLine[]|\Cake\Datasource\ResultSetInterface|false saveMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\OrderLine[]|\Cake\Datasource\ResultSetInterface saveManyOrFail(iterable $entities, $options = [])
 * @method \App\Model\Entity\OrderLine[]|\Cake\Datasource\ResultSetInterface|false deleteMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\OrderLine[]|\Cake\Datasource\ResultSetInterface deleteManyOrFail(iterable $entities, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class OrderLinesTable extends Table
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

        $this->setTable('order_lines');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');
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
            ->scalar('qb_code')
            ->maxLength('qb_code', 255)
            ->allowEmptyString('qb_code');

        $validator
            ->scalar('name')
            ->maxLength('name', 255)
            ->allowEmptyString('name');

        $validator
            ->integer('quantity')
            ->allowEmptyString('quantity');

        return $validator;
    }
}
