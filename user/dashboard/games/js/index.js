var running; //we will use this variable to clear the setInterval()

var move = 0;
var position = 0;

var up = 0;
var j = 225;

var run = false;
var stop = true;
var jump = false;

function runn() {
  if (stop) {
    stop = false;
    run = true;
    animateAvatar();
  }
}

function stopp() {
  if (run) {
    run = false;
    stop = true;
    clearInterval(running);
    document.getElementById("avatar").style.backgroundPosition = '0px 0px';
  }
}

function jumpp() {
  if (run) {
    jump = true;
  }
}

function animateAvatar() {
  const  interval = 60; //100 ms of interval for the setInterval()

  running = setInterval ( () => {

    if (jump) {
      if (j < 360) {
        j = j + 45;
        up = up + 20;
        document.getElementById("avatar").style.transform = "translateY(-"+up+"px)";
      } else {
        j = 225;
        up = up - 60;
        document.getElementById("avatar").style.transform = "translateY(-"+up+"px)";
        jump = false;
      }
    }

    if (jump) {
      document.getElementById("avatar").style.backgroundPosition = '-'+j+'px 0px';
      // document.getElementById("avatar").style.transform = "translateY(-"+up+"px)";
    } else {
      document.getElementById("avatar").style.backgroundPosition = '-'+position+'px -150px';
    }

    document.getElementById("avatar").style.transform = "translateX("+move+"px)";

    if (position < 360) {
      position = position + 45;
    } else {
      position = 0;
    }

    if (move < screen.width) {
      move = move + 6;
    } else {
      move = -45;
    }

  }
  , interval ); //end of setInterval
} //end of animateScript()

document.addEventListener('keydown', (e) => {

  if (e.key == "ArrowRight") {
    runn();
  }

  if (e.key == "ArrowLeft") {
    stopp();
  }

  if (e.key == "ArrowUp") {
    jumpp();
  }
});

// document.addEventListener('keyup', (e) => {
//   stopp();
// });
