<?php

class SessionManager {
    private static $sessionDir = __DIR__ . '/session_data';
    private static $sessionFile = null;
    private static $sessionId = null;
    private static $sessionData = [];
    private static $cookieName = 'SESSION_ID';
    private static $isInitialized = false;

    public static function init() {
        if (self::$isInitialized) {
            return;
        }

        if (!is_dir(self::$sessionDir)) {
            @mkdir(self::$sessionDir, 0755, true);
        }

        if (isset($_COOKIE[self::$cookieName]) && !empty($_COOKIE[self::$cookieName])) {
            self::$sessionId = $_COOKIE[self::$cookieName];
            $file = self::$sessionDir . '/' . self::$sessionId . '.json';
            if (!file_exists($file)) {
                self::$sessionId = null;
            }
        }

        if (empty(self::$sessionId)) {
            self::$sessionId = self::generateSessionId();
            setcookie(self::$cookieName, self::$sessionId, [
                'expires' => time() + (12 * 3600),
                'path' => '/',
                'httponly' => true,
                'samesite' => 'Lax'
            ]);
        }

        self::$sessionFile = self::$sessionDir . '/' . self::$sessionId . '.json';
        self::loadSessionData();
        self::$isInitialized = true;
    }

    private static function generateSessionId() {
        return bin2hex(random_bytes(16));
    }

    private static function loadSessionData() {
        if (file_exists(self::$sessionFile)) {
            $content = file_get_contents(self::$sessionFile);
            self::$sessionData = json_decode($content, true) ?? [];
        } else {
            self::$sessionData = [];
        }
    }

    private static function saveSessionData() {
        if (empty(self::$sessionFile)) {
            return;
        }
        file_put_contents(self::$sessionFile, json_encode(self::$sessionData, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    }

    /**
     * Define um valor na sessão
     */
    public static function set($key, $value) {
        self::init();
        self::$sessionData[$key] = $value;
        self::saveSessionData();
    }

    /**
     * Obtém um valor da sessão
     */
    public static function get($key, $default = null) {
        self::init();
        return self::$sessionData[$key] ?? $default;
    }

    /**
     * Remove um valor da sessão
     */
    public static function remove($key) {
        self::init();
        unset(self::$sessionData[$key]);
        self::saveSessionData();
    }

    /**
     * Verifica se um valor existe na sessão
     */
    public static function has($key) {
        self::init();
        return isset(self::$sessionData[$key]);
    }

    /**
     * Verifica se o usuário está logado
     */
    public static function isLoggedIn() {
        return self::get('user_logged_in', false) === true;
    }

    /**
     * Obtém todos os dados da sessão
     */
    public static function getAll() {
        self::init();
        return self::$sessionData;
    }

    /**
     * Limpa a sessão completamente
     */
    public static function destroy() {
        self::init();
        
        // Remove o arquivo de sessão
        if (file_exists(self::$sessionFile)) {
            unlink(self::$sessionFile);
        }

        // Remove o cookie
        setcookie(self::$cookieName, '', [
            'expires' => time() - 3600,
            'path' => '/',
            'httponly' => true,
            'samesite' => 'Lax'
        ]);

        self::$sessionData = [];
        self::$sessionId = null;
        self::$sessionFile = null;
        self::$isInitialized = false;
    }

    /**
     * Obtém o ID da sessão atual
     */
    public static function getId() {
        self::init();
        return self::$sessionId;
    }

    /**
     * Limpa sessões expiradas (mais de 12 horas)
     */
    public static function cleanup() {
        if (!is_dir(self::$sessionDir)) {
            return;
        }

        $expireTime = time() - (12 * 3600); // 12 horas
        $files = glob(self::$sessionDir . '/*.json');

        foreach ($files as $file) {
            if (filemtime($file) < $expireTime) {
                @unlink($file);
            }
        }
    }
}

class _SESSION implements ArrayAccess {
    
    // Deve retornar booleano (verdadeiro/falso)
    public function offsetExists($offset): bool {
        return SessionManager::has($offset);
    }

    // Pode retornar qualquer tipo de dado (mixed)
    public function offsetGet($offset): mixed {
        return SessionManager::get($offset);
    }

    // Não retorna nada (void)
    public function offsetSet($offset, $value): void {
        SessionManager::set($offset, $value);
    }

    // Não retorna nada (void)
    public function offsetUnset($offset): void {
        SessionManager::remove($offset);
    }
}

// Cria uma instância global de $_SESSION que funciona com SessionManager
if (!isset($_SESSION)) {
    $_SESSION = new _SESSION();
}

// Inicializa a sessão automaticamente no primeiro require
SessionManager::init();
