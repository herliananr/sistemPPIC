<?php
    if (isset($_POST["submit"])){
        $id_karyawan = htmlentities(strip_tags(trim($_POST["id_karyawan"])));
        $password = htmlentities(strip_tags(trim($_POST["password"])));

        $pesan="";

        // cek apakah "id karyawan" sudah diisi
        if (is_numeric($id_karyawan)) {
            $pesan .= "ID Karyawan tidak boleh diisi dengan angka <br>";
        }
        
        // cek apakah "password" sudah diisi
        if (strlen($password)>10) {
            $pesan .= "Password hanya diisi maksimal 10 digit <br>";
        }
        
        // buat koneksi ke mysql dari file connection.php
        include("layout/koneksi.php");

            
        // cek apakah id karyawan dan password ada di tabel karyawan
        $query = "SELECT * FROM karyawan WHERE id_karyawan = '$id_karyawan' AND password = '$password'";
        $result = mysqli_query($link,$query);

        if(mysqli_num_rows($result) == 0 )  { 
            // data tidak ditemukan, buat pesan error
            $pesan .= "ID karyawan atau Password tidak sesuai";
        }
        
        // bebaskan memory 
        mysqli_free_result($result);
        
        // tutup koneksi dengan database MySQL
        mysqli_close($link);
    
        // jika lolos validasi, set session 
        if ($pesan === "") {
            session_start();
            $_SESSION["id_kar"] = $id_karyawan;
            header("Location: index.php");
        }
    }
    else {
    // form belum disubmit atau halaman ini tampil untuk pertama kali 
    // memberi nilai awal
    $pesan = "";
    $id_karyawan = "";
    $password = "";
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

    <title>Login</title>

    <?php
    include ("layout/title_import.php");
    ?>

    <style>
        .container{
            position:relative;
        }

        .peringatan{
            position:absolute;
			top:5px;
            left: 165px;
			width:71%;
        }

        .card{
            top: 70px;    
        }

        .kartu{
            background-color: #dcdcdc;
        }
    </style>

</head>

<body class="bg-gradient-secondary">

    <div class="container">
        <?php
        // tampilkan error jika ada
        if ($pesan !== "") {
            echo "<div class=\"col-md-9 col-sm-9 alert alert-danger alert-dismissible fade show peringatan\" >$pesan<button type=\"button\" class=\"close\" data-dismiss=\"alert\">
            <span>&times;</span></button></div>";}
        ?>

        <!-- Outer Row -->
        <div class="row justify-content-center">
            <div class="col-xl-10 col-lg-10 col-md-9">

                <div class="card border-0 shadow-lg my-5 mx-5 kartu">

                    <div class="card-body p-0">
                        
                        <!-- Nested Row within Card Body -->
                        <div class="row">
                            <div class="col">
                                <div class="p-5">
                                    <div class="text-center">
                                        <h1 class="h4 text-gray-900 mb-4">Selamat  Datang</h1>
                                    </div>
                                    <form class="user" id="formlogin" action="login.php" method="post">
                                        <div class="form-group">
                                            <input type="text" class="form-control form-control-user"
                                                id="id_karyawan" name="id_karyawan" placeholder="Masukkan ID" value="<?php echo $id_karyawan?>" required>
                                        </div>
                                        <div class="form-group">
                                            <input type="password" class="form-control form-control-user"
                                                id="password" name="password" placeholder="Masukkan Password" value="<?php echo $password?>" required>
                                        </div>
                                        <div class="form group">
                                            <input type="submit" name="submit" id="submit" class="btn btn-primary btn-block btn-user" value="Masuk">
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

        </div>

    </div>

    <!-- Bootstrap core JavaScript-->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="js/sb-admin-2.min.js"></script>

</body>

</html>