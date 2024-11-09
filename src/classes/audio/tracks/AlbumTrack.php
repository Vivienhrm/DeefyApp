<?php

namespace iutnc\deefy\audio\tracks;

use \iutnc\deefy\render as R;


class AlbumTrack extends AudioTrack {

    protected string $album;
    protected string $annee;
    protected int $numeroPiste;

    
    public function __construct(string $titre, string $nomFichier, string $album, string $annee, int $numeroPiste, string $auteur = 'inconnu', int $duree = 0, string $genre = 'inconnu', int $id = 0) {
        parent::__construct($titre, $nomFichier);
        $this->album = $album;
        $this->annee = $annee;
        $this->numeroPiste = $numeroPiste;
        $this->auteur = $auteur;
        $this->duree = $duree;
        $this->genre = $genre;
    }

    
    public function getRenderer(): R\AlbumTrackRenderer {
        return new R\AlbumTrackRenderer($this);
    }

    
    public function __toString(): string {
        return parent::__toString() . json_encode($this);
    }
    
    public function getAlbum(): string {
        return $this->album;
    }

    
    public function getAnnee(): string {
        return $this->annee;
    }

    
    public function getNumeroPiste(): int {
        return $this->numeroPiste;
    }
    
    public function getType(): string {
        return 'AlbumTrack';
    }
}

?>