
  <header class="bg-primary py-3" id="home">
    <div class="container">
      <div class="row">
        <div class="col-12 text-center">
          <img src="assets/images/aai_logo.png" alt="Airport Authority of India" class="img-fluid" style="max-width: 200px;">
          <h1 class="text-light mt-3">Visitor Entry Pass System</h1>
        </div>
      </div>
    </div>
  </header>

  <!-- Introduction Section -->
  <section class="py-5">
    <div class="container">
      <div class="row">
        <div class="col-12 text-center">
          <h2>Welcome to the Visitor Entry Pass System of Airport Authority of India</h2>
          <p class="lead">Our system facilitates the smooth and secure entry of visitors into Airport Authority of India premises.</p>
        </div>
      </div>
    </div>
  </section>

  <!-- Key Features Section -->
  <section class="bg-light py-5" id="features">
    <div class="container">
      <div class="row">
        <div class="col-12 text-center">
          <h2>Key Features</h2>
          <ul class="list-unstyled">
            <li>Streamlined visitor registration process.</li>
            <li>Secure access control.</li>
            <li>Real-time monitoring of visitor movements.</li>
            <li>Instant notification to hosts upon visitor arrival.</li>
          </ul>
        </div>
      </div>
    </div>
  </section>

  <!-- How It Works Section -->
  <section class="py-5" id="working">
    <div class="container">
      <div class="row">
        <div class="col-12 text-center">
          <h2>How It Works</h2>
          <p class="lead">Follow these simple steps to obtain your entry pass:</p>
        </div>
      </div>
      <div class="row">
        <div class="col-md-3 text-center">
          <h3>1. Register/Login</h3>
          <p>Visitors need to register/login to the system.</p>
        </div>
        <div class="col-md-3 text-center">
          <h3>2. Fill Form</h3>
          <p>Visitors fill out necessary details and purpose of visit.</p>
        </div>
        <div class="col-md-3 text-center">
          <h3>3. Approval</h3>
          <p>Pass requests are reviewed and approved by AAI authorities.</p>
        </div>
        <div class="col-md-3 text-center">
          <h3>4. Pass Issuance</h3>
          <p>Approved visitors receive their entry passes via email or print.</p>
        </div>
      </div>
    </div>
  </section>

  <!-- Benefits Section -->
  <section class="bg-light py-5" id="benifits">
    <div class="container">
      <div class="row">
        <div class="col-12 text-center">
          <h2>Benefits</h2>
          <ul class="list-unstyled">
            <li>Enhanced security measures.</li>
            <li>Reduced waiting time for visitors.</li>
            <li>Efficient management of visitor data.</li>
          </ul>
        </div>
      </div>
    </div>
  </section>

  <!-- About Us Section -->
  <section class="py-5" id="about">
    <div class="container">
      <div class="row">
        <div class="col-12 text-center">
          <h2>About Us</h2>
          <p class="lead">Brief overview of Airport Authority of India and its commitment to security and efficiency.</p>
        </div>
      </div>
    </div>
  </section>

  <!-- Contact Us Section -->
  <section class="bg-light py-5" id="contact">
    <div class="container mt-5 p-4 mb-3" id="contact" data-aos="fade-up-right" data-aos-easing="ease-out-cubic" data-aos-duration="2000">
        <h2>Contact here..!</h2>
        <p>Visit us..!</p>
        <div class="d-flex align-content-center flex-wrap">

            <div class="container">
                <div class="row">
                    <div class="col-sm">
                        <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3781.882330467227!2d73.90634187519314!3d18.579342982525617!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3bc2c134e6c4ff8f%3A0xa810745cf9310798!2sPune%20International%20Airport!5e0!3m2!1sen!2sin!4v1714864661971!5m2!1sen!2sin" width="500" height="400" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                    </div>
                    <div class="col-sm p-5">
                        <a href="https://goo.gl/maps/MQLAoAQH77FDb2ET6" target="__blank" class="text-decoration-none"><i class="fa fa-map-marker"></i> New Airport Rd, Pune International Airport Area, Lohegaon, Pune, Maharashtra 411032</a>
                        <hr>
                        <a href="tel: +91 9067404012" target="__blank" class="text-decoration-none"><i class="fa fa-phone"></i> +91 9067404012</a>
                        <hr>
                        <a href="mailto: contact@nationalmuseum.com" target="__blank" class="text-decoration-none"><i class="fa fa-envelope"></i> contact@aai.com</a>
                        <hr>
                        <a href="" target="__blank" class="text-decoration-none"><i class="fa fa-globe"></i> www.aai.com</a>
                        <hr>
                        <a href="" target="__blank" title="plus code" class="text-decoration-none"><i class="fa fa-plus"></i> Pune, Maharashtra</a>
                    </div>
                </div>
            </div>            
            <form onsubmit="return checkCaptcha();" id="contact_form" method="post" class="form p-5 w-100" data-aos="fade-up-left" data-aos-easing="ease-in-cubic" data-aos-duration="1500">
                <h4>Contact Form</h4>
                <div class="mb-3 form-floating">
                    <input type="text" name="name" class="form-control" required>
                    <label for="name">Full Name</label>
                </div>
                <div class="mb-3 form-floating">
                    <input type="email" name="email" class="form-control" required>
                    <label for="email">Email</label>
                </div>
                <div class="mb-3 form-floating">
                    <input type="text" name="subject" class="form-control" required>            
                    <label for="subject">Subject</label>
                </div>
                <?php $captcha = "NM".rand(1000,9000); ?>
                <div class="mb-3 form-floating">
                    <input type="text" id="captcha" class="form-control" required>            
                    <label for="captcha"><p class="fw-bold">Enter : <?php echo $captcha; ?></p></label>
                </div>
                <div class="mb-3 form-floating">
                    <textarea name="message" class="form-control" required></textarea>
                    <label for="message">Message</label>
                </div>
                <button name="contact" type="submit" class="btn btn-outline-primary">Contact</button>
            </form>
        </div>
        <?php
        if(isset($_POST['contact'])){
            $name = $_POST['name'];
            $email = $_POST['email'];
            $subject = $_POST['subject'];
            $message = $_POST['message'];
            $body = "Details Of the Contact Form <br><br> Name : ".$name. "<br>Email : ".$email."<br>Subject : ".$subject."<br>Message : ".$message;

            $subject = $name." Contacted You from AAI Visitor Pass System";
            
            $role = "contact_form";
            require "sendEmail.php";
        }
        ?>
        <script>
            function checkCaptcha(){
                if(document.getElementById("captcha").value == "<?php echo $captcha; ?>"){
                    var contact_form = document.getElementById('contact_form');
                    contact_form.action = "index.php";
                    contact_form.submit();
                    return true;
                }else{                        
                    alert("Invalid Captcha Entered..");
                    return false;
                }
            }
        </script>
    </div>
  </section>

  <!-- Footer Section -->
  <footer class="bg-dark text-light text-center py-3">
    <div class="container">
      <div class="row">
        <div class="col-12">
          <p>&copy; 2024 Airport Authority of India. All rights reserved.</p>
        </div>
      </div>
    </div>
  </footer>