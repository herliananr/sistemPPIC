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
    <title>Stok Bahan Baku</title>
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
                            <h6 class="m-0 font-weight-bold text-primary">Stok Bahan Baku</h6>
                        </div>
                        <div class="card-body">
 
                            <div style="<?php echo $ppicnone?>" class="dropdown mb-4 dropright float-left">
                                <button class="btn btn-primary dropdown-toggle" type="button" id="dropdownStokBarang" data-toggle="dropdown" aria-haspopup="true"
                                    aria-expanded="false">Update Stok
                                </button>
                                <div class="dropdown-menu animated--fade-in" aria-labelledby="dropdownStokBarang">
                                    <a class="dropdown-item" href="tambah_stok_bahan_baku.php"><i class="fas fa-fw fa-plus-circle"></i>  Tambah Stok</a>
                                    <a class="dropdown-item" data-toggle="modal" data-target="#piliStokBahanBaku"><i class="fas fa-fw fa-minus-circle"></i>  Kurangi Stok</a>
                                </div>
                            </div>

                            
                                    <div class="modal fade" id="piliStokBahanBaku" tabindex="-1" role="dialog" aria-labelledby="piliStokBahanBaku"
                                        aria-hidden="true">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="piliStokBahanBaku">Peringatan</h5>
                                                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">×</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    <p>Apakah anda ingin melakukan pengurangan stok bahan baku? </p>
                                                    <p>Anda hanya bisa mengurangi stok untuk keperluan subcont.</p></div>
                                                <div class="modal-footer">
                                                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Tidak</button>
                                                    <a class="btn btn-primary" href="kurangi_stok_bahan_baku.php?subcont=produk&warna=netral">Iya, subcont produk</a>
                                                    <a class="btn btn-primary" href="kurangi_stok_bahan_baku.php?subcont=bahanbaku&warna=netral">Iya, subcont bahan baku</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                            <div class="table-responsive">
                                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>ID Bahan Baku</th>
                                            <th>Nama Bahan Baku</th>
                                            <th>Stok</th>
                                            <th>UOM</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                            include("layout/koneksi.php");
                                            
                                            $query = "SELECT a.id_bahan_baku as id_bahan_baku, b.nama_bahan_baku as nama_bahan_baku, a.uom as uom, a.stok as stok from stok_bahan_baku a, bahan_baku b where a.id_bahan_baku=b.id_bahan_baku";
                                            $nomor=1;
                                            $result = mysqli_query($link,$query);
                                            while($hasil = mysqli_fetch_assoc($result)){
                                                echo "<tr>";
                                                echo "<td>$nomor</td>";
                                                echo "<td>$hasil[id_bahan_baku]</td>";
                                                echo "<td>$hasil[nama_bahan_baku]</td>";
                                                echo "<td>$hasil[stok]</td>";
                                                echo "<td>$hasil[uom]</td>";
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