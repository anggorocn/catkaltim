<?
/* *******************************************************************************************************
MODUL NAME 			: 
FILE NAME 			: string.func.php
AUTHOR				: 
VERSION				: 1.0
MODIFICATION DOC	:
DESCRIPTION			: Functions to handle string operation
***************************************************************************************************** */



/* fungsi untuk mengatur tampilan mata uang
 * $value = string
 * $digit = pengelompokan setiap berapa digit, default : 3
 * $symbol = menampilkan simbol mata uang (Rupiah), default : false
 * $minusToBracket = beri tanda kurung pada nilai negatif, default : true
 */
function setinfo($kondisi="")
{
	if($kondisi == 8)
	{
		$statement= '
		<div class="area-instruksi">
		 
			<div class="judul">Tes Logika A Sub test 1</div>
			
			<div class="keterangan">
			<p>Lihatlah gambar-gambar pada contoh pada tes ini. Kita lihat contoh pada baris pertama. Disitu Anda lihat bagaimana ranting pohon condong merebah, bagaikan tertiup angin. Mulai gambar di kotak pertama sampai pada kotak ketiga.</p>
			
			<span><img src="../WEB/images/contoh/a_subtes1_contoh1.jpg"></span>
			
			<p>Dapatkah Anda memilih 1 (satu) kotak selanjutnya diantara 6 (enam) kotak yang telah tersedia?</p>
			
			<p>Kotak <b>"c"</b> adalah yang tepat untuk gambar ranting yang rendah selanjutnya. Maka pilihlah <b>"c"</b> untuk jawaban Anda.</p>
			 
			<p>Sekarang lihatlah contoh kedua. Lihatlah bagian yang hitam, makin lama makin turun ke bawah. Seolah-olah itu sebuah tirai yang diturunkan dari atas ke bawah. Tentunya Anda tahu gambar yang tepat untuk mengisi kotak selanjutnya?</p>
			
			<span><img src="../WEB/images/contoh/a_subtes1_contoh2.jpg"></span>
			
			<p>Kotak <b>"e"</b> adalah yang tepat untuk gambar selanjutnya, maka pilihlah jawaban <b>"e"</b>.</p>
			
			<p>Sekarang kita beralih pada contoh ketiga. Lihatlah gambar-gambar kelopak daun bunga itu. Makin lama makin merekah bertambah satu helai daun, dimulai dari atas terus ke kiri. Nah, bagaimanakah Anda mengisi kelanjutan dari gambar-gambar tersebut? Coba cari kotak mana yang paling tepat?</p>
			
			<span><img src="../WEB/images/contoh/a_subtes1_contoh3.jpg"></span>
			
			<p>Kotak <b>"e"</b> adalah yang tepat untuk gambar selanjutnya.</p>
			
			<p>Mulailah dari no. 1 – 13. <b>Waktu anda terbatas, bekerjalah dengan cepat, teliti dan tepat.</b></p>
			
			<input type="button" value="OK" onclick="setBacaTipeUjian('.$kondisi.')" id="fvpp-close">
			
			</div>

		</div>
		';
	}
	elseif($kondisi == 9)
	{
		$statement= '
		<div class="area-instruksi">
			<div class="judul">Tes Logika A Sub test 2</div>
			
			<div class="keterangan">
			<p>Lihatlah gambar-gambar contoh pada baris pertama. Disitu ada 3 (tiga) kotak yang mempunyai bentuk gambar yang sama, tetapi 2 (dua) kotak lain yang mempunyai bentuk gambar yang berbeda.
Tugas Anda adalah mencari pada setiap baris soal, 2 kotak yang gambarnya berbeda dengan gambar 3 kotak lainnya. Apabila Anda telah menemukan 2 kotak yang berbeda tersebut, maka pilihlah 2 (dua) huruf jawaban yang mewakili kotak tersebut.
</p>
			<p>Pada gambar di garis pertama, Anda melihat ada 3 (tiga) kotak yang gambarnya <i>segitiga</i> dan 2 (dua) kotak yang gambarnya <i>segiempat</i>. Jadi, 2 kotak yang gambarnya <ul>segiempat</ul> itulah jawabannya.</p>
			
			<span><img src="../WEB/images/contoh/a_subtes2_contoh1.jpg"></span>
			
			<p>Kedua gambar itu berada di kotak dengan huruf <b>"b"</b> dan <b>"d"</b>. Maka Anda harus memilih jawaban <b>"b"</b> dan <b>"d"</b>.</p>
			 							
			<p>Sekarang lihatlah contoh pada baris kedua. Carilah 2 (dua) gambar yang berbeda dengan 3 (tiga) gambar lainnya.</p>
			
			<span><img src="../WEB/images/contoh/a_subtes2_contoh2.jpg"></span>
			
			<p>Jawabannnya adalah <b>"c"</b> dan <b>"e"</b>.</p>
			
			<p>Sekali lagi, tugas Anda adalah memilih 2 kotak atau 2 gambar yang berbeda dengan gambar-gambar yang lainnya pada setiap baris soal. Jadi, Anda harus memilih 2 jawaban.</p>
			  
			
			<p><b>Waktu anda terbatas, bekerjalah dengan cepat, teliti dan tepat.</b></p>
			
			<input type="button" value="OK" onclick="setBacaTipeUjian('.$kondisi.')" id="fvpp-close">
			
			</div>

		</div>
		';
	}
	elseif($kondisi == 10)
	{
		$statement= '
		<div class="area-instruksi">
			<div class="judul">Tes Logika A Sub test 3</div>
			
			<div class="keterangan">
			<p>Perhatikan gambar-gambar contoh pada tes 3 ini. Di dalam kotak yang besar, ada 4 kotak yang kecil. Tiga dari kotak-kotak kecil itu bergambar, tetapi ada 1 kotak kecil yang hilang. Kotak yang hilang ini terletak di antara 6 kotak kecil yang berada di sebelah kanan. Pilihlah salah satu.</p>
			
			<span><img src="../WEB/images/contoh/a_subtes3_contoh1.jpg"></span>
			
			<p>Dapatkah Anda memilih 1 (satu) kotak selanjutnya diantara 6 (enam) kotak yang telah tersedia?</p>
			
			<p>Perhatikan, kotak kedua lah yang tepat untuk mengisi kotak yang kosong itu. Kotak kedua ini terletak pada huruf <b>"b"</b>. Maka pilihlah jawaban <b>"b"</b>.</p>
			 
			<p>Sekarang lihatlah contoh kedua. Manakah jawaban yang benar?</p>
			
			<span><img src="../WEB/images/contoh/a_subtes3_contoh2.jpg"></span>
			
			<p>Kotak yang terletak pada huruf <b>"c"</b> yang tepat untuk mengisi kotak kecil yang hilang tersebut, karena yang hilang itu adalah gambar <ul>ayam berbintik</ul> yang menghadap ke kanan. Maka pilihlah jawaban sesuai dengan yang Anda anggap benar tersebut.</p>
			
			<p>Sekarang perhatikan kotak ketiga. Ada berapakah lingkaran yang Anda lihat?</p>
			
			<span><img src="../WEB/images/contoh/a_subtes3_contoh3.jpg"></span>
			
			<p>Kotak dengan satu lingkaran putih adalah yang tepat untuk mengisinya. Kotak yang terletak pada  huruf <b>"f"</b> itulah jawabannya.</p>
			
			<p><b>Waktu anda terbatas, bekerjalah dengan cepat, teliti dan tepat.</b></p>
			
			<input type="button" value="OK" onclick="setBacaTipeUjian('.$kondisi.')" id="fvpp-close">
			
			</div>

		</div>
		';
	}
	elseif($kondisi == 11)
	{
		$statement= '
		<div class="area-instruksi">
			<div class="judul">Tes Logika A Sub test 4</div>
			
			<div class="keterangan">
			<p>Lihatlah gambar contoh yang pertama. Anda akan melihat 1 (satu) gambar di sebelah kiri dan 5 (lima) gambar di sebelah kanannya.
Pada gambar di sebelah kiri, Anda lihat ada sebuah titik. Titik ini terletak di dalam bujur sangkar dan di dalam lingkaran.
</p>
			
			<span><img src="../WEB/images/contoh/a_subtes4_contoh1.jpg"></span>
			
			<p>Sekarang lihatlah pada 5 gambar yang berada di sebelah kanan. Gambar manakah yang mungkin diletakkan sebuah titik di dalam lingkaran dan juga di dalam bujur sangkar.</p>
			
			<p>Jawabannya adalah gambar yang terletak di atas huruf <b>"c"</b>.</p> 
			
			<p>Ingat, Anda diminta untuk mencari titik dalam kondisi yang sama seperti gambar soal (kiri).</p>
			
			<p>Sekarang lihat contoh kedua. Perhatikan gambar dalam kotak sebelah kiri. Dimanakah kemungkinan letak titik tersebut?</p>
			
			<span><img src="../WEB/images/contoh/a_subtes4_contoh2.jpg"></span>
			
			<p>Titik tersebut berada di dalam segitiga tetapi di luar segi panjang.</p>
			
			<p>Jawabannya adalah gambar yang terletak dibawah huruf <b>"d"</b>. Maka pilihlah jawaban <b>"d"</b>.</p>			
			
			<p>Contoh ketiga. Lihatlah gambar yang terletak di sebelah kiri. Dimanakah letak titik?
Titik itu terletak di dalam segitiga, dan di atas garis lengkung. Sekarang lihat gambar-gambar di sebelah kanan. Carilah kemungkinan gambar yang letak titiknya serupa dengan yang dipersoalkan.
</p>

			<span><img src="../WEB/images/contoh/a_subtes4_contoh3.jpg"></span>
			
			<p>Gambar yang benar adalah yang berada pada huruf <b>"b"</b>.</p>
			
			<p>Gunakanlah imajinasi Anda untuk mencari kemungkinan letak titik tersebut.</p>

			<p><b>Waktu anda terbatas, bekerjalah dengan cepat, teliti dan tepat.</b></p>
			
			<input type="button" value="OK" onclick="setBacaTipeUjian('.$kondisi.')" id="fvpp-close">
			
			</div>

		</div>
		';
	}
	elseif($kondisi == 12)
	{
		$statement= '
		<div class="area-instruksi">
		 
			<div class="judul">Tes Logika B Sub test 1</div>
			
			<div class="keterangan"> 
			<p>Lihatlah gambar-gambar pada contoh pada tes ini. Kita lihat contoh pada baris pertama. Disitu Anda lihat lingkaran-lingkaran dari yang terbesar berurutan sampai yang paling kecil, mulai dari gambar di kotak pertama sampai pada kotak ketiga.</p>
		 
			<span><img src="../WEB/images/contoh/b_subtes1_contoh1.jpg"></span>
		 
			
			<p>Dapatkah Anda memilih 1 (satu) kotak selanjutnya diantara 6 (enam) kotak yang telah tersedia?</p>
			
			<p>Kotak <b>"c"</b> adalah yang tepat untuk gambar lingkaran yang paling terkecil. Maka pilihlah <b>"c"</b> untuk jawaban Anda.</p>
			
		 		
			<p>Sekarang lihatlah contoh kedua. Anda dapat lihat beberapa batang kayu (stick) yang berdiri sejajar sama tinggi, dari satu batang lalu bertambah menjadi dua batang, tiga dan seterusnya bertambah satu persatu. Tentunya Anda tahu, di kotak mana yang berisi kayu lebih banyak.</p>
			
			<span><img src="../WEB/images/contoh/b_subtes1_contoh2.jpg"></span>
			
			<p>Kotak <b>"e"</b> adalah yang tepat untuk gambar selanjutnya, maka pilihlah jawaban <b>"e"</b>.</p>
			
			<p>Sekarang kita beralih pada contoh ketiga. Lihatlah gambar seperti dua stick golf dan sebuat titik di atara keduanya. Dari tegak, miring dan makin lama merebah. Bagaimana Anda mengetahui kelanjutan dari gambar-gambar tersebut?</p>
			
             <span><img src="../WEB/images/contoh/b_subtes1_contoh3.jpg"></span>
						
			<p>Kotak <b>"e"</b> adalah yang tepat untuk gambar selanjutnya.</p>
			
			<p>Mulailah dari no. 1 – 13. <b>Waktu anda terbatas, bekerjalah dengan cepat, teliti dan tepat.</b></p>
			
			<input type="button" value="OK" onclick="setBacaTipeUjian('.$kondisi.')" id="fvpp-close">
			
			</div>

		</div>
		';
	}
	elseif($kondisi == 13)
	{
		$statement= '
		<div class="area-instruksi">
		 
			<div class="judul">Tes Logika B Sub test 2</div>
			
			<div class="keterangan"> 
			<p>Lihatlah gambar-gambar contoh pada baris pertama. Disitu ada 3 (tiga) kotak yang mempunyai bentuk gambar yang sama, tetapi 2 (dua) kotak lain yang mempunyai bentuk gambar yang berbeda.
Tugas Anda adalah mencari pada setiap baris soal, 2 kotak yang gambarnya berbeda dengan gambar 3 kotak lainnya. Apabila Anda telah menemukan 2 kotak yang berbeda tersebut, maka pilihlah 2 (dua) huruf jawaban yang mewakili kotak tersebut.
</p>
			
			<span><img src="../WEB/images/contoh/b_subtes2_contoh1.jpg"></span>
			
			<p>Pada gambar di garis pertama, Anda melihat ada 3 (tiga) kotak yang gambarnya seperti beduk dengan lingkaran hitam di atasnya, dan 2 kotak lain yang bergambar <ul>lingkaran</ul>. Jadi, 2 kotak yang bergambar <ul>lingkaran</ul> itulah jawabannya.</p>
			
			<p>Kedua gambar itu berada di kotak dengan huruf <b>"b"</b> dan <b>"d"</b>. Maka Anda harus memilih jawaban  <b>"b"</b> dan <b>"d"</b>.</p>
			
		 		
			<p>Sekarang lihatlah contoh pada baris kedua. Carilah 2 (dua) gambar yang berbeda dengan 3 (tiga) gambar lainnya.</p>
			
			<span><img src="../WEB/images/contoh/b_subtes2_contoh2.jpg"></span>
			
			<p>Jawabannnya adalah <b>"c"</b> dan <b>"e"</b>.</p>
					 
			<p>Sekali lagi, tugas Anda adalah memilih 2 kotak atau 2 gambar yang berbeda dengan gambar-gambar yang lainnya pada setiap baris soal. Jadi, anda harus memilih 2 jawaban.</p>
			
			<p><b>Waktu anda terbatas, bekerjalah dengan cepat, teliti dan tepat.</b></p>
			
			<input type="button" value="OK" onclick="setBacaTipeUjian('.$kondisi.')" id="fvpp-close">
			
			</div>

		</div>
		';
	}
	elseif($kondisi == 14)
	{
		$statement= '
		<div class="area-instruksi">
		 
			<div class="judul">Tes Logika B Sub test 3</div>
			
			<div class="keterangan"> 
			<p>Perhatikan gambar-gambar yang berisi 3 garis tegak lurus di dalam setiap kotak, tapi ada 1 kotak yang kosong atau hilang. Kotak yang hilang ini terletak di antara 6 kotak kecil yang berada di sebelah kanan. Pilihlah salah satu.
</p>
			
			<span><img src="../WEB/images/contoh/b_subtes3_contoh1.jpg"></span>
			
			<p>Perhatikan, kotak kedua lah yang tepat untuk mengisi kotak yang kosong itu. Kotak kedua ini terletak pada huruf <b>"b"</b>. Maka pilihlah jawaban <b>"b"</b>.</p>
			
		 
			<p>Sekarang lihatlah contoh kedua. Manakah jawaban yang benar?</p>
			
			<span><img src="../WEB/images/contoh/b_subtes3_contoh2.jpg"></span>
			
			<p>Kotak yang terletak pada huruf <b>"c"</b> yang tepat untuk mengisi kotak kecil yang hilang tersebut, karena yang hilang itu adalah gambar <ul>telunjuk tangan berbintik</ul> yang menghadap ke kanan. Maka pilihlah jawaban sesuai dengan yang anda anggap benar tersebut.</p>
					 
			<p>Sekarang perhatikan kotak ketiga. Di dalam kotak yang besar, berisi 4 (empat) kotak yang terdapat garis-garis tebal. Kotak yang 1 (satu) garis tebal berwarna berpasangan dengan kotak yang bergaris 2 (dua) tebal berwarna pula. Sedangkan dibawahnya ada 1 (satu) kotak yang bergaris satu putih, Anda harus mencari kotak pasangan yang bergaris dua putih.</p>
			
			<span><img src="../WEB/images/contoh/b_subtes3_contoh3.jpg"></span>
			
			<p>Kotak yang terletak pada  huruf <b>"f"</b> itulah jawabannya.</p>
			
			<b><p>Waktu anda terbatas, bekerjalah dengan cepat, teliti dan tepat.</p></b>
			
			<input type="button" value="OK" onclick="setBacaTipeUjian('.$kondisi.')" id="fvpp-close">
			
			</div>

		</div>
		';
	}
	elseif($kondisi == 15)
	{
		$statement= '
		<div class="area-instruksi">
		 
			<div class="judul">Tes Logika B Sub test 4</div>
			
			<div class="keterangan"> 
			<p>Lihatlah gambar contoh yang pertama. Anda akan melihat 1 (satu) gambar di sebelah kiri dan 5 (lima) gambar di sebelah kanannya.
Pada gambar di sebelah kiri, Anda lihat di dalam kotak ada gambar lingkaran, kotak persegi panjang dan sebuah titik. Perhatikan titik tersebut. titik tersebut berada di dalam kotak persegi panjang namun di luar lingkaran.
</p>
			<p>Sekarang lihatlah pada 5 gambar yang berada di sebelah kanan. Gambar manakah yang mungkin diletakkan sebuah titik di dalam kotak persegi panjang namun di luar lingkaran?</p>
			
			<span><img src="../WEB/images/contoh/b_subtes4_contoh1.jpg"></span>
			
			<p>Jawabannya adalah gambar yang terletak di atas huruf <b>"c"</b>.</p>
			
		 
			<p>Ingat, Anda diminta untuk mencari titik dalam kondisi yang sama seperti gambar soal (kiri).</p>
			
			<p>Sekarang lihat contoh kedua. Perhatikan gambar dalam kotak sebelah kiri. Dimanakah kemungkinan letak titik tersebut?</p>
			
			<span><img src="../WEB/images/contoh/b_subtes4_contoh2.jpg"></span>
			
			<p>Titik tersebut berada di dalam 2 (dua) buah segitiga. Sekarang, lihatlah contoh ketiga. Lihat gambar pada kotak sebelah kiri. Dimanakah letak titik tersebut?</p>
			
			<p>Jawabannya adalah gambar yang terletak dibawah huruf <b>"d"</b>. Maka pilihlah jawaban <b>"d"</b>.</p>
					 
			<p>Contoh ketiga. Lihatlah gambar yang terletak di sebelah kiri. Dimanakah letak titik?
Titik itu terletak di dalam segitiga, dan di atas garis lengkung. Sekarang lihat gambar-gambar di sebelah kanan. Carilah kemungkinan gambar yang letak titiknya serupa dengan yang dipersoalkan.
</p>
			
			<span><img src="../WEB/images/contoh/b_subtes4_contoh3.jpg"></span>
			
			<p>Gambar yang benar adalah yang berada pada huruf <b>"b"</b>.</p>
			<p>Gunakanlah imajinasi Anda untuk mencari kemungkinan letak titik tersebut.</p>
			
			<b><p>Waktu anda terbatas, bekerjalah dengan cepat, teliti dan tepat.</p></b>
			
			<input type="button" value="OK" onclick="setBacaTipeUjian('.$kondisi.')" id="fvpp-close">
			
			</div>

		</div>
		';
	}
	elseif($kondisi == 3)
	{
		$statement= '
		<div class="area-instruksi">
		 
			<div class="judul">Pengetahuan Umum</div>
			
			<div class="keterangan"> 
			<b><p>Kerjakan Ujian dengan baik, waktu anda terbatas, bekerjalah dengan cepat, teliti dan tepat.</p></b>
			
			<input type="button" value="OK" onclick="setBacaTipeUjian('.$kondisi.')" id="fvpp-close">
			
			</div>

		</div>
		';
	}
	elseif($kondisi == 4)
	{
		$statement= '
		<div class="area-instruksi">
		 
			<div class="judul">Tes Bahasa Inggris</div>
			
			<div class="keterangan"> 
			<b><p>Kerjakan Ujian dengan baik, waktu anda terbatas, bekerjalah dengan cepat, teliti dan tepat.</p></b>
			
			<input type="button" value="OK" onclick="setBacaTipeUjian('.$kondisi.')" id="fvpp-close">
			
			</div>

		</div>
		';
	}
	elseif($kondisi == 5)
	{
		$statement= '
		<div class="area-instruksi">
		 
			<div class="judul">Tes Mekanikal</div>
			
			<div class="keterangan"> 
			<b><p>Kerjakan Ujian dengan baik, waktu anda terbatas, bekerjalah dengan cepat, teliti dan tepat.</p></b>
			
			<input type="button" value="OK" onclick="setBacaTipeUjian('.$kondisi.')" id="fvpp-close">
			
			</div>

		</div>
		';
	}
	elseif($kondisi == 6)
	{
		$statement= '
		<div class="area-instruksi">
		 
			<div class="judul">TKD 1</div>
			
			<div class="keterangan"> 
			<b><p>Kerjakan Ujian dengan baik, waktu anda terbatas, bekerjalah dengan cepat, teliti dan tepat.</p></b>
			
			<input type="button" value="OK" onclick="setBacaTipeUjian('.$kondisi.')" id="fvpp-close">
			
			</div>

		</div>
		';
	}
	elseif($kondisi == 7)
	{
		$statement= '
		<div class="area-instruksi">
		 
			<div class="judul">Papikostik</div>
			
			<div class="keterangan"> 
			<b><p>Kerjakan Ujian dengan baik, waktu anda terbatas, bekerjalah dengan cepat, teliti dan tepat.</p></b>
			
			<input type="button" value="OK" onclick="setBacaTipeUjian('.$kondisi.')" id="fvpp-close">
			
			</div>

		</div>
		';
	}
	else
	{
		$statement= "Belum ada";
	}
	return $statement;
}
?>