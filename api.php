<?php

header('Content-Type: text/html; charset=utf-8');


if(isset($_GET['add'])){
        if ( isset($_GET['p'])){
                player_start($_GET['p']);
        }
}
else if(isset($_GET['get'])){

        if(isset($_GET['p'])){
                if (isset($_GET['g']))
                        get_game($_GET['p']);
                else
                        get_user($_GET['p']);
        }

        else if( isset($_GET['q']) ){
                get_ques($_GET['q']);
        }
}

function player_start($player){
  // main function: if queue empty => add to queue else => match
  $result = mysqli_fetch_assoc(mysqli_query(db(),"SELECT id, player FROM queue;"));
  if($result['player'] == NULL) add_queue($player);
  else{
    if( $result['player'] != $player){
    remove_queue($result['player']);
    add_game($result['player'],$player);
    }
  }
}

function get_game($player){
        $var  = mysqli_fetch_assoc(mysqli_query(db(),"SELECT * FROM games WHERE player1 = '$player' or player2 = '$player' "));
        get_ques$var['id']
}

function add_queue($player){
        mysqli_query(db(),"INSERT INTO queue (`player`) VALUES ('$player')");
}

function add_game($player1,$player2){
  mysqli_query(db(), "INSERT INTO games (`player1`,`player2`) VALUES ('$player1', '$player2')");
  # send random questions
}

function remove_queue($player){
  mysqli_query(db(), "DELETE FROM `queue` WHERE player = '$player'");
}

function get_ques($id_game){
  include_once 'generator.php';
  $questions = get_questions($id_game);
  $arrayName = array('id'=>$id_game,'array'=>$questions);
  print_r(json_encode($arrayName));
}

function get_user($player){
        $user =  mysqli_fetch_assoc(mysqli_query(db(), "SELECT * FROM Users WHERE UID = $player"));
        if( $user != ''){
          return JSON.encode($user);
        }else{
          return "{}";
        }
}

function  db(){
        return mysqli_connect('localhost','hack','xampp','hack');
        mysql_set_charset('utf8');

}
?>
