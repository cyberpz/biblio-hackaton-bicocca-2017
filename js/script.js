function populate(id){
	var ids = ["index","gioca","classifica","statistiche","about","singleplayer","multiplayer"];
	
	if(!ids.includes(id)){
		id = "index";
	}
	
	for(var i=0;i<ids.length;i++){
		var doc = document.getElementById(ids[i]);
		if(doc != null){
			doc.style.display = "none";
		}
	}
	if(id=="singleplayer" || id=="multiplayer"){
		$("#navigator").fadeOut(100);
	}else{
		$("#navigator").fadeIn(100);
	}
	$("#"+id).fadeIn(100);
}