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
        $inputTipe = htmlentities(strip_tags(trim($_POST["inputTipe"])));
        $inputWarna = htmlentities(strip_tags(trim($_POST["inputWarna"])));
        $inputKeterangan = htmlentities(strip_tags(trim($_POST["inputKeterangan"]))); 

        //menyiapkan variabel untuk pesan error
        $pesan="";


        $query = "SELECT * FROM produk WHERE id_produk='$inputID'";
        $result = mysqli_query($link, $query);
        $jumlah_data = mysqli_num_rows($result);
        if ($jumlah_data >= 1 ) {
             $pesan .= "ID yang sama sudah digunakan. ";  
        }

        else if (strlen($inputID)>20) {
            $pesan .="ID hanya diisi maksimal 20 digit.";
        }

        else if (strlen($inputTipe)>25) {
            $pesan .="Tipe hanya diisi maksimal 25 digit.";
        }

        else if (strlen($inputWarna)>25) {
            $pesan .="Warna hanya diisi maksimal 25 digit.";
        }

        if ($pesan==="") {
            //jalankan query insert
            $query = "insert into produk values ('$inputID','$inputNama', '$inputUOM', '$inputTipe', '$inputWarna', '$inputKeterangan')";
            $hasil = mysqli_query($link, $query);

            $query2 = "insert into stok_produk values ('$inputID','$inputUOM', 0)";
            $hasil2 = mysqli_query($link, $query2);

            $query3 = "insert into lead_time values ('$inputID','$inputNama', 0)";
            $hasil3 = mysqli_query($link, $query3);

            if ($hasil) {
                $pesandikirim .="Produk dengan ID $inputID berhasil ditambahkan.";
                $pesan_dikirim =urlencode($pesandikirim);
                header("Location: data_produk.php?pesandikirim=$pesan_dikirim");
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
        $inputTipe="";
        $inputWarna="";
        $inputKeterangan="";

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
    <title>Data produk</title>
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
                            <h6 class="m-0 font-weight-bold text-primary">Tambah Data Produk</h6>
                        </div>
                        <div class="card-body">
                            <form action="tambah_data_produk.php" class="col-10 offset-1" method="post">
                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label" for="inputID">ID produk</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" name="inputID" id="inputID" required value="<?php echo $inputID ?>">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label" for="inputNama">Nama Produk</label>
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
                                    <label class="col-sm-2 col-form-label" for="inputTipe">Tipe</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" name="inputTipe" id="inputTipe" value="<?php echo $inputTipe ?>">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label" for="inputWarna">Warna</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" name="inputWarna" id="inputWarna" value="<?php echo $inputWarna ?>">
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

</body>

</html>