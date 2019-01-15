<?php
namespace App\CBRepositories;

use DB;
use App\CBModels\T4TWins;

class T4TWinsRepository extends T4TWins
{
    // TODO : Make you own query methods

    public static function findByTrans($id_trans) {
        $data = static::simpleQuery()
            ->where("id_api_trans", $id_trans)
            ->first();
        return new static($data);
    }

    public static function findAllUnused($id_part, $id_pohon = null, $limit = 10)
    {
        $data = static::simpleQuery()
            ->join('t4t_api.view_tree_stock_details as a','a.wins','=','t4t_wins.wins')
            ->where('t4t_wins.relation',0)
            ->where('t4t_wins.id_part', $id_part);

        if($id_pohon) {
            $data->where('a.id_pohon', $id_pohon);
        }

        return $data->take($limit)->get();
    }
}