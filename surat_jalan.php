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
    <title>Surat Jalan</title>
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
                            <h6 class="m-0 font-weight-bold text-primary">Surat Jalan</h6>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div><a href="tambah_surat_jalan.php" style="<?php echo $warehousenone?>" class="btn btn-primary btn-icon-split mb-4">
                                    <span class="icon text-white-50">
                                        <i class="fas fa-plus-circle"></i>
                                    </span>
                                    <span class="text">Tambah Data</span>
                                    </a>
                                </div>

                                <div><a href="daftar_bahan_baku_keluar.php" class="btn btn-primary mb-4 ml-2">
                                    <span class="text">SJ Bahan Baku</span>
                                    </a>
                                </div>
                            </div>
                            
                            <div class="table-responsive">
                                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>ID SJ</th>
                                            <th>Tanggal Pengiriman</th>
                                            <th>ID PO</th>
                                            <th style="<?php echo $warehousenone?>">Cetak</th>
                                            <th>Produk</th>
                                            <th>Lihat</th>
                                            <th style="<?php echo $warehousenone?>">Hapus</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                            include("layout/koneksi.php");
                                            $query = "SELECT distinct id_surat_jalan, tanggal_pengiriman, id_po FROM surat_jalan";
                                            $nomor=1;
                                            $result = mysqli_query($link,$query);
                                            while($hasil = mysqli_fetch_assoc($result)){
                                                echo "<tr>";
                                                echo "<td>$nomor</td>";
                                                echo "<td>$hasil[id_surat_jalan]</td>";
                                                echo "<td>$hasil[tanggal_pengiriman]</td>";
                                                echo "<td>$hasil[id_po]</td>";

                                                $pengantar=urlencode($hasil['id_surat_jalan']);
                                                echo "<td style=\"$warehousenone\"><a href=\"cetak_surat_jalan.php?id_surat_jalan=$pengantar\" target=\"_BLANK\" class=\"btn btn-success\"><i class=\"fas fa-print\"></i></a></td>";

                                                $querycari = "select*from produk_keluar where keterangan like '%$hasil[id_surat_jalan]%'";
                                                $resultcari = mysqli_query($link, $querycari);
                                                $jumlah_data = mysqli_num_rows($resultcari);

                                                if ($jumlah_data>0) {
                                                    echo "<td><button class=\"btn btn-info\" disabled>Sudah disiapkan</button></td>";
                                                    $disabled="disabled";
                                                }
                                                else {
                                                    echo "<td><a href=\"kurangi_stok_produk.php?id_surat_jalan=$pengantar\" class=\"btn btn-info $ppicdisabled\">Belum tersedia</a></td>";
                                                    $disabled="";
                                                }
                                                echo "<td><a href=\"lihat_surat_jalan.php?id_surat_jalan=$pengantar\" class=\"btn btn-info\">Lihat</a></td>";
                                                echo "<td style=\"$warehousenone\"><a href=\"hapus_surat_jalan.php?id_surat_jalan=$pengantar\" class=\"btn btn-danger $disabled\">Hapus</a></td>";
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