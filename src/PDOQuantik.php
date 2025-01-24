<?php

namespace quantiketape1;
require_once __DIR__.'/Player.php';
require_once __DIR__.'/QuantikGame.php';
require_once __DIR__.'/EntiteGameQuantik.php';
require_once __DIR__.'/../env/db.php';
use PDO;
use PDOException;
use PDOStatement;

class PDOQuantik
{
    private static PDO $pdo;

    public static function initPDO(string $sgbd, string $host, string $db, string $user, string $password, string $nomTable = ''): void
    {
        switch ($sgbd) {
            case 'pgsql':
                self::$pdo = new PDO('pgsql:host=' . $host . ' dbname=' . $db . ' user=' . $user . ' password=' . $password);
                break;
            default:
                exit ("Type de sgbd non correct : $sgbd fourni, 'mysql' ou 'pgsql' attendu");
        }

        // pour récupérer aussi les exceptions provenant de PDOStatement
        self::$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    /* requêtes Préparées pour l'entitePlayer */
    private static PDOStatement $createPlayer;
    private static PDOStatement $selectPlayerByName;


    /******** Gestion des requêtes relatives à Player *************/
    public static function createPlayer(string $name): Player
    {
        if (!isset(self::$createPlayer))
            self::$createPlayer = self::$pdo->prepare('INSERT INTO Player(name) VALUES (:name)');
        self::$createPlayer->bindValue(':name', $name, PDO::PARAM_STR);
        self::$createPlayer->execute();
        return self::selectPlayerByName($name);
    }

    public static function selectPlayerByName(string $name): ?Player
    {
        // Vérifier si la requête a déjà été préparée
        if (!isset(self::$selectPlayerByName))
            self::$selectPlayerByName = self::$pdo->prepare('SELECT * FROM Player WHERE name=:name');
        // Lié le paramètre et exécuter la requête
        self::$selectPlayerByName->bindParam(':name', $name, PDO::PARAM_STR);
        self::$selectPlayerByName->execute();
        // Récupérer les résultats sous forme d'objet de la classe Player
        $playerData = self::$selectPlayerByName->fetch();

        // Vérifier si des données ont été trouvées
        if ($playerData) {
            // Créer une instance de la classe Player avec les données récupérées
            return new \quantiketape1\Player($playerData['id'], $playerData['name']);
        } else {
            return null;
        }
    }

    public static function selectPlayerNameByID(int $id): ?string
    {
        $query = self::$pdo->prepare('SELECT name FROM Player WHERE id = :id');
        $query->bindParam(':id', $id, PDO::PARAM_INT);
        $query->execute();
        return $query->fetchColumn();
    }

    /* requêtes préparées pour l'entiteGameQuantik */
    private static PDOStatement $createGameQuantik;
    private static PDOStatement $saveGameQuantik;
    private static PDOStatement $addPlayerToGameQuantik;
    private static PDOStatement $selectGameQuantikById;
    private static PDOStatement $selectAllGameQuantik;
    private static PDOStatement $selectAllGameQuantikByPlayerName;

    /******** Gestion des requêtes relatives à QuantikGame *************/

    /**
     * initialisation et execution de $createGameQuantik la requête préparée pour enregistrer une nouvelle partie
     */
    public static function createGameQuantik(string $playerName, string $json): void
    {
        if (!isset(self::$createGameQuantik)) {
            self::$createGameQuantik = self::$pdo->prepare('INSERT INTO QuantikGame (playerOne, json) VALUES ((SELECT id FROM Player WHERE name = :name), :json)');
        }
        self::$createGameQuantik->bindParam(':name', $playerName, PDO::PARAM_STR);
        self::$createGameQuantik->bindParam(':json', $json, PDO::PARAM_STR);
        self::$createGameQuantik->execute();
    }

    /**
     * initialisation et execution de $saveGameQuantik la requête préparée pour changer
     * l'état de la partie et sa représentation json
     */
    public static function saveGameQuantik(string $gameStatus, string $json, int $gameId): void
    {
        if (!isset(self::$saveGameQuantik)) {
            self::$saveGameQuantik = self::$pdo->prepare('UPDATE QuantikGame SET gameStatus = :gameStatus, json = :json WHERE gameId = :gameId');
        }
        self::$saveGameQuantik->bindValue(':gameStatus', $gameStatus, PDO::PARAM_STR);
        self::$saveGameQuantik->bindValue(':json', $json, PDO::PARAM_STR);
        self::$saveGameQuantik->bindValue(':gameId', $gameId, PDO::PARAM_INT);
        self::$saveGameQuantik->execute();
    }

    /**
     * initialisation et execution de $addPlayerToGameQuantik la requête préparée pour intégrer le second joueur
     */
    public static function addPlayerToGameQuantik(string $playerName, string $json, int $gameId): void
    {
        if (!isset(self::$addPlayerToGameQuantik)) {
            self::$addPlayerToGameQuantik = self::$pdo->prepare('UPDATE QuantikGame SET playerTwo = (SELECT id FROM Player WHERE name = :name), json = :json, gamestatus = \'initialized\' WHERE gameId = :gameId');
        }
        self::$addPlayerToGameQuantik->bindParam(':name', $playerName, PDO::PARAM_STR);
        self::$addPlayerToGameQuantik->bindParam(':json', $json, PDO::PARAM_STR);
        self::$addPlayerToGameQuantik->bindParam(':gameId', $gameId, PDO::PARAM_INT);
        self::$addPlayerToGameQuantik->execute();
    }

    /**
     * initialisation et execution de $selectAllGameQuantikById la requête préparée pour récupérer
     * une instance de quantikGame en fonction de son identifiant
     */

    /**
     * initialisation et execution de $selectAllGameQuantik la requête préparée pour récupérer toutes
     * les instances de quantikGame
     */
    public static function getAllGameQuantik(): array
    {
        if (!isset(self::$selectAllGameQuantik)) {
            self::$selectAllGameQuantik = self::$pdo->query('SELECT * FROM QuantikGame');
        }
                    return self::$selectAllGameQuantik->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * initialisation et execution de $selectAllGameQuantikByPlayerName la requête préparée pour récupérer les instances
     * de quantikGame accessibles au joueur $playerName
     * ne pas oublier les parties "à un seul joueur"
     */
    public static function getAllGameQuantikByPlayerName(string $playerName): array
    {
        if (!isset(self::$selectAllGameQuantikByPlayerName)) {
            self::$selectAllGameQuantikByPlayerName = self::$pdo->prepare('SELECT * FROM QuantikGame WHERE playerOne = (SELECT id FROM Player WHERE name = :name) OR playerTwo = (SELECT id FROM Player WHERE name = :name)');
        }
        self::$selectAllGameQuantikByPlayerName->bindParam(':name', $playerName, PDO::PARAM_STR);
        self::$selectAllGameQuantikByPlayerName->execute();
        return self::$selectAllGameQuantikByPlayerName->fetchAll(PDO::FETCH_ASSOC);
    }
    /**
     * initialisation et execution de la requête préparée pour récupérer
     * l'identifiant de la dernière partie ouverte par $playername
     */
    public static function getLastGameIdForPlayer(string $playerName): int
    {
        if (!isset(self::$selectLastGameIdForPlayer)) {
            self::$selectLastGameIdForPlayer = self::$pdo->prepare('SELECT MAX(gameId) AS lastGameId FROM QuantikGame WHERE playerOne = (SELECT id FROM Player WHERE name = :name) OR playerTwo = (SELECT id FROM Player WHERE name = :name)');
        }
        self::$selectLastGameIdForPlayer->bindValue(':name', $playerName, PDO::PARAM_STR);
        self::$selectLastGameIdForPlayer->execute();
        $row = self::$selectLastGameIdForPlayer->fetch(PDO::FETCH_ASSOC);
        return $row['lastGameId'] ?? 0;
    }
    /*public static function getLastGameIdForPlayer(string $playerName): ? int
{
   $stmt = self::$pdo->prepare('SELECT MAX(gameId) AS lastGameId FROM QuantikGame WHERE playerOne = (SELECT id FROM Player WHERE name = :name) OR playerTwo = (SELECT id FROM Player WHERE name = :name)');

    $stmt->bindValue(':name', $playerName, PDO::PARAM_STR);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

  return $result ? (int) $result['gameid'] : null;
}*/

    /**
     * Obtient l'ID du joueur adverse dans un jeu spécifié.
     *
     * @param int $gameId ID du jeu concerné.
     * @param int $currentPlayerId ID du joueur actuel.
     *
     * @return int|null L'ID du joueur adverse, ou null si non trouvé.
     */


    public static function getGameQuantikById(int $gameId): ?QuantikGame
    {
        $stmt = self::$pdo->prepare('SELECT * FROM quantikGame WHERE gameid = :gameid');
        $stmt->bindParam(':gameid', $gameId, PDO::PARAM_INT);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$row) {
            return null;
        }

        return EntiteGameQuantik::fromDatabaseRow($row)->toQuantikGame();
    }

    public static function saveGameQuantiky(EntiteGameQuantik $game): void
    {
        $data = $game->toDatabaseRow();
        if ($data["gameid"] == 0) {
            // Insertion
            $stmt = self::$pdo->prepare('INSERT INTO QuantikGame (playerone, playertwo, gamestatus, json) VALUES (:playerone, :playertwo, :gamestatus, :json)');
        } else {
            // Mise à jour
            $stmt = self::$pdo->prepare('UPDATE QuantikGame SET playerone = :playerone, playertwo = :playertwo, gamestatus = :gamestatus, json = :json WHERE gameid = :gameid');
            $stmt->bindParam(':gameid', $data["gameid"], PDO::PARAM_INT);
        }
    }
    // methode pour recommencer la partie


    /*public static function requestReplay(int $gameId, int $requesterId): void {
        try {$stmt = self::$pdo->prepare('UPDATE QuantikGame SET replayRequested = TRUE, replayRequesterId = :requesterId WHERE gameId = :gameId');
            $stmt->bindParam(':gameId', $gameId, PDO::PARAM_INT);
            $stmt->bindParam(':requesterId', $requesterId, PDO::PARAM_INT);
            $stmt->execute();

            echo "Demande de recommencer mise à jour.";
        } catch (PDOException $e) {
            echo "Erreur lors de la mise à jour de la demande de recommencer : " . $e->getMessage();
        }

}*/
    /*public static function resetGame(int $gameId): void {
        // Réinitialisez le jeu en base de données
       $game = self::getGameQuantikById($gameId);
        if ($game instanceof QuantikGame) {
            $game->reset();

            // Mettez à jour le jeu avec son nouvel état JSON après la réinitialisation
            $newJsonGameState = $game->getJson();
            $stmt = self::$pdo->prepare('UPDATE QuantikGame SET gameStatus = :status, replayRequested = :replayRequested, json = :json WHERE gameId = :gameId');
            $status = 'initialized';
            $replayRequested = false;
            $stmt->bindParam(':status', $status);
            $stmt->bindParam(':replayRequested', $replayRequested, PDO::PARAM_BOOL);
            $stmt->bindParam(':json', $json);
            $stmt->bindParam(':gameId', $gameId, PDO::PARAM_INT);


            $stmt->bindValue(':json', $newJsonGameState, PDO::PARAM_STR);
            $stmt->bindValue(':gameId', $gameId, PDO::PARAM_INT);
            $stmt->execute();
        }

  }*/

   //methode pour mette a jour le score
    public static function updateScore($gameId, $winnerPlayerId): void
    {
        try {
            // On détermine d'abord si le joueur gagnant est playerOne ou playerTwo
            $stmt = self::$pdo->prepare("SELECT playerone, playertwo FROM QuantikGame WHERE gameId = :gameId");
            $stmt->bindParam(':gameId', $gameId, PDO::PARAM_INT);
            $stmt->execute();
            $game = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$game) {
                throw new Exception("Jeu introuvable.");
            }

            if ($game['playerone'] == $winnerPlayerId) {
                // Mise à jour du score pour playerOne
                $sql = "UPDATE QuantikGame SET scorePlayerOne = scorePlayerOne + 1 WHERE gameId = :gameId";
            } elseif ($game['playertwo'] == $winnerPlayerId) {
                // Mise à jour du score pour playerTwo
                $sql = "UPDATE QuantikGame SET scorePlayerTwo = scorePlayerTwo + 1 WHERE gameId = :gameId";
            } else {
                throw new Exception("Identifiant du joueur gagnant non valide.");
            }

            $stmt = self::$pdo->prepare($sql);
            $stmt->bindParam(':gameId', $gameId, PDO::PARAM_INT);
            $stmt->execute();

            if ($stmt->rowCount() == 0) {
                throw new Exception("La mise à jour du score a échoué.");
            }
            //echo "Score mis à jour avec succès.";
        } catch (Exception $e) {
            echo "Erreur lors de la mise à jour du score : " . $e->getMessage();
        }
    }public static function getScoresByGameId($gameId) {
    try {

        $stmt = self::$pdo->prepare("SELECT scorePlayerOne, scorePlayerTwo FROM QuantikGame WHERE gameid = :gameId");
        $stmt->execute([':gameId' => $gameId]);
        $scores = $stmt->fetch();
        //print_r( $scores);
        return $scores; // Retourne un tableau associatif avec les clés scorePlayerOne et scorePlayerTwo
    } catch (\PDOException $e) {
        die($e->getMessage());
    }
}





}