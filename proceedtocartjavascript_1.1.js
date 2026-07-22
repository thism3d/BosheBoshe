




/* If Found Product Starts Here */


var isAnyFroductFound;




function foundFoundChanger(){
    var order_cart_container = document.getElementById("order_cart_container");

    if(order_cart_container){
        
        order_cart_container.innerHTML = '<div id="empty_cart_container">' +
                                              '<h2>Empty Cart</h2>' + 
                                              '<img src="images/undraw_empty_cart_co35.svg"><br>' +
                                              '<a href="startshopping"><button id="start_shopping_afterreg">Start Shopping</button></a>'+
                                            '</div>';
        order_cart_container.style.margin = "0px";
        order_cart_container.style.padding = "0px";
        order_cart_container.style.border = "none";
        order_cart_container.style.maxWidth = "none";
    }
}


 


function isProductCookieFound() {
  isAnyFroductFound = getCookie("meAtQsrzAmKla");
  if (isAnyFroductFound == "") {
      foundFoundChanger();
  }
}

isProductCookieFound();









/* If Found Product Ends Here */





var isCouponFound;

function checkCookie() {
  isCouponFound = getCookie("tMeWAMem");
  if (isCouponFound != "") {
      console.log("Cookie Enables: " + isCouponFound);
  }
}


checkCookie();







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







var coupon_input_system = document.getElementById("coupon_input_system");
var apply_coupon_btn = document.getElementById("apply_coupon_btn");
var iscouputinputdisable;


if(isCouponFound=="t"){
    coupon_input_system.disabled = true;
    coupon_input_system.value = "Coupon (besafe)";
    apply_coupon_btn.disabled = true;
    apply_coupon_btn.innerHTML = "Applied";
}

var couponinputvalue;
function applycoupon(){
    couponinputvalue = coupon_input_system.value;
    
    iscouputinputdisable = coupon_input_system.disabled;
    
    if(couponinputvalue.length < 7 && couponinputvalue.toLocaleLowerCase() == "besafe" && iscouputinputdisable!=true){
        console.log(couponinputvalue);
        coupon_input_system.disabled = true;
        apply_coupon_btn.disabled = true;
        apply_coupon_btn.innerHTML = "Applied";
        setCookie("tMeWAMem", "t", 1);
        checkCookie();
        updateCookieSystem();
    }
}










// Values for total price
var subtotal_price_of_cart = document.getElementById("subtotal_price_of_cart");
var total_offer_of_cart = document.getElementById("total_offer_of_cart");
var total_price_of_cart = document.getElementById("total_price_of_cart");
var final_price_of_cart = 0;

var mycartitemcountspan = document.getElementById("mycartitemcountspan");










/* Data Value For AnotherSystem */

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

var main_order_form = document.getElementById("main_order_form");



function AcheckCookie() {
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
        
        var insertAfterCartBtn = '<div id="productOfDivp'+ numberIdOfDatButton +'" class="singleproduct clearfix">' +
          '<div id="nameOfProduct'+ numberIdOfDatButton  +'" class="left_side_single1">'+ retrivedId[1] +'</div>' +
          '<div class="right_side_single1"><span id="spananotherpiece'+ numberIdOfDatButton +'">'+ retrivedId[4] +'</span> piece</div>' +

          '<div class="left_side_single2"><img src="tk_icon.png"> <span id="priceOf'+ numberIdOfDatButton +'">'+ retrivedId[2] +'</span> x 1</div>' +
          '<div class="right_side_single2">' +

            '<div class="right_side_single2_inside clearfix">' +
              '<button id="delbtn'+ numberIdOfDatButton +'" onclick="deletefromcart(this.id, '+ numberIdOfDatButton +')" class="left_button_single_cart"><i class="fa fa-minus"></i></button>' +
              '<button class="middle_button_single_cart"><span id="spanpiece'+ numberIdOfDatButton +'">'+ retrivedId[4] +'</span></button>' +
              '<span style="display:none;" id="besafepriceof'+ numberIdOfDatButton +'">'+ retrivedId[3]  +'</span></button>' +
              '<button id="addbtn'+ numberIdOfDatButton +'" onclick="addtocart(this.id, '+ numberIdOfDatButton +')" class="right_button_single_cart"><i class="fa fa-plus"></i></button>' +
            '</div>' +

          '</div>' +
        '</div>';
        
        main_order_form.insertAdjacentHTML("beforeend", insertAfterCartBtn);
        
        
        
        
        
        
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


//    cart_div_container = document.getElementById("cart_div_container");
//    cart_div_container.style.display = "block";
//    items_selected = document.getElementById("items_selected");
//    total_price = document.getElementById("total_price");

    updateCookieSystem();
      
  }
}



AcheckCookie();      // Check Cookie Once




var cart_div_container;
var items_selected;
var total_price;
var total_discount_price;


var parentOfCartClikedButton;
var addToCartClickedButton;


var insertAfterClickSection;



var counterOfCookieId;
function updateCookieSystem(){
//    console.log("\n\nCounter Started");
    
    initilizerCartString = "";
    totalItemCounter = 0;
    totalPriceCounter = 0;
    total_discount_price = 0;
    
    for(counterOfCookieId = 0; counterOfCookieId<allDataCookieArray.length; counterOfCookieId++){
        var single_data_splitter = allDataCookieArray[counterOfCookieId].split("~"); 
//        totalItemCounter = totalItemCounter +  parseInt(single_data_splitter[4]);  // Item With Quantity
        totalItemCounter = totalItemCounter +  1;  // Item Without Quantity
        // Item Without Quantity
        totalPriceCounter = totalPriceCounter + parseInt(single_data_splitter[5]); 
        initilizerCartString = initilizerCartString + allDataCookieArray[counterOfCookieId];
        total_discount_price = total_discount_price + parseInt(single_data_splitter[3] * single_data_splitter[4]);
//        console.log(allDataCookieArray[counterOfCookieId]);
//        console.log(single_data_splitter);
    }
    
//    console.log(initilizerCartString);
//    console.log(allDataCookieArray);
//    console.log(allDataCookieIdPush);
    setCookie("meAtQsrzAmKla", initilizerCartString, 1) ;
//    console.log(getCookie("meAtQsrzAmKla"));
//    items_selected.innerHTML = totalItemCounter + " ITEM";
    
    
    
    mycartitemcountspan.innerHTML = totalItemCounter;
    subtotal_price_of_cart.innerHTML = totalPriceCounter + " TK";
    total_offer_of_cart.innerHTML = "- " + total_discount_price + " TK";
    
    final_price_of_cart = totalPriceCounter + 30;
    total_price_of_cart.innerHTML = final_price_of_cart + " TK";
    
    
    
    if(isCouponFound=="t"){
        if(total_discount_price<100){
            total_offer_of_cart.innerHTML = "- " + total_discount_price + " TK";
            final_price_of_cart = totalPriceCounter - total_discount_price + 30;
            total_price_of_cart.innerHTML = final_price_of_cart + " TK";
       }else{
           total_offer_of_cart.innerHTML = "- 100 TK";
           final_price_of_cart = totalPriceCounter - 70;
           total_price_of_cart.innerHTML = final_price_of_cart + " TK";
       }
        
    }else{
        total_offer_of_cart.innerHTML = " 0 TK";
        total_price_of_cart.innerHTML = final_price_of_cart + " TK";
    }
    
//    total_price.innerHTML = totalPriceCounter + " TK";
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
//        cart_div_container = document.getElementById("cart_div_container");
//        cart_div_container.style.display = "none";
        setCookie("meAtQsrzAmKla", "", -1) ;
        setCookie("tMeWAMem", "", -1);
        foundFoundChanger();
    }
    
    if(document.getElementById("productOfDiv" + deleteCheckerId)){
       productOfDivElement = document.getElementById("productOfDiv" + deleteCheckerId);
       productOfDivElement.remove(this);
    }
    
//    console.log(deleteCheckerId);
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



var clickedAnotherSpan;
var clickedAnotherSpanString;
var clickedSpanAnotherProductCount;


var clickedSpan;
var clickedSpanString;
var clickedSpanProductCount;


var returnToAddToCartButton;
var returnToAddParentElement;


var productOfDivElement;






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
    
    
    
    clickedAnotherSpan = "spananotherpiece" + idOfDelProduct;
    clickedAnotherSpanString = document.getElementById(clickedAnotherSpan);
    
    
    if(clickedSpanProductCount>1){
        clickedSpanProductCount = clickedSpanProductCount-1;
        clickedSpan.innerHTML = clickedSpanProductCount;
        clickedAnotherSpanString.innerHTML = clickedSpanProductCount;
        
        
        
    
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
        
//        returnToAddToCartButton = '<button class="add_to_cart_btn_class" id="btn'+ idOfDelProduct +'" onclick="addtocartfunction(this.id, '+ idOfDelProduct +')"><i class="fa fa-shopping-cart"></i> Add to cart</button>';
//        
//        returnToAddParentElement = deletefrombtn.parentElement.parentElement;
//    
//        deletefrombtn.parentElement.remove(this);
//        
//        returnToAddParentElement.insertAdjacentHTML("beforeend", returnToAddToCartButton);
        
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
    
    
    
    
    clickedAnotherSpan = "spananotherpiece" + idOfAddProduct;
    clickedAnotherSpanString = document.getElementById(clickedAnotherSpan);
    
    clickedAnotherSpanString.innerHTML = clickedSpanProductCount;
    
    
    
    
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








var confirmorderbtn = document.getElementById("confirm_cart_order");

var error_found_p;






function processTheAjaxHere(){
    
    confirmorderbtn = document.getElementById("confirm_cart_order");
    
    if(confirm_cart_order){
        
        error_found_p = document.getElementById("error_found_p");
        
        
        
        // Gather Customer Information
        
        var nameOfUser;
        var phoneOfUser;
        var delivryOfUser;
        var cityOfUser;
        
        
        var totalCookieOfProduct;
        
        totalCookieOfProduct = getCookie("meAtQsrzAmKla");
        
        
        
        var error_found_p = document.getElementById("error_found_p");
        
        
        var nameOfRegisteredUser = document.getElementById("nameOfRegisteredUser");
        var phoneOfRegisteredUser = document.getElementById("phoneOfRegisteredUser");
        
        
        var formfaddress;
        var formfcity;
        
        
        
        
        var errorFound = 0;
        
        
        if(nameOfRegisteredUser){
            errorFound = 0;
            console.log("RegisteredUser");
            
            nameOfRegisteredUser = document.getElementById("nameOfRegisteredUser");
            phoneOfRegisteredUser = document.getElementById("phoneOfRegisteredUser");
            
            formfaddress = document.getElementById("faddress");
            formfcity = document.getElementById("fcity");
            
            
            
            if(formfaddress.value.length > 5 && formfaddress.value.length < 150){
                if(formfcity.value.length > 5 && formfcity.value.length < 50){
                   
               }else{
                   errorFound = 1;
               }
            }else{
                errorFound = 1;
            }
            
            
            
            if(errorFound==1){
               error_found_p.innerHTML = "Fill the form correctly";
           }else{
               error_found_p.innerHTML = "";
               confirmorderbtn.disabled = true;
               nameOfUser = nameOfRegisteredUser.innerHTML;
               phoneOfUser = phoneOfRegisteredUser.innerHTML;
               delivryOfUser = formfaddress.value;
               cityOfUser = formfcity.value;
//                console.log("Name: " + nameOfUser);
//                console.log("Phone: " + phoneOfUser);
//                console.log("Address: " + delivryOfUser);
//                console.log("City: " + cityOfUser);
//                console.log(totalCookieOfProduct);
               
               
                confirmorderbtn.innerHTML = 'Processing <i class="fa fa-circle-o-notch fa-spin"></i>';
               
                if(isCouponFound!=""){
//                    console.log("CouponEnable");
                    
                    finalAjaxProcessing(nameOfUser, phoneOfUser, delivryOfUser, cityOfUser, totalCookieOfProduct, "besafe");
                }else{
//                    console.log("Coupon Disabled");
                    
                    finalAjaxProcessing(nameOfUser, phoneOfUser, delivryOfUser, cityOfUser, totalCookieOfProduct, "no");
                }
               
               
           }
        
            
            
        }else{
            console.log("Not Registered");
            
            errorFound = 0;
            
            
             var registrationformname = document.getElementById("fname").value;
             nameOfUser = registrationformname;
              var namelength = registrationformname.length;

              for(var i = 0; i< namelength; i++){
                if(!(registrationformname.charAt(i)>='a' && registrationformname.charAt(i)<='z') && !(registrationformname.charAt(i)>='A' && registrationformname.charAt(i)<='Z') && !(registrationformname.charAt(i)==' ') && !(registrationformname.charAt(i)=='.')){
                  errorFound = 1;
                }
              }


               if(errorFound==0){
                if(namelength<5 || namelength>59){
                   errorFound = 1;
                  }
                }
              

              if(errorFound==0){
                 if (registrationformname == "") {
                    errorFound = 1;
                  }
              }
              
            
            
            
            
             var registrationformphone = document.getElementById("fphone").value;
             var registrationformphonestring = registrationformphone.toString();
                phoneOfUser = registrationformphonestring;

              if (registrationformphonestring.length==11) {
                if(!(registrationformphonestring.charAt(0)=='0' && registrationformphonestring.charAt(1)=='1')){
                   errorFound = 1;
                }
              }else if (registrationformphonestring.length==10) {
                if(registrationformphonestring.charAt(0)!='1'){
                   errorFound = 1;
                }
              }


              if(registrationformphonestring.length<10){
                 errorFound = 1;
              }
            
                formfaddress = document.getElementById("faddress");
                formfcity = document.getElementById("fcity");



                if(formfaddress.value.length > 5 && formfaddress.value.length < 150){
                    if(formfcity.value.length > 5 && formfcity.value.length < 50){

                   }else{
                       errorFound = 1;
                   }
                }else{
                    errorFound = 1;
                }
            
            
                if(errorFound == 1){
                   error_found_p.innerHTML = "Fill the form correctly";
                }else{
                   error_found_p.innerHTML = "";
                   confirmorderbtn.disabled = true;
                   delivryOfUser = formfaddress.value;
                   cityOfUser = formfcity.value;
                    
                    document.getElementById("fname").disabled = true;
                    document.getElementById("fphone").disabled = true;
                    formfaddress.disabled = true;
                    formfcity.disabled = true;
                    
//                    console.log("Name: " + nameOfUser);
//                    console.log("Phone: " + phoneOfUser);
//                    console.log("Address: " + delivryOfUser);
//                    console.log("City: " + cityOfUser);
//                    console.log(totalCookieOfProduct);
//                    
                    confirmorderbtn.innerHTML = 'Processing <i class="fa fa-circle-o-notch fa-spin"></i>';
                    
                    
                    if(isCouponFound!=""){
//                        console.log("CouponEnable");

                        finalAjaxProcessing(nameOfUser, phoneOfUser, delivryOfUser, cityOfUser, totalCookieOfProduct, "besafe");
                    }else{
//                        console.log("Coupon Disabled");

                        finalAjaxProcessing(nameOfUser, phoneOfUser, delivryOfUser, cityOfUser, totalCookieOfProduct, "no");
                    }
                }
            
            
        }
        
        
        
        
        
        
        
        
        
        /* Ajax Processing Starts Here */
   
        
        
        
    }
    
}



function finalAjaxProcessing(customerName, customerPhone, cutsomerDelivery, customerCity, customerProducts, customerCoupon){
         

      var xhttp = new XMLHttpRequest();
      xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {

          var requestResponse = this.responseText;
          
            
            if(requestResponse!="N"){
                console.log("Inserted Into Server");
                
                setCookie("meAtQsrzAmKla", "", -1) ;
                setCookie("tMeWAMem", "", -1);
//                confirmorderbtn.innerHTML = 'Confirmed!';
                
                var order_cart_container = document.getElementById("order_cart_container");

                if(order_cart_container){
                    
                    order_cart_container.innerHTML = '<div id="empty_cart_container">' +
                                                          '<h2>Order No: '+ requestResponse +' Received</h2>' + 
                                                            '<p style="padding-top: 12px;">Soon someone will confirm your order!</p>' +
                                                          '<img src="images/undraw_confirmation_2uy0.svg"><br>' +
                                                          '<a href="profile"><button id="start_shopping_afterreg">Profile &amp; Pay</button></a>'+
                                                        '<a href="trackmyorder"><button id="left_of_another_shop">Track My Order</button></a>'+
                                                        '</div>';
                    order_cart_container.style.margin = "0px";
                    order_cart_container.style.padding = "0px";
                    order_cart_container.style.border = "none";
                    order_cart_container.style.maxWidth = "none";
                    
                    
                }
                
            }else{
                console.log("Not Inserted Into Server");
                
                var error_found_p = document.getElementById("error_found_p");
                
                
                
                error_found_p.innerHTML = "Server Request Error!";
                confirmorderbtn.disabled = false;
                confirmorderbtn.innerHTML = 'Confirm Again!';
            }



        }else{

        }
      };
      xhttp.open("POST", "order_ajax.php", true);
      xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
      xhttp.send("customerName="+ customerName + "&customerPhone=" + customerPhone + "&cutsomerDelivery=" + cutsomerDelivery + "&customerCity=" + customerCity + "&customerProducts=" + customerProducts + "&customerCoupon=" + customerCoupon);


    /* Ajax Processing Ends Here */
}

