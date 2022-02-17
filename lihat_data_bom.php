<?php
    include("layout/session.php");
?>

<?php
        include("layout/koneksi.php");

        $id_produk_bom=$_GET['id_produk_bom'];

        $result=mysqli_query($link, "select * from bom where id_produk_bom='$id_produk_bom'");
        $hasil=mysqli_fetch_assoc($result);

        $inputIDProduk =  $hasil['id_produk_bom'];
        $inputNamaProduk = $hasil['nama_produk'];
    
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

                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Lihat Data Bill of Material</h6>
                        </div>
                        <div class="card-body">
                            <form>
                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label" for="inputIDProduk">ID Produk</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" name="inputIDProduk" id="inputIDProduk" readonly value="<?php echo $inputIDProduk?>">
                                                    
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label" for="inputNamaProduk">Nama Produk</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" name="inputNamaProduk" id="inputNamaProduk" readonly value="<?php echo $inputNamaProduk?>">
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
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                            //apabila ID PO kosong
                                            if ($inputIDProduk=="") {
                                                
                                            }
                                            //apabila ID PO terisi
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

</body>

</html>