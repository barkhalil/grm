<?php
try {
    $PDO = new PDO('mysql:host=127.0.0.1;dbname=crm', 'crm', 'CrmVit@17',array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
    $PDO->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
    $PDO->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);
    $PDO->setAttribute(PDO::MYSQL_ATTR_USE_BUFFERED_QUERY, true);
    $PDO->query("SET NAMES 'utf8'");
} catch (PDOException $exc) {
    // print_r($exc);
    $_SESSION['msg']="Problème de connexion";
    $_SESSION['type']="alert-danger";
    // echo '<h1 class="text-red center-block text-center">Connexion Immpossible</h1>';
}