<?php

namespace quantiketape1;
require_once __DIR__.'/AbstractUIGenerator.php';
require_once __DIR__.'/PlateauQuantik.php';
require_once __DIR__.'/PieceQuantik.php';
require_once __DIR__.'/ArrayPieceQuantik.php';
require_once __DIR__.'/ActionQuantik.php';
require_once __DIR__.'/QuantikGame.php';
require_once __DIR__.'/Player.php';


class QuantikUIGenerator extends AbstractUIGenerator
{
    public static function getButtonClass(PieceQuantik $piece): string
    {
        if ($piece->getCouleur() == PieceQuantik::VOID) {
            return "vide";
        } else {
            $forme = $piece->formeToString($piece->getForme()) . $piece->couleurToString($piece->getCouleur());
            return $forme;
        }
    }

    public static function getDivPiecesDisponibles(ArrayPieceQuantik $pieces): string
    {
        // Ajoutez une classe à la div contenant les pièces
        $div = "<table>";
        for ($i = 0; $i < $pieces->count(); $i += 2) {
            // Ouvrir une nouvelle ligne à chaque itération
            $div .= "<tr>";
            // Première pièce
            $piece1 = $pieces->getPieceQuantik($i);
            $forme1 = $piece1->formeToString($piece1->getForme());
            $couleur1 = $piece1->couleurToString($piece1->getCouleur());
            $classePiece1 = "piece piece-$forme1-$couleur1"; // Formez le nom de la classe CSS
            $imgTag1 = self::getImageFromPiece($piece1); // Obtenir la balise <img> pour la pièce
            $div .= "<td><button type='submit' class='$classePiece1'  name='active' disabled>$imgTag1</button></td>";
            // Deuxième pièce
            if ($i + 1 < $pieces->count()) {
                $piece2 = $pieces->getPieceQuantik($i + 1);
                $forme2 = $piece2->formeToString($piece2->getForme());
                $couleur2 = $piece2->couleurToString($piece2->getCouleur());
                $classePiece2 = "piece piece-$forme2-$couleur2"; // Formez le nom de la classe CSS
                $imgTag2 = self::getImageFromPiece($piece2); // Obtenir la balise <img> pour la pièce
                $div .= "<td><button type='submit' class='$classePiece2'  name='active' disabled>$imgTag2</button></td>";
            }
            // Fermer la ligne à chaque itération
            $div .= "</tr>";
        }
        $div .= "</table></div>";
        return $div;
    }


    public static function getFormSelectionPiece(ArrayPieceQuantik $pieces): string
    {
        // Ajoutez une classe à la div contenant les pièces

        // Ajoutez une classe à la div contenant les pièces
        $formulaire = "<table>";
        for ($i = 0; $i < $pieces->count(); $i += 2) {
            $formulaire .= "<form method='post' action='../controlleur/traiteFormQuantik.php'><table><tr>";
            $formulaire.= "<input type='hidden' name='action' value='choisirPiece'>";

            // Ouvrir une nouvelle ligne à chaque itération
            $formulaire .= "<tr>";
            // Première pièce
            $piece1 = $pieces->getPieceQuantik($i);
            $forme1 = $piece1->formeToString($piece1->getForme());
            $couleur1 = $piece1->couleurToString($piece1->getCouleur());
            $classePiece1 = "piece piece-$forme1-$couleur1"; // Formez le nom de la classe CSS
            $imgTag1 = self::getImageFromPiece($piece1); // Obtenir la balise <img> pour la pièce
            $formulaire .= "<td><button  type='submit' name='case' value='$i' class='$classePiece1'>$imgTag1</button></td>";
            // Deuxième pièce
            if ($i + 1 < $pieces->count()) {
                $piece2 = $pieces->getPieceQuantik($i + 1);
                $forme2 = $piece2->formeToString($piece2->getForme());
                $couleur2 = $piece2->couleurToString($piece2->getCouleur());
                $classePiece2 = "piece piece-$forme2-$couleur2"; // Formez le nom de la classe CSS
                $imgTag2 = self::getImageFromPiece($piece2); // Obtenir la balise <img> pour la pièce
                $formulaire .= "<td><button type='submit'  name='case' value='$i' class='$classePiece2' >$imgTag2</button></td>";
            }
            // Fermer la ligne à chaque itération
            $formulaire .= "</tr>";
        }
        $formulaire .= "</tr></table></div>";
        return $formulaire;
    }


    public
    static function getFormBoutonAnnulerChoixPiece(): string
    {
        $formulaire = "<div class ='button-Container'> ";
        $formulaire .= "<form method='post' action='../controlleur/traiteFormQuantik.php'>";
        $formulaire .= "<button  class ='buttonAnnuler' type='submit' name='annuler_selection' value='true' >Changer de pièce</button>";
        $formulaire .= "<input type='hidden' name='action' value='AnnulerChoix'>";
        $formulaire .= "</form></div>";
        return $formulaire;
    }
    /*public static function getDivMessageVictoire(int $couleur): string
    {
        $message = ($couleur == PieceQuantik::WHITE) ? "Blanches" : "Noirs";
        $div = "<div>$message</div>";
        $div .= self::getLienRecommencer();
        return $div;
    }*/

    public static function getLienRecommencer(): string
    {
        $lien ="<div class='recommencer-container'>";
        $lien .= " <form method='post' action='../controlleur/traiteFormQuantik.php'>
    <input type='hidden' name='action' value='home'>
    <button type='submit' class='body-button'>Recommencer</button>
</form>";


        return $lien;
    }

    public static function getPageSelectionPiece(QuantikGame $game, int $couleur): string
    {
        $page = self::getDebutHTML("selection piece");
        $joueurBlanc = $game->getPlayer(0);
        $joueurNoir = $game->getPlayer(1);
        $nomJoueurBlanc = htmlspecialchars($joueurNoir->getName());
        $nomJoueurNoir = htmlspecialchars($joueurBlanc->getName());
        $photoJoueurBlanc = "../Ressources/Images/player.png";
        $photoJoueurNoir = "../Ressources/Images/player.png";


        $page .= "<div class='icon-container'>";
        $page .= "<form method='post' action='../controlleur/traiteFormQuantik.php'>";
        $page .= "<button type='submit' style='border: none; background: none; padding: 0; cursor: pointer;'>"; // Bouton sans bordure ni arrière-plan
        $page .= "<img src='../Ressources/Images/iconHome.png' alt='Icône'>"; // Icône à l'intérieur du bouton
        $page .= "</button>";
        $page .= "<input type='hidden' name='action' value='home'>";
        $page .= "</form>";
        $page .= "</div>";
        $page .="<div class ='titre'> Selectionnez une pièce</div>";
        // Ajout des informations du joueur blanc

        $page .="<div class='containerJeu'>";
        $page.="<div class='element-container'>";
         $page .="<div class='element-wrapper'>";
        if ($couleur == PieceQuantik::WHITE) {

            $page .="<div class='element'>";
            $page.="<div class='container-photo'>";
            $page .= "<div class='player-info'>";
            $page .= "<img src='$photoJoueurBlanc' alt='Photo joueur blanc' class='player-photo'>";
            $page .= "<p class='player-name'>$nomJoueurBlanc</p>";
            $page .= "</div></div>";
            $page.="<div class='container-piece'>";
            $page .= self::getFormSelectionPiece($game->piecesBlanches);
            $page .="</div></div>";
            $page .="<div class='element'>";
            $page .= self::getDivPlateauQuantik($game->plateau);
            $page .="</div><div class='element'>";
            $page.="<div class='container-photo'>";
            $page.="<div class='player-info'>";
            $page .= "<img src='$photoJoueurNoir' alt='Photo joueur noir' class='player-photo'>";
            $page .= "<p class='player-name'>$nomJoueurNoir</p>";
            $page .= "</div></div>";
            $page.="<div class='container-piece'>";
            $page .= self::getDivPiecesDisponibles($game->piecesNoires);
            $page .="</div></div>";
        } else {
            $page .="<div class='element'>";
            // Ajout des informations du joueur noir
            $page.="<div class='container-photo'>";
            $page .= "<div class='player-info'>";
            $page .= "<img src='$photoJoueurNoir' alt='Photo joueur noir' class='player-photo'>";
            $page .= "<p class='player-name'>$nomJoueurNoir</p>";
            $page .= "</div></div>";
            $page.="<div class='container-piece'>";
            $page .= self::getFormSelectionPiece($game->piecesNoires);
            $page .="</div></div>";
            $page .="<div class='element'>";
            $page .= self::getDivPlateauQuantik($game->plateau);

            $page .="</div><div class='element'>";
            $page.="<div class='container-photo'>";
            $page .= "<div class='player-info'>";
            $page .= "<img src='$photoJoueurBlanc' alt='Photo joueur blanc' class='player-photo'>";
            $page .= "<p class='player-name'>$nomJoueurBlanc</p>";
            $page .= "</div></div>";
            $page.="<div class='container-piece'>";
            $page .= self::getDivPiecesDisponibles($game->piecesBlanches);
            $page .="</div></div>";
        }

        $page .= "</div>"; // Fin de element-container
        $page .= "</div></div>"; // Fin de containerJeu

        $page .= self::getFinHTML();
        return $page;


    }
    public static function getPagePosePiece(QuantikGame $game, int $couleur, int $position): string
    {
        $joueurBlanc = $game->getPlayer(0);
        $joueurNoir = $game->getPlayer(1);
        $nomJoueurBlanc = htmlspecialchars($joueurNoir->getName());
        $nomJoueurNoir = htmlspecialchars($joueurBlanc->getName());
        $photoJoueurBlanc = "../Ressources/Images/player.png";
        $photoJoueurNoir = "../Ressources/Images/player.png";

        $page = self::getDebutHTML("pose piece");
        $page .= "<div class='icon-container'>";
        $page .= "<form method='post' action='../controlleur/traiteFormQuantik.php'>";
        $page .= "<button type='submit' style='border: none; background: none; padding: 0; cursor: pointer;'>"; // Bouton sans bordure ni arrière-plan
        $page .= "<img src='../Ressources/Images/iconHome.png' alt='Icône'>"; // Icône à l'intérieur du bouton
        $page .= "</button>";
        $page .= "<input type='hidden' name='action' value='home'>";
        $page .= "</form>";
        $page .= "</div>";
        $page .="<div class ='titre'> Posez la pièce</div>";
        $page .="<div class='containerJeu'>";
        $page.="<div class='element-container'>
        <div class='element-wrapper'>";
        $page .="<div class='element'>";
        $page.="<div class='container-photo'>";
        $page .= "<div class='player-info'>";
        $page .= "<img src='$photoJoueurNoir' alt='Photo joueur Noir' class='player-photo'>";
        $page .= "<p class='player-name'>$nomJoueurNoir</p>";
        $page .= "</div></div>";
        $page.="<div class='container-piece'>";
        $page .= self::getDivPiecesDisponibles($game->piecesNoires);
        $page .= "</div></div>";
        $page .="<div class='element'>";
        if ($couleur == PieceQuantik::WHITE) {
            $page .= self::getFormPlateauQuantik($game->plateau, $game->piecesBlanches->getPieceQuantik($position));
        } else {
            $page .= self::getFormPlateauQuantik($game->plateau, $game->piecesNoires->getPieceQuantik($position));

        }
        $page .="</div><div class='element'>";
        $page.="<div class='container-photo'>";
        $page .= "<div class='player-info'>";
        $page .= "<img src='$photoJoueurBlanc' alt='Photo joueur blanc' class='player-photo'>";
        $page .= "<p class='player-name'>$nomJoueurBlanc</p>";
        $page .= "</div></div>";
        $page.="<div class='container-piece'>";
        $page .= self::getDivPiecesDisponibles($game->piecesBlanches);
        $page.="</div></div></div></div></div>";
        $page .= self::getFinHTML();
        return $page;
    }
    public static function getPageVictoire(QuantikGame $game, int $couleur): string
    {
        $currentPlayer = $game->getPlayer($game->getCurrentPlayer());
        $page = self::getDebutHTML("victoire");
        /*$winnerPlayerId= $currentPlayer->getId();
        //echo $winnerPlayerId;
        PDOQuantik::updateScore($_SESSION['gameid'], $winnerPlayerId);
        $scores = PDOQuantik::getScoresByGameId($_SESSION['gameid']);
        $scoreJoueurBlanc = $scores['scoreplayerone'];
        $scoreJoueurNoir = $scores['scoreplayertwo'];

        // Récupérer les scores
        /*$page .= "<div class='score'>";
        $page .= "<div>Score</div>";
        $page .= "<div>Noir  ".$scoreJoueurBlanc." -". $scoreJoueurNoir." Blanc</div>";

        $page .= "</div>";*/


        // Détermination du titre en fonction du résultat
        $titre = ($currentPlayer->getId() != $_SESSION['player']->getId()) ? "Victoire" : "Défaite";
        $page .= "<div class='titre'>$titre</div>";
        // Affichage du message de victoire ou de défaite
        $page .= ($currentPlayer->getId() != $_SESSION['player']->getId()) ?
            "<div class='message-victoire'  <div class='confetti-piece'>
 <div class='confetti-piece'></div>
  <div class='confetti-piece'></div>
  <div class='confetti-piece'></div>
  <div class='confetti-piece'></div>
  <div class='confetti-piece'></div>
  <div class='confetti-piece'></div>
  <div class='confetti-piece'></div>
  <div class='confetti-piece'></div>
  <div class='confetti-piece'></div>
  <div class='confetti-piece'></div>
  <div class='confetti-piece'></div>
  <div class='confetti-piece'></div>
  <div class='confetti-piece'></div>
  Félicitations ! Vous avez gagné ".self::getLienRecommencer()."</div>" :
            "<div class='message-defaite'> Oups ! Vous avez perdu".self::getLienRecommencer(). "</div>";


        $page .= "<div class='icon-container'>";
        $page .= "<form method='post' action='../controlleur/traiteFormQuantik.php'>";
        $page .= "<button type='submit' style='border: none; background: none; padding: 0; cursor: pointer;'>"; // Bouton sans bordure ni arrière-plan
        $page .= "<img src='../Ressources/Images/iconHome.png' alt='Icône'>"; // Icône à l'intérieur du bouton
        $page .= "</button>";
        $page .= "<input type='hidden' name='action' value='home'>";
        $page .= "</form>";
        $page .= "</div>";

        $page .= "<div class='containerJeu'>
                <div class='element-container'>
                    <div class='element-wrapper'>
                        <div class='element'>";
        $page .= self::getDivPiecesDisponibles($game->piecesBlanches);
        $page .= "</div><div class='element'>";
        $page .= self::getDivPlateauQuantik($game->plateau);
        $page .= "</div><div class='element'>";
        $page .= self::getDivPiecesDisponibles($game->piecesNoires);
        $page .= "</div></div></div></div>";




        $page .= self::getFinHTML();

        return $page;
    }

    static function getPagePartieEncours(QuantikGame $game, Player $currentPlayer): string
    {
        $joueurBlanc = $game->getPlayer(0);
        $joueurNoir = $game->getPlayer(1);
        $nomJoueurBlanc = htmlspecialchars($joueurNoir->getName());
        $nomJoueurNoir = htmlspecialchars($joueurBlanc->getName());
        $photoJoueurBlanc = "../Ressources/Images/player.png";
        $photoJoueurNoir = "../Ressources/Images/player.png";
        $page = self::getDebutHTML("Partie en cours");
        $page .= "<div class='titre'>Partie en cours</div>";
        $page .= "<div class='icon-container'>";
        $page .= "<form method='post' action='../controlleur/traiteFormQuantik.php'>";
        $page .= "<button type='submit' style='border: none; background: none; padding: 0; cursor: pointer;'>"; // Bouton sans bordure ni arrière-plan
        $page .= "<img src='../Ressources/Images/iconHome.png' alt='Icône'>"; // Icône à l'intérieur du bouton
        $page .= "</button>";
        $page .= "<input type='hidden' name='action' value='home'>";
        $page .= "</form>";
        $page .= "</div>";
        $page .= "<h1 style = 'color: rgba(26,26,37,0.5);'> <span style='color: #ef6d08;'>" . $currentPlayer->getName() . "</span> est entrain de jouer...</h1>";

        $page .="<div class='containerJeu'>
        <div class='element-container'>
        <div class='element-wrapper'>";
        $page .="<div class='element'>";

        $page.="<div class='container-photo'>";
        $page .= "<div class='player-info'>";
        $page .= "<img src='$photoJoueurBlanc' alt='Photo joueur Blanc' class='player-photo'>";
        $page .= "<p class='player-name'>$nomJoueurBlanc</p>";
        $page .= "</div></div>";
        $page.="<div class='container-piece'>";
         $page .= self::getDivPiecesDisponibles($game->piecesBlanches);
        $page .="</div></div>";
        $page .="<div class='element'>";
        $page .= self::getDivPlateauQuantik($game->plateau);
        $page .="</div>";
        $page .="<div class='element'>";
        $page .= "<div class='player-info'>";
        $page.="<div class='container-photo'>";
        $page .= "<img src='$photoJoueurNoir' alt='Photo joueur Noir' class='player-photo'>";
        $page .= "<p class='player-name'>$nomJoueurNoir</p>";
        $page .= "</div></div>";
        $page.="<div class='container-piece'>";
        $page .= self::getDivPiecesDisponibles($game->piecesNoires);
        $page .="</div></div>";
        $page .="</div></div></div>";
        $page .= self::getFinHTML();
        return $page;
    }
    public static function getDivPlateauQuantik(PlateauQuantik $plateau): string
    {
        $div = "<table class='plateau' border='1'> ";
        for ($i = 0; $i < PlateauQuantik::NB_ROWS; $i++) {
            $div .= "<tr>";
            for ($j = 0; $j < PlateauQuantik::NB_COLS; $j++) {
                $element = $plateau->getPiece($i, $j);
                $div .= "<td>". self::getImageFromPiece($element) . "</td>";
            }
            $div .= "</tr>";
        }
        $div .= " </table> ";
        return $div;
    }

    public static function getFormPlateauQuantik(PlateauQuantik $plateau, PieceQuantik $piece): string
    {
        $formulaire = self::getFormBoutonAnnulerChoixPiece();
        $formulaire .="<div class='plateau-container'>";
        $formulaire .= "<form method='post' action='../controlleur/traiteFormQuantik.php'>";
        $formulaire .= "<table class='plateau'>";
        $formulaire .= "<input type='hidden' name='action' value='poserPiece'>";
        $action = new ActionQuantik($plateau);
        for ($i = 0; $i < PlateauQuantik::NB_ROWS; $i++) {
            $formulaire .= "<tr>";
            for ($j = 0; $j < PlateauQuantik::NB_COLS; $j++) {
                $element = $plateau->getPiece($i, $j);
                if (!$action->isValidePose($i, $j, $piece)) {
                    $formulaire .= "<td><button class='casePasDisponibles' type='button' button type='submit' style='border: none; background: none; padding: 0; cursor: pointer;'disabled>";
                    $formulaire .= self::getImageFromPiece($element) . "</button></td>";
                } else {
                    if ($element->isEqual(PieceQuantik::initVoid())) {
                        $formulaire .= "<td><button class='caseDisponibles' type='submit' name='case' value='$i $j'>";
                        $formulaire .= self::getImageFromPiece($element) . "</button></td>";
                    } else {
                        $formulaire .= "<td><button type='button' disabled>";
                        $formulaire .= self::getImageFromPiece($element) . "</button></td>";
                    }
                }
            }
            $formulaire .= "</tr>";
        }
        $formulaire .= "</table>";
        $formulaire .= "</form></div>";


        return $formulaire;
    }
    private static function getImageFromPiece(PieceQuantik $piece): string
    {
        $src = "";// ou chemin
        switch ($piece->__toString()){
            case "(  )":
                $src .= "../Ressources/Images/void.png";
                break;
            case "(Cu:W)":
                $src .= "../Ressources/Images/cube_beige.png";
                break;
            case "(Co:W)":
                $src .= "../Ressources/Images/cone_beige.png";
                break;
            case "(Cy:W)":
                $src .= "../Ressources/Images/cylindre_beige.png";
                break;
            case "(Sp:W)":
                $src .= "../Ressources/Images/sphere_beige.png";
                break;
            case "(Cu:B)":
                $src .= "../Ressources/Images/cube_marron.png";
                break;
            case "(Co:B)":
                $src .= "../Ressources/Images/cone_marron.png";
                break;
            case "(Cy:B)":
                $src .= "../Ressources/Images/cylindre_marron.png";
                break;
            case "(Sp:B)":
                $src .= "../Ressources/Images/sphere_marron.png";
                break;
            default : break;
        }
        return "<img class='piece-images' src=' $src ' alt=' '>";

    }


}

