<?php
require_once "php/dbConnect.php";
class UserService
{
    private $pdo;

    public function __construct()
    {
        $pdo = getPDODbConnection();
        $this->pdo = $pdo;
    }

    public function authenticate(string $user_email, string $user_pass, )
    {
        try {
            $stmt = $this->pdo->prepare("SELECT user_name, user_surname, user_pass FROM users WHERE user_email = :user_email");
            $stmt->bindParam(':user_email', $user_email);
            $stmt->execute();
            $userData = $stmt->fetch();
            if (password_verify($user_pass, $userData['user_pass'])) {
                $_SESSION['logged_in'] = true;
                $_SESSION['auth_token'] = $this->generateToken($user_email, $user_pass);
                return json_encode([
                    'authInfo' => [
                        'message' => "You're successfuly registered.",
                        'authenticated' => $_SESSION['logged_in'],
                        'auth-token' => $_SESSION['auth_token'],
                        'user' => $user_email
                    ]
                ]);
            } else {
                return json_encode(['authInfo' => ['error' => "Wrong Password or Email"]]);
            }
        } catch (PDOException $e) {
            return json_encode(['authInfo' => ['error' => "Authentication error" . ': ' . $e]]);
        }
    }

    public function registerNewUser(string $user_name, string $user_surname, string $user_email, string $user_pass)
    {
        try {
            try {
                $stmt = $this->pdo->prepare("SELECT id FROM users WHERE user_email = :user_email");
                $stmt->bindParam(':user_email', $user_email);
                $stmt->execute();
            } catch (PDOException $e) {
                return json_encode(['regInfo' => ['error' => "Registration error" . ': ' . $e]]);
            }
            if ($stmt->rowCount() > 0) {
                return json_encode(['regInfo' => ['error' => "User already exist."]]);
            } else {
                $stmt = $this->pdo->prepare("INSERT INTO users (user_name, user_surname, user_email, user_pass) 
                    VALUES (:user_name, :user_surname, :user_email, :user_pass)");
                $stmt->bindParam(':user_name', $user_name);
                $stmt->bindParam(':user_surname', $user_surname);
                $stmt->bindParam(':user_email', $user_email);

                $hashedPassword = password_hash($user_pass, PASSWORD_DEFAULT);
                $stmt->bindParam(':user_pass', $hashedPassword);
                $stmt->execute();
                $_SESSION['logged_in'] = true;
                $_SESSION['auth_token'] = $this->generateToken($user_email, $user_pass);
                return json_encode([
                    'regInfo' => [
                        'message' => "You're successfuly registered.",
                        'authenticated' => $_SESSION['logged_in'],
                        'auth-token' => $_SESSION['auth_token'],
                        'user' => $user_email
                    ]
                ]);
            }
        } catch (PDOException $e) {
            return json_encode(['regInfo' => ['error' => "Registration error" . ': ' . $e]]);
        }
    }

    public function logout()
    {
        $_SESSION = array();
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
        header("Location:/nobel-app?logged=false", true, 301);
        // return json_encode([]);
    }

    protected function generateToken($username, $password)
    {
        $salt = 'жопа-на-мосту';
        $timestamp = time();

        $hash = hash('sha256', $username . $password . $timestamp . $salt);

        return $hash;
    }
}