<?php

namespace quantiketape1;
require_once __DIR__.'/PieceQuantik.php';
require_once __DIR__.'/Player.php';

use ArrayAccess;
use Countable;
use OutOfBoundsException;

class ArrayPieceQuantik implements ArrayAccess, Countable
{
    protected array $pieceQuantik;


    public function __construct()
    {
        //initialisation du tableau
        $this->pieceQuantik = array();

    }

    public static function initPiecesBlanches(): ArrayPieceQuantik
    {
        // Initialise le tableau avec 8 pièces blanches.
        $arrayPieceQuantik = new ArrayPieceQuantik();

        // Ajoute deux cubes blancs
        $arrayPieceQuantik->addPieceQuantik(PieceQuantik::initWhiteCube());
        $arrayPieceQuantik->addPieceQuantik(PieceQuantik::initWhiteCube());

        // Ajoute deux cônes blancs
        $arrayPieceQuantik->addPieceQuantik(PieceQuantik::initWhiteCone());
        $arrayPieceQuantik->addPieceQuantik(PieceQuantik::initWhiteCone());

        // Ajoute deux cylindres blancs
        $arrayPieceQuantik->addPieceQuantik(PieceQuantik::initWhiteCylindre());
        $arrayPieceQuantik->addPieceQuantik(PieceQuantik::initWhiteCylindre());

        // Ajoute deux sphères blanches
        $arrayPieceQuantik->addPieceQuantik(PieceQuantik::initWhiteSphere());
        $arrayPieceQuantik->addPieceQuantik(PieceQuantik::initWhiteSphere());

        return $arrayPieceQuantik;
    }

    public function addPieceQuantik(PieceQuantik $piece): void
    {
        // permet d'ajouter la pièce à la fin du tableau.
        $this->pieceQuantik[] = $piece;

    }

    public static function initPiecesNoires(): ArrayPieceQuantik
    {
        // Initialise le tableau avec 8 pièces noires.
        $arrayPieceQuantik = new ArrayPieceQuantik();

        // Ajoute deux cubes noirs
        $arrayPieceQuantik->addPieceQuantik(PieceQuantik::initBlackCube());
        $arrayPieceQuantik->addPieceQuantik(PieceQuantik::initBlackCube());

        // Ajoute deux cônes noirs
        $arrayPieceQuantik->addPieceQuantik(PieceQuantik::initBlackCone());
        $arrayPieceQuantik->addPieceQuantik(PieceQuantik::initBlackCone());

        // Ajoute deux cylindres noirs
        $arrayPieceQuantik->addPieceQuantik(PieceQuantik::initBlackCylindre());
        $arrayPieceQuantik->addPieceQuantik(PieceQuantik::initBlackCylindre());

        // Ajoute deux sphères noires
        $arrayPieceQuantik->addPieceQuantik(PieceQuantik::initBlackSphere());
        $arrayPieceQuantik->addPieceQuantik(PieceQuantik::initBlackSphere());

        return $arrayPieceQuantik;
    }


    public function offsetUnset($offset): void
    {
        if ($this->offsetExists($offset)) {
            unset($this->pieceQuantik[$offset]);
        }else{
            throw new OutOfBoundsException("Offset $offset does not exist.");
        }

    }

    public function offsetExists($offset): bool
    {
        return isset($this->pieceQuantik[$offset]);
    }

    public function count(): int
    {
        // renvoie le nombre de pièces dans le tableau
        return count($this->pieceQuantik);
    }

    public function getPieceQuantik(int $pos): ?PieceQuantik
    {
            return ($this->offsetExists($pos))? $this->pieceQuantik[$pos]:null;
    }

    public function setPieceQuantik(int $pos, PieceQuantik $piece): void
    {
        $this->offsetSet($pos, $piece);
    }

    public function offsetGet($offset): ?PieceQuantik
    {
            return $this->pieceQuantik[$offset];
    }



    public function offsetSet($offset, $value): void
    {
        // permet d'ajouter $value à la position $offset
        $this->pieceQuantik[$offset] = $value;

    }

    public function removePieceQuantik(int $pos) : void
    {
        $this->offsetUnset($pos);

    }

    public function __toString(): string
    {
        // retourne un objet sous  forme d'une chaine de caractère

        $output = '';
        foreach ($this->pieceQuantik as $position => $piece) {
            $output .= "Position: $position, Piece: " . $piece->__toString() . "\n";
        }
        return $output;
    }

    /* TODO implantation schéma UML */

    public function getJson(): string
    {
        $json = "[";
        $jTab = [];
        foreach ($this->pieceQuantik as $p)
            $jTab[] = $p->getJson();
        $json .= implode(',', $jTab);
        return $json . ']';
    }

    public static function initArrayPieceQuantik(string|array $json): ArrayPieceQuantik
    {
        $apq = new ArrayPieceQuantik();
        if (is_string($json)) {
            $json = json_decode($json);
        }
        foreach ($json as $j)
            $apq->addPieceQuantik(PieceQuantik::initPieceQuantik($j));
        return $apq;
    }
}

/*$p = ArrayPieceQuantik::initPiecesBlanches();
echo $p[0];
$p = ArrayPieceQuantik::initPiecesBlanches();
echo $p."\n";
$t=ArrayPieceQuantik::initArrayPieceQuantik($p->getJson());
echo "le nouveau\n";
echo $t;*/