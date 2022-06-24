<?php

namespace MySql\MVC\Controllers;

use Exception;
use MySql\MVC\Models\Model;
use Twig\Environment;

class Controller
{
    private Environment $twig;
    private Model $model;

    public function __construct($twig)
    {
        $this->twig = $twig;
        $this->model = new Model();
    }


    public function action() : void
    {
        try {
            if (isset($_COOKIE['login']) && isset($_COOKIE['password']) && isset($_POST['exitButton']) === false)
            {
                echo $this->twig->render('mainForm.twig', [
                    'login' => $_COOKIE['login'],
                    'hashPassword' => $_COOKIE['password'],
                ]);
            }
            else {
                if ($_SERVER['REQUEST_URI'] === '/?action=Authorization' && $this->model->checkUser($_POST['userAutLogin'], $_POST['userAutPassword']))
                {
                    setcookie("login", $_POST['userAutLogin'], time() + 86400);
                    setcookie("password", $this->model->getHashPassword($_POST['userAutPassword']), time() + 86400);
                    echo $this->twig->render('mainForm.twig', [
                        'login' => $_COOKIE['login'],
                        'hashPassword' => $_COOKIE['password'],
                    ]);
                }
                elseif ($_SERVER['REQUEST_URI'] === '/?action=Registration')
                {
                    $this->model->addUser($_POST['userRegLogin'], $_POST['userRegPassword']);
                    echo $this->twig->render('authorizationForm.twig');
                }
                elseif ($_SERVER['REQUEST_URI'] === '/') {
                    echo $this->twig->render('authorizationForm.twig');
                }
                else {
                    header('Location: /');
                    echo $this->twig->render('authorizationForm.twig');
                }
            }

            if (isset($_POST['exitButton']))
            {
                setcookie("login", null);
                setcookie("password", null);
            }

        }catch (Exception $exception) {
            die ('ERROR: ' . $exception->getMessage());
        }
    }
}