<?php


class Payments
{
    protected $endpoint;
    protected $apiUrl;
    protected $apiKey;
    protected $data;
    protected $message;
    protected $callback;

    public function __construct(string $apiKey)
    {
        $this->apiKey = $apiKey;
        $this->apiUrl = "https://api.pagar.me";
    }

    public function creditCard(string $name, string $number, string $expdate, string $cvv): ?Payments
    {
        $this->data = [
            "holder_name" => filter_var($name, FILTER_SANITIZE_STRIPPED), 
            "number" => $this->clearNumber($number), 
            "expiration_date" => $this->clearNumber($expdate), 
            "cvv" => $this->clearNumber($cvv)
        ];

        if (empty($name) || empty($number) || empty($expdate) || empty($cvv)) {
            $this->message = "Favor informar todos os dados";
            return null;
        }

        $this->endpoit = "/1/cards";
        $this->post();
        
        if (!$this->callback->valid) {
            $this->message = "O cartão informado não é valido";
            return null;
        }

        return $this;
    }
    
    private function clearNumber(string $data)
    {
        return preg_replace("/[^0-9]/", "", $data);
    }

    public function post()
    {
        $url = $this->apiUrl . $this->endpoit;
        $apiKey = ["api_key" => $this->apiKey];
        $data = array_merge($this->data, $apiKey);

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
        curl_setopt($ch, CURLOPT_HTTPHEADER, []);
        $this->callback = json_decode(curl_exec($ch));
        curl_close($ch);
    }
}