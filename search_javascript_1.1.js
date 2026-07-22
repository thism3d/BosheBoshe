var waitingforsearchh2 = document.getElementById("waitingforsearchh2");
var search_result_goes_here_conatiner = document.getElementById("search_result_goes_here_conatiner");
var every_product_category = document.getElementById("every_product_category");


var search_image = document.getElementById("search_image");




var my_real_search_box = document.getElementById("my_real_search_box");
var value_of_my_real_search_box = my_real_search_box.value;

function searchbuttonclicked(){
    value_of_my_real_search_box = my_real_search_box.value;
    value_of_my_real_search_box = value_of_my_real_search_box.replace(/[^a-zA-Z ]/g, "");
    value_of_my_real_search_box = value_of_my_real_search_box.trim();
    
    if(value_of_my_real_search_box.length>2){
       window.location.replace("https://bosheboshe.com/search?s=" + value_of_my_real_search_box);
    }
    
}


function myCustomKeyPress( str , e){
    
    
    
  str = str.replace(/[^a-zA-Z ]/g, "");
  str = str.trim();
    
var keynum;


  if(window.event) { // IE
    keynum = e.keyCode;
  } else if(e.which){ // Netscape/Firefox/Opera
    keynum = e.which;
  }

  if(keynum == 13){

    if (str == "" || str.length<3) {
        return;
    } else {
        window.location.replace("https://bosheboshe.com/search?s=" + str);
    }
  }else{
      if(str.length>2){
          sendRequestToServer(str);
          waitingforsearchh2 = document.getElementById("waitingforsearchh2");
              if(waitingforsearchh2){
                 waitingforsearchh2.innerHTML = "Searching . . . ";
                  search_image.src = "images/undraw_file_searching_duff.svg";
//                  location.replace("localhost/");
              }
          
          
       // Changing the Borwser Location
          addState();
          function addState() { 
                let stateObj = { id: "100" }; 

                window.history.pushState(stateObj, 
                         str + " - Serach BosheBoshe", "/search?s=" + str); 
            } 

          
       }else{
           waitingforsearchh2 = document.getElementById("waitingforsearchh2");
           if(waitingforsearchh2){
             waitingforsearchh2.innerHTML = "Waiting for search . . .";
               search_image.src = "images/undraw_file_searching_duff.svg";
//              location.replace("localhost/");
               
           }
           
           
           // Changing the Borwser Location
//          addState();
//          function addState() { 
//                let stateObj = { id: "100" }; 
//
//                window.history.pushState(stateObj, 
//                         "Serach BosheBoshe", "/bosheboshe/search"); 
//            } 
          
       }
  }

}

//var checkifuserwaited = 0;

var timerSetout;

function myFunction(str) {
  timerSetout = setTimeout(function(){ 
      
//      checkifuserwaited = 1; 
//        console.log(str);
      
      loadDoc(str);
  }, 1000);
}




function sendRequestToServer( str ){
    clearTimeout(timerSetout);
    myFunction(str);
//        console.log(str);
        
}






function loadDoc(str) {
  var xhttp = new XMLHttpRequest();
  xhttp.onreadystatechange = function() {
    if (this.readyState == 4 && this.status == 200) {

      var requestResponse = this.responseText;
//      console.log(requestResponse);
        
        
        search_result_goes_here_conatiner = document.getElementById("search_result_goes_here_conatiner");
        every_product_category = document.getElementById("every_product_category");
        if(search_result_goes_here_conatiner){
           search_result_goes_here_conatiner.innerHTML = requestResponse;
       }else{
           if(every_product_category){
               every_product_category.innerHTML = '<div id="search_result_goes_here_conatiner">' + requestResponse + '</div>';
               
            
              }
       }
        
        
        //Lazy Load After Every Response
        reCallAbleLazyLoad();
        
        
      


    }else{
      
    }
  };
  xhttp.open("POST", "search_ajax_1.1.php", true);
  xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
  xhttp.send("searchquery="+ str);
}