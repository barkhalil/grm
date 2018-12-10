<?php
/**
 * Created by IntelliJ IDEA.
 * User: KHALIL
 * Date: 07/12/2018
 * Time: 13:02
 */
$p=filter_input(INPUT_POST,'p',FILTER_VALIDATE_INT);


$_SESSION['cdxSansPB']=$p;
