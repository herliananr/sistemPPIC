<?php
    include("layout/session.php");
?>

<?php
    include("layout/koneksi.php");

    // cek apakah form telah di submit
    if (isset($_POST["submit"])) {
        // form telah disubmit, proses data
        $inputID = htmlentities(strip_tags(trim($_POST["inputID"])));
        $inputIDProduk = htmlentities(strip_tags(trim($_POST["inputIDProduk"])));
        $inputTanggalProduksi = htmlentities(strip_tags(trim($_POST["inputTanggalProduksi"])));
        $inputJumlahProduksi = htmlentities(strip_tags(trim($_POST["inputJumlahProduksi"])));
        $inputUOM = htmlentities(strip_tags(trim($_POST["inputUOM"])));

        //menyiapkan variabel untuk pesan error
        $pesan="";
        $warna="";


        if ($pesan==="") {
            //jalankan query insert
            $query= "select*from pengendalian_produksi where id_produksi='$inputID'";
            $result= mysqli_query($link, $query);
            $hasil = mysqli_fetch_assoc($result);

            if ($hasil['status_produksi']=="Belum diproduksi") {
                $query2= "update pengendalian_produksi set status_produksi='Sedang diproses' where id_produksi='$inputID'";
                $result2= mysqli_query($link, $query2);
                $pesandikirim .="Produksi dengan ID Produksi $inputID sedang diproses.";
            }
            else {
                $query2= "update pengendalian_produksi set status_produksi='Selesai diproduksi' where id_produksi='$inputID'";
                $result2= mysqli_query($link, $query2);

                $query3= "update stok_produk set stok=stok+$inputJumlahProduksi where id_produk='$inputIDProduk'";
                $result3= mysqli_query($link, $query3);

                $idprodkeluar='OUT'.$inputID;
                $tanggalterkini= date("Y-m-d");

                $queryhistoriproduk = "insert into produk_masuk values('$idprodkeluar','$tanggalterkini',
                '$inputIDProduk',$inputJumlahProduksi,'$inputUOM','$inputID')";
                $resulthistoriproduk = mysqli_query($link, $queryhistoriproduk);

                $pesandikirim .="Produksi dengan ID Produksi $inputID selesai diproduksi.";
            }

            if ($result2) {               
                $pesan_dikirim = urlencode($pesandikirim);
                header("Location: pengendalian_produksi.php?pesandikirim=$pesan_dikirim");
                die();
            }
            else {
                die ("Query gagal dijalankan: ".mysqli_errno($link).
                " - ".mysqli_error($link));
            }
        }
    }

    else {  
        $diterima_id=$_GET['id_produksi'];

        $query = "SELECT a.id_produksi as id_produksi, b.id_produk as id_produk, b.tanggal_produksi as tanggal_produksi, 
        b.jumlah_produksi as jumlah_produksi, b.uom as uom from pengendalian_produksi a, schedule_produksi b 
        where a.id_produksi=b.id_produksi and a.id_produksi='$diterima_id'";
        $result = mysqli_query($link, $query);
        $hasil = mysqli_fetch_assoc($result);

        $inputID=$hasil['id_produksi'];
        $inputIDProduk=$hasil['id_produk'];
        $inputTanggalProduksi=$hasil['tanggal_produksi'];
        $inputJumlahProduksi=$hasil['jumlah_produksi'];
        $inputUOM=$hasil['uom'];
        $pesan="";
        $isi_select="";
        $warna="";
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
    <title>Pengendalian Produksi</title>
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
                        if ($pesan !== "") {
                            if ($warna=="hijau") {
                                echo "<div class=\"col-md-12 col-sm-12 alert alert-success alert-dismissible fade show notifikasiperingatan\">$pesan<button type=\"button\" class=\"close\" data-dismiss=\"alert\">
                                <span>&times;</span></button></div>";  
                            }
                            else {
                                echo "<div class=\"col-md-12 col-sm-12 alert alert-danger alert-dismissible fade show notifikasiperingatan\">$pesan<button type=\"button\" class=\"close\" data-dismiss=\"alert\">
                                <span>&times;</span></button></div>";  
                            }

                        }
                    ?>

                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Jumlah Produksi</h6>
                        </div>
                        <div class="card-body">
                            <form action="statusprod_pengendalian_produksi.php" class="col-10 offset-1" method="post">
                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label" for="inputID">ID Produksi</label>
                                    <div class="col-sm-10">
                                    <input type="text" class="form-control" name="inputID" id="inputID" readonly value="<?php echo $inputID ?>">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label" for="inputIDProduk">ID Produk</label>
                                    <div class="col-sm-10">
                                    <input type="text" class="form-control" name="inputIDProduk" id="inputIDProduk" readonly value="<?php echo $inputIDProduk ?>">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label" for="inputTanggalProduksi">Tanggal Produksi</label>
                                    <div class="col-sm-10">
                                    <input type="text" class="form-control" name="inputTanggalProduksi" id="inputTanggalProduksi" readonly value="<?php echo $inputTanggalProduksi ?>">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label" for="inputJumlahProduksi">Jumlah Produksi</label>
                                    <div class="col-sm-10">
                                    <input type="text" class="form-control" name="inputJumlahProduksi" id="inputJumlahProduksi" readonly value="<?php echo $inputJumlahProduksi ?>">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label" for="inputUOM">UOM</label>
                                    <div class="col-sm-10">
                                    <input type="text" class="form-control" name="inputUOM" id="inputUOM" readonly value="<?php echo $inputUOM ?>">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <div class="col">                                   
                                        <button class="btn btn-primary mt-3 mb-3" type="submit" name="submit">Proses Produksi</button>
                                    </div>
                                </div>
                                
                            </form>
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

    <!--Untuk tanggal-->
        <script type="text/javascript">
            $function () {
                $('.datepicker').datepicker({
                    startDate: '-3d';
                });

            };
        </script>
</body>

</html>