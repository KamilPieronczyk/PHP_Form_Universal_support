<?php
require 'connect.php';
require 'Form_Universal.class.php';
require 'functions.php';
?>
<?php
  function nick() {
    if (isset($_POST['nick'])) {
      return $_POST['nick'];
    }
  }
  $form = new Form_Universal ('example', array(
    'text' => array('password2','nick'),
    'checkbox' => 'check'
  ));
  $form->send();
?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>Example</title>

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.6/css/bootstrap.min.css" integrity="sha384-rwoIResjU2yc3z8GV/NPeZWAv56rSmLldC3R/AZzGRnGxQQKnKkoFVhFQhNUwEyJ" crossorigin="anonymous">
  </head>
  <body>
    <h1>Hello, world!</h1>
    <form method="post">
      <fieldset class="form-group">
        <label for="exampleInputPassword1">Password</label>
        <input type="text" name="password2" class="form-control" id="exampleInputPassword1" value="">
      </fieldset>
      <fieldset class="form-group">
        <label for="exampleInputPassword1">Password</label>
        <input type="text" name="nick" class="form-control" id="exampleInputPassword1" value="">
      </fieldset>
      <div class="checkbox">
        <label>
          <input type="checkbox" name='check'> Check me out
        </label>
      </div>
      <button type="submit" class="btn btn-primary">Submit</button>
    </form>
    <script src="https://code.jquery.com/jquery-3.1.1.slim.min.js" integrity="sha384-A7FZj7v+d/sdmMqp/nOQwliLvUsJfDHW+k9Omg/a/EheAdgtzNs3hpfag6Ed950n" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/tether/1.4.0/js/tether.min.js" integrity="sha384-DztdAPBWPRXSA/3eYEEUWrWCy7G5KFbe8fFjk5JAIxUYHKkDx6Qin1DkWx51bBrb" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.6/js/bootstrap.min.js" integrity="sha384-vBWWzlZJ8ea9aCX4pEW3rVHjgjt7zpkNpZk+02D9phzyeVkE+jo0ieGizqPLForn" crossorigin="anonymous"></script>
  </body>
</html>
