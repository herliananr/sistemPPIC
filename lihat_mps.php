<?php
    include("layout/session.php");
?>


<?php
    include("layout/koneksi.php");

        $diterima_id = $_GET['id_mps'];
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

            if ($inputTanggalMulaisatu=="0000-00-00") {
                $inputTanggalMulaisatu="-";
            }
            if ($inputTanggalMulaidua=="0000-00-00") {
                $inputTanggalMulaidua="-";
            }
            if ($inputTanggalMulaitiga=="0000-00-00") {
                $inputTanggalMulaitiga="-";
            }
            if ($inputTanggalMulaiempat=="0000-00-00") {
                $inputTanggalMulaiempat="-";
            }
            if ($inputTanggalSelesaisatu=="0000-00-00") {
                $inputTanggalSelesaisatu="-";
            }
            if ($inputTanggalSelesaidua=="0000-00-00") {
                $inputTanggalSelesaidua="-";
            }
            if ($inputTanggalSelesaitiga=="0000-00-00") {
                $inputTanggalSelesaitiga="-";
            }
            if ($inputTanggalSelesaiempat=="0000-00-00") {
                $inputTanggalSelesaiempat="-";
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

                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Lihat Master Production Schedule</h6>
                        </div>
                        <div class="card-body">
                            <form>
    
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

                                <div class="input-group">
                                    <div class="input-group-prepend">
                                    <span class="input-group-text">Periode ke-1</span>
                                    </div>
                                    <input type="text" class="form-control" id="inputTanggalMulaisatu" name="inputTanggalMulaisatu" readonly value="<?php echo $inputTanggalMulaisatu ?>">
                                    <input type="text" class="form-control" id="inputTanggalSelesaisatu" name="inputTanggalSelesaisatu" readonly value="<?php echo $inputTanggalSelesaisatu ?>">
                                    <input type="text" class="form-control" id="inputQtysatu" name="inputQtysatu"  placeholder="Qty" readonly min="0" value="<?php echo $inputQtysatu ?>">
                                </div>

                                <div class="input-group mt-2">
                                    <div class="input-group-prepend">
                                    <span class="input-group-text">Periode ke-2</span>
                                    </div>
                                    <input type="text" class="form-control" id="inputTanggalMulaidua" name="inputTanggalMulaidua" readonly value="<?php echo $inputTanggalMulaidua ?>">
                                    <input type="text" class="form-control" id="inputTanggalSelesaidua" name="inputTanggalSelesaidua" readonly value="<?php echo $inputTanggalSelesaidua ?>">
                                    <input type="text" class="form-control" id="inputQtydua" name="inputQtydua"  placeholder="Qty" readonly min="0" value="<?php echo $inputQtydua ?>">
                                </div>

                                <div class="input-group  mt-2">
                                    <div class="input-group-prepend">
                                    <span class="input-group-text">Periode ke-3</span>
                                    </div>
                                    <input type="text" class="form-control" id="inputTanggalMulaitiga" name="inputTanggalMulaitiga" readonly value="<?php echo $inputTanggalMulaitiga ?>">
                                    <input type="text" class="form-control" id="inputTanggalSelesaitiga" name="inputTanggalSelesaitiga" readonly value="<?php echo $inputTanggalSelesaitiga ?>">
                                    <input type="text" class="form-control" id="inputQtytiga" name="inputQtytiga"  placeholder="Qty" readonly min="0" value="<?php echo $inputQtytiga ?>">
                                </div>

                                <div class="input-group  mt-2">
                                    <div class="input-group-prepend">
                                    <span class="input-group-text">Periode ke-4</span>
                                    </div>
                                    <input type="text" class="form-control" id="inputTanggalMulaiempat" name="inputTanggalMulaiempat" readonly value="<?php echo $inputTanggalMulaiempat ?>">
                                    <input type="text" class="form-control" id="inputTanggalSelesaiempat" name="inputTanggalSelesaiempat" readonly value="<?php echo $inputTanggalSelesaiempat ?>">
                                    <input type="text" class="form-control" id="inputQtyempat" name="inputQtyempat"  placeholder="Qty" readonly min="0" value="<?php echo $inputQtyempat ?>">
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

</body>

</html>