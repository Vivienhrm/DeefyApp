<?php

class Psr4ClassLoader
{
    private string $prefixe; // Préfixe de namespace
    private string $chemin;  // Chemin du répertoire contenant les fichiers de classe

    /**
     * Psr4ClassLoader constructor.
     *
     * @param string $prefixe Le préfixe de namespace à associer à ce chargeur.
     * @param string $chemin Le chemin du répertoire où les classes sont stockées.
     * @throws InvalidArgumentException Si le chemin spécifié n'est pas un répertoire.
     */
    public function __construct(string $prefixe, string $chemin)
    {
        $this->prefixe = rtrim($prefixe, '\\') . '\\'; // Assure que le préfixe se termine par un \
        $this->chemin = rtrim($chemin, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR; // Assure que le chemin se termine par un /

        // Vérifie si le chemin spécifié est un répertoire
        if (!is_dir($this->chemin)) {
            throw new InvalidArgumentException("Le chemin spécifié n'est pas un répertoire : $chemin");
        }
    }

    /**
     * Charge une classe en fonction de son nom.
     *
     * @param string $class Le nom complet de la classe à charger.
     * @return bool Retourne true si la classe a été chargée avec succès, false sinon.
     */
    public function loadClass(string $class): bool
    {
        // Remplace le préfixe par le chemin pour obtenir le chemin du fichier
        $file_name = str_replace($this->prefixe, $this->chemin, $class);
        $file_name = str_replace('\\', DIRECTORY_SEPARATOR, $file_name) . ".php"; // Remplace les backslashes par le séparateur de répertoire

        // Vérifie si le fichier existe et l'inclut si c'est le cas
        if (is_file($file_name)) {
            require_once($file_name);
            return true; // Indique que le chargement a réussi
        }

        return false; // Indique que le chargement a échoué
    }

    /**
     * Enregistre le chargeur de classes dans la pile d'autoloaders de PHP.
     *
     * @return void
     */
    public function register(): void
    {
        spl_autoload_register([$this, 'loadClass']); // Enregistre la méthode loadClass comme autoloader
    }
}