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
        $inputNama = htmlentities(strip_tags(trim($_POST["inputNama"])));
        $inputUOM = htmlentities(strip_tags(trim($_POST["inputUOM"])));
        $inputWarna = htmlentities(strip_tags(trim($_POST["inputWarna"])));
        $inputBentuk = htmlentities(strip_tags(trim($_POST["inputBentuk"])));
        $inputSpesifikasi = htmlentities(strip_tags(trim($_POST["inputSpesifikasi"])));
        $inputKeterangan = htmlentities(strip_tags(trim($_POST["inputKeterangan"]))); 

        //menyiapkan variabel untuk pesan error
        $pesan="";
        $checkedcustomer="";
        $checkedsupplier="";
        //deklarasi untuk radio button customer atau supplier
        $jsArraySupplier= "var dtIDBBSupplier = new Array();\n";
        $jsArrayCustomer= "var dtIDBBCustomer = new Array();\n";

        $query = "SELECT * FROM bahan_baku WHERE id_bahan_baku='$inputID'";
        $result = mysqli_query($link, $query);
        $jumlah_data = mysqli_num_rows($result);
        
        if (!isset($_POST['jenisBB'])) {
            $pesan .= "Mohon untuk mengisi ID Bahan Baku";
        }

        else if ($jumlah_data >= 1 ) {
             $pesan .= "ID yang sama sudah digunakan. ";  
        }

        else if (strlen($inputNama)>35) {
            $pesan .="Nama hanya diisi maksimal 35 digit";
        }

        else if (strlen($inputBentuk)>35) {
            $pesan .="Bentuk hanya diisi maksimal 35 digit.";
        }

        else if (strlen($inputWarna)>25) {
            $pesan .="Warna hanya diisi maksimal 25 digit.";
        }

        else if (strlen($inputSpesifikasi)>35) {
            $pesan .="Spesifikasi hanya diisi maksimal 35 digit.";
        }

        else if (strlen($inputKeterangan)>40) {
            $pesan .="Keterangan hanya diisi maksimal 40 digit.";
        }


        if ($pesan==="") {
            //jalankan query insert
            $query = "insert into bahan_baku values ('$inputID','$inputNama', '$inputUOM', '$inputWarna', '$inputBentuk', '$inputSpesifikasi', '$inputKeterangan')";
            $hasil = mysqli_query($link, $query);

            $query2 = "insert into stok_bahan_baku values ('$inputID','$inputUOM', 0)";
            $hasil2 = mysqli_query($link, $query2);

            $query3 = "insert into lead_time values ('$inputID','$inputNama', 0)";
            $hasil3 = mysqli_query($link, $query3);

            if ($hasil) {
                $pesandikirim .="Bahan Baku dengan ID $inputID berhasil ditambahkan.";
                $pesan_dikirim =urlencode($pesandikirim);
                header("Location: data_bahan_baku.php?pesandikirim=$pesan_dikirim");
                die();
            }
            else {
                die ("Query gagal dijalankan: ".mysqli_errno($link).
                " - ".mysqli_error($link));
            }
        }
    }
    else {
        $inputID="";
        $inputNama="";
        $inputUOM="";
        $inputWarna="";
        $inputBentuk="";
        $inputSpesifikasi="";
        $inputKeterangan="";

        $jenisBB="";
        $checkedcustomer="";
        $checkedsupplier="";
        //deklarasi untuk radio button customer atau supplier
        $jsArraySupplier= "var dtIDBBSupplier = new Array();\n";
        $jsArrayCustomer= "var dtIDBBCustomer = new Array();\n";

        $pesan="";
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
    <title>Data Bahan Baku</title>
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
                            <h6 class="m-0 font-weight-bold text-primary">Tambah Data Bahan Baku</h6>
                        </div>
                        <div class="card-body">
                            <form action="tambah_data_bahan_baku.php" class="col-10 offset-1" method="post">
                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label" for="inputID">ID Bahan Baku</label>
                                    <div class="col-sm-6">
                                        <input type="text" class="form-control" name="inputID" id="inputID" readonly required value="<?php echo $inputID ?>">
                                    </div>
                                    <div class="col-sm-4 mt-1">
                                        <div class="custom-control custom-radio custom-control-inline">
                                            <?php
                                                echo "<input type=\"radio\" id=\"jenisBBSupplier\" name=\"jenisBB\" class=\"custom-control-input\" value=\"supplier\" onchange=\"ubahIDBB(this.value)\" $checkedsupplier>";
                                                echo "<label class=\"custom-control-label\" for=\"jenisBBSupplier\">Supplier</label>";
                                                include("layout/koneksi.php");
                                                $query="select max(substring(id_bahan_baku,4,3)) as id_bb_terbesar from bahan_baku";
                                                $result = mysqli_query($link, $query);
                                                $hasil = mysqli_fetch_array($result);
                                                $potonganurutan = $hasil['id_bb_terbesar'];
                                                $potonganurutan++;

                                                $hurufdepan = "BB1";
                                                $idbb = $hurufdepan. sprintf("%03s", $potonganurutan);
                                                $jsArraySupplier .= "dtIDBBSupplier = {idbb:'" . $idbb . "'};\n";
                                            ?>
                                        </div>
                                        <div class="custom-control custom-radio custom-control-inline">
                                            <?php
                                                echo "<input type=\"radio\" id=\"jenisBBCustomer\" name=\"jenisBB\" class=\"custom-control-input\" value=\"customer\" onchange=\"ubahIDBB(this.value)\" $checkedcustomer>";
                                                echo "<label class=\"custom-control-label\" for=\"jenisBBCustomer\">Customer</label>";
                                                include("layout/koneksi.php");
                                                
                                                $query="select max(substring(id_bahan_baku,4,3)) as id_bb_terbesar from bahan_baku";
                                                $result = mysqli_query($link, $query);
                                                $hasil = mysqli_fetch_array($result);
                                                $potonganurutan =  $hasil['id_bb_terbesar'];
                                                $potonganurutan++;

                                                $hurufdepan = "BB0";
                                                $idbb = $hurufdepan. sprintf("%03s", $potonganurutan);
                                                $jsArrayCustomer .= "dtIDBBCustomer = {idbb:'" . $idbb . "'};\n";
                                            ?>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label" for="inputNama">Nama Bahan Baku</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" name="inputNama" id="inputNama" required value="<?php echo $inputNama ?>">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label" for="inputUOM">UOM</label>
                                    <div class="col-sm-6">
                                        <select class="form-control" name="inputUOM" id="inputUOM">
                                            <?php
                                                include("layout/koneksi.php");
                                                $query = "select * from uom";
                                                $result = mysqli_query($link, $query);
                                                while ($hasil=mysqli_fetch_assoc($result)) {
                                                    echo "<option value=\"$hasil[uom]\">$hasil[uom]</option>";
                                                }
                                            ?>
                                        </select>
                                    </div>
                                    <div class="col-sm-4">
                                        <a href="tambah_data_uom.php" class="btn btn-primary">Tambah UOM</a>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label" for="inputWarna">Warna</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" name="inputWarna" id="inputWarna" value="<?php echo $inputWarna ?>">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label" for="inputBentuk">Bentuk</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" name="inputBentuk" id="inputBentuk" required value="<?php echo $inputBentuk ?>">
                                    </div>
                                </div>
 
                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label" for="inputSpesifikasi">Spesifikasi</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" name="inputSpesifikasi" id="inputSpesifikasi" required value="<?php echo $inputSpesifikasi ?>">
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


                                                
    <!--Ambil nilai radio button untuk dimasukkan kedalam textbox-->
    <script>
        <?php echo $jsArraySupplier; echo $jsArrayCustomer;?> 
        
        function ubahIDBB(jenisBB){
            if (document.getElementById('jenisBBSupplier').checked) {
                document.getElementById('inputID').value = dtIDBBSupplier.idbb;
            }
            else if (document.getElementById('jenisBBCustomer').checked) {
                document.getElementById('inputID').value = dtIDBBCustomer.idbb;
            }
            
        }

    </script>
</body>

</html>