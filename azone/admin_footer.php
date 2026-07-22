          <div id="last_footer">
            <div id="inside_last_footer">
              FIND US ON
              <span><a href=""><i class="fa fa-facebook-square"></i></a></span>
              <span><a href=""><i class="fa fa-instagram"></i></a></span>
              <span><a href=""><i class="fa fa-twitter-square"></i></a></span>
            </div>



            <div id="four_div_in_footer" class="clearfix">

              <div id="first_of_fourdiv">
                <img src="bosheboshefinal.png" alt="BosheBoshe Full Image">
                <!-- <img src="bosheboshefinal.png" alt="BosheBoshe Full Image"> -->
                <p>Welcome to BosheBoshe.com<br>An online shopping market in Dinajpur, Bangladesh</p>
              </div>

              <div id="second_of_fourdiv">
                <p>HELP</p>
                <span>
                <a class="hiddenhelp">How To Order</a>
                <a class="hiddenhelp"  id="hidden_help2" href="../trackmyorder">Track Your Order</a>
                <a class="visiblehelp" href="../contactus">Customer Care</a>
                <a class="visiblehelp" href="../faq">FAQ</a>
                <a class="visiblehelp" href="../returnpolicy">Return Policy</a>
                </span>
              </div>


              <div id="third_of_fourdiv">
                  <p>ABOUT US</p>
                  <span>
                  <a href="../privacypolicy">Privacy Policy</a>
                  <a href="../termsofuse">Terms of Use</a>
                  </span>
              </div>

              <div id="fourth_of_fourdiv">
                <a href="tel:+8801884084849"><i class="fa fa-phone" aria-hidden="true">
                  </i> CALL US<br>
                  <span id="phone_number_contact">01884084849</span><br>
                </a>

                <a href="mailto:enquiries@bosheboshe.com?Subject=MemberHelp" target="_top">
                  <i class="fa fa-envelope"></i> MAIL US<br>
                  <span>enquiries@bosheboshe.com</span>
                </a>
              </div>



            </div>

            <div id="we_accept_cards">
              <span>PAY EASILY AND SECURELY</span>
              <!-- PAY EASILY AND SECURELY -->
              <span><img src="cashondelivery2.jpg" alt="Cash On Delivery"></span>
              <span><img src="mastercard.jpg" alt="Master Card"></span>
              <span><img src="visacard.jpg" alt="Visa Card"></span>
              <span><img src="bkash3.jpg" alt="bKash"></span>
              <!-- <img src="sslcommerz" alt="Verified By SSL Commerz"> -->
            </div>




            <div id="copyright_issue">
              <br>
              &copy; Copyright BosheBoshe 2020
            </div>




            <div>
              <br>
              <br>
              <br>
              <br>
            </div>
          </div>




        </div>                       <!-- Main Container Ends Here -->





        <!-- Script For Lazy Load -->
          <script>

          !function(window){
            var $q = function(q, res){
                  if (document.querySelectorAll) {
                    res = document.querySelectorAll(q);
                  } else {
                    var d=document
                      , a=d.styleSheets[0] || d.createStyleSheet();
                    a.addRule(q,'f:b');
                    for(var l=d.all,b=0,c=[],f=l.length;b<f;b++)
                      l[b].currentStyle.f && c.push(l[b]);

                    a.removeRule(0);
                    res = c;
                  }
                  return res;
                }
              , addEventListener = function(evt, fn){
                  window.addEventListener
                    ? this.addEventListener(evt, fn, false)
                    : (window.attachEvent)
                      ? this.attachEvent('on' + evt, fn)
                      : this['on' + evt] = fn;
                }
              , _has = function(obj, key) {
                  return Object.prototype.hasOwnProperty.call(obj, key);
                }
              ;

            function loadImage (el, fn) {
              var img = new Image()
                , src = el.getAttribute('data-src');
              img.onload = function() {
                if (!! el.parent)
                  el.parent.replaceChild(img, el)
                else
                  el.src = src;

                fn? fn() : null;
              }
              img.src = src;
            }

            function elementInViewport(el) {
              var rect = el.getBoundingClientRect()

              return (
                 rect.top    >= 0
              && rect.left   >= 0
              && rect.top <= (window.innerHeight || document.documentElement.clientHeight)
              )
            }

              var images = new Array()
                , query = $q('img.lazy')
                , processScroll = function(){
                    for (var i = 0; i < images.length; i++) {
                      if (elementInViewport(images[i])) {
                        loadImage(images[i], function () {
                          images.splice(i, i);
                        });
                      }
                    };
                  }
                ;
              // Array.prototype.slice.call is not callable under our lovely IE8
              for (var i = 0; i < query.length; i++) {
                images.push(query[i]);
              };

              processScroll();
              addEventListener('scroll',processScroll);

          }(this);

          </script>
