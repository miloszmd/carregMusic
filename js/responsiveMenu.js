var threeLineButton = document.getElementById("threeLineButton");

<<<<<<< HEAD
var container = document.getElementById("container");
=======
var container = document.getElementById("firstMenuContainer");
>>>>>>> 9846f48fc60eb3a0f793e780af31124f41c33460


threeLineButton.addEventListener("click", menuButtonClicked, false);

function menuButtonClicked(e){
    e.preventDefault(); //this stops an event that could occur, in this case the link from being used
    console.log("working here")
    
    if(container.classList.contains("showMenu")){
       container.classList.remove("showMenu");
          console.log("if");
    }

    else {
        container.classList.add("showMenu");
        console.log("else");
    }

}