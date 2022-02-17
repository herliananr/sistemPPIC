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
        $inputPwd = htmlentities(strip_tags(trim($_POST["inputPwd"])));
        $inputNama = htmlentities(strip_tags(trim($_POST["inputNama"])));
        $inputJk = htmlentities(strip_tags(trim($_POST["inputJk"])));
        $inputTelp = htmlentities(strip_tags(trim($_POST["inputTelp"])));
        $inputAlamat = htmlentities(strip_tags(trim($_POST["inputAlamat"])));
        $inputPeran = htmlentities(strip_tags(trim($_POST["inputPeran"]))); 

        //menyiapkan variabel untuk pesan error
        $pesan="";


        if (strlen($inputPwd)>25) {
            $pesan .="Password hanya diisi maksimal 25 digit";
        }


        if ($pesan==="") {
            //jalankan query edit
            $query = "update karyawan set password='$inputPwd' where id_karyawan='$inputID'";
            $hasil = mysqli_query($link, $query);

            if ($hasil) {
                $pesan .="Password dengan ID = $inputID berhasil diupdate";
                
            }
            else {
                die ("Query gagal dijalankan: ".mysqli_errno($link).
                " - ".mysqli_error($link));
            }
        }
    }

    else {
        //diproses saat muncul profil pengguna

        $result=mysqli_query($link, "select * from karyawan where id_karyawan='$isi_sesi'");
        $hasil=mysqli_fetch_assoc($result);

        $inputID=$hasil['id_karyawan'];
        $inputNama=$hasil['nama_karyawan'];
        $inputPwd=$hasil['password'];
        $inputJk=$hasil['jenis_kelamin'];
        $inputAlamat=$hasil['alamat'];
        $inputTelp=$hasil['no_telp'];
        $inputPeran=$hasil['peran'];

        $pesan="";

        switch ($inputJk) {
            case 'l':
                $inputJk="Laki-laki";
                break;
                
            case 'p':
                $inputJk="Perempuan";
                break;

            default:
                break;

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
    <title>Data Karyawan</title>

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
                                echo "<div class=\"col-md-12 col-sm-12 alert alert-success alert-dismissible fade show notifikasiperingatan\">$pesan<button type=\"button\" class=\"close\" data-dismiss=\"alert\">
                                <span>&times;</span></button></div>";  
                        }
                    ?>

                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Edit Data Karyawan</h6>
                        </div>
                        <div class="card-body">
                            <form action="profil_pengguna.php" class="col-10 offset-1" method="post">
                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label" for="inputID">ID Karyawan</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" name="inputID" id="inputID" readonly value="<?php echo $inputID ?>">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label" for="inputPwd">Password</label>
                                    <div class="col-sm-7">
                                        <input type="password" class="form-control" name="inputPwd" id="inputPwd" required value="<?php echo $inputPwd ?>">
                                    </div>
                                    <div class="col-sm-3">
                                        <p><i>*Password bisa diganti</i></p>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label" for="inputNama">Nama Karyawan</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" name="inputNama" id="inputNama" readonly value="<?php echo $inputNama ?>">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label" for="inputJk">Jenis Kelamin</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="inputJk" name="inputJk" readonly value="<?php echo $inputJk?>">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label" for="inputTelp">No telp</label>
                                    <div class="col-sm-10">
                                        <input type="number" class="form-control" name="inputTelp" id="inputTelp" readonly value="<?php echo $inputTelp ?>">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label" for="inputAlamat">Alamat</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" name="inputAlamat" id="inputAlamat" readonly value="<?php echo $inputAlamat ?>">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label" for="inputPeran">Peran</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" name="inputPeran" id="inputPeran" readonly value="<?php echo $inputPeran ?>">
                                    </div>
                                </div>
                                
                                <div class="form-group row">
                                    <div class="col">                                   
                                        <button class="btn btn-primary mt-3" type="submit" name="submit">Ganti Password</button>
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