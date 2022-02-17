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
        $inputIDProduk = htmlentities(strip_tags(trim($_POST["inputIDProduk"])));
        $inputNama = htmlentities(strip_tags(trim($_POST["inputNama"])));
        $inputQty = htmlentities(strip_tags(trim($_POST["inputQty"])));
        $inputUOM = htmlentities(strip_tags(trim($_POST["inputUOM"])));
        $inputKeterangan = htmlentities(strip_tags(trim($_POST["inputKeterangan"]))); 

        //menyiapkan variabel untuk pesan error
        $pesan="";
        $penanda="";
        
        $query = "SELECT * FROM produk_masuk WHERE id_produk_masuk='$inputID'";
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
        }

        else if (!is_numeric($inputQty)) {
            $pesan .="Qty harus diisi angka.";
        }
        else if ($inputIDProduk=="0") {
            $pesan .="ID Produk harus diisi.";
        }

        if ($pesan==="") {
            $inputQty = (float)$inputQty;
            //jalankan query insert
            $query = "update stok_produk set stok=stok+$inputQty where id_produk='$inputIDProduk'";
            $hasil = mysqli_query($link, $query);

            $query2 = "insert into produk_masuk values ('$inputID','$inputTanggalMasuk','$inputIDProduk',$inputQty,'$inputUOM','$inputKeterangan')";
            $hasil2 = mysqli_query($link, $query2);

            if ($hasil) {
                $pesandikirim .="Stok produk dengan ID $inputIDProduk berhasil diupdate.";
                $pesan_dikirim =urlencode($pesandikirim);
                header("Location: stok_produk.php?pesandikirim=$pesan_dikirim");
                die();
            }
            else {
                die ("Query gagal dijalankan: ".mysqli_errno($link).
                " - ".mysqli_error($link));
            }
        }
    }
    else {
        //value dari $diterima_id didapatkan dari cari_id_po.php
        $diterima_id = $_GET['id_po'];

        //deklarasi untuk id produk_masuk
        $query="select max(id_produk_masuk) as id_produk_masuk_terbesar from produk_masuk where id_produk_masuk like 'SUBPROD%'";
        $result = mysqli_query($link, $query);
        $hasil = mysqli_fetch_array($result);
        $idprodukmasuk = $hasil['id_produk_masuk_terbesar'];
        $potonganurutan = (int) substr($idprodukmasuk,7,4);
        $potonganurutan++;

        $hurufdepan = "SUBPROD";
        $inputID = $hurufdepan. sprintf("%04s", $potonganurutan);

        $inputTanggalMasuk="";
        $inputIDProduk="";
        $inputNama="";
        $inputUOM="";
        $inputQty="";
        $inputKeterangan="$diterima_id";

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
    <title>Stok Produk</title>
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
                            <h6 class="m-0 font-weight-bold text-primary">Tambah Stok Produk</h6>
                        </div>
                        <div class="card-body">
                            <form action="tambah_stok_produk.php" class="col-10 offset-1" method="post">
                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label" for="inputID">ID Produk Masuk</label>
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
                                    <label class="col-sm-2 col-form-label" for="inputIDProduk">ID Produk</label>
                                    <div class="col-sm-10">
                                        <?php
                                        if ($penanda=="iya") {
                                            echo "<input type=\"text\" class=\"form-control\" name=\"inputIDProduk\" id=\"inputIDProduk\" readonly value=\"$inputIDProduk\">";
                                                                
                                            //hanya berupa trik agar javascript bisa berjalan tanpa error
                                            $jsArray= "var dtProduk= new Array();\n";
                                            $jsArray .= "dtProduk['" . $inputIDProduk . "'] = {namaproduk:'" . addslashes($inputNama) ."'};\n";
                                        }
                                        else {
                                            echo "<select class=\"form-control\" name=\"inputIDProduk\" id=\"inputIDProduk\" onchange=\"ubahProduk(this.value)\">";
                                            
                                                include("layout/koneksi.php");
                                                echo"<option value=\"0\" selected>----Pilih----</option>";
                                                $query = "select * from po where id_po='$inputKeterangan' ";
                                                $jsArray= "var dtProduk = new Array();\n";

                                                $result = mysqli_query($link, $query);
                                                while ($hasil=mysqli_fetch_array($result)) {
                                                    echo "<option value=\"$hasil[id_produk]\">$hasil[id_produk]</option>";
                                                    $jsArray .= "dtProduk['" . $hasil['id_produk'] . "'] = {namaproduk:'" . addslashes($hasil['nama_produk']) . "',uomproduk:'".addslashes($hasil['uom'])."'};\n";
                                                }
                                            
                                            echo "</select>";
                                        }
                                        ?>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label" for="inputNama">Nama Produk</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" name="inputNama" id="inputNama" readonly 
                                        <?php
                                                if ($penanda=="iya") {
                                                    echo "value=\"$inputNama\"";
                                                }
                                        ?>
                                        >
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label" for="inputQty">Qty</label>
                                    <div class="col-sm-10">
                                        <input type="number" class="form-control" name="inputQty" id="inputQty" value="<?php echo $inputQty ?>" required>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label" for="inputUOM">UOM</label>
                                    <div class="col-sm-10">
                                    <input type="text" class="form-control" name="inputUOM" id="inputUOM" readonly
                                            <?php
                                                if ($penanda=="iya") {
                                                    echo "value=\"$inputUOM\"";
                                                }
                                            ?>
                                    >
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label" for="inputKeterangan">Keterangan</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" name="inputKeterangan" id="inputKeterangan" readonly value="<?php echo $inputKeterangan ?>">
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
    <!--Ambil nilai id produk masuk untuk dimasukkan kedalam textbox-->
    <script>
        <?php echo $jsArray; ?>  

        function ubahProduk(inputIDProduk){
            document.getElementById('inputNama').value = dtProduk[inputIDProduk].namaproduk;
            document.getElementById('inputUOM').value = dtProduk[inputIDProduk].uomproduk;
        }

    </script>

</body>

</html>