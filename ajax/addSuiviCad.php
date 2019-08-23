<?php
/**
 * Created by IntelliJ IDEA.
 * User: KHALIL
 * Date: 23/08/2019
 * Time: 12:33
 */
session_start();
require '../Connextion.php';
require '../librairie/loadall.php';


$id=filter_input(INPUT_POST,'id',FILTER_DEFAULT);

$data=array(

    'suivi'=>1
);

updateCond($data,' id='.$id.'','grm_demande_cadeaux');

  echo "valider";