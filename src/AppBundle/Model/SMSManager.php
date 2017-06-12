<?php
namespace AppBundle\Model;

class SMSManager {
    private $endPoint;
    private $username;
    private $password;

    public function __construct($endpoint, $username, $password)
    {
        $this->endPoint = $endpoint;
        $this->username = $username;
        $this->password = $password;
    }

    function sendSms($mobileNum,$text)
    {
        $url = $this->endPoint.'?'.'username='.$this->username.'&password='.$this->password.'&ms='.$mobileNum.'&txt='.urlencode($text);
        $ch = curl_init();

        //set the url, number of POST vars, POST data
        curl_setopt($ch,CURLOPT_URL, $url);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER, 1);

        //execute post
        $result = curl_exec($ch);

        $info = curl_getinfo($ch);

        //close connection
        curl_close($ch);
        if ($info['http_code'] == 200) {
            return json_decode($result);
        }
    }
    /*public function create($data)
    {
        $ch = curl_init();
        //set the url, number of POST vars, POST data
        curl_setopt($ch,CURLOPT_URL, $this->apiEndpoint.'/api/save-khatian');
        curl_setopt($ch,CURLOPT_POST, count($data));
        curl_setopt($ch,CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'X-ApiKey: 123'
        ]);
        $result = curl_exec($ch);
        $info   = curl_getinfo($ch);
        curl_close($ch);

        if ($info['http_code'] == 201) {
            return json_decode($result);
        }
        throw new \Exception($result);
    }*/

}
