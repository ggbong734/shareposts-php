<?php
  class Users extends Controller {
    public function __construct(){
      $this->userModel = $this->model('User');
    }
    public function register(){
      // Check for POST
      if($_SERVER['REQUEST_METHOD'] == 'POST'){
        // Process form
        // Sanitize POST data (even when using prepared statement)
        $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

        // Init data using user's input
        $data=[
          'name'=> trim($_POST['name']),
          'email' => trim($_POST['email']),
          'password' => trim($_POST['password']),
          'confirm_password' => trim($_POST['confirm_password']),
          'name_err' => '',
          'email_err' => '',
          'password_err' => '',
          'confirm_password_err' => ''
        ];

        // Validate email
        if(empty($data['email'])){
          $data['email_err'] = ' Please enter email';
        } else {
          // if email is valid, Check email in database
          if($this->userModel->findUserByEmail($data['email'])){
            $data['email_err'] = ' Email is already taken';
          }
        };

        // Validate Name
        if(empty($data['name'])){
          $data['name_err'] = ' Please enter name';
        };

        // Validate Password
        if(empty($data['password'])){
          $data['password_err'] = ' Please enter password';
        } elseif(strlen($data['password']) < 6) {
          $data['password_err'] = ' Password must be at least 6 characters';
        };

        // Validate confirm Password
        if(empty($data['confirm_password'])){
          $data['confirm_password_err'] = ' Please confirm password';
        } else {
          if ($data['password'] != $data['confirm_password']){
            $data['confirm_password_err'] = 'Passwords do not match';
          }
        };

        // Make sure errors are empty
        if (empty($data['email_err']) && empty($data['name_err']) &&
        empty($data['password_err']) && empty($data['confirm_password_err'])){
          // die("SUCCESS");
          // Hash Password
          $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);

          // Register User
          if($this->userModel->register($data)){
          //redirect to login page if registration successful
          redirect('users/login');
          } else {
            die("Something went wrong");
          };
        } else {
          // Load view with errors
          $this->view('users/register', $data);
        };



      } else {
        // Init data
        $data=[
          'name'=> '',
          'email' => '',
          'password' => '',
          'confirm_password' => '',
          'name_err' => '',
          'email_err' => '',
          'password_err' => '',
          'confirm_password_err' => ''
        ];

        // Load view
        $this->view('users/register', $data);
      }
    }

    public function login(){
      // Check for POST-
      if($_SERVER['REQUEST_METHOD'] == 'POST'){
        // Process form if form is submitted
        $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

        // Init data using user's input
        $data=[
          'email' => trim($_POST['email']),
          'password' => trim($_POST['password']),
          'email_err' => '',
          'password_err' => '',
        ];

        // Validate email
        if(empty($data['email'])){
          $data['email_err'] = ' Please enter email';
        };

        // Validate Password
        if(empty($data['password'])){
          $data['password_err'] = ' Please enter password';
        } elseif(strlen($data['password']) < 6) {
          $data['password_err'] = ' Password must be at least 6 characters';
        };

        // Make sure errors are empty
        if (empty($data['email_err']) && empty($data['password_err'])){
          // die("SUCCESS");
          // validated

        } else {
          // Load view with errors
          $this->view('users/login', $data);
        };


      } else {
        // Init data
        $data=[
          'email' => '',
          'password' => '',
          'email_err' => '',
          'password_err' => '',
        ];

        // Load view
        $this->view('users/login', $data);
      }
    }
  }
