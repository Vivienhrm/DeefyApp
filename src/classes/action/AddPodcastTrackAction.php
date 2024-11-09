<?php

namespace iutnc\deefy\action;

use getID3;
use iutnc\deefy\audio\tracks\AlbumTrack;
use iutnc\deefy\audio\tracks\AudioTrack;
use iutnc\deefy\audio\tracks\PodcastTrack;
use iutnc\deefy\exception\InvalidPropertyValueException;
use iutnc\deefy\render\AudioListRenderer;
use iutnc\deefy\repository\DeefyRepository;

class AddPodcastTrackAction extends Action
{
    protected function get(): string
    {
        return <<<HTML
<div class="main-container mb-5 p-4 text-light rounded shadow">
    <h3 class="text-center mb-3">Ajoutez votre Podcast ou une Musique</h3>
    <p>
    Veuillez remplir les champs nécessaires pour ajouter une piste ou un podcast. Si certains champs sont laissés vides, notre système récupérera automatiquement les informations disponibles dans les métadonnées du fichier.
    </p>
</div>
<div class="form-playlist mb-5">
    <form method="post" action="?action=add-track" enctype="multipart/form-data">
        <h1 class="h3 mb-3 fw-normal text-center">Ajouter un Podcast</h1>
        <input type="hidden" name="typeTrack" value="1">
        <div class="form-floating mb-3">
            <input type="text" class="form-control" id="floatingNom1" name="titre" placeholder="Titre">
            <label for="floatingNom1">Titre</label>
        </div>
        <div class="form-floating mb-3">
            <input type="text" class="form-control" name="auteur" placeholder="Auteur">
            <label>Auteur</label>
        </div>
        <div class="form-floating mb-3">
            <input type="date" class="form-control" name="date" placeholder="Date de publication">
            <label>Date de publication</label>
        </div>
        <div class="form-floating mb-3">
            <input type="number" class="form-control" name="duree" placeholder="Durée (en secondes)">
            <label>Durée</label>
        </div>
        <div class="form-floating mb-3">
            <input type="text" class="form-control" name="genre" placeholder="Genre">
            <label>Genre</label>
        </div>
        <div>
            <input class="form-control" type="file" name="fichier" required>
        </div>
        <button class="btn btn-primary w-100 py-2" type="submit">Valider</button>
    </form>
</div>

<div class="form-playlist mb-5">
    <form method="post" action="?action=add-track" enctype="multipart/form-data">
        <h1 class="h3 mb-3 fw-normal text-center">Ajouter une Musique</h1>
        <input type="hidden" name="typeTrack" value="0">
        <div class="form-floating mb-3">
            <input type="text" class="form-control" id="floatingNom2" name="titre" placeholder="Titre">
            <label for="floatingNom2">Titre</label>
        </div>
        <div class="form-floating mb-3">
            <input type="text" class="form-control" name="album" placeholder="Album">
            <label>Album</label>
        </div>
        <div class="form-floating mb-3">
            <input type="number" class="form-control" name="annee" placeholder="Année de sortie">
            <label>Année de sortie</label>
        </div>
        <div class="form-floating mb-3">
            <input type="number" class="form-control" name="numeroPiste" placeholder="Numéro de piste">
            <label>Numéro de piste</label>
        </div>
        <div class="form-floating mb-3">
            <input type="text" class="form-control" name="auteur" placeholder="Auteur">
            <label>Auteur</label>
        </div>
        <div class="form-floating mb-3">
            <input type="number" class="form-control" name="duree" placeholder="Durée (en secondes)">
            <label>Durée</label>
        </div>
        <div class="form-floating mb-3">
            <input type="text" class="form-control" name="genre" placeholder="Genre">
            <label>Genre</label>
        </div>
        <div>
            <input class="form-control" type="file" name="fichier" required>
        </div>
        <button class="btn btn-primary w-100 py-2" type="submit">Valider</button>
    </form>
</div>
HTML;
    }


    /**
     * @throws InvalidPropertyValueException
     */
    protected function post(): string
    {
        if (!isset($_SESSION["playlist"])) {
            return "Aucune playlist trouvée.";
        }

        $upload_dir = __DIR__ . "/../../../audio/";
        $filename = uniqid();
        $tmp = $_FILES['fichier']['tmp_name'];
        $playlist = unserialize($_SESSION["playlist"]);

        // Verifie si l'upload a echoue
        if ($_FILES['fichier']['error'] !== UPLOAD_ERR_OK) {
            return "Upload du fichier échoué : " . $this->getUploadErrorMessage($_FILES['fichier']['error']);
        }

        // Verification du type de fichier
        $extension = strtolower(pathinfo($_FILES['fichier']['name'], PATHINFO_EXTENSION));
        if ($extension !== 'mp3' && $_FILES['fichier']['type'] !== 'audio/mpeg') {
            return "Upload du fichier échoué : type non autorisé.";
        }

        // Deplacement du fichier
        $dest = $upload_dir . $filename . '.mp3';
        if (!move_uploaded_file($tmp, $dest)) {
            return "Upload du fichier échoué : impossible de déplacer le fichier.";
        }

        // Extraction des métadonnées
        $getID3 = new getID3;
        $infoFichier = $getID3->analyze($dest);
        if (isset($infoFichier['error'])) {
            return 'Erreur lors de l\'analyse du fichier : ' . implode(', ', $infoFichier['error']);
        }

        // Validation et nettoyage des donnees
        $titre = filter_var(trim($_POST['titre'] ?? ''), FILTER_SANITIZE_SPECIAL_CHARS);

        if($titre === ''){
            $titre = $infoFichier['tags']['id3v2']['title'][0] ?? 'Titre non disponible';
        }

        $typeTrack = filter_var($_POST['typeTrack'], FILTER_VALIDATE_INT);
        if ($titre === false || $typeTrack === false) {
            return "Données invalides fournies.";
        }

        $auteur = filter_var(trim($_POST['auteur'] ?? ''), FILTER_SANITIZE_SPECIAL_CHARS);
        if ($auteur === '') {
            $auteur = $infoFichier['tags']['id3v2']['artist'][0] ?? 'Artiste non disponible';
        }

        $date = filter_var(trim($_POST['date'] ?? ''), FILTER_SANITIZE_SPECIAL_CHARS);
        if ($date === '') {
            $date = $infoFichier['tags']['id3v2']['year'][0] ?? '';
        }

        $duree = filter_var(trim($_POST['duree'] ?? ''), FILTER_VALIDATE_INT);
        if ($duree === false) {
            $duree = $this->convertDurationToSeconds($infoFichier['playtime_string'] ?? '0:00');
        }

        $genre = filter_var(trim($_POST['genre'] ?? ''), FILTER_SANITIZE_SPECIAL_CHARS);
        if ($genre === '') {
            $genre = $infoFichier['tags']['id3v2']['genre'][0] ?? 'Genre inconnu';
        }

        // Creation de la piste
        try {
            $track = $this->createTrack($typeTrack, $titre, $filename, $infoFichier, $auteur, $date, $duree, $genre);

            $repository = DeefyRepository::getInstance();
            $track = $repository->saveTrack($track);

            // Ajoute la piste à la playlist et enregistre
            $playlist->ajouterPiste($track);
            $repository->addTrackToPlaylist($playlist->id, $track->id);
            $_SESSION["playlist"] = serialize($playlist);

            // Rendu de la playlist mise à jour
            $render = new AudioListRenderer($playlist);
            $res = $render->render();
            return <<<HTML
<div class="playlist-container">
        <div class="alert alert-success mt-3 text-center" role="alert">
            Upload du fichier réussi
        </div>
$res
    <div class="text-center mt-4">
        <a href="?action=add-track" class="btn btn-primary">Ajouter une piste</a>
    </div>
</div>
HTML;
        } catch (InvalidPropertyValueException $e) {
            return $this->errorMessage("Erreur lors de la création de la piste : " . $e->getMessage());
        }
    }


    private function createTrack(int $type, string $title, string $fileBaseName, string $author, string $date, int $duration, string $genre): AudioTrack
    {
        $filePath = $fileBaseName . ".mp3";

        if ($type === 1) {
            return new PodcastTrack($title, $filePath, $author, $date, $duration, $genre);
        } else {
            $albumTitle = $_POST['album'] ?? "Album inconnu";
            $year = $_POST['annee'] ?? null;
            $trackNumber = $_POST['numeroPiste'] ?? 0;

            $track = new AlbumTrack($title, $filePath, $albumTitle, $year, $trackNumber);
            $track->setGenre($genre);
            $track->setDuree($duration);
            $track->setAuteur($author);

            return $track;
        }
    }


    private function convertDurationToSeconds(string $duration): int
    {
        $parts = explode(':', $duration);
        if (count($parts) === 3) {
            return $parts[0] * 3600 + $parts[1] * 60 + $parts[2];
        }
        return $parts[0] * 60 + $parts[1];
    }

    
    private function getUploadErrorMessage(int $errorCode): string
    {
        $errorMessages = [
            UPLOAD_ERR_INI_SIZE => "Le fichier dépasse la taille autorisée.",
            UPLOAD_ERR_FORM_SIZE => "Le fichier dépasse la taille autorisée par le formulaire.",
            UPLOAD_ERR_PARTIAL => "Le fichier a été partiellement téléchargé.",
            UPLOAD_ERR_NO_FILE => "Aucun fichier téléchargé.",
            UPLOAD_ERR_NO_TMP_DIR => "Dossier temporaire manquant.",
            UPLOAD_ERR_CANT_WRITE => "Erreur d'écriture sur le disque.",
            UPLOAD_ERR_EXTENSION => "Échec dû à une extension PHP.",
        ];
        return $errorMessages[$errorCode] ?? "Erreur inconnue lors du téléchargement.";
    }
}
