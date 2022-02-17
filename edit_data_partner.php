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
        $inputPeran = htmlentities(strip_tags(trim($_POST["inputPeran"])));
        $inputTelp = htmlentities(strip_tags(trim($_POST["inputTelp"])));
        $inputAlamat = mysqli_real_escape_string($link, htmlentities(strip_tags(trim($_POST["inputAlamat"]))));
        $inputKeterangan = htmlentities(strip_tags(trim($_POST["inputKeterangan"]))); 

        //menyiapkan variabel untuk pesan error
        $pesan="";
        $warna ="";

        if (strlen($inputID)>20) {
            $pesan .="ID hanya diisi maksimal 20 digit.";
        }

        elseif (strlen($inputPwd)>25) {
            $pesan .="Password hanya diisi maksimal 25 digit";
        }

        else if (is_numeric($inputNama)) {
            $pesan .="Nama tidak boleh diisi dengan angka.";
        }

        else if (strlen($inputTelp)>13) {
            $pesan .="Nomor telepon hanya diisi maksimal 13 digit";
        }

        else if (!is_numeric($inputTelp)) {
            $pesan .="Nomor telepon boleh diisi dengan angka.";
        }


        if ($pesan==="") {
            //jalankan query edit
            $query = "update partner set nama_partner='$inputNama', peran='$inputPeran', no_telp='$inputTelp', alamat='$inputAlamat', keterangan='$inputKeterangan' where id_partner='$inputID'";
            $hasil = mysqli_query($link, $query);

            if ($hasil) {
                $pesandikirim .="Data dengan ID = $inputID berhasil diupdate";
                $pesan_dikirim=urlencode($pesandikirim);
                header("Location: data_partner.php?pesandikirim=$pesan_dikirim");
                die();
                
            }
            else {
                die ("Query gagal dijalankan: ".mysqli_errno($link).
                " - ".mysqli_error($link));
            }
        }
    }

    else {
        //diproses setelah tombol edit pada data partner diklik
        $id_partner=$_GET['id_partner'];

        $result=mysqli_query($link, "select * from partner where id_partner='$id_partner'");
        $hasil=mysqli_fetch_assoc($result);

        $inputID=$hasil['id_partner'];
        $inputNama=$hasil['nama_partner'];
        $inputPeran=$hasil['peran'];
        $inputTelp=$hasil['no_telp'];
        $inputAlamat=$hasil['alamat'];
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
    <title>Data partner</title>

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
                            <h6 class="m-0 font-weight-bold text-primary">Edit Data Partner</h6>
                        </div>
                        <div class="card-body">
                            <form action="edit_data_partner.php" class="col-10 offset-1" method="post">
                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label" for="inputID">ID Partner</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" name="inputID" id="inputID" readonly required value="<?php echo $inputID ?>">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label" for="inputNama">Nama Partner</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" name="inputNama" id="inputNama" required value="<?php echo $inputNama ?>">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label" for="inputPeran">Peran</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" name="inputPeran" id="inputPeran" readonly value="<?php echo $inputPeran ?>">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label" for="inputTelp">No Telp</label>
                                    <div class="col-sm-10">
                                        <input type="number" class="form-control" name="inputTelp" id="inputTelp" required value="<?php echo $inputTelp ?>">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label" for="inputAlamat">Alamat</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" name="inputAlamat" id="inputAlamat" required value="<?php echo $inputAlamat ?>">
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

</body>

</html>