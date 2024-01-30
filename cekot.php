art();
$koneksi = mysqli_connect("localhost", "root", "", "tikettt");
if(!isset($_SESSION['username'])){
    header('location:login.php');
  }
if (isset($_POST["add"])) {
    if (isset($_SESSION["cart"])) {
        $item_array_id = array_column($_SESSION["cart"], "product_id");
        if (!in_array($_GET["id"], $item_array_id)) {
            $count = count($_SESSION['cart']);
            $item_array = array(
                'product_id' => $_GET["id"],
                'foto' => $_POST["foto"],
                'item_name' => $_POST["name"],
                'product_price' => $_POST["price"],
                'item_quantity' => $_POST["quantity"],
            );
            $_SESSION["cart"][$count] = $item_array;
            echo '<script>alert("produk berhasil dimasukan keranjang")</script>';
            echo '<script>window.location="cart1.php"</script>';
        } else {
            echo '<script>alert("produk berhasil dimasukan keranjang")</script>';
            echo '<script>window.location="cart1.php"</script>';
        }
    } else {
        $item_array = array(
            'product_id' => $_GET["id"],
            'foto' => $_POST["foto"],
            'item_name' => $_POST["name"],
            'product_price' => $_POST["price"],
            'item_quantity' => $_POST["quantity"],
        );
        $_SESSION["cart"][0] = $item_array;
    }
}
if (isset($_GET["action"])) {
    if ($_GET["action"] == "delete") {
        foreach ($_SESSION["cart"] as $keys => $value) {
            if ($value["product_id"] == $_GET["id"]) {
                unset($_SESSION["cart"][$keys]);
                echo '<script>alert("Product has ben removed...!")</script>';
                echo '<script>window.location="cart1.php"</script>';
            }
        }
     } elseif($_GET["action"] == "beli"){

        if(isset($_POST['beli'])){
            $total=0;
            foreach($_SESSION["cart"] as $key => $value){
            $total = $total + ($value["item_quantity"] * $value["product_price"]);
            }
            $query = mysqli_query($koneksi, "SELECT * FROM pembeli WHERE username='$_SESSION[username]'");
            $d = mysqli_fetch_array($query);
            $id_pembeli = $d['id_pembeli'];
            $alamat = $_POST['alamat'];
            $sql = mysqli_query($koneksi, "INSERT into transaksi(total,tgl_transaksi,id_pembeli,alamat) VALUES ('$total','".date("Y-m-d")."','$id_pembeli','$alamat')");
        }
       $id_transaksi = mysqli_insert_id($koneksi);
        foreach($_SESSION["cart"] as $key => $value){
            $id_barang = $value['product_id'];
            $quantity = $value['item_quantity'];
            $q1 = "INSERT INTO detail VALUES('','$id_transaksi','$id_barang','$quantity')";
            $res = mysqli_query($koneksi,$q1);
        }
    unset($_SESSION["cart"]);
    echo '<script>alert("Terimkasih")</script>';
    echo "<script>window.location='cetak.php?id=".$id_transaksi."'</script>";
} 
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.bundle.min.js">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <title>Document</title>
</head>

<body>
    <div class=" container-fluid my-5 ">
        <div class="row justify-content-center ">
            <div class="col-xl-10">
                <div class="card shadow-lg ">
                    <div class="row p-2 mt-3 justify-content-between mx-sm-2">
                        <div class="col">
                            <div class="row justify-content-start ">
                            </div>
                        </div>
                        <div class="col-auto">
                            <img class="irc_mi img-fluid bell" src="https://i.imgur.com/uSHMClk.jpg" width="30" height="30">
                        </div>
                    </div>
                    <div class="row  mx-auto justify-content-center text-center">
                        <div class="col-12 mt-3 ">
                            <nav aria-label="breadcrumb" class="second ">
                            </nav>
                        </div>
                    </div>

                    <form action="cart1.php?action=beli"  method="POST">
                    <div class="row justify-content-around">
                        <div class="col-md-5">
                            <div class="card border-0">
                                <div class="card-header pb-0">
                                    <h2 class="card-title space ">Checkout</h2>
                                    <p class="card-text text-muted mt-4  space">SHIPPING DETAILS</p>
                                    <hr class="my-0">
                                </div>
                                <div class="card-body">
                                    <div class="row justify-content-between">
                                        <div class="col-auto mt-0">
                                            <p><b></b></p>
                                        </div>
                                        <div class="col-auto">
                                            <p><b></b> </p>
                                        </div>
                                    </div>
                                    <div class="row mt-4">
                                        <div class="col">
                                            <p class="text-muted mb-2">PAYMENT DETAILS</p>
                                            <hr class="mt-0">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="NAME" class="small text-muted mb-1">GOPAY,DANA,BNI,BRI</label>
                                        <input type="text" class="form-control form-control-sm" name="NAME" id="NAME" aria-describedby="helpId" placeholder="-">
                                    </div>
                                    <div class="form-group">
                                        <label for="NAME" class="small text-muted mb-1">MASUKAN NO/ID KARTU KALIAN</label>
                                        <input type="text" class="form-control form-control-sm" name="NAME" id="NAME" aria-describedby="helpId" placeholder="0xxxxxxxxxxx">
                                    </div>
                                    <div class="row no-gutters">
                                        <div class="col-sm-6 pr-sm-2">
                                            <div class="form-group">
                                                <label for="NAME" class="small text-muted mb-1">ALAMAT LENGKAP</label>
                                                <input type="text" class="form-control form-control-sm" name="alamat" id="NAME" aria-describedby="helpId" placeholder="alamat lengkap">
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label for="NAME" class="small text-muted mb-1">KODE POS KOTA ANDA</label>
                                                <input type="text" class="form-control form-control-sm" name="NAME" id="NAME" aria-describedby="helpId" placeholder="51353">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mb-md-5">
                                        <div class="col">
                                            <input type="submit" name="beli" class="btn  btn-lg btn-block"></input>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                        </form>
                        <div class="col-md-5">
                            <div class="card border-0 ">
                                <div class="card-header card-2">
                                    <p class="card-text text-muted mt-md-4  mb-2 space">YOUR ORDER <span class=" small text-muted ml-2 cursor-pointer"></span> </p>
                                    <hr class="my-2">
                                </div>
                                <?php
                                if (!empty($_SESSION["cart"])) {
                                    $total = 0;
                                foreach ($_SESSION["cart"] as $key => $value){
                                ?>
                                        <div class="card-body pt-0">
                                            <div class="row  justify-content-between">
                                                <div class="col-auto col-md-7">
                                                    <div class="media flex-column flex-sm-row">
                                                        <img class="rouned" src="img.1/<?= $value['foto']; ?>" width="62" height="62">
                                                        <div class="media-body  my-auto">
                                                            <div class="row ">
                                                                <div class="col-auto"><?= $value['item_name']; ?></div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="pl-0 flex-sm-col col-auto  my-auto"><?= $value['item_quantity']; ?></div>
                                                <div class=" pl-0 flex-sm-col col-auto  my-auto">Rp.<?= $value['product_price']; ?></div>
                                            </div>
                                <?php   try{
                                                     
                                                     $sub = $value['item_quantity'] *
                                                         $value['product_price'];
                                                         echo $sub;
                                        
                                                 }catch(exception $e){
                                                    echo 'massage: ' .$e->getmassage();
                                                 }
                                                
                                                    $total = $total + $sub; 
                                                    } ?>
                                    <hr class="my-2">
                                    <div class="row ">
                                        <div class="col">
                                            <div class="row justify-content-between">
                                                <div class="col-4">
                                                    <p class="mb-1"><b>Subtotal</b></p>
                                                </div>
                                                <div class="flex-sm-col col-auto">                                         
                                                 <p class='mb-1'><b><?=$total?></b></p>
                                               </div>
                                            </div>
                                            <?php 
                                        } ?>
                                    <div class="row mb-5 mt-4 ">
                                        <div class="col-md-7 col-lg-6 mx-auto"><a class="btn btn-block btn-outline-primary btn-lg" href="home.php">TAMBAH PEMBELIAN</a></div>
                                    </div>
                                        </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <style>
        body {
            background: linear-gradient(110deg, #BBDEFB 60%, #42A5F5 60%);
        }

        .shop {
            font-size: 10px;
        }

        .space {
            letter-spacing: 0.8px !important;
        }

        .second a:hover {
            color: rgb(92, 92, 92);
        }

        .active-2 {
            color: rgb(92, 92, 92)
        }


        .breadcrumb>li+li:before {
            content: "" !important
        }

        .breadcrumb {
            padding: 0px;
            font-size: 10px;
            color: #aaa !important;
        }

        .first {
            background-color: white;
        }

        a {
            text-decoration: none !important;
            color: #aaa;
        }

        .btn-lg,
        .form-control-sm:focus,
        .form-control-sm:active,
        a:focus,
        a:active {
            outline: none !important;
            box-shadow: none !important
        }

        .form-control-sm:focus {
            border: 1.5px solid #4bb8a9;
        }

        .btn-group-lg>.btn,
        .btn-lg {
            padding: .5rem 0.1rem;
            font-size: 1rem;
            border-radius: 0;
            color: white !important;
            background-color: #4bb8a9;
            height: 2.8rem !important;
            border-radius: 0.2rem !important;
        }

        .btn-group-lg>.btn:hover,
        .btn-lg:hover {
            background-color: #26A69A;
        }

        .btn-outline-primary {
            background-color: #fff !important;
            color: #4bb8a9 !important;
            border-radius: 0.2rem !important;
            border: 1px solid #4bb8a9;
        }

        .btn-outline-primary:hover {
            background-color: #4bb8a9 !important;
            color: #fff !important;
            border: 1px solid #4bb8a9;
        }

        .card-2 {
            margin-top: 40px !important;
        }

        .card-header {
            background-color: #fff;
            border-bottom: 0px solid #aaaa !important;
        }

        p {
            font-size: 13px;
        }

        .small {
            font-size: 9px !important;
        }

        .form-control-sm {
            height: calc(2.2em + .5rem + 2px);
            font-size: .875rem;
            line-height: 1.5;
            border-radius: 0;
        }

        .cursor-pointer {
            cursor: pointer;
        }

        .boxed {
            padding: 0px 8px 0 8px;
            background-color: #4bb8a9;
            color: white;
        }

        .boxed-1 {
            padding: 0px 8px 0 8px;
            color: black !important;
            border: 1px solid #aaaa;
        }

        .bell {
            opacity: 0.5;
            cursor: pointer;
        }

        @media (max-width: 767px) {
            .breadcrumb-item+.breadcrumb-item {
                padding-left: 0
            }
        }
    </style>
</body>

</html>