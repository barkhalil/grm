<?php
/**
 * Created by PhpStorm.
 * User: T-Yazen
 * Date: 18/12/2016
 * Time: 00:49
 */
session_start();
ini_set('display_errors','On');
error_reporting(E_ALL & ~E_NOTICE);
$Client=filter_input(INPUT_GET,'id',FILTER_SANITIZE_STRING);

if($Client):
    try
    {
        $db = new PDO('odbc:Driver=FreeTDS; Server=192.168.1.100; Port=51170; Database=VITAL; UID=CRM; PWD=Vital0000;');
    }
    catch(PDOException $exception)
    {
        die("Unable to open database.<br />Error message:<br /><br />$exception.");
    }
    echo '<h1>Successfully connected!</h1>';
    $query = "SELECT * FROM FactureE WHERE Client = '$Client' ORDER BY FactureE.Date ASC";
    $statement = $db->prepare($query);
    $statement->execute();
    $result = $statement->fetchAll(PDO::FETCH_ASSOC);
    echo "Nombre de factures ".count($result);
    ?>
    <h3>Liste Facture du client <?=$Client?></h3>
    <table class="table table-bordered" border="1">
        <thead>
        <tr>
            <th>#</th>
            <th>Numero</th>
            <th>Type</th>
            <th>Date</th>
            <th>Client</th>
            <th>RS</th>
            <th>Timbre</th>
            <th>THT</th>
            <th>Fodec</th>
            <th>TVA</th>
            <th>TTC</th>
            <th>Replicat</th>
            <th>Sens</th>
        </tr>
        </thead>
        <tbody>
    <?$THT=0;$TTC=0;$i=1;

    foreach ($result as $row):
?>
        <tr>
            <td><? echo $i++;?></td>
            <td><?=$row['Numero']?></td>
            <td><?=$row['Type']?></td>
            <td><?=$row['Date']?></td>
            <td><?=$row['Client']?></td>
            <td><?=$row['RS']?></td>
            <td><?=$row['Timbre']?></td>
            <td><?$THT+=$row['THT'];
                echo $row['THT']?></td>
            <td><?=$row['Fodec']?></td>
            <td><?=$row['TVA']?></td>
            <td><? $TTC +=$row['TTC'];
                echo $row['TTC']?></td>
            <td><?=$row['Reliquat']?></td>
            <td><?=$row['Sens']?></td>

        </tr>
    <?php
    endforeach;
    ?>
        </tbody>
        <tfoot>
        <tr>
            <td colspan="6"></td>
            <td>Total THT</td>
            <td><?=$THT?></td>
            <td></td>
            <td>Total TTC</td>
            <td><?=$TTC?></td>
            <td></td>
            <td></td>
        </tr>
        </tfoot>
    </table>
    <?
        foreach ($result as $row):
        $querys = "SELECT * FROM FactureD WHERE Numero = '".$row['Numero']."'";
        $statements = $db->prepare($querys);
        $statements->execute();
        $results = $statements->fetchAll(PDO::FETCH_ASSOC);
        ?>
        <h3>Liste Détails facture N° <?=$row['Numero']?> </h3>
        <table class="table table-bordered" border="1">
            <thead>
            <tr>
                <th>Numero</th>
                <th>Article</th>
                <th>Description</th>
                <th>QStock</th>
                <th>QPrix</th>
                <th>Unite</th>
                <th>PUHTB</th>
                <th>TxRem</th>
                <th>Rem</th>
                <th>TxTVA</th>
                <th>THT</th>
                <th>TVA</th>
                <th>TTC</th>
                <th>Statut</th>
                <th>Sens</th>
            </tr>
            </thead>
            <tbody>
            <?$THTs=0;$TTCs=0;
            foreach($results as $rows){
                ?>
                <tr>
                    <td><?=$rows['Numero']?></td>
                    <td><?=$rows['Article']?></td>
                    <td><?=utf8_encode($rows['Des'])?></td>
                    <td><?=$rows['QStock']?></td>
                    <td><?=$rows['QPrix']?></td>
                    <td><?=$rows['unite']?></td>
                    <td><?=$rows['PUHTB']?></td>
                    <td><?=$rows['TxRem']?></td>
                    <td><?=$rows['Rem']?></td>
                    <td><?=$rows['TxTVA']?></td>
                    <td><?$THTs+=$rows['THT'];
                        echo $rows['THT']?></td>
                    <td><?=$rows['TVA']?></td>
                    <td><? $TTCs +=$rows['TTC'];
                        echo $rows['TTC']?></td>
                    <td><?=$rows['Statut']?></td>
                    <td><?=$rows['sens']?></td>
                </tr>
            <?   }   ?>
            </tbody>
            <tfoot>
            <tr>
                <td colspan="8"></td>
                <td>Total THT</td>
                <td><?=$THTs?></td>
                <td>Total TTC</td>
                <td><?=$TTCs?></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            </tfoot>
        </table>
        <?php

    endforeach;


else:
    echo '<h3>Code Client est introuvable</h3>';
endif;