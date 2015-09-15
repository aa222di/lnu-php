<?php

namespace view;

class LayoutView {

  public function __construct( \model\Login $loginModel) {
    $this->loginModel = $loginModel;
  }
  
  public function render( LoginView $v, DateTimeView $dtv ) {
    echo '<!DOCTYPE html>
      <html>
        <head>
          <meta charset="utf-8">
          <title>Login Example</title>
        </head>
        <body>
          <h1>Assignment 2</h1>
          ' . $this->renderIsLoggedIn() . '
          
          <div class="container">
              ' . $v->response() . '
              
              ' . $dtv->show() . '
          </div>
         </body>
      </html>
    ';
  }
  
  private function renderIsLoggedIn() {
    $boolean = $this->loginModel->checkLoginStatus();
    var_dump($boolean);
    if ($boolean) {
      return '<h2>Logged in</h2>';
    }
    else {
      return '<h2>Not logged in</h2>';
    }
  }
}
