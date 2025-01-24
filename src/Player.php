<?php


namespace quantiketape1;

class Player
{

    /**
     * @param String $name
     * @param int $id
     */
    protected string $name;
    protected int $id;

    public function __construct(int $id, string $name)
    {
        $this->id = $id;
        $this->name = $name;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }


    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getJson(): string {
        $data = array();
        if (isset($this->id)) {
            $data["id"] = $this->getId();
        }
        if (isset($this->name)) {
            $data["name"] = $this->getName();
        }
        return json_encode($data);
    }

    public static function initPlayer($json): ?Player {
        if (is_string($json)) {
            $object = json_decode($json);
            if ($object !== null && isset($object->id) && isset($object->name)) {
                return new Player($object->id, $object->name);
            }
        }
        return null;
    }

    public function __toString(): string {
        return "Player[id={$this->getId()}, name={$this->getName()}]";
    }
}


