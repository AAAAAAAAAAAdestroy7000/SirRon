var slides=document.getElementsByClassName("slide");
var ii=0;

setInterval(function(){
if (slides.length>0){
slides[ii].classList.remove("active");
ii=ii+1;
if (ii>=slides.length){ii=0;}
slides[ii].classList.add("active");
}
},6000);

var currentModal=null;
var bubblesElement=null;
var numberElement=null;
var reviewsElement=null;
var poweredElement=null;

function openModalInfo(name,localRating,modalId){
currentModal=document.getElementById(modalId);
if (currentModal.getAttribute("data-loaded")=="1"){
}
else{
currentModal.setAttribute("data-loaded","1");
bubblesElement=currentModal.querySelector(".ta-bubbles");
numberElement=currentModal.querySelector(".ta-number");
reviewsElement=currentModal.querySelector(".ta-reviews");
poweredElement=currentModal.querySelector(".ta-powered");
if (numberElement!=null){numberElement.textContent="Loading...";}
if (reviewsElement!=null){reviewsElement.textContent="";}
if (poweredElement!=null){poweredElement.innerHTML="";}
var request=new XMLHttpRequest();
request.open("GET","../API/ta_lookup.php?name="+encodeURIComponent(name));
request.onload=function(){
var data=JSON.parse(request.responseText);
var rating=localRating;
if (data!=null){
if (data.rating!=null){
rating=parseFloat(data.rating);
}
}
renderRating(rating);
if (numberElement!=null){numberElement.textContent=rating.toFixed(1)+"/5.0";}
if (reviewsElement!=null){
if (data!=null){
if (data.num_reviews!=""){reviewsElement.textContent="("+data.num_reviews+" reviews)";}
else{reviewsElement.textContent="";}
}
}
if (poweredElement!=null){poweredElement.innerHTML="<small class='text-muted'>Powered by TripAdvisor</small>";}
};
request.send();
}
}

function renderRating(value){
if (bubblesElement!=null){
var html="";
var j=1;
while (j<=5){
if (j<=value){html=html+"<span class='bubble filled'>●</span>";}
else{html=html+"<span class='bubble empty'>●</span>";}
j=j+1;
}
html=html+" <span class='ta-number' style='color:#4a2a8a;'>"+value.toFixed(1)+"/5.0</span>";
bubblesElement.innerHTML=html;
}
}
