<?php
// include dirname(__DIR__)."/form-modal.php";
/**
 * top.php
 *
 * Author: pixelcave
 *
 * The first block of code used in every page of the template
 * Start of html, <head> tag, as well as the header of the page are included here
 *
 */
?>
<!DOCTYPE html>
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!-->
<html class="no-js">
<!--<![endif]-->

<head>
    <meta charset="utf-8">

    <meta name="description" content="<?php echo $template['description'] ?>">
    <meta name="author" content="<?php echo $template['author'] ?>">
    <meta name="robots" content="noindex, nofollow">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <!-- JavaScript -->
    <script src="//cdn.jsdelivr.net/npm/alertifyjs@1.13.1/build/alertify.min.js"></script>
    <link rel="stylesheet" href="//cdn.jsdelivr.net/npm/alertifyjs@1.13.1/build/css/themes/default.min.css" />
    <link rel="stylesheet" href="//cdn.jsdelivr.net/npm/alertifyjs@1.13.1/build/css/themes/semantic.min.css" />
    <!-- CSS -->
    <script src="https://js.pusher.com/7.0/pusher.min.js"></script>
    <link href="https://www.jqueryscript.net/demo/Highly-Customizable-jQuery-Toast-Message-Plugin-Toastr/build/toastr.css" rel="stylesheet">

    <script src="https://www.jqueryscript.net/demo/Highly-Customizable-jQuery-Toast-Message-Plugin-Toastr/toastr.js"></script>

    <script>
        let offset = 0,
            paused = true;

        function startStopwatch(evt) {
            if (paused) {
                paused = false;
                offset -= Date.now();
                render();
            }
        }

        function stopStopwatch(evt) {
            if (!paused) {
                paused = true;
                offset += Date.now();
            }
        }

        function resetStopwatch(evt) {
            if (paused) {
                offset = 0;
                render();
            } else {
                offset = -Date.now();
            }
        }

        function format(value, scale, modulo, padding) {
            value = Math.floor(value / scale) % modulo;
            return value.toString().padStart(padding, 0);
        }

        function render() {
            var value = paused ? offset : Date.now() + offset;

            ms = format(value, 1, 1000, 3);
            s = format(value, 1000, 60, 2);
            m = format(value, 60000, 60, 2);

            h = s + "." + ms + " Detik";
            document.querySelector('#detik_hasil').textContent = h;

            if (!paused) {
                requestAnimationFrame(render);
            }
        }

        // Enable pusher logging - don't include this in production
        // Pusher.logToConsole = true;
        alertify.set('notifier', 'position', 'bottom-center');

        user = "<?= $_SESSION['data']['user_nama']; ?>";
        var pusher = new Pusher('1a2dc3e3683ccf1e5ee7', {
            cluster: 'ap1'
        });

        var global = pusher.subscribe('global');
        global.bind('reload', function(data) {
            alertify.confirm("Reload Halaman", "Mohon maaf menganggu " + data.user + " <br> Halaman ini akan di reload, Dikarenakan ada perbaikan " + data.message + ", Jika bersedia silahkan klik OK, dan jika tidak klik Cancel dan lanjutkan pekerjaan ", function() {
                location.reload();
            }, function() {
                alertify.error('Mohon Maaf, Silahkan dilanjutkan')
            });
        });

        global.bind('index', function(data) {
            alertify.confirm("Redirect Ke Halaman Index", "Mohon maaf menganggu " + user + ",Halaman ini akan di alihkan ke halaman Index, Jika bersedia silahkan klik OK, dan jika tidak klik Cancel", function() {
                location.href = "index.php";
            }, function() {
                alertify.error('Mohon Maaf, Silahkan dilanjutkan')
            });
        });

        global.bind('logout', function(data) {
            location.href = "logout.php";
        });

        global.bind('notif', function(data) {
            alertify.alert(data.title, data.message);
        });

        global.bind('redirect', function(data) {
            alertify.confirm("Redirect", "Mohon maaf URL akan segera dimatikan, apakah ingin dialihkan ke bswmalang.com ? atau melanjutkan pekerjaan yang ada ?", function() {
                location.href = 'https://bswmalang.com/?<?= $_SERVER['HTTP_REFERER']; ?>';
            }, function() {
                alertify.error('Silahkan dilanjutkan')
            });
        });

        var logout = pusher.subscribe('<?= $_SESSION['mydata']['user_id']; ?>');
        logout.bind('user', function(data) {
            location.href = "logout.php?<?= $_SERVER['HTTP_REFERER']; ?>";
        });

        logout.bind('notif', function(data) {
            console.log(JSON.stringify(data));
            alertify.alert(data.title, data.message);
        });

        logout.bind('reload', function(data) {
            alertify.confirm("Reload Halaman", "Mohon maaf menganggu " + data.user + " <br> Halaman ini akan di reload, Dikarenakan ada perbaikan <b>" + data.message + "</b>, Jika bersedia silahkan klik OK, dan jika tidak klik Cancel dan lanjutkan pekerjaan ", function() {
                location.reload();
            }, function() {
                alertify.error('Mohon Maaf, Silahkan dilanjutkan')
            });
        });

        logout.bind('force_reload', function(data) {
            location.reload();
        });

        logout.bind('redirect', function(data) {
            alertify.confirm("Redirect", "Mohon maaf URL akan segera dimatikan, apakah ingin dialihkan ke bswmalang.com ? atau melanjutkan pekerjaan yang ada ?", function() {
                location.href = 'https://bswmalang.com/?<?= $_SERVER['HTTP_REFERER']; ?>';
            }, function() {
                alertify.error('Silahkan dilanjutkan')
            });
        });

        logout.bind('index', function(data) {
            location.href = "index.php";
        });

        logout.bind('chat_notif', function(data) {
            // location.href="logout.php";
            var notification = new Notification(data.judul, {
                icon: 'https://www.bswmalang.com/login/assets/img/icon/favicon-96x96.png',
                body: data.message,
            });

            notification.onclick = function() {
                alert(data.message)
            };

            data1 = JSON.stringify(data);
            $html = ""
            var msg = alertify.warning("<b style='text-align:left'>" + data.message + "</b>", "5");
            // toastr.error(data.message,data.judul)

            // msg.callback = function (isClicked) {
            //     if(isClicked){
            //       console.log('notification dismissed by user');
            //       // update_notifikasi(user,result.idNotif)
            //       alert(data.message)

            //     }else{
            //       console.log('notification auto-dismissed');
            //       // update_notifikasi(user,result.idNotif)
            //     }
            // };
            console.log(JSON.stringify(data));

            // html = '<fieldset style="margin-bottom:5px;border:1px solid grey;padding:5px;">'
            // html += '<legend style="font-size:14px;margin:1px;width:auto;border:0px;padding:2px;">'+data.judul+' ('+data.jam+')</legend>'
            // html += '<div class="col-md-9" style="padding:5px;">'+data.message+'</div>'
            // html += '<div class="col-md-3" style="padding:5px;">'
            // html += '<div class="btn-group btn-group-vertical" style="width:100%;">'
            // html += '<button class="btn btn-primary btn-xs" onclick="MarkRead('+data.id_user+')">Mark As Read</button>'
            // html += '<button class="btn btn-default btn-xs" onclick="readMore('+data.id_user+')">Balas</button>'
            // html += '<button class="btn btn-danger btn-xs" onclick="Hapus('+data.id_user+')">Hapus</button>'
            // html += '</div>'
            // $("#data-notif").append(html);
        });

        // channel.bind('global', function(data) {
        //   alertify.set('notifier','position', 'bottom-left');
        //   data1 = JSON.stringify(data);
        //   $html = ""
        //   var msg = alertify.warning("<b style='text-align:left'>"+data.message+"</b>", "5");
        //   msg.callback = function (isClicked) {
        //       if(isClicked){
        //         console.log('notification dismissed by user');
        //         // update_notifikasi(user,result.idNotif)
        //       }else{
        //         console.log('notification auto-dismissed');
        //         // update_notifikasi(user,result.idNotif)
        //       }
        //   };
        //   console.log(JSON.stringify(data));

        //   var notification = new Notification(data.judul, {
        //       icon: 'https://www.bswmalang.com/login/assets/img/icon/favicon-96x96.png',
        //       body: data.message,
        //   });
        //   notification.onclick = function() {
        //     // alert(result.message)
        //   };
        // });
    </script>
    <link rel="stylesheet" href="//cdn.jsdelivr.net/npm/alertifyjs@1.13.1/build/css/alertify.min.css" />
    <!-- Icons -->
    <!-- The following icons can be replaced with your own, they are used by desktop and mobile browsers -->
    <link rel="icon" type="image/png" sizes="192x192" href="<?= $template['base']; ?>/login/assets/img/icon/android-icon-192x192.png">
    <link rel="icon" type="image/png" sizes="32x32" href="<?= $template['base']; ?>/login/assets/img/icon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="96x96" href="<?= $template['base']; ?>/login/assets/img/icon/favicon-96x96.png">
    <link rel="icon" type="image/png" sizes="16x16" href="<?= $template['base']; ?>/login/assets/img/icon/favicon-16x16.png">
    <!-- <link rel="shortcut icon" href="https://ssl.gstatic.com/ui/v1/icons/mail/images/favicon5.ico" type="image/x-icon"> -->
    <link rel="apple-touch-icon" href="<?= $template['base']; ?>/img/icon57.png" sizes="57x57">
    <link rel="apple-touch-icon" href="<?= $template['base']; ?>/img/icon72.png" sizes="72x72">
    <link rel="apple-touch-icon" href="<?= $template['base']; ?>/img/icon76.png" sizes="76x76">
    <link rel="apple-touch-icon" href="<?= $template['base']; ?>/img/icon114.png" sizes="114x114">
    <link rel="apple-touch-icon" href="<?= $template['base']; ?>/img/icon120.png" sizes="120x120">
    <link rel="apple-touch-icon" href="<?= $template['base']; ?>/img/icon144.png" sizes="144x144">
    <link rel="apple-touch-icon" href="<?= $template['base']; ?>/img/icon152.png" sizes="152x152">
    <!-- END Icons -->

    <!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.17.1/moment-with-locales.min.js"></script> -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.17.1/moment.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.17.1/locale/id.js"></script>
    <!-- Stylesheets -->
    <!-- Bootstrap is included in its original form, unaltered -->

    <link rel="stylesheet" href="<?= $template['base']; ?>/css/bootstrap.css">
    <link href="//netdna.bootstrapcdn.com/bootstrap/3.0.0/css/bootstrap-glyphicons.css" rel="stylesheet">


    <!-- Related styles of various javascript plugins -->
    <link rel="stylesheet" href="https://mydatabase.id/appbsw/css/plugins.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.3.0/Chart.bundle.js"></script>
    <link rel="stylesheet" href="https://cdn.datatables.net/fixedheader/3.1.5/css/fixedHeader.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/select/1.2.7/css/select.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/colreorder/1.5.2/css/colReorder.dataTables.min.css">
    <!-- The main stylesheet of this template. All Bootstrap overwrites are defined in here -->
    <link rel="stylesheet" href="https://mydatabase.id/appbsw/css/main.css">

    <!-- Load a specific file here from css/themes/ folder to alter the default theme of the template -->
    <?php if ($template['theme']) { ?>
        <link id="theme-link" rel="stylesheet" href="<?= $template['base']; ?>/css/themes/<?php echo $template['theme']; ?>.css">
    <?php } ?>

    <!-- The themes stylesheet of this template (for using specific theme color in individual elements - must included last) -->
    <link rel="stylesheet" href="<?= $template['base']; ?>/css/themes.css">
    <!-- END Stylesheets -->

    <script src="<?= $template['base']; ?>/js/vendor/modernizr-respond.min.js"></script>
    <!--<script src="<?= $template['base']; ?>/js/simple-mask-money.js"></script>-->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Modernizr (browser feature detection library) & Respond.js (Enable responsive CSS code on browsers that don't support it, eg IE8) -->
    <script src="<?= $template['base']; ?>/js/vendor/modernizr-respond.min.js"></script>

    <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.3/jquery-ui.min.js">
    </script>
</head>

<!-- Add the class .fixed to <body> for a fixed layout on large resolutions (min: 1200px) -->
<!-- <body class="fixed"> -->
<title>DATA EKSPOR</title>


<body>
    <?php
    $halaman = (isset($_GET['halaman'])) ? $_GET['halaman'] : 0;
    ?>
    <div class="col-md-12" style="height:160px;width:100%;background: url('../img/sasa2.png');">
        <div class="col-md-2 col-md-offset-5" style="height:40px;background-color:#758C48;border: 2px solid bisque;margin-top:115px;padding:0px;">
            <center><a style="color: white;text-align:center;font-size:25px;font-family:'Times New Roman';" href="ekspor.php">DATA EKSPOR </a></center>
        </div>
    </div>
    <div class="col-md-12" style="height:37px;border: 2px solid bisque;background: #666;padding:0px;">
        <table width="100%" style="margin-top: 5px;">
            <tr>
                <td class="tl" width="121px">
                    <button class="button" style="width: 100%;" id="realisasi_stuffing" onclick="go_to('realisasi_stuffing')"> <img src="https://bswmalang.com/img/tables.png" width="15" style="margin-right: 5px;"> REALISASI STUFFING</button>
                </td>
                <td class="tl" width="129px">
                    <button class="button" style="width: 100%;" id="realisasi_dok" onclick="go_to('realisasi_dok')"> <img src="https://bswmalang.com/img/tables.png" width="15" style="margin-right: 5px;">REALISASI SEQUENTIAL</button>
                </td>
                <td class="tl" width="97px">
                    <button class="button" style="width: 100%;" id="sch_bsw" onclick="go_to('sch_bsw')"> <img src="https://bswmalang.com/img/tables.png" width="15" style="margin-right: 5px;">SCHEDULE BSW</button>
                </td>
                <td class="tl" width="92px">
                    <button class="button" style="width: 100%;" id="blc_order" onclick="go_to('blc_order')"> <img src="https://bswmalang.com/img/tables.png" width="15" style="margin-right: 5px;">BLC BY SEASON</button>
                </td>
                <td class="tl" width="61px">
                    <button class="button" style="width: 100%;" id="list_pfi" onclick="go_to('list_pfi')"> <img src="https://bswmalang.com/img/tables.png" width="15" style="margin-right: 5px;">LIST PFI</button>
                </td>
                <td class="tl" width="145px">
                    <button class="button" style="width: 100%;" id="kirim_item" onclick="go_to('kirim_item')"> <img src="https://bswmalang.com/img/tables.png" width="15" style="margin-right: 2px;">PENGIRIMAN PER ITEM : SEASON</button>
                </td>
                <td class="tl" width="132px">
                    <button class="button" style="width: 100%;" id="kirim_item_bln" onclick="go_to('kirim_item_bln')"> <img src="https://bswmalang.com/img/tables.png" width="15" style="margin-right: 2px;">PENGIRIMAN PER ITEM : BLN</button>
                </td>
                <td class="tl tr" width="100px">
                    <button class="button" style="width: 100%;" onclick="location.href = 'AppRekap.php'"> <img src="https://bswmalang.com/img/utama.png" width="15" style="margin-right: 2px;"> MENU UTAMA</button>
                </td>
            </tr>
        </table>
    </div>
    <!-- Page Container -->
    <div id="page-container" style="background:white !important;">
        <!-- Header -->
        <!-- Add the class .navbar-fixed-top or .navbar-fixed-bottom for a fixed header on top or bottom respectively -->
        <!-- <header class="navbar navbar-inverse navbar-fixed-top"> -->
        <!-- <header class="navbar navbar-inverse navbar-fixed-bottom"> -->
        <!-- <table width="100%" border="0" id="headertable" style="background:white;">
              <tr height="70px">
                <td class="text-center" width="100px"><img src="./img/data/logoBsw.png" alt="" width="90" height="65"></td>
                <td style="font-weight:bold;font-size:28px;" valign="middle">APLIKASI PENGOLAHAN DATA BSW</td>
              </tr>
            </table> -->
        <link rel="stylesheet" href="https://netdna.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css">
        <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bs/jq-3.3.1/jszip-2.5.0/dt-1.10.24/af-2.3.6/b-1.7.0/b-colvis-1.7.0/b-html5-1.7.0/b-print-1.7.0/cr-1.5.3/date-1.0.3/fc-3.3.2/fh-3.1.8/kt-2.6.1/r-2.2.7/rg-1.1.2/rr-1.2.7/sc-2.0.3/sb-1.0.1/sp-1.2.2/sl-1.3.3/datatables.min.css" />

        <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/pdfmake.min.js"></script>
        <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/vfs_fonts.js"></script>
        <script type="text/javascript" src="https://cdn.datatables.net/v/bs/jszip-2.5.0/dt-1.10.23/af-2.3.5/b-1.6.5/b-colvis-1.6.5/b-flash-1.6.5/b-html5-1.6.5/b-print-1.6.5/cr-1.5.3/fc-3.3.2/fh-3.1.7/kt-2.5.3/r-2.2.7/rg-1.1.2/rr-1.2.7/sc-2.0.3/sb-1.0.1/sp-1.2.2/sl-1.3.1/datatables.min.js"></script>

        <?php
        error_reporting(0);
        include "fungsi.php";
        include "koneksi2.php";
        $cari_data = isset($_GET['cari']) ? $_GET['cari'] : '';

        ?>
        <style>
            .button {
                padding: 3px 10px;
                font-size: 12px;
                text-align: center;
                cursor: pointer;
                outline: none;
                color: #fff;
                background: #ba471a;
                border: none;
                border-radius: 3px;
            }

            .button:hover {
                background-color: #0088ff
            }

            .active {
                background-color: black;
                color: white;
            }

            .button:active {
                background-color: #0088ff;
                box-shadow: 0 3px #666;
                transform: translateY(4px);
            }

            @media (max-width:1024px) {
                .button {
                    padding: 75px 100px 100px
                }

                .button:active {
                    margin-bottom: 60px
                }
            }

            @media (max-width:758px) {
                .button {
                    padding: 45px 35px 60px
                }

                .button:hover {
                    margin-bottom: 40px
                }
            }

            @media (max-width:767px) {
                .button {
                    padding: 35px 30px 50px
                }

                .button:hover {
                    margin-bottom: 30px
                }

                .button:active {
                    font-size: 30px;
                    margin-bottom: 0
                }

                .active {
                    margin-top: 6px
                }
            }

            @media (max-width:575px) {
                .button {
                    padding: 26px 20px 40px
                }

                .button:hover {
                    text-align: left
                }
            }
        </style>

        </script>