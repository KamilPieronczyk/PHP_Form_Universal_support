<?php
    $mysql_server = "localhost";
    // admin
    $mysql_admin = "root";
    // hasło
    $mysql_pass = "";
    // nazwa baza
    $mysql_db = "forms";
    // nawiązujemy połączenie z serwerem MySQL
    $polanczenie=@new mysqli($mysql_server, $mysql_admin, $mysql_pass,$mysql_db);
    //or die('Brak połączenia z serwerem MySQL.');
    // łączymy się z bazą danych
    //@mysqli_select_db($mysql_db)
    //or die('Błąd wyboru bazy danych.');
    if (mysqli_connect_errno())
  {
  echo "Połączenie z MySQL nieudane: " . mysqli_connect_error();
  };
  function conn() {
  // serwer
  $mysql_server = "localhost";
  // admin
  $mysql_admin = "root";
  // hasło
  $mysql_pass = "";
  // nazwa baza
  $mysql_db = "forms";
    // nawiązujemy połączenie z serwerem MySQL
  $polanczenie=@new mysqli($mysql_server, $mysql_admin, $mysql_pass,$mysql_db);
return $polanczenie;
}
?>
