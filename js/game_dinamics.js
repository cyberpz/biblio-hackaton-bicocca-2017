var game_data;
var n_question=0;
var right_question=0;
var gid = -1;
var pid = Math.floor(Math.random()*10000);

function add_match(){
	console.log("PID: "+pid);
	$.get("api.php?add&pid="+pid);
}
function wait_for_match(id){
	$.get("api.php?get&game&pid="+pid,function(data){
		if(data==""){
			setTimeout(function(){wait_for_match(pid)}, 1000);
		}else{
			gid = data;
			get_questions();
		}
	});
}
function get_questions(){
	console.log("GID: "+gid);
	$.get("api.php?get&gid="+gid,function(data){
		game_data = JSON.parse(data);
		display_multiplayer_game()
	});
}
function evaluate_answer(n){
	var correct_answer = game_data[n_question].correct;
	if(n==correct_answer) right_question++;
	if(n_question==3){
		clearInterval(timer_count);
		$.get("api.php?set&gid="+gid+"&pid="+pid+"&corrette="+right_question);
		display_risultati();
		return;
	}
	n_question++;
	clearInterval(timer_count);
	timer();
	document.getElementById("question").innerHTML = game_data[n_question].q;
	document.getElementById("answer_0").innerHTML = game_data[n_question].a[0];
	document.getElementById("answer_1").innerHTML = game_data[n_question].a[1];
	document.getElementById("answer_2").innerHTML = game_data[n_question].a[2];
	document.getElementById("answer_3").innerHTML = game_data[n_question].a[3];
}