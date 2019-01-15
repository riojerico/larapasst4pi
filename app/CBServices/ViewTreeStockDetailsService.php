<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 12/9/2018
 * Time: 10:31 PM
 */

namespace App\CBServices;


use App\CBRepositories\ViewTreeStockDetailsRepository;

class ViewTreeStockDetailsService
{

    public static function findAllStock($id_participant)
    {
        $data = ViewTreeStockDetailsRepository::findAllTreeByParticipant($id_participant);
        foreach($data as &$d)
        {
            $d->used = ViewTreeStockDetailsRepository::findUsedTree($id_participant, $d->id_pohon);
            $d->unused = ViewTreeStockDetailsRepository::findUnusedTree($id_participant, $d->id_pohon);
        }
        return $data;
    }
}