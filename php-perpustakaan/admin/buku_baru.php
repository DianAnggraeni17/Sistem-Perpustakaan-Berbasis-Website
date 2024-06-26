<?php
include'../koneksi.php';
include'../proteksi.php';
include'sidebar.php';
 ?>

 <html>
 <head>
 <meta name="viewport" content="width=device-width, initial-scale=1">
 <link rel="stylesheet" href="../img/csskonten.css">
 <link rel="stylesheet" href="../css/bootstrap.css">
 <link rel="stylesheet" href="../img/cssform.css">
 </head>
 <body>


 <div class="main">
   <b><i class="fa fa-Book"></i>Book Information</b><a href="#" rel="tooltip" title="Add Book"><button type="button" name="button" class="btn btn-secondary" style="margin-left:70%" onclick="document.getElementById('id01').style.display='block'" style="width:auto;"><i class="fa fa-fw fa-plus"></i></button>
   </a><a href="buku_print.php" target="blank" rel="tooltip" title="Print"><button type="button" name="button" class="btn btn-success"><i class="fa fa-fw fa-print"></i></button></a>
   <div id="id01" class="modal">

     <?php
      $sql = $koneksi -> prepare("SELECT max(kode_buku) as kd_buku FROM buku");
      $sql -> execute();
      $hsl = $sql -> fetch();
      $kode = $hsl['kd_buku'];
      $nourut = (int) substr($kode, 3, 3);
      $nourut++;
      $char = "BK";
      $new = $char . sprintf("%03s", $nourut);
     ?>

     <form class="modal-content animate" action="buku_proses.php?prabowo=tambah" method="post">
       <div class="imgcontainer">
         <h3>Add Book</h3>
       </div>

       <div class="container1">
         <input type="text" name="kd_buku" readonly value="<?php echo $new; ?>">

         <input type="text" placeholder="Add Book Title" name="judul" required>

         <input type="text" placeholder="Add Book Author" name="pengarang" required>

         <select name="kat" >
           <option></option>
           <?php
           $zxc = $koneksi -> prepare("SELECT * FROM kategori");
           $zxc -> execute();
           while($cat = $zxc -> fetch()){
           ?>
           <option value="<?php echo $cat['id_kategori']; ?>"><?php echo $cat['nama_kategori']; ?></option>
         <?php } ?>
         </select>

         <input type="text" placeholder="Add Book Stock" name="stok" required>

         <input type="text" placeholder="Add Book Year" name="tahun" required>

         <select name="status" >
           <option></option>
           <option>New</option>
           <option>Old</option>
           <option>Broken</option>
           <option>Lost</option>
         </select>
       </div>

       <div class="container1" style="background-color:#f1f1f1">
         <button type="button" onclick="document.getElementById('id01').style.display='none'" class="btn btn-danger">Back</button>
         <button type="submit" class="btn btn-success">Add</button>
       </div>
     </form>
   </div>


   <script>
   // Get the modal
   var modal = document.getElementById('id01');

   // When the user clicks anywhere outside of the modal, close it
   window.onclick = function(event) {
       if (event.target == modal) {
           modal.style.display = "none";
       }
   }
   </script>
<hr>



<ul class="nav nav-tabs">
  <li class="nav-item">
    <a class="nav-link  text-dark" href="buku.php">All <span class="sr-only">(current)</span></a>
  </li>
  <li class="nav-item">
    <a class="nav-link text-dark" href="buku_baru.php">New</a>
  </li>
  <li class="nav-item">
    <a class="nav-link text-dark" href="buku_lama.php">Old</a>
  </li>
  <li class="nav-item">
    <a class="nav-link text-dark" href="buku_rusak.php">Broken</a>
  </li>
  <li class="nav-item">
    <a class="nav-link text-dark" href="buku_hilang.php">Lost</a>
  </li>
</ul>
<form class="" action="" method="post">
                <div class="input-group input-group-sm" style="width: 150px; margin-left: 950px;">
                  <input type="text" name="search" class="form-control float-right" placeholder="Search">

                  <div class="input-group-append">
                    <button type="submit" class="btn btn-default"><i class="fa fa-search"></i></button>
                  </div>
                </div>
                </form>

    <table class="table table-hover">
      <thead align=center>
        <tr>
          <th scope="col">No</th>
          <th scope="col">Book Code</th>
          <th scope="col">Title</th>
          <th scope="col">Author</th>
          <th scope="col">Category</th>
          <th scope="col">Stock</th>
          <th scope="col">Year</th>
          <th scope="col">Book Status</th>
          <th scope="col" colspan="3">Action</th>
        </tr>
      </thead>
      <?php
      if(isset($_POST['search'])){
        $param = $_POST['search'];
        $asd = $koneksi->prepare("SELECT * FROM buku WHERE judul LIKE :1 OR kode_buku LIKE :1 ");
        $asd->bindParam(':1', $param);
        $asd->execute();
      }else{
      $asd = $koneksi -> prepare("SELECT * FROM buku WHERE status = 'baru'");
      $asd -> execute();
    }
      $i = 1;
      while($data = $asd -> fetch()){
      $id = $data['id_kategori'];
      $kat = $koneksi -> prepare("SELECT * FROM kategori WHERE id_kategori = :kategori");
      $kat -> bindParam(':kategori', $id);
      $kat -> execute();
      $hasil = $kat -> fetch();
      ?>
      <tbody>
        <tr>
          <td><?php echo $i ?></td>
          <td><?php echo $data['kode_buku']; ?></td>
          <td><?php echo $data['judul']; ?></td>
          <td><?php echo $data['pengarang']; ?></td>
          <td><?php echo $hasil['nama_kategori']; ?></td>
          <td><?php echo $data['stok']; ?></td>
          <td><?php echo $data['tahun']; ?></td>
          <td><?php echo $data['status']; ?></td>
          <td>
            <a rel="tooltip" title="Pinjam" href="pinjam_tambah.php?agung=<?php echo $data['kode_buku']; ?>">
            <i class="fa fa-fw fa-pencil"></i></a>
          </td>
          <td>
            <a rel="tooltip" title="Edit" href="buku_edit.php?agung=<?php echo $data['kode_buku']; ?>">
            <i class="fa fa-fw fa-wrench"></i></a>
          </td>
          <td>
            <a rel="tooltip" title="Hapus" href="buku_proses.php?prabowo=hapus&app=<?php echo $data['kode_buku']; ?>" onclick="return confirm('apakah anda yakin?')">
            <i class="fa fa-fw fa-trash"></i></a>
          </td>
        </tr>
      </tbody>
      <?php $i++;  } ?>
    </table>
   </div>

 </body>
 </html>
