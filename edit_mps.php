<?php
    include("layout/session.php");
?>


<?php
    include("layout/koneksi.php");

    // cek apakah form telah di submit
    if (isset($_POST["submit"])) {
        // form telah disubmit, proses data
        
        // ambil semua nilai form
        $inputIDMPS = htmlentities(strip_tags(trim($_POST["inputIDMPS"])));
        $inputIDPO = htmlentities(strip_tags(trim($_POST["inputIDPO"])));
        $inputIDProduk = htmlentities(strip_tags(trim($_POST["inputIDProduk"])));
        $inputNamaProduk = htmlentities(strip_tags(trim($_POST["inputNamaProduk"])));
        $inputUOM = htmlentities(strip_tags(trim($_POST["inputUOM"])));
        $inputQty = htmlentities(strip_tags(trim($_POST["inputQty"])));
        $inputTanggalMulaisatu = htmlentities(strip_tags(trim($_POST["inputTanggalMulaisatu"])));
        $inputTanggalMulaidua = htmlentities(strip_tags(trim($_POST["inputTanggalMulaidua"])));
        $inputTanggalMulaitiga = htmlentities(strip_tags(trim($_POST["inputTanggalMulaitiga"])));
        $inputTanggalMulaiempat = htmlentities(strip_tags(trim($_POST["inputTanggalMulaiempat"])));
        $inputTanggalSelesaisatu = htmlentities(strip_tags(trim($_POST["inputTanggalSelesaisatu"])));
        $inputTanggalSelesaidua = htmlentities(strip_tags(trim($_POST["inputTanggalSelesaidua"])));
        $inputTanggalSelesaitiga = htmlentities(strip_tags(trim($_POST["inputTanggalSelesaitiga"])));
        $inputTanggalSelesaiempat = htmlentities(strip_tags(trim($_POST["inputTanggalSelesaiempat"])));
        $inputQtysatu = htmlentities(strip_tags(trim($_POST["inputQtysatu"])));
        $inputQtydua = htmlentities(strip_tags(trim($_POST["inputQtydua"])));
        $inputQtytiga = htmlentities(strip_tags(trim($_POST["inputQtytiga"])));
        $inputQtyempat = htmlentities(strip_tags(trim($_POST["inputQtyempat"])));
        

        //menyiapkan variabel untuk pesan error
        $pesan="";

        $jumlah_qty_mps = (int)$inputQtysatu + (int)$inputQtydua + (int)$inputQtytiga + (int)$inputQtyempat;

        if ($jumlah_qty_mps<$inputQty) {
            $pesan .= "Total Qty pada MPS harus sama atau lebih besar dari Qty Produk";
        }
        else if ($jumlah_qty_mps>($inputQty+($inputQty*0.1))) {
            $penanda="iya";
            $pesan .="Total Qty pada MPS terlalu banyak sehingga sangat berpotensi terjadinya penumpukan stok";
        }

        if ($pesan==="") {
            //jalankan query insert
            $query = "update mps set tanggal_mulai_periode_1='$inputTanggalMulaisatu', tanggal_selesai_periode_1='$inputTanggalSelesaisatu', 
            tanggal_mulai_periode_2='$inputTanggalMulaidua', tanggal_selesai_periode_2='$inputTanggalSelesaidua', tanggal_mulai_periode_3='$inputTanggalMulaitiga', 
            tanggal_selesai_periode_3='$inputTanggalSelesaitiga', tanggal_mulai_periode_4='$inputTanggalMulaiempat', tanggal_selesai_periode_4='$inputTanggalSelesaiempat', 
            qty_periode_1='$inputQtysatu', qty_periode_2='$inputQtydua', qty_periode_3='$inputQtytiga', qty_periode_4='$inputQtyempat' where id_mps='$inputIDMPS'";
            $hasil = mysqli_query($link, $query);

            if ($hasil) {
                $pesandikirim .="Produk dengan ID MPS $inputIDMPS berhasil diedit.";
                $pesan_dikirim =urlencode($pesandikirim);
                header("Location: mps.php?pesandikirim=$pesan_dikirim");
                die();
            }
            else {
                die ("Query gagal dijalankan: ".mysqli_errno($link).
                " - ".mysqli_error($link));
            }
        }
    }
    else {

        $diterima_id = $_GET['id_mps'];
        
        if ($diterima_id !=="") {
            $query = "SELECT * FROM mps where id_mps='$diterima_id'";
            $result = mysqli_query($link, $query);
            $hasil = mysqli_fetch_assoc($result);
            $inputIDMPS=$hasil['id_mps'];
            $inputIDPO=$hasil['id_po'];
            $inputIDProduk=$hasil['id_produk'];
            $inputNamaProduk=$hasil['nama_produk'];
            $inputUOM=$hasil['uom'];

            $inputTanggalMulaisatu=$hasil['tanggal_mulai_periode_1'];
            $inputTanggalMulaidua=$hasil['tanggal_mulai_periode_2'];
            $inputTanggalMulaitiga=$hasil['tanggal_mulai_periode_3'];
            $inputTanggalMulaiempat=$hasil['tanggal_mulai_periode_4'];

            $inputTanggalSelesaisatu=$hasil['tanggal_selesai_periode_1'];
            $inputTanggalSelesaidua=$hasil['tanggal_selesai_periode_2'];
            $inputTanggalSelesaitiga=$hasil['tanggal_selesai_periode_3'];
            $inputTanggalSelesaiempat=$hasil['tanggal_selesai_periode_4'];

            $inputQtysatu=$hasil['qty_periode_1'];
            $inputQtydua=$hasil['qty_periode_2'];
            $inputQtytiga=$hasil['qty_periode_3'];
            $inputQtyempat=$hasil['qty_periode_4'];
            
            $query2 = "SELECT * FROM po where id_po='$hasil[id_po]' and id_produk='$hasil[id_produk]'";
            $result2 = mysqli_query($link, $query2);
            $hasil2 = mysqli_fetch_assoc($result2);
            $inputQty=$hasil2['qty'];
            
            $pesan="";

            if ($inputTanggalMulaisatu=="0000-00-00") {
                $inputTanggalMulaisatu="";
            }
            if ($inputTanggalMulaidua=="0000-00-00") {
                $inputTanggalMulaidua="";
            }
            if ($inputTanggalMulaitiga=="0000-00-00") {
                $inputTanggalMulaitiga="";
            }
            if ($inputTanggalMulaiempat=="0000-00-00") {
                $inputTanggalMulaiempat="";
            }
            if ($inputTanggalSelesaisatu=="0000-00-00") {
                $inputTanggalSelesaisatu="";
            }
            if ($inputTanggalSelesaidua=="0000-00-00") {
                $inputTanggalSelesaidua="";
            }
            if ($inputTanggalSelesaitiga=="0000-00-00") {
                $inputTanggalSelesaitiga="";
            }
            if ($inputTanggalSelesaiempat=="0000-00-00") {
                $inputTanggalSelesaiempat="";
            }

        }
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
                                echo "<div class=\"col-md-12 col-sm-12 alert alert-danger alert-dismissible fade show notifikasiperingatan\">$pesan<button type=\"button\" class=\"close\" data-dismiss=\"alert\">
                                <span>&times;</span></button></div>";         
                        }
                    ?>

                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Edit Master Production Schedule</h6>
                        </div>
                        <div class="card-body">
                            <form action="edit_mps.php" class="col-10 offset-1" method="post">
    
                            <div class="form-group row">
                                    <label class="col-sm-2 col-form-label" for="inputIDPO">ID PO</label>
                                    <div class="col-sm-10">
                                    <input type="text" class="form-control" name="inputIDPO" id="inputIDPO" readonly value="<?php echo $inputIDPO ?>">
                                    </div>
                            </div>

                            <div class="form-group row">
                                    <label class="col-sm-2 col-form-label" for="inputIDMPS">ID MPS</label>
                                    <div class="col-sm-10">
                                    <input type="text" class="form-control" name="inputIDMPS" id="inputIDMPS" readonly value="<?php echo $inputIDMPS ?>">
                                    </div>
                                </div>


                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label" for="inputIDProduk">ID Produk</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" name="inputIDProduk" id="inputIDProduk" readonly value="<?php echo $inputIDProduk ?>">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label" for="inputNamaProduk">Nama Produk</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" name="inputNamaProduk" id="inputNamaProduk" readonly value="<?php echo $inputNamaProduk?>">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label" for="inputUOM">UOM</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" name="inputUOM" id="inputUOM" readonly value="<?php echo $inputUOM?>">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label" for="inputQty">Qty Produk</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" name="inputQty" id="inputQty" readonly value="<?php echo $inputQty?>">
                                    </div>
                                </div>

                                <div class="input-group">
                                    <div class="input-group-prepend">
                                    <span class="input-group-text">Periode ke-1</span>
                                    </div>
                                    <input type="text" class="form-control" id="inputTanggalMulaisatu" name="inputTanggalMulaisatu" placeholder="Tanggal Mulai" 
                                    data-provide="datepicker" data-date-format="yyyy-mm-dd" value="<?php echo $inputTanggalMulaisatu ?>" required>
                                    <input type="text" class="form-control" id="inputTanggalSelesaisatu" name="inputTanggalSelesaisatu" placeholder="Tanggal Selesai" 
                                    data-provide="datepicker" data-date-format="yyyy-mm-dd" value="<?php echo $inputTanggalSelesaisatu ?>" required>
                                    <input type="number" class="form-control" id="inputQtysatu" name="inputQtysatu"  placeholder="Qty" min="0" value="<?php echo $inputQtysatu ?>" required>
                                </div>

                                <div class="input-group mt-2">
                                    <div class="input-group-prepend">
                                    <span class="input-group-text">Periode ke-2</span>
                                    </div>
                                    <input type="text" class="form-control" id="inputTanggalMulaidua" name="inputTanggalMulaidua" placeholder="Tanggal Mulai" 
                                    data-provide="datepicker" data-date-format="yyyy-mm-dd" value="<?php echo $inputTanggalMulaidua ?>" required>
                                    <input type="text" class="form-control" id="inputTanggalSelesaidua" name="inputTanggalSelesaidua" placeholder="Tanggal Selesai" 
                                    data-provide="datepicker" data-date-format="yyyy-mm-dd" value="<?php echo $inputTanggalSelesaidua ?>" required>
                                    <input type="number" class="form-control" id="inputQtydua" name="inputQtydua"  placeholder="Qty" min="0" value="<?php echo $inputQtydua ?>" required>
                                </div>

                                <div class="input-group  mt-2">
                                    <div class="input-group-prepend">
                                    <span class="input-group-text">Periode ke-3</span>
                                    </div>
                                    <input type="text" class="form-control" id="inputTanggalMulaitiga" name="inputTanggalMulaitiga" placeholder="Tanggal Mulai" 
                                    data-provide="datepicker" data-date-format="yyyy-mm-dd" value="<?php echo $inputTanggalMulaitiga ?>" required>
                                    <input type="text" class="form-control" id="inputTanggalSelesaitiga" name="inputTanggalSelesaitiga" placeholder="Tanggal Selesai" 
                                    data-provide="datepicker" data-date-format="yyyy-mm-dd" value="<?php echo $inputTanggalSelesaitiga ?>" required>
                                    <input type="number" class="form-control" id="inputQtytiga" name="inputQtytiga"  placeholder="Qty" min="0" value="<?php echo $inputQtytiga ?>" required>
                                </div>

                                <div class="input-group  mt-2">
                                    <div class="input-group-prepend">
                                    <span class="input-group-text">Periode ke-4</span>
                                    </div>
                                    <input type="text" class="form-control" id="inputTanggalMulaiempat" name="inputTanggalMulaiempat" placeholder="Tanggal Mulai" 
                                    data-provide="datepicker" data-date-format="yyyy-mm-dd" value="<?php echo $inputTanggalMulaiempat ?>" required>
                                    <input type="text" class="form-control" id="inputTanggalSelesaiempat" name="inputTanggalSelesaiempat" placeholder="Tanggal Selesai" 
                                    data-provide="datepicker" data-date-format="yyyy-mm-dd" value="<?php echo $inputTanggalSelesaiempat ?>" required>
                                    <input type="number" class="form-control" id="inputQtyempat" name="inputQtyempat"  placeholder="Qty" min="0" value="<?php echo $inputQtyempat ?>" required>
                                </div>

                                <div class="form-group row">
                                    <div class="col">                                   
                                        <button class="btn btn-primary mt-3 mb-3" type="submit" name="submit">Simpan</button>
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