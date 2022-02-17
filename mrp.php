<?php
include("layout/session.php");
?>

<?php
    $pesan_diterima="";
    $pesan_diterima_gagal="";
    if (isset($_GET["pesandikirim"])) {
        $pesan_diterima = $_GET["pesandikirim"];
    }

    if (isset($_GET["gagal"])) {
        $pesan_diterima_gagal = $_GET["gagal"];
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
    <title>MRP</title>
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

                <?php
                         // tampilkan error jika ada
                        if ($pesan_diterima !== "") {
                            if ($pesan_diterima_gagal=="iya") {
                                echo "<div class=\"col-md-12 col-sm-12 alert alert-danger alert-dismissible fade show notifikasiperingatan\">$pesan_diterima<button type=\"button\" class=\"close\" data-dismiss=\"alert\">
                                <span>&times;</span></button></div>"; 
                            }
                            else {
                                echo "<div class=\"col-md-12 col-sm-12 alert alert-success alert-dismissible fade show notifikasiperingatan\">$pesan_diterima<button type=\"button\" class=\"close\" data-dismiss=\"alert\">
                                <span>&times;</span></button></div>"; 
                            }
                        }
                    ?>

                    <!-- DataTales Example -->
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">MRP</h6>
                        </div>
                        <div class="card-body">
                            <div><a href="tambah_mrp.php" class="btn btn-primary btn-icon-split mb-4">
                                <span class="icon text-white-50">
                                    <i class="fas fa-plus-circle"></i>
                                </span>
                                <span class="text">Tambah Data</span>
                                </a>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>ID MRP</th>
                                            <th>ID MPS</th>
                                            <th>ID BOM</th>
                                            <th>PO</th>
                                            <th>Cetak</th>
                                            <th>Lihat</th>
                                            <th>Hapus</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                            include("layout/koneksi.php");
                                            
                                            $query = "SELECT distinct id_mrp, id_mps, id_bom FROM mrp";
                                            $nomor=1;
                                            $result = mysqli_query($link,$query);
                                            while($hasil = mysqli_fetch_assoc($result)){
                                                echo "<tr>";
                                                echo "<td>$nomor</td>";
                                                echo "<td>$hasil[id_mrp]</td>";
                                                echo "<td>$hasil[id_mps]</td>";
                                                echo "<td>$hasil[id_bom]</td>";

                                                $query= mysqli_query($link, "select *from purchase_order where id_mrp='$hasil[id_mrp]'");
                                                $jumlah=mysqli_num_rows($query);

                                                $pengantar=urlencode($hasil['id_mrp']);
                                                if ($jumlah>=1) {
                                                    echo "<td><a href=\"tambah_purchase_order.php?id_mrp=$pengantar\" class=\"btn btn-warning disabled\">Buat</a></td>";
                                                }
                                                else {
                                                    echo "<td><a href=\"tambah_purchase_order.php?id_mrp=$pengantar\" class=\"btn btn-warning\">Buat</a></td>";
                                                }

                                                echo "<td><a href=\"cetak_mrp.php?id_mrp=$pengantar\" target=\"_BLANK\" class=\"btn btn-success\"><i class=\"fas fa-print\"></i></a></td>";
                                                echo "<td><a href=\"lihat_mrp.php?id_mrp=$pengantar\" class=\"btn btn-info\">Lihat</a></td>";
                                                echo "<td><a href=\"hapus_mrp.php?id_mrp=$pengantar\" class=\"btn btn-danger\">Hapus</a></td>";
                                                echo "</tr>";
                                                $nomor++;

                                            };
                                        ?>
                                    </tbody>
                                </table>
                            </div>
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