if(isset($_GET['add'])){
        if ( isset($_GET['p']))
                player_start($_GET['p']);
}

else if(isset($_GET['get'])){
        if(isset($_GET['id'])){
                if (isset($_GET['g']))
                        get_game($_GET['id']);
                else
                        get_user($_GET['id']);
        }
        else if(isset($_GET['q']))
                get_questions();
}

function player_start($player){
  # main function: if queue empty => add to queue else => match
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
        $var  = mysqli_fetch_assoc(mysqli_query(db(),"SELECT * FROM games WHERE player1=$playerr
 or player2 = $player "));
        print($var['id']);
}

function add_queue($player){
        mysqli_query(db(),"INSERT INTO queue (`player`) VALUES ($player)");
}

function add_game($player1,$player2){
        mysqli_query(db(), "INSERT INTO games (`player1`,`player2`) VALUES ($player1, $player2))
");
  # send random questions
  print(mysqli_fetch_assoc(mysqli_query(db(),"SELECT id FROM games WHERE player1 = $player1;")))
['id']);
}

function remove_queue($player){
  mysqli_query(db(), "DELETE FROM `queue` WHERE player = $player");
}

function get_questions(){
  # get 5 random questions w/ answ
  // get min and  max int
  $min =mysqli_fetch_assoc(mysqli_query(db(),"SELECT MIN(id) FROM questions"))['MIN(id)'];
  $max =mysqli_fetch_assoc(mysqli_query(db(),"SELECT MAX(id) FROM questions"))['MAX(id)'];
  $rand = rand($min,$max);

  $question = mysqli_fetch_assoc(mysqli_query(db(),"SELECT * FROM questions WHERE id = $rand")))
;
  $ans = explode('|',$question['a']);
  $question['a'] = $ans;
  $question['correct'] = $ans[0];
  print(json_encode($question));

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
}
?>
