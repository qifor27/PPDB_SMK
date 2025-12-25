<?php
/**
 * PPDB SMK - Session Management
 * Handles user authentication and session security
 */

require_once __DIR__ . '/config.php';

class Session {
    private static $started = false;
    
    /**
     * Start session with security settings
     */
    public static function start() {
        if (self::$started) {
            return;
        }
        
        // Session configuration
        ini_set('session.name', SESSION_NAME);
        ini_set('session.gc_maxlifetime', SESSION_LIFETIME);
        ini_set('session.cookie_lifetime', SESSION_LIFETIME);
        ini_set('session.cookie_httponly', 1);
        ini_set('session.use_only_cookies', 1);
        ini_set('session.use_strict_mode', 1);
        
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        self::$started = true;
        
        // Regenerate session ID periodically for security
        if (!isset($_SESSION['_last_regenerate'])) {
            $_SESSION['_last_regenerate'] = time();
        } elseif (time() - $_SESSION['_last_regenerate'] > 300) {
            session_regenerate_id(true);
            $_SESSION['_last_regenerate'] = time();
        }
    }
    
    /**
     * Set session value
     */
    public static function set($key, $value) {
        self::start();
        $_SESSION[$key] = $value;
    }
    
    /**
     * Get session value
     */
    public static function get($key, $default = null) {
        self::start();
        return $_SESSION[$key] ?? $default;
    }
    
    /**
     * Check if session key exists
     */
    public static function has($key) {
        self::start();
        return isset($_SESSION[$key]);
    }
    
    /**
     * Remove session key
     */
    public static function remove($key) {
        self::start();
        unset($_SESSION[$key]);
    }
    
    /**
     * Clear all session data
     */
    public static function clear() {
        self::start();
        $_SESSION = [];
    }
    
    /**
     * Destroy session completely
     */
    public static function destroy() {
        self::start();
        $_SESSION = [];
        
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(
                session_name(),
                '',
                time() - 42000,
                $params["path"],
                $params["domain"],
                $params["secure"],
                $params["httponly"]
            );
        }
        
        session_destroy();
        self::$started = false;
    }
    
    /**
     * Set flash message
     */
    public static function flash($key, $message) {
        self::set('_flash_' . $key, $message);
    }
    
    /**
     * Get and remove flash message
     */
    public static function getFlash($key, $default = null) {
        $message = self::get('_flash_' . $key, $default);
        self::remove('_flash_' . $key);
        return $message;
    }
    
    /**
     * Check if user is logged in
     */
    public static function isLoggedIn() {
        return self::has('user_id') && self::has('user_role');
    }
    
    /**
     * Get current user role
     */
    public static function getRole() {
        return self::get('user_role');
    }
    
    /**
     * Get current user ID
     */
    public static function getUserId() {
        return self::get('user_id');
    }
    
    /**
     * Check if user has specific role
     */
    public static function hasRole($role) {
        return self::getRole() === $role;
    }
    
    /**
     * Login user
     */
    public static function login($userId, $role, $userData = []) {
        self::start();
        session_regenerate_id(true);
        
        self::set('user_id', $userId);
        self::set('user_role', $role);
        self::set('user_data', $userData);
        self::set('login_time', time());
    }
    
    /**
     * Logout user
     */
    public static function logout() {
        self::destroy();
    }
    
    /**
     * Get user data
     */
    public static function getUserData($key = null) {
        $userData = self::get('user_data', []);
        if ($key !== null) {
            return $userData[$key] ?? null;
        }
        return $userData;
    }
    
    /**
     * Require login - redirect if not logged in
     */
    public static function requireLogin($redirectUrl = null) {
        if (!self::isLoggedIn()) {
            $redirect = $redirectUrl ?? SITE_URL . '/login.php';
            header("Location: {$redirect}");
            exit;
        }
    }
    
    /**
     * Require specific role
     */
    public static function requireRole($role, $redirectUrl = null) {
        self::requireLogin();
        
        if (!self::hasRole($role)) {
            $redirect = $redirectUrl ?? SITE_URL . '/login.php';
            self::flash('error', 'Anda tidak memiliki akses ke halaman ini.');
            header("Location: {$redirect}");
            exit;
        }
    }
    
    /**
     * Generate CSRF token
     */
    public static function generateCsrf() {
        $token = bin2hex(random_bytes(32));
        self::set('csrf_token', $token);
        return $token;
    }
    
    /**
     * Verify CSRF token
     */
    public static function verifyCsrf($token) {
        $storedToken = self::get('csrf_token');
        if (!$storedToken || !hash_equals($storedToken, $token)) {
            return false;
        }
        return true;
    }
    
    /**
     * Get CSRF input field
     */
    public static function csrfField() {
        $token = self::generateCsrf();
        return '<input type="hidden" name="csrf_token" value="' . htmlspecialchars($token) . '">';
    }
}

// Start session automatically
Session::start();
