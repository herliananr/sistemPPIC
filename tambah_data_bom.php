<?php
    include("layout/session.php");
?>

<?php
    $pesan_diterima="";
    $pesan_diterima_id_produk="";
    $pesan_diterima_nama_produk ="";

    if (isset($_GET["pesandikirim"])) {
        $pesan_diterima = $_GET["pesandikirim"];
        $pesan_diterima_id_produk = $_GET['id_produk_bom'];
        $pesan_diterima_nama_produk = $_GET['nama_produk'];
    }

?>

<?php
    include("layout/koneksi.php");

    // cek apakah form telah di submit
    if (isset($_POST["submit"])) {
        // form telah disubmit, proses data
        
        // ambil semua nilai form

        $inputIDProduk = htmlentities(strip_tags(trim($_POST["inputIDProduk"])));
        $inputNamaProduk = htmlentities(strip_tags(trim($_POST["inputNamaProduk"])));
        $inputLevel = htmlentities(strip_tags(trim($_POST["inputLevel"])));
        $inputIDBB = htmlentities(strip_tags(trim($_POST["inputIDBB"])));
        $inputNamaBB = htmlentities(strip_tags(trim($_POST["inputNamaBB"])));
        $inputJumlahPemakaian = htmlentities(strip_tags(trim($_POST["inputJumlahPemakaian"])));
        $inputUOMBB = htmlentities(strip_tags(trim($_POST["inputUOMBB"])));
        $inputIDInduk = htmlentities(strip_tags(trim($_POST["inputIDInduk"])));
        $inputKeterangan = htmlentities(strip_tags(trim($_POST["inputKeterangan"])));

        //menyiapkan variabel untuk pesan error
        $pesan="";
        $warna="";
        $penanda="";

        $selectedsatu="";
        $selecteddua="";
        $selectedtidakada="";
        $selectedtidakdibeli="";

        $query = "SELECT * FROM bom WHERE id_produk_bom='$inputIDProduk' and id_bahan_baku='$inputIDBB'";
        $result = mysqli_query($link, $query);
        $jumlah_data = mysqli_num_rows($result);

        if ($inputIDProduk=="0") {
            $pesan .="ID Produk harus diisi.";
        }

        else if ($inputLevel=="0") {
            $pesan .="Level harus diisi.";
            $penanda="iya";
        }

        else if ($inputIDBB=="0") {
            $pesan .="ID Bahan Baku harus diisi.";
            $penanda="iya";
        }

        else if (!is_numeric($inputJumlahPemakaian)) {
            $pesan .="Jumlah pemakaian hanya diisi dengan angka.";
            $penanda="iya";
        }

        else if ($jumlah_data >= 1 ) {
            $pesan .= "ID Bahan Baku yang sama sudah digunakan. ";  
            $penanda = "iya";
        }

        else if ($inputLevel !=="1" and $inputIDInduk=="0") {
            $pesan .="ID Induk harus diisi.";
            $penanda="iya";
        }

        else if ($inputLevel =="1" and $inputIDInduk !=="0") {
            $pesan .="ID Induk tidak boleh dipilih karena bahan baku berada di level 1.";
            $penanda="iya";
        }
        else if ($inputLevel !=="1" and $inputKeterangan !=="") {
            $pesan .="Bahan Baku level 2 harus dibeli.";
            $penanda="iya";
        }


        switch ($inputLevel) {
            case '1':
                $selectedsatu="selected";
                break;
                
            case '2':
                $selecteddua="selected";
                break;

            default:
                break;

        }

        switch ($inputKeterangan) {
            case '':
                $selectedtidakada="selected";
                break;
            case 'tidak dibeli':
                $selectedtidakdibeli="selected";
                break;
            default:
                break;
        }

        if ($pesan==="") {
            //jalankan query insert
            $query = "insert into bom values ('$inputIDProduk','$inputNamaProduk','$inputLevel','$inputIDBB','$inputNamaBB', '$inputJumlahPemakaian', '$inputUOMBB','$inputIDInduk', '$inputKeterangan')";
            $hasil = mysqli_query($link, $query);

            if ($hasil) {
                $pesan .="BOM dengan ID Produk $inputIDProduk berhasil ditambahkan.";
                $warna = "hijau";
            }
            else {
                die ("Query gagal dijalankan: ".mysqli_errno($link).
                " - ".mysqli_error($link));
            }
        }
    }
    else {

        //apabila $pesan_diterima terisi setelah melakukan hapus pada suatu bahan baku
        if ($pesan_diterima !=="") {
            //sebagai indikator untuk select
            $warna="hijau";
            $inputIDProduk=$pesan_diterima_id_produk;
            $inputNamaProduk=$pesan_diterima_nama_produk;
        }
        else {
            $inputIDProduk="";
            $inputNamaProduk="";
            $warna="";
        }

        $inputLevel="";
        $inputIDBB="";
        $inputNamaBB="";
        $inputUOMBB="";
        $inputJumlahPemakaian="";
        $inputIDInduk="";
        $inputKeterangan="";

        $selectedsatu="";
        $selecteddua="";
        $selectedtidakada="";
        $selectedtidakdibeli="";
        
        $penanda="";
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
    <title>Data BOM</title>
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
                        if ($pesan_diterima !== "") {
                                echo "<div class=\"col-md-12 col-sm-12 alert alert-success alert-dismissible fade show notifikasiperingatan\">$pesan_diterima<button type=\"button\" class=\"close\" data-dismiss=\"alert\">
                                <span>&times;</span></button></div>";  
                        }
                    ?>
                    <?php
                         // tampilkan error jika ada
                        if ($pesan !== "") {
                            if ($warna=="hijau") {
                                echo "<div class=\"col-md-12 col-sm-12 alert alert-success alert-dismissible fade show notifikasiperingatan\">$pesan<button type=\"button\" class=\"close\" data-dismiss=\"alert\">
                                <span>&times;</span></button></div>";  
                            }
                            else {
                                echo "<div class=\"col-md-12 col-sm-12 alert alert-danger alert-dismissible fade show notifikasiperingatan\">$pesan<button type=\"button\" class=\"close\" data-dismiss=\"alert\">
                                <span>&times;</span></button></div>";  
                            }
                                         
                        }
                    ?>

                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Tambah Data Bill of Material</h6>
                        </div>
                        <div class="card-body">
                            <form action="tambah_data_bom.php" class="col-10 offset-1" method="post">

                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label" for="inputIDProduk">ID Produk</label>
                                    <div class="col-sm-10">
                                        
                                            <?php
                                                include("layout/koneksi.php");

                                                if ($warna =="hijau" or $penanda=="iya") {
                                                    echo "<input type=\"text\" class=\"form-control\" name=\"inputIDProduk\" id=\"inputIDProduk\" readonly value=\"$inputIDProduk\">";
                                                    
                                                    //hanya berupa trik agar javascript bisa berjalan tanpa error
                                                    $jsArray= "var dtP = new Array();\n";
                                                    $jsArray .= "dtP['" . $inputIDProduk . "'] = {namaproduk:'" . addslashes($inputNamaProduk) ."'};\n";
                                                    
                                                }

                                                else if ($warna =="" or $penanda==""){
                                                        echo "<select class=\"form-control\" name=\"inputIDProduk\" id=\"inputIDProduk\" onchange=\"ubahProduk(this.value)\">";
                                                        include("layout/koneksi.php");
                                                        echo"<option value=\"0\" selected>----Pilih----</option>";
                                                        $query = "select id_produk, nama_produk from produk where (id_produk, nama_produk) not in (select id_produk, nama_produk from bom)";
                                                        $jsArray= "var dtP = new Array();\n";
        
                                                        $result = mysqli_query($link, $query);
                                                        while ($hasil=mysqli_fetch_array($result)) {
                                                            echo "<option value=\"$hasil[id_produk]\">$hasil[id_produk]</option>";
                                                            $jsArray .= "dtP['" . $hasil['id_produk'] . "'] = {namaproduk:'" . addslashes($hasil['nama_produk']) ."'};\n";
                                                        }

                                                        echo "</select>";
                                                }

                                            ?>
                                        
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label" for="inputNamaProduk">Nama Produk</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" name="inputNamaProduk" id="inputNamaProduk" readonly 
                                            <?php
                                            if ($warna=="hijau" or $penanda="iya") {
                                                echo "value=\"$inputNamaProduk\"";
                                            }
                                            ?>
                                        >
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label" for="inputLevel">Level</label>
                                    <div class="col-sm-10">
                                    <select class="form-control" name="inputLevel" id="inputLevel">
                                            <option value="0" selected>----Pilih----</option>
                                            <option value="1" <?php echo $selectedsatu?>>1</option>
                                            <option value="2" <?php echo $selecteddua?>>2</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label" for="inputIDBB">ID Bahan Baku</label>
                                    <div class="col-sm-10">
                                        <select class="form-control" name="inputIDBB" id="inputIDBB" onchange="ubahBB(this.value)">
                                            <?php
                                                include("layout/koneksi.php");
                                                echo"<option value=\"0\" selected>----Pilih----</option>";
                                                $query = "select * from bahan_baku";
                                                $jsArrayBB= "var dtBB = new Array();\n";

                                                $result = mysqli_query($link, $query);
                                                while ($hasil=mysqli_fetch_array($result)) {
                                                    echo "<option value=\"$hasil[id_bahan_baku]\">$hasil[id_bahan_baku]</option>";
                                                    $jsArrayBB .= "dtBB['" . $hasil['id_bahan_baku'] . "'] = {namabb:'" . addslashes($hasil['nama_bahan_baku']) . "',uombb:'".addslashes($hasil['uom'])."'};\n";
                                                }
                                            ?>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label" for="inputNamaBB">Nama Bahan Baku</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" name="inputNamaBB" id="inputNamaBB" readonly>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label" for="inputJumlahPemakaian">Jumlah Pemakaian</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" name="inputJumlahPemakaian" id="inputJumlahPemakaian" required value="<?php echo $inputJumlahPemakaian ?>">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label" for="inputUOMBB">UOM</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" name="inputUOMBB" id="inputUOMBB" readonly>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label" for="inputIDInduk">ID Induk</label>
                                    <div class="col-sm-10">
                                        <select class="form-control" name="inputIDInduk" id="inputIDInduk">
                                            <?php
                                                include("layout/koneksi.php");
                                                echo"<option value=\"0\" selected>----Pilih----</option>";
                                                $query = "select id_bahan_baku from bahan_baku where (id_bahan_baku) in (select id_bahan_baku from bom where id_produk_bom='$inputIDProduk' and keterangan='tidak dibeli')";
                                                $result = mysqli_query($link, $query);
                                                while ($hasil=mysqli_fetch_array($result)) {
                                                    echo "<option value=\"$hasil[id_bahan_baku]\">$hasil[id_bahan_baku]</option>";
                                                }
                                            ?>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label" for="inputKeterangan">Keterangan</label>
                                    <div class="col-sm-10">
                                        <select class="form-control" name="inputKeterangan" id="inputKeterangan">
                                                <option value="" <?php echo $selectedtidakada ?>>-</option>
                                                <option value="tidak dibeli" <?php echo $selectedtidakdibeli ?>>Tidak dibeli</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <div class="col-sm-1">                                   
                                        <button class="btn btn-primary mt-3 mb-3" type="submit" name="submit">Simpan</button>
                                    </div>
                                    <div class="col ml-3">
                                        <button type="button" class="btn btn-dark mt-3 mb-3" data-toggle="modal" data-target="#Selesai">Selesai</button>
                                        <div class="modal fade" id="Selesai" tabindex="-1">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">Peringatan</h5>
                                                        <button type="button" class="close" data-dismiss="modal"><span>&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <p>Apakah anda sudah selesai dan ingin keluar?</p>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Tidak</button>
                                                        <a href="data_bom.php" class="btn btn-primary">Iya</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                            </form>


                            <div class="table-responsive">
                                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>ID Produk</th>
                                            <th>Level</th>
                                            <th>ID Bahan Baku</th>
                                            <th>Nama Bahan Baku</th>
                                            <th>Jumlah Pemakaian</th>
                                            <th>UOM</th>
                                            <th>ID Induk</th>
                                            <th>Keterangan</th>
                                            <th>Hapus</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                            if ($inputIDProduk=="") {
                                                
                                            }
                                            else {
                                                include("layout/koneksi.php");
                                            
                                                $query = "SELECT * from bom where id_produk_bom='$inputIDProduk'";
                                                $nomor=1;
                                                $result = mysqli_query($link,$query);
                                                while($hasil = mysqli_fetch_assoc($result)){
                                                    echo "<tr>";
                                                    echo "<td>$nomor</td>";
                                                    echo "<td>$hasil[id_produk_bom]</td>";
                                                    echo "<td>$hasil[level]</td>";
                                                    echo "<td>$hasil[id_bahan_baku]</td>";
                                                    echo "<td>$hasil[nama_bahan_baku]</td>";
                                                    echo "<td>$hasil[jml_pemakaian_bb]</td>";
                                                    echo "<td>$hasil[uom_pemakaian_bb]</td>";
                                                    echo "<td>$hasil[id_induk]</td>";
                                                    echo "<td>$hasil[keterangan]</td>";
                                                    
                                                    $pengantar=urlencode($hasil['id_produk_bom']);
                                                    $pengantardua=urlencode($hasil['id_bahan_baku']);
                                                    echo "<td><a href=\"hapus_data_pbb_bom.php?id_produk_bom=$pengantar&id_bahan_baku=$pengantardua\" class=\"btn btn-danger\">Hapus</a></td>";
                                                    echo "</tr>";
                                                    $nomor++;
    
                                                };
                                            }

                                        ?>
                                    </tbody>
                                </table>
                            </div>

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

    <!--Ambil nilai select untuk dimasukkan kedalam textbox-->
    <script>
        <?php echo $jsArray; ?> 
        <?php echo $jsArrayBB; ?> 

        function ubahProduk(inputIDProduk){
            document.getElementById('inputNamaProduk').value = dtP[inputIDProduk].namaproduk;
        }

        function ubahBB(inputIDBB){
            document.getElementById('inputNamaBB').value = dtBB[inputIDBB].namabb;
            document.getElementById('inputUOMBB').value = dtBB[inputIDBB].uombb;
        }

    </script>
</body>

</html>