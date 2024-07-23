<?php


namespace App\Test\Traits;


use App\Constants\RoleCon;
use App\Test\Factory\PersonFactory;
use Cake\ORM\Entity;
use Cake\ORM\Locator\LocatorAwareTrait;
use Cake\ORM\Query;

trait RetrievalTrait
{

    use LocatorAwareTrait;

    public function getFirst($alias): Entity
    {
        $records = $this->getRecords($alias);
        if (empty($records)) {
            debug($alias . ' no first record found');
        }
        return $records[0];
    }

    public function getLast($alias)
    {
        $records = $this->getRecords($alias, 'DESC');
        return $records[0];
    }

    /**
     * @param $alias
     * @return array
     */
    protected function getRecords($alias, $direction = 'ASC'): array
    {
        $table = $this->getTableLocator()->get($alias);
        if($table->getPrimaryKey() === 'id') {
            $sort = ['id' => $direction];
        }
        else {
            $sort = ['created' => $direction];

        }
        $records = $table
            ->find()
            ->orderBy($sort)
            ->toArray();
        return $records;
    }

    protected function countRecords($alias): int
    {
        $table = $this->getTableLocator()->get($alias);
        return $table
            ->find()
            ->all()
            ->count();
    }

    /**
     * @param $alias
     * @param $id
     * @param array $contain
     * @return Query query
     */
    public function getRecordsNot($alias, $id, $contain = []): Query
    {
        $prefix = empty($contain) ? '' : "$alias.";
        $conditions = is_array($id)
            ? ["{$prefix}id NOT IN" => $id]
            : ["{$prefix}id !=" => $id];
        $conditions = empty($id)
            ? []
            : $conditions;

        $table = $this->getTableLocator()->get($alias);
        $query = $table
            ->find()
            ->where($conditions)
            ->contain($contain);
        return $query;

    }
}

