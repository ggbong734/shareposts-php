<?php

class Posts extends Controller {
  public function __construct(){
    // if not logged in
    if(!isLoggedIn()){
      redirect('users/login');
    }
  }

  public function index(){
    $data = [];
    $this->view('posts/index', $data);
  }
}
