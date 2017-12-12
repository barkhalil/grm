<?php
/**
 * Created by PhpStorm.
 * User: T-Yazen
 * Date: 12/12/2016
 * Time: 22:44
 */
//phpinfo();
/*
$serverName = "T-Yazen-PC\sqlexpress"; //serverName\instanceName
$connectionInfo = array( "Database"=>"crm_sql", "UID"=>"sa", "PWD"=>"yazen2016");
$conn = sqlsrv_connect( $serverName, $connectionInfo);

if( $conn ) {
    echo "Connexion établie.<br />";
}else{
    echo "La connexion n'a pu être établie.<br />";
    die( print_r( sqlsrv_errors(), true));
}*/
//mssql_query(SELECT tbDocs FROM View_Technical_Service);
ini_set('display_errors','On');
error_reporting(E_ALL);
//print_r(PDO::getAvailableDrivers());
echo "<br/>";
# connect to a DSN "DSN_NAME" with a user "Bob" and password "Marley"
$connect = odbc_connect("CRMCONNECT", "CRM", "Vital0000");

if($connect){
	echo "good";
    $query_string="SELECT ALL Numero, Type, Date, Client, RS, Timbre,THT, Fodec, TVA, TTC, Reliquat, Sens  FROM FactureE WHERE CLient = '411C010' ORDER BY FactureE.Date";
   // $query_string="SELECT @@Version as SQL_VERSION";
    $res = odbc_prepare($connect, $query_string);
    if(!$res) die("could not prepare statement ".$query_string);
    $resAr=odbc_exec($connect,$query_string);
    while (odbc_fetch_row($resAr))
    {
       // $result = odbc_result($resAr,);
        $row = odbc_fetch_array($resAr);
        echo "<pre>";
      //  print_r($row);
        echo '</pre>';
    }
    if(odbc_execute($res)) {
        $row = odbc_fetch_array($res);
       // print_r($row);
    } else {
        // handle error
    }

}

# close the connection
odbc_close($connect);
try
{
    $db = new PDO('odbc:Driver=FreeTDS; Server=192.168.1.100; Port=51170; Database=VITAL; UID=CRM; PWD=Vital0000;');
}
catch(PDOException $exception)
{
    die("Unable to open database.<br />Error message:<br /><br />$exception.");
}
echo '<h1>Successfully connected!</h1>';
$query = "SELECT * FROM FactureE  ORDER BY FactureE.Date";
$statement = $db->prepare($query);
$statement->execute();
$result = $statement->fetchAll(PDO::FETCH_ASSOC);
echo count($result); echo '<br/><pre>';
print_r($result);
echo "</pre>";