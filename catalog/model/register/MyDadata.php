<?php

class MyDadata
{
    private $clean_url = "https://cleaner.dadata.ru/api/v1/clean";
    private $suggest_url = "https://suggestions.dadata.ru/suggestions/api/4_1/rs";
    private $token;
    private $secret;
    private $handle;

    public function __construct($token, $secret)
    {
        $this->token = $token;
        $this->secret = $secret;
    }

    /**
     * Initialize connection.
     */
    public function init()
    {
        $this->handle = curl_init();
        curl_setopt($this->handle, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($this->handle, CURLOPT_HTTPHEADER, array(
            "Content-Type: application/json",
            "Accept: application/json",
            "Authorization: Token " . $this->token,
            "X-Secret: " . $this->secret,
        ));
        curl_setopt($this->handle, CURLOPT_POST, 1);
    }

    public function findByEmail($email)
    {
        return $this->executeRequest($this->suggest_url . '/findByEmail/company', [
            'query' => $email,
        ]);
    }

    public function findByInn($inn)
    {
        return $this->executeRequest($this->suggest_url . '/findById/party', [
            'query' => $inn,
        ]);
    }

    public function findByInnKz($inn)
    {
        return $this->executeRequest($this->suggest_url . '/findById/party_kz', [
            'query' => $inn,
        ]);
    }

    public function findByInnBy($inn)
    {
        return $this->executeRequest($this->suggest_url . '/findById/party_by', [
            'query' => $inn,
        ]);
    }

    public function findRuCompany($companyName)
    {
        return $this->executeRequest(
            $this->suggest_url . '/suggest/party',
            [
                'query' => $companyName,
            ]
        );
    }

    public function findByCompany($companyName)
    {
        return $this->executeRequest(
            $this->suggest_url . '/suggest/party_by',
            [
                'query' => $companyName,
            ]
        );
    }

    public function findKzCompany($companyName)
    {
        return $this->executeRequest(
            $this->suggest_url . '/suggest/party_kz',
            [
                'query' => $companyName,
            ]
        );
    }

    /**
     * Close connection.
     */
    public function close()
    {
        curl_close($this->handle);
    }

    private function executeRequest($url, $fields)
    {
        curl_setopt($this->handle, CURLOPT_URL, $url);
        if ($fields != null) {
            curl_setopt($this->handle, CURLOPT_POST, 1);
            curl_setopt($this->handle, CURLOPT_POSTFIELDS, json_encode($fields));
        } else {
            curl_setopt($this->handle, CURLOPT_POST, 0);
        }
        $result = $this->exec();
        $result = json_decode($result, true);
        return $result;
    }

    private function exec()
    {
        $result = curl_exec($this->handle);
        $info = curl_getinfo($this->handle);
        if ($info['http_code'] == 429) {
            throw new TooManyRequests();
        } elseif ($info['http_code'] != 200) {
            throw new Exception('Request failed with http code ' . $info['http_code'] . ': ' . $result);
        }
        return $result;
    }
}

class TooManyRequests extends Exception
{
}