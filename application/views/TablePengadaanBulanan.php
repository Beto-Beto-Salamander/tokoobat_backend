<html>
<style>
	html {
		width: 100%;
	}
	.center {
		display: block;
		margin-left: auto;
		margin-right: auto;
		width: 100%;
	}

	h1 {
		text-align: center;
	}

	.column {
		flex: 50%;
	}
	table{
      border-collapse: collapse;
      font-family: arial;
    }
</style>
<body>
<div class='center'> 
	<!-- <img src="http://kouvee.xyz/upload/kop.PNG"  style="width:100%"> -->
	<hr>
	<h1>LAPORAN PENGADAAN BULANAN</h1>
	<div>Bulan : <?php echo $month ?></div>
	<div>Tahun : <?php echo $year ?></div>
	<table  style='width: 100%'>
		<thead>
			<tr>
				<th >No</th>
				<th>Nama Produk</th>
				<th>Jumlah Pengeluaran</th>
			</tr>
		</thead>
    	<?php $no=1; ?>
    	<?php $total=0; ?>
	  	<?php foreach($pengadaan as $list): ?>
		<tr>
			<td  style='text-align:center'><?php echo $no ?></td>
			<td  style='text-align:center' ><?php echo $list['NAMA PRODUK'] ?></td>
			<?php $hasil = "Rp " . number_format($list['JUMLAH PENGELUARAN'],2,',','.');?>
			<td  style='text-align:center'> <?php echo $hasil ?></td>
    	</tr>
    	<?php $total = $total + intval($list['JUMLAH PENGELUARAN']) ?>
    	<?php $no++; ?>
	  	<?php endforeach; ?>
	</table>
	
	<br>
	<hr>
	<div style='display:flex;'>
		<div class='column'>
		    <?$hasil = "Rp " . number_format($total,2,',','.');?>
			<h3 style='text-align:center'>Total <?php echo $hasil ?></h3>
		</div>
	</div>
	<div style='display:flex;'>
		<div class='column'>
			<p style='text-align:right'>Dicetak tanggal <?php echo $tanggal ?></p>
		</div>
	</div>
	
</div>
</body>
</html>