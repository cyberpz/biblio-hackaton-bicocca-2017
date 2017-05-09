<?php

error_reporting(E_ALL);

$ini_array = parse_ini_file("config.ini");
$action = $_GET['action'] or die("Please specify an action");
$conn = mysqli_connect($ini_array['dbhost'], $ini_array['dbuser'], $ini_array['dbpass'], 'hack')
    or die('Error connecting to mysql: '.mysqli_error());


if ($action == "adder") {  // setter - nuovo game
  echo "inside adder";
  $result = mysqli_query($conn, "SELECT COUNT(*) FROM queue");
  if (mysqli_fetch_row($result) == 0) {  // se la coda e' vuota
    mysqli_query("INSERT INTO queue VALUES ".$_GET['id']);
  } else {        // altrimenti copia in games e cancella riga
    $player2 = mysqli_fetch_row(mysqli_query("SELECT player FROM queue;"));
    mysqli_query("INSERT INTO games (player1, player2, win) VALUES ({$_GET['id']}, $player2, NULL);");
    mysqli_query("DELETE FROM queue WHERE player = $player2;");
  }
}
else if($action == "updater") {
    $set = $_GET['what'];
    $value = $_GET['value'];
    $result = mysql_query("UPDATE games SET $set = $value WHERE id = ".$_GET['id']) or die('Could not run query');
} else if($action == "getter") {
   $where = $_GET['what'];
    /*if (($where = $_GET['what']) == 'users')  // users o games
        $what = "id,name";
    else if ($where == 'games')
        $what = "player1,player2,score,win";*/
    $result = mysqli_query("SELECT * FROM $where;") or die('Could not run query');

    $output = array();
    $i = 0;
    while($row = mysqli_fetch_row($result)) {
      foreach($row as $value) {
          array_push($output[$i], $value);
      }
      $i++;
    }
    echo json_encode($output);
}
