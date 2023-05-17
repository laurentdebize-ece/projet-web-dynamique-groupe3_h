<?php
require_once 'src/database/models/User.php';

/// Gère les sessions utilisateur
class SessionManager
{
    private const SESSION_VALID_UNTIL = "valid_until";
    private const SESSION_VALID_DURATION = 3600;
    private const SESSION_UID = "uid";
    private const SESSION_UAUTHORITY = "authority";

    private static ?SessionManager $instance = null;

    private function __construct()
    {
        session_start();
    }

    /// Récupère l'instance du gestionnaire de session
    public static function getInstance(): SessionManager
    {
        if (self::$instance === null) {
            self::$instance = new SessionManager();
        }

        return self::$instance;
    }

    /// Vérifie si l'utilisateur est connecté.
    public function isLoggedIn(): bool
    {
        return isset($_SESSION[self::SESSION_UID]) && $_SESSION[self::SESSION_UID] !== null;
    }

    /// Vérifie si la session est valide.
    public function isSessionValid(): bool
    {
        return isset($_SESSION[self::SESSION_VALID_UNTIL]) && $_SESSION[self::SESSION_VALID_UNTIL] > time();
    }

    public function getAuthority(): int
    {
        return $_SESSION[self::SESSION_UAUTHORITY];
    }

    /// Connecte un utilisateur.
    public function login(User $user): void
    {
        session_regenerate_id(true);
        $_SESSION[self::SESSION_UID] = $user->getID();
        $_SESSION[self::SESSION_VALID_UNTIL] = time() + self::SESSION_VALID_DURATION;
        $_SESSION[self::SESSION_UAUTHORITY] = $user->getAccountType();
    }

    /// Déconnecte un utilisateur.
    public function logout(): void
    {
        session_destroy();
        session_start();
    }

    /// Récupère l'utilisateur connecté.
    public function getUser(): ?User
    {
        if ($this->isLoggedIn()) {
            $sess_id = $_SESSION[self::SESSION_UID];
            return User::select(DatabaseController::getInstance(), null, ["WHERE", "`idUser` = $sess_id", "LIMIT 1"])->fetchTyped();
        }
        return null;
    }

    /// Vérifie que l'utilisateur est connecté et que la session est valide pour aacéder à la page.
    public function ensureLoggedIn(): void
    {
        if (!$this->isLoggedIn() || !$this->isSessionValid()) {
            header("Location: /login.php?redirect=" . urlencode($_SERVER["REQUEST_URI"]));
            exit();
        }
    }

    /// Vérifie que l'utilisateur est connecté et que la session est valide pour aacéder à la page et possède l'autorité nécessaire.
    public function ensureHasAuthority(int $authority): void
    {
        $this->ensureLoggedIn();
        if ($this->getAuthority() != $authority) {
            // header("Location: /");
            echo "non je crois pas non";
            http_response_code(403);
            exit();
        }
    }
}
