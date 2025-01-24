<?php

namespace quantiketape1;
require_once __DIR__.'/Player.php';

abstract class AbstractGame
{
    protected int $gameID;
    protected array $players;
    public int $currentPlayer;
    public string $gameStatus;
    public  array $Etat = ["ChoixPiece","PosePiece","Victoire"];
    public const JOUEUR1 = 0;
    public const JOUEUR2 = 1;

    public function __construct(int $gameID, array $players)
    {
        $this->gameID = $gameID;
        $this->players = $players;
        $this->currentPlayer = self::JOUEUR1;
        $this->gameStatus = $this->Etat[0];
}

    public function getGameID(): int
    {
        return $this->gameID;
    }

    public function setGameID(int $gameID): void
    {
        $this->gameID = $gameID;
    }

    public function getPlayers(): array
    {
        return $this->players;
    }

    public function getPlayer(int $Id): Player
    {
        return $this->players[$Id];
    }

    public function setPlayers(Player $player): void
    {
        // Trouver le premier emplacement vide pour ajouter le joueur
        $emptySlot = array_search(null, $this->players, true);
        if ($emptySlot !== false) {
            $this->players[$emptySlot] = $player;
        }
    }

    public function getCurrentPlayer(): int
    {
        return $this->currentPlayer;
    }

    public function setCurrentPlayer(int $currentPlayer): void
    {
        $this->currentPlayer = $currentPlayer;
    }

    public function getGameStatus(): string
    {
        return $this->gameStatus;
    }

    public function setGameStatus(string $gameStatus): void
    {
        $this->gameStatus = $gameStatus;
    }

    private function Tirage(int $min, int $max): int
    {
        return rand($min, $max);
    }
}