<?php

namespace iutnc\deefy\audio\lists;


class Album extends AudioList
{
    
    private string $artiste;

    
    private ?string $dateSortie;

    
    public function __construct(string $nomAlbum, string $artiste, array $pistes = [],$dateSortie = null)
    {
        parent::__construct($nomAlbum, 0, $pistes); // Appel du constructeur parent avec id par défaut à 0
        $this->artiste = $artiste;
        $this->dateSortie = $dateSortie;
    }

    
    public function setArtiste(string $value): void
    {
        $this->artiste = $value;
    }

    
    public function setDateSortie(?string $value): void
    {
        $this->dateSortie = $value;
    }

    
    public function getArtiste(): string
    {
        return $this->artiste;
    }

    
    public function getDateSortie(): ?string
    {
        return $this->dateSortie;
    }
}
?>