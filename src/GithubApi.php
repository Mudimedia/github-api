<?php

namespace Mudimedia\GithubApi;

class GithubApi
{

    private $github_username = null;
    private $github_password = null;

    // Setting Github username and password to use while connecting to the Github API
    public function set_auth_credentials($username, $password)
    {
        $this->github_username = $username;
        $this->github_password = $password;
    }

    // Function to fetch github output
    private function fetch($url, $postdata = null, $getdata = null) {
        if ($url=="") return false;
    
        $get = ($getdata == null) ? "" : "?".$getdata;
    
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, false);
        curl_setopt($ch, CURLOPT_URL, $url.$get);
        curl_setopt($ch, CURLOPT_USERAGENT, "Github-Api");
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 20);
        curl_setopt($ch, CURLOPT_TIMEOUT, 20);
        
        if ($postdata != null)
        {
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata);
        }
        
        if ($this->github_username != null && $this->github_password != null) 
            curl_setopt($ch, CURLOPT_USERPWD, $this->github_username . ':' . $this->github_password);
    
        $content = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        
        curl_close($ch);
        
        return $content;
    }

    public function repo_info($owner_login = null, $repository_name = null)
    {
        if ($owner_login == null || $repository_name == null) return false;

        $content = $this->fetch("https://api.github.com/repos/$owner_login/$repository_name");

        return $content;
    }

    public function user_repos($owner_login = null)
    {
        if ($owner_login == null) return false;

        $content = $this->fetch("https://api.github.com/users/$owner_login/repos");

        return $content;
    }
}


?>