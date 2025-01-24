<?php

namespace quantiketape1;

class PieceQuantik
{
    //les différentes constantes
    public const  WHITE = 0;
    public const  BLACK = 1;

    public  const  VOID = 0;  // Absence d'une pièce
    public const  CUBE = 1;
    public const   CONE = 2;
    public const   CYLINDRE = 3;
    public const SPHERE = 4;
    protected  $forme; // propriété représentant la forme d'une piecequantik
    protected  $couleur;  // propriété représentant la couleur d'une piecequantik

    private function __construct(int $forme, int $couleur)
    {
        $this->forme = $forme;
        $this->couleur = $couleur;
    }

    public static function initVoid(): PieceQuantik
    {
        return new self(self::VOID, 2); // renvoi une PieceQuantik VOID
    }

    public static function initWhiteCube(): PieceQuantik
    {
        return new self(self::CUBE, self::WHITE); // renvoi une PieceQuantik CUBE de couleur blanche
    }

    public static function initBlackCube(): PieceQuantik
    {
        return new self(self::CUBE, self::BLACK); // renvoi une PieceQuantik CUBE de couleur noire
    }

    public static function initWhiteCone(): PieceQuantik
    {
        return new self(self::CONE, self::WHITE); // renvoi une PieceQuantik CONE de couleur blanche
    }

    public static function initBlackCone(): PieceQuantik
    {
        return new self(self::CONE, self::BLACK); // renvoi une PieceQuantik CONE de couleur noire
    }

    public static function initWhiteCylindre(): PieceQuantik
    {
        return new self(self::CYLINDRE, self::WHITE); // renvoi une PieceQuantik CYLINDRE de couleur blanche
    }

    public static function initBlackCylindre(): PieceQuantik
    {
        return new self(self::CYLINDRE, self::BLACK); // renvoi une PieceQuantik CYLINDRE de couleur noire
    }

    public static function initWhiteSphere(): PieceQuantik
    {
        return new self(self::SPHERE, self::WHITE); // renvoi une PieceQuantik SPHERE de couleur blanche
    }

    public static function initBlackSphere(): PieceQuantik
    {
        return new self(self::SPHERE, self::BLACK); // renvoi une PieceQuantik SPHERE de couleur noire
    }

    public function __toString()
    {
        $formeString = $this->formeToString($this->forme);
        $couleurString = $this->couleurToString($this->couleur);

        return "({$formeString}{$couleurString})";
    }

    public function formeToString($forme)
    { // fonction qui permet de retourner deux caractères indiquant le nom d'une piece
        switch ($forme) {
            case self::CONE:
                return 'Co';
            case self::CUBE:
                return 'Cu';
            case self::CYLINDRE:
                return 'Cy';
            case self::SPHERE:
                return 'Sp';
            default:
                return '';
        }
    }

    public function couleurToString($couleur)
    {// fonction qui permet de retourner un caractère indiquant la couleur d'une piece
        switch ($couleur) {
            case self::BLACK:
                return ':B';
            case self::WHITE:
                return ':W';
            default :
                return '';
        }
    }

    public function getForme(): int
    {
        return $this->forme; //renvoi la forme de l'instance courant
    }

    public function getCouleur(): int
    {
        return $this->couleur; //renvoi la couleur de l'instance courant
    }

    public function isEqual(PieceQuantik $otherPiece): bool {
        // Comparaison basée sur les attributs de la pièce
        return $this->forme === $otherPiece->forme &&
            $this->couleur===$otherPiece->couleur;
    }

    /* TODO implantation schéma UML */
    public function getJson(): string {
        return '{"forme":'. $this->forme . ',"couleur":'.$this->couleur. '}';
    }

    public static function initPieceQuantik(string|object $json): PieceQuantik {
        if (is_string($json)) {
            $props = json_decode($json, true);
            return new PieceQuantik($props['forme'], $props['couleur']);
        }
        else
            return new PieceQuantik($json->forme, $json->couleur);
    }
}

