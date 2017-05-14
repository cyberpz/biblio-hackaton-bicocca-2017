var timer_count;
function populate(id){
	var ids = ["index","gioca","classifica","statistiche","about","singleplayer","multiplayer_find_game","multiplayer_game","risultati"];
	
	if(!ids.includes(id)){
		id = "index";
	}
	
	for(var i=0;i<ids.length;i++){
		var doc = document.getElementById(ids[i]);
		if(doc != null){
			doc.style.display = "none";
		}
	}
	if(id=="singleplayer" || id=="multiplayer_find_game" || id=="multiplayer_game"){
		$("#navigator").fadeOut(100);
	}else{
		$("#navigator").fadeIn(100);
	}
	$("#"+id).fadeIn(100);
}

function timer(){
	document.getElementById("timer").innerHTML = "15";
	var countDownDate = new Date().getTime()+16000;
	timer_count = setInterval(function() {
	var now = new Date().getTime();
	var distance = countDownDate - now;
	var seconds = Math.floor((distance % (1000 * 60)) / 1000);
	document.getElementById("timer").innerHTML = seconds;
		if (distance < 0) {
			clearInterval(timer_count);
			document.getElementById("timer").innerHTML = "0";
			evaluate_answer(-1);
		}
	}, 500);
}

function bar_progress(){
	var elem = document.getElementById("barra");
	var width = 1;
	var id = setInterval(frame, 10);
	function frame() {
		if (width >= 100) {
				width=0;
		} else {
		  width++;
		  elem.style.width = width + '%';
		}
	}
}

function display_multiplayer_find_game(){
	populate('multiplayer_find_game');
	bar_progress();
	add_match(pid);
	wait_for_match(pid);
}

function display_multiplayer_game(){
	//game_data = JSON.parse(`{"id":"20","array":[{"q":"Non provare piet\u00e0\u00a0 per i morti, Harry. Prova piet\u00e0\u00a0 per i vivi e soprattutto per coloro che vivono senza amore.","a":["Albus Silente","Sirius Black","Ron Weasley","Draco Malfoy"],"correct":0},{"q":"La bellezza delle cose esiste nella mente che le contempla. ","a":["Locke","Hume","Berkeley","Hobbes"],"correct":1},{"q":"Pecunia non olet","a":["Vespasiano","Nerone","Aristotele","Traiano"],"correct":0},{"q":"Perdona sempre i tuoi nemici; nulla li infastidisce cos\u00ec tanto.","a":["Dickens","Blake","Shakespear","Oscar Wilde"],"correct":3},{"q":"m'illumino d' immenso.","a":["Montale","Manzoni","Ungaretti","Foscolo"],"correct":2}]}`);
	populate('multiplayer_game');
	timer();
	n_question = 0;
	document.getElementById("question").innerHTML = game_data[n_question].q;
	document.getElementById("answer_0").innerHTML = game_data[n_question].a[0];
	document.getElementById("answer_1").innerHTML = game_data[n_question].a[1];
	document.getElementById("answer_2").innerHTML = game_data[n_question].a[2];
	document.getElementById("answer_3").innerHTML = game_data[n_question].a[3];
}

function display_risultati(){
	populate("risultati");
	var c = Math.floor(Math.random()*5);
	document.getElementById("c").innerHTML= c;
	document.getElementById("s").innerHTML = 5-c;
	
	
	
	/*var results;
	var doc = document.getElementById("risultati");
	var new_doc = document.createElement("div");
	new_doc.innerHTML="";
	for(var i=0;i<results.r.length;i++){
		doc.appendChild(new_doc);
	}*/
	
}