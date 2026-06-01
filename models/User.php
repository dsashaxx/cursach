<?php

class User
{public function __construct()
    {if (!isset($_SESSION['api_users'])) {
            $_SESSION['api_users'] = [
                [
                    'id' => 1,
                    'name' => 'Александра',
                    'email' => 'user@test.ru',
                    'age' => 18
                ]
            ];
        }
    }

    public function all()
    {return $_SESSION['api_users']; }

    public function findById($id)
    { foreach ($_SESSION['api_users'] as $user) {
            if ($user['id'] == $id) {
                return $user;
            }
        }

        return null; }

    public function create($data)
    {$users = $_SESSION['api_users'];
        $ids = array_column($users, 'id');
        $newUser = [
            'id' => $ids ? max($ids) + 1 : 1,
            'name' => $data['name'],
            'email' => $data['email'],
            'age' => $data['age'] ?? null
        ];

        $users[] = $newUser;
        $_SESSION['api_users'] = $users;
        return $newUser;
    }
}
