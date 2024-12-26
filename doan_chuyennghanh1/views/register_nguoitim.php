<?php
session_start();
ob_start();
include "../model/connectdb.php";
include "../model/user.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];
    $repassword = $_POST['repassword'];
    $usertype = "Candidate";
    // Kiểm tra các trường không được để trống
    if (empty($email)) {
        $error_message = "Email không được để trống";
    } elseif (empty($password)) {
        $error_message = "Mật khẩu không được để trống";
    } elseif (empty($repassword)) {
        $error_message = "Nhập lại mật khẩu không được để trống";
    } elseif ($password !== $repassword) {
        $error_message = "Mật khẩu và Nhập lại mật khẩu không khớp";
    } else {
        // Kiểm tra xem email đã tồn tại chưa
        if (checkEmailExists($email)) {
            $error_message = "Email đã tồn tại";
        } else {
            // Thêm người dùng mới
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $result = addUser_register($email, $hashed_password,$usertype);

            if ($result) {
                // Chuyển hướng tới trang đăng nhập sau khi đăng ký thành công
                header('Location: login.php');
                exit();
            } else {
                $error_message = "Có lỗi xảy ra khi đăng ký tài khoản";
            }
        }
    }
}

?>


<!DOCTYPE php>
<php lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta http-equiv="x-ua-compatible" content="ie=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>đăng ký người dùng</title>
    <link rel="manifest" href="site.webmanifest" />
    <link
      rel="shortcut icon"
      type="image/x-icon"
      href="../assets/img/favicon.ico"
    />

    <!-- css Here 12 cai -->
    <link rel="stylesheet" href="../assets/css/bootstrap.min.css" />
    <link rel="stylesheet" href="../assets/css/owl.carousel.min.css" />
    <link rel="stylesheet" href="../assets/css/flaticon.css" />
    <link rel="stylesheet" href="../assets/css/price_rangs.css" />
    <link rel="stylesheet" href="../assets/css/slicknav.css" />
    <link rel="stylesheet" href="../assets/css/animate.min.css" />
    <link rel="stylesheet" href="../assets/css/magnific-popup.css" />
    <link rel="stylesheet" href="../assets/css/fontawesome-all.min.css" />
    <link rel="stylesheet" href="../assets/css/themify-icons.css" />
    <link rel="stylesheet" href="../assets/css/slick.css" />
    <link rel="stylesheet" href="../assets/css/nice-select.css" />
    <link rel="stylesheet" href="../assets/css/style.css" />
  </head>
  <body>
    <!-- Preloader Start -->
    <!-- <div id="preloader-active">
      <div class="preloader d-flex align-items-center justify-content-center">
        <div class="preloader-inner position-relative">
          <div class="preloader-circle"></div>
          <div class="preloader-img pere-text">
            <img src="../assets/img/logo/logo.png" alt="" />
          </div>
        </div>
      </div>
    </div> -->

    <!-- Preloader Start -->
    <header>
      <!-- Header Start -->
      <div class="header-area header-transparrent">
        <div class="headder-top header-sticky">
          <div class="container">
            <div class="row align-items-center">
              <div class="col-lg-3 col-md-2">
                <!-- Logo -->
                <div class="logo">
                  <a href="../index.php"
                    ><img src="../assets/img/logo/logo.png" alt="" width ="200" height="150" />
                  </a>
                </div>
              </div>
              <div class="col-lg-9 col-md-9">
                <div class="menu-wrapper">
                  <!-- Main-menu -->
                  <div class="main-menu">
                    <nav class="d-none d-lg-block">
                      <ul id="navigation">
                        <li><a href="../index.php">Trang chủ</a></li>
                        <li><a href="job_listing.php">Tìm việc </a></li>
                        <li><a href="register_employer.php">Cho nhà tuyển dụng</a></li>
                        
                      </ul>
                    </nav>
                  </div>
                  <!-- Header-btn -->
                  <div class="header-btn d-none f-right d-lg-block">
                    <a href="register_nguoitim.php" class="btn head-btn1">Đăng Ký</a>
                    <a href="login.php" class="btn head-btn2">Đăng Nhập</a>
                  </div>

                </div>
              </div>
              <!-- Mobile Menu -->
              <div class="col-12">
                <div class="mobile_menu d-block d-lg-none"></div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <!-- Header End -->
    </header>

<main>
    <div class="container d-flex justify-content-center align-items-center">
    <form action="register_nguoitim.php" method="POST">
    <h2 class="text-center">ĐĂNG KÝ TÀI KHOẢN</h2>
    <div class="mb-3">
        <label for="email" class="form-label">Email</label>
        <input class="form-control" type="text" id="email" name="email" placeholder="Nhập Email">
        <div id="emailError" style="color: red; display: none;">Email không được để trống</div>
    </div>
    <div class="mb-3">
        <label for="password" class="form-label">Mật khẩu</label>
        <input class="form-control" type="password" id="password" name="password" placeholder="Nhập mật khẩu">
        <div id="passwordError" style="color: red; display: none;">Mật khẩu không được để trống</div>
    </div>
    <div class="mb-3">
        <label for="repassword" class="form-label">Nhập lại mật khẩu</label>
        <input class="form-control" type="password" id="repassword" name="repassword" placeholder="Nhập lại mật khẩu">
        <div id="repasswordError" style="color: red; display: none;">Mật khẩu không được để trống</div>
    </div>
    

        <div>
        <input type="submit" class="btn btn-success" style="width: 100%;" name="register" value="Đăng ký">
    </div>
    <p class="text-center mt-2">
        bạn đã có tài khoản? <a href="login.php">ĐĂNG NHẬP</a>
    </p>
      </form>
      </div>
    </div>
    </main>
    <footer>
        <!-- Footer Start-->
        <div class="footer-area footer-bg footer-padding py-2">
            <div class="container">
                <div class="row d-flex justify-content-between py-1">
                    <div class="col-xl-3 col-lg-3 col-md-4 col-sm-5">
                        <div class="single-footer-caption mb-50">
                            <div class="footer-tittle">
                                <h4>Thông tin liên hệ</h4>
                                <ul>
                                    <li>
                                    <p>Địa chỉ : ấp 5 bình chánh, tp.Hồ Chí Minh</p>
                                    </li>
                                    <li><a href="#">Phone : +84 0866377095</a></li>
                                    <li><a href="#">Email : hoainguyen.090217@gmail.com</a></li>
                                </ul>
                            </div>

                        </div>
                    </div>
                    <div class="col-xl-3 col-lg-3 col-md-4 col-sm-5">
                        <div class="single-footer-caption mb-50">
                            <div class="footer-tittle">
                                <h4>Liên kết quan trọng</h4>
                                <ul>
                                    <li><a href="#"> Xem dự án</a></li>
                                    <li><a href="#">Liên hệ với chúng tôi</a></li>
                                    <li><a href="#">Lời chứng thực</a></li>
                                    <li><a href="#">quyền sở hữu</a></li>
                                    <li><a href="#">Ủng hộ</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-lg-3 col-md-4 col-sm-5">
                        <div class="single-footer-caption mb-50">
                            <div class="footer-tittle">
                                <h4>Newsletter</h4>
                                <div class="footer-pera footer-pera2">
                                 <p>Thiên đường màu mỡ không hề ít đi theo ngày tháng.</p>
                             </div>
                             <!-- Form -->
                             <div class="footer-form" >
                                 <div id="mc_embed_signup">
                                     <form target="_blank" action="https://spondonit.us12.list-manage.com/subscribe/post?u=1462626880ade1ac87bd9c93a&amp;id=92a4423d01"
                                     method="get" class="subscribe_form relative mail_part">
                                         <input type="email" name="email" id="newsletter-form-email" placeholder="Email Address"
                                         class="placeholder hide-on-focus" onfocus="this.placeholder = ''"
                                         onblur="this.placeholder = ' Email Address '">
                                         <div class="form-icon">
                                             <button type="submit" name="submit" id="newsletter-submit"
                                             class="email_icon newsletter-submit button-contactForm"><img src="assets/img/icon/form.png" alt=""></button>
                                         </div>
                                         <div class="mt-10 info"></div>
                                     </form>
                                 </div>
                             </div>
                            </div>
                        </div>
                    </div>
                </div>
               <!--  -->
               <div class="row footer-wejed justify-content-between py-1">
                    <div class="col-xl-3 col-lg-3 col-md-4 col-sm-5">
                    <div class="footer-tittle-bottom">
                        <span>5000+</span>
                        <p>Talented Hunter</p>
                    </div>
                    </div>
                    <div class="col-xl-3 col-lg-3 col-md-4 col-sm-5">
                        <div class="footer-tittle-bottom">
                            <span>451</span>
                            <p>Talented Hunter</p>
                        </div>
                    </div>
                    <div class="col-xl-3 col-lg-3 col-md-4 col-sm-5">
                        <!-- Footer Bottom Tittle -->
                        <div class="footer-tittle-bottom">
                            <span>568</span>
                            <p>Talented Hunter</p>
                        </div>
                    </div>
               </div>
            </div>
        </div>
        <!-- footer-bottom area -->
        <div class="footer-bottom-area footer-bg">
            <div class="container">
                <div class="footer-border">
                     <div class="row d-flex justify-content-between align-items-center">

                         <div class="col-xl-2 col-lg-2">
                             <div class="footer-social f-right">
                                 <a href="#"><i class="fab fa-facebook-f"></i></a>
                                 <a href="#"><i class="fab fa-twitter"></i></a>
                                 <a href="#"><i class="fas fa-globe"></i></a>
                                 <a href="#"><i class="fab fa-behance"></i></a>
                             </div>
                         </div>
                     </div>
                </div>
            </div>
        </div>
        <!-- Footer End-->
    </footer>

    <!-- JS here -->

    <!-- All JS Custom Plugins Link Here here -->
    <script src="./assets/js/vendor/modernizr-3.5.0.min.js"></script>
    <!-- Jquery, Popper, Bootstrap -->
    <script src="./assets/js/vendor/jquery-1.12.4.min.js"></script>
    <script src="./assets/js/popper.min.js"></script>
    <script src="./assets/js/bootstrap.min.js"></script>
    <!-- Jquery Mobile Menu -->
    <script src="./assets/js/jquery.slicknav.min.js"></script>

    <!-- Jquery Slick , Owl-Carousel Plugins -->
    <script src="./assets/js/owl.carousel.min.js"></script>
    <script src="./assets/js/slick.min.js"></script>
    <script src="./assets/js/price_rangs.js"></script>

    <!-- One Page, Animated-HeadLin -->
    <script src="./assets/js/wow.min.js"></script>
    <script src="./assets/js/animated.headline.js"></script>
    <script src="./assets/js/jquery.magnific-popup.js"></script>

    <!-- Scrollup, nice-select, sticky -->
    <script src="./assets/js/jquery.scrollUp.min.js"></script>
    <script src="./assets/js/jquery.nice-select.min.js"></script>
    <script src="./assets/js/jquery.sticky.js"></script>

    <!-- contact js -->
    <script src="./assets/js/contact.js"></script>
    <script src="./assets/js/jquery.form.js"></script>
    <script src="./assets/js/jquery.validate.min.js"></script>
    <script src="./assets/js/mail-script.js"></script>
    <script src="./assets/js/jquery.ajaxchimp.min.js"></script>

    <!-- Jquery Plugins, main Jquery -->
    <script src="./assets/js/plugins.js"></script>
    <script src="./assets/js/main.js"></script>
  </body>
</php>
