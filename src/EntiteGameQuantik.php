<?php

namespace quantiketape1;
require_once __DIR__.'/QuantikGame.php';

class EntiteGameQuantik
{
    public int $gameid;
    public int $playerone;
    public ?int $playertwo = null;
    public string $gamestatus; // was = 'init';

    public ?string $json = '';
    public function __construct(int $gameid, int $playerone, ?int $playertwo, string $gamestatus, ?string $json)
    {
        $this->gameid = $gameid;
        $this->playerone = $playerone;
        $this->playertwo = $playertwo;
        $this->gamestatus = $gamestatus;
        $this->json = $json;
    }

    // Méthode pour convertir une instance de QuantikGame en EntiteGameQuantik
    public static function fromQuantikGame(QuantikGame $game,string $status): EntiteGameQuantik
    {
        return new self(
            $game->getGameID(),
            $game->getPlayer(0)->getId(),
            $game->getPlayer(1) ->getId(),
            $status,
            $game->getJson()
        );
    }

    // Méthode pour convertir une instance de EntiteGameQuantik en QuantikGame
    public function toQuantikGame(): QuantikGame
    {
        return QuantikGame::initQuantikGame($this->json);
    }

    // Méthode pour créer une instance de EntiteGameQuantik à partir des données de la base de données
    public static function fromDatabaseRow(array $row): EntiteGameQuantik
    {
        return new self(
            $row['gameid'],
            $row['playerone'],
            $row['playertwo'],
            $row['gamestatus'],
            $row['json']
        );
    }

    // Méthode pour convertir une instance de EntiteGameQuantik en tableau associatif pour une insertion ou une mise à jour de la base de données
    public function toDatabaseRow(): array
    {
        return [
            'gameid' => $this->gameid,
            'playerone' => $this->playerone,
            'playertwo' => $this->playertwo,
            'gamestatus' => $this->gamestatus,
            'json' => $this->json
        ];
    }

}