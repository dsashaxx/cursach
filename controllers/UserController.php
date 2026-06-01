<?php

require_once __DIR__ . '/../models/User.php';
class UserController
{
    private $model;
    public function __construct()
    {$this->model = new User();}
    private function response($data, $code = 200)
    {
        http_response_code($code);
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        exit;
    }

    private function validate($input)
    {
        $vendor = __DIR__ . '/../vendor/autoload.php';

        if (file_exists($vendor)) {
            require_once $vendor;
        }
        if (class_exists('Respect\\Validation\\Validator')) {
            $v = 'Respect\\Validation\\Validator';
            if (!isset($input['name']) || !$v::stringType()->notEmpty()->validate($input['name'])) {
                $this->response(['error' => 'Имя обязательно'], 400);
            }
            if (!isset($input['email']) || !$v::email()->validate($input['email'])) {
                $this->response(['error' => 'Некорректный email'], 400);
            }
            if (isset($input['age']) && $input['age'] !== '' && !$v::intVal()->between(1, 120)->validate($input['age'])) {
                $this->response(['error' => 'Возраст должен быть от 1 до 120'], 400);
            }

            return;
        }
        if (empty($input['name']) || empty($input['email'])) {
            $this->response(['error' => 'name и email обязательны'], 400);
        }
        if (!filter_var($input['email'], FILTER_VALIDATE_EMAIL)) {
            $this->response(['error' => 'Некорректный email'], 400);
        }
        if (isset($input['age']) && $input['age'] !== '' && ($input['age'] < 1 || $input['age'] > 120)) {
            $this->response(['error' => 'Возраст должен быть от 1 до 120'], 400);
        }
    }
    public function getAll()
    {
        $this->response($this->model->all());
    }
    public function getById($id)
    {
        $user = $this->model->findById($id);
        if (!$user) {
            $this->response(['error' => 'Пользователь не найден'], 404);
        }
        $this->response($user);
    }
    public function create()
    {
        $input = json_decode(file_get_contents('php://input'), true) ?: [];
        $this->validate($input);
        $user = $this->model->create($input);
        $this->response($user, 201);
    }
}
