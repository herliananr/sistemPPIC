<?php
    include("layout/session.php");
?>

<?php
    include("layout/koneksi.php");

    // cek apakah form telah di submit untuk dilakukan edit data
    if (isset($_POST["submit"])) {
        // form telah disubmit, proses data
        
        // ambil semua nilai form

        $inputID = htmlentities(strip_tags(trim($_POST["inputID"])));
        $inputNama = mysqli_real_escape_string($link, htmlentities(strip_tags(trim($_POST["inputNama"]))));
        $inputUOM = mysqli_real_escape_string($link, htmlentities(strip_tags(trim($_POST["inputUOM"]))));
        $inputTipe = htmlentities(strip_tags(trim($_POST["inputTipe"])));
        $inputWarna = htmlentities(strip_tags(trim($_POST["inputWarna"])));
        $inputKeterangan = htmlentities(strip_tags(trim($_POST["inputKeterangan"]))); 

        //menyiapkan variabel untuk pesan error
        $pesan="";

        if (strlen($inputID)>20) {
            $pesan .="ID hanya diisi maksimal 20 digit.";
        }

        elseif (strlen($inputNama)>35) {
            $pesan .="Nama hanya diisi maksimal 35 digit";
        }

        else if (strlen($inputTipe)>25) {
            $pesan .="Tipe hanya diisi maksimal 25 digit.";
        }

        else if (strlen($inputWarna)>25) {
            $pesan .="Warna hanya diisi maksimal 25 digit.";
        }

        if ($pesan==="") {
            //jalankan query edit
            $query = "update produk set nama_produk='$inputNama', uom='$inputUOM', tipe='$inputTipe', warna='$inputWarna', keterangan='$inputKeterangan' where id_produk='$inputID'";
            $hasil = mysqli_query($link, $query);

            $query2 = "update stok_produk set uom='$inputUOM' where id_produk='$inputID'";
            $hasil2 = mysqli_query($link, $query2);

            $query3 = "update lead_time set nama_barang='$inputNama' where id_barang='$inputID'";
            $hasil3 = mysqli_query($link, $query3);

            if ($hasil) {
                $pesandikirim .="Data dengan ID = $inputID berhasil diupdate";
                $pesan_dikirim=urlencode($pesandikirim);
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
        //diproses setelah tombol edit pada data produk diklik
        $id_produk=$_GET['id_produk'];

        $result=mysqli_query($link, "select * from produk where id_produk='$id_produk'");
        $hasil=mysqli_fetch_assoc($result);

        $inputID=$hasil['id_produk'];
        $inputNama=$hasil['nama_produk'];
        $inputUOM=$hasil['uom'];
        $inputTipe=$hasil['tipe'];
        $inputWarna=$hasil['warna'];
        $inputKeterangan=$hasil['keterangan'];

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
                            <h6 class="m-0 font-weight-bold text-primary">Edit Data Produk</h6>
                        </div>
                        <div class="card-body">
                            <form action="edit_data_produk.php" class="col-10 offset-1" method="post">
                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label" for="inputID">ID Produk</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" name="inputID" id="inputID" readonly required value="<?php echo $inputID ?>">
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
                                                echo "<option value=\"$inputUOM\">$inputUOM</option>";
                                                while ($hasil=mysqli_fetch_assoc($result)) {
                                                    if ($hasil['uom']==$inputUOM) {
                                                        echo "";
                                                    }
                                                    else{
                                                        echo "<option value=\"$hasil[uom]\">$hasil[uom]</option>";
                                                    }
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