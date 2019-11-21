<!DOCTYPE html>
<html lang="en">
<head>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" />
  <link rel="stylesheet" href="https://raw.githubusercontent.com/daneden/animate.css/master/animate.css" />
  <link href="https://fonts.googleapis.com/css?family=Roboto+Condensed:300,400,700" rel="stylesheet"> 
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
  <title>MSP Mall</title> 
<style>
/* LiveBox Start */
.livebox-container {
  width: 99%;
  height: 130px;
  position: absolute;
}
.livebox-container#sol{ border-style: solid; }

#sol{
  position:relative;
  color: white;
}
#sol::before{
  border: 2px dashed #fff;
  content: '';
  width: 98.5%;
  position: absolute;
  right: 50%;
  top: 50%;
  height: 94%;
  transform: translate(50%,-50%);
}
#sol::after{
  border: 1px dotted #fff;
  content: '';
  width: 97%;
  position: absolute;
  right: 50%;
  top: 50%;
  height: 85%;
  transform: translate(50%,-50%);
}

/* slideshow */

ul {
    display: flex;
    flex: 1;
    min-height: 150px;
    width: 100%;
    align-items: center;
    justify-content: center;
    text-transform: uppercase;
    list-style: none;
    -webkit-perspective: 700;
    perspective: 700;
    padding-bottom: 20px;
    font-family: "Impact", Charcoal, sans-serif;
}

li {
    position: absolute;
    border-width: 2px;
    border-color: #fff;
    transform-origin: 0 100%;
}

li.current {
    transition: all 0.3s ease-out;
    opacity: 1.0;
}

li.in {
    opacity: 0.0;
    transform: rotate3d(1, 0, 0, -90deg);
}

li.out {
    transition: all 0.3s ease-out;
    opacity: 0.0;
    transform: rotate3d(1, 0, 0, 90deg);
}

.jumbotron {
  /* background-image: url('assets/img/bg-luckydraw1.jpg');
  background-size:cover;
  background-attachment: fixed; */
  top:0; 
  left:0;
  width: 100%;
  height:100%;
  padding: 4rem 9rem;
  padding-bottom: 100px;
  margin:0;
}

.draw {
  color: white;
  font-family: "Impact", Charcoal, sans-serif;
  font-size: 400%;
}

.coba {
  background-image: url('assets/img/badges-putih.png');
  background-repeat: round;
  white-space: nowrap;
  font-size: 50px;
  text-align: center;
  width: 250px;
}
.custom {
  font-size: 1.90rem;
  color: #f88616;
  font-family: "Impact", Charcoal, sans-serif;
  width: inherit;
}

#sandbox-data {
  width: 
  /* width: 1340px;
  display: flex; 
  grid-gap: 5px; 
  grid-template-columns: repeat(auto-fit, 100px); 
  grid-template-rows: repeat(2, 100px); */
}

.modal-dialog {
  max-width: 57%;
  margin: 15.75rem auto;  
}

.modal-content {
  color: #fff;
  /* background: linear-gradient(40deg,#ffd86f,#fc6262) !important; */
  background: linear-gradient(40deg,#ffd86f,#fc6262) !important;
  border: 0;
  text-align: center;
  border-radius: 1.3rem;
  width: 99%;
}

h2 {
  font-size: 3rem;
}

/* Winner */
@-webkit-keyframes bounceInUp {
 from,
 60%,
 75%,
 90%,
 to {
   -webkit-animation-timing-function: cubic-bezier(0.215, 0.61, 0.355, 1);
   animation-timing-function: cubic-bezier(0.215, 0.61, 0.355, 1);
 }
 from {
   opacity: 0;
   -webkit-transform: translate3d(0, 3000px, 0);
   transform: translate3d(0, 3000px, 0);
 }
 60% {
   opacity: 1;
   -webkit-transform: translate3d(0, -20px, 0);
   transform: translate3d(0, -20px, 0);
 }
 75% {
   -webkit-transform: translate3d(0, 10px, 0);
   transform: translate3d(0, 10px, 0);
 }
 90% {
   -webkit-transform: translate3d(0, -5px, 0);
   transform: translate3d(0, -5px, 0);
 }
 to {
   -webkit-transform: translate3d(0, 0, 0);
   transform: translate3d(0, 0, 0);
 }
}
@keyframes  bounceInUp {
 from,
 60%,
 75%,
 90%,
 to {
   -webkit-animation-timing-function: cubic-bezier(0.215, 0.61, 0.355, 1);
   animation-timing-function: cubic-bezier(0.215, 0.61, 0.355, 1);
 }
 from {
   opacity: 0;
   -webkit-transform: translate3d(0, 3000px, 0);
   transform: translate3d(0, 3000px, 0);
 }
 60% {
   opacity: 1;
   -webkit-transform: translate3d(0, -20px, 0);
   transform: translate3d(0, -20px, 0);
 }
 75% {
   -webkit-transform: translate3d(0, 10px, 0);
   transform: translate3d(0, 10px, 0);
 }
 90% {
   -webkit-transform: translate3d(0, -5px, 0);
   transform: translate3d(0, -5px, 0);
 }
 to {
   -webkit-transform: translate3d(0, 0, 0);
   transform: translate3d(0, 0, 0);
 }
}
.bounceInUp {
 -webkit-animation-name: bounceInUp;
 animation-name: bounceInUp;
}

.animated {
  -webkit-animation-duration: 1s;
  animation-duration: 1s;
  -webkit-animation-fill-mode: both;
  animation-fill-mode: both;
}

/* .kiri {
  image: url('assets/img/logo-city-fm.png');
} */
body{
  position:relative;
}
body::before{
  position:fixed;
  top:0;
  right:0;
  width:100%;
  height:100%;
  content:'';
  background-image: url('assets/img/bg-luckydraw1.jpg');
  background-size:cover;
  background-attachment: fixed;
}
body::after{
  top:0;
  right:0;
  pointer-events:none;
  content:'';
  position:fixed;
  width:100%;
  z-index:9999;
  height:100%;
  background-color:rgba(67,67,67,.35)
}

body .fixedContainer{
  outline: 1px solid red;
  position:fixed;
  top:0;
  right: 0;
  width: 100%;
  height: 100%;
  pointer-events: none;
}

#mainTitle {
  transform: translateY(-150%);
  text-align: center;
  font-family: "Roboto Condensed", Geneva, Tahoma, sans-serif;
  font-size: 3em;
  padding-top: 1%;
  color: #fff;
  font-weight: bolder;
  transition:transform 3s ease 1s
}

@keyframes  scaleUp{
  0%{
    transform:scale(1)
  }
  33%{
    transform:scale(1.15)
  }
  66%{
    transform:scale(.85)
  }
  100%{
    transform: scale(1);
  }
}

#winnerModalTitle{
  width: 100%;
  font-family:"Roboto Condensed";
  font-weight: 700;
  text-transform:uppercase;
}
#winnerModalContent h1{
  font-size: 5em!important;
}

#modalCover{
  top: 0;
  right: 0;
  display:none;
  width: 100%;
  height: 100%;
  position: fixed;
  background-color: rgba(0,0,0,.75)
}
#modalCover.shown{
  display:block
}
</style>

</head>
<body>
  <div class="jumbotron">
    <img src="assets/img/logo-city-fm.png" width="100px" height="100px" style="position:absolute;top:3%;left:3%" />
    <img src="assets/img/logo-msp-mall.png" width="100px" height="100px" style="position:absolute;top:3%;right:3%; width:auto;" />
    
    <div class="row">
      <div id="mainTitle" class="col-sm-12">PENGUNDIAN DRAW BOX <br> KONSER SHAWN MENDES DI KUALA LUMPUR</div>
    </div>
  
  
  <div class="row">
    <div class="container" style="padding-top:2%; padding-bottom:0">
    <div class="col-2 col-sm-2"></div> 
      <div class="col-md-12">
        <div class="livebox-container" id="sol">
              <ul id='livebox-slideshow'>
              </ul>
          </div>
        </div>
        <audio id="mySound">
          <source src="/assets/sound/drumroll.mp3" type="audio/mpeg">
        </audio>
        <audio id="popper">
          <source src="/assets/sound/winner-sound.mp3" type="audio/mpeg">
      </audio>
    <div class="col-2 col-sm-2"></div> 
    </div>
    </div>

    <div class="row">
      <div class="col-1 col-sm-1"></div> 
      <div class="col-10">
        <div class="container my-5" style="max-width: 1350px;">
            <div class="sandbox-container">
                <div id='sandbox-data' class="d-flex align-content-center justify-content-center flex-wrap">
                  <div class="d-flex justify-content-around border border-secondary rounded m-1">
                  </div>
                </div>
            </div>
        </div>
      </div>
       <div class="col-1 col-sm-1"></div> 
    </div>

    <div class="row">
      <div class="col-2 col-lg-2"></div> 
      <div class="col-8">
        <button id='start' class="btn btn-outline-warning btn-block" style="border-radius: 10em; height: 170%;"> START </button>
        <button id='stop' class="btn btn-outline-warning btn-block" style="display: none; border-radius: 10em; height:170%"> STOP </button>
      </div>
      
      <div class="col-2 col-lg-2"></div> 
    </div>


  </div>
  <div class=fixedContainer>
      <img src="assets/img/shawn-kiri.png" width="100px" height="100px" style="position:absolute;bottom:0%;left: 0%;width: auto;height: 45%;" />
      <img src="assets/img/shawn-kanan.png" width="100px" height="100px" style="position:absolute;bottom:0%;right: 0%;width: auto;height: 45%;" />
    </div>

    <div id=modalCover></div>
    <div class="modal" tabindex="-1" role="dialog" id="winnerModal">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 id="winnerModalTitle" class="modal-title"></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="return hideWinnerModal()">
                        <span aria-hidden="true">&times;</span>
                      </button>
                    </div>
                <div class="modal-body">
                    <div class="d-flex flex-column flex-1 align-content-center justify-content-center">
                        <h6 class="text-center"> </h6>
                        <div id="winnerModalContent" class="text-center"></div>
                        <img src="https://loading.io/assets/img/icon/showcase/Icon%20(2).svg" alt="winner">
                    </div>
                </div>
            </div>
          </div>
    </div>

<script>
let interval, currentWinner, winnerHistory, dummyData = [ ], currentIndex, counter, winnerCounter, mySound, liveboxElement, sandboxElement;

const TRANSITION = 'transitionend'

$(document).ready(() => {
  let mainTitle = $('#mainTitle')[0];
  mainTitle.addEventListener(TRANSITION, function callback() {
    mainTitle.removeEventListener(TRANSITION, callback)
    mainTitle.style.animation = 'scaleUp 2s ease 1s infinite'
  })
  mainTitle.style.transform = 'translateY(0)'

  currentWinner = {index:null,id:null,text:null,el:null}
  winnerHistory = [ ]
  currentIndex = Math.floor(Math.random() * dummyData.length);
  counter = 0;
  winnerCounter = 0;
  liveboxElement = $('#livebox-slideshow');
  sandboxElement = $('#sandbox-data');
	loadData();

  $('#start').click(function(){
  	// Check if data exist
  	if (dummyData.length > 2) {
  		// start counter from 0
  		counter = 0;
  		$('#livebox-slideshow').show()
  		interval = setInterval(slideshow, 77); // Start slideshow with interval value Recommend Value 60-200
      let sound = $('#mySound')[0]
      sound.loop = true
      sound.play();
  		// Hide and disable start button
  		$('#start').hide();
  		$('#stop').show();
  		$('#start').attr('disabled','true');
  	}
  });

  $('#stop').click(function(){
  	clearInterval(interval); // Stop slideshow to get current winner
  	// Show and enabled start button
  	let sound = $('#mySound')[0]
    sound.pause()
    $('#winnerModal').addClass('animated bounceInUp');
    $('#stop').hide();
  	$('#start').show();
  	$('#start').removeAttr('disabled');
  	// Set Current winner data to show in modal
    // let sound = $('#popper')[0]
    // sound.play()
  	winnerCounter += 1;
  	$('#winnerModalTitle').html('<h2>Pemenang #' + winnerCounter + '</h2>')
  	$('#winnerModalContent').html(currentWinner.el)
  	$('#winnerModal').show()
    
    let sound2 = $('#popper')[0]
    sound2.play()

    $('#modalCover')[0].classList.add('shown')
  	// NB: Mulai dari sini bisa diganti dengan fetch ke API ya untuk sent data pemenang saat ini ke back end
  	// Set current winner data to object
  	let currentWinnerData = {
  		winner: winnerCounter,
  		data: currentWinner
  	};
  	console.log(currentWinnerData, currentIndex)
  	winnerHistory.push(currentWinnerData) // Optional if to show winner history
    fetch('https://mymspmall.id/api/v2/lottery/update_lottery', {
        method: 'POST', // or 'PUT'
        body: JSON.stringify({ user_id:currentWinnerData.data.user_id }), // data can be `string` or {object}!
        headers:{
          'Content-Type': 'application/json'
        }
      }).then(res => res.json())
      .then(response => {
        console.log('Success:', JSON.stringify(response))
  	    dummyData.splice(currentWinner.index, 1) // Remove 1 object for reupdate dummy data
      })
      .catch(error => console.error('Error:', error))
      .finally(() => { dummyData = [ ] })
  })
})

let loadData = () => {
	// Set element to empty
	liveboxElement.empty();
	sandboxElement.empty();

	// NB: Ini bisa diganti dengan fetching datanya melalui api
	// Init element
  fetch('https://mymspmall.id/api/v2/lottery/list', { method:'GET' }).then(result => result.status === 200? result.json() : Promise.reject(result.json()))
    .then(data => {
      let result = data.items
      result.forEach((e, i) => {
        if(e.lottery_status == 1)
          delete result[i]
      })
      for(let e of result)
         if(e)
           dummyData.push(e)
      dummyData.forEach((item, index) => {
	    	//if (item.lottery_status == 0) {
	    		let selectedIndex = (currentIndex === index) ? 'current' : 'in';
	    		let liveboxItems = '<li class="'+ selectedIndex +'" data-id="'+ item.id +'" data-user="' + item.user_id + '" data-index="' + index + '"><h1 class="draw">' + item.username + '</h1></li>';
	    		let sandboxItems = '<div class="d-flex flex-1 p-2 rounded m-1 coba" data-id="'+ item.id +'" data-index="' + index + '"><h5 class="custom ">' + item.username + '</h5></div>'
	    		liveboxElement.append(liveboxItems);
	    		sandboxElement.append(sandboxItems);
	    	//}
	    });
    })
}

let slideshow = () => {
	var slides = $('#livebox-slideshow').find('li');

	currentIndex += 1;
	if (currentIndex >= slides.length) {
		currentIndex = 0;
	}

	// move any previous 'out' slide to the right side.
	$('.out').removeClass().addClass('in');
	
	// move current to left.
	$('.current').removeClass().addClass('out');
	
	// move next one to current.
	$(slides[currentIndex]).removeClass().addClass('current');
	// Set the winner data
	let winner = $(slides[currentIndex]);
	currentWinner = {
		index: winner.data('index'),
		id: winner.data('id'),
    user_id:winner.data('user'),
		text: winner[0].innerText,
		el: `<h1>${ winner[0].innerText }</h1>`,
	}
	// Counter increment
	counter += 1;
}

let hideWinnerModal = () => {
	$('#winnerModal').hide()
  $('#modalCover')[0].classList.remove('shown')

  let sound2 = $('#popper')[0]
  sound2.pause()

	// update and remove every current winner element
	liveboxElement.find('li[data-index="'+ currentWinner.index +'"]').remove()
	sandboxElement.find('div[data-index="'+ currentWinner.index +'"]').remove()
	loadData() // reload data
}
</script>
    </body>
</html>