<?php
        include("koneksi.php");
        // cek apakah id karyawan dan password ada di tabel karyawan
        $query = "SELECT * FROM karyawan WHERE id_karyawan = '$isi_sesi'";
        $result = mysqli_query($link,$query);
        $hasil = mysqli_fetch_assoc($result);

        //untuk hak akses sidebar
        $masterdatanone="";
        $penyimpanannone="";
        $transaksinone="";
        $karyawannone="";
        $partnernone="";
        $uomnone="";
        $popembelinone="";
        $sdnone="";
        $produknone="";
        $bbnone="";
        $bomnone="";
        $stokbbnone="";
        $stokproduknone="";
        $historibbmasuknone="";
        $historibbkeluarnone="";
        $historiprodukmasuknone="";
        $historiprodukkeluarnone="";
        $leadtimenone="";
        $mpsnone="";
        $mrpnone="";
        $ponone="";
        $spnone="";
        $pengendalianproduksinone="";
        $sjnone="";
        
        if ( $hasil['peran'] == "administrator"){
            $karyawannone="";
        }
        else if ($hasil['peran'] == "sistemppic") {
            $karyawannone="";
        }
        else {
            $karyawannone="d-none";
        }
        
            if ($hasil['peran']=="administrator") {
                $partnernone="d-none";
                $uomnone="d-none";
                $popembelinone="d-none";
                $sdnone="d-none";
                $produknone="d-none";
                $bbnone="d-none";
                $bomnone="d-none";
                $penyimpanannone="d-none";
                $transaksinone="d-none";
            }
            else if ($hasil['peran']=="warehouse") {
                $masterdatanone="d-none";
                $stokbbnone="";
                $stokproduknone="";
                $historibbmasuknone="";
                $historibbkeluarnone="";
                $historiprodukmasuknone="";
                $historiprodukkeluarnone="";
                $leadtimenone="d-none";
                $mpsnone="d-none";
                $mrpnone="d-none";
                $ponone="d-none";
                $spnone="d-none";
                $pengendalianproduksinone="";
                $sjnone="";
            }
            else if ($hasil['peran']=="engineering") {
                $partnernone="d-none";
                $uomnone="d-none";
                $popembelinone="d-none";
                $sdnone="d-none";
                $produknone="d-none";
                $bbnone="d-none";
                $bomnone="";
                $penyimpanannone="d-none";
                $transaksinone="d-none";
            }
            else if ($hasil['peran']=="purchasing") {
                $partnernone="d-none";
                $uomnone="d-none";
                $popembelinone="";
                $sdnone="d-none";
                $produknone="d-none";
                $bbnone="d-none";
                $bomnone="d-none";
                
                $penyimpanannone="d-none";
                $leadtimenone="d-none";
                $mpsnone="d-none";
                $mrpnone="d-none";
                $ponone="";
                $spnone="d-none";
                $pengendalianproduksinone="d-none";
                $sjnone="d-none";
            }
            else if ($hasil['peran']=="production") {
                $masterdatanone="d-none";
                $penyimpanannone="d-none";
                $leadtimenone="d-none";
                $mpsnone="d-none";
                $mrpnone="d-none";
                $ponone="d-none";
                $spnone="d-none";
                $pengendalianproduksinone="";
                $sjnone="d-none";
            }
            else if ($hasil['peran']=="qc") {
                $masterdatanone="d-none";
                $penyimpanannone="d-none";
                $leadtimenone="d-none";
                $mpsnone="d-none";
                $mrpnone="d-none";
                $ponone="d-none";
                $spnone="d-none";
                $pengendalianproduksinone="";
                $sjnone="d-none";
            }
            else if ($hasil['peran']=="sistemppic") {
                # code...
            }
            else if ($hasil['peran']=="ppic") {
                # code...
            }
            else {
                $masterdatanone="d-none";
                $penyimpanannone="d-none";
                $transaksinone="d-none";
            }



?>

        <!-- Sidebar -->
        <ul class="navbar-nav bg-purple-600 sidebar sidebar-dark accordion" id="accordionSidebar">

            <!-- Sidebar - Brand -->
            <a class="sidebar-brand d-flex align-items-center justify-content-center">
                <div class="sidebar-brand-text mx-1">sistem informasi PPIC</div>
            </a>

            <!-- Divider -->
            <hr class="sidebar-divider my-0">

            <!-- Nav Item - Dashboard -->
            <li class="nav-item active">
                <a class="nav-link" href="index.php">
                    <i class="fas fa-fw fa-tachometer-alt"></i>
                    <span>Dashboard</span></a>
            </li>

            <!-- Divider -->
            <hr class="sidebar-divider">

            <!-- Heading -->
            <div class="sidebar-heading">
                Menu
            </div>

            <!-- Nav Item - Pages Collapse Menu -->
            <li class="nav-item">
                <a class="nav-link collapsed <?php echo $masterdatanone?>" href="#" data-toggle="collapse" data-target="#collapseTwo"
                    aria-expanded="true" aria-controls="collapseTwo">
                    <i class="fas fa-fw fa-cog"></i>
                    <span>Master Data</span>
                </a>
                <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <h6 class="collapse-header">Data</h6>
                        <a class="collapse-item <?php echo $karyawannone?>" href="data_karyawan.php">Data Karyawan</a>
                        <a class="collapse-item <?php echo $partnernone?>" href="data_partner.php">Data Partner</a>
                        <a class="collapse-item <?php echo $uomnone?>" href="data_uom.php">Data UOM</a>
                        <a class="collapse-item <?php echo $popembelinone?>" href="data_po.php">Data PO Customer</a>
                        <a class="collapse-item <?php echo $sdnone?>" href="data_schedule_delivery.php">Data Schedule Delivery</a>
                        <a class="collapse-item <?php echo $produknone?>" href="data_produk.php">Data Produk</a>
                        <a class="collapse-item <?php echo $bbnone?>" href="data_bahan_baku.php">Data Bahan Baku</a>
                        <a class="collapse-item <?php echo $bomnone?>" href="data_bom.php">Data BOM</a>
                        
                    </div>
                </div>
            </li>

            <li class="nav-item">
                <a class="nav-link collapsed <?php echo $penyimpanannone?>" href="#" data-toggle="collapse" data-target="#collapseThree"
                    aria-expanded="true" aria-controls="collapseThree">
                    <i class="fas fa-fw fa-box"></i>
                    <span>Penyimpanan</span>
                </a>
                <div id="collapseThree" class="collapse" aria-labelledby="headingThree" data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <h6 class="collapse-header">Stok</h6>
                        <a class="collapse-item <?php echo $stokbbnone?>" href="stok_bahan_baku.php">Stok Bahan Baku</a>
                        <a class="collapse-item <?php echo $stokproduknone?>" href="stok_produk.php">Stok Produk</a>
                        <h6 class="collapse-header">Histori</h6>
                        <a class="collapse-item <?php echo $historibbmasuknone?>" href="history_bb_masuk.php">Bahan Baku Masuk</a>
                        <a class="collapse-item <?php echo $historibbkeluarnone?>" href="history_bb_keluar.php">Bahan Baku Keluar</a>
                        <a class="collapse-item <?php echo $historiprodukmasuknone?>" href="history_produk_masuk.php">Produk Masuk</a>
                        <a class="collapse-item <?php echo $historiprodukkeluarnone?>" href="history_produk_keluar.php">Produk Keluar</a>
                    </div>
                </div>
            </li>

            <!-- Nav Item - Utilities Collapse Menu -->
            <li class="nav-item">
                <a class="nav-link collapsed <?php echo $transaksinone?>" href="#" data-toggle="collapse" data-target="#collapseUtilities"
                    aria-expanded="true" aria-controls="collapseUtilities">
                    <i class="fas fa-fw fa-wrench"></i>
                    <span>Transaksi</span>
                </a>
                <div id="collapseUtilities" class="collapse" aria-labelledby="headingUtilities"
                    data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <h6 class="collapse-header">Transaksi</h6>
                        <a class="collapse-item <?php echo $leadtimenone?>" href="lead_time.php">Lead Time</a>
                        <a class="collapse-item <?php echo $mpsnone?>" href="mps.php">MPS</a>
                        <a class="collapse-item <?php echo $mrpnone?>" href="mrp.php">MRP</a>
                        <a class="collapse-item <?php echo $ponone?>" href="purchase_order.php">Purchase Order</a>
                        <a class="collapse-item <?php echo $spnone?>" href="schedule_produksi.php">Schedule Produksi</a>
                        <a class="collapse-item <?php echo $pengendalianproduksinone?>" href="pengendalian_produksi.php">Pengendalian Produksi</a>
                        <a class="collapse-item <?php echo $sjnone?>" href="surat_jalan.php">Surat Jalan</a>
                    </div>
                </div>
            </li>

            <!-- Divider -->
            <hr class="sidebar-divider">

            <!-- Sidebar Toggler (Sidebar) -->
            <div class="text-center d-none d-md-inline">
                <button class="rounded-circle border-0" id="sidebarToggle"></button>
            </div>

        </ul>
        <!-- End of Sidebar -->