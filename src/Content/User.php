<?php namespace RobinCms\Core\Content;

use RobinCms\Core\Http\Session;

class User
{

    /**
     * Is the user logged in
     * @var boolean
     */
    protected $loggedIn = false;

    /**
     * User session key
     * @var string
     */
    protected $usrSess = 'current_robin_user';

    /**
     * Current user
     * @var array
     */
    protected $user = [];

    /**
     * Content instance
     * @var Content
     */
    protected $content;


    /**
     * Create new instance
     * 
     * @param Content $content    DataLoader instance
     */
    public function __construct(Content $content, Session $session)
    {
        $this->content  = $content;
        $this->session  = $session;
        $this->loggedIn = $session->get($this->usrSess, false) === true;
        $this->user     = $this->content->user();
    }


    /**
     * Check if a user has been created yet
     * 
     * @return boolean
     */
    public function isUserCreated()
    {
        return !empty($this->user['username'])
            && !empty($this->user['password']) 
            && !empty($this->user['email']);
    }


    /**
     * Create and store a new user object
     * 
     * @param  array  $data
     * @return boolean
     */
    public function create(array $data)
    {
        $errors = $this->validate($data, $validatePassword = true);
        if ($errors) {
            return $errors;
        }

        $data['password'] = $this->hashString($data['password']);

        
        if (!$this->content->updateUser($data)) {
            $errors[] = "Error saving user";
        }
        

        return $errors?: true;
    }


    /**
     * Update a user object
     * 
     * @param  array  $data
     * @return boolean
     */
    public function update(array $data, $forcePassword = false)
    {
        $validatePassword = $forcePassword 
            || (arr_val($data, 'password') != '' || arr_val($data, 'confirm_password') != '');

        $errors = $this->validate($data, $validatePassword);
        if ($errors) {
            return $errors;
        }

        $updatedData = [
            'username' => $data['username'],
            'email'    => $data['email'],
        ];

        if ($validatePassword) {
            $updatedData['password'] = $this->hashString($data['password']);
        }

        return $this->content->updateUser($updatedData);
    }


    /**
     * Validate user data
     * 
     * @param  array   $data
     * @param  boolean $validatePassword
     * @return [type]                    [description]
     */
    public function validate($data, $validatePassword = false)
    {
        $errors = [];

        $username = arr_val($data, 'username');
        if ($username != trim($username) || strlen($username) < 3) {
            $errors[] = "Username should be at least 3 characters long and can not start or end with spaces";
        }

        if (!filter_var(arr_val($data, 'email'), FILTER_VALIDATE_EMAIL)) {
            $errors[] = "You have to enter a valid email address";
        }

        if ($validatePassword) {
            $password = arr_val($data, 'password');
            if ($password != trim($password) || strlen($password) < 5) {
                $errors[] = "Password should be at least 5 characters long and can not start or end with spaces";
            }

            if ($password != arr_val($data, 'confirm_password')) {
                $errors[] = "The passwords does not match";
            }
        }

        return $errors;
    }


    /**
     * Get the loaded user from the user repository
     * This returns the user regardless of log in state
     * 
     * @return array
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Authenticate a user and log him/her in
     * 
     * @return boolean
     */
    public function login($username, $password)
    {
        $usr    = arr_val($this->user, 'username');
        $pwd    = arr_val($this->user, 'password');

        if (empty($usr) || empty($pwd) || $username != $usr || !$this->hashVerify($password, $pwd)) {
            return false;
        }

        $this->session->set($this->usrSess, true);
        return true;
    }


    /**
     * Check if the user is logged in
     * 
     * @return boolean
     */
    public function loggedIn()
    {
        return $this->loggedIn;
    }


    /**
     * Log the user out
     * 
     * @return void
     */
    public function logout()
    {
        $this->session->destroy();
    }

    /**
     * Hash a string
     * 
     * @param  string   $string
     * @return string
     */
    protected function hashString($string)
    {
        return password_hash($string, PASSWORD_BCRYPT , ['cost' => 10]);
    }


    /**
     * Verify a hash with a string
     * 
     * @param  string $string
     * @param  string $hash
     * @return boolean
     */
    protected function hashVerify($string, $hash)
    {
        return password_verify($string, $hash);
    }

}