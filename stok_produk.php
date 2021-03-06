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
    <title>Stok Produk</title>
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
                            <h6 class="m-0 font-weight-bold text-primary">Stok Produk</h6>
                        </div>
                        <div class="card-body">
                            <button type="button" style="<?php echo $ppicnone?>" class="btn btn-primary mb-4" data-toggle="modal" data-target="#pilihStokProduk">Tambah Stok</button>
                            <div class="modal fade" id="pilihStokProduk" tabindex="-1" role="dialog" aria-labelledby="pilihStokProduk"
                                aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="pilihStokProduk">Peringatan</h5>
                                            <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">??</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <p>Apakah anda ingin melakukan penambahan stok produk? </p>
                                            <p>Anda hanya bisa memasukkan stok produk yang berasal dari subcont.</p></div>
                                        <div class="modal-footer">
                                            <button class="btn btn-secondary" type="button" data-dismiss="modal">Tidak</button>
                                            <a class="btn btn-primary" href="cari_id_po.php?cari_id=tambah_stok_produk">Iya</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>ID Produk</th>
                                            <th>Nama Produk</th>
                                            <th>Stok</th>
                                            <th>UOM</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                            include("layout/koneksi.php");
                                            
                                            $query = "SELECT a.id_produk as id_produk, b.nama_produk as nama_produk, a.uom as uom, a.stok as stok from stok_produk a, produk b where a.id_produk=b.id_produk";
                                            $nomor=1;
                                            $result = mysqli_query($link,$query);
                                            while($hasil = mysqli_fetch_assoc($result)){
                                                echo "<tr>";
                                                echo "<td>$nomor</td>";
                                                echo "<td>$hasil[id_produk]</td>";
                                                echo "<td>$hasil[nama_produk]</td>";
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