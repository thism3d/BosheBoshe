//function gotoproduct() {
//    window.open("https://www.w3schools.com");
//}



var allDataCookieArray = [];
var allDataCookieIdPush = [];
var allDataCookieArrayLength;
var allDataCookieIdPushLength;

var emptyarray = [];
var anotheremptyarray = [];


var initilizerCartString = "";

var single_data_splitter;


var totalItemCounter;
var totalPriceCounter;



function checkCookie() {
  var username = getCookie("meAtQsrzAmKla");
  if (username != "") {
      
    allDataCookieArray = emptyarray;
    allDataCookieIdPush = anotheremptyarray;
      
      
    var retrivedArray = username.split(",");
      
      
    var retrivedArrayLength = retrivedArray.length;
//      retrivedArrayLength=retrivedArrayLength-1;
      
      
    var lastElementOfRetriveArray = retrivedArray[retrivedArrayLength-1].trim();
      
      
      if(lastElementOfRetriveArray==""){
        retrivedArrayLength=retrivedArrayLength-1;
      }else{
        retrivedArrayLength=retrivedArrayLength;
      }
      
      
      
      
    for(var r = 0; r<retrivedArrayLength; r++){
        
        var stringtopush = retrivedArray[r] + ",";
        allDataCookieArray.push(stringtopush);
        
        var retrivedId = retrivedArray[r].split("~");
        allDataCookieIdPush.push(retrivedId[0]);
        
        var buttonIdFounded = retrivedId[0].replace("p", "");
        var numberIdOfDatButton = buttonIdFounded;
        
        buttonIdFounded = "btn" + buttonIdFounded;
        
        
        
        
        if(document.getElementById(buttonIdFounded)){
            var buttonFoundElement = document.getElementById(buttonIdFounded);
            
            
            var parentOfButtonFound = buttonFoundElement.parentElement;

            buttonFoundElement.remove(this);




        //    console.log(parentOfCartClikedButton.innerHTML);

            var insertAfterButtonFound = '<div class="sinle_product_after_add_container clearfix">' +
                                      '<button id="delbtn'+ numberIdOfDatButton +'" onclick="deletefromcart(this.id, '+ numberIdOfDatButton +')"><i class="fa fa-minus"></i></button>' +
                                      '<button><span id="spanpiece'+ numberIdOfDatButton +'">'+ retrivedId[4] +'</span> Piece</button>' +
                                      '<button id="addbtn'+ numberIdOfDatButton +'" onclick="addtocart(this.id, '+ numberIdOfDatButton +')"><i class="fa fa-plus"></i></button>' +
                                    '</div>';


            parentOfButtonFound.insertAdjacentHTML("beforeend", insertAfterButtonFound);
    
           
       }
        
    }


    cart_div_container = document.getElementById("cart_div_container");
    cart_div_container.style.display = "block";
    items_selected = document.getElementById("items_selected");
    total_price = document.getElementById("total_price");

    updateCookieSystem();
      
  }
}



checkCookie();      // Check Cookie Once


function setCookie(cname, cvalue, exdays) {
  var d = new Date();
  d.setTime(d.getTime() + (exdays*24*60*60*1000));
  var expires = "expires="+ d.toUTCString();
  document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
}

function getCookie(cname) {
  var name = cname + "=";
  var decodedCookie = decodeURIComponent(document.cookie);
  var ca = decodedCookie.split(';');
  for(var i = 0; i <ca.length; i++) {
    var c = ca[i];
    while (c.charAt(0) == ' ') {
      c = c.substring(1);
    }
    if (c.indexOf(name) == 0) {
      return c.substring(name.length, c.length);
    }
  }
  return "";
}



var cart_div_container;
var items_selected;
var total_price;


var parentOfCartClikedButton;
var addToCartClickedButton;


var insertAfterClickSection;



var counterOfCookieId;
function updateCookieSystem(){
//    console.log("\n\nCounter Started");
    
    initilizerCartString = "";
    totalItemCounter = 0;
    totalPriceCounter = 0;
    
    for(counterOfCookieId = 0; counterOfCookieId<allDataCookieArray.length; counterOfCookieId++){
        var single_data_splitter = allDataCookieArray[counterOfCookieId].split("~"); 
//        totalItemCounter = totalItemCounter +  parseInt(single_data_splitter[4]);  // Item With Quantity
        totalItemCounter = totalItemCounter +  1;  // Item Without Quantity
        // Item Without Quantity
        totalPriceCounter = totalPriceCounter + parseInt(single_data_splitter[5]);
        initilizerCartString = initilizerCartString + allDataCookieArray[counterOfCookieId];
//        console.log(allDataCookieArray[counterOfCookieId]);
//        console.log(single_data_splitter);
    }
    
//    console.log(initilizerCartString);
//    console.log(allDataCookieArray);
//    console.log(allDataCookieIdPush);
    setCookie("meAtQsrzAmKla", initilizerCartString, 1) ;
//    console.log(getCookie("meAtQsrzAmKla"));
    items_selected.innerHTML = totalItemCounter + " ITEM";
    total_price.innerHTML = totalPriceCounter + " TK";
}




function updateTheCookie(updateCheckerId, stringForUpdate){
    
    for(counterOfCookieId = 0; counterOfCookieId<allDataCookieIdPush.length; counterOfCookieId++){
        if(allDataCookieIdPush[counterOfCookieId].includes(updateCheckerId)){
            allDataCookieArray[counterOfCookieId] = stringForUpdate;
            updateCookieSystem();
            break;
        }
    }
    
    
    
}



function deleteTheSpecificProduct(deleteCheckerId){
    for(counterOfCookieId = 0; counterOfCookieId<allDataCookieIdPush.length; counterOfCookieId++){
        if(allDataCookieIdPush[counterOfCookieId].includes(deleteCheckerId)){
            allDataCookieIdPush.splice(counterOfCookieId, 1);
            allDataCookieArray.splice(counterOfCookieId, 1);
            updateCookieSystem();
            break;
        }
    }
    
    
    if(allDataCookieArray.length==0){
        cart_div_container = document.getElementById("cart_div_container");
        cart_div_container.style.display = "none";
        setCookie("meAtQsrzAmKla", "", -1) ;
    }
}







// Information For Cookie Holder Starts Here

var singlefullproductString; 

var productClikedCode;
var productClickedName;
var productClickedOfferdPrice;
var productClickedBeSafeLessPrice;
var productClickedQuantity;


var productClikedNameElement;
var productClickedOfferdPriceElement;
var productClickedBeSafeLessPriceElement;

// Information For Cookie Holder Ends Here




var deletefrombtn;


var clickedSpan;
var clickedSpanString;
var clickedSpanProductCount;


var returnToAddToCartButton;
var returnToAddParentElement;






function addtocartfunction(buttonid, idofproduct){
    cart_div_container = document.getElementById("cart_div_container");
    cart_div_container.style.display = "block";
    items_selected = document.getElementById("items_selected");
    total_price = document.getElementById("total_price");
    
//    buttonid.parentElement.parentElement.parentElement.parentElement.remove(this);
    addToCartClickedButton = document.getElementById(buttonid);
    
    parentOfCartClikedButton = addToCartClickedButton.parentElement;
        
    addToCartClickedButton.remove(this);
    
    
    
    
//    console.log(parentOfCartClikedButton.innerHTML);
    
    insertAfterClickSection = '<div class="sinle_product_after_add_container clearfix">' +
                              '<button id="delbtn'+ idofproduct +'" onclick="deletefromcart(this.id, '+ idofproduct +')"><i class="fa fa-minus"></i></button>' +
                              '<button><span id="spanpiece'+ idofproduct +'">1</span> Piece</button>' +
                              '<button id="addbtn'+ idofproduct +'" onclick="addtocart(this.id, '+ idofproduct +')"><i class="fa fa-plus"></i></button>' +
                            '</div>';
    
    
    
    
    parentOfCartClikedButton.insertAdjacentHTML("beforeend", insertAfterClickSection);
    
    
    
    
    productClikedCode = idofproduct;
    productClikedNameElement = document.getElementById("nameOfProduct" + idofproduct);
    productClickedName = productClikedNameElement.innerHTML;
    productClickedOfferdPriceElement = document.getElementById("priceOf" + idofproduct);
    productClickedOfferdPrice = parseInt(productClickedOfferdPriceElement.innerHTML);
    productClickedBeSafeLessPriceElement = document.getElementById("besafepriceof" + idofproduct);
    productClickedBeSafeLessPrice = parseInt(productClickedBeSafeLessPriceElement.innerHTML);
    productClickedQuantity = 1;
    
    singlefullproductString = "p" + productClikedCode + "~" + productClickedName + "~" + productClickedOfferdPrice + "~" + productClickedBeSafeLessPrice + "~" + productClickedQuantity + "~" + productClickedOfferdPrice + "~,";
    
    allDataCookieIdPush.push("p"+productClikedCode);
    allDataCookieArray.push(singlefullproductString);
    
    
    
    updateCookieSystem();
    
    
}




var addtoupdatecheckid;



function deletefromcart(deletebtnid, idOfDelProduct){
    
    deletefrombtn = document.getElementById(deletebtnid);
    
    
    clickedSpanString = "spanpiece" + idOfDelProduct;
    clickedSpan = document.getElementById(clickedSpanString);
    
    clickedSpanProductCount = parseInt(clickedSpan.innerHTML);
    
    
    if(clickedSpanProductCount>1){
        clickedSpanProductCount = clickedSpanProductCount-1;
       clickedSpan.innerHTML = clickedSpanProductCount;
        
        
        
    
        productClikedCode = idOfDelProduct;
        productClikedNameElement = document.getElementById("nameOfProduct" + idOfDelProduct);
        productClickedName = productClikedNameElement.innerHTML;
        productClickedOfferdPriceElement = document.getElementById("priceOf" + idOfDelProduct);
        productClickedOfferdPrice = parseInt(productClickedOfferdPriceElement.innerHTML);
        productClickedBeSafeLessPriceElement = document.getElementById("besafepriceof" + idOfDelProduct);
        productClickedBeSafeLessPrice = parseInt(productClickedBeSafeLessPriceElement.innerHTML);
        productClickedQuantity = clickedSpanProductCount;

        singlefullproductString = "p" + productClikedCode + "~" + productClickedName + "~" + productClickedOfferdPrice + "~" + productClickedBeSafeLessPrice + "~" + productClickedQuantity + "~" + productClickedOfferdPrice*productClickedQuantity + "~,";
        
//        console.log(singlefullproductString);
        
        
        addtoupdatecheckid = "p" + productClikedCode;
        updateTheCookie(addtoupdatecheckid, singlefullproductString);
        
        
        
        
    }else{
        
        returnToAddToCartButton = '<button class="add_to_cart_btn_class" id="btn'+ idOfDelProduct +'" onclick="addtocartfunction(this.id, '+ idOfDelProduct +')"><i class="fa fa-shopping-cart"></i> Add to cart</button>';
        
        returnToAddParentElement = deletefrombtn.parentElement.parentElement;
    
        deletefrombtn.parentElement.remove(this);
        
        returnToAddParentElement.insertAdjacentHTML("beforeend", returnToAddToCartButton);
        
        deleteTheSpecificProduct("p" + idOfDelProduct);
    } 
}





var addtocartbtn;


function addtocart(addtocartbtnid, idOfAddProduct){
    addtocartbtn = document.getElementById(addtocartbtnid);
    
    
    clickedSpanString = "spanpiece" + idOfAddProduct;
    clickedSpan = document.getElementById(clickedSpanString);
    
    clickedSpanProductCount = parseInt(clickedSpan.innerHTML);
    
    clickedSpanProductCount = clickedSpanProductCount + 1;
    
    clickedSpan.innerHTML = clickedSpanProductCount;
    
    
    
    
    productClikedCode = idOfAddProduct;
    productClikedNameElement = document.getElementById("nameOfProduct" + idOfAddProduct);
    productClickedName = productClikedNameElement.innerHTML;
    productClickedOfferdPriceElement = document.getElementById("priceOf" + idOfAddProduct);
    productClickedOfferdPrice = parseInt(productClickedOfferdPriceElement.innerHTML);
    productClickedBeSafeLessPriceElement = document.getElementById("besafepriceof" + idOfAddProduct);
    productClickedBeSafeLessPrice = parseInt(productClickedBeSafeLessPriceElement.innerHTML);
    productClickedQuantity = clickedSpanProductCount;

    
    singlefullproductString = "p" + productClikedCode + "~" + productClickedName + "~" + productClickedOfferdPrice + "~" + productClickedBeSafeLessPrice + "~" + productClickedQuantity + "~" + productClickedOfferdPrice*productClickedQuantity + "~,";
    
//    console.log(singlefullproductString);
    addtoupdatecheckid = "p" + productClikedCode;
    updateTheCookie(addtoupdatecheckid, singlefullproductString);
    
    
    
}

//
//allDataCookieArray.push("Muzahid");
//allDataCookieArray.push("Islam");
//
//allDataCookieArrayLength = allDataCookieArray.length;
//
//
//var x = 0;
//
//for(; x<allDataCookieArrayLength; x++){
//    if(allDataCookieArray[x].includes("Mu")){
//       allDataCookieArray[x] = "Ha Ha Changed!";
//    }
//    console.log(allDataCookieArray[x]);
//}
//
//
