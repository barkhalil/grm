<?php

/**
 * Created by PhpStorm.
 * User: LENOVO
 * Date: 07/02/2017
 * Time: 15:41
 */
class Ventes
{
public function getVentesByYears($Years,$Art){
    try
    {
        $db = new PDO('odbc:Driver=FreeTDS; Server=192.168.1.100; Port=51170; Database=VITAL; UID=CRM; PWD=Vital2017;');
        //    $db = new PDO('sqlsrv:server=tcp:192.168.1.100; Database=VITAL; "CRM"; PWD=Vital0000;');
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
        $db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);
        $db->setAttribute(PDO::MYSQL_ATTR_USE_BUFFERED_QUERY, true);
        // $db->query("SET NAMES 'utf8'");
    }
    catch(PDOException $exception)
    {
        die("Unable to open database.<br />Error message:<br /><br />$exception.");
    }
    $query = "SELECT SUM(FactureD.QPrix) as TotProd FROM FactureD LEFT JOIN FactureE on FactureD.Numero=FactureE.Numero WHERE year(FactureE.Date) = $Years AND FactureD.Article='$Art' AND FactureD.TxRem < 100 GROUP BY FactureD.Article, FactureD.Des";
    $statement = $db->prepare($query);
    $statement->execute();
    $result = $statement->fetch(PDO::FETCH_OBJ)->TotProd;
    return $result;
}
}
$VentesC=new Ventes();