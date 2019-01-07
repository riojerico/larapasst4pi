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

class TreeTransactionService
{
    public static function assignTree($id_part_from, $id_part_to, $qty = 1, $id_pohon = null)
    {
        //Save Transaction
        $noTrans = str_pad(TreeTransactions::getMaxId()+1,5,0,STR_PAD_LEFT);
        $trans = new TreeTransactions();
        $trans->setNoTransaction($noTrans);
        $trans->setQuantity($qty);
        $trans->setIdPartFrom($id_part_from);
        $trans->setIdPartTo($id_part_to);
        $trans->setIdPohon($id_pohon);
        $trans->save();

        //Find All Wins Of Participant From
        $partTo = T4tParticipantRepository::findByParticipantID($id_part_to);
        $wins = T4tWinsRepository::findAllUnused($id_part_from, $id_pohon, $qty);
        foreach($wins as $win)
        {
            //Update Win
            $update = T4tWins::findById($win->no);
            $update->setIdRetailer($id_part_to);
            $update->setRelation(1);
            $update->setIdApiTrans($trans->getId());
            $update->save();

            //Get Detail
            $stock = ViewTreeStockDetailsRepository::findByShipment($win->no_shipment);
            $htc = DB::table("t4t_t4t.t4t_htc")->where("no_shipment", $win->no_shipment)
                ->first();
            //Save To Planting Maps
            $map = new PlantingMaps();
            $map->setIdMapdata($htc->no);
            $map->setIdPart($id_part_to);
            $map->setGeo($stock->koordinat);
            $map->setName($partTo->getName());
            $map->setTotalTrees(1);
            $map->setIdShipment($win->no_shipment);
            $map->setSpecies($stock->nama_latin);
            $map->setArea($stock->luas_tanam);
            $map->setVillage($stock->desa);
            $map->setDistrict($stock->ta);
            $map->setMunicipality($stock->mu);
            $map->setFarmer($stock->nm_petani);
            $map->setPlantingYear($stock->thn_tanam);
            $map->save();
        }

        return $trans->getId();
    }
}