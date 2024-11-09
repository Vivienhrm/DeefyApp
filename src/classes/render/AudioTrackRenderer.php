<?php

namespace iutnc\deefy\render;


abstract class AudioTrackRenderer implements Renderer
{
    protected $audio;

    
    public function __construct($audio)
    {
        $this->audio = $audio;
    }

    
    public function render(int $selector): string
    {
        $res = '';
        if ($selector === Renderer::COMPACT) {
            $res = $this->renderCompact();
        } elseif ($selector === Renderer::LONG) {
            $res = $this->renderLong();
        }
        return $res;
    }

    
    protected abstract function renderCompact(): string;

    
    protected abstract function renderLong(): string;
}
?>