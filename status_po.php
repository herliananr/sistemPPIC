<?php
include("layout/session.php");
?>

<?php
    $pesan_diterima="";

    if (isset($_GET["pesandikirim"])) {
        $pesan_diterima = $_GET["pesandikirim"];
    }

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>Data PO Customer</title>
    <?php
    include ("layout/title_import.php");
    include ("layout/hak_akses.php");
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

                    <?php
                         // tampilkan error jika ada
                        if ($pesan_diterima !== "") {
                                echo "<div class=\"col-md-12 col-sm-12 alert alert-success alert-dismissible fade show notifikasiperingatan\">$pesan_diterima<button type=\"button\" class=\"close\" data-dismiss=\"alert\">
                                <span>&times;</span></button></div>";  
                        }
                    ?>

                    <!-- Project Card Example -->
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Status PO Customer</h6>
                        </div>
                        
                        <div class="card-body">
                            <?php
                                include("layout/koneksi.php"); 

                                $query = "select id_po, sum(qty) as qty from po group by id_po";
                                $result = mysqli_query($link, $query);
                                while ($hasil = mysqli_fetch_assoc($result)){
                                    $keterangan = $hasil['id_po'].", LIP-";
                                    $querycari = "select sum(qty) as qty from produk_keluar where keterangan like '$keterangan%'";
                                    $resultcari = mysqli_query($link, $querycari);
                                    $hasilcari = mysqli_fetch_assoc($resultcari);
                                    $jumlahcari = mysqli_num_rows($resultcari);

                                    if ($jumlahcari>0) {
                                        $sudahdikerjakan = $hasilcari['qty'];
                                    }
                                    else {
                                        $sudahdikerjakan = 0;
                                    }

                                    $nilai = ($sudahdikerjakan/$hasil['qty']) *100;

                                    echo "<h4 class=\"small font-weight-bold\">$hasil[id_po] <span class=\"float-right\">".round($nilai,2)."%</span></h4>";
                                    echo "<div class=\"progress mb-4\">";
                                    echo "<div class=\"progress-bar bg-success\" role=\"progressbar\" 
                                    style=\"width: $nilai%\" aria-valuenow=\"$nilai\" aria-valuemin=\"0\" aria-valuemax=\"100\"></div>";
                                    echo "</div>";
                                }
                            ?>
                        </div>
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

    <!--modal untuk hapus-->


</body>

</html>