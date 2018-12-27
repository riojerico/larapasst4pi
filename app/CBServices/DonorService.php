<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 12/9/2018
 * Time: 10:31 PM
 */

namespace App\CBServices;


use App\CBModels\T4tIdrelation;
use App\CBModels\T4tParticipant;
use App\CBModels\T4TWebParticipant;
use App\CBModels\Trees4treesFieldDataFieldLogo;
use App\CBModels\Trees4TreesFieldLogo;
use App\CBModels\Trees4TreesFieldParticipantName;
use App\CBModels\Trees4treesFileManaged;
use App\CBModels\Trees4treesNode;
use App\CBModels\Users;
use App\CBRepositories\T4tIdrelationRepository;
use App\CBRepositories\T4tParticipantRepository;
use App\CBRepositories\Trees4TreesFieldLogoRepository;
use App\CBRepositories\Trees4TreesFieldParticipantNameRepository;
use App\CBRepositories\Trees4treesNodeRepository;
use App\CBRepositories\UsersRepository;
use App\Helpers\FileHelper;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

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
        $partByMail = T4tParticipantRepository::findByEmail($request->get("email"));
        if($partByMail->getId()) {
            throw new \Exception("The email has been already exists!");
        }

        //Insert to t4t_participant
        DB::beginTransaction();
        try{

            $participantID = "EU".str_pad(T4tParticipant::getMaxId()+1,8,0, STR_PAD_LEFT);

            $participant = new T4tParticipant();
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
            $node = new Trees4treesNode();
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
                $fileManaged = new Trees4treesFileManaged();
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
                'field_participant_name_value'=>$participant->getName()
            ]);

            DB::commit();

            return $participant;
        }catch (QueryException $e) {
            DB::rollBack();
            throw new \Doctrine\DBAL\Query\QueryException($e);
        }
    }


    /**
     * @param Request $request
     * @return T4tParticipant
     * @throws \Exception
     */
    public static function update(Request $request)
    {
        DB::beginTransaction();
        try{
            //Insert to t4t_participant
            $participant = T4tParticipantRepository::findByParticipantID($request->get('id_participant'));
            if($request->get("first_name")) $participant->setName($request->get("first_name"));
            if($request->get("comment")) $participant->setComment($request->get("comment"));
            if($request->get("last_name")) $participant->setLastname($request->get("last_name"));
            if($request->get("email")) $participant->setEmail($request->get("email"));
            $participant->save();


            //insert trees4trees_node
            $node = Trees4treesNodeRepository::findByParticipantID($participant->getId());
            $node->setChanged(time());
            $node->save();

            if($request->hasFile("photo")) {
                $photo = $request->file("photo");
                $photoURL = FileHelper::uploadFile("photo");
                $imagedetails = getimagesize($_FILES['photo']['tmp_name']);
                $width = $imagedetails[0];
                $height = $imagedetails[1];
                $fileManaged = new Trees4treesFileManaged();
                $fileManaged->setFilename($photo->getFilename());
                $fileManaged->setUri($photoURL);
                $fileManaged->setFilemime($photo->getMimeType());
                $fileManaged->setFilesize($photo->getSize());
                $fileManaged->setStatus(1);
                $fileManaged->setTimestamp(time());
                $fileManaged->setType("image");
                $fileManaged->save();

                //Save Trees4Trees Logo
                $logo = Trees4TreesFieldLogoRepository::findByEntityId($node->getNid());
                $logo->setFieldLogoFid($fileManaged->getFid());
                $logo->setFieldLogoAlt($photo->getFilename());
                $logo->setFieldLogoTitle($photo->getFilename());
                $logo->setFieldLogoHeight($height);
                $logo->setFieldLogoWidth($width);
                $logo->save();
            }

            //Save Trees4treesParticipantName
            $treesParticipant = Trees4TreesFieldParticipantNameRepository::findByEntityId($node->getNid());
            $treesParticipant->setFieldParticipantNameValue($participant->getName());
            $treesParticipant->save();
            DB::commit();
            return $participant;
        }catch (\Exception $e) {
            DB::rollBack();
            throw new \Exception($e);
        }

    }
}