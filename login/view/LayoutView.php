<?php

namespace view;

class LayoutView {

  public function __construct( \model\Login $loginModel ) {
    $this->loginModel = $loginModel;
  }
  
  public function render( IView $v, DateTimeView $dtv, NavigationView $nav,  $message ) {
    echo '<!DOCTYPE html>
      <html>
        <head>
          <meta charset="utf-8">
          <title>Login Example</title>
        </head>
        <body>
          <h1>Assignment 4</h1>
          ' . $this->renderIsLoggedIn($nav) . '
          
          <div class="container">
              ' . $v->response($message) . '
              
              ' . $dtv->show() . '
          </div>
         </body>
      </html>
    ';
  }
  
  private function renderIsLoggedIn($nav) {
    if ($this->loginModel->checkLoginStatus()) {
      return '<h2>Logged in</h2>';
    }
    else {
      return '<p>' . $nav->response() . '</p><h2>Not logged in</h2>';
    }
  }
}
