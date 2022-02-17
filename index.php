<?php
include("layout/session.php");
?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>Halaman Utama</title>

    <style>
            /*-- SHOWCASE --*/
        #showcase{
            background: url('img/gulungan_kain.jpg');
            height: 490px; 
            background-size: cover; /*--cover, agar background memenuhi seluruh showcase*/
            background-attachment: fixed; /*--posisi background akan tetap (fixed), sehingga terjadi efek yang menarik ketika di scroll--*/
            background-repeat: no-repeat; /*--background tidak berulang--*/
        }
        #showcase h1{
            padding-top: 242px; /*--untuk tulisan sistem informasi ppic--*/
        }

        .dark-overlay{
            background-color: rgba(0, 0, 0, 0.5);
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            min-height: 490px;
        }

    </style>
    <?php
    include ("layout/title_import.php");
    ?>

</head>

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">

        <?php
        include ("layout/sidebar.php");
        ?>

    

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

            <?php
            include ("layout/topbar.php");
            ?>
                <!-- Begin Page Content -->
                <div class="container-fluid">

                    <!-- Content Row -->
                    <div class="row">

                        <!-- SHOWCASE -->
                        <section id="showcase" class="col-sm-12 mb-3">
                            <div class="dark-overlay">
                                <div class="container">
                                    <div class="row">
                                        <div class="col text-center text-white">
                                            <h1 class="display-4">Sistem Informasi PPIC</strong></h1>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </section>
                    </div>

                </div>
                <!-- /.container-fluid -->

            </div>
            <!-- End of Main Content -->

    
            <?php
            include ("layout/footer.php");
            ?>
        </div>
        <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->

    <?php
    include ("layout/plugin_and_logout_modal.php")
    ?>

</body>

</html>