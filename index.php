<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>NBSC Online Admission System</title>
  <meta content="" name="description">
  <meta content="" name="keywords">

  <!-- Favicons -->
  <link href="components/img/apple-touch-icon.png" rel="apple-touch-icon">

  <!-- Google Fonts -->
  <link href="https://fonts.gstatic.com" rel="preconnect">
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Nunito:300,300i,400,400i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">

  <!-- Vendor CSS Files -->
  <link href="components/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="components/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
  <link href="components/vendor/boxicons/css/boxicons.min.css" rel="stylesheet">
  <link href="components/vendor/quill/quill.snow.css" rel="stylesheet">
  <link href="components/vendor/quill/quill.bubble.css" rel="stylesheet">
  <link href="components/vendor/remixicon/remixicon.css" rel="stylesheet">
  <link href="components/vendor/simple-datatables/style.css" rel="stylesheet">

  <!-- Template Main CSS File -->
  <link href="components/css/style.css" rel="stylesheet">

  <!-- =======================================================
  * Template Name: NiceAdmin
  * Updated: Sep 18 2023 with Bootstrap v5.3.2
  * Template URL: https://bootstrapmade.com/nice-admin-bootstrap-admin-html-template/
  * Author: BootstrapMade.com
  * License: https://bootstrapmade.com/license/
  ======================================================== -->
  <?php
        
        include_once('functions/functions.php');
        session_start();
  ?>
</head>

<body>

  <main>

    

      <section class="section register min-vh-100 d-flex flex-column align-items-center justify-content-center py-4">
        <div class="container">
          <div class="row justify-content-center">
            <div class="col-lg-4 col-md-6 d-flex flex-column align-items-center justify-content-center">

              <div class="d-flex justify-content-center py-4">
                
              <a href="index.html" class="logo d-flex align-items-center w-auto">
                 
                </a>
              </div><!-- End Logo -->

              <div class="card mb-3">

                <div class="card-body">

                  <div class="pt-4 pb-2">
                    <h5 class="card-title text-center pb-0 fs-4">NBSC Online Admission System 2025</h5>
                    <p class="text-center small">Enter your username & password to login</p>
                  </div>

                  

                  <form class="row g-3 needs-validation" action="index.php" method="POST" novalidate >

                    <div class="col-12">
                      <label for="" class="form-label">Username</label>
                      <div class="input-group has-validation">
                        
                        <input type="text" name="username" class="form-control" id="yourUsername" required>
                        <div class="invalid-feedback">Please enter your username.</div>
                      </div>
                    </div>

                    <div class="col-12">
                      <label for="yourPassword" class="form-label">Password</label>
                      <input type="password" name="password" class="form-control" id="yourPassword" required>
                      <div class="invalid-feedback">Please enter your password!</div>
                    </div>

                    
                    <div class="col-12">
                      <button class="btn btn-primary w-100" type="submit" name="submit">Login</button>
                    </div>
                   
                  </form>

                </div>
              </div>

              <div class="credits">
                <!-- All the links in the footer should remain intact. -->
                <!-- You can delete the links only if you purchased the pro version. -->
                <!-- Licensing information: https://bootstrapmade.com/license/ -->
                <!-- Purchase the pro version with working PHP/AJAX contact form: https://bootstrapmade.com/nice-admin-bootstrap-admin-html-template/ -->
                Designed by <a href="https://github.com/K3NT0Z4K1">K3NT0Z4K1 & Lloydy</a>
              </div>

            </div>
          </div>
        </div>

      </section>

    </div>
  </main><!-- End #main -->

  <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

  <!-- Vendor JS Files -->
  <script src="components/vendor/apexcharts/apexcharts.min.js"></script>
  <script src="components/vendor/bootstrap/javascript/bootstrap.bundle.min.js"></script>
  <script src="components/vendor/chart.js/chart.umd.js"></script>
  <script src="components/vendor/echarts/echarts.min.js"></script>
  <script src="components/vendor/quill/quill.min.js"></script>
  <script src="components/vendor/simple-datatables/simple-datatables.js"></script>
  <script src="components/vendor/tinymce/tinymce.min.js"></script>
  <script src="components/vendor/php-email-form/validate.js"></script>

  <!-- Template Main JS File -->
  <script src="components/javascript/main.js"></script>

</body>

</html>

<?php
    if(isset($_POST["submit"])){
            // echo"<script> alert('dashboard.php triggered');</script>";
            $username = $_POST['username'];
            $password = $_POST['password'];
            $sql = "select * from tbl_admin where username = '$username' and password = '$password' ";
           // echo"<script> alert('".$sql."');</script>";

            $res = $mycon->query($sql);
            if($res->num_rows > 0){
                while($row = $res->fetch_array()){
                    $_SESSION['user'] = $row['User_value'];
                    $user = $_SESSION['user'];

                    echo"<script> alert('Welcome ".$user."');</script>";
                    echo"User: ".$_SESSION['user'];
                    redirect("public/dashboard.php");
                }
            }else{
                echo"<script> alert('Unknown username and password');</script>";
            }
    }


?>