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
        $participant = new T4tParticipant();
        $participant->setId("EU".str_pad(T4tParticipant::getMaxId()+1,8,0, STR_PAD_LEFT));
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
        $repeatId = T4tIdrelationRepository::getNewRepeatId($parentParticipant->getId());
        $t4tRelation = new T4tIdrelation();
        $t4tRelation->setIdPart($parentParticipant->getId());
        $t4tRelation->setRelatedPart($participant->getId());
        $t4tRelation->setRepeatId($repeatId);
        $t4tRelation->save();

        //insert t4t_web.participant
        $t4tWebParticipant = new T4TWebParticipant();
        $t4tWebParticipant->setIdPart($t4tRelation->getRelatedPart()->getId());
        $t4tWebParticipant->setStartYear(date('Y'));
        $t4tWebParticipant->setQtyTrees(0);
        $t4tWebParticipant->setStartYear(0);
        $t4tWebParticipant->save();

        //insert trees4trees_node
        $node = new Trees4treesNode();
        $node->setTitle($participant->getId());
        $node->setType("your_trees_wincheck_configuratio");
        $node->setLanguage('und');
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
            $logo = new Trees4TreesFieldLogo();
            $logo->setEntityId($node->getNid());
            $logo->setLanguage("und");
            $logo->setBundle("see_your_trees_api");
            $logo->setFieldLogoFid($fileManaged->getFid());
            $logo->setFieldLogoAlt($photo->getFilename());
            $logo->setFieldLogoTitle($photo->getFilename());
            $logo->setFieldLogoHeight($height);
            $logo->setFieldLogoWidth($width);
            $logo->save();
        }

        //Save Trees4treesParticipantName
        $treesParticipant = new Trees4TreesFieldParticipantName();
        $treesParticipant->setEntityType("node");
        $treesParticipant->setBundle("see_your_trees_api");
        $treesParticipant->setEntityId($node->getNid());
        $treesParticipant->setLanguage("und");
        $treesParticipant->setFieldParticipantNameValue($participant->getName());
        $treesParticipant->save();

        return $participant;
    }


    /**
     * @param Request $request
     * @return T4tParticipant
     * @throws \Exception
     */
    public static function update(Request $request)
    {

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

        return $participant;
    }
}