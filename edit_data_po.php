<?php
    include("layout/session.php");
?>

<?php
    $pesan_diterima="";
    $pesan_diterima_id_po="";
    $pesan_diterima_id_partner="";
    $pesan_diterima_nama_partner="";
    $pesan_diterima_tanggal_terbit="";

    if (isset($_GET["pesandikirim"])) {
        $pesan_diterima = $_GET["pesandikirim"];
        $pesan_diterima_id_po = $_GET['id_po'];
        $pesan_diterima_id_partner=$_GET['id_partner'];
        $pesan_diterima_nama_partner=$_GET['nama_partner'];
        $pesan_diterima_tanggal_terbit = $_GET['tanggal_terbit'];
    }

?>

<?php
    include("layout/koneksi.php");

    // cek apakah form telah di submit
    if (isset($_POST["submit"])) {
        // form telah disubmit, proses data
        
        // ambil semua nilai form

        $inputID = htmlentities(strip_tags(trim($_POST["inputID"])));
        $inputIDPartner = htmlentities(strip_tags(trim($_POST["inputIDPartner"])));
        $inputNamaPartner = htmlentities(strip_tags(trim($_POST["inputNamaPartner"])));
        $inputTanggalTerbit = htmlentities(strip_tags(trim($_POST["inputTanggalTerbit"])));
        $inputIDProduk = htmlentities(strip_tags(trim($_POST["inputIDProduk"])));
        $inputNamaProduk = htmlentities(strip_tags(trim($_POST["inputNamaProduk"])));
        $inputUOM = htmlentities(strip_tags(trim($_POST["inputUOM"])));
        $inputQty = htmlentities(strip_tags(trim($_POST["inputQty"])));


        //menyiapkan variabel untuk pesan error
        $pesan="";
        $warna="";
        $pk="";

        $query = "SELECT * FROM po WHERE id_po='$inputID' and id_produk='$inputIDProduk'";
        $result = mysqli_query($link, $query);
        $jumlah_data = mysqli_num_rows($result);
        
        $query2 = "SELECT * FROM po_pk WHERE id_po='$inputID'";
        $result2 = mysqli_query($link, $query2);
        $jumlah_data2 = mysqli_num_rows($result2);

        $queryproduk = "SELECT * FROM po WHERE id_po='$inputID' and id_produk='$inputIDProduk'";
        $resultproduk = mysqli_query($link, $queryproduk);
        $jumlah_dataproduk = mysqli_num_rows($resultproduk);

        if ($jumlah_data2 >= 1 ) {
            $pk = "tidak diinput";
        }

        if ($jumlah_data >= 1 ) {
             $pesan .= "ID Produk yang sama sudah digunakan. ";  
        }

        else if (strlen($inputID)>20) {
            $pesan .="ID hanya diisi maksimal 20 digit.";
        }

        else if ($inputIDPartner=="0") {
            $pesan .="ID Partner harus diisi.";
        }

        else if ($inputIDProduk=="0") {
            $pesan .="ID Produk harus diisi.";
        }
        
        else if ($inputTanggalTerbit=="") {
            $pesan .="Tanggal Terbit harus diisi.";
        }

        else if ($jumlah_dataproduk>=1) {
            $pesan .="Data produk yang sama sudah digunakan.";
        }


        if ($pesan==="") {
            //jalankan query insert
            $query = "insert into po values ('$inputID','$inputIDProduk','$inputNamaProduk', '$inputQty', '$inputUOM')";
            $hasil = mysqli_query($link, $query);

            if ($pk !=="tidak diinput") {
                $query2 = "insert into po_pk values('$inputID','$inputIDPartner','$inputNamaPartner', '$inputTanggalTerbit', 'tidak')";
                $hasil2 = mysqli_query($link, $query2);
            }

            if ($hasil) {
                $pesan .="PO dengan ID $inputID berhasil ditambahkan.";
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
            $inputID=$pesan_diterima_id_po;
            $inputIDPartner=$pesan_diterima_id_partner;
            $inputNamaPartner=$pesan_diterima_nama_partner;
            $inputTanggalTerbit=$pesan_diterima_tanggal_terbit;
            $inputQty="";
        }
        else {
            $id_po=$_GET['id_po'];
            $result=mysqli_query($link, "select * from po_pk where id_po='$id_po'");
            $hasil=mysqli_fetch_assoc($result);

            $inputID= $hasil['id_po'];
            $inputIDPartner= $hasil['id_partner'];
            $inputNamaPartner=$hasil['nama_partner'];
            $inputTanggalTerbit=$hasil['tanggal_terbit'];
            $warna="";
            $pk="";
        }

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
    <title>Data PO</title>
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
                            <h6 class="m-0 font-weight-bold text-primary">Edit Data Purchase Order</h6>
                        </div>
                        <div class="card-body">
                            <form action="edit_data_po.php" class="col-10 offset-1" method="post">
                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label" for="inputID">ID PO</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" name="inputID" id="inputID" readonly value="<?php echo $inputID ?>">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label" for="inputIDPartner">ID Partner</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" name="inputIDPartner" id="inputIDPartner" readonly value="<?php echo $inputIDPartner?>">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label" for="inputNamaPartner">Nama Partner</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" name="inputNamaPartner" id="inputNamaPartner" readonly value="<?php echo $inputNamaPartner?>">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label" for="inputTanggalTerbit">Tanggal Terbit</label>
                                    <div class="col-sm-10 input-group date">
                                    <input type="text" class="form-control datepicker datetimepicker-input" name="inputTanggalTerbit" id="inputTanggalTerbit" data-provide="datepicker" data-date-format="yyyy-mm-dd" readonly value="<?php echo $inputTanggalTerbit ?>">
                                        <div class="input-group-append">
                                            <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label" for="inputIDProduk">ID Produk</label>
                                    <div class="col-sm-10">
                                        <select class="form-control" name="inputIDProduk" id="inputIDProduk" onchange="ubahProduk(this.value)">
                                            <?php
                                                include("layout/koneksi.php");
                                                echo"<option value=\"0\" selected>----Pilih----</option>";
                                                $query = "select id_produk, nama_produk, uom from produk"; // where (id_produk, nama_produk, uom) not in (select id_produk, nama_produk, uom from po)
                                                $jsArray= "var dtP = new Array();\n";

                                                $result = mysqli_query($link, $query);
                                                while ($hasil=mysqli_fetch_array($result)) {
                                                    echo "<option value=\"$hasil[id_produk]\">$hasil[id_produk]</option>";
                                                    $jsArray .= "dtP['" . $hasil['id_produk'] . "'] = {namaproduk:'" . addslashes($hasil['nama_produk']) . "',uomproduk:'".addslashes($hasil['uom'])."'};\n";
                                                }
                                            ?>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label" for="inputNamaProduk">Nama Produk</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" name="inputNamaProduk" id="inputNamaProduk" readonly>
                                    </div>
                                </div>


                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label" for="inputQty">Qty</label>
                                    <div class="col-sm-10">
                                        <input type="number" class="form-control" name="inputQty" id="inputQty" min="0" required value="<?php echo $inputQty ?>">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label" for="inputUOM">UOM</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" name="inputUOM" id="inputUOM" readonly>
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
                                                        <a href="data_po.php" class="btn btn-primary">Iya</a>
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
                                            <th>ID PO</th>
                                            <th>ID Produk</th>
                                            <th>Nama Produk</th>
                                            <th>Qty</th>
                                            <th>UOM</th>
                                            <th>Hapus</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                            if ($inputID=="") {
                                                
                                            }
                                            else {
                                                include("layout/koneksi.php");
                                            
                                                $query = "SELECT * from po where id_po='$inputID'";
                                                $nomor=1;
                                                $result = mysqli_query($link,$query);
                                                while($hasil = mysqli_fetch_assoc($result)){
                                                    echo "<tr>";
                                                    echo "<td>$nomor</td>";
                                                    echo "<td>$hasil[id_po]</td>";
                                                    echo "<td>$hasil[id_produk]</td>";
                                                    echo "<td>$hasil[nama_produk]</td>";
                                                    echo "<td>$hasil[qty]</td>";
                                                    echo "<td>$hasil[uom]</td>";
                                                    
                                                    $pengantar=urlencode($hasil['id_po']);
                                                    $pengantardua=urlencode($hasil['id_produk']);
                                                    echo "<td><a href=\"hapus_data_produk_po.php?id_po=$pengantar&id_produk=$pengantardua&edit=y\" class=\"btn btn-danger\">Hapus</a></td>";
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

    <!--Untuk tanggal-->
        <script type="text/javascript">
            $function () {
                $('.datepicker').datepicker({
                    startDate: '-3d';
                });

            };
        </script>

    <!--Ambil nilai select untuk dimasukkan kedalam textbox-->
    <script>
        <?php echo $jsArray; ?> 

        function ubahProduk(inputIDProduk){
            document.getElementById('inputNamaProduk').value = dtP[inputIDProduk].namaproduk;
            document.getElementById('inputUOM').value = dtP[inputIDProduk].uomproduk;
        }

    </script>
</body>

</html>