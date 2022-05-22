<?php

class User
{
    protected $data;

    public function __set($name, $value)
    {
        if (empty($this->data)) {
            $this->data = new \stdClass();
        }

        $this->data->$name = $value;
    }

    public function __isset($name)
    {
        return isset($this->data->$name);
    }

    public function __get($name)
    {
        return ($this->data->$name ?? null);
    }

    public function bootstrap(string $first_name, string $last_name, string $document)
    {
        $this->first_name =  $first_name;
        $this->last_name = $last_name;
        $this->document = $document;

        return $this;
    }

    public function data()
    {
        return $this->data;
    }
}