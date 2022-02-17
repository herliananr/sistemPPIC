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
        $inputNama = mysqli_real_escape_string($link, htmlentities(strip_tags(trim($_POST["inputNama"]))));
        $inputJk = htmlentities(strip_tags(trim($_POST["inputJk"])));
        $inputTelp = htmlentities(strip_tags(trim($_POST["inputTelp"])));
        $inputAlamat = mysqli_real_escape_string($link, htmlentities(strip_tags(trim($_POST["inputAlamat"]))));
        $inputPeran = htmlentities(strip_tags(trim($_POST["inputPeran"]))); 

        //menyiapkan variabel untuk pesan error
        $pesan="";
        
        $checkedlaki="";
        $checkedperempuan="";
        $checkedhide="";

        $selectedppic="";
        $selectedwarehouse="";
        $selectedengineering="";
        $selectedpurchasing="";
        $selectedproduction="";
        $selectedqc="";

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

        $query = "SELECT * FROM karyawan WHERE id_karyawan='$inputID'";
        $result = mysqli_query($link, $query);
        $hasil = mysqli_fetch_assoc($result);
        if ($hasil['peran'] == "administrator") {
            $tblperannone="d-none";
            $inputPeran="administrator";
        }
        else {
            if ($inputPeran=="0") {
                $pesan .="Mohon untuk memilih peran. ";
            }
        }

        //repopulate jenis kelamin
        switch ($inputJk) {
            case 'l':
                $checkedlaki="checked";
                break;
                
            case 'p':
                $checkedperempuan="checked";
                break;

            default:
                break;

        }

        //repopulate peran
        switch ($inputPeran) {
            case 'ppic':
                $selectedppic="selected";
                break;
            
            case 'warehouse':
                $selectedwarehouse="selected";
                break;

            case 'engineering':
                $selectedengineering="selected";
                break;
                
            case 'purchasing':
                $selectedpurchasing="selected";
                break;

            case 'production':
                $selectedproduction="selected";
                break;

            case 'qc':
                $selectedqc="selected";
                break;

            default:
                break;
        }

        if ($pesan==="") {
            //jalankan query edit
            $query = "update karyawan set password='$inputPwd', nama_karyawan='$inputNama', jenis_kelamin='$inputJk', no_telp='$inputTelp', alamat='$inputAlamat', peran='$inputPeran' where id_karyawan='$inputID'";
            $hasil = mysqli_query($link, $query);

            if ($hasil) {
                $pesandikirim .="Data dengan ID = $inputID berhasil diupdate";
                $pesan_dikirim=urlencode($pesandikirim);
                header("Location: data_karyawan.php?pesandikirim=$pesan_dikirim");
                die();
                
            }
            else {
                die ("Query gagal dijalankan: ".mysqli_errno($link).
                " - ".mysqli_error($link));
            }
        }
    }

    else {
        //diproses setelah tombol edit pada data karyawan diklik
        $id_karyawan=$_GET['id_karyawan'];

        $result=mysqli_query($link, "select * from karyawan where id_karyawan='$id_karyawan'");
        $hasil=mysqli_fetch_assoc($result);

        $inputID=$hasil['id_karyawan'];
        $inputNama=$hasil['nama_karyawan'];
        $inputPwd=$hasil['password'];
        $inputJk=$hasil['jenis_kelamin'];
        $inputAlamat=$hasil['alamat'];
        $inputTelp=$hasil['no_telp'];
        $inputPeran=$hasil['peran'];

        $pesan="";
        $checkedlaki="";
        $checkedperempuan="";
        $selectedppic="";
        $selectedwarehouse="";
        $selectedengineering="";
        $selectedpurchasing="";
        $selectedproduction="";
        $selectedqc="";

        if ($inputPeran =="administrator") {
            $tblperannone="d-none";
        }
        else{
            $tblperannone="";
        }
        

        switch ($inputJk) {
            case 'l':
                $checkedlaki="checked";
                break;
                
            case 'p':
                $checkedperempuan="checked";
                break;

            default:
                break;

        }

        switch ($inputPeran) {
            case 'ppic':
                $selectedppic="selected";
                break;
            
            case 'warehouse':
                $selectedwarehouse="selected";
                break;

            case 'engineering':
                $selectedengineering="selected";
                break;
                
            case 'purchasing':
                $selectedpurchasing="selected";
                break;

            case 'production':
                $selectedproduction="selected";
                break;

            case 'qc':
                $selectedqc="selected";
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
                                echo "<div class=\"col-md-12 col-sm-12 alert alert-danger alert-dismissible fade show notifikasiperingatan\">$pesan<button type=\"button\" class=\"close\" data-dismiss=\"alert\">
                                <span>&times;</span></button></div>";  
                        }
                    ?>

                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Edit Data Karyawan</h6>
                        </div>
                        <div class="card-body">
                            <form action="edit_data_karyawan.php" class="col-10 offset-1" method="post">
                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label" for="inputID">ID Karyawan</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" name="inputID" id="inputID" readonly required value="<?php echo $inputID ?>">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label" for="inputPwd">Password</label>
                                    <div class="col-sm-10">
                                        <input type="password" class="form-control" name="inputPwd" id="inputPwd" required value="<?php echo $inputPwd ?>">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label" for="inputNama">Nama Karyawan</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" name="inputNama" id="inputNama" required value="<?php echo $inputNama ?>">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label">Jenis Kelamin</label>
                                    <div class="col-sm-10">
                                        <div class="custom-control custom-radio custom-control-inline">
                                            <input type="radio" id="customRadio1" name="inputJk" class="custom-control-input" value="l" <?php echo $checkedlaki?>>
                                            <label class="custom-control-label" for="customRadio1">Laki-laki</label>
                                        </div>
                                        <div class="custom-control custom-radio custom-control-inline">
                                            <input type="radio" id="customRadio2" name="inputJk" class="custom-control-input" value="p" <?php echo $checkedperempuan?>>
                                            <label class="custom-control-label" for="customRadio2">Perempuan</label>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label" for="inputTelp">No telp</label>
                                    <div class="col-sm-10 input-group">
                                        <input type="number" class="form-control" name="inputTelp" id="inputTelp" required value="<?php echo $inputTelp ?>">
                                        <div class="input-group-append">
                                            <div class="input-group-text"><i class="fas fa-address-book"></i></div>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label" for="inputAlamat">Alamat</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" name="inputAlamat" id="inputAlamat" required value="<?php echo $inputAlamat ?>">
                                    </div>
                                </div>

                                <div class="form-group row <?php echo $tblperannone?>">
                                    <label class="col-sm-2 col-form-label" for="inputPeran">Peran</label>
                                    <div class="col-sm-10">
                                        <select class="form-control" name="inputPeran" id="inputPeran">
                                            <option value="0" selected>----Pilih----</option>
                                            <option value="ppic" <?php echo $selectedppic?>>PPIC</option>
                                            <option value="warehouse" <?php echo $selectedwarehouse?>>Warehouse</option>
                                            <option value="engineering" <?php echo $selectedengineering?>>Engineering</option>
                                            <option value="purchasing" <?php echo $selectedpurchasing?>>Purchasing</option>
                                            <option value="qc" <?php echo $selectedqc?>>QC</option>
                                        </select>
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