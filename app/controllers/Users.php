<?php


class Users extends Controller
{

    /**
     * Users constructor.
     */
    public function __construct()
    {
        $this->usersModel = $this->model('User');
    }

    public function register()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
            $data = array(
                'name' => trim($_POST['name']),
                'email' => trim($_POST['email']),
                'password' => trim($_POST['password']),
                'confirm_password' => trim($_POST['confirm_password']),
                'name_err' => '',
                'email_err' => '',
                'password_err' => '',
                'confirm_password_err' => ''
            );
            if (empty($data['name'])) {
                $data['name_err'] = 'Please enter the name';
            }
            if (empty($data['email'])) {
                $data['email_err'] = 'Please enter the email';
            } else if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
                $data['email_err'] = 'Please enter the valid email';
            } else if ($this->usersModel->findUserByEmail($data['email'])) {
                $data['email_err'] = 'Email is already taken';
            }
            if (empty($data['password'])) {
                $data['password_err'] = 'Please enter the password';
            } else if (strlen($data['password']) < 6) {
                $data['password_err'] = 'Password must consists at least 6 caracters';
            }
            if (empty($data['confirm_password'])) {
                $data['confirm_password_err'] = 'Please enter the confirm password';
            } else if (strlen($data['confirm_password']) < 6) {
                $data['confirm_password_err'] = 'Password must consists at least 6 caracters';
            } else if ($data['password'] !== $data['confirm_password']) {
                $data['confirm_password_err'] = 'Passwords do not match';
            }
            if (empty($data['name_err']) and empty($data['email_err']) and empty($data['password_err']) and empty($data['confirm_password_err'])) {
                $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
                if ($this->usersModel->register($data)) {
                    message('register_success', 'You are registred and now can log in');
                    header('Location: ' . URLROOT . '/' . 'users/login');
                } else {
                    die('Something went wrong');
                }
            }
        } else {
            $data = array(
                'name' => '',
                'email' => '',
                'password' => '',
                'confirm_password' => '',
                'name_err' => '',
                'email_err' => '',
                'password_err' => '',
                'confirm_password_err' => ''
            );
        }
        $this->view('users/register', $data);
    }


    public function login()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
            $data = array(
                'email' => trim($_POST['email']),
                'password' => trim($_POST['password']),
                'email_err' => '',
                'password_err' => ''
            );
            if (empty($data['email'])) {
                $data['email_err'] = 'Please enter the email';
            } else if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
                $data['email_err'] = 'Please enter the valid email';
            } else if (!$this->usersModel->findUserByEmail($data['email'])) {
                $data['email_err'] = 'User not found';
            }
            if (empty($data['password'])) {
                $data['password_err'] = 'Please enter the password';
            }

            if (empty($data['email_err']) and empty($data['password_err'])) {
                $loggedInUser = $this->usersModel->login($data['email'], $data['password']);
                if ($loggedInUser) {
                    $this->createUserSession($loggedInUser);
                    header('Location: ' . URLROOT . '/' . 'pages/index');
                } else {
                    $data['password_err'] = 'Password is incorrect';
                    $this->view('users/login', $data);
                }
            }
        } else {
            $data = array(
                'email' => '',
                'password' => '',
                'email_err' => '',
                'password_err' => ''
            );
        }
        $this->view('users/login', $data);
    }

    public function createUserSession($user)
    {
        $_SESSION['user_id'] = $user->id;
        $_SESSION['user_name'] = $user->name;
        $_SESSION['user_email'] = $user->email;
    }
}