<?php
$host = "localhost";
$user = "root";
$pass = "";
$db = "cakezone";

$koneksi = mysqli_connect($host, $user, $pass, $db);
if (!$koneksi) {
    die("Tidak bisa terkoneksi ke database");
}

$id_barang="";
$jenis_kue="";
$stok="";
$harga="";
$foto="";
$error="";
$sukses="";

if(isset($_GET['op'])){
     $op=$_GET['op'];
}else{
    $op="";
}

if($op =='delete'){
    $id_barang=$_GET['id'];
    $sql1="delete from barang where id_barang='$id_barang'";
    $q1=mysqli_query($koneksi,$sql1);
    if($q1){
        $sukses="berhasil hapus data";
    }else{
        $error="gagal hapus data";
    }
}

if($op=='edit'){
    $id_barang=$_GET['id'];
    $sql1="select * from barang where id_barang = '$id_barang'";
    $q1=mysqli_query($koneksi,$sql1);
    $r1=mysqli_fetch_array($q1);
    $jenis_kue=$r1['jenis_kue'];
    $stok=$r1['stok'];
    $harga=$r1['harga'];
    $foto=$r1['foto'];

    if($jenis_kue==''){
        $error="data tidak ditemukan";
    }
}

if(isset($_POST['simpan'])){
    $jenis_kue=$_POST['jenis_kue'];
    $stok=$_POST['stok'];
    $harga=$_POST['harga'];
    $foto=$_FILES['foto']['name'];
    $ekstensi1= array('png','jpg','jpeg');
    $x = explode('.',$foto);
    $ekstensi = strtolower(end($x));
    $file_tmp = $_FILES['foto']['tmp_name'];

    if(in_array($ekstensi,$ekstensi1)=== true){
        move_uploaded_file($file_tmp, 'img/'.$foto);
    }else{
        echo"<script> alert('ekstensi tidak diperbolehkan')</script>";
    }

    if($jenis_kue && $stok && $harga && $foto){

        if($op=='edit'){
            $sql1="update barang set jenis_kue='$jenis_kue', stok='$stok' ,harga='$harga',foto='$foto' where id_barang='$id_barang' ";
            $q1=mysqli_query($koneksi,$sql1);
            if($q1){
                $sukses="data berhasil diupdate";
            }else{
                $error="data gagal diupdate";
            }
        }else{
            $sql1="insert into barang(jenis_kue,stok,harga,foto) values ('$jenis_kue',$stok,'$harga','$foto')";
        $q1=mysqli_query($koneksi,$sql1);
        if($q1){
            $sukses="berhasil memasukan data baru";
        }else{
            $error="gagal memasukan data";
        }
        }
    }else{
        $error="silakan masukan datanya";
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data barang</title>
    <!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4bw+/aepP/YC94hEpVNVgiZdgIC5+VKNBQNGCHeKRQN+PtmoHDEXuppvnDJzQIu9" crossorigin="anonymous">
  </head>
  <body>
    <h1>Hello, world!</h1>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-HwwvtgBNo3bZJJLYd8oVXjrBZt8cqVSpeBNS5n7C8IVInixGAoxmnlMuBnhbgrkm" crossorigin="anonymous"></script>
  </body>
</html>
    <style>
        .mx-auto {
            width: 600px
        }

        .card {
            margin-top: 10px;
        }
    </style>
</head>

<body>
<nav class="navbar bg-body-tertiary fixed-top">
  <div class="container-fluid">
  <a class="navbar-brand" href="#">Halaman Admin</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasNavbar" aria-controls="offcanvasNavbar" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasNavbar" aria-labelledby="offcanvasNavbarLabel">
      <div class="offcanvas-header">
        <h5 class="offcanvas-title" id="offcanvasNavbarLabel">Offcanvas</h5>
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
      </div>
      <div class="offcanvas-body">
        <ul class="navbar-nav justify-content-end flex-grow-1 pe-3">
        <li class="nav-item">
          <a class="nav-link" href="halamantransaksi.php">Halaman Transaksi</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="halamanuser.php">Halaman User</a>
          </li>
        <form class="d-flex mt-3" role="search">
          <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search">
          <button class="btn btn-outline-success" type="submit">Search</button>
        </form>
      </div>
    </div>
  </div>
</nav>
    <div class="mx-auto">
        <div class="card">
            <div class="card-header">
                Create / edit data
            </div>
            <div class="card-body">
                <?php
                if($error){
                ?>
                    <div class="alert alert-danger" role="alert">
                        <?php echo $error ?>
                    </div>                
                <?php
                }
                ?>
            <div class="card-body">
                <?php
                if($sukses){
                ?>
                    <div class="alert alert-success" role="alert">
                        <?php echo $sukses ?>
                    </div>                
                <?php
                 header("refresh:5;url=halamanbarang.php");
                }
                ?>
                <form action="" method="POST" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label for="jenis_kue" class="form-label">jenis_kue</label></label>
                        <input type="text" class="form-control" id="jenis_kue" name="jenis_kue" value="<?php echo $jenis_kue ?>">
                    </div>
                    <div class="mb-3">
                        <label for="stok" class="form-label">stok</label></label>
                        <input type="text" class="form-control" id="stok" name="stok" value="<?php echo $stok ?>">
                    </div>
                    <div class="mb-3">
                        <label for="harga" class="form-label">harga</label></label>
                        <input type="text" class="form-control" id="harga" name="harga" value="<?php echo $harga ?>">
                    </div>
                    <div class="mb-3">
                        <input type="file" class="form-control" id="foto" name="foto" value="<?php echo $foto ?>">
                    </div>
            </div>
            <div class="col-12">
                 <input type="submit" name="simpan" value="simpan data" class="btn btn-primary">
            </div>
            </form>
            <div class="card">
                <div class="card-header text-white bg-secondary">
                    data barang ditoko
                </div>
                <div class="card-body">
                    <table class="table">
                        <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">id_barang</th>
                                <th scope="col">jenis_kue</th>
                                <th scope="col">stok</th>
                                <th scope="col">harga</th>
                                <th scope="col">foto</th>
                            </tr>
                            <tbody>
                                <?php
                                 $sql2="select * from barang order by id_barang desc";
                                 $q2=mysqli_query($koneksi,$sql2);
                                 $urut=1;
                                 while($r2=mysqli_fetch_array($q2)){
                                    $id_barang=$r2['id_barang'];
                                    $jenis_kue=$r2['jenis_kue'];
                                    $stok=$r2['stok'];
                                    $harga=$r2['harga'];
                                    $foto=$r2['foto'];
                                 
                                    ?>
                                    <tr>
                                        <th scope="row"><?php echo $urut++ ?></th>
                                        <td scope="row"><?php echo $id_barang ?></td>
                                        <td scope="row"><?php echo $jenis_kue ?></td>
                                        <td scope="row"><?php echo $stok  ?></td>
                                        <td scope="row"><?php echo $harga ?></td>
                                        <td scope="row"><img src="bahanimg/<?=$foto?>" class="img-thumbnail" widht="100px" height="100px"></td>
                                        <td scope="row">
                                        <a href="halamanbarang.php?op=edit&id=<?php echo $id_barang ?>"> <button type="button" class="btn btn-danger">Edit</button></a>
                                        <a href="halamanbarang.php?op=delete&id=<?php echo $id_barang ?>"onclick="return confirm('yakin ingin delete?')"><button type="button" class="btn btn-warning">Delete</button></a>

                                        </td>
                                    </tr>
                                    <?php
                                 }
                                ?>
                            </tbody>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
</body>

</html