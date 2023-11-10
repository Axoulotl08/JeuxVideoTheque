function addTimeSeparator(){
    console.log('insideJS');
    var text = document.getElementById("game_update_gameTimeText").value;
    console.log(text);
    if(text.length === 2){
        document.getElementById("game_update_gameTimeText").value = text + ':';
    }
}

function addTimeSeparatorSession(){
    console.log('insideJS');
    var text = document.getElementById("session_sessionTimeText").value;
    console.log(text);
    if(text.length === 2){
        document.getElementById("session_sessionTimeText").value = text + ':';
    }
}
