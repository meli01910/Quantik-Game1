<?php
require_once __DIR__.'/../src/QuantikGame.php';
require_once __DIR__.'/../src/Player.php';
require_once __DIR__.'/../src/PlateauQuantik.php';
require_once __DIR__.'/../src/QuantikUIGenerator.php';
require_once __DIR__.'/../env/db.php';
require_once __DIR__.'/../src/PDOQuantik.php';


use quantiketape1\PDOQuantik;
use quantiketape1\QuantikUIGenerator;

session_start();
if (isset($_SESSION['etat']) && isset($_SESSION['player'])) {


    // partie accessible uniquement par la session
    switch ($_SESSION['etat']) {
        case 'home' :
            header('Location: home.php');
            exit();
            break;
        case 'consultePartieEncours':
            /* le jeu choisi dans la partie en cours de la page home sera consulté
            en fonction du joueur de la session
             * */
            $game = PDOQuantik::getGameQuantikById($_SESSION['gameid']);
            $status = $_SESSION['gamestatus'];
            $numCurrentPlayer = $game->getCurrentPlayer();
            $couleur = $game->getCouleurPlayer($numCurrentPlayer);
            $currentPlayer = $game->getPlayer($numCurrentPlayer);
            /* le joueur de la session joue uniquement lorsque c'est son tour et aussi lorsque la partie n'est pas terminé,pour cela on fait sur le status du jeu et sur le joueur courant
             * */
            if($_SESSION['player']->getId() === $currentPlayer->getId() && $game->getGameStatus() != 'Victoire'){
                switch ($status){
                    case 'ChoixPiece' :
                        echo QuantikUIGenerator::getPageSelectionPiece($game,$couleur); //page de selection d'une piece pour le joueur courant
                        break;
                    case 'PosePiece':
                        $position = $_SESSION['positionpiece'];
                        echo QuantikUIGenerator::getPagePosePiece($game,$couleur,$position); // page pour poser la piece selectionnée par le joueur courant
                        break;
                }

            }else { // lorsque ce n'est pas au tour du joueur de la session de jouer
                    if($game->getGameStatus() === 'Victoire'){
                        $winnerPlayerId = $game->getCurrentPlayer();

                        echo QuantikUIGenerator::getPageVictoire($game,$couleur); // si l'adverssaire a gagné apres avoir jouer
                        // Exemple de détermination du joueur gagnant

                    }else{
                        echo QuantikUIGenerator::getPagePartieEncours($game,$currentPlayer); // page d'attente quand l'adverssaire joue
                        echo '<script> setInterval(function() { location.reload(); }, 3000); </script>'; // script pour raffraichir la page afin de recuperer la partie jouée par l'adverssaire
                    }
            }
            break;
        case 'consulterPartieVictoire': // consulter les parties terminées
            $game = PDOQuantik::getGameQuantikById($_SESSION['gameid']);
            $numCurrentPlayer = $game->getCurrentPlayer();
            $couleur = $game->getCouleurPlayer($numCurrentPlayer);
            echo QuantikUIGenerator::getPageVictoire($game,$couleur);
            break;

    }



} else {
   header('Location: login.php'); // redirection vers la page de login si y'a pas de session
}
?>
