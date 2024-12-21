<!DOCTYPE php>
<php lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta http-equiv="x-ua-compatible" content="ie=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>FUNNY-JOB</title>
    <link rel="manifest" href="site.webmanifest" />
    <link
      rel="shortcut icon"
      type="image/x-icon"
      href="assets/img/favicon.ico"
    />

    <!-- css Here 12 cai -->
    <link rel="stylesheet" href="assets/css/bootstrap.min.css" />
    <link rel="stylesheet" href="assets/css/owl.carousel.min.css" />
    <link rel="stylesheet" href="assets/css/flaticon.css" />
    <link rel="stylesheet" href="assets/css/price_rangs.css" />
    <link rel="stylesheet" href="assets/css/slicknav.css" />
    <link rel="stylesheet" href="assets/css/animate.min.css" />
    <link rel="stylesheet" href="assets/css/magnific-popup.css" />
    <link rel="stylesheet" href="assets/css/fontawesome-all.min.css" />
    <link rel="stylesheet" href="assets/css/themify-icons.css" />
    <link rel="stylesheet" href="assets/css/slick.css" />
    <link rel="stylesheet" href="assets/css/nice-select.css" />
    <link rel="stylesheet" href="assets/css/style.css" />
  </head>
  <body>
    <!-- Preloader Start -->
    <!-- <div id="preloader-active">
      <div class="preloader d-flex align-items-center justify-content-center">
        <div class="preloader-inner position-relative">
          <div class="preloader-circle"></div>
          <div class="preloader-img pere-text">
            <img src="assets/img/logo/logo.png" alt="" />
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
                  <a href="index.php"
                    ><img src="assets/img/logo/logo.png" alt="" width ="200" height="150" />
                  </a>
                </div>
              </div>
              <div class="col-lg-9 col-md-9">
                <div class="menu-wrapper">
                  <!-- Main-menu -->
                   <!-- code php voi vai tro la 1-->
            <?php
              if(isset($_SESSION['UserType'])&&($_SESSION['UserType']=='Admin')){

            ?>
                  <div class="main-menu">
                    <nav class="d-none d-lg-block">
                      <ul id="navigation">
                        <li><a href="index.php?act=user">Danh sách tài khoản</a></li>
                        <li><a href="index.php?act=company">Danh sách công ty </a></li>
                        <li><a href="index.php?act=job">Danh sách công việc</a></li>
                        <li><a href="index.php?act=review">Đánh giá</a></li>
                        <?php
                          $ten = isset($_SESSION['FullName']) ? $_SESSION['FullName'] : '';
                          echo '<li><a href="index.php?act=userinfo">'.$ten.'</a></li>
                        <li><a href="index.php?act=thoat">Đăng xuất</a></li>';
                        ?>  
                      </ul>
                                          
                      </nav>
                  </div>
               <?php
                  }else if(isset($_SESSION['UserType'])&&($_SESSION['UserType']=='Candidate')){
                ?>
                       <div class="main-menu">
                    <nav class="d-none d-lg-block">
                      <ul id="navigation">
                        <li><a href="index.php?act=home">trang chủ</a></li>
                        <li><a href="index.php?act=joblisting">công việc apply </a></li>
                        <li><a href="index.php?act=profile">profile</a></li>

                        <?php
                          $ten = isset($_SESSION['FullName']) ? $_SESSION['FullName'] : '';
                          echo '<li><a href="index.php?act=userinfo">'.$ten.'</a></li>
                        <li><a href="index.php?act=thoat">Đăng xuất</a></li>';
                        ?>  
                      </ul>
                                          
                      </nav>
                  </div>
                <?php
                  }else if(isset($_SESSION['UserType'])&&($_SESSION['UserType']=='Employer')){
                   
                ?>
             
                  <div class="main-menu">
                    <nav class="d-none d-lg-block">
                      <ul id="navigation">
                        <li><a href="index.php?act=home">trang chủ</a></li>
                        <li><a href="index.php?act=dangbai">đăng bài </a></li>
                        <li><a href="index.php?act=dangquangcao">đăng quản cáo</a></li>
                        <li><a href="index.php?act=quanglydon">quảng lý đơn</a></li>
                        <?php
                          $ten = isset($_SESSION['FullName']) ? $_SESSION['FullName'] : '';
                          echo '<li><a href="index.php?act=userinfo">'.$ten.'</a></li>
                        <li><a href="index.php?act=thoat">Đăng xuất</a></li>';
                        ?>  
                      </ul>
                                          
                      </nav>
                  </div>
                <?php
                  }else{
                ?>
                  <!-- Header-btn -->
                  <div class="header-btn d-none f-right d-lg-block">
                    <a href="views/register_ntd.php" class="btn head-btn1">Đăng Ký</a>
                    <a href="views/login.php" class="btn head-btn2">Đăng Nhập</a>
                  </div>
                <?php
                  }
                ?>
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
