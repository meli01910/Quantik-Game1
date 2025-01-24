<?php
namespace quantiketape1;
require_once __DIR__.'/AbstractGame.php';
require_once __DIR__.'/ArrayPieceQuantik.php';
require_once __DIR__.'/PlateauQuantik.php';
require_once __DIR__.'/Player.php';

class QuantikGame extends AbstractGame
{


    public PlateauQuantik $plateau;
    public ArrayPieceQuantik $piecesBlanches;
    public ArrayPieceQuantik $piecesNoires;
    public array $couleurPlayer;

    /**
     * @param PlateauQuantik $plateau
     */
    public function __construct(array $players)
    {
        parent::__construct(0, $players);
        $this->plateau = new PlateauQuantik();
        $this->piecesBlanches = ArrayPieceQuantik::initPiecesBlanches();
        $this->piecesNoires = ArrayPieceQuantik::initPiecesNoires();
        $this->couleurPlayer = [PieceQuantik::BLACK, PieceQuantik::WHITE];
    }

    public function getCouleurPlayer(int $pos): int
    {
        return $this->couleurPlayer[$pos];
    }

    /* TODO implantation schéma UML */

   public function __toString(): string
    {
        return 'Partie n°' . $this->gameID . ' lancée par joueur ' . $this->getPlayers()[0];
    }

    public function getJson(): string
    {
        $json = '{';
        $json .= '"plateau":' . $this->plateau->getJson();
        $json .= ',"piecesBlanches":' . $this->piecesBlanches->getJson();
        $json .= ',"piecesNoires":' . $this->piecesNoires->getJson();
        $json .= ',"currentPlayer":' . json_encode($this->currentPlayer); // Encapsuler en json_encode si nécessaire
        $json .= ',"gameID":' . json_encode($this->gameID); // Encapsuler en json_encode si nécessaire
        if (isset($this->players[0]) && $this->players[0] !== null) {
            $json .= ',"player1":' . $this->players[0]->getJson();
        }

        if (isset($this->players[1]) && $this->players[1] !== null) {
            $json .= ',"player2":' . $this->players[1]->getJson();
        }
        $json .= ',"gameStatus":' . json_encode($this->gameStatus); // Encapsuler en json_encode si nécessaire
        if (is_null($this->couleurPlayer[1]))
            $json .= ',"couleurPlayer":[' . json_encode($this->couleurPlayer[0]) . ']'; // Encapsuler en json_encode si nécessaire
        else
            $json .= ',"couleurPlayer":[' . json_encode($this->couleurPlayer[0]) . ',' . json_encode($this->couleurPlayer[1]) . ']'; // Encapsuler en json_encode si nécessaire
        return $json . '}';
    }

    public static function initQuantikGame(string $json): QuantikGame
    {
        $object = json_decode($json);

        // Initialiser les joueurs
        $player1 = Player::initPlayer(json_encode($object->player1));
        $player2 = Player::initPlayer(json_encode($object->player2));
        // Créer un nouvel objet GameQuantik avec les joueurs
        $gameQuantik = new QuantikGame([$player1, $player2]);

        // Modifier les autres attributs avec les valeurs du JSON
        $gameQuantik->plateau = PlateauQuantik::initPlateauQuantik(json_encode($object->plateau));
        $gameQuantik->piecesBlanches = ArrayPieceQuantik::initArrayPieceQuantik(json_encode($object->piecesBlanches));
        $gameQuantik->piecesNoires = ArrayPieceQuantik::initArrayPieceQuantik(json_encode($object->piecesNoires));
        $gameQuantik->currentPlayer = $object->currentPlayer;
        $gameQuantik->gameID = $object->gameID;
        $gameQuantik->gameStatus = $object->gameStatus;
        $gameQuantik->couleurPlayer = $object->couleurPlayer;

        // Retourner l'objet GameQuantik modifié
        return $gameQuantik;
    }
    public function reset(): void {
        $this->plateau = new PlateauQuantik();
        $this->piecesBlanches = ArrayPieceQuantik::initPiecesBlanches();
        $this->piecesNoires = ArrayPieceQuantik::initPiecesNoires();
        $this->currentPlayer = 0; // Assurez-vous de remettre le joueur courant à l'état initial si nécessaire
        // Vous pouvez également réinitialiser d'autres attributs de l'état du jeu si nécessaire
    }

}
