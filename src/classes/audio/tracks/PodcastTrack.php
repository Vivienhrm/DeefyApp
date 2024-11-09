<?php

namespace iutnc\deefy\audio\tracks;

use iutnc\deefy\render as R;


class PodcastTrack extends AudioTrack
{
    
    protected string $date;

    
    public function __construct(
        string $titre,
        string $nomFichier,
        string $auteur = 'inconnu',
        string $date = 'inconnu',
        int $duree = 0,
        string $genre = 'inconnu',
        int $id = 0
    ) {
        parent::__construct($titre, $nomFichier);
        $this->id = $id;
        $this->date = $date;
        $this->auteur = $auteur;
        $this->duree = $duree;
        $this->genre = $genre;
    }

    
    public function getRenderer(): R\PodcastRenderer {
        return new R\PodcastRenderer($this);
    }

    
    public function setDate(string $date): void {
        $this->date = $date;
    }

    
    public function getType(): string {
        return 'PodcastTrack';
    }


}
?>