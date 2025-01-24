<?php

namespace quantiketape1;

require_once '../src/ActionQuantik.php';
require_once '../src/PlateauQuantik.php';
require_once '../src/ArrayPieceQuantik.php';
require_once '../src/PieceQuantik.php';

// Création du plateau

$plateau = new PlateauQuantik();
// Création de l'action quantik
$actionQuantik = new ActionQuantik($plateau);

// Affichage initial du plateau
echo "Plateau initial:\n";
echo $plateau;





// Pose de pièces pour simuler une victoire sur la première ligne
$actionQuantik->posePiece(0, 0, PieceQuantik::initWhiteCube());
$actionQuantik->posePiece(0, 1, PieceQuantik::initWhiteCylindre());
$actionQuantik->posePiece(0, 2, PieceQuantik::initWhiteSphere());
$actionQuantik->posePiece(0, 3, PieceQuantik::initWhiteCone());

// Affichage après les modifications
echo "\nPlateau après les modifications:\n";
echo $plateau;

// Test des conditions de victoire
echo "\nConditions de victoire :\n";
for ($i = 0; $i < PlateauQuantik::NB_ROWS; $i++) {
    echo "Ligne $i : " . ($actionQuantik->isRowWin($i) ? "Victoire" : "Pas de victoire") . "\n";
}

for ($j = 0; $j < PlateauQuantik::NB_COLS; $j++) {
    echo "Colonne $j : " . ($actionQuantik->isColWin($j) ? "Victoire" : "Pas de victoire") . "\n";
}

for ($dir = PlateauQuantik::NW; $dir <= PlateauQuantik::SE; $dir++) {
    echo "Coin $dir : " . ($actionQuantik->isCornerWin($dir) ? "Victoire" : "Pas de victoire") . "\n";
}

