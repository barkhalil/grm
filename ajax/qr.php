<?php
/**
 * Created by PhpStorm.
 * User: T-Yazen
 * Date: 19/10/2016
 * Time: 22:04
 */
session_start();
require '../Connextion.php';
require '../librairie/loadall.php';
use Endroid\QrCode\QrCode;
$qrCode = new QrCode();
//récupération de l'id du prospect :
$id=filter_input(INPUT_GET,'id',FILTER_VALIDATE_INT);
if($id){
    $qrCode
        ->setText(getinfo($id,'prospect','Nom').' '.getinfo($id,'prospect','Prenom').' url : www.vital.com')
        ->setSize(140)
        ->setPadding(5)
        ->setErrorCorrection('high')
        ->setForegroundColor(array('r' => 0, 'g' => 0, 'b' => 0, 'a' => 0))
        ->setBackgroundColor(array('r' => 255, 'g' => 255, 'b' => 255, 'a' => 0))
        // ->setLabel('pros name')
        ->setLabelFontSize(16)
        ->setImageType(QrCode::IMAGE_TYPE_PNG)
    ;

// now we can directly output the qrcode
    header('Content-Type: '.$qrCode->getContentType());
    $qrCode->render();
}


// or create a response object
//$response = new Response($qrCode->get(), 200, array('Content-Type' => $qrCode->getContentType()));