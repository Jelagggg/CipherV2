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

if(!isset($_GET["payload"])){
  die(header("location:../dashboard.php"));
}

$getaccess = "SELECT * FROM users WHERE uname = '".$_SESSION["username"]."'";
if($result = mysqli_query($db, $getaccess)){
    if(mysqli_num_rows($result) > 0){
        while($row = mysqli_fetch_array($result)){
          if ($row["rank"] == 3){
          }else{
            $ifaccess = "SELECT token FROM payloads WHERE token = '".$row["token"]."' AND  id = $idpay";
              if($resultd = mysqli_query($db, $ifaccess)){
                 if(mysqli_num_rows($resultd) > 0){
              }else{
                setlocale (LC_TIME, 'fr_FR.utf8','fra'); 
                $stmt = $db->prepare("INSERT INTO logs (comment, heure, rank, status) VALUES (?, ?, ?, ?)");
                $stmt->bind_param("ssss", $comment, $heure, $rank, $status);
                $comment = $_SESSION["username"]." a tenté d'accéder à un payload dont il ne disposait pas l'accès.";
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

$idpay = $_GET["payload"];  
$idp = $db->real_escape_string($idpay);


if(isset($_POST["editpayload"])){
  if (!empty($_POST["nompay"] and $_POST["cate"] and $_POST["contenup"])){
    $nomp = $db->real_escape_string($_POST["nompay"]);
    $cate = $db->real_escape_string($_POST["cate"]);
    $contn = $db->real_escape_string($_POST["contenup"]);
    $sqld = "SELECT * FROM payloadscate WHERE name = '".$_POST["cate"]."'";
    if($resultd = mysqli_query($db, $sqld)){
      if(mysqli_num_rows($resultd) > 0){
        $stmt = $db->prepare("UPDATE payloads SET name = ?, category = ?, content = ? WHERE id = '". $idpay ."'");
        $stmt->bind_param("sss", $name, $category, $content);
        $name = htmlspecialchars($nomp);
        $category = htmlentities($cate);
        $content = htmlspecialchars($contn);

  if(mysqli_stmt_execute($stmt)){
    setlocale (LC_TIME, 'fr_FR.utf8','fra'); 
        $stmtl = $db->prepare("INSERT INTO logs (comment, heure, rank, status) VALUES (?, ?, ?, ?)");
        $stmtl->bind_param("ssss", $comment, $heure, $rank, $status);
        $comment = "Payload édité : ".$cate." par : ".$_SESSION["username"].".";
        $heure = strftime('%d %B %Y à %H:%M',strtotime("+6 hours")); 
        $rank = "1";
        $status = "warning";
        $stmtl->execute();
        $stmtl->close();
  $_POST['success'] = 'Payload édité avec succès.';
  $stmt->close();
  }else{
  $_POST['error'] = 'Une erreur est survenue. Contactez un administrateur.';
  }
  }else{
  $_POST['error'] = 'Catégorie inéxistante.';
  }
  }else{
  $_POST['error'] = 'Une erreur est survenue. Contactez un administrateur.';
  }
  }else{
  $_POST['error'] = 'Un des champs est manquant.';
  }
}

if(isset($_POST["deletepayload"])){
  $stmt = $db->prepare("DELETE FROM payloads WHERE id = ?");
  $stmt->bind_param("s", $id);
  $id = $idpay;
  if(mysqli_stmt_execute($stmt)){
  $stmt->close();
  header("Location: ../payloads.php"); 
  }else{
  $_POST['error'] = 'Une erreur est survenue. Contactez un administrateur.';
  }
}


$check = "SELECT * FROM users WHERE uname = '".$_SESSION["username"]."'";
if($resultc = mysqli_query($db, $check)){
 if(mysqli_num_rows($resultc) > 0){
  while($rowa = mysqli_fetch_array($resultc)){
    if($rowa["rank"] == 1 or $rowa['rank'] == 2){
      $sql = "SELECT * FROM payloads WHERE id = '$idp' AND token = '".$rowa["token"]."'";
    }else{
      $sql = "SELECT * FROM payloads WHERE id = '$idp'";
    }
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
    Payloads - Modification
  </title>
  <meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0, shrink-to-fit=no' name='viewport' />
  <!--     Fonts and icons     -->
  <link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700|Roboto+Slab:400,700|Material+Icons" />
    <script src="https://kit.fontawesome.com/647923b639.js"></script>
  <!-- CSS Files -->
  <link href="../assets/css/material-dashboard.minf066.css?v=2.1.0" rel="stylesheet" />
</head>

<body class="">
  <div class="wrapper ">
    
    <? include '../inc/sidebar.php'; ?>
    <div class="main-panel">
      <!-- Navbar -->
      <?php include '../inc/navbar.php'; ?>
      <!-- End Navbar -->
      <div class="content">
        <div class="container-fluid">
          <div class="row">
            <div class="col-md-12">
              <form method="POST">
                <div class="card ">
                  <div class="card-header card-header-danger card-header-icon">
                    <div class="card-icon">
                      <i class="fas fa-edit"></i>
                    </div>
                    <h4 class="card-title">Modification payload</h4>
                  </div>
                  <div class="card-body ">
                    <div class="form-group">
                      <label  class="bmd-label-floating"> Nom</label>
                      <input type="text" class="form-control" name="nompay" value="<? echo $row["name"]; ?>" required="true">
                    </div>
                    <div class="form-group">
                      <label class="bmd-label-floating"> Catégorie</label>
                      <input type="text" class="form-control" name="cate" value="<? echo $row["category"]; ?>" required="true">
                    </div>
                    <div class="form-group">
                      <label class="bmd-label-floating"> Contenu </label>
                      <textarea class="form-control" rows="20" name="contenup"><? echo $row["content"]; ?></textarea> 
                    </div>
                    <div class="category form-category">Catégorie disponible : <?php
                        $check = "SELECT * FROM users WHERE uname = '".$_SESSION['username']."'";
                          if($resultc = mysqli_query($db, $check)){
                              if(mysqli_num_rows($resultc) > 0){
                                while($rowa = mysqli_fetch_array($resultc)){
                        if ($rowa["rank"] == 1 or $rowa["rank"] == 2){
                          $catall = "SELECT * FROM payloadscate WHERE token = '".$rowa["token"]."'";
                        }else{
                          $catall = "SELECT * FROM payloadscate";
                        }
                        if($resultcat = mysqli_query($db, $catall)){
                                if(mysqli_num_rows($resultcat) > 0){
                                    while($row = mysqli_fetch_array($resultcat)){ 
                               echo " - ".$row["name"]; 
                           }
                       }else{
                        echo "Aucune catégorie n'a été crée";
                       }
                   }else{
                    $_POST["error"] =  "Erreur lors du chargement de la page";
                   }
                   }
                   }
                   } ?></div>
                  </div>
                  <div class="card-footer text-right">
                    <div class="form-check mr-auto">
                    </div>
                    <button type="submit" name="deletepayload" class="btn btn-danger"><i class="fas fa-trash"></i> Supprimer</button>
                    <button type="submit" name="editpayload" class="btn btn-success"> <i class="fas fa-check"></i> Sauvegarder</button>
                  </div>
                </div>
              </form>
            </div>
            
            </div>
     <? include '../inc/footer.php'; ?>
    </div>
  </div>
  
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
  <script src="../../cdnjs.cloudflare.com/ajax/libs/core-js/2.4.1/core.js"></script>
  <!-- Library for adding dinamically elements -->
  <script src="../assets/js/plugins/arrive.min.js"></script>
  <!--  Google Maps Plugin    -->
  <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyB2Yno10-YTnLjjn_Vtk0V8cdcY5lC4plU"></script>
  <!-- Place this tag in your head or just before your close body tag. -->
  <script async defer src="../../buttons.github.io/buttons.js"></script>
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

<?php if (isset($_POST["success"])) { ?> 
<script> md.showNotification("top","right","<?php echo htmlspecialchars($_POST["success"]) ?>","done","3") </script>
<?php } ?>
<?php if (isset($_POST["error"])) { ?> 
<script> md.showNotification("top","right","<?php echo htmlspecialchars($_POST["error"]) ?>","report_problem","2") </script>
<?php } ?>
<!-- Cipher Panel - Author : exit - Free Access Version -->
</html>



<?php
}
}else{
  die('Payload introuvable.');
}
}else{
  die('Erreur n2.');
}
}
}
} ?>