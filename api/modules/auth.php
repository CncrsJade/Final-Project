<?php
class Auth {
    private $key;
    private $cipher = "AES-256-CBC";
    
    public function __construct() {
        $this->key = $_ENV['ENCRYPTION_KEY'];
        if (!$this->key) {
            throw new Exception('Encryption key not set');
        }
    }
    
    public function encryptData($data) {
        $ivLength = openssl_cipher_iv_length($this->cipher);
        $iv = openssl_random_pseudo_bytes($ivLength);
        
        $encrypted = openssl_encrypt(
            json_encode($data),
            $this->cipher,
            $this->key,
            OPENSSL_RAW_DATA,
            $iv
        );
        
        return [
            'data' => base64_encode($encrypted),
            'iv' => base64_encode($iv)
        ];
    }
    
    public function decryptData($encryptedData, $iv) {
        $decrypted = openssl_decrypt(
            base64_decode($encryptedData),
            $this->cipher,
            $this->key,
            OPENSSL_RAW_DATA,
            base64_decode($iv)
        );
        
        return json_decode($decrypted, true);
    }
    
    public function validateLogin($username, $password) {
        global $db;
        
        $stmt = $db->prepare("SELECT id, password, role FROM users WHERE username = ?");
        $stmt->execute([$username]);
        $user = $stmt->fetch();
        
        if ($user && password_verify($password, $user['password'])) {
            return [
                'success' => true,
                'user' => [
                    'id' => $user['id'],
                    'username' => $username,
                    'role' => $user['role']
                ]
            ];
        }
        
        return ['success' => false, 'message' => 'Invalid credentials'];
    }
}
