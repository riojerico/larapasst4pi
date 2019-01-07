<?php
namespace App\CBRepositories;

use App\CBModels\ApiLogs;
use Illuminate\Support\Facades\DB;

class ViewTreeStockDetailsRepository
{
    // TODO : Make you own query methods

    public static function findByShipment($no_shipment)
    {
        return DB::table("view_tree_stock_details")
            ->where("no_shipment", $no_shipment)
            ->first();
    }

    public static function findAllTreeByParticipant($id_participant)
    {
        return DB::table("view_tree_stock_details")
            ->select("id_pohon","nama_pohon","nama_latin")
            ->where("id_part", $id_participant)
            ->groupBy("id_pohon")
            ->get();
    }

    public static function findUsedTree($id_participant, $id_pohon)
    {
        return DB::table("view_tree_stock_details")
            ->where("id_part", $id_participant)
            ->where('id_pohon', $id_pohon)
            ->where('relation',1)
            ->count();
    }

    public static function findUnusedTree($id_participant, $id_pohon)
    {
        return DB::table("view_tree_stock_details")
            ->where("id_part", $id_participant)
            ->where('id_pohon', $id_pohon)
            ->where('relation',0)
            ->count();
    }
}