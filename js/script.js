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
		if(id=="singleplayer" || id=="multiplayer"){
			$(document.getElementById("navigator")).fadeOut(100);
		}else{
			$(document.getElementById("navigator")).fadeIn(100);
		}
		$(document.getElementById(id)).fadeIn(100);
		//document.getElementById(id).style.display = "inline";
	}
	
}