<?php


namespace App\Utilities;

use App\Model\Table\SkusTable;
use Cake\Datasource\EntityInterface;
use Cake\ORM\Entity;
use Cake\ORM\Table;

/**
 * Class BusinessRules
 *
 * This contains Table Rule implementations to insure business rules
 * apply to data before it is saved or deleted.
 *
 * @link https://book.cakephp.org/4/en/orm/validation.html#applying-application-rules
 * @package App\Lib
 */
class BusinessRules
{

    /**
     * Is the field value unique within a similarly linked set of records
     *
     * This rule does a query to find all other same-type records that share a
     * common foreign key link. With the source record and all other members
     * linked to the same parent, we compare a single column value to see that
     * this new records column is unique.
     *
     * Collisions with records linked to other parents are allowed.
     *
     * Both new records and edited records are checked because an edit may
     * make a new collision. To do edit checks, this current record being
     * tested is removed from the list of found records and the check is
     * made against the remainder.
     *
     * Option keys
     *
     * Required:
     * 'foreign_key' the key to search on to find the possible collision-set
     * 'errorField' the field that will be tested for collisions
     *
     * Other keys:
     * 'repository' the current table, provided by the save() process
     * 'ignore_empty' boolean true to allow empty value without unique checking
     *
     * @param $entity Entity the entity that is a save/delete candidate
     * @param $options array options for the rule
     * @return bool result of the test
     */
    static public function uniqueNameInScope($entity, $options)
    {
        $name = $options['errorField'];

        if (($options['ignore_empty'] ?? false) && empty($entity->$name)) {
            return true;
        }

        $condition = isset($entity->id) ? ['id !=' => $entity->id] : [];
        $foreign_key = $options['foreign_key'];

        $names = $options['repository']
            ->find('list', ['valueField' => $name])
            ->where($condition + [$foreign_key => $entity->$foreign_key])
            ->toArray();

        /* Fix whitespace-makes-unique bug 03/2023 */
        return !in_array(trim($entity->$name), $names);
    }

    /**
     * Is the field value unique within a similarly linked set of records
     *
     * This rule does a query to find all other same-type records that share a
     * common foreign key link. With the source record and all other members
     * linked to the same parent, we compare a single column value to see that
     * this new records column is unique.
     *
     * Collisions with records linked to other parents are allowed.
     *
     * Both new records and edited records are checked because an edit may
     * make a new collision. To do edit checks, this current record being
     * tested is removed from the list of found records and the check is
     * made against the remainder.
     *
     * Option keys
     *
     * Required:
     * 'foreign_key' the key to search on to find the possible collision-set
     * 'errorField' the field that will be tested for collisions
     *
     * Other keys:
     * 'repository' the current table, provided by the save() process
     * 'ignore_empty' boolean true to allow empty value without unique checking
     * TenantPreferences->unique_code (t/f preference value for tenant)
     *
     * @param $entity Entity the entity that is a save/delete candidate
     * @param $options array options for the rule
     * @return bool result of the test
     */
    static public function uniqueSkuInScope($entity, $options): bool
    {
        /**
         * @var SkusTable $table
         */
        $table = $options['repository'];
        $item = $table->Items->find()
            ->select(['id', 'tenant_id'])
            ->where(['id' => $entity->item_id])
            ->toArray();
        $prefs = $table->Items->TenantPreference->get($item[0]->tenant_id);
        $self_id = $table->getAlias() . '.id';
        $name = $options['errorField'];

        if (!$prefs->unique_code || (($options['ignore_empty'] ?? false) && empty($entity->$name))) {
            return true;
        }

        $tenant_id = $table->Items->get($entity->item_id)->tenant_id;
        $name_condition = $name == 'name' ? ['item_id !=' => $entity->item_id] : [];
        $id_condition = isset($entity->id) ? [$self_id . ' !=' => $entity->id] : [];
        $condition = array_merge($name_condition, $id_condition);

        $names = $table
            ->find('list', ['valueField' => $name])
            ->where($condition)
            ->matching('Items', function ($q) use ($tenant_id) {
                return $q->where(['Items.tenant_id' => $tenant_id]);
            })
            ->toArray();

        return !in_array($entity->$name, $names);
    }

    /**
     * Provide an array|entity and list of node-names, get a digest of the data
     *
     * @param EntityInterface|array $entity
     * @param string[] $columns
     * @return string
     */
    static public function digest($entity, $columns): string
    {
        $rawData = $entity;
        if ($entity instanceof EntityInterface) {
            $rawData = $entity->toArray();
        }

        //control the keys and their order for consistent hashes
        $digestData = array_intersect_key($rawData, array_combine($columns, $columns));
        $string = collection($columns)
            ->reduce(function ($accum, $key) use ($digestData) {
                return $accum . ($digestData[$key] ?? '');
            }, '');

        return sha1($string);
    }
}
