<?php


namespace App\Controller;


class SocialAuthConfigController
{
    public function getGithubUrl() :string
    {
        /* GitHub
        Client ID
        597984a0e83f563d832d

        Client Secret
        00026b0739272712b7273ddf0e16bfe1c7f677c0
        */

        $clientId = '597984a0e83f563d832d';
        $githubUrl = 'https://github.com/login/oauth/authorize';

        $params = [
            'client_id' => $clientId,
            'redirect_uri' => 'http://localhost:8000/github-callback',
            'scope' => 'read:user user:email',
        ];

        $githubUrl .= '?' . http_build_query($params);

        return $githubUrl;
    }

    public function getGoogleUrl() :string
    {
        $client = $this -> getGoogleClient();

        $googleUrl = $client -> createAuthUrl();

        return $googleUrl;
    }

    public function getGoogleClient() :object
    {
        /* Google
        Client ID
        794142538438-ieuk05aohr6b3c1tadnltummj8t9jtqu.apps.googleusercontent.com

        Client Secret
        iW7RAoLa7LZ_lshb7nRMfUVc
        */

        $clientID = '794142538438-ieuk05aohr6b3c1tadnltummj8t9jtqu.apps.googleusercontent.com';
        $clientSecret = 'iW7RAoLa7LZ_lshb7nRMfUVc';
        $redirectUri = 'http://localhost:8000/google-callback';

        $client = new \Google_Client();
        $client->setClientId($clientID);
        $client->setClientSecret($clientSecret);
        $client->setRedirectUri($redirectUri);
        $client->addScope("email");
        $client->addScope("profile");
        $client->addScope('openid');

        return $client;
    }
}