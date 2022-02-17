<?php
    include("layout/session.php");
?>

<?php
    include("layout/koneksi.php");
    $diterima_ket = $_GET['ket'];
    $diterima_id = $_GET['id_purchase_order'];
    $query = "SELECT * FROM purchase_order where id_purchase_order='$diterima_id'";
    $result = mysqli_query($link, $query);
    $hasil = mysqli_fetch_assoc($result);

    $inputID= $hasil['id_purchase_order'];
    $inputIDMRP= $hasil['id_mrp'];
    $inputIDProduk = $hasil['id_produk'];
?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>Purchase Order</title>
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
                            <h6 class="m-0 font-weight-bold text-primary">Lihat Purchase Order</h6>
                        </div>
                        <div class="card-body">
                            <form>
                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label" for="inputID">ID Purchase Order</label>
                                    <div class="col-sm-10">
                                    <input type="text" class="form-control" name="inputID" id="inputID" readonly value="<?php echo $inputID ?>">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label" for="inputIDMRP">ID MRP</label>
                                    <div class="col-sm-10">
                                    <input type="text" class="form-control" name="inputIDMRP" id="inputIDMRP" readonly value="<?php echo $inputIDMRP ?>">
                                    </div>
                                </div>
                                
                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label" for="inputIDProduk">ID Produk</label>
                                    <div class="col-sm-10">
                                    <input type="text" class="form-control" name="inputIDProduk" id="inputIDProduk" readonly value="<?php echo $inputIDProduk ?>">
                                    </div>
                                </div>
                                <div class="table-responsive">
                                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>ID Bahan Baku</th>
                                                <th>Nama Bahan Baku</th>
                                                <th>Tanggal Penerimaan</th>
                                                <th>Qty</th>
                                                <th>UOM</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                                if ($inputID=="") {
                                                    
                                                }
                                                else {
                                                    include("layout/koneksi.php");

                                                    if ($diterima_ket=="pembelian") {
                                                    $query = "select a.id_bahan_baku as id_bahan_baku, b.nama_bahan_baku as nama_bahan_baku, 
                                                    a.tanggal_penerimaan as tanggal_penerimaan, a.qty as qty, b.uom_pemakaian_bb as uom 
                                                    from purchase_order a, bom b where a.id_bahan_baku=b.id_bahan_baku and a.id_purchase_order='$inputID' 
                                                    and b.id_produk_bom='$inputIDProduk' and b.keterangan<>'tidak dibeli'";
                                                    }
                                                    else if ($diterima_ket=="subcontbb"){
                                                    $query = "select a.id_bahan_baku as id_bahan_baku, b.nama_bahan_baku as nama_bahan_baku, 
                                                    a.tanggal_penerimaan as tanggal_penerimaan, a.qty as qty, b.uom_pemakaian_bb as uom 
                                                    from purchase_order a, bom b where a.id_bahan_baku=b.id_bahan_baku and a.id_purchase_order='$inputID' 
                                                    and b.id_produk_bom='$inputIDProduk' and b.keterangan='tidak dibeli'";
                                                    }
                                                
                                                    
                                                    $nomor=1;
                                                    $result = mysqli_query($link,$query);
                                                    while($hasil = mysqli_fetch_assoc($result)){
                                                        echo "<tr>";
                                                        echo "<td>$nomor</td>";
                                                        echo "<td>$hasil[id_bahan_baku]</td>";
                                                        echo "<td>$hasil[nama_bahan_baku]</td>";
                                                        echo "<td>$hasil[tanggal_penerimaan]</td>";
                                                        echo "<td>$hasil[qty]</td>";
                                                        echo "<td>$hasil[uom]</td>";
                                                        $nomor++;
        
                                                    };
                                                }

                                            ?>
                                        </tbody>
                                    </table>
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