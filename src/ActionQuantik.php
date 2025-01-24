<?php

namespace quantiketape1;
require_once __DIR__.'/PieceQuantik.php';
require_once __DIR__.'/ArrayPieceQuantik.php';
require_once __DIR__.'/PlateauQuantik.php';
class ActionQuantik
{

    protected PlateauQuantik $plateau;




//Constructeur
    public function __construct(PlateauQuantik $plateau)
    {
        $this->plateau = $plateau;
    }




//getter

    /**
     * retourne le plateau
     * @return PlateauQuantik
     */
    public function getPlateau(): PlateauQuantik
    {
        return $this->plateau;
    }



 /**méthodes qui retourne vrai si le joueur a gagner , sinon retourne false
elle vérifiee que la ligne contient des formes différentes .*/
    public function isRowWin(int $rowNum): bool
    {
        return self::isComboWin($this->plateau->getRow($rowNum));
    }
    public function isColWin(int $colNum): bool {
        return self::isComboWin($this->plateau->getCol($colNum));
    }
    public function isCornerWin(int $dir): bool {
        return self::isComboWin($this->plateau->getCorner($dir));
    }

   /**
    * Retoune vrai si les condtions d'une victoire sont réunis,  retourne faux sinon .
  */

    private static function isComboWin(ArrayPieceQuantik $pieces): bool
    {
        for ($i = 0; $i < 4; $i++) {
            if ($pieces->getPieceQuantik($i)->getForme() == PieceQuantik::VOID) {
                return false;
            }
            for ($j = $i + 1; $j < 4; $j++) {
                if ($pieces->getPieceQuantik($i)->getForme() == $pieces->getPieceQuantik($j)->getForme()) {
                        return false;
                }
            }
        }
        return true;
    }


    // verifie si la position recherchée sur le plateau est valide.
    public function isValidePose(int $rowNum,int $colNum, PieceQuantik $piece):bool
    {
        if($this->plateau->getPiece($rowNum,$colNum)->isEqual($piece)){
            return false;
        }else{
            $ligne = $this->plateau->getRow($rowNum);
            $col = $this->plateau->getCol($colNum);
            $corner = $this->plateau->getCorner($this->plateau->getCornerFromCoord($rowNum, $colNum));
            if(!ActionQuantik::isPieceValide($ligne,$piece) || !ActionQuantik::isPieceValide($col,$piece) || !ActionQuantik::isPieceValide($corner,$piece))
            {
                return false;
            } else {
                return true;
            }

        }

    }



    // permet de poser une piéce dans le  plateau

    public function posePiece(int $rowNum, int $colNum, PieceQuantik $piece)
    {
        if ( $this->isValidePose($rowNum, $colNum, $piece) )
            $this->plateau->setPiece($rowNum, $colNum, $piece);
    }



    /**
      Vérifie si on peut poser une forme dans un tableau.
     * elle retourne vrai si on peut poser la piece sinon retourne faux.
     */
    private static function isPieceValide(ArrayPieceQuantik $tabpieces, PieceQuantik $piece): bool
    {
        for ($i = 0; $i < 4; $i++) {
            if ($tabpieces->getPieceQuantik($i)->getForme() == $piece->getForme() && $tabpieces->getPieceQuantik($i)->getCouleur() != $piece->getCouleur()) {
                return false;
            }
        }
        return true;
    }



    //Affichage
    public function __toString():string{
        return $this->plateau->__toString();
    }

}

/*
$plateau = new PlateauQuantik();
$plateau->setPiece(0,0,PieceQuantik::initWhiteCylindre());
$action = new ActionQuantik($plateau);
echo $action;
echo $action->isValidePose(3,3,PieceQuantik::initWhiteCylindre());*/