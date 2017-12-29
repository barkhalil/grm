<?php
/**
 * Created by PhpStorm.
 * User: LENOVO
 * Date: 07/02/2017
 * Time: 12:14
 */
try
{
    $db = new PDO('odbc:Driver=FreeTDS; Server=192.168.1.100; Port=51170; Database=VITAL; UID=CRM; PWD=Vital2017;');
    //    $db = new PDO('sqlsrv:server=tcp:192.168.1.100; Database=VITAL; "CRM"; PWD=Vital0000;');
}
catch(PDOException $exception)
{
    die("Unable to open database.<br />Error message:<br /><br />$exception.");
}