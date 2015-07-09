<?php namespace Opb\LaravelOdbcDb2\Query;

use Illuminate\Database\Query\Builder;
use Illuminate\Database\Query\Processors\Processor;

class DB2Processor extends Processor {

    /**
     * Process an "insert get ID" query.
     *
     * @param  \Illuminate\Database\Query\Builder  $query
     * @param  string  $sql
     * @param  array   $values
     * @param  string  $sequence
     * @return int/array
     */
    public function processInsertGetId(Builder $query, $sql, $values, $sequence = null)
    {
        $sequenceStr = $sequence ?: 'id';
        if (is_array($sequence))
        {
            $sequenceStr = (new DB2Grammar)->columnize($sequence);
        }

        $sql = "select {$sequenceStr} from new table({$sql})";
        $results = $query->getConnection()->select($sql, $values, false);
        if (is_array($sequence))
        {
            return array_values((array) $results[0]);
        }
        else
        {
            $result = (array) $results[0];
            $id = $result[$sequenceStr];

            return is_numeric($id) ? (int) $id : $id;
        }
        
    }

}