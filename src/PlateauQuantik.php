<?php


namespace quantiketape1;

require_once __DIR__.'/PieceQuantik.php';
require_once __DIR__.'/Player.php';
require_once __DIR__.'/ArrayPieceQuantik.php';



class PlateauQuantik
{

    public const NB_ROWS = 4;

    public const NB_COLS = 4;

    public const NW = 0;

    public const NE = 1;

    public const SW = 2;

    public const SE = 3;


    protected array $cases;

    //  Constructeur qui initialise le tableau  avec des case vides.

    public function __construct()
    {
        $this->cases = array();

        // Ajouter 4 lignes à la matrice
        for ($i = 0; $i < self::NB_ROWS; $i++) {
            // Ajouter une ligne (ArrayPieceQuantik) à la matrice
            $this->cases[$i] = new ArrayPieceQuantik();
            // Ajouter 4 pièces à chaque ligne
            for ($j = 0; $j < self::NB_COLS; $j++) {
                $this->cases[$i][$j] =PieceQuantik::initVoid();
            }
        }
    }

    private static function checkBounds(int $rowNum, int $colNum): void
    {
        if ($rowNum < 0 || $rowNum >= self::NB_ROWS || $colNum < 0 || $colNum >= self::NB_COLS)
            throw new \Exception("Coordonnées hors du plateau\n");
    }

    // Vérifie que la direction donné est valide

    private static function checkDir(int $dir)
    {
        if ($dir < 0 && $dir > self::SE) throw new \Exception("Direction non valide\n");
    }
    //retourne la PieceQuantik de la ligne $rowNum et de la colonne $colNum
    public function getPiece(int $rowNum, int $colNum): PieceQuantik
    {
        self::checkBounds($rowNum, $colNum);
        return $this->cases[$rowNum][$colNum];
    }

    //  retourne la PieceQuantik de la ligne $rowNum.

    public function getRow(int $rowNum): ArrayPieceQuantik
    {
        self::checkBounds($rowNum, 0);

        return $this->cases[$rowNum];
    }

    //  retourne la PieceQuantik de la colonne $colNum.
    public function getCol(int $colNum): ArrayPieceQuantik
    {
        self::checkBounds(0, $colNum);
        $columnArrayPiece = new ArrayPieceQuantik();

        foreach ($this->cases as $row) {
            $columnArrayPiece->addPieceQuantik($row[$colNum]);
        }

        return $columnArrayPiece;
    }


    public function getCorner(int $dir): ArrayPieceQuantik
    {
        self::checkDir($dir);
        $corners = [
            self::NW => [$this->cases[0][0], $this->cases[0][1], $this->cases[1][0], $this->cases[1][1]],
            self::NE => [$this->cases[0][2], $this->cases[0][3], $this->cases[1][2], $this->cases[1][3]],
            self::SW => [$this->cases[2][0], $this->cases[2][1], $this->cases[3][0], $this->cases[3][1]],
            self::SE => [$this->cases[2][2], $this->cases[2][3], $this->cases[3][2], $this->cases[3][3]],
        ];

        $cornerPieces = $corners[$dir];
        $arrayPieceQuantik = new ArrayPieceQuantik();
        foreach ($cornerPieces as $piece) {
            $arrayPieceQuantik->addPieceQuantik($piece);
        }
        return $arrayPieceQuantik;
    }

    public static function getCornerFromCoord(int $rowNum, int $colNum): int
    {
        self::checkBounds($rowNum, $colNum);

        if($rowNum < self::NB_ROWS / 2){
            if($colNum < self::NB_COLS / 2){
                return self::NW;
            }
            return self::NE;
        }else{
            if($colNum < self::NB_COLS / 2){
                return self::SW;
            }
            return self::SE;
        }

    }


    public function setPiece(int $rowNum, int $colNum, PieceQuantik $p)
    {
        self::checkBounds($rowNum, $colNum);
        $this->cases[$rowNum][$colNum] = $p;
    }


    // affichage
    public function __toString(): string
    {
        $output = "<table border='1'>";
        for ($i = 0; $i < self::NB_ROWS; $i++) {
            $output .= "<tr>";
            for ($j = 0; $j < self::NB_COLS; $j++) {
                $output .= "<td>" . $this->cases[$i][$j] . "</td>";
            }
            $output .= "</tr>";
        }
        $output .= "</table>";

        return $output;
    }

    /* TODO implantation schéma UML */
    public function getJson(): string {
        $json = "[";
        $jTab = [];
        foreach ($this->cases as $apq)
            $jTab[] = $apq->getJson();
        $json .= implode(',',$jTab);
        return $json.']';
    }



    public static function initPlateauQuantik(string|array $json) : PlateauQuantik
    {
        $pq = new PlateauQuantik();
        if (is_string($json))
            $json = json_decode($json);
        $cases = [];
        foreach($json as $elem)
            $cases[] = ArrayPieceQuantik::initArrayPieceQuantik($elem);
        $pq->cases = $cases;
        return $pq;
    }
}
