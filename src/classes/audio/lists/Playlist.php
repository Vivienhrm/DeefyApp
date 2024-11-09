<?php

namespace iutnc\deefy\audio\lists;

use iutnc\deefy\audio\tracks\AudioTrack;


class Playlist extends AudioList
{
    
    public function ajouterPiste(AudioTrack $track): void
    {
        $this->pistes[$this->nbPistes] = $track;
        $this->nbPistes++;
        $this->duree += $track->duree;
    }

    
    public function supprimerPiste(int $indTrack): void {
        // 
        if ($indTrack >= 0 && $indTrack < $this->nbPistes) {
            $this->duree -= $this->pistes[$indTrack]->duree;
            unset($this->pistes[$indTrack]);
            $this->pistes = array_values($this->pistes); // Réindexer le tableau
            $this->nbPistes--;
        }
    }

    
    public function ajouterPistes(array $tracks): void
    {
        foreach ($tracks as $track) {
            if (!in_array($track, $this->pistes, true)) { // Vérifie si la piste n'est pas déjà présente
                $this->ajouterPiste($track);
            }
        }
    }
}
?>