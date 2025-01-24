<?php

namespace quantiketape1;
require_once __DIR__.'/../src/PDOQuantik.php';
require_once __DIR__.'/../src/Player.php';
require_once __DIR__.'/../env/db.php';
session_start();
if (isset($_REQUEST['playerName'])) {
    // recupère le joueur dans la base de donnée par son nom
    $player = PDOQuantik::selectPlayerByName($_REQUEST['playerName']);
    if (is_null($player)) {
        $player = PDOQuantik::createPlayer($_REQUEST['playerName']); //ajoute le joueur dans la bd s'il n'existait pas
    }
    $_SESSION['player'] = $player;
    $_SESSION['etat'] = "home";
    header('HTTP/1.1 303 See Other');
    header("Location: index.php"); // redirection vers la page de gestion des etats index.php

} else {
    echo getPageLogin(); // formulaire de cconnexion
}

function getPageLogin(): string
{
    $form = '<!DOCTYPE html>
<html class="no-js" lang="fr" dir="ltr">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="Author" content="Dominique Fournier" />
    <link rel="stylesheet" href="../Ressources/quantik.css" />
    <title>Accès à la salle de jeux</title></head>
    
<body>
<div class="main-login">
        <div class="left-login">
            <h1><br>Acces à la salle de jeu</h1>
            <img src="../Ressources/Images/jeuQ.png" class="left-login-image" alt="PlateauQuantik">
            </div>
        <div class="right-login">
            <div class="card-login">
            <h1 class="login-heading">LOGIN</h1>
               
                <div class="textfield">
                   
<form action="' . $_SERVER['PHP_SELF'] . '" method="post">
<fieldset><legend>Nom</legend>

          <input type="text" name="playerName" placeholder="nom" />
          <input type="submit" name="action" value="connecter"></fieldset></form>
 </div></body></html>';

    return $form;
}