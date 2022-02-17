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
    <title>Data Bill of Material</title>
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

                    <!-- DataTales Example -->
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Data Bill of Material</h6>
                        </div>
                        <div class="card-body">
                            <div><a href="tambah_data_bom.php" style="<?php echo $ppicnone?>" class="btn btn-primary btn-icon-split mb-4">
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
                                            <th>ID Produk</th>
                                            <th>Nama Produk</th>
                                            <th>Cetak (Aktual)</th>
                                            <th>Cetak (Penawaran)</th>
                                            <th>Rincian</th>
                                            <th style="<?php echo $ppicnone?>">Edit</th>
                                            <th style="<?php echo $ppicnone?>">Hapus</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                            include ("layout/koneksi.php");
                                            $query = "SELECT distinct id_produk_bom, nama_produk from bom";
                                            $nomor=1;
                                            $result = mysqli_query($link,$query);

                                            while($hasil = mysqli_fetch_assoc($result)){
                                                echo "<tr>";
                                                echo "<td>$nomor</td>";
                                                echo "<td>$hasil[id_produk_bom]</td>";
                                                echo "<td>$hasil[nama_produk]</td>";

                                                $pengantar=urlencode($hasil['id_produk_bom']);
                                                $pengantardua=urlencode($hasil['nama_produk']);
                                                
                                                echo "<td><a href=\"cetak_data_bom.php?id_produk_bom=$pengantar&nama_produk=$pengantardua&ket=aktual\" class=\"btn btn-success\" target=\"_BLANK\"><i class=\"fas fa-print\"></i></a></td>";
                                                echo "<td><a href=\"cetak_data_bom.php?id_produk_bom=$pengantar&nama_produk=$pengantardua&ket=penawaran\" class=\"btn btn-success\" target=\"_BLANK\"><i class=\"fas fa-print\"></i></a></td>";
                                                echo "<td><a href=\"lihat_data_bom.php?id_produk_bom=$pengantar\" class=\"btn btn-info\">Lihat</a></td>";
                                                echo "<td style=\"$ppicnone\"><a href=\"edit_data_bom.php?id_produk_bom=$pengantar\" class=\"btn btn-warning\">Edit</a></td>";
                                                echo "<td style=\"$ppicnone\"><a href=\"hapus_data_bom.php?id_produk_bom=$pengantar\" class=\"btn btn-danger\">Hapus</a></td>";
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