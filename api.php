<?php
function player_start($player){
  $result = mysqli_fetch_assoc(mysqli_query(db(),"SELECT MIN(id), player FROM queue;"));
  if($result['player'] == NULL) add_queue($player);
  else{
    remove_queue($result['player']);
    add_game($result['player'],$player);
  }
}

function add_queue($player){
  mysqli_query(db(),"INSERT INTO queue (`player`) VALUES ($player)");
}

function add_game($player1,$player2){
  mysqli_query(db(), "INSERT INTO games (`player1`,`player2`) VALUES ($player1, $player2)");
  # NOW START THE GAME
}

function remove_queue($player){
  mysqli_query(db(), "DELETE FROM `queue` WHERE player = $player");
}

function db(){
  return mysqli_connect('localhost','root','','hack');
}

if( isset($_GET['new_player']) and isset($_GET['id']) ){
  player_start($_GET['id']);
}


?>
