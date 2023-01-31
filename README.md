Integração para realizar validações de cartão com a Pagar.me utilizando a versão 4 da API da
da Pagar.me

Você pode saber mais **[clicando aqui](https://www.linkedin.com/in/cleyber-matos/)**.


## Documentation

###### For details on how to use, see a sample folder in the component directory. In it you will have an example of use for each class. It works like this:

Para mais detalhes sobre como usar, veja uma pasta de exemplo no diretório do componente. Nela terá um exemplo de uso para cada classe. Ele funciona assim:

#### User endpoint:

```php
<?php

require __DIR__ . "/../src/Payments.php";
require __DIR__ . "/../src/User.php";

// Create user
$user =  new User();
$user->bootstrap(
    "Nome", 
    "Sobrenome",
    "098980980"
);


$pay = new Payments($user, "sua api_key");

// Create new credit card
$pay->creditCard(
    "Nome Sobrenome", 
    "5308 0842 4204 2820",
    "1122",
    "416"
);

#### Payments:

```php
<?php


class Payments
{
    protected $user;
    protected $endpoint;
    protected $apiUrl;
    protected $apiKey;
    protected $data;
    protected $message;
    protected $callback;

    public function __construct(User $user, string $apiKey)
    {
        $this->user = $user;
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
```

## Credits

- [Cleyber F. Matos](https://github.com/cleyber2010) (Developer)
/
