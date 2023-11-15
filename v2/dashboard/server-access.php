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

if(!isset($_GET["serv"])){
  die(header("location:dashboard.php"));
}

$idserv = $db->real_escape_string($_GET["serv"]);

$getaccess = "SELECT * FROM users WHERE uname = '".$_SESSION["username"]."'";
if($result = mysqli_query($db, $getaccess)){
    if(mysqli_num_rows($result) > 0){
        while($row = mysqli_fetch_array($result)){
          if ($row["rank"] == 3){
          }else{
            $ifaccess = "SELECT token FROM servers WHERE token = '".$row["token"]."' AND  id = $idserv";
              if($resultd = mysqli_query($db, $ifaccess)){
                 if(mysqli_num_rows($resultd) > 0){
              }else{
                setlocale (LC_TIME, 'fr_FR.utf8','fra'); 
                $stmt = $db->prepare("INSERT INTO logs (comment, heure, rank, status) VALUES (?, ?, ?, ?)");
                $stmt->bind_param("ssss", $comment, $heure, $rank, $status);
                $comment = $_SESSION["username"]." a tenté d'accéder à un serveur dont il ne disposait pas l'accès.";
                $heure = strftime('%d %B %Y à %H:%M',strtotime("+6 hours")); 
                $rank = "2";
                $status = "error";
                $stmt->execute();
                $stmt->close();
                die("Bien essayé, les administrateurs ont été prévenu de votre présence sur une page dont vous ne disposez pas l'accès.");
              }
            }
          }
        }
    }
}


$sql = "SELECT * FROM servers WHERE id = '$idserv'";
  if($result = mysqli_query($db, $sql)){
    if(mysqli_num_rows($result) > 0){
      while($row = mysqli_fetch_array($result)){ 
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
    Accès serveur
  </title>
  <meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0, shrink-to-fit=no' name='viewport' />
  <link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700|Roboto+Slab:400,700|Material+Icons" />
  <script src="https://kit.fontawesome.com/647923b639.js"></script>
  <!-- CSS Files -->
  <link href="../assets/css/material-dashboard.minf066.css?v=2.1.0" rel="stylesheet" />
  <link href="../assets/css/load.css" rel="stylesheet" />
</head>

<body class="">
  
  <div class="wrapper ">
   <?php include '../inc/sidebar.php'; ?>
    <div class="main-panel">
      <!-- Navbar -->
      <?php include '../inc/navbar.php'; ?>
      <!-- End Navbar -->
      <div class="content">
        <div class="container-fluid">
          <div class="row">
            <div class="col-lg-8 col-md-12">
              <div class="card">
                <div class="card-header card-header-tabs card-header-danger">
                  <div class="nav-tabs-navigation">
                    <div class="nav-tabs-wrapper">
                      <span class="nav-tabs-title">Serveur:</span>
                      <ul class="nav nav-tabs" data-tabs="tabs">
                        <li class="nav-item">
                          <a class="nav-link active" href="#chat" data-toggle="tab">
                            <i class="fas fa-comments"></i> Chat
                            <div class="ripple-container"></div>
                          </a>
                        </li>
                        <li class="nav-item">
                          <a class="nav-link" href="#infos" data-toggle="tab">
                            <i class="fas fa-info-circle"></i> Informations
                            <div class="ripple-container"></div>
                          </a>
                        </li>
                      </ul>
                    </div>
                  </div>
                </div>
                <div class="card-body">
                  <div class="tab-content">
                    <div class="tab-pane active" id="chat">
                      <table class="table">
                        <style type="text/css"> 
                    #madiv{height:300pt;overflow:auto} 
                    </style>
                        <? $userinf = "SELECT * FROM users WHERE uname = '".$_SESSION["username"]."'";
                        if($resultss = mysqli_query($db, $userinf)){
                            if(mysqli_num_rows($resultss) > 0){
                                while($rowusr = mysqli_fetch_array($resultss)){ ?>
                        <div class="container-flude" id="madiv"><? if ($rowusr["rank"] != 1){ echo 'Chargement du chat en cours...';?> 
                        <script>
                      setInterval(function() {
                          $('#madiv').load('chatsys/chat.php?serv=<?php echo $idserv; ?>').fadeIn("slow");
                      }, 2000);
                            </script> <? }else{ ?>
                          <div class="row">
                <div class="col-lg-12 cards">
                  <div class="card card-pricing card-raised">
                    <div class="card-body">
                      <h6 class="card-category">Premium</h6>
                      <div class="card-icon icon-warning">
                        <i class="far fa-gem"></i>
                      </div>
                      <p class="card-description">
                        Cette fonctionnalité est réservée aux Premiums.
                      </p>
                      <a href="" class="btn btn-warning btn-round">Premium</a>
                    </div>
                  </div>
                </div>
              </div>  <? } ?>
                      </table>
                    </div>
                    <div class="tab-pane" id="infos">
                  Nom du serveur : <label class="text-primary"><? echo $row["name"]; ?></label>
                  <div class="clearfix">&nbsp;</div>
                  Mot de passe : <label class="text-primary"><? echo $row["password"]; ?></label>
                  <div class="clearfix">&nbsp;</div>
                  Mot de passe RCON : <label class="text-primary"><? echo $row["rcon"]; ?></label>
                  <div class="clearfix">&nbsp;</div>
                  Joueurs connectés : <label class="text-primary"><? echo $row["players"]; ?></label>
                  <div class="clearfix">&nbsp;</div>
                  <?php 
                  $duree = $row["uptime"];
                  $minutes=$duree%60;
            $heures=($duree-$minutes)/60; ?>
                  UPTime : <label class="text-primary"><? echo $heures." heures et "; echo $minutes; ?> minutes</label>
                  <div class="clearfix">&nbsp;</div>
                  Liste des joueurs :
                  <div class="clearfix">&nbsp;</div>
                  <label class="text-primary"><? echo nl2br($row["ply"]); ?></label>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-md-4">
              <div class="card">
                <div class="card-header card-header-danger">
                  <h4 class="card-title">Payload launcher</h4>
                  <p class="card-category">Exécutez les payloads</p>
                </div>
                <div id="pldiv" class="card-body"><?
                    if($rowusr["rank"] == 1){
                        $sqld = "SELECT * FROM payloadscate WHERE token = '".$rowusr["token"]."'";
                    }else{
                         $sqld = "SELECT * FROM payloadscate";
                    }
                    if($resultd = mysqli_query($db, $sqld)){
                        if(mysqli_num_rows($resultd) > 0){
                            while($rowusr = mysqli_fetch_array($resultd)){ ?>
                                <form >
                                    <label><? echo $rowusr["name"]?></label>
                                    <select id="payloadselected_<?echo $rowusr["name"];?>" name="payloadselected" class="custom-select" style="background-color: #1A2035; color: #FFFFFF; border: 5px; border-color : #661515; box-shadow: none;">
                                    <?php $sqldd = "SELECT * FROM payloads WHERE category = '".$rowusr["name"]."' AND token = '".$rowusr["token"]."'";
                                        if($resultdd = mysqli_query($db, $sqldd)){
                                            if(mysqli_num_rows($resultdd) > 0){
                                                while($rowdd = mysqli_fetch_array($resultdd)){ ?>
                                                    <option value="<? echo $rowdd["name"]; ?>"><? echo $rowdd["name"]; ?></option>
                                                <? }
                                            }
                                        }
                                    ?>
                                    </select>
                                    <button type="button" id="btn_<?echo $rowusr["name"];?>" onclick="sendPl_<?echo $rowusr['name'];?>()" name="sendpay" class="btn btn-success pull-right"><i class="fas fa-check"></i>&nbsp;Exécuter</button><div id='loadingmessage' style='display:none'>
                                    Envoyé
                                    </div>
                                    <div class="clearfix">&nbsp;</div>
                                </form>

                                <script>
                                    function sendPl_<?echo $rowusr['name'];?>(){
                                        alert($('#payloadselected_<?echo $rowusr["name"];?>').val())
                                        $('#pldiv').fadeOut(200);
                                        $('#load').fadeIn(2000);
                                        var idserver = <?php echo json_encode($idserv); ?>;
                                        $.post('psubmit.php',
                                        {pname: $('#payloadselected_<?echo $rowusr["name"];?>').val(), idserv:idserver},
                                        function(data){
                                            if(data == 'done'){
                                                setTimeout("showIt()", 3000); 
                                            }
                                        }
                                        );
                                    }
                                </script>

                             <? }
                        }
                    }
                ?>
            </div>
                                                   <div id="load" class="containAll" style="display:none">
    <div class="containLoader">
    <div class="circleGroup circle-1"></div>
    <div class="circleGroup circle-2"></div>
    <div class="circleGroup circle-3"></div>
    <div class="circleGroup circle-4"></div>
    <div class="circleGroup circle-5"></div>
    <div class="circleGroup circle-6"></div>
    <div class="circleGroup circle-7"></div>
    <div class="circleGroup circle-8"></div>
    
    <div class="innerText">
      <p>lancement...</p>
    </div>
  </div>
  </div>
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
  <!--  Plugin for Select, full documentation here: http://silviomoreto.github.io/bootstrap-select -->
  <script src="../assets/js/plugins/bootstrap-selectpicker.js"></script>
  <!--  Plugin for the DateTimePicker, full documentation here: https://eonasdan.github.io/bootstrap-datetimepicker/ -->
  <script src="../assets/js/plugins/bootstrap-datetimepicker.min.js"></script>
  <!--  DataTables.net Plugin, full documentation here: https://datatables.net/  -->
  <script src="../assets/js/plugins/jquery.dataTables.min.js"></script>
  <!--  Plugin for Tags, full documentation here: https://github.com/bootstrap-tagsinput/bootstrap-tagsinputs  -->
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

<? } } } 
}
}else{
  die("Le serveur est introuvable.");
}

}else{
  die("Une erreur est survenue");
}  ?>
  <script>

    function showIt() {
  		$('#load').fadeOut(200);
  		$('#pldiv').fadeIn(1000);
  		md.showNotification("top","right","Payload envoyé avec succès.","done","3")
	}


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
  <noscript>
    <img height="1" width="1" style="display:none" src="https://www.facebook.com/tr?id=111649226022273&amp;ev=PageView&amp;noscript=1" />
  </noscript>
</body>
<?php if (isset($_POST["success"])) { ?> 
<script> md.showNotification("top","right","<?php echo htmlspecialchars($_POST["success"]) ?>","done","3") </script>
<?php } ?>
<?php if (isset($_POST["error"])) { ?> 
<script> md.showNotification("top","right","<?php echo htmlspecialchars($_POST["success"]) ?>","report_problem","2") </script>
<?php } ?>
<!-- Cipher Panel - Author : exit - Free Access Version -->
</html>