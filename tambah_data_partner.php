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
        $inputPeran = htmlentities(strip_tags(trim($_POST["inputPeran"])));
        $inputTelp = htmlentities(strip_tags(trim($_POST["inputTelp"])));
        $inputAlamat = htmlentities(strip_tags(trim($_POST["inputAlamat"])));
        $inputKeterangan = htmlentities(strip_tags(trim($_POST["inputKeterangan"]))); 

        // ambil nilai produk dan bahan baku yang dipilih
        $inputSubcontProduk = htmlentities(strip_tags(trim($_POST["inputSubcontProduk"])));
        $inputSubcontBB = htmlentities(strip_tags(trim($_POST["inputSubcontBB"])));

        //menyiapkan variabel untuk pesan error
        $pesan="";
        $checkedcustomer="";
        $checkedsubcontproduk="";
        $checkedsubcontbb="";
        //deklarasi untuk pembuatan javascript
        $jsArrayCustomer= "var dtIDCustomer = new Array();\n";
        $jsArraySubcontProduk= "var dtIDSubcontProduk = new Array();\n";
        $jsArraySubcontBB= "var dtIDSubcontBB = new Array();\n";

        $query = "SELECT * FROM partner WHERE id_partner='$inputID'";
        $result = mysqli_query($link, $query);
        $jumlah_data = mysqli_num_rows($result);
  
        if ($inputID =="" ) {
            $pesan .= "ID Partner belum ada. ";  
        }


        else if ($jumlah_data >= 1 ) {
             $pesan .= "ID yang sama sudah digunakan. ";  
        }

        else if (strlen($inputID)>20) {
            $pesan .="ID hanya diisi maksimal 20 digit.";
        }

        
        else if ($inputPeran=="subcontproduk" and $inputSubcontProduk=="0") {
        
                $pesan .= "Mohon untuk memilih produk yang ingin dikerjakan oleh subcont";
            
        }

        else if ($inputPeran=="subcontbb" and $inputSubcontBB=="0") {
            
                $pesan .= "Mohon untuk memilih bahan baku yang ingin dikerjakan oleh subcont";
            
        }

        else if (is_numeric($inputNama)) {
            $pesan .="Nama tidak boleh diisi dengan angka.";
        }

        else if (strlen($inputTelp)>13) {
            $pesan .="Nomor telepon hanya diisi maksimal 13 digit";
        }

        else if (!is_numeric($inputTelp)) {
            $pesan .="Nomor telepon hanya boleh diisi dengan angka.";
        }

        switch ($inputPeran) {
            case 'customer':
                $checkedcustomer="checked";
                break;

            case 'subcontproduk':
                $checkedsubcontproduk="checked";
                break;
                
            case 'subcontbb':
                $checkedsubcontbb="checked";
                break;
            default:
                break;

        }

        if ($pesan==="") {
            if ($inputPeran=="subcontproduk") {
                $inputPeran="Subcont Produk";
                $inputKeterangan = $inputKeterangan.". ID Produk: ".$inputSubcontProduk;
            }
            else if ($inputPeran=="subcontbb") {
                $inputPeran="Subcont Bahan Baku";
                $inputKeterangan = $inputKeterangan.". ID BB: ".$inputSubcontBB;
            }

            //jalankan query insert
            $query = "insert into partner values ('$inputID','$inputNama', '$inputPeran', '$inputTelp', '$inputAlamat', '$inputKeterangan')";
            $hasil = mysqli_query($link, $query);

            if ($hasil) {
                $pesandikirim .="Partner dengan ID $inputID berhasil ditambahkan.";
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
            //deklarasi untuk pembuatan javascript
            $jsArrayCustomer= "var dtIDCustomer = new Array();\n";
            $jsArraySubcontProduk= "var dtIDSubcontProduk = new Array();\n";
            $jsArraySubcontBB= "var dtIDSubcontBB = new Array();\n";

            $query="select max(substring(id_partner,4,4)) as id_partner_terbesar from partner";
            $result = mysqli_query($link, $query);
            $hasil = mysqli_fetch_array($result);
            $potonganurutan =  $hasil['id_partner_terbesar'];
            $potonganurutan++;

            $hurufdepan = "PCU";
            $idpartner = $hurufdepan. sprintf("%04s", $potonganurutan);
            $jsArrayCustomer .= "dtIDCustomer = {idpartner:'" . $idpartner . "'};\n";

        $inputID="";
        $inputNama="";
        $inputPeran="";
        $inputAlamat="";
        $inputTelp="";
        $inputKeterangan="";

        $pesan="";

        $checkedcustomer="checked";
        $checkedsubcontproduk="";
        $checkedsubcontbb="";

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
    <title>Data Partner</title>
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
                            <h6 class="m-0 font-weight-bold text-primary">Tambah Data Partner</h6>
                        </div>
                        <div class="card-body">
                            <form action="tambah_data_partner.php" class="col-10 offset-1" method="post">
                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label" for="inputID">ID Partner</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" name="inputID" id="inputID" readonly value="<?php echo $inputID ?>">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label" for="inputNama">Nama Partner</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" name="inputNama" id="inputNama" required value="<?php echo $inputNama ?>">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label">Peran</label>
                                    <div class="col-sm-10">
                                        <div class="row">
                                            <div class="custom-control custom-radio ml-2">
                                                <input type="radio" id="jenisPartnerCustomer" name="inputPeran" class="custom-control-input" value="customer" onchange="ubahIDPartner(this.value)" <?php echo $checkedcustomer?>>
                                                <label class="custom-control-label" for="jenisPartnerCustomer">Customer</label>
                                                <?php
                                                //untuk mengisi id_partner apabila memilih customer
                                                        include("layout/koneksi.php");
                                                        $query="select max(substring(id_partner,4,4)) as id_partner_terbesar from partner";
                                                        $result = mysqli_query($link, $query);
                                                        $hasil = mysqli_fetch_array($result);
                                                        $potonganurutan =  $hasil['id_partner_terbesar'];
                                                        $potonganurutan++;

                                                        $hurufdepan = "PCU";
                                                        $idpartner = $hurufdepan. sprintf("%04s", $potonganurutan);
                                                        $jsArrayCustomer .= "dtIDCustomer = {idpartner:'" . $idpartner . "'};\n";
                                                ?>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-4 custom-control custom-radio ml-2">
                                                <input type="radio" id="jenisPartnerProduk" name="inputPeran" class="custom-control-input" value="subcontproduk" onchange="ubahIDPartner(this.value)" <?php echo $checkedsubcontproduk?>>
                                                <label class="custom-control-label" for="jenisPartnerProduk">Subcont (Produk)</label>
                                                <?php               
                                                //untuk mengisi id_partner apabila memilih supplier produk
                                                        include("layout/koneksi.php");                        
                                                        $query="select max(substring(id_partner,4,4)) as id_partner_terbesar from partner";
                                                        $result = mysqli_query($link, $query);
                                                        $hasil = mysqli_fetch_array($result);
                                                        $potonganurutan =  $hasil['id_partner_terbesar'];
                                                        $potonganurutan++;

                                                        $hurufdepan = "PSP";
                                                        $idpartner = $hurufdepan. sprintf("%04s", $potonganurutan);
                                                        $jsArraySubcontProduk .= "dtIDSubcontProduk = {idpartner:'" . $idpartner . "'};\n";
                                                ?>
                                            </div>

                                            <div class="col-sm-6 form-group">
                                                <select class="form-control" name="inputSubcontProduk" id="inputSubcontProduk">
                                                    <?php
                                                    //untuk mengambil semua data produk
                                                    include("layout/koneksi.php");
                                                    echo"<option value=\"0\" selected>----Pilih----</option>";
                                                    $query = "select * from produk";
                                                    $result = mysqli_query($link, $query);
                                                    while ($hasil=mysqli_fetch_array($result)) {
                                                        echo "<option value=\"$hasil[id_produk]\">$hasil[id_produk]-$hasil[nama_produk]</option>";
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-4 custom-control custom-radio ml-2">
                                                <input type="radio" id="jenisPartnerBB" name="inputPeran" class="custom-control-input" value="subcontbb" onchange="ubahIDPartner(this.value)" <?php echo $checkedsubcontbb?>>
                                                <label class="custom-control-label" for="jenisPartnerBB">Subcont (Bahan Baku)</label>
                                                <?php
                                                //untuk mengisi id_partner apabila memilih supplier bahan baku
                                                    include("layout/koneksi.php");
                                                    $query="select max(substring(id_partner,4,4)) as id_partner_terbesar from partner";
                                                    $result = mysqli_query($link, $query);
                                                    $hasil = mysqli_fetch_array($result);
                                                    $potonganurutan =  $hasil['id_partner_terbesar'];
                                                    $potonganurutan++;

                                                    $hurufdepan = "PSB";
                                                    $idpartner = $hurufdepan. sprintf("%04s", $potonganurutan);
                                                    $jsArraySubcontBB .= "dtIDSubcontBB = {idpartner:'" . $idpartner . "'};\n";
                                                ?>
                                            </div>

                                            <div class="col-sm-6 form-group">
                                                <select class="form-control" name="inputSubcontBB" id="inputSubcontBB">
                                                    <?php
                                                    //untuk mengambil semua data bahan baku
                                                    include("layout/koneksi.php");
                                                    echo"<option value=\"0\" selected>----Pilih----</option>";
                                                    $query = "select * from bahan_baku";
                                                    $result = mysqli_query($link, $query);
                                                    while ($hasil=mysqli_fetch_array($result)) {
                                                        echo "<option value=\"$hasil[id_bahan_baku]\">$hasil[id_bahan_baku]-$hasil[nama_bahan_baku]</option>";
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label" for="inputTelp">No telp</label>
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
        <?php echo $jsArrayCustomer; echo $jsArraySubcontProduk; echo $jsArraySubcontBB;?> 
            //untuk mengambil nilai radio button apabila mengklik customer (baru masuk form tambah partner)
            if (document.getElementById('jenisPartnerCustomer').checked) {
                document.getElementById('inputID').value = dtIDCustomer.idpartner;
            }
        function ubahIDPartner(inputPeran){
            //untuk mengambil nilai radio button apabila mengklik customer
            if (document.getElementById('jenisPartnerCustomer').checked) {
                document.getElementById('inputID').value = dtIDCustomer.idpartner;
            }
            //untuk mengambil nilai radio button apabila mengklik partner produk
            else if (document.getElementById('jenisPartnerProduk').checked) {
                document.getElementById('inputID').value = dtIDSubcontProduk.idpartner;
            }
            //untuk mengambil nilai radio button apabila mengklik partner bahan baku
            else if (document.getElementById('jenisPartnerBB').checked){
                document.getElementById('inputID').value = dtIDSubcontBB.idpartner;
            }
            
        }

    </script>
</body>

</html>