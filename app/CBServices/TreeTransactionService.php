<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 12/9/2018
 * Time: 10:31 PM
 */

namespace App\CBServices;

use App\CBModels\TreeTransactions;
use App\CBRepositories\T4TParticipantRepository;
use App\CBRepositories\T4TWinsRepository;
use App\CBRepositories\ViewTreeStockDetailsRepository;
use Illuminate\Support\Facades\DB;

class TreeTransactionService
{
    /**
     * @param $id_part_from
     * @param $id_part_to
     * @param int $qty
     * @param null $id_pohon
     * @return int
     * @throws \Exception
     */
    public static function assignTree($id_part_from, $id_part_to, $qty = 1, $id_pohon = null)
    {
        //Save Transaction
        $noTrans = str_pad(TreeTransactions::getMaxId()+1,5,0,STR_PAD_LEFT);

        $transId = DB::table("tree_transactions")->insertGetId([
            'created_at'=>date('Y-m-d H:i:s'),
            'no_transaction'=>$noTrans,
            'quantity'=>$qty,
            'id_part_from'=>$id_part_from,
            'id_part_to'=>$id_part_to,
            'id_pohon'=>$id_pohon
        ]);

        //Find All Wins Of Participant From
        $partTo = T4TParticipantRepository::findByParticipantID($id_part_to);
        $wins = T4TWinsRepository::findAllUnused($id_part_from, $id_pohon, $qty);
        foreach($wins as $win)
        {
            //Update Win
            DB::table(env('DB_T4T_T4T').".t4t_wins")
            ->where('no',$win->no)
            ->update([
               'id_retailer'=>$id_part_to,
               'relation'=>1,
               'id_api_trans'=>$transId
            ]);

            //Get Detail
            $stock = ViewTreeStockDetailsRepository::findByShipment($win->no_shipment);
            if(!$stock) throw new \Exception("Sorry stock data for shipment ".$win->no_shipment." is not found!");

            $htc = DB::table(env('DB_T4T_T4T').".t4t_htc")
                ->where("no_shipment", $win->no_shipment)
                ->first();
            if(!$htc) throw new \Exception("Sorry t4t_htc for shipment $win->no_shipment is not found!");

            if(!$stock->mu) {
                throw new \Exception("Sorry municipality is not found!");
            }

            //Save To Planting Maps
            DB::table(env('DB_T4T_WEB').".planting_maps")->insert([
               'id_mapdata'=>$htc->no,
               'id_part'=>$id_part_to,
               'geo'=>$stock->koordinat,
               'name'=>$partTo->getName(),
               'total_trees'=>1,
               'id_shipment'=>$win->no_shipment,
               'species'=>$stock->nama_latin,
               'area'=>$stock->luas_tanam,
               'village'=>$stock->desa,
               'district'=>$stock->ta,
               'municipality'=>$stock->mu,
               'farmer'=>$stock->nm_petani,
               'planting_year'=>$stock->thn_tanam
            ]);
        }

        return $transId;
    }
}