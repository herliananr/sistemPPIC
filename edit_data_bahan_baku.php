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
        $inputNama = htmlentities(strip_tags(trim($_POST["inputNama"])));
        $inputUOM = htmlentities(strip_tags(trim($_POST["inputUOM"])));
        $inputWarna = htmlentities(strip_tags(trim($_POST["inputWarna"])));
        $inputBentuk = htmlentities(strip_tags(trim($_POST["inputBentuk"])));
        $inputSpesifikasi = htmlentities(strip_tags(trim($_POST["inputSpesifikasi"])));
        $inputKeterangan = htmlentities(strip_tags(trim($_POST["inputKeterangan"]))); 

        //menyiapkan variabel untuk pesan error
        $pesan="";
        $warna ="";

        if (strlen($inputID)>20) {
            $pesan .="ID hanya diisi maksimal 20 digit.";
        }

        elseif (strlen($inputNama)>35) {
            $pesan .="Nama hanya diisi maksimal 35 digit";
        }

        else if (strlen($inputBentuk)>35) {
            $pesan .="Bentuk hanya diisi maksimal 35 digit.";
        }

        else if (strlen($inputWarna)>25) {
            $pesan .="Warna hanya diisi maksimal 25 digit.";
        }

        else if (strlen($inputSpesifikasi)>35) {
            $pesan .="Spesifikai hanya diisi maksimal 35 digit.";
        }

        else if (strlen($inputKeterangan)>40) {
            $pesan .="Keterangan hanya diisi maksimal 40 digit.";
        }

        if ($pesan==="") {
            //jalankan query edit
            $query = "update bahan_baku set nama_bahan_baku='$inputNama', uom='$inputUOM', warna='$inputWarna', bentuk='$inputBentuk', spesifikasi='$inputSpesifikasi', keterangan='$inputKeterangan' where id_bahan_baku='$inputID'";
            $hasil = mysqli_query($link, $query);

            $query2 = "update stok_bahan_baku set uom='$inputUOM' where id_bahan_baku='$inputID'";
            $hasil2 = mysqli_query($link, $query2);
            
            $query3 = "update lead_time set nama_barang='$inputNama' where id_barang='$inputID'";
            $hasil3 = mysqli_query($link, $query3);

            if ($hasil) {
                $pesandikirim .="Data dengan ID = $inputID berhasil diupdate";
                $pesan_dikirim=urlencode($pesandikirim);
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
        //diproses setelah tombol edit pada data bahan_baku diklik
        $id_bahan_baku=$_GET['id_bahan_baku'];

        $result=mysqli_query($link, "select * from bahan_baku where id_bahan_baku='$id_bahan_baku'");
        $hasil=mysqli_fetch_assoc($result);

        $inputID=$hasil['id_bahan_baku'];
        $inputNama=$hasil['nama_bahan_baku'];
        $inputUOM=$hasil['uom'];
        $inputWarna=$hasil['warna'];
        $inputBentuk=$hasil['bentuk'];
        $inputSpesifikasi=$hasil['spesifikasi'];
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
                            <h6 class="m-0 font-weight-bold text-primary">Edit Data Bahan Baku</h6>
                        </div>
                        <div class="card-body">
                            <form action="edit_data_bahan_baku.php" class="col-10 offset-1" method="post">
                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label" for="inputID">ID Bahan Baku</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" name="inputID" id="inputID" readonly required value="<?php echo $inputID ?>">
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

</body>

</html>