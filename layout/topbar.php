                <!-- Topbar -->
                <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">

                    <!-- Sidebar Toggle (Topbar) -->
                    <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
                        <i class="fa fa-bars"></i>
                    </button>



                    <!-- Topbar Navbar -->
                    <ul class="navbar-nav ml-auto">

                        <!-- Nav Item - User Information -->
                        <li class="nav-item dropdown no-arrow">
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span class="mr-2 d-none d-lg-inline text-gray-600 small">
                                    <?php
                                        include("koneksi.php");
                                        // cek apakah id karyawan dan password ada di tabel karyawan
                                        $query = "SELECT * FROM karyawan WHERE id_karyawan = '$isi_sesi'";
                                        $result = mysqli_query($link,$query);

                                        $hasil=mysqli_fetch_assoc($result);
                                        $tampil_nama_karyawan=$hasil['nama_karyawan'];
                                        echo $tampil_nama_karyawan;
                                        
                                    ?>
                                </span>
                                <img class="img-profile rounded-circle"
                                    <?php
                                        include("koneksi.php");
                                        // cek apakah id karyawan dan password ada di tabel karyawan
                                        $query = "SELECT * FROM karyawan WHERE id_karyawan = '$isi_sesi'";
                                        $result = mysqli_query($link,$query);
                                        $hasil=mysqli_fetch_assoc($result);
                                        $tampil_gambar_karyawan= $hasil['jenis_kelamin'];

                                            if ($tampil_gambar_karyawan =='p'){
                                                $gambarprofil="img/undraw_profile_1.svg";
                                            }
                                            else if($tampil_gambar_karyawan =='l'){
                                                $gambarprofil="img/undraw_profile_2.svg";
                                            }
                                            else {
                                                $gambarprofil="img/system.svg";
                                            }
                                        
                                    ?>
                                src="<?php echo $gambarprofil?>">
                            </a>
                            <!-- Dropdown - User Information -->
                            <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in"
                                aria-labelledby="userDropdown">
                                <a class="dropdown-item" href="profil_pengguna.php">
                                    <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Profil
                                </a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="#" data-toggle="modal" data-target="#logoutModal">
                                    <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Keluar
                                </a>
                            </div>
                        </li>

                    </ul>

                </nav>
                <!-- End of Topbar -->