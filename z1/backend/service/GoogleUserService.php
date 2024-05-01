<?php
require_once 'vendor/autoload.php';

class GoogleUserService
{
    protected $client;

    public function __construct()
    {
        $this->client = new Google\Client();
        $this->client->setAuthConfig('./data/client_secret.json');
        $this->client->setRedirectUri('https://node86.webte.fei.stuba.sk/nobel-app/login');
        $this->client->addScope('email');
        $this->client->addScope('profile');
    }
    public function getLoginUrl()
    {
        return json_encode(['url' => $this->client->createAuthUrl()]);

    }

    public function authenticate($code)
    {
        try {
            $accessToken = $this->client->fetchAccessTokenWithAuthCode($code);
            $this->client->setAccessToken($accessToken);

            if ($this->client->isAccessTokenExpired()) {
                throw new Exception('Token expired');
            }

            $_SESSION['auth_token'] = $accessToken;

            $oauth2Service = new Google\Service\Oauth2($this->client);
            $account_info = $oauth2Service->userinfo->get();

            $g_fullname = $account_info->name;
            $g_email = $account_info->email;
            $g_surname = $account_info->familyName;

            $_SESSION['logged_in'] = true;
            // $userEmail = '$g_email';
            // $subject = 'Your new pass';
            // $message = "{password}";
            // $headers = 'From: webmaster@example.com' . "\r\n" .
            //     'Reply-To: webmaster@example.com' . "\r\n" .
            //     'X-Mailer: PHP/' . phpversion();

            // mail($userEmail, $subject, $message, $headers);

            return json_encode([
                'authInfo' => [
                    'authenticated' => $_SESSION['logged_in'],
                    'auth-token' => $_SESSION['auth_token'],
                    'user' => $g_email
                ]
            ]);
        } catch (Exception $e) {
            return json_encode(['authInfo' => ['error' => $e
                ]]);
        }
    }

    public function getAccessToken()
    {
        return $_SESSION['access_token'] ?? null;
    }
}

?>