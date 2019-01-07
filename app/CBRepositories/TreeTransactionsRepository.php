<?php
namespace App\CBRepositories;

use DB;
use App\CBModels\TreeTransactions;

class TreeTransactionsRepository extends TreeTransactions
{
    // TODO : Make you own query methods

    public static function findAllTransactionByParticipant($id_part,$dateFrom,$dateUntil)
    {
        $data = static::simpleQuery();
        $data->where('id_part_from', $id_part);
        $data->whereBetween(DB::raw("DATE(created_at)"),[$dateFrom,$dateUntil]);
        $data->join('t4t_t4t.t4t_pohon as a','a.id_pohon','=','tree_transactions.id_pohon');
        $data->orderby('tree_transactions.id','desc');
        $data->select('tree_transactions.*','a.nama_pohon','a.nama_latin');
        return $data->get();
    }
}