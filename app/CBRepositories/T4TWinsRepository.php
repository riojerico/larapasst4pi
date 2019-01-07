<?php
namespace App\CBRepositories;

use DB;
use App\CBModels\T4tWins;

class T4tWinsRepository extends T4tWins
{
    // TODO : Make you own query methods

    public static function findAllUnused($id_part, $id_pohon = null, $limit = 10)
    {
        $data = static::simpleQuery()
            ->join('view_tree_stock_details as a','a.wins','=','t4t_wins.wins')
            ->where('t4t_wins.relation',0)
            ->where('t4t_wins.id_part', $id_part);

        if($id_pohon) {
            $data->where('a.id_pohon', $id_pohon);
        }

        return $data->take($limit)->get();
    }
}