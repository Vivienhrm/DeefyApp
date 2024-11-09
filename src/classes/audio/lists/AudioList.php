<?php

namespace iutnc\deefy\audio\lists;

use iutnc\deefy\exception\InvalidPropertyNameException;


class AudioList
{
    
    protected string $nom;

    
    protected int $nbPistes;

    
    protected int $duree;
    protected array $pistes;
    protected int $id;

    
    public function __construct(string $nom, int $id = 0, array $pistes = [])
    {
        $this->nom = $nom;
        $this->pistes = $pistes;
        $this->nbPistes = count($pistes);
        $this->duree = $this->calculerDureePiste($pistes);
        $this->id = $id;
    }

    
    // Calcule la duree totale des pistes audio dans la liste
    public function calculerDureePiste(array $pistes): int
    {
        $duree = 0;

        // Parcourt chaque piste et additionne sa duree
        foreach ($pistes as $piste) {
            $duree += $piste->__get('duree');
        }
        return $duree;
    }

    
    public function __get(string $at)
    {
        if (property_exists($this, $at)) {
            return $this->$at;
        }
        throw new InvalidPropertyNameException("$at : invalid property");
    }

    
    // Definit l'identifiant unique a la liste de tracks
    public function setId(int $id): void
    {
        $this->id = $id;
    }
}
?>