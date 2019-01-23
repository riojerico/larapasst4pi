<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 12/9/2018
 * Time: 10:31 PM
 */

namespace App\CBServices;

use App\CBModels\T4TParticipant;
use App\CBModels\Trees4TreesFileManaged;
use App\CBModels\Trees4TreesNode;
use App\CBRepositories\T4TParticipantRepository;
use App\CBRepositories\Trees4TreesNodeRepository;
use App\Helpers\FileHelper;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DonorService
{

    /**
     * @param T4tParticipant $parentParticipant
     * @param Request $request
     * @return T4tParticipant
     * @throws \Exception
     */
    public static function register($parentParticipant, Request $request)
    {
        $partByMail = T4TParticipantRepository::findByEmail($request->get("email"));
        if($partByMail->getId()) {
            throw new \Exception("The email has been already exists!");
        }

        //Insert to t4t_participant
        DB::beginTransaction();
        try{

            $participantID = "EU".str_pad(T4TParticipant::getMaxId()+1,8,0, STR_PAD_LEFT);

            $participant = new T4TParticipant();
            $participant->setId($participantID);
            $participant->setName($request->get("first_name"));
            $participant->setComment($request->get("comment"));
            $participant->setAddress("-");
            $participant->setPhone("000");
            $participant->setFax("000");
            $participant->setDirector("NA");
            $participant->setPic("NA");
            $participant->setProduct("NA");
            $participant->setMaterial("NA");
            $participant->setOutletQty(0);
            $participant->setLastname($request->get("last_name"));
            $participant->setEmail($request->get("email"));
            $participant->setDateJoin(date("Y-m-d H:i:s"));
            $participant->setType("Donor");
            $participant->save();

            //insert to t4t_idrelation
            $lastRelation = DB::table("t4t_t4t.t4t_idrelation")
                ->where("id_part", $parentParticipant->getId())
                ->orderBy("no","desc")
                ->first();
            if($lastRelation) {
                $repeatId = $lastRelation->repeat_id + 1;
            }else{
                $repeatId = 1;
            }
            DB::table("t4t_t4t.t4t_idrelation")->insert([
                "id_part"=>$parentParticipant->getId(),
                "related_part"=>$participantID,
                "repeat_id"=>$repeatId
            ]);

            //insert t4t_web.participant
            DB::table("t4t_web.participants")->insert([
               "id_part"=>$participantID,
               "start_year"=>date('Y'),
               'qty_trees'=>0,
               'qty_families'=>0
            ]);

            //insert trees4trees_node
            $node = new Trees4TreesNode();
            $node->setTitle($participantID);
            $node->setType("your_trees_wincheck_configuratio");
            $node->setLanguage('und');
            $node->setUid($parentParticipant->getNo());
            $node->setStatus(1);
            $node->setCreated(time());
            $node->setChanged(time());
            $node->setComment(0);
            $node->setPromote(0);
            $node->setSticky(0);
            $node->setTnid(0);
            $node->setTranslate(0);
            $node->save();
            $node->setVid($node->getNid());
            $node->save();

            if($request->hasFile("photo")) {
                $photo = $request->file("photo");
                $imagedetails = getimagesize($_FILES['photo']['tmp_name']);
                $fliename = $_FILES['photo']['name'];
                $width = $imagedetails[0];
                $height = $imagedetails[1];
                $fileManaged = new Trees4TreesFileManaged();
                $fileManaged->setFilename($fliename);
                $fileManaged->setFilemime($photo->getMimeType());
                $fileManaged->setFilesize($photo->getSize());
                $fileManaged->setUri(FileHelper::uploadFile("photo"));
                $fileManaged->setUid($parentParticipant->getNo());
                $fileManaged->setStatus(1);
                $fileManaged->setTimestamp(time());
                $fileManaged->setType("image");
                $fileManaged->save();

                //Save Trees4Trees Logo
                DB::table("trees_trees4trees.trees4trees_field_data_field_logo")
                ->insert([
                   "entity_type"=>'node',
                   'entity_id'=>$node->getNid(),
                   'language'=>'und',
                   'bundle'=>'see_your_trees_api',
                   'field_logo_fid'=>$fileManaged->getFid(),
                    'field_logo_alt'=>$fliename,
                    'field_logo_title'=>$fliename,
                    'field_logo_height'=>$height,
                    'field_logo_width'=>$width
                ]);
            }

            //Save Trees4treesParticipantName
            DB::table("trees_trees4trees.trees4trees_field_data_field_participant_name")
            ->insert([
                'entity_type'=>'node',
                'bundle'=>'see_your_trees_api',
                'entity_id'=>$node->getNid(),
                'language'=>'und',
                'field_participant_name_value'=>$participant->getName().' '.$participant->getLastname()
            ]);

            //Save Main Text
            DB::table("trees_trees4trees.trees4trees_field_data_field_main_text")
            ->insert([
                'entity_type'=>'node',
                'bundle'=>'see_your_trees_api',
                'entity_id'=>$node->getNid(),
                'language'=>'und',
                'field_main_text_value'=>"<p>A tree has been donated and planted in a community forest on your behalf. That tree will help the environment and improve the lives of a local farming family. Thank You!</p>"
            ]);

            //Save Widget
            DB::table("trees_trees4trees.trees4trees_field_data_field_widget_title")
            ->insert([
                'entity_type'=>'node',
                'bundle'=>'see_your_trees_api',
                'entity_id'=>$node->getNid(),
                'language'=>'und',
                'field_widget_title_value'=>"I Planted a Tree!"
            ]);

            //Save Logo Shape
            DB::table("trees_trees4trees.trees4trees_field_data_field_widget_title")
            ->insert([
                'entity_type'=>'node',
                'bundle'=>'see_your_trees_api',
                'entity_id'=>$node->getNid(),
                'language'=>'und',
                'field_logo_shape_value'=>"round_logo"
            ]);

            DB::commit();

            return $participant;
        }catch (QueryException $e) {
            DB::rollBack();
            throw new \Doctrine\DBAL\Query\QueryException($e);
        }
    }


    public static function update(Request $request)
    {

        $participant = DB::table("t4t_t4t.t4t_participant")
            ->where("id", $request->get('id_participant'))
            ->first();

        //Insert to t4t_participant
        $a = [];
        if($request->get("first_name")) $a['name'] = $request->get('first_name');
        if($request->get("comment")) $a['comment'] = $request->get('comment');
        if($request->get("last_name")) $a['lastname'] = $request->get('last_name');
        if($request->get("email")) $a['email'] = $request->get('email');
        DB::table("t4t_t4t.t4t_participant")
        ->where("id", $request->get('id_participant'))
        ->update($a);

        //insert trees4trees_node
        $node = DB::table("trees_trees4trees.trees4trees_node")
            ->where("title", $request->get('id_participant'))
            ->first();

        DB::table("trees_trees4trees.trees4trees_node")
            ->where("title", $request->get('id_participant'))
            ->update(['changed'=>time()]);

        if($request->hasFile("photo")) {
            $photo = $request->file("photo");
            $photoURL = FileHelper::uploadFile("photo");
            $fliename = $_FILES['photo']['name'];
            $imagedetails = getimagesize($_FILES['photo']['tmp_name']);
            $width = $imagedetails[0];
            $height = $imagedetails[1];

            $a = [];
            $a['filename'] = $photo->getFilename();
            $a['uri'] = $photoURL;
            $a['filemime'] = $photo->getFilename();
            $a['filesize'] = $photo->getSize();
            $a['status'] = 1;
            $a['timestamp'] = time();
            $a['type'] = 'image';
            $fid = DB::table("trees_trees4trees.trees4trees_file_managed")
                ->insertGetId($a);

            //Save Trees4Trees Logo
            DB::table("trees_trees4trees.trees4trees_field_data_field_logo")
            ->where("entity_id", $node->nid)
            ->update([
                'field_logo_fid'=>$fid,
                'field_logo_alt'=>$fliename,
                'field_logo_title'=>$fliename,
                'field_logo_height'=>$height,
                'field_logo_width'=>$width
            ]);
        }

//        //Save Trees4treesParticipantName
        DB::table("trees_trees4trees.trees4trees_field_data_field_participant_name")
            ->where("entity_id", $node->nid)
            ->update([
                'field_participant_name_value'=>$participant->name.' '.$participant->lastname
            ]);

        $participant = DB::table("t4t_t4t.t4t_participant")
            ->where("id", $request->get('id_participant'))
            ->first();

        return $participant;
    }
}