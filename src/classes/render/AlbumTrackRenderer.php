<?php

namespace iutnc\deefy\render;


class AlbumTrackRenderer extends AudioTrackRenderer
{
    
    protected function renderCompact(): string
    {
        return <<<HTML

<div class="track-item mb-3 p-3 bg-dark text-light rounded shadow-sm d-flex flex-column flex-md-row align-items-md-center">
    <div class="track-info flex-grow-1">
        <strong>{$this->audio->titre}</strong> par {$this->audio->auteur}
        <span>-</span>
        <span>{$this->audio->duree} secondes</span>
    </div>
   

    <audio controls class="mt-3 mt-md-0 ml-md-3 audio-playe"><source src='../../../audio/{$this->audio->nomFichier}' type='audio/mpeg'></audio> 
</div>
HTML;
    }

    
    protected function renderLong(): string
    {
        return "<div><h1>{$this->audio->titre}
        </h1><p>Artiste: {$this->audio->auteur}</p>
        <p>Album: {$this->audio->album}</p>
        <p>Année: {$this->audio->annee}</p>
        <p>Numéro: {$this->audio->numero_piste}</p>
        <p>Genre: {$this->audio->genre}</p>
        <p>Durée: {$this->audio->duree} secondes</p>
        <audio controls><source src='audio/{$this->audio->nomFichier}' type='audio/mp3'></audio></div>";
    }
}

?>
