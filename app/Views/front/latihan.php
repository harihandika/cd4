<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Latihan</title>

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome Icons -->
  <link rel="stylesheet" href="<?= base_url() ?>/plugins/fontawesome-free/css/all.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="<?= base_url() ?>/dist/css/adminlte.min.css">
 
</head>
<body class="hold-transition layout-top-nav">

  <div class="wrapper">
  <div id="bgamerika" style="">
  <!-- Navbar -->
  <?= $this->include('front/navbar') ?>


  <!-- Content Wrapper. Contains page content -->
  <div id="contentwrapper" class="content-wrapper" style="background-color: #00000052;">

    <!-- Main content -->
    <div class="content" style="padding-top: 30px;">
      <div class="container" style="padding:0px;min-width: 90%;">
        <div class="row">
          <div class="col-lg-12">
            <div id="card" class="card" style="background-color: #00000052;">
              <div class="card-body">
              <span id="countdown" style='float:right;font-size:40px;color:#000000;'></span>
                <div id="cardbody"><!-- END ID CARDBODY -->
                <div class="row" style="min-height:400px;">
                <div class="col-lg-12" style="text-align:center;">
                <h1 style="margin:10px;text-decoration:underline;font-weight:bold;color:#ffffff;"> LATIHAN </h1>
                </div>
                <div class="col-lg-12" style="text-align:center;">
                
                  <?php
                  $this->session = \Config\Services::session();
                      foreach ($materiSK as $key) {
                        $used = 0;
                        $materi_id = $key->materi_id;
                        $user_id = $this->session->user_id;
                        $db = db_connect();
                        $query = $db->query("SELECT * FROM respon WHERE materi = $materi_id AND created_user_id = $user_id")->getResultArray();

                        if (count($query)>0) {
                          $used = $query[0]['used'] + 1;
                        } else {
                          $used = $used + 1;
                        }
                        
                        $stylebox = "width:100%;text-align:center;border:2px solid black;line-height: 100px;margin:10px;border-radius:5px;cursor:pointer;font-size: 20px;font-weight: bold;";
                        $click = "onclick=\"showsk_grp()\"";
                        
                  ?>
                    <div style="cursor:pointer;display:inline-block;margin-left:10px;margin-right:10px;margin-top:30px;text-align:center;">
                      <div <?= $click ?> id="materi_<?= $key->materi_id ?>"><img style='height:200px;width:150px;border: 2px solid white;border-radius:10px;' src="images/bg/photo3.jpg"></div>
                      <label style="color:#ffffff;font-size:20px;"><?= $key->materi_nm ?></label>
                    </div>
                  <?php
                      }
                  ?>
                </div>

              


                </div><!-- END ID row -->
                </div><!-- END ID CARDBODY -->
              </div>
            </div>
          </div>
        </div>
        <!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

  <!-- Control Sidebar -->

  <!-- /.control-sidebar -->
  <div class="d-none" id='loader-wrapper'>
        <div class="loader">Mohon menunggu</div>
      </div>
  <!-- Main Footer -->
</div>
</div>
<!-- ./wrapper -->

<!-- REQUIRED SCRIPTS -->

<!-- jQuery -->
<script src="<?= base_url() ?>/plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="<?= base_url() ?>/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- AdminLTE App -->
<script src="<?= base_url() ?>/plugins/chart.js/Chart.min.js"></script>
<script src="<?= base_url() ?>/dist/js/adminlte.min.js"></script>
<script>
  var timers;
  var bgamerika = $("#bgamerika");
  var navbar = $("#navbar");
  var contentwrapper = $("#contentwrapper");
  var card = $("#card");

  function showresult(jenis_id) {
    bgamerika.removeAttr("style");
    navbar.css("background-color", "#344e41");
    contentwrapper.css("background-color", "#588157");
    card.css("background-color", "#dad7cd");
    $("#bgamerika").removeAttr("style");
    $("#countdown").text("");
    $.ajax({
            url: "<?= base_url('latihan/showresult') ?>",
            type: "post",
            dataType: "json",
            data: {
                "jenis_id": jenis_id
            },
            success: function(data) {
                // console.log(data);
                $("#cardbody").html(data.html);
            },
            error: function() {
                alert("error ajax");
            }
        });
  }

  function showsk_grp() {
    $.ajax({
      url: "<?= base_url('latihan/showsk_grp') ?>",
      type: "post",
      dataType: "json",
      success: function(data) {
        $("#cardbody").html(data);
      },
      error: function() {
          alert("error ajax");
      }
    });
  }

  function petunjuksoalsk(class_soal,kolom_id,used,sk_group_id) {
    bgamerika.removeAttr("style");
    navbar.css("background-color", "#344e41");
    contentwrapper.css("background-color", "#588157");
    card.css("background-color", "#dad7cd");
    
    $.ajax({
            url: "<?= base_url('sikapkerja/petunjuksoal') ?>",
            type: "post",
            dataType: "json",
            data: {
                "class_soal" : class_soal,
                "sk_group_id" : sk_group_id
            },
            success: function(data) {
                // console.log(data);
                $("#cardbody").html(data);
            },
            error: function() {
                alert("error ajax");
            }
        });
  }

  function countdownsk(detik,text,kolom_id,used) {
      var secondss=detik;
      timers = window.setInterval(function() { 
            myFunction();
          }, 1000); // every second

     function myFunction() {
        secondss--;
        $("#countdown").text(convertSeconds(secondss));
        if (secondss === 0) {
          if (text == "rehatsk") {
            window.clearInterval(timers);
            startujiansk("start",1,kolom_id+1,used);
          } else {
            window.clearInterval(timers);
            startujiansk("rehatsk",1,kolom_id,used);
          }
            
        } else {
            //Do nothing
        }
      }
  }

  function startujiansk(class_soal,no_soal,kolom_id,used,jawaban_id,pilihan_nm) {
    bgamerika.removeAttr("style");
    navbar.css("background-color", "#344e41");
    contentwrapper.css("background-color", "#588157");
    card.css("background-color", "#dad7cd");
    var soal_id = $("#soal_id").val();
    if (class_soal == "start" || class_soal == "petunjuk") {
        no_soal = no_soal;
        countdownsk(11160,class_soal,kolom_id,used)
    } else if (class_soal == "nextsoal") {
      no_soal = no_soal + 1;
    } 
      $.ajax({
              url: "<?= base_url('sikapkerja/startujian') ?>",
              type: "post",
              dataType: "json",
              data: {
                  "class_soal": class_soal,
                  "no_soal": no_soal,
                  "jawaban_id": jawaban_id,
                  "pilihan_nm": pilihan_nm,
                  "kolom_id": kolom_id,
                  "used": used,
                  'soal_id' : soal_id
              },
              success: function(data) {
                if (data == "jawabankosong") {
                    alert("Jawaban belum dipilih");
                } else {
                    if (data.class_soal == "finish") {
                      window.clearInterval(timers);
                      $("#countdown").text("");
                    } else if (data.class_soal == "rehatsk") {
                      window.clearInterval(timers);
                      countdownsk(3,"rehatsk",kolom_id,used);
                    }

                    $("#cardbody").html(data.html);
                }
                
              },
              error: function() {
                  alert("Error system");
              }
          });
    
  }

  function petunjuksoal(jenis_id) {
    bgamerika.removeAttr("style");
    navbar.css("background-color", "#344e41");
    contentwrapper.css("background-color", "#588157");
    card.css("background-color", "#dad7cd");
    $.ajax({
            url: "<?= base_url('latihan/petunjuksoal') ?>",
            type: "post",
            dataType: "json",
            data: {
                "jenis_id": jenis_id

            },
            success: function(data) {
                // console.log(data);
                $("#cardbody").html(data);
            },
            error: function() {
                alert("error ajax");
            }
        });
  }


  function startlatihan(class_soal,materi,no_soal,jenis_id,jawaban_id,pilihan_nm) {
    bgamerika.removeAttr("style");
    navbar.css("background-color", "#344e41");
    contentwrapper.css("background-color", "#588157");
    card.css("background-color", "#dad7cd");
    var soal_id = $("#soal_id").val();
    if (class_soal == "nextsoal") {
        no_soal = no_soal + 1;
        if (jawaban_id == "radio") {
          jawaban_id = $("input[type='radio'][name='jawaban']:checked").val();
          pilihan_nm = $("input[type='radio'][name='jawaban']:checked").data("pilihan");
        } else {
          jawaban_id = jawaban_id;
          pilihan_nm = pilihan_nm;
        }
    } 
    
      $.ajax({
              url: "<?= base_url('latihan/startlatihan') ?>",
              type: "post",
              dataType: "json",
              data: {
                  "class_soal" : class_soal,
                  "materi" : materi,
                  "no_soal" : no_soal,
                  "jenis_id" : jenis_id,
                  "jawaban_id" : jawaban_id,
                  "pilihan_nm" : pilihan_nm,
                  "soal_id" : soal_id
              },
              beforeSend: function() {
                $("#loader-wrapper").removeClass("d-none");
              },
              success: function(data) {
                $("#loader-wrapper").addClass("d-none");
                if (data == "jawabankosong") {
                  alert("Jawaban belum dipilih");
                } else if (data.html == "belumadasoal") {
                  alert("Tidak ada soal");
                } else if (data.html == "finish") {
                  $("#loader-wrapper").removeClass("d-none");
                  setTimeout(showresult(jenis_id), 3000);
                } else {
                  if (class_soal == "start") {
                      window.clearInterval(timers);
                      countdown(1200,"Passhand",materi,jenis_id);
                  }
                  $("#cardbody").html(data.html);
                    if (data.class_soal == "finish") {
                      window.clearInterval(timers);
                      $("#countdown").text("");
                    }
                }
              },
              error: function() {
                  alert("Error system");
              }
          });
    
  }

  
  function convertSeconds(s){
    var min = Math.floor(s / 60);
    var sec = s % 60;
    return min + ":" + sec;
  }


  function countdown(detik,text,materi,jenis_id) {
      var secondss=detik;
      timers = window.setInterval(function() { 
            myFunction();
          }, 1000); // every second

     function myFunction() {
        secondss--;
        $("#countdown").text(convertSeconds(secondss));
        if (secondss === 0) {
            window.clearInterval(timers);
            startlatihan("finish",materi,11,jenis_id,"null","null");
            
            
        } else {
            //Do nothing
        }

      }
  }

  
</script>
</body>
</html>