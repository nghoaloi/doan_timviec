 <main>
      <!-- slider Area Start-->
      <div class="slider-area">
        <!-- Mobile Menu -->
        <div class="slider-active">
          </div>
        </div>
      </div>
      <!-- slider Area End-->
      <!-- Our Services Start -->
      <div class="our-services section-pad-t30">
        <div class="container">
          <!-- Section Tittle -->
          <div class="row d-flex justify-contnet-center">
            <div class="col-xl-3 col-lg-3 col-md-4 col-sm-6">
              <div class="single-services text-center mb-30">
                <div class="services-ion">
                  <span class="flaticon-tour"></span>
                </div>  
                <div class="services-cap">
                  <h5><a href="job_listing.php">Design & Creative</a></h5>
                  <span>(653)</span>
                </div>
              </div>
            </div>
            <div class="col-xl-3 col-lg-3 col-md-4 col-sm-6">
              <div class="single-services text-center mb-30">
                <div class="services-ion">
                  <span class="flaticon-cms"></span>
                </div>
                <div class="services-cap">
                  <h5><a href="job_listing.php">Design & Development</a></h5>
                  <span>(658)</span>
                </div>
              </div>
            </div>
            <div class="col-xl-3 col-lg-3 col-md-4 col-sm-6">
              <div class="single-services text-center mb-30">
                <div class="services-ion">
                  <span class="flaticon-report"></span>
                </div>
                <div class="services-cap">
                  <h5><a href="job_listing.php">Sales & Marketing</a></h5>
                  <span>(658)</span>
                </div>
              </div>
            </div>
            <div class="col-xl-3 col-lg-3 col-md-4 col-sm-6">
              <div class="single-services text-center mb-30">
                <div class="services-ion">
                  <span class="flaticon-app"></span>
                </div>
                <div class="services-cap">
                  <h5><a href="job_listing.php">Mobile Application</a></h5>
                  <span>(658)</span>
                </div>
              </div>
            </div>
            <div class="col-xl-3 col-lg-3 col-md-4 col-sm-6">
              <div class="single-services text-center mb-30">
                <div class="services-ion">
                  <span class="flaticon-helmet"></span>
                </div>
                <div class="services-cap">
                  <h5><a href="job_listing.php">Construction</a></h5>
                  <span>(658)</span>
                </div>
              </div>
            </div>
            <div class="col-xl-3 col-lg-3 col-md-4 col-sm-6">
              <div class="single-services text-center mb-30">
                <div class="services-ion">
                  <span class="flaticon-high-tech"></span>
                </div>
                <div class="services-cap">
                  <h5><a href="job_listing.php">Information Technology</a></h5>
                  <span>(658)</span>
                </div>
              </div>
            </div>
            <div class="col-xl-3 col-lg-3 col-md-4 col-sm-6">
              <div class="single-services text-center mb-30">
                <div class="services-ion">
                  <span class="flaticon-real-estate"></span>
                </div>
                <div class="services-cap">
                  <h5><a href="job_listing.php">Real Estate</a></h5>
                  <span>(658)</span>
                </div>
              </div>
            </div>
            <div class="col-xl-3 col-lg-3 col-md-4 col-sm-6">
              <div class="single-services text-center mb-30">
                <div class="services-ion">
                  <span class="flaticon-content"></span>
                </div>
                <div class="services-cap">
                  <h5><a href="job_listing.php">Content Writer</a></h5>
                  <span>(658)</span>
                </div>
              </div>
            </div>
          </div>
          <!-- More Btn -->
          <!-- Section Button -->
          <div class="row">
            <div class="col-lg-12">
              <div class="browse-btn2 text-center mt-50">
                <a href="job_listing.php" class="border-btn2"
                  >Browse All Sectors</a
                >
              </div>
            </div>
          </div>
        </div>
      </div>
      <!-- Our Services End -->
      <!-- Online CV Area Start -->
      
      </div>
      <!-- Online CV Area End-->
      <!-- Featured_job_start -->
      <section class="featured-job-area feature-padding">
        <div class="container">
          <!-- Section Tittle -->
          <div class="row">
            <div class="col-lg-12">
              <div class="section-tittle text-center">
                <span>Công việc gần đây</span>
                <h2>Việc làm nổi bật</h2>
              </div>
            </div>
          </div>
          <div class="row justify-content-center">
            <div class="col-xl-10">
            <?php
                               
                               $jobs = getJobs(); // Lấy tất cả danh sách công việc
                               foreach ($jobs as $job) {
                                   echo '<div class="single-job-items mb-30">
                                           <div class="job-items">
                                               <div class="company-img" >
                                                   <a href="index.php?act=job_thongtin&jobid='. htmlspecialchars($job['JobID']) .'"  ><img src="uploads/' . htmlspecialchars($job['LogoURL']) . ' " alt=""></a>
                                               </div>

                                               <div class="job-tittle job-tittle2">
                                                   <a href="#">
                                                       <h4>' . htmlspecialchars($job['JobTitle']) . ' </h4>
                                                   </a>
                                                   <ul>
                                                       <li>' . htmlspecialchars($job['CompanyName']) . '</li>
                                                       <li><i class="fas fa-map-marker-alt"></i>' . htmlspecialchars($job['Location']) . '</li>
                                                       <li>' . htmlspecialchars($job['SalaryRange']) . '</li>
                                                   </ul>
                                               </div>
                                               </div>
                                               <div class="items-link items-link2 f-right">
                                               <a href="#">' . htmlspecialchars($job['EmploymentType']) . '</a>
                                               <!-- <span>7 hours ago</span> -->
                                               </div>
                                         </div>';
                               }
                               ?>
                                                    
            </div>
          </div>
        </div>
      </section>
      <!-- Featured_job_end -->
      <!-- How  Apply Process Start-->
      <div
        class="apply-process-area apply-bg pt-150 pb-150"
        data-background="assets/img/gallery/how-applybg.png"
      >
        <div class="container">
          <!-- Section Tittle -->
          <div class="row">
            <div class="col-lg-12">
              <div class="section-tittle white-text text-center">
                <span>quá trình xin việc</span>
                <h2>cách hoạt động</h2>
              </div>
            </div>
          </div>
          <!-- Apply Process Caption -->
          <div class="row">
            <div class="col-lg-4 col-md-6">
              <div class="single-process text-center mb-30">
                <div class="process-ion">
                  <span class="flaticon-search"></span>
                </div>
                <div class="process-cap">
                  <h5>1. tìm việc</h5>
                  <p>
                    Sorem spsum dolor sit amsectetur adipisclit, seddo eiusmod
                    tempor incididunt ut laborea.
                  </p>
                </div>
              </div>
            </div>
            <div class="col-lg-4 col-md-6">
              <div class="single-process text-center mb-30">
                <div class="process-ion">
                  <span class="flaticon-curriculum-vitae"></span>
                </div>
                <div class="process-cap">
                  <h5>2. nộp đơn</h5>
                  <p>
                    Sorem spsum dolor sit amsectetur adipisclit, seddo eiusmod
                    tempor incididunt ut laborea.
                  </p>
                </div>
              </div>
            </div>
            <div class="col-lg-4 col-md-6">
              <div class="single-process text-center mb-30">
                <div class="process-ion">
                  <span class="flaticon-tour"></span>
                </div>
                <div class="process-cap">
                  <h5>3. chờ xét duyểt</h5>
                  <p>
                    Sorem spsum dolor sit amsectetur adipisclit, seddo eiusmod
                    tempor incididunt ut laborea.
                  </p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <!-- How  Apply Process End-->

                               
    </main>

