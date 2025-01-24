<?php
namespace quantiketape1;
require_once '../src/PieceQuantik.php';
require_once '../src/ArrayPieceQuantik.php';


$apq = ArrayPieceQuantik::initPiecesNoires();
for ($i = 0; $i < count($apq); $i++)
    echo $apq->offsetGet($i);
echo "\n";

$apq = ArrayPieceQuantik::initPiecesBlanches();
for ($i = 0; $i < count($apq); $i++)
    echo $apq->offsetGet($i);
echo "\n";

/*$piecesNoires = ArrayPieceQuantik::initPiecesNoires();
echo "Pièces Noires:\n";
echo $piecesNoires;

$piecesBlanches = ArrayPieceQuantik::initPiecesBlanches();
echo "Pièces Blanches:\n";
echo $piecesBlanches;*/


/* *********************** TRACE d'éxécution de ce programme
(Co:B)(Co:B)(Cu:B)(Cu:B)(Cy:B)(Cy:B)(Sp:B)(Sp:B)
(Co:W)(Co:W)(Cu:W)(Cu:W)(Cy:W)(Cy:W)(Sp:W)(Sp:W)
*********************** */