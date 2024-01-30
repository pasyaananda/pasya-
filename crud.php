<?php
include 'koneksi.php';
$host = "localhost";
$user = "root";
$pass = "";
$db = "cakezone";

$koneksi = mysqli_connect($host, $user, $pass, $db);
if (!$koneksi) { //cek koneksi
  die("tidak bisa terkoneksi ke database");
}

$nama = "";
$alamat = "";
$harga_kue= "";
$jenis_kue = "";
$foto = "";
$error = "";
$sukses = "";

if (isset($_GET['op'])) {
  $op = $_GET['op'];
} else {
  $op = "";
}

if ($op == 'delete') {
  $id = $_GET['id'];
  $sql1 = "delete from admin where id_pembeli = '$id'";
  $q1 = mysqli_query($konek, $sql1);
  if ($q1) {
    $sukses = "Berhasil hapus data";
  } else {
    $error = "Gagal melakukan delete data";
  }
}

if ($op == 'edit') {
  $id = $_GET['id'];
  $sql1 = "select * from admin where id_pembeli = '$id'";
  $q1 = mysqli_query($koneksi, $sql1);
  $r1 = mysqli_fetch_array($q1);
  $nama = $r1['nama'];
  $alamat = $r1['alamat'];
  $harga_kue = $r1['harga_kue'];
  $jenis_kue = $r1['jenis_kue'];
 

  if ($nama == '') {
    $error = "Data tidak ditemukan";
  }
}
if (isset($_POST['simpan'])) { //untuk create
  
  $nama = $_POST['nama'];
  $alamat = $_POST['alamat'];
  $harga_kue = $_POST['harga_kue'];
  $jenis_kue = $_POST['jenis_kue'];
   $foto = $_FILES['foto']['name'];
   $ekstensi1=array('png','jpg','jpeg');
   $x=explode('.',$foto);
   $ekstensi=strtolower(end($x));
   $file_tmp=$_FILES['foto']['tmp_name'];
   if(in_array($ekstensi, $ekstensi1)===true){
    move_uploaded_file($file_tmp, 'img/'.$foto);
   }
   else{
    echo "<script>alert ('Eksentasi tidak diperbolehkan')</script>";
   }
   $nama =$_POST['nama'];

  if ($nama && $alamat && $harga_kue && $jenis_kue && $foto) {
    if ($op == 'edit') { //untuk update
      $sql1 = "update admin set nama = '$nama', alamat = '$alamat', harga_kue='$harga_kue', jenis_kue='$jenis_kue', foto='$foto' where id_pembeli='$_GET[id]'";
      $q1 = mysqli_query($konek, $sql1);
      if ($q1) {
        $sukses = "Data berhasil diupdate";
      } else {
        $error = "Data gagal diupdate";
      }
    } else { //untuk insert
      $sql1 = "insert into admin(Nama,alamat,harga_kue,jenis_kue,foto) values ('$nama','$alamat','$harga_kue','$jenis_kue','$foto')";
      $q1 = mysqli_query($koneksi, $sql1);
      if ($q1) {
        $sukses = "Berhasil memasukkan data baru";
      } else {
        $error = "Gagal memasukkan data";
      }
    }
  } else {
    $error = "Silahkan masukkan semua data";
  }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>cakezone</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-4bw+/aepP/YC94hEpVNVgiZdgIC5+VKNBQNGCHeKRQN+PtmoHDEXuppvnDJzQIu9" crossorigin="anonymous">
  <style>
    .mx-auto {
      width: 800px;
    }

    .card {
      margin-top: 10px;
    }
  </style>
</head>

<body>
  <div class="mx-auto">
    <!----untuk memasukan data---->
    <div class="card">
      <div class="card-header">
        Create / edit data
      </div>
      <div class="card-body">
        <?php
        if ($error) {
          ?>
          <div class="alert alert-danger" role="alert">
            <?php echo $error ?>
          </div>
          <?php
          header("refresh:3;url=crud.php"); //5 : detik
        }
        ?>
        <?php
        if ($sukses) {
          ?>
          <div class="alert alert-success" role="alert">
            <?php echo $sukses ?>
          </div>
          <?php
          header("refresh:3;url=crud.php"); //5 : detik
        }
        ?>
        
        <form action="crud.php" method="POST" enctype="multipart/form-data">
           
          
          <div class="mb-3 row">
            <label for="nama" class="col-sm-2 col-form-label">nama</label>
            <div class="col-sm-10">
              <input type="text" class="form-control" id="nama" name="nama" value="<?php echo $nama ?>">
            </div>
          </div>
          <div class="mb-3 row">
            <label for="alamat" class="col-sm-2 col-form-label">alamat</label>
            <div class="col-sm-10">
              <input type="text" class="form-control" id="alamat" name="alamat" value="<?php echo $alamat ?>">
            </div>
          </div>
          <div class="mb-3 row">
            <label for="produk" class="col-sm-2 col-form-label">harga_kue</label>
            <div class="col-sm-10">
              <input type="text" class="form-control" name="harga_kue" id="harga_kue" value="<?php echo $harga_kue ?>">
              </div>
            </div>
          <div class="mb-3 row">
            <label for="produk" class="col-sm-2 col-form-label">jenis_kue</label>
            <div class="col-sm-10">
              <input type="text" class="form-control" name="jenis_kue" id="jenis_kue" value="<?php echo $jenis_kue ?>">
              </div>
            </div>
          <div class="mb-3 row">
            <label for="produk" class="col-sm-2 col-form-label">foto</label>
            <div class="col-sm-10">
              <input type="file" class="form-control" name="foto" id="foto" value="<?php echo $foto ?>">
                
                
              </div>
            </div>
            <div class="col-12">
              <input type="submit" name="simpan" value="Simpan data" class="btn btn-primary">
            </div>
          </form>
          <!--untuk mengeluarkan data-->

        </div>
      </div>
      <div class="card">
        <div class="card-header text-white bg-secondary">
          data pembelian
        </div>
        <div class="card-body">
          <table class="table">
            <thead>
              <tr>
                <th scope="col">#</th>

                <th scope="col">nama</th>
                <th scope="col">alamat</th>
                <th scope="col">harga_kue</th>
                <th scope="col">jenis_kue</th>
                <th scope="col">foto</th>
              </tr>
            <tbody>
              <?php
                $sql2 = "select * from admin order by id_pembeli";
                $q2 = mysqli_query($koneksi, $sql2);
                $urut = 1;
                while ($r2 = mysqli_fetch_array($q2)) {
                  $id = $r2['id_pembeli'];
                  $nama = $r2['nama'];
                  $alamat = $r2['alamat'];
                  $harga = $r2['harga_kue'];
                  $jenis = $r2['jenis_kue'];
                  $foto = $r2['foto'];

                  ?>
              <tr>
                <th scope="row">
                  <?php echo $urut++ ?>
                </th>
                
                <td scope="row">
                  <?php echo $nama ?>
                </td>
                <td scope="row">
                  <?php echo $alamat ?>
                </td>
                <td scope="row">
                  <?php echo $harga_kue ?>
                </td>
                <td scope="row">
                  <?php echo $jenis_kue ?>
                </td>
                <td scope="row"><img src="img/<?= $foto?>" class="img-thumbnail" width="100px" height="100px">
                </td>
                <td scope="row">
                  <a href="crud.php?op=edit&id=<?php echo $id ?>"><button type="button"
                      class="btn btn-warning">Edit</button></a>
                  <a href="crud.php?op=delete&id=<?php echo $id ?>"> <button type="button" class="btn btn-danger"
                      onclick="return confirm('Yakin ingin delete data?')">Delete</button></a>
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
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"
      integrity="sha384-HwwvtgBNo3bZJJLYd8oVXjrBZt8cqVSpeBNS5n7C8IVInixGAoxmnlMuBnhbgrkm"
      crossorigin="anonymous"></script>
</body>

</html>