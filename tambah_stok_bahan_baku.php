<?php
    include("layout/session.php");
?>

<?php
    include("layout/koneksi.php");

    // cek apakah form telah di submit
    if (isset($_POST["submit"])) {
        // form telah disubmit, proses data
        
        // ambil semua nilai form

        $inputID = htmlentities(strip_tags(trim($_POST["inputID"])));
        $inputTanggalMasuk = htmlentities(strip_tags(trim($_POST["inputTanggalMasuk"])));
        $inputIDBB = htmlentities(strip_tags(trim($_POST["inputIDBB"])));
        $inputNama = htmlentities(strip_tags(trim($_POST["inputNama"])));
        $inputQty = htmlentities(strip_tags(trim($_POST["inputQty"])));
        $inputUOM = htmlentities(strip_tags(trim($_POST["inputUOM"])));
        $inputKeterangan = htmlentities(strip_tags(trim($_POST["inputKeterangan"]))); 

        //menyiapkan variabel untuk pesan error
        $pesan="";
        $penanda="";


        $query = "SELECT * FROM bahan_baku_masuk WHERE id_bb_masuk='$inputID'";
        $result = mysqli_query($link, $query);
        $jumlah_data = mysqli_num_rows($result);
        if ($jumlah_data >= 1 ) {
             $pesan .= "ID yang sama sudah digunakan. ";  
        }

        else if ($inputTanggalMasuk=="") {
            $pesan .="Tanggal Masuk harus diisi.";
        }

        else if ($inputQty=="") {
            $pesan .="Qty harus diisi.";
            $penanda="iya";
        }

        else if (!is_numeric($inputQty)) {
            $pesan .="Qty harus diisi angka.";
            $penanda="iya";
        }

        else if ($inputIDBB=="0") {
            $pesan .="ID Bahan Baku harus diisi.";
        }

        if ($pesan==="") {
            $inputQty = (float)$inputQty;
            //jalankan query insert
            $query = "update stok_bahan_baku set stok=stok+$inputQty where id_bahan_baku='$inputIDBB'";
            $hasil = mysqli_query($link, $query);

            $query2 = "insert into bahan_baku_masuk values ('$inputID','$inputTanggalMasuk','$inputIDBB',$inputQty,'$inputUOM','$inputKeterangan')";
            $hasil2 = mysqli_query($link, $query2);

            if ($hasil) {
                $pesandikirim .="Stok bahan baku dengan ID $inputIDBB berhasil diupdate.";
                $pesan_dikirim =urlencode($pesandikirim);
                header("Location: stok_bahan_baku.php?pesandikirim=$pesan_dikirim");
                die();
            }
            else {
                die ("Query gagal dijalankan: ".mysqli_errno($link).
                " - ".mysqli_error($link));
            }
        }
    }
    else {
        //deklarasi untuk id bahan_baku_masuk
        $query="select max(id_bb_masuk) as id_bb_masuk_terbesar from bahan_baku_masuk where id_bb_masuk like 'BBIN%'";
        $result = mysqli_query($link, $query);
        $hasil = mysqli_fetch_array($result);
        $idbbmasuk = $hasil['id_bb_masuk_terbesar'];
        $potonganurutan = (int) substr($idbbmasuk,4,4);
        $potonganurutan++;

        $hurufdepan = "BBIN";
        $inputID = $hurufdepan. sprintf("%04s", $potonganurutan);

        $inputTanggalMasuk="";
        $inputIDBB="";
        $inputNama="";
        $inputUOM="";
        $inputQty="";
        $inputKeterangan="";

        $pesan="";
        $penanda="";
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
                            <h6 class="m-0 font-weight-bold text-primary">Tambah Stok Bahan Baku</h6>
                        </div>
                        <div class="card-body">
                            <form action="tambah_stok_bahan_baku.php" class="col-10 offset-1" method="post">
                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label" for="inputID">ID BB Masuk</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" name="inputID" id="inputID" readonly value="<?php echo $inputID ?>">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label" for="inputTanggalMasuk">Tanggal Masuk</label>
                                    <div class="col-sm-10 input-group date">
                                    <input type="text" class="form-control datepicker datetimepicker-input" name="inputTanggalMasuk" id="inputTanggalMasuk" data-provide="datepicker" data-date-format="yyyy-mm-dd" value="<?php echo $inputTanggalMasuk ?>" required>
                                        <div class="input-group-append">
                                            <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label" for="inputIDBB">ID Bahan Baku</label>
                                    <div class="col-sm-10">
                                        <?php
                                        if ($penanda=="iya") {
                                            echo "<input type=\"text\" class=\"form-control\" name=\"inputIDBB\" id=\"inputIDBB\" readonly value=\"$inputIDBB\">";
                                                                
                                            //hanya berupa trik agar javascript bisa berjalan tanpa error
                                            $jsArray= "var dtBB= new Array();\n";
                                            $jsArray .= "dtBB['" . $inputIDBB . "'] = {namabb:'" . addslashes($inputNama) ."'};\n";
                                        }
                                        else {
                                            echo "<select class=\"form-control\" name=\"inputIDBB\" id=\"inputIDBB\" onchange=\"ubahBB(this.value)\">";
                                            
                                                include("layout/koneksi.php");
                                                echo"<option value=\"0\" selected>----Pilih----</option>";
                                                $query = "select * from bahan_baku";
                                                $jsArray= "var dtBB = new Array();\n";

                                                $result = mysqli_query($link, $query);
                                                while ($hasil=mysqli_fetch_array($result)) {
                                                    echo "<option value=\"$hasil[id_bahan_baku]\">$hasil[id_bahan_baku]</option>";
                                                    $jsArray .= "dtBB['" . $hasil['id_bahan_baku'] . "'] = {namabb:'" . addslashes($hasil['nama_bahan_baku']) . "',uombb:'".addslashes($hasil['uom'])."'};\n";
                                                }
                                            
                                            echo "</select>";
                                        }
                                        ?>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label" for="inputNama">Nama Bahan Baku</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" name="inputNama" id="inputNama" readonly value="<?php echo $inputNama?>">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label" for="inputQty">Qty</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" name="inputQty" id="inputQty" value="<?php echo $inputQty ?>" required>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label" for="inputUOM">UOM</label>
                                    <div class="col-sm-10">
                                    <input type="text" class="form-control" name="inputUOM" id="inputUOM" readonly value="<?php echo $inputUOM ?>">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label" for="inputKeterangan">Keterangan</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" name="inputKeterangan" id="inputKeterangan" value="<?php echo $inputKeterangan ?>">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <div class="col">                                   
                                        <button class="btn btn-primary mt-3" type="submit" name="submit">Simpan</button>
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

    <!--Ambil nilai select untuk dimasukkan kedalam textbox-->
    <!--Ambil nilai id bahan baku masuk untuk dimasukkan kedalam textbox-->
    <script>
        <?php echo $jsArray; ?>   

        function ubahBB(inputIDBB){
            document.getElementById('inputNama').value = dtBB[inputIDBB].namabb;
            document.getElementById('inputUOM').value = dtBB[inputIDBB].uombb;
        }

    </script>

</body>

</html>