<?php
namespace App\CBRepositories;

use DB;
use App\CBModels\TreeTransactions;

class TreeTransactionsRepository extends TreeTransactions
{
    // TODO : Make you own query methods

    public static function findAllTransactionByParticipant($id_part,$dateFrom,$dateUntil, $limit=10, $offset=0)
    {
        $data = static::simpleQuery();
        $data->where('id_part_from', $id_part);
        $data->whereBetween(DB::raw("DATE(created_at)"),[$dateFrom,$dateUntil]);
        $data->join(env('DB_T4T_T4T').'.t4t_pohon as a','a.id_pohon','=','tree_transactions.id_pohon');
        $data->orderby('tree_transactions.id','desc');
        $data->take($limit);
        $data->offset($offset);
        $data->select(
            'tree_transactions.id',
            'tree_transactions.no_transaction',
            'tree_transactions.created_at',
            'tree_transactions.quantity',
            'tree_transactions.id_pohon',
            'a.nama_pohon',
            'a.nama_latin');
        return $data->get();
    }
}