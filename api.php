<?php


if(isset($_GET['add'])){
  if ( isset($_GET['pid'])){
          player_start($_GET['pid']);       // ?add&pid=***
  }
}
else if(isset($_GET['set'])){
  if(isset($_GET['gid']) and isset($_GET['pid'])){
    update_results($_GET['gid'],$_GET['pid'],$_GET['corrette']);
  }
}

else if(isset($_GET['get'])){

  if(isset($_GET['pid'])){
          if (isset($_GET['game']))
                  get_game($_GET['pid']);   // ?get&game&pid=***
          else if( isset($_GET['results']) )
                  get_result($_GET['pid']);   // ?get&results&pid=***
  }

  else if(isset($_GET['gid'])){           
          get_ques($_GET['gid']);           // ?get&gid=***
  }
}

function update_results($gid,$pid,$corrette){
  $game = mysqli_fetch_assoc(mysqli_query(db(),"SELECT * FROM games WHERE id=$gid"));
  if($game['player1'] == $pid)
    mysqli_query(db(),"UPDATE `games` SET `corrette1`= $corrette WHERE id = $gid");
  else
    mysqli_query(db(),"UPDATE `games` SET `corrette2`= $corrette WHERE id = $gid");
}


function player_start($player){
  // main function: if queue empty => add to queue else => match
  $result = mysqli_fetch_assoc(mysqli_query(db(),"SELECT id, player, token FROM queue;"));
  if($result['player'] == NULL) add_queue($player);
  else{
    if( $result['player'] != $player){
    remove_queue($result['player']);
    echo add_game($result['player'],$player); // stampa il gid
    }
  }
}

function get_game($player){
        // altrimenti crea il game
        $var  = mysqli_fetch_row(mysqli_query(db(),"SELECT MAX(`id`) FROM games WHERE player1 = '$player' or player2 = '$player' "));
        // ...e ritorna il GID
        echo $var[0];
}

function add_queue($player){
        $token = md5(openssl_random_pseudo_bytes(8));
        mysqli_query(db(),"INSERT INTO queue (`player`, `token`) VALUES ('$player', '$token')");
}

function add_game($player1,$player2){

  $idDomande = array();
  $conn = db();
  /* generator */
  $q = 'SELECT `id` FROM `questions` ORDER BY RAND() LIMIT 5';
  if ($result = mysqli_query($conn, $q)) {   // estraggo l'id di 5 domande a caso
  /* fetch object array */

      while ($row = mysqli_fetch_assoc($result))
            array_push($idDomande, $row['id']);


      $q = sprintf("INSERT INTO games (`player1`,`player2`,`domande`) VALUES ('%s', '%s', '%s')", $player1, $player2, implode('|', $idDomande));
      if (!mysqli_query($conn, $q))
          printf("Errormessage: %s\n", $mysqli->error);
  }

  $gid =  mysqli_fetch_row(mysqli_query($conn, "SELECT MAX(`id`) FROM  games WHERE `player1` = '$player1' AND `player2` = '$player2' LIMIT 1"))[0];
  # send random questions
  mysqli_close($conn);
  
  // ritorna il GID
  return $gid;
}

function remove_queue($player){
  mysqli_query(db(), "DELETE FROM `queue` WHERE player = '$player'");
}

function get_ques($gid) {
  //include_once 'generator.php';
  //$questions = get_questions($gid);
  $conn = db();
  $questions = array();
  $q = sprintf('SELECT `domande` FROM `games` WHERE `id` = %d', $gid);
  $res = mysqli_query($conn, $q);

  echo mysqli_error($conn);
  $result = mysqli_fetch_row($res)[0];   // id delle domande 

  $q = sprintf('SELECT * FROM `questions` WHERE `id` IN (%s)', str_replace('|', ',', $result));
  if ($result = mysqli_query($conn, $q)) {

          while ($row = $result->fetch_assoc()) {

              $a = explode('|', $row['a']);
              $acorrect = $a[0];
              shuffle($a);
              $correct = array_search($acorrect, $a);
              array_push($questions, array(
                             'q' => $row['q'],
                             'a' => $a,
                             'correct' => $correct));

        }
        mysqli_close($conn);

  } else
    printf("Errormessage: %s\n", mysqli_error($conn));

  $json = utf8ize($questions);
  echo json_encode($json);
}

function get_result($player){  
  // restituisco { [corrette1,corrette2] }
    
  $arr = [];
  $r = (mysqli_query(db(), "SELECT * FROM games WHERE player1 = '$player' or player2 = '$player'"));
  
  while ($row = mysqli_fetch_assoc($r)) {
    if($row['player1'] == $player){
      $suck = [$row['corrette1'],$row['corrette2']];
      array_push($arr, $suck);
    }
    else{
      $suck = [$row['corrette2'],$row['corrette1']];
      array_push($arr, $suck);
    }
  }
  $json = array("r" => $arr);
  echo json_encode($json);
}

function utf8ize($d) {
        if (is_array($d)) {
            foreach ($d as $k => $v) {
                $d[$k] = utf8ize($v);
            }
        } else if (is_string ($d)) {
            return utf8_encode($d);
        }
        return $d;
}

function  db(){
        return mysqli_connect('localhost','hack','xampp','hack');
}
?>
