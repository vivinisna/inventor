<script language="javascript">
function validasi(form){
  if (form.nama.value == ""){
    alert("Anda belum mengisikan Nama.");
    form.nama.focus();
    return (false);
  }    
  if (form.alamat.value == ""){
    alert("Anda belum mengisikan Alamat.");
    form.alamat.focus();
    return (false);
  }
  if (form.telpon.value == ""){
    alert("Anda belum mengisikan Telpon.");
    form.telpon.focus();
    return (false);
  }
  if (form.email.value == ""){
    alert("Anda belum mengisikan Email.");
    form.email.focus();
    return (false);
  }
  if (form.kota.value == 0){
    alert("Anda belum mengisikan Kota.");
    form.kota.focus();
    return (false);
  }
  if (form.kode.value == ""){
    alert("Anda belum mengisikan Kode.");
    form.kode.focus();
    return (false);
  }
  return (true);
}

function validasi2(form2){
  if (form2.email.value == ""){
    alert("Anda belum mengisikan Email.");
    form2.email.focus();
    return (false);
  }
  if (form2.password.value == ""){
    alert("Anda belum mengisikan Password.");
    form2.password.focus();
    return (false);
  }
  return (true);
}

function harusangka(jumlah){
  var karakter = (jumlah.which) ? jumlah.which : event.keyCode
  if (karakter > 31 && (karakter < 48 || karakter > 57))
    return false;
  return true;
}
</script>

<?php
// Halaman utama (Home)
if ($_GET[module]=='home'){
  echo "<div class='center_title_bar'>Jenis Perangkat</div>";
  $sql=mysql_query("SELECT * FROM produk ORDER BY id_produk DESC LIMIT 12");
  while ($r=mysql_fetch_array($sql)){
    
    include "diskon_stok.php";

    echo "<div class='prod_box'>
          <div class='top_prod_box'></div> 
          <div class='center_prod_box'>            
             <div class='product_title'><a href='produk-$r[id_produk]-$r[produk_seo].html'>$r[merk]</a></div>
             <div class='product_img'>
               <a href='foto_produk/$r[gambar]' title='$r[merk]' class='lightbox'>
               <img src='foto_produk/small_$r[gambar]' border='0' height='110' title='klik untuk memperbesar gambar' /></a><br />
              </div>
           <div class='price'>$divharga  &nbsp;Stok : $stok</div>
            </div>
          <div class='bottom_prod_box'></div>
          <div class='prod_details_tab'>
             $tombol      
             <a href='produk-$r[id_produk]-$r[produk_seo].html' class='prod_details'>selengkapnya</a>            
          </div> 
          </div>";
  }
}



// Modul detail produk
elseif ($_GET[module]=='detailproduk'){
  // Tampilkan detail produk berdasarkan produk yang dipilih
	$detail=mysql_query("SELECT * FROM produk,sub_kategori    
                      WHERE sub_kategori.id_sub_kategori=produk.id_kategori 
                      AND id_produk='$_GET[id]'");
	$r = mysql_fetch_array($detail);
  
  include "diskon_stok.php";
  
  echo "<div class='center_title_bar'>Kategori: <a href='subkategori-$r[id_sub_kategori]-$r[nama_sub_kategori].html'>$r[nama_sub_kategori]</a></div>
    	  <div class='prod_box_big'>
        	<div class='top_prod_box_big'></div>
        <div class='center_prod_box_big'>            
                 <div class='product_img_big'>
                 <a href='#'><img src='foto_produk/$r[gambar]' border='0' /></a>
            <p align=center>(stok: $r[stok])</p>
            $tombol
            </div>
          <div class='details_big_box'>
            <div class='product_title_big'>$r[merk]</div>
              <div>$r[deskripsi]</div>
          </div>    
          </div>
            <div class='bottom_prod_box_big'></div>
          </div>";
          
// Produk Lainnya (random)          
  $sql=mysql_query("SELECT * FROM produk ORDER BY rand() LIMIT 12");
      
  echo "<div class='center_title_bar'>Produk Lainnya</div>";
      
  while ($r=mysql_fetch_array($sql)){

  include "diskon_stok.php";
    /* <a href='produk-$r[id_produk]-$r[produk_seo].html'>
     <img src='foto_produk/small_$r[gambar]' border='0' height='110'></a>*/
    echo "<div class='prod_box'>
          <div class='top_prod_box'></div> 
          <div class='center_prod_box'>            
             <div class='product_title'><a href='produk-$r[id_produk]-$r[produk_seo].html'>$r[merk]</a></div>
             <div class='product_img'>
              	<a href='foto_produk/$r[gambar]' title='$r[merk]' class='lightbox'>
               	<img src='foto_produk/small_$r[gambar]' border='0' height='110' title='klik untuk memperbesar gambar' /></a><br />
             </div>			 
			
          <div class='price'>$divharga  &nbsp;Stok : $stok</div>
            </div>
          <div class='bottom_prod_box'></div>
          <div class='prod_details_tab'>
             $tombol           
             <a href='produk-$r[id_produk]-$r[produk_seo].html' class='prod_details'>selengkapnya</a>            
          </div> 
          </div>";
  }                                      
}


// Modul produk per kategori
elseif ($_GET[module]=='detailkategori'){
  // Tampilkan nama kategori
  $sq = mysql_query("SELECT id_kategori,nama_kategori from kategori where id_kategori='$_GET[id]'");
  $n = mysql_fetch_array($sq);

  echo "<div class='center_title_bar'>Kategori: $n[nama_kategori]</div>";

  // Tentukan berapa data yang akan ditampilkan per halaman (paging)
  $p      = new Paging3;
  $batas  = 2;
  $posisi = $p->cariPosisi($batas);
  
  //get sub kategori berdasarkan kategori
  
  // Tampilkan daftar produk yang sesuai dengan kategori yang dipilih
  $sql = mysql_query("select sk.nama_sub_kategori,k.nama_kategori,p.id_produk,p.gambar from kategori k,(sub_kategori sk LEFT JOIN produk p 
					 on sk.id_sub_kategori=p.id_kategori) where sk.id_kategori='".$_GET[id]."' and sk.id_kategori=k.id_kategori ORDER BY id_produk DESC LIMIT $posisi,$batas");		 
  $jumlah = mysql_num_rows($sql);

	// Apabila ditemukan produk dalam kategori
	if ($jumlah > 0){
  while ($r=mysql_fetch_array($sql)){

  include "diskon_stok.php";

	 //<a href='produk-$r[id_produk]-$r[produk_seo].html'>
     //<img src='foto_produk/small_$r[gambar]' border='0' height='110'></a>

    echo "<div class='prod_box'>
          <div class='top_prod_box'></div> 
          <div class='center_prod_box'>            
             <div class='product_title'><a href='produk-$r[id_produk]-$r[produk_seo].html'>$r[merk]</a></div>
             <div class='product_img'>
              	<a href='foto_produk/$r[gambar]' title='$r[merk]' class='lightbox'>
                <img src='foto_produk/small_$r[gambar]' border='0' height='110' title='klik untuk memperbesar gambar' /></a><br />
            </div>
            
          <div class='price'>$divharga  &nbsp;Stok : $stok</div>
            </div>
          <div class='bottom_prod_box'></div>
          <div class='prod_details_tab'>
             $tombol           
             <a href='produk-$r[id_produk]-$r[produk_seo].html' class='prod_details'>selengkapnya</a>            
          </div> 
          </div>";
  } 
  $jmldata     = mysql_num_rows(mysql_query("select sk.nama_sub_kategori,k.nama_kategori,p.id_produk from kategori k,(sub_kategori sk LEFT JOIN produk p 
					 on sk.id_sub_kategori=p.id_kategori) where sk.id_kategori='".$_GET[id]."' and sk.id_kategori=k.id_kategori"));
  $jmlhalaman  = $p->jumlahHalaman($jmldata, $batas);
  $linkHalaman = $p->navHalaman($_GET[halkategori], $jmlhalaman);

  echo "<div class='center_title_bar'>Halaman : $linkHalaman </div>";
  }
  else{
    echo "<p align=center>Belum ada produk pada kategori ini.</p>";
  }
}



//sub kategori

// Modul produk per kategori
elseif ($_GET[module]=='detailsubkategori'){
  // Tampilkan nama kategori
  $sq = mysql_query("SELECT nama_sub_kategori from sub_kategori where id_kategori='$_GET[id]'");
  $n = mysql_fetch_array($sq);

  echo "<div class='center_title_bar'>Kategori: $n[sub_nama_kategori]</div>";

  // Tentukan berapa data yang akan ditampilkan per halaman (paging)
  $p      = new Paging6;
  $batas  = 12;
  $posisi = $p->cariPosisi($batas);

  // Tampilkan daftar produk yang sesuai dengan kategori yang dipilih
 	$sql = mysql_query("SELECT * FROM produk WHERE id_kategori='$_GET[id]' 
            ORDER BY id_produk DESC LIMIT $posisi,$batas");		 
	$jumlah = mysql_num_rows($sql);

	// Apabila ditemukan produk dalam kategori
	if ($jumlah > 0){
  while ($r=mysql_fetch_array($sql)){

  include "diskon_stok.php";
	//<a href='produk-$r[id_produk]-$r[produk_seo].html'>
	//<img src='foto_produk/small_$r[gambar]' border='0' height='110'></a>
    echo "<div class='prod_box'>
          <div class='top_prod_box'></div> 
          <div class='center_prod_box'>            
             <div class='product_title'><a href='produk-$r[id_produk]-$r[produk_seo].html'>$r[merk]</a></div>
             <div class='product_img'>
              <a href='foto_produk/$r[gambar]' title='$r[merk]' class='lightbox'>
              <img src='foto_produk/small_$r[gambar]' border='0' height='110' title='klik untuk memperbesar gambar' /></a><br> 
             </div>
            
			
          <div class='price'>$divharga  &nbsp;Stok : $stok</div>
            </div>
          <div class='bottom_prod_box'></div>
          <div class='prod_details_tab'>
             $tombol          
             <a href='produk-$r[id_produk]-$r[produk_seo].html' class='prod_details'>selengkapnya</a>            
          </div> 
          </div>";
  }  
  
  $jmldata     = mysql_num_rows(mysql_query("SELECT * FROM produk WHERE id_kategori='$_GET[id]'"));
  $jmlhalaman  = $p->jumlahHalaman($jmldata, $batas);
  $linkHalaman = $p->navHalaman($_GET[halkategori], $jmlhalaman);

  echo "<div class='center_title_bar'>Halaman : $linkHalaman </div>";
  }
  else{
    echo "<p align=center>Belum ada produk pada kategori ini.</p>";
  }
}




// Menu utama di header

// Modul profil
elseif ($_GET[module]=='profilkami'){
  // Data profil mengacu pada id_modul=43
	$profil = mysql_query("SELECT * FROM modul WHERE id_modul='43'");
	$r      = mysql_fetch_array($profil);

  echo "<div class='center_title_bar'>Profil</div>
    	  <div class='prod_box_big'>
        	<div class='top_prod_box_big'></div>
        <div class='center_prod_box_big'>            
                 <div class='product_img_big'>
                 <img src='foto_banner/$r[gambar]' border='0' />
            </div>
          <div class='details_big_box'>
            <div class='product_title_big'>Profil Warehouse</div>
              <div>$r[static_content]</div>
          </div>    
          </div>
            <div class='bottom_prod_box_big'></div>
          </div>";                              
}


// Modul cara pembelian
elseif ($_GET[module]=='carabeli'){
  // Data cara pembelian mengacu pada id_modul=45
	$profil = mysql_query("SELECT * FROM modul WHERE id_modul='45'");
	$r      = mysql_fetch_array($profil);

  echo "<div class='center_title_bar'>Cara Order</div>
    	  <div class='prod_box_big'>
        	<div class='top_prod_box_big'></div>
        <div class='center_prod_box_big'>            
                 <div class='product_img_big'>
                 <img src='foto_banner/$r[gambar]' border='0' />
            </div>
          <div class='details_big_box'>
            <div class='product_title_big'>Prosedur Order</div>
              <div>$r[static_content]</div>
          </div>    
          </div>
            <div class='bottom_prod_box_big'></div>
          </div>";                              
}


// Modul semua produk
elseif ($_GET[module]=='semuaproduk'){

  echo "<div class='center_title_bar'>Semua Produk</div>";
  // Tentukan berapa data yang akan ditampilkan per halaman (paging)
  $p      = new Paging2;
  $batas  = 12;
  $posisi = $p->cariPosisi($batas);

  // Tampilkan semua produk
  $sql=mysql_query("SELECT * FROM produk ORDER BY id_produk DESC LIMIT $posisi,$batas");

  while ($r=mysql_fetch_array($sql)){
  
    include "diskon_stok.php";

    echo "<div class='prod_box'>
          <div class='top_prod_box'></div> 
          <div class='center_prod_box'>            
             <div class='product_title'><a href='produk-$r[id_produk]-$r[produk_seo].html'>$r[merk]</a></div>
             <div class='product_img'>
               <a href='foto_produk/$r[gambar]' title='$r[nama_produk]' class='lightbox'>
               <img src='foto_produk/small_$r[gambar]' border='0' height='110' title='klik untuk memperbesar gambar' /></a><br />
              </div>
          <div class='price'>$divharga  &nbsp;Stok : $stok</div>
            </div>
          <div class='bottom_prod_box'></div>
          <div class='prod_details_tab'>
             $tombol          
             <a href='produk-$r[id_produk]-$r[produk_seo].html' class='prod_details'>selengkapnya</a>            
          </div> 
          </div>";
  }  
    
  $jmldata     = mysql_num_rows(mysql_query("SELECT * FROM produk"));
  $jmlhalaman  = $p->jumlahHalaman($jmldata, $batas);
  $linkHalaman = $p->navHalaman($_GET[halproduk], $jmlhalaman);

  echo "<div class='center_title_bar'>Halaman : $linkHalaman </div>";
}


// Modul keranjang belanja
elseif ($_GET[module]=='keranjangbelanja'){
  // Tampilkan produk-produk yang telah dimasukkan ke keranjang belanja
	$sid = session_id();
	$sql = mysql_query("SELECT * FROM orders_temp, produk 
			                WHERE id_session='$sid' AND orders_temp.id_produk=produk.id_produk");
  $ketemu=mysql_num_rows($sql);
  if($ketemu < 1){
    echo "<script>window.alert('Keranjang Order Masih Kosong');
        window.location=('index.php')</script>";
    }
  else{  
    echo "<div class='center_title_bar'>Keranjang Order Perangkat</div>
          <div class='prod_box_big'>
        	<div class='top_prod_box_big'></div>
        <div class='center_prod_box_big'>            
          <div class='details_big_cari'>
              <div>
          <form method=post action=aksi.php?module=keranjang&act=update>
          <table width=90% border=0 cellpadding=3 align=center>
          <tbody>
          <tr background='images/bg_tab.jpg' align=center height=23><th>No</th><th>Perangkat</th><th>Nama Perangkat</th><th>Serial Number</th><th>Qty</th>
          <th>Hapus</th></tr>";  
  
  $no=1;
  while($r=mysql_fetch_array($sql)){
    $disc        = ($r[diskon]/100)*$r[harga];
    $hargadisc   = number_format(($r[harga]-$disc),0,",",".");

    $subtotal    = ($r[harga]-$disc) * $r[jumlah];
    $total       = $total + $subtotal;  
    $subtotal_rp = format_rupiah($subtotal);
    $total_rp    = format_rupiah($total);
    $harga       = format_rupiah($r[harga]);
    
    echo "<tr bgcolor=#dad0d0><td>$no</td><input type=hidden name=id[$no] value=$r[id_orders_temp]>
              <td align=center><br><img src=foto_produk/small_$r[gambar]></td>
              <td>$r[merk]</td>
       			  <td align=center>$r[serial_number]</td>
              <td><select name='jml[$no]' value=$r[jumlah] onChange='this.form.submit()'>";
              for ($j=1;$j <= $r[stok];$j++){
                  if($j == $r[jumlah]){
                   echo "<option selected>$j</option>";
                  }else{
                   echo "<option>$j</option>";
                  }
              }
			 
        echo "</select></td>				
              <td align=center><a href='aksi.php?module=keranjang&act=hapus&id=$r[id_orders_temp]'>
              <img src=images/kali.png border=0 title=Hapus></a></td>
          </tr>";
    $no++; 
  } 
  echo "<tr><td colspan=4 align=right><br><b>Total</b>:</td><td colspan=2><br>Rp. <b>$total_rp</b></td></tr>
        <tr><td colspan=3><br /><a href='javascript:history.go(-1)' class='button'>Lanjutkan Order</a><br /></td>";		
  echo "</tr>
        </tbody></table></form><br />
      	<hr>	
		<form method=post action=simpan-transaksi-member.html>
          <table width=65% border=0 cellpadding=3 align=left>
          <tbody>
		  </tbody>
		  	<tr>
				<td>Pelanggan</td>
				<td>:</td>
				<td><input type=text name='nama_pelanggan' size=40></td>
			</tr>
			<tr>
				<td>Alamat Pelanggan</td>
				<td>:</td>
				<td><textarea name='alamat_pelanggan' rows=5 cols=40></textarea></td>
			</tr>
			<tr>
				<td>PIC Pelanggan</td>
				<td>:</td>
				<td><input type=text name='pic_pelanggan' size=40></td>
			</tr>
			<tr>
				<td>No Hp</td>
				<td>:</td>
				<td><input type=text name='no_hp_pelanggan' size=40></td>
			</tr>
			<tr>
				<td>Email</td>
				<td>:</td>
				<td><input type=text name='email_pelanggan' size=40></td>
			</tr>
			<tr>
				<td>Use For</td>
				<td>:</td>
				<td><select name='use_for'>
						<option value='PROVISIONING REGULER'>PROVISIONING REGULER</option>
						<option value='PROVISIONING PROJECT'>PROVISIONING PROJECT</option>
						<option value='TROUBLE HANDLING'>TROUBLE HANDLING</option>
						<option value='IMPROVEMENT'>IMPROVEMENT</option>
						<option value='OTHER'>OTHER</option>
					</select>
				</td>
			</tr>
			<tr>";
				if(!isset($_SESSION['nik']) && empty($_SESSION['nik'])){
					echo"<td colspan=4 align=right><br /><disable><a href='proses-order.html' class='button'>Submit Order<disable></a></td>";
					echo "<script>alert('Anda belum login. Silahkan login terlebih daluhu');</script>";
				}else{
			  		echo "<td colspan=4 align=right><br /><input type=submit name=submit value='Submit Order' class='button'><br /></td>";
				}
			echo "</tr>
		  </table>
		 </form>
		  
        </div>
        
          </div>    
          </div>
            <div class='bottom_prod_box_big'></div>
          </div>";
  }

}

// Modul hubungi kami
elseif ($_GET[module]=='hubungikami'){
  echo "<div class='center_title_bar'>Hubungi Kami</div>
    	  <div class='prod_box_big'>
        	<div class='top_prod_box_big'></div>
        <div class='center_prod_box_big'>            
                 <div class='product_img_big'>
                 <img src='foto_banner/gedung.jpg' border='0' />
            </div>
          <div class='details_big_box'>
            <div class='product_title_big'>Hubungi Kami Secara Online:</div>
              <div>
        <table width=100% style='border: 1pt dashed #0000CC;padding: 10px;'>
        <form action=hubungi-aksi.html method=POST>
        <tr><td>Nama</td><td> : <input type=text name=nama size=30></td></tr>
        <tr><td>Email</td><td> : <input type=text name=email size=30></td></tr>
        <tr><td>Subjek</td><td> : <input type=text name=subjek size=40></td></tr>
        <tr><td valign=top>Pesan</td><td> <textarea name=pesan  style='width: 270px; height: 100px;'></textarea></td></tr>
        <tr><td>&nbsp;</td><td><img src='captcha.php'></td></tr>
        <tr><td>&nbsp;</td><td>(masukkan 6 kode di atas)<br /><input type=text name=kode size=6 maxlength=6><br /></td></tr>
        </td><td colspan=2><input type=submit name=submit value=Kirim></td></tr>
        </form></table>
          </div>
          </div>    
          </div>
            <div class='bottom_prod_box_big'></div>
          </div>";                              
}

// Modul hubungi aksi
elseif ($_GET[module]=='hubungiaksi'){
$nama=trim($_POST['nama']);
$email=trim($_POST['email']);
$subjek=trim($_POST['subjek']);
$pesan=trim($_POST['pesan']);

if (empty($nama)){
  echo "Anda belum mengisikan NAMA<br />
  	      <a href=javascript:history.go(-1)><b>Ulangi Lagi</b>";
}
elseif (empty($email)){
  echo "Anda belum mengisikan EMAIL<br />
  	      <a href=javascript:history.go(-1)><b>Ulangi Lagi</b>";
}
elseif (empty($subjek)){
  echo "Anda belum mengisikan SUBJEK<br />
  	      <a href=javascript:history.go(-1)><b>Ulangi Lagi</b>";
}
elseif (empty($pesan)){
  echo "Anda belum mengisikan PESAN<br />
  	      <a href=javascript:history.go(-1)><b>Ulangi Lagi</b>";
}
else{
	if(!empty($_POST['kode'])){
		if($_POST['kode']==$_SESSION['captcha_session']){

  mysql_query("INSERT INTO hubungi(nama,
                                   email,
                                   subjek,
                                   pesan,
                                   tanggal) 
                        VALUES('$_POST[nama]',
                               '$_POST[email]',
                               '$_POST[subjek]',
                               '$_POST[pesan]',
                               '$tgl_sekarang')");

  echo "<div class='center_title_bar'>Hubungi Kami</div>
    	  <div class='prod_box_big'>
        	<div class='top_prod_box_big'></div>
        <div class='center_prod_box_big'>            
                 <div class='product_img_big'>
                 <img src='foto_banner/gedung.jpg' border='0' />
            </div>
          <div class='details_big_box'>
            <div class='product_title_big'>Terimakasih</div>
              <div>
              <br />Terimakasih telah menghubungi kami.<br /><br /> Kami akan segera membalasnya ke email Anda.
              </div>
          </div>    
          </div>
            <div class='bottom_prod_box_big'></div>
          </div>";
		}else{
			echo "Kode yang Anda masukkan tidak cocok<br />
			      <a href=javascript:history.go(-1)><b>Ulangi Lagi</b></a>";
		}
	}else{
		echo "Anda belum memasukkan kode<br />
  	      <a href=javascript:history.go(-1)><b>Ulangi Lagi</b></a>";
	}
}                              
}


// Modul hasil pencarian produk 
elseif ($_GET['module']=='hasilcari'){
  // menghilangkan spasi di kiri dan kanannya
  $kata = trim($_POST['kata']);
  // mencegah XSS
  $kata = htmlentities(htmlspecialchars($kata), ENT_QUOTES);

  // pisahkan kata per kalimat lalu hitung jumlah kata
  $pisah_kata = explode(" ",$kata);
  $jml_katakan = (integer)count($pisah_kata);
  $jml_kata = $jml_katakan-1;

  $cari = "SELECT * FROM produk WHERE " ;
    for ($i=0; $i<=$jml_kata; $i++){
      $cari .= "deskripsi LIKE '%$pisah_kata[$i]%' OR merk LIKE '%$pisah_kata[$i]%'";
      if ($i < $jml_kata ){
        $cari .= " OR ";
      }
    }
  $cari .= " ORDER BY id_produk DESC LIMIT 7";
  $hasil  = mysql_query($cari);
  $ketemu = mysql_num_rows($hasil);

  echo "<div class='center_title_bar'>Hasil Pencarian</div>";

  if ($ketemu > 0){
  echo "<div class='prod_details_cari'>Ditemukan <b>$ketemu</b> produk dengan kata <font style='background-color:#00FFFF'><b>$kata</b></font> : </div>";
    while($t=mysql_fetch_array($hasil)){
      // Tampilkan hanya sebagian isi produk
      $isi_produk = htmlentities(strip_tags($t['spesification'])); // mengabaikan tag html
      $isi = substr($isi_produk,0,250); // ambil sebanyak 250 karakter
      $isi = substr($isi_produk,0,strrpos($isi," ")); // potong per spasi kalimat
    	  echo "<div class='prod_box_big'>
        	<div class='top_prod_box_big'></div>
        <div class='center_prod_box_big'>            
          <div class='details_big_cari'>
            <div class='product_title_big'><a href=produk-$t[id_produk]-$t[produk_seo].html>$t[merk]</a></div>
              <div>
              <br />$isi ... <a href=produk-$t[id_produk]-$t[produk_seo].html>selengkapnya</a>
              </div>
          </div>    
          </div>
            <div class='bottom_prod_box_big'></div>
          </div>";                      
      }        
    }                                                          
  else{
    echo "<p>Tidak ditemukan produk dengan kata <b>$kata</b></p>";
  }
}


// Modul download katalog
elseif ($_GET['module']=='downloadkatalog'){
  echo "<div class='center_title_bar'>Download Katalog</div>";
  // Tampilkan daftar katalog download
 	$sql = mysql_query("SELECT * FROM download ORDER BY id_download DESC");		 

  echo "<br /><br /><ul>";   
   while($d=mysql_fetch_array($sql)){
      echo "<li><a href='downlot.php?file=$d[nama_file]'>$d[judul]</a></li>";
	 }
  echo "</ul><br />";	
}


// Modul selesai belanja
elseif ($_GET[module]=='selesaibelanja'){
  $sid = session_id();
  $sql = mysql_query("SELECT * FROM orders_temp, produk 
			                WHERE id_session='$sid' AND orders_temp.id_produk=produk.id_produk");
  $ketemu=mysql_num_rows($sql);
  if($ketemu < 1){
   echo "<script> alert('Keranjang belanja masih kosong');window.location='index.php'</script>\n";
   	 exit(0);
	}
	else{
  echo "<div class='center_title_bar'>Assistant PM</div>";
    	  echo "<div class='prod_box_big'>
        	<div class='top_prod_box_big'></div>
        <div class='center_prod_box_big'>            
          <div class='details_big_cari'>
              <div>
      <form name=form2 action=simpan-transaksi-member.html method=POST onSubmit=\"return validasi2(this)\">
      <table>
      <tr><td>Email</td><td> : <input type=text name=email size=30></td></tr>
      <tr><td>Password</td><td> : <input type=password name=password size=30></td></tr>
	  <tr><td>Tujuan</td><td> : <input type=text name=tujuan size=30></td></tr>
      <tr><td><input type='submit' class='button' value='Login'></td><td align=right><a href='lupa-password.html'>Lupa Password?</a></td></tr>
      </table>
      </form>
              </div>
          </div>    
          </div>
            <div class='bottom_prod_box_big'></div>
          </div>";                      

  echo "<div class='center_title_bar'>Assitance PM Baru</div>";
    	  echo "<div class='prod_box_big'>
        	<div class='top_prod_box_big'></div>
        <div class='center_prod_box_big'>            
          <div class='details_big_cari'>
              <div>
      <form name=form action=simpan-transaksi.html method=POST onSubmit=\"return validasi(this)\">
      <table>
      <tr><td>Nama Lengkap</td><td> : <input type=text name=nama size=30></td></tr>
      <tr><td>Password</td><td> : <input type=text name=password></td></tr>
      <tr><td>Alamat Pengiriman</td><td> : <input type=text name=alamat size=80>
      <br />: Alamat pengiriman harus di isi lengkap, termasuk kota/kabupaten dan kode posnya.</td></tr>
      <tr><td>Telpon/HP</td><td> : <input type=text name=telpon></td></tr>
      <tr><td>Email</td><td> : <input type=text name=email size=30></td></tr>
      <tr><td valign=top>Kota Tujuan</td><td> :  
      <select name='kota'>
      <option value=0 selected>- Pilih Kota -</option>";
      $tampil=mysql_query("SELECT * FROM kota ORDER BY nama_kota");
      while($r=mysql_fetch_array($tampil)){
         echo "<option value=$r[id_kota]>$r[nama_kota]</option>";
      }
  echo "</select> <br /><br />*)  Apabila tidak terdapat nama kota tujuan Anda, pilih <b>Lainnya</b>
                  <br />**) Ongkos kirim dihitung berdasarkan kota tujuan</td></tr>
        <tr><td>&nbsp;</td><td><img src='captcha.php'></td></tr>
        <tr><td>&nbsp;</td><td>(Masukkan 6 kode diatas)<br /><input type=text name=kode size=6 maxlength=6><br /></td></tr>
      <tr><td colspan=2><input type='submit' class='button' value='Daftar'></td></tr>
      </table>
      </form>
              </div>
          </div>    
          </div>
            <div class='bottom_prod_box_big'></div>
          </div>";
  }
}      


// Modul lupa password
elseif ($_GET[module]=='lupapassword'){
  echo "<div class='center_title_bar'>Lupa Password</div>";
    	  echo "<div class='prod_box_big'>
        	<div class='top_prod_box_big'></div>
        <div class='center_prod_box_big'>            
          <div class='details_big_cari'>
              <div>
      <form name=form3 action=kirim-password.html method=POST>
      <table>
      <tr><td>Masukkan Email Anda</td><td> : <input type=text name=email size=30></td></tr>
      <tr><td colspan=2><input type='submit' class='button' value='Kirim'></td></td></tr>
      </table>
      </form>
              </div>
          </div>    
          </div>
            <div class='bottom_prod_box_big'></div>
          </div>";                      
}


// Modul kirim password
elseif ($_GET[module]=='kirimpassword'){

// Cek email kustomer di database
$cek_email=mysql_num_rows(mysql_query("SELECT email FROM kustomer WHERE email='$_POST[email]'"));
// Kalau email tidak ditemukan
if ($cek_email == 0){
  echo "Email <b>$_POST[email]</b> tidak terdaftar di database kami.<br />
        <a href=javascript:history.go(-1)><b>Ulangi Lagi</b></a>";
}
else{

$password_baru = substr(md5(uniqid(rand(),1)),3,10);

// ganti password kustomer dengan password yang baru (reset password)
$query=mysql_query("update kustomer set password=md5('$password_baru') where email='$_POST[email]'");

// dapatkan email_pengelola dari database
$sql2 = mysql_query("select email_pengelola from modul where id_modul='43'");
$j2   = mysql_fetch_array($sql2);

$subjek="Password Baru";
$pesan="Password Anda yang baru adalah <b>$password_baru</b>";
// Kirim email dalam format HTML
$dari = "From: $j2[email_pengelola]\r\n";
$dari .= "Content-type: text/html\r\n";

// Kirim password ke email kustomer
mail($_POST[email],$subjek,$pesan,$dari);

  echo "<div class='center_title_bar'>Kirim Password</div>
    	  <div class='prod_box_big'>
        	<div class='top_prod_box_big'></div>
        <div class='center_prod_box_big'>            
                 <div class='product_img_big'>
                 <img src='foto_banner/gedung.jpg' border='0' />
            </div>
          <div class='details_big_box'>
            <div class='product_title_big'>Password Sudah Terkirim</div>
              <div>
              <br />Silahkan cek email Anda.
              </div>
          </div>    
          </div>
            <div class='bottom_prod_box_big'></div>
          </div>";
}                              
}

// Modul simpan transaksi
elseif ($_GET[module]=='simpantransaksi'){
$kar1=strstr($_POST[email], "@");
$kar2=strstr($_POST[email], ".");

// Cek email kustomer di database
$cek_email=mysql_num_rows(mysql_query("SELECT email FROM kustomer WHERE email='$_POST[email]'"));
// Kalau email sudah ada yang pakai
if ($cek_email > 0){
  echo "Email <b>$_POST[email]</b> sudah ada yang pakai.<br />
        <a href=javascript:history.go(-1)><b>Ulangi Lagi</b></a>";
}
elseif (empty($_POST[nama]) || empty($_POST[password]) || empty($_POST[alamat]) || empty($_POST[telpon]) || empty($_POST[email]) || empty($_POST[kota]) || empty($_POST[kode])){
  echo "Data yang Anda isikan belum lengkap<br />
  	    <a href='selesai-order.html'><b>Ulangi Lagi</b>";
}
elseif (!ereg("[a-z|A-Z]","$_POST[nama]")){
  echo "Nama tidak boleh diisi dengan angka atau simbol.<br />
 	      <a href=javascript:history.go(-1)><b>Ulangi Lagi</b></a>";
}
elseif (strlen($kar1)==0 OR strlen($kar2)==0){
  echo "Alamat email Anda tidak valid, mungkin kurang tanda titik (.) atau tanda @.<br />
 	      <a href=javascript:history.go(-1)><b>Ulangi Lagi</b></a>";
}
else{

// fungsi untuk mendapatkan isi keranjang belanja
function isi_keranjang(){
	$isikeranjang = array();
	$sid = session_id();
	$sql = mysql_query("SELECT * FROM orders_temp WHERE id_session='$sid'");
	
	while ($r=mysql_fetch_array($sql)) {
		$isikeranjang[] = $r;
	}
	return $isikeranjang;
}

$tgl_skrg = date("Ymd");
$jam_skrg = date("H:i:s");

if(!empty($_POST['kode'])){
  if($_POST['kode']==$_SESSION['captcha_session']){

function antiinjection($data){
  $filter_sql = mysql_real_escape_string(stripslashes(strip_tags(htmlspecialchars($data,ENT_QUOTES))));
  return $filter_sql;
}

$nama   = antiinjection($_POST['nama']);
$alamat = antiinjection($_POST['alamat']);
$telpon = antiinjection($_POST['telpon']);
$email = antiinjection($_POST['email']);
$password=md5($_POST['password']);

// simpan data kustomer 
mysql_query("INSERT INTO kustomer(nama_lengkap, password, alamat, telpon, email, id_kota) 
             VALUES('$nama','$password','$alamat','$telpon','$email','$_POST[kota]')");

// mendapatkan nomor kustomer
$nik=mysql_insert_id();

// simpan data pemesanan 
mysql_query("INSERT INTO orders(tgl_order,jam_order,nik) VALUES('$tgl_skrg','$jam_skrg','$nik')");
  
// mendapatkan nomor orders
$id_orders=mysql_insert_id();

// panggil fungsi isi_keranjang dan hitung jumlah produk yang dipesan
$isikeranjang = isi_keranjang();
$jml          = count($isikeranjang);

// simpan data detail pemesanan  
for ($i = 0; $i < $jml; $i++){
  mysql_query("INSERT INTO orders_detail(id_orders, id_produk, jumlah) 
               VALUES('$id_orders',{$isikeranjang[$i]['id_produk']}, {$isikeranjang[$i]['jumlah']})");
}
  
// setelah data pemesanan tersimpan, hapus data pemesanan di tabel pemesanan sementara (orders_temp)
for ($i = 0; $i < $jml; $i++) {
  mysql_query("DELETE FROM orders_temp
	  	         WHERE id_orders_temp = {$isikeranjang[$i]['id_orders_temp']}");
}

  echo "<div class='center_title_bar'>Proses Transaksi Selesai</div>";

    	  echo "<div class='prod_box_big'>
        	<div class='top_prod_box_big'></div>
        <div class='center_prod_box_big'>            
          <div class='details_big_cari'>
              <div>
      Data pemesan beserta ordernya adalah sebagai berikut: <br />
      <table>
      <tr><td>Nama           </td><td> : <b>$nama</b> </td></tr>
      <tr><td>Alamat Lengkap </td><td> : $alamat </td></tr>
      <tr><td>Telpon         </td><td> : $telpon </td></tr>
      <tr><td>E-mail         </td><td> : $email </td></tr>
      </table><hr /><br />
      
      Nomor Order: <b>$id_orders</b><br /><br />";

      $daftarproduk=mysql_query("SELECT * FROM orders_detail,produk 
                                 WHERE orders_detail.id_produk=produk.id_produk 
                                 AND id_orders='$id_orders'");

echo "<table cellpadding=10>
      <tr bgcolor=#6da6b1><th>No</th><th>Nama Produk</th><th>Berat(Kg)</th><th>Qty</th><th>Harga Satuan</th><th>Sub Total</th></tr>";
      
$pesan="Terimakasih telah melakukan pemesanan online di toko online kami <br /><br />  
        Nama: $nama <br />
        Password: $_POST[password]<br />
        Alamat: $alamat <br/>
        Telpon: $telpon <br /><hr />
        
        Nomor Order: $id_orders <br />
        Data order Anda adalah sebagai berikut: <br /><br />";
        
$no=1;
while ($d=mysql_fetch_array($daftarproduk)){
   $disc        = ($d[diskon]/100)*$d[harga];
   $hargadisc   = number_format(($d[harga]-$disc),0,",","."); 
   $subtotal    = ($d[harga]-$disc) * $d[jumlah];

   $subtotalberat = $d[berat] * $d[jumlah]; // total berat per item produk 
   $totalberat  = $totalberat + $subtotalberat; // grand total berat all produk yang dibeli

   $total       = $total + $subtotal;
   $subtotal_rp = format_rupiah($subtotal);    
   $total_rp    = format_rupiah($total);    
   $harga       = format_rupiah($d[harga]);

   echo "<tr bgcolor=#dad0d0><td>$no</td><td>$d[merk]</td><td align=center>$d[berat]</td><td align=center>$d[jumlah]</td>
                             <td align=right>$harga</td><td align=right>$subtotal_rp</td></tr>";

   $pesan.="$d[jumlah] $d[nama_produk] -> Rp. $harga -> Subtotal: Rp. $subtotal_rp <br />";
   $no++;
}

$ongkos=mysql_fetch_array(mysql_query("SELECT ongkos_kirim FROM kota WHERE id_kota='$_POST[kota]'"));
$ongkoskirim1=$ongkos[ongkos_kirim];
$ongkoskirim = $ongkoskirim1 * $totalberat;

$grandtotal    = $total + $ongkoskirim; 

$ongkoskirim_rp = format_rupiah($ongkoskirim);
$ongkoskirim1_rp = format_rupiah($ongkoskirim1); 
$grandtotal_rp  = format_rupiah($grandtotal);  

// dapatkan email_pengelola dan nomor rekening dari database
$sql2 = mysql_query("select email_pengelola,nomor_rekening,nomor_hp from modul where id_modul='43'");
$j2   = mysql_fetch_array($sql2);

$pesan.="<br /><br />Total : Rp. $total_rp 
         <br />Ongkos Kirim untuk Tujuan Kota Anda : Rp. $ongkoskirim1_rp/Kg 
         <br />Total Berat : $totalberat Kg
         <br />Total Ongkos Kirim  : Rp. $ongkoskirim_rp		 
         <br />Grand Total : Rp. $grandtotal_rp 
         <br /><br />Silahkan lakukan pembayaran sebanyak Grand Total yang tercantum, rekeningnya: $j2[nomor_rekening]
         <br />Apabila sudah transfer, konfirmasi ke nomor: $j2[nomor_hp]";

$subjek="Pemesanan Online";

// Kirim email dalam format HTML
$dari = "From: $j2[email_pengelola]\r\n";
$dari .= "Content-type: text/html\r\n";

// Kirim email ke kustomer
mail($email,$subjek,$pesan,$dari);

// Kirim email ke pengelola toko online
mail("$j2[email_pengelola]",$subjek,$pesan,$dari);

echo "<tr><td colspan=5 align=right>Total : Rp. </td><td align=right><b>$total_rp</b></td></tr>
      <tr><td colspan=5 align=right>Ongkos Kirim untuk Tujuan Kota Anda: Rp. </td><td align=right><b>$ongkoskirim1_rp</b>/Kg</td></tr>      
	    <tr><td colspan=5 align=right>Total Berat : </td><td align=right><b>$totalberat Kg</b></td></tr>
      <tr><td colspan=5 align=right>Total Ongkos Kirim : Rp. </td><td align=right><b>$ongkoskirim_rp</b></td></tr>      
      <tr><td colspan=5 align=right>Grand Total : Rp. </td><td align=right><b>$grandtotal_rp</b></td></tr>
      </table>";
echo "<hr /><p>Data order dan nomor rekening transfer sudah terkirim ke email Anda. <br />
               Apabila Anda tidak melakukan pembayaran dalam 3 hari, maka transaksi dianggap batal.</p><br />      
              </div>
          </div>    
          </div>
            <div class='bottom_prod_box_big'></div>
          </div>";                      
}
else{
echo "Kode yang Anda masukkan tidak cocok<br />
<a href=javascript:history.go(-1)><b>Ulangi Lagi</b></a>";
}
}else{
echo "Anda belum memasukkan kode<br />
<a href=javascript:history.go(-1)><b>Ulangi Lagi</b></a>";
}
}
}


// Modul simpan transaksi member
elseif ($_GET[module]=='simpantransaksimember'){
	  
	  $nama_pelanggan 	= $_POST[nama_pelanggan];
	  $alamat_pelanggan	= $_POST[alamat_pelanggan];
	  $pic_pelanggan	= $_POST[pic_pelanggan];
	  $no_hp_pelanggan	= $_POST[no_hp_pelanggan];
	  $email_pelanggan	= $_POST[email_pelanggan];
	  $use_for			= $_POST[use_for];
	  
	// fungsi untuk mendapatkan isi keranjang belanja
	function isi_keranjang(){
		$isikeranjang = array();
		$sid = session_id();
		$sql = mysql_query("SELECT * FROM orders_temp WHERE id_session='$sid'");
		
		while ($r=mysql_fetch_array($sql)) {
			$isikeranjang[] = $r;
		}
		return $isikeranjang;
	}
  	
	$tgl_skrg = date("Ymd");
	$jam_skrg = date("H:i:s");

	// simpan data pemesanan 
	mysql_query("INSERT INTO orders(tgl_order,jam_order,nik,nama_pelanggan,alamat_pelanggan,pic_pelanggan,no_hp_pelanggan,email_pelanggan,use_for) 
				VALUES('$tgl_skrg','$jam_skrg','".$_SESSION['nik']."','".$nama_pelanggan."','".$alamat_pelanggan."',
				'".$pic_pelanggan."','".$no_hp_pelanggan."','".$email_pelanggan."','".$use_for."')");
	
	  
	// mendapatkan nomor orders
	$id_orders=mysql_insert_id();
	// panggil fungsi isi_keranjang dan hitung jumlah produk yang dipesan
	$isikeranjang = isi_keranjang();
	$jml          = count($isikeranjang);

	// simpan data detail pemesanan  
	for ($i = 0; $i < $jml; $i++){
	  mysql_query("INSERT INTO orders_detail(id_orders, id_produk, jumlah, nama_project) 
				   VALUES('".$id_orders."','".$isikeranjang[$i][id_produk]."', '".$isikeranjang[$i][jumlah]."', '".$isikeranjang[$i][nama_project]."' )");
	
		
		 // Update untuk mengurangi stok 
      mysql_query("UPDATE produk SET produk.stok=produk.stok-'".$isikeranjang[$i]['jumlah']."' 
	  WHERE produk.id_produk='".$isikeranjang[$i]['id_produk']."'");
	}
  
	// setelah data pemesanan tersimpan, hapus data pemesanan di tabel pemesanan sementara (orders_temp)
	for ($i = 0; $i < $jml; $i++) {
	  mysql_query("DELETE FROM orders_temp
					 WHERE id_orders_temp = {$isikeranjang[$i]['id_orders_temp']}");
	}	
	
	echo "<script>alert('Submit order berhasil'); window.location = 'history-transaksi-member.html'</script>";  
	 
}else if($_GET[module]=='historitransaksimember'){
	$id = mysql_fetch_array(mysql_query("SELECT * FROM kustomer WHERE nik='".$_SESSION['nik']."' AND password='".$_SESSION['password']."'"));	
	// mendapatkan nomor kustomer

  echo "<div class='center_title_bar'>Daftar Transaksi</div>";
    	  echo "<div class='prod_box_big'>
        	<div class='top_prod_box_big'></div>
        <div class='center_prod_box_big'>            
          <div class='details_big_cari'>
              <div>
      Data pemesan beserta ordernya adalah sebagai berikut: <br />
      <table>
	  <tr><td>NIK   </td><td> : <b>$id[nik]</b> </td></tr>
      <tr><td>Nama Lengkap   </td><td> : <b>$id[nama_lengkap]</b> </td></tr>
	  <tr><td>Divisi   </td><td> : <b>$id[divisi]</b> </td></tr>
      <tr><td>Unit Kerja Lantai  </td><td> : $id[unit_kerja_lantai] </td></tr>
      <tr><td>Nama Manajer </td><td> : $id[nama_manajer] </td></tr>	
      <tr><td>Telpon         </td><td> : $id[telpon] </td></tr>
      <tr><td>E-mail         </td><td> : $id[email] </td></tr></table><hr /><br />
      
      Nomor Order: <b>$id_orders</b><br /><br />";
	  $p      = new Paging;
      $batas  = 10;
      $posisi = $p->cariPosisi($batas);
	  $daftarorders=mysql_query("SELECT * FROM orders where nik='".$_SESSION['nik']."' order by id_orders DESC LIMIT $posisi,$batas");

	$nomor=1;
	echo "<table cellpadding=10>
      <tr bgcolor=#6da6b1><th>No Order</th><th>Status Order</th><th>Id Wo</th><th>Tanggal Order</th><th>Tanggal Pengambilan</th><th>PIC Penerima</th><th>Aksi</th></tr>";
      while($od=mysql_fetch_array($daftarorders)){
	  		$tanggal=date("d-m-Y",strtotime($od[tgl_order]));
			if($od[tgl_pengambilan]!=null){
			 	$tanggalAmbil=date("d-m-Y",strtotime($od[tgl_pengambilan]));
			}
	  		echo("	
				<td>$od[id_orders]</td>			
				<td>$od[status_order]</td>
				<td>$od[id_wo]</td>
				<td>$tanggal $od[jam_order]</td>
				<td>$tanggalAmbil $od[jam_pengambilan]</td>	
				<td>$od[pic_penerima]</td>			
				<td colspan=2><a href=detail-$od[id_orders]-$od[status_order].html>detail</td></tr>");
		$nomor++;		
	  }
	echo("</table>");	
	//untuk paging  
	$jmldata     = mysql_num_rows(mysql_query("SELECT * FROM orders where nik='".$_SESSION['nik']."'"));
    $jmlhalaman  = $p->jumlahHalaman($jmldata, $batas);
    $linkHalaman = $p->navHalaman($_GET[halproduk], $jmlhalaman);
    echo "<div class='center_title_bar'>Halaman : $linkHalaman </div>";
  
	
	echo "<hr /><p>Klik detail untuk meliahat detail order anda. <br />  
				  </div>
			  </div>    
			  </div>
				<div class='bottom_prod_box_big'></div>
			  </div>";    
}else if ($_GET[module]=='detailorder'){
	$daftarproduk=mysql_query("SELECT * FROM orders_detail,produk,orders 
                                 WHERE orders_detail.id_produk=produk.id_produk and orders.id_orders=orders_detail.id_orders
                                 AND orders_detail.id_orders='".$_GET[id]."'");

echo "<div class='prod_box_big'>
        	<div class='top_prod_box_big'></div>
        <div class='center_prod_box_big'>            
          <div class='details_big_cari'>
              <div><table cellpadding=10>
      <tr bgcolor=#6da6b1><th>No</th><th>Nama Produk</th><th>Use For</th><th>Berat(Kg)</th><th>Qty</th><th>Harga Satuan</th><th>Sub Total</th></tr>";        
$no=1;
while ($d=mysql_fetch_array($daftarproduk)){
   $disc        = ($d[diskon]/100)*$d[harga];
   $hargadisc   = number_format(($d[harga]-$disc),0,",","."); 
   $subtotal    = ($d[harga]-$disc) * $d[jumlah];
   $qty			= $qty+ $d[jumlah];

   $subtotalberat = $d[berat] * $d[jumlah]; // total berat per item produk 
   $totalberat  = $totalberat + $subtotalberat; // grand total berat all produk yang dibeli

   $total       = $total + $subtotal;
   $subtotal_rp = format_rupiah($subtotal);    
   $total_rp    = format_rupiah($total);    
   $harga       = format_rupiah($d[harga]);
	
   echo "<tr bgcolor=#dad0d0><td>$no</td><td>$d[merk]</td><td>$d[use_for]</td><td align=center>$d[berat]</td><td align=center>$d[jumlah]</td>
                             <td align=right>$harga</td><td align=right>$subtotal_rp</td></tr>";

   $pesan.="$d[jumlah] $d[nama_produk] -> Rp. $harga -> Subtotal: Rp. $subtotal_rp <br />";
   $no++;
}
	
echo "<tr><td colspan=6 align=right>Total : Rp. </td><td align=right><b>$total_rp</b></td></tr>
		<tr><td colspan=6 align=right>Total Quantity : </td><td align=right><b>$qty</b></td></tr> 
	   <tr><td colspan=6 align=right>Total Berat : </td><td align=right><b>$totalberat Kg</b></td></tr>    
      </table>";
echo "<hr /><p>Data order anda terlampir seperti rincian diatas. <br />
               Apabila Anda yakin akan mengorder rincian ini, silahkan klik button Submit Order. <a href='history-transaksi-member.html' >Kembali</a><br />      
              </div>
          </div>    
          </div>
            <div class='bottom_prod_box_big'></div>
          </div>"; 
}else if($_GET[module]=='ceklogin'){
	$nik = $_POST['nik'];
	$password = md5($_POST['password']);	
	$sql = "SELECT * FROM kustomer WHERE nik='" .$nik . "' AND password='" . $password. "'";
	$hasil = mysql_query($sql);
	$r = mysql_fetch_array($hasil);
	
	if(mysql_num_rows($hasil) == 0){
	echo($_SESSION['nik'] );
	echo("nik: ". $nik );					
				 echo "NIK atau Password tidak salah";
				// echo "<a href=javascript:history.go(-1)><b>Ulangi Lagi</b></a>";
	}
	else{
	//simpan session
	  $_SESSION['nik']     = $r['nik'];
	  $_SESSION['nama_lengkap']  = $r['nama_lengkap'];
	  $_SESSION['password']     = $r['password'];
	  $_SESSION['id']    = $r['id'];	
	  echo "<script>alert('Login berhasil'); window.location = 'index.php'</script>";  
	}
  }
?>
