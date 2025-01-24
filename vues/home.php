<?php
require_once __DIR__.'/../src/Player.php';
require_once __DIR__.'/../src/PDOQuantik.php';
require_once __DIR__.'/../env/db.php';

use quantiketape1\PDOQuantik;

session_start();
// Vérification de la connexion de l'utilisateur
if (isset($_SESSION['player']) && isset($_SESSION['etat'])) {
    $playerName = $_SESSION['player']->getName();

    $parties = PDOQuantik::getAllGameQuantikByPlayerName($playerName);

    $debut = '
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Salon de jeu</title>
    <link rel="stylesheet" type="text/css" href="../Ressources/quantik.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>
<body>

     <div class="icon-container">
       <img src="../Ressources/Images/iconHome.png" alt="home">
    </div>   
<h1><span>Bienvenue</span>
<span>' . $playerName . '</span></h1>

<div class="tutorial-container">
    
        <h2> Objectif du jeu</h2>
        <p>Dans Quantik Game, l’objectif est d\'être le premier joueur à poser la quatrième forme différente
         d’une ligne, d’une colonne ou d’une zone carrée.</p>
         <div class="video-button">
    <a href="https://www.youtube.com/watch?v=Cgicdw6j32o&pp=ygULamV1IHF1YW50aWs%3D" target="_blank">Visionner la vidéo</a>
</div>

  </div>

    
 <div class="container">
    <div class="card-container">
     <div class="card-wrapper">
     <div class="card">
      <div class="card-front">
      <p>Créer une nouvelle Partie</p>      
      <i class="arrow-icon fas fa-arrow-right"></i>
      <i class= "iconNewGame"></i>
</div>
<div class="card-back">

<form action="../controlleur/traiteFormQuantik.php" method="post">
    <input type="hidden" name="action" value="constructed">
    <button  type="submit" class="menu-button">Creer une nouvelle partie</button>
</form>
</div>
</div>
</div>
<!-- PARTIE EN COURS -->
<div class="card-wrapper">
     <div class="card">
      <div class="card-front">
      <p>Parties en cours</p>      
      <i class="arrow-icon fas fa-arrow-right"></i>
       <i class= "iconAttente"></i>
</div>
<div class="card-back" style="max-height: 200px; overflow-y: auto;">
            <form action="../controlleur/traiteFormQuantik.php" method="post">';

                    foreach ($parties as $partie) {
                        $joueur = PDOQuantik::selectPlayerNameByID($partie["playerone"]);
                        if ($partie["gamestatus"] === 'initialized') {

    $debut .='<button type="submit"  class="menu-button" name="gameid" value="' . $partie["gameid"] . '">
        Partie   <b>'. $partie["gameid"] . ' </b> initialisée par '.' <b>'.$joueur.'</b> 
    </button>';
                             }
                    }
$debut.='<input type="hidden" name="action" value="waitingForPlayer">
</form>

</div>
</div>
</div>
<div class="card-wrapper">
     <div class="card">
      <div class="card-front">
      <p>Parties en attente <br>d\'un second joueur</p>
   <p style="display: inline; margin-right: 5px;">Rejoindre</p>   
<i class="arrow-icon fas fa-arrow-right"></i>
   <i class= "iconJoin"></i>
      
</div>
<div class="card-back" style="max-height: 200px; overflow-y: auto;">';

//Formulaire pour rejoindre les parties en attente dun deuxieme joueur
    $parties1 = PDOQuantik::getAllGameQuantik();
    $debut .= '<form action="../controlleur/traiteFormQuantik.php" method="post">';

    foreach ($parties1 as $partie) {
        $joueur = PDOQuantik::selectPlayerNameByID($partie["playerone"]);

        if ($partie["gamestatus"] === 'constructed') {
            if ($_SESSION['player']->getId() === $partie["playerone"]) {
                $debut .= '
    <button type="submit"  class="menu-button" name="gameid" value="' . $partie['gameid'] . '" disabled >

        Partie ' . $partie["gameid"] . ': <b>'.$joueur.'</b> en attente d\'un autre joueur
  </button><br/>';
            } else {
                $debut .= '
    <button type="submit" class="menu-button" name="gameid" value="' . $partie["gameid"] . '" >
    
        Partie ' . $partie["gameid"] . ' : <b>'.$joueur.'</b> en attente d\'un autre joueur
    </button><br/>';
            }
        }
    }

    $debut .= '
    <input type="hidden" name="action" value="initialized">
</form>


</div>
</div>
</div>

<div class="card-wrapper">
     <div class="card">
      <div class="card-front">
      <p>Parties Terminées</p>      
      <i class="arrow-icon fas fa-arrow-right"></i>
       <i class= "iconOver"></i>
</div>
<div class="card-back">

<form action="../controlleur/traiteFormQuantik.php" method="post">';

    foreach ($parties as $partie) {
        $joueur = PDOQuantik::selectPlayerNameByID($partie["playerone"]);
        if ($partie["gamestatus"] === 'finished' )  {
            $debut .= '
    <button type="submit"  class="menu-button" name="gameid" value="' . $partie["gameid"] . '">
        Partie ' . $partie["gameid"] .' terminée  ' . '
    </button>';
        }
    }

    $debut .= '
    <input type="hidden" name="action" value="finished">
</form>



</div>
</div>
</div>

</div>
</div>';


    $fin = '
<footer class="footer">
 @M.melissa & R.Dicard
    </footer></body></html>';

      $form5= '<div class="logout-container">
<form  action="../controlleur/traiteFormQuantik.php" method="post">
<input type="hidden" name="action" value="deconnecter">
<button type="submit" class="body-button">Deconnexion</button>
</form>
</div>';





    echo $debut.$form5 . $fin.'<script> setInterval(function() { location.reload(); }, 30000); </script>';
} else {
    header('Location: login.php');
    exit();
}
?>