<?php

namespace quantiketape1;
require_once '../src/ArrayPieceQuantik.php';
require_once '../src/PlateauQuantik.php';
require_once '../src/PieceQuantik.php';
class testPlateauQuantik
{


    public static function Test(): void
    {
        self::testInitPlateau();
        self::testSetAndGetPiece();

    }
    //test de l'initialisation
    private static function testInitPlateau(): void{

        $plateau = new PlateauQuantik();

        // Vérifie si le plateau est bien initialisé avec des cases VOID
        $voidPiece = PieceQuantik::initVoid();
        for ($i = 0; $i < PlateauQuantik::NB_ROWS; $i++) {
            for ($j = 0; $j < PlateauQuantik::NB_COLS; $j++) {
                $piece = $plateau->getPiece($i, $j);
                if ($piece != $voidPiece) {
                    echo "Erreur : La case ($i, $j) n'est pas initialisée à VOID.\n";
                    return;
                }
            }
        }
 echo "Le test d'initialisation du plateau a réussi.\n";
    }

    // test des getters et setters
    private static function testSetAndGetPiece(): void
    {

        $plateau = new PlateauQuantik();

        // Test de getRow
        $rowNumber = 0;
        echo "Affichage de la pièce à la ligne $rowNumber :\n";
        $piecesInRow = $plateau->getRow(0);
        echo $piecesInRow . "\n";
        echo "Le test getRow a réussi.\n";

        // Test de getCorner pour chaque direction
        for ($dir = PlateauQuantik::NW; $dir <= PlateauQuantik::SE; $dir++) {
            echo "Coins pour la direction $dir :\n";
            $cornerPieces = $plateau->getCorner($dir);
            echo $cornerPieces . "\n";
        }
    }
}

// Exécute les tests
TestPlateauQuantik::Test();

