<?php

namespace iutnc\deefy\render;


class AudioListRenderer implements Renderer
{
    private $audioList;

    
    public function __construct($audioList)
    {
        $this->audioList = $audioList;
    }

    
    public function render(int $selector = Renderer::COMPACT): string
    {
        $res = <<<HTML
<h1 class="h3 mb-4 text-center">Nom : {$this->audioList->nom}</h1>
HTML;

        foreach ($this->audioList->pistes as $value) {
            $renderValue = $value->getRenderer();
            $res .= $renderValue->render(Renderer::COMPACT) . "\n";
        }

        $res .= "<p class=\"mt-4\">Nombre de pistes : <strong>{$this->audioList->nbPistes}</strong></p>\n";
        $res .= "<p>Durée totale : <strong>{$this->audioList->duree} secondes</strong></p>\n";

        return $res;
    }
}