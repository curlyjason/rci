<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query\SelectQuery;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * First Model
 *
 * @method \App\Model\Entity\First newEmptyEntity()
 * @method \App\Model\Entity\First newEntity(array $data, array $options = [])
 * @method \App\Model\Entity\First[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\First get($primaryKey, $options = [])
 * @method \App\Model\Entity\First findOrCreate($search, ?callable $callback = null, $options = [])
 * @method \App\Model\Entity\First patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\First[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\First|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\First saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\First[]|\Cake\Datasource\ResultSetInterface|false saveMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\First[]|\Cake\Datasource\ResultSetInterface saveManyOrFail(iterable $entities, $options = [])
 * @method \App\Model\Entity\First[]|\Cake\Datasource\ResultSetInterface|false deleteMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\First[]|\Cake\Datasource\ResultSetInterface deleteManyOrFail(iterable $entities, $options = [])
 */
class FirstTable extends Table
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

        $this->setTable('first');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');
    }
}
