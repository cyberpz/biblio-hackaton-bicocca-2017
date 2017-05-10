<?php

if(isset($_GET['add']) and $_GET['p']!='')
	player_start($_GET['id']);
if(isset($_GET['get'] and $_GET['id']!='')
	get_user($_GET['id']);


function player_start($player){
  # main function: if queue empty => add to queue else => match 
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
  # send random questions
}

function remove_queue($player){
  mysqli_query(db(), "DELETE FROM `queue` WHERE player = $player");
}

function get_questions(){
  # get 5 random questions w/ answ
  // get min and  max int
  $min = inval(mysqli_fetch_all(mysqli_query(db(),"SELECT MIN(id) FROM questions"))[0]);
  $max = inval(mysqli_fetch_all(mysqli_query(db(),"SELECT MAX(id) FROM questions"))[0]);
  return "min: $min <br> max: $max";x

}

function get_user($player){
  $user =  mysqli_fetch_assoc(mysqli_query(db(), "SELECT * FROM Users WHERE UID = $player")));
  if( $user != ''){
    return JSON.encode($user);
  }else{
    return "{}";
  }
}




function db(){
  return mysqli_connect('localhost','root','','hack');
}
?>
