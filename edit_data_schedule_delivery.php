<?php
    include("layout/session.php");
?>

<?php
    $pesan_diterima="";
    $pesan_diterima_id_po="";
    $pesan_diterima_id_produk="";
    $pesan_diterima_id_namaproduk="";
    $pesan_diterima_id_uomproduk="";
    $pesan_diterima_id_qtyproduk="";

    if (isset($_GET["pesandikirim"])) {
        $pesan_diterima = $_GET["pesandikirim"];
        $pesan_diterima_id_po = $_GET['id_po'];
        $pesan_diterima_id_produk = $_GET['id_produk'];
        $pesan_diterima_id_namaproduk = $_GET['nama_produk'];
        $pesan_diterima_id_uomproduk = $_GET['uom_produk'];
        $pesan_diterima_id_qtyproduk= $_GET['qty_produk'];;
    }

?>

<?php
    include("layout/koneksi.php");

    // cek apakah form telah di submit
    if (isset($_POST["submit"])) {
        // form telah disubmit, proses data
        
        // ambil semua nilai form

        $inputID = htmlentities(strip_tags(trim($_POST["inputID"])));
        $inputIDProduk = htmlentities(strip_tags(trim($_POST["inputIDProduk"])));
        $inputNamaProduk = htmlentities(strip_tags(trim($_POST["inputNamaProduk"])));
        $inputTanggalPengiriman = htmlentities(strip_tags(trim($_POST["inputTanggalPengiriman"])));
        $inputQtyProduk = htmlentities(strip_tags(trim($_POST["inputQtyProduk"])));
        $inputUOM = htmlentities(strip_tags(trim($_POST["inputUOM"])));
        $inputQty = htmlentities(strip_tags(trim($_POST["inputQty"])));

        //menyiapkan variabel untuk pesan error
        $pesan="";
        $warna="";


        $query = "SELECT * FROM schedule_delivery WHERE id_po='$inputID' and id_produk='$inputIDProduk' and tanggal_pengiriman='$inputTanggalPengiriman'";
        $result = mysqli_query($link, $query);
        $jumlah_data = mysqli_num_rows($result);

        $query2 = "SELECT sum(qty) as qty from schedule_delivery where id_po='$inputID' and id_produk='$inputIDProduk'";
        $result2 = mysqli_query($link, $query2);
        $hasil = mysqli_fetch_assoc($result2);
        $totalsemuaqty = (int)$hasil['qty'] + (int)$inputQty;

        if ($inputIDProduk=="0") {
            $pesan .="ID Produk harus diisi.";
            
        }
        
        else if ($inputTanggalPengiriman=="") {
            $pesan .="Tanggal Pengiriman harus diisi.";
        }

        else if ($jumlah_data >= 1 ) {
            $pesan .= "Tanggal yang sama sudah digunakan. ";
        }

        else if ($inputQty=="") {
            $pesan .="Qty harus diisi.";
        }

        else if ($totalsemuaqty > $inputQtyProduk) {
            $pesan .="Total Qty keseluruhan untuk schedule delivery tidak boleh melebihi nilai Qty Produk.";
        }

        if ($pesan==="") {
            //jalankan query insert
            $query = "insert into schedule_delivery values ('$inputID','$inputIDProduk','$inputNamaProduk',' ','$inputTanggalPengiriman', '$inputQty', '$inputUOM')";
            $hasil = mysqli_query($link, $query);

            if ($hasil) {
                $pesan .="Schedule delivery berhasil ditambahkan.";
                $warna = "hijau";
            }
            else {
                die ("Query gagal dijalankan: ".mysqli_errno($link).
                " - ".mysqli_error($link));
            }
        }
    }
    else {
        if ($pesan_diterima!==""){
            $inputID=$pesan_diterima_id_po;
            $inputIDProduk=$pesan_diterima_id_produk;
            $inputNamaProduk=$pesan_diterima_id_namaproduk;
            $inputUOM=$pesan_diterima_id_uomproduk;
            $inputQtyProduk=$pesan_diterima_id_qtyproduk;      
        }
        else {
            $id_po=$_GET['id_po'];
            $id_produk=$_GET['id_produk'];

            $result=mysqli_query($link, "select * from schedule_delivery where id_po='$id_po' and id_produk='$id_produk'");
            $hasil=mysqli_fetch_assoc($result);

            $result2=mysqli_query($link, "select * from po where id_po='$id_po' and id_produk='$id_produk'");
            $hasil2=mysqli_fetch_assoc($result2);

            $inputID=$hasil['id_po'];
            $inputIDProduk=$hasil['id_produk'];
            $inputNamaProduk=$hasil['nama_produk'];
            $inputUOM=$hasil['uom'];
            $inputQtyProduk=$hasil2['qty'];
        }

        $inputTanggalPengiriman="";
        $inputQty="";

        $pesan="";
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
    <title>Data Schedule Delivery</title>
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
                                echo "<div class=\"col-md-12 col-sm-12 alert alert-success alert-dismissible fade show notifikasiperingatan\">$pesan_diterima<button type=\"button\" class=\"close\" data-dismiss=\"alert\">
                                <span>&times;</span></button></div>";  
                        }
                    ?>
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
                            <h6 class="m-0 font-weight-bold text-primary">Edit Data Schedule Delivery</h6>
                        </div>
                        <div class="card-body">
                            <form action="edit_data_schedule_delivery.php" class="col-10 offset-1" method="post">
                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label" for="inputID">ID PO</label>
                                    <div class="col-sm-10">
                                    <input type="text" class="form-control" name="inputID" id="inputID" readonly value="<?php echo $inputID ?>">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label" for="inputIDProduk">ID Produk</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" name="inputIDProduk" id="inputIDProduk" readonly value= "<?php echo $inputIDProduk?>">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label" for="inputNamaProduk">Nama Produk</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" name="inputNamaProduk" id="inputNamaProduk" readonly value="<?php echo $inputNamaProduk ?>">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label" for="inputQtyProduk">Qty Produk</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" name="inputQtyProduk" id="inputQtyProduk" readonly value="<?php echo $inputQtyProduk ?>">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label" for="inputTanggalPengiriman">Tanggal Kirim</label>
                                    <div class="col-sm-10 input-group date">
                                    <input type="text" class="form-control datepicker datetimepicker-input" name="inputTanggalPengiriman" id="inputTanggalPengiriman" data-provide="datepicker" data-date-format="yyyy-mm-dd" value="<?php echo $inputTanggalPengiriman ?>">
                                        <div class="input-group-append">
                                            <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label" for="inputQty">Qty</label>
                                    <div class="col-sm-10">
                                        <input type="number" class="form-control" name="inputQty" id="inputQty" min="0" value="<?php echo $inputQty ?>">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label" for="inputUOM">UOM</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" name="inputUOM" id="inputUOM" readonly value="<?php echo $inputUOM?>">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <div class="col-sm-1">                                   
                                        <button class="btn btn-primary mt-3 mb-3" type="submit" name="submit">Simpan</button>
                                    </div>
                                    <div class="col ml-3">
                                        <button type="button" class="btn btn-dark mt-3 mb-3" data-toggle="modal" data-target="#Selesai">Selesai</button>
                                        <div class="modal fade" id="Selesai" tabindex="-1">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">Peringatan</h5>
                                                        <button type="button" class="close" data-dismiss="modal"><span>&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <p>Apakah anda sudah selesai dan ingin keluar?</p>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Tidak</button>
                                                        <a href="data_schedule_delivery.php" class="btn btn-primary">Iya</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                            </form>


                            <div class="table-responsive">
                                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>ID PO</th>
                                            <th>ID Produk</th>
                                            <th>Nama Produk</th>
                                            <th>ID Delivery</th>
                                            <th>Tanggal Pengiriman</th>
                                            <th>Qty</th>
                                            <th>UOM</th>
                                            <th>Hapus</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                            if ($inputID=="") {
                                                
                                            }
                                            else {
                                                include("layout/koneksi.php");
                                            
                                                $query = "SELECT * from schedule_delivery where id_po='$inputID' and id_produk='$inputIDProduk'";
                                                $nomor=1;
                                                $result = mysqli_query($link,$query);
                                                while($hasil = mysqli_fetch_assoc($result)){
                                                    echo "<tr>";
                                                    echo "<td>$nomor</td>";
                                                    echo "<td>$hasil[id_po]</td>";
                                                    echo "<td>$hasil[id_produk]</td>";
                                                    echo "<td>$hasil[nama_produk]</td>";
                                                    echo "<td>$hasil[id_delv]</td>";
                                                    echo "<td>$hasil[tanggal_pengiriman]</td>";
                                                    echo "<td>$hasil[qty]</td>";
                                                    echo "<td>$hasil[uom]</td>";
                                                    
                                                    $pengantar=urlencode($hasil['id_po']);
                                                    $pengantardua=urlencode($hasil['id_delv']);
                                                    $pengantartiga=urlencode($hasil['id_produk']);
                                                    $pengantarempat=urlencode($inputQtyProduk);
                                                    echo "<td><a href=\"hapus_data_delv_sd.php?id_po=$pengantar&id_delv=$pengantardua&id_produk=$pengantartiga&qty_produk=$pengantarempat&edit=y\" class=\"btn btn-danger\">Hapus</a></td>";
                                                    echo "</tr>";
                                                    $nomor++;
    
                                                };
                                            }

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