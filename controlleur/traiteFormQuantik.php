<?php
require_once __DIR__.'/../src/ActionQuantik.php';
require_once __DIR__.'/../src/PlateauQuantik.php';
require_once __DIR__.'/../src/QuantikGame.php';
require_once __DIR__.'/../src/PieceQuantik.php';
require_once __DIR__.'/../src/PDOQuantik.php';
require_once __DIR__.'/../src/EntiteGameQuantik.php';
require_once __DIR__.'/../env/db.php';


use \quantiketape1\PlateauQuantik;
use \quantiketape1\QuantikGame;
use \quantiketape1\PieceQuantik;
use \quantiketape1\ActionQuantik;
use \quantiketape1\PDOQuantik;

session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && isset($_SESSION['player'])) {
    switch ($_POST['action']) {
        case'choisirPiece':
            if (isset($_POST['case']) && isset($_SESSION['gameid'])) {
                $game = PDOQuantik::getGameQuantikById($_SESSION['gameid']);
                $game->setGameStatus("PosePiece");
                $_SESSION['gamestatus'] = 'PosePiece';
                $_SESSION['positionpiece'] = $_POST['case'];
            }
            header("Location: /quantiketape1/vues/index.php");
            exit;
            break;
        case 'poserPiece':
            if (isset($_POST['case']) && isset($_SESSION['gameid'])) {
                $game = PDOQuantik::getGameQuantikById($_SESSION['gameid']);
                if ($game->getGameStatus() != 'Victoire') {
                    list($ligne, $colonne) = explode(' ', $_POST['case']);
                    $courant = $game->getCurrentPlayer();
                    $couleurActive = $game->getCouleurPlayer($courant);
                    if ($couleurActive == PieceQuantik::BLACK) {
                        $piece = $game->piecesNoires->getPieceQuantik($_SESSION['positionpiece']);
                        $game->plateau->setPiece($ligne, $colonne, $piece);
                        $game->piecesNoires->removePieceQuantik($_SESSION['positionpiece']);
                        $game->setCurrentPlayer(PieceQuantik::BLACK);
                    } else {
                        $piece = $game->piecesBlanches->getPieceQuantik($_SESSION['positionpiece']);
                        $game->plateau->setPiece($ligne, $colonne, $piece);
                        $game->piecesBlanches->removePieceQuantik($_SESSION['positionpiece']);
                        $game->setCurrentPlayer(PieceQuantik::WHITE);
                    }
                    $action = new ActionQuantik($game->plateau);
                    $dir = PlateauQuantik::getCornerFromCoord($ligne, $colonne);
                    if ($action->isColWin($colonne) || $action->isRowWin($ligne) || $action->isCornerWin($dir)) {
                        $game->setGameStatus("Victoire");
                        $_SESSION['gamestatus'] = 'Victoire';
                        $_SESSION['etat'] = 'consulterPartieVictoire';
                        PDOQuantik::saveGameQuantik('finished', $game->getJson(), $_SESSION['gameid']);
                        //PDOQuantik::saveGameQuantiky(EntiteGameQuantik::fromQuantikGame($game,'finished'));

                    } else {
                        $game->setGameStatus("ChoixPiece");
                        $_SESSION['gamestatus'] = 'ChoixPiece';
                        PDOQuantik::saveGameQuantik('initialized', $game->getJson(), $_SESSION['gameid']);
                        //PDOQuantik::saveGameQuantiky(EntiteGameQuantik::fromQuantikGame($game,'initialized'));

                    }
                }else{
                    $_SESSION['etat'] = 'consulterPartieVictoire';
                }
            }
            header("Location: /quantiketape1/vues/index.php");
            exit();
            break;
        case 'AnnulerChoix':
            if (isset($_SESSION['gameid'])) {
                $game = PDOQuantik::getGameQuantikById($_SESSION['gameid']);
                $game->setGameStatus("ChoixPiece");
                $_SESSION['gamestatus'] = 'ChoixPiece';
            }
            header("Location: /quantiketape1/vues/index.php");
            exit();
            break;
        case 'home':
            unset($_SESSION['gameid']);
            unset($_SESSION['gamestatus']);
            unset($_SESSION['positionpiece']);
            $_SESSION['etat'] = 'home';
            session_write_close();
            header("Location: /quantiketape1/vues/index.php");
            exit();
            break;
        case 'constructed':
            $playerName = $_SESSION['player']->getName();
            $game = new QuantikGame([$_SESSION['player'], null]);
            PDOQuantik::createGameQuantik($playerName, $game->getJson());
            header("Location: /quantiketape1/vues/index.php");
            exit();
            break;
        case 'initialized':
            $gameiD = $_POST['gameid'];
            $game = PDOQuantik::getGameQuantikById($gameiD);
            $game->setPlayers($_SESSION['player']);
            PDOQuantik::addPlayerToGameQuantik($_SESSION['player']->getName(), $game->getJson(), $gameiD);
            header("Location: /quantiketape1/vues/index.php");

            break;
        case 'waitingForPlayer':
            $_SESSION['gameid'] = $_POST['gameid'];
            $_SESSION['etat'] = 'consultePartieEncours';
            $_SESSION['gamestatus'] = 'ChoixPiece';
            header("Location: /quantiketape1/vues/index.php");
            exit();
            break;
        case 'finished':
            $_SESSION['gameid'] = $_POST['gameid'];

            $_SESSION['etat'] = 'consulterPartieVictoire';
            header("Location: /quantiketape1/vues/index.php");
            exit();
            break;
        case 'deconnecter':
            session_unset();
            session_destroy();
            header("Location:  /quantiketape1/vues/login.php");
            break;
}
} else {
    header("Location:  /quantiketape1/vues/login.php");
    exit;
}
