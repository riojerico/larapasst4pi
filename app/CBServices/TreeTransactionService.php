<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 12/9/2018
 * Time: 10:31 PM
 */

namespace App\CBServices;


use App\CBModels\PlantingMaps;
use App\CBModels\T4tParticipant;
use App\CBModels\T4tWins;
use App\CBModels\TreeTransactions;
use App\CBModels\Users;
use App\CBRepositories\T4tParticipantRepository;
use App\CBRepositories\T4tWinsRepository;
use App\CBRepositories\UsersRepository;
use App\CBRepositories\ViewTreeStockDetailsRepository;
use App\Helpers\FileHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class TreeTransactionService
{
    public static function assignTree($id_part_from, $id_part_to, $qty = 1, $id_pohon = null)
    {
        //Save Transaction
        $noTrans = str_pad(TreeTransactions::getMaxId()+1,5,0,STR_PAD_LEFT);

        $transId = DB::table("tree_transactions")->insertGetId([
            'no_transaction'=>$noTrans,
            'quantity'=>$qty,
            'id_part_from'=>$id_part_from,
            'id_part_to'=>$id_part_to,
            'id_pohon'=>$id_pohon
        ]);

        //Find All Wins Of Participant From
        $partTo = T4tParticipantRepository::findByParticipantID($id_part_to);
        $wins = T4tWinsRepository::findAllUnused($id_part_from, $id_pohon, $qty);
        foreach($wins as $win)
        {
            //Update Win
            DB::table("t4t_t4t.t4t_wins")
            ->where('no',$win->no)
            ->update([
               'id_retailer'=>$id_part_to,
               'relation'=>1,
               'id_api_trans'=>$transId
            ]);

            //Get Detail
            $stock = ViewTreeStockDetailsRepository::findByShipment($win->no_shipment);
            $htc = DB::table("t4t_t4t.t4t_htc")->where("no_shipment", $win->no_shipment)
                ->first();
            //Save To Planting Maps
            DB::table("t4t_web.planting_maps")->insert([
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