<?php

namespace iutnc\deefy\audio\tracks;

use iutnc\deefy\exception\InvalidPropertyNameException;
use iutnc\deefy\exception\InvalidPropertyValueException;


class AudioTrack {
    protected string $titre;
    protected string $auteur;
    protected string $genre;
    protected int $duree;
    protected string $nomFichier;
    protected int $id;

    
    public function __construct(string $titre, string $nomFichier) {
        $this->titre = $titre;
        $this->nomFichier = $nomFichier;
    }

    
    public function __toString(): string {
        return "Titre : " . $this->titre . "\n" .
               "Nom du fichier audio : " . $this->nom_fichier_audio . "\n" .
               "Duree : " . $this->duree . " secondes\n";

    }

    
    public function __get(String $property) : mixed {
        if (!property_exists($this, $property)) {
            throw new InvalidPropertyNameException($property);
        }
        return $this->$property;
    }

    
    public function __set(string $prop, mixed $value): void {
        if ($prop === 'duree') {
            if ($value >= 0) {
                $this->$prop = $value;
            } else {
                
                throw new InvalidPropertyValueException($prop, "$value < 0 : invalid value");
            }
        } elseif (property_exists($this, $prop) && $prop !== 'titre' && $prop !== 'nomFichier') {
            $this->$prop = $value;
        } else {
            throw new InvalidPropertyNameException($prop);
        }
    }

    
    public function setAuteur(string $auteur): void {
        $this->auteur = $auteur;
    }

    
    public function setGenre(string $genre): void {
        $this->genre = $genre;
    }

    
    public function setDuree(mixed $dur): void {
        if (!is_int($dur)) {
            throw new InvalidPropertyValueException('duree',"$dur isn't an int : invalid value");
        }
        if ($dur < 0) {
            throw new InvalidPropertyValueException('duree',"$dur < 0 : invalid value");
        }
        $this->duree = $dur;
    }

    
    public function setId(int $id): void {
        $this->id = $id;
    }

    
    public function getType(): string {
        return 'AudioTrack';
    }
}
?>