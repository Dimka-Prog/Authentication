<?php

namespace MySql\MVC\Models;

use PDO;

class Model
{
    private PDO $link;
    public array $data = [];

    public function __construct()
    {
        $dsn = 'mysql:host=localhost;dbname=PDO_MySql';
        $this->link = new PDO($dsn, 'admin', 'password');
    }

    private function getAll() : void
    {
        $this->data = $this->query("select* from RegisteredUsers");
    }

    public function getHashPassword(string $password) : string
    {
        return password_hash($password, PASSWORD_DEFAULT);
    }

    public function checkUser(string $login, string $password) : bool
    {
        $this->getAll();

        foreach ($this->data as $user)
        {
            if ($user['login'] === $login && password_verify($password, $user['passwordUser']))
                return true;
        }
        return false;
    }

    private function checkingUserRegistration(string $login) : bool
    {
        $this->getAll();

        foreach ($this->data as $user)
        {
            if ($user['login'] === $login) {
                echo "<script>alert(\"Пользователь с таким логином уже существует\")</script>";
                return false;
            }
        }
        return true;
    }

    public function addUser(string $login, string $password) : void
    {
        if($login !== "" && $password !== "" && $this->checkingUserRegistration($login)) {
            $hashPas = $this->getHashPassword($password);
            $this->execute("insert into RegisteredUsers values (default,'$login', '$hashPas')");
            echo "<script>alert(\"Вы успешно зарегестрировались\")</script>";
        }
    }

    public function execute($sql) : void
    {
        $sth = $this->link->prepare($sql);
        $sth->execute();
    }

    private function query($sql): array
    {
        $sth = $this->link->prepare($sql);
        $sth->execute();

        $result = $sth->fetchAll(PDO::FETCH_ASSOC);

        if ($result === false)
            return [];

        return  $result;
    }
}