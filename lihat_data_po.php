<?php
    include("layout/session.php");
?>

<?php
        include("layout/koneksi.php");

        $id_po=$_GET['id_po'];

        $result=mysqli_query($link, "select b.id_po as id_po, b.id_partner as id_partner, 
        b.nama_partner as nama_partner, b.tanggal_terbit as tanggal_terbit, a.id_produk as id_produk, 
        a.nama_produk as nama_produk, a.qty as qty, a.uom as uom from po a, po_pk b 
        where a.id_po=b.id_po and a.id_po='$id_po'");
        $hasil=mysqli_fetch_assoc($result);

        $inputID=$hasil['id_po'];
        $inputIDPartner=$hasil['id_partner'];
        $inputNamaPartner=$hasil['nama_partner'];
        $inputTanggalTerbit=$hasil['tanggal_terbit'];
        $inputIDProduk=$hasil['id_produk'];
        $inputNamaProduk=$hasil['nama_produk'];
        $inputQty=$hasil['qty'];
        $inputUOM=$hasil['uom'];

    
?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>Data PO Customer</title>

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
                            <h6 class="m-0 font-weight-bold text-primary">Lihat Data PO Customer</h6>
                        </div>
                        <div class="card-body">
                            <form>
                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label" for="inputID">ID PO</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" name="inputID" id="inputID" required readonly value="<?php echo $inputID ?>">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label" for="inputIDPartner">ID Partner</label>
                                    <div class="col-sm-10">
                                    <input type="text" class="form-control" name="inputID" id="inputID" required readonly value="<?php echo $inputIDPartner ?>">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label" for="inputNamaPartner">Nama Partner</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" name="inputNamaPartner" id="inputNamaPartner" value="<?php echo $inputNamaPartner ?>" readonly>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label" for="inputTanggalTerbit">Tanggal Terbit</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" name="inputTanggalTerbit" id="inputTanggalTerbit" value="<?php echo $inputTanggalTerbit ?>" readonly>
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
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                            //apabila ID PO kosong
                                            if ($inputID=="") {
                                                
                                            }
                                            //apabila ID PO terisi
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