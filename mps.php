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
    <title>MPS</title>
    <?php
    include ("layout/title_import.php");
    include ("layout/hak_akses.php");
                                            
    $querykar = "select * from karyawan where id_karyawan='$isi_sesi'";
    $resultkar = mysqli_query($link, $querykar);
    $hasilkar = mysqli_fetch_assoc($resultkar);
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
                            <?php
                                if ($hasilkar['peran'] == "warehouse") {
                                    echo "<h6 class=\"m-0 font-weight-bold text-primary\">Master Production Schedule (Subcont)</h6>";
                                }
                                else {
                                    echo "<h6 class=\"m-0 font-weight-bold text-primary\">Master Production Schedule</h6>";
                                }
                            ?>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div><a href="cari_id_po.php?cari_id=mps"  style="<?php echo $warehousenone?>" class="btn btn-primary btn-icon-split mb-4 ml-2">
                                    <span class="icon text-white-50">
                                        <i class="fas fa-plus-circle"></i>
                                    </span>
                                    <span class="text">Tambah Data</span>
                                    </a>
                                </div>
                                <div><a href="cari_id_po.php?cari_id=cetak_mps"  target="_BLANK" style="<?php echo $warehousenone?>" class="btn btn-success btn-icon-split mb-4 ml-2" >
                                    <span class="icon text-white-50">
                                        <i class="fas fa-download"></i>
                                    </span>
                                    <span class="text">Cetak berdasarkan PO Subcont</span>
                                    </a>
                                </div>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>ID MPS</th>
                                            <th>ID PO</th>
                                            <th>ID Produk</th>
                                            <th>Nama Produk</th>
                                            <th>Rincian</th>
                                            <th style="<?php echo $warehousenone?>">Edit</th>
                                            <th style="<?php echo $warehousenone?>">Hapus</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                            if ($hasilkar['peran'] == "warehouse") {
                                                $query = "select id_mps, id_po, id_produk, nama_produk from mps where (id_po) in (select id_po from po_pk where subcont='iya')";
                                            }
                                            else {
                                                $query = "SELECT *from mps";
                                            }

                                            
                                            $nomor=1;
                                            $result = mysqli_query($link,$query);
                                            while($hasil = mysqli_fetch_assoc($result)){
                                                echo "<tr>";
                                                echo "<td>$nomor</td>";
                                                echo "<td>$hasil[id_mps]</td>";
                                                echo "<td>$hasil[id_po]</td>";
                                                echo "<td>$hasil[id_produk]</td>";
                                                echo "<td>$hasil[nama_produk]</td>";
                                                
                                                $pengantar=urlencode($hasil['id_mps']);

                                                echo "<td><a href=\"lihat_mps.php?id_mps=$pengantar\" class=\"btn btn-info\">Lihat</a></td>";
                                                echo "<td style=\"$warehousenone\"><a href=\"edit_mps.php?id_mps=$pengantar\" class=\"btn btn-warning\">Edit</a></td>";
                                                echo "<td  style=\"$warehousenone\"><a href=\"hapus_mps.php?id_mps=$pengantar\" class=\"btn btn-danger\">Hapus</a></td>";
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