<?php
include "koneksi.php";
session_start();
if(!isset($_SESSION['user'])){
  header('location:index.php');
};

$nama_barang    ="";
$stok_barang    ="";
$harga_barang ="";
$kategori_barang="";
$foto="";
$sukses  ="";
$eror    ="";

if(isset($_GET['op'])){
  $op=$_GET['op'];
}else{
  $op="";
}

if($op=='delete'){
  $id_barang=$_GET['id'];
  $sql1="delete from barang where id_barang= '$id_barang'";
  $q1=mysqli_query($koneksi,$sql1);
  if($q1){
    $sukses="berhasil menghapus data";
  }else{
    $eror="Gagal menghapus data";
  }
}

if($op =='edit'){
  $id_barang      =$_GET['id'];
  $sql1    ="select * from barang where id_barang ='$id_barang'";
  $q1      =mysqli_query($koneksi,$sql1);
  $r1      =mysqli_fetch_array($q1);
  $nama_barang    =$r1['harga_barang'];
  $stok_barang    =$r1['stok_barang'];
  $harga_barang =$r1['harga_barang'];
  $kategori_barang=$r1['kategori_barang'];


  if($nama_barang==''){
    $eror="data tidak ditemukan";
  }
}

if(isset($_POST['simpan'])){
  $nama_barang   =$_POST['nama_barang'];
  $stok_barang   =$_POST['stok_barang'];
  $harga_barang  =$_POST['harga_barang'];
  $kategori_barang=$_POST['kategori_barang'];
  $foto=$_FILES['foto']['name'];
  $ekstensi1= array('png','jpg','jpeg');
  $x= explode('.',$foto);
  $ekstensi = strtolower(end($x));
  $file_tmp=$_FILES['foto']['tmp_name'];

  if(in_array($ekstensi,$ekstensi1)=== true){
    move_uploaded_file($file_tmp, 'img.1/'.$foto);
  }else{
    echo"<script> alert('ekstensi tidak diperbolehkan')</script>";
  }

  if($nama_barang && $stok_barang && $harga_barang && $kategori_barang && $foto){
    if($op=='edit'){
      $sql1="update barang set nama_barang='$nama_barang',stok_barang='$stok_barang',harga_barang='$harga_barang',foto='$foto',kategori_barang='$kategori_barang' where id_barang='$id_barang'";
      $q1=mysqli_query($koneksi,$sql1);
      if($q1){
        $sukses="Data berhasil di update";
      }else{
        $eror="Data Gagal di Update";
      }
    }else{
    $sql1="insert into barang (nama_barang,stok_barang,harga_barang,foto,kategori_barang) values('$nama_barang','$stok_barang','$harga_barang','$foto','$kategori_barang')";
     $q1= mysqli_query($koneksi,$sql1);
     if($q1){
      $sukses="berhasil memasukkan data";
     }else{
      $eror="gagal memasukkan data";
     }
    }
    
  }else{
    $eror="silakan memasukkan semua data";
  }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Barang</title>
    <!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
  </head>
  <body>
  
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>
  </body>
</html>
  <style>
    body{
      background: url(img/core-img/image.jpeg);
    }
  </style>
    <style>
      .mx-auto{width:800px}
      .card{margin-top:10px;}
      .nav-link{
        color:white;
      }
    </style>
</head>
<body>
<nav class="nav nav-pills nav-fill">
  <a class="nav-link active" aria-current="page" href="barang.php">BARANG</a>
  <a class="nav-link" href="pembeli2.php">PEMBELI</a>
  <a class="nav-link" href="transaksi.php">TRANSAKSI</a>
  <button type="button" class="btn btn-light" name="keluar" value="keluar">KELUAR</button>
</nav>
    <div class="mx-auto">
        <div class="card">
  <div class="card-header">
    Create /Edit BARANG FURNITURE
  </div>
  <div class="card-body">
    <?php
    if($eror){
        ?>

        <div class="alert alert-danger" role="alert">
  <?php  echo $eror?>
</div>
        <?php
        header("refresh:5;url=barang.php");
    }
    ?>
    <?php
    if($sukses){
        ?>

        <div class="alert alert-success" role="alert">
 <?php  echo $sukses?>
</div>
        <?php header("refresh:5;url=barang.php");
    }
    ?>
    <form action="" method="POST" enctype="multipart/form-data">
   <div class="mb-3 row">
    <label for="nama_barang" class="col-sm-2 col-form-label">NAMA BARANG</label>
    <div class="col-sm-10">
      <input type="text" class="form-control" id="nama_barang" name="nama_barang" value="<?php echo $nama_barang?>">
    </div>
  </div>
   <div class="mb-3 row">
    <label for="stok_barang" class="col-sm-2 col-form-label">STOK BARANG</label>
    <div class="col-sm-10">
      <input type="text" class="form-control" id="stok_barang" name ="stok_barang" value="<?php echo $stok_barang?>">
    </div>
  </div>
  <div class="mb-3 row">
    <label for="harga_barang" class="col-sm-2 col-form-label">HARGA BARANG</label>
    <div class="col-sm-10">
      <input type="text" class="form-control" id="harga_barang" name ="harga_barang" value="<?php echo $harga_barang?>">
    </div>
  </div>
  <div class="mb-3 row">
    <label for="foto" class="col-sm-2 col-form-label">FOTO BARANG</label>
    <div class="col-sm-10">
      <input type="file" class="form-control" id="foto" name ="foto" value="<?php echo $foto?>">
    </div>
  </div>
  <div class="mb-3 row">
    <label for="kategori_barang" class="col-sm-2 col-form-label">KATEGORI BARANG</label>
    <div class="col-sm-10">
      <select class="form-control" id="kategori_barang" name="kategori_barang">
        <option value="">PILIH KATEGORI</option>
        <option value="chairs"<?php if($kategori_barang=="chairs") echo"selected"?>>CHAIRS</option>
        <option value="table"<?php if($kategori_barang=="table") echo"selected"?>>TABLE</option>
        <option value="homedeco"<?php if($kategori_barang=="homedeco") echo"selected"?>>HOME DECO</option>
        <option value="beds"<?php if($kategori_barang=="beds") echo"selected"?>>BEDS</option>
      </select>
    </div>
  </div>
  <div class="col-12">
    <input type="submit" name="simpan" value="simpan data" class="btn btn-primary">
  </div>
    </form>
  </div>
</div>



<div class="card">
  <div class="card-header text-white bg-secondary">
   Data Barang Furniture
  </div>
  <div class="card-body">
  <table class="table">
    <thead>
      <tr>
        <th scope="col">#</th>
        <th scope="col">NAMA</th>
        <th scope="col">STOK</th>
        <th scope="col">HARGA</th>
        <th scope="col">FOTO</th>
        <th scope="col">KATEGORI</th>
        <th scope="col">AKSI</th>
      </tr>
      <tbody>
        <?php
       
        $sql2="select * from barang order by id_barang desc";
        $q2=mysqli_query($koneksi,$sql2);
        $urut=1;
        while($r2=mysqli_fetch_array($q2)){
          $id=$r2['id_barang'];
          $nama_barang=$r2['nama_barang'];
          $stok_barang=$r2['stok_barang'];
          $harga_barang=$r2['harga_barang'];
          $foto=$r2['foto'];
          $kategori_barang=$r2['kategori_barang'];

          ?>
            <tr>
              <th scope="row"><?php  echo $urut++ ?></th>
              <td scope="row"><?php echo $nama_barang?></td>
              <td scope="row"><?php echo $stok_barang?></td>
              <td scope="row"><?php echo $harga_barang?></td>
              <td scope="row"><img src="img.1/<?=$foto?>" class="img-thumbnail" width="100px" height="100px"></td>
              <td scope="row"><?php echo $kategori_barang?></td>
              <td scope="row">
                <a href="barang.php?op=edit&id=<?php echo $id ?>"><button type="button" class="btn btn-warning">Edit</button></a>
                <a href="barang.php?op=delete&id=<?=$id?>" onclick="return confirm('yakin mau delete data')"> <button type="button" class="btn btn-danger">Delete</button></a>
               
                
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
</html>