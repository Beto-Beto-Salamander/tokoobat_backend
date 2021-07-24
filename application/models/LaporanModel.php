<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class LaporanModel extends CI_Model{
    
    public function getDataTransaksiProdukTahun($year){
        date_default_timezone_set('Asia/Jakarta');
        $conn = mysqli_connect('localhost', $this->db->username, $this->db->password,$this->db->database);
    
        // $sql = "SELECT m.Nama AS 'BULAN', 
        //         COALESCE(p.nama,'-') AS 'NAMA PRODUK',
        //         COALESCE(max(p.total),0) 'JUMLAH PENJUALAN'
        //         FROM (
        //             SELECT * FROM BULAN
        //         )AS m 
        //         LEFT JOIN (
        //             SELECT t.TGL_TRANSAKSI, p.nama_produk AS nama, SUM(d.JUMLAH_PRODUK) AS total
        //             FROM TRANSAKSI_PRODUK t
        //             JOIN DETIL_TRANSAKSI_PRODUK d ON t.ID_TRANSAKSI_PRODUK = d.ID_TRANSAKSI_PRODUK
        //             join PRODUK p on p.ID_PRODUK = d.ID_PRODUK
        //             GROUP BY p.ID_PRODUK
        //         )AS p ON MONTHNAME(p.TGL_TRANSAKSI) = m.Nama
        //         GROUP BY m.Nama
        //         ORDER BY m.Nomor ASC";

        $sql = "SELECT m.nama AS 'BULAN', 
                COALESCE(p.nama,'-') AS 'NAMA PRODUK',
                COALESCE(max(p.total),0) 'JUMLAH PENJUALAN'
                FROM (
                    SELECT * FROM BULAN
                )AS m 
                LEFT JOIN (
                    SELECT t.tgl_transaksi, p.nama_produk AS nama, SUM(d.jml_transaksi_produk) AS total
                    FROM transaksi t
                    JOIN detail_transaksi d ON t.id_transaksi = d.id_transaksi
                    join produk p on p.id_produk = d.id_produk
                    GROUP BY p.id_produk
                )AS p ON MONTHNAME(p.tgl_transaksi) = m.nama
                GROUP BY m.nama
                ORDER BY m.id ASC";
        
        $result = mysqli_query($conn,$sql);
        
        while($row = mysqli_fetch_array($result, MYSQLI_ASSOC))
        {
            $rows[] = $row;
        }
        
        return $rows;
    }
    
    public function getDataPendapatanTahun($year){
        $conn = mysqli_connect('localhost', $this->db->username, $this->db->password,$this->db->database);
    
        // $sql = "SELECT m.Nama AS 'BULAN', 
        //         COALESCE(sum(layanan),0) AS 'JASA LAYANAN',
        //         COALESCE(sum(produk),0) AS 'PRODUK',
        //         COALESCE(sum(layanan),0) + COALESCE(sum(produk),0) AS 'TOTAL'
        //         FROM (
        //             SELECT * FROM BULAN
        //         )AS m 
        //         LEFT JOIN (
        //             SELECT
        //             		ID_TRANSAKSI_PRODUK AS ID,
        //                     TGL_TRANSAKSI AS TGL,
        //                     TOTAL_TRANSAKSI_PRODUK AS produk,
        //             		0 AS layanan
        //                 FROM
        //                     TRANSAKSI_PRODUK
        //                 WHERE STATUS_TRANSAKSI_PRODUK = '1' AND
        //                 YEAR(TGL_TRANSAKSI) = '$year'
        //                 UNION ALL
        //                 SELECT
        //             		ID_TRANSAKSI_LAYANAN AS ID,
        //                     TGL_TRANSAKSI_LAYANAN AS TGL,
        //                     0  AS produk,
        //                     TOTAL_TRANSAKSI_LAYANAN AS layanan
        //                 FROM
        //                     TRANSAKSI_LAYANAN
        //                 WHERE STATUS_LAYANAN = '1' AND
        //                 YEAR(TGL_TRANSAKSI_LAYANAN) = '$year'   
        //         ) p ON MONTHNAME(p.TGL) = m.Nama 
        //         GROUP BY m.Nama
        //         ORDER by m.Nomor";

        $sql = "SELECT m.nama AS 'BULAN', 
                COALESCE(sum(produk),0) AS 'TOTAL'
                FROM (
                    SELECT * FROM bulan
                )AS m 
                LEFT JOIN (
                        SELECT
                            id_transaksi AS ID,
                            tgl_transaksi AS TGL,
                            total_transaksi AS produk
                            
                        FROM
                            transaksi
                        WHERE status_transaksi = '1' AND
                        YEAR(tgl_transaksi) = 2020 /*ganti ke '$year' */
                        
                ) p ON MONTHNAME(p.TGL) = m.nama 
                GROUP BY m.nama
                ORDER by m.id";
        
        $result = mysqli_query($conn,$sql);
        
        while($row = mysqli_fetch_array($result, MYSQLI_ASSOC))
        {
            $rows[] = $row;
        }
        
        return $rows;
    }
    
    public function getDataPengadaanTahun($year){
        $conn = mysqli_connect('localhost', $this->db->username, $this->db->password,$this->db->database);
    
        // $sql = "SELECT m.Nama AS 'BULAN', 
        //         COALESCE(SUM(p.total),0) AS 'JUMLAH PENGELUARAN'
        //         FROM (
        //             SELECT * FROM BULAN
        //         )AS m 
        //         LEFT JOIN (
        //             SELECT tgl_pengadaan,
        //             TOTAL AS total
        //             FROM PEMESANAN 
        //             WHERE YEAR(tgl_pengadaan) = '$year'
        //             GROUP BY id_pengadaan
        //         )AS p ON MONTHNAME(p.tgl_pengadaan) = m.Nama
        //         GROUP BY m.Nama
        //         ORDER BY m.Nomor ASC";

        $sql = "SELECT m.nama AS 'BULAN', 
                COALESCE(SUM(p.total),0) AS 'JUMLAH PENGELUARAN'
                FROM (
                    SELECT * FROM bulan
                )AS m 
                LEFT JOIN (
                    SELECT tgl_pengadaan,
                    total_pengadaan AS total
                    FROM pengadaan 
                    WHERE YEAR(tgl_pengadaan) = 2020
                    GROUP BY id_pengadaan
                )AS p ON MONTHNAME(p.tgl_pengadaan) = m.nama
                GROUP BY m.nama
                ORDER BY m.id ASC";
        
        $result = mysqli_query($conn,$sql);
        
        while($row = mysqli_fetch_array($result, MYSQLI_ASSOC))
        {
            $rows[] = $row;
        }
        
        return $rows;
    }
    
    public function getDataPengadaanBulan($year,$month){
        $conn = mysqli_connect('localhost', $this->db->username, $this->db->password,$this->db->database);
    
        // $sql = "SELECT
        //         pr.nama_produk AS 'Nama Produk', 
        //         SUM(dp.subtotal_pengadaan) AS 'Jumlah Pengeluaran'
        //         FROM detail_pengadaan dp
        //         JOIN pengadaan p
        //         ON dp.id_pengadaan = p.id_pengadaan
        //         JOIN produk pr
        //         ON dp.id_produk = pr.id_produk
        //         WHERE YEAR(p.tgl_pengadaan) = '$year'
        //         AND MONTHNAME(p.tgl_pengadaan) = '$month'
        //         GROUP BY pr.id_produk";

        $sql = "SELECT
                pr.nama_produk AS 'Nama Produk', 
                SUM(dp.subtotal_pengadaan) AS 'Jumlah Pengeluaran'
                FROM detail_pengadaan dp
                JOIN pengadaan p
                ON dp.id_pengadaan = p.id_pengadaan
                JOIN produk pr
                ON dp.id_produk = pr.id_produk
                WHERE YEAR(p.tgl_pengadaan) = 2020
                AND MONTHNAME(p.tgl_pengadaan) = 1
                GROUP BY pr.id_produk";
        
        $result = mysqli_query($conn,$sql);
        
        while($row = mysqli_fetch_array($result, MYSQLI_ASSOC))
        {
            $rows[] = $row;
        }
        
        return $rows;
    }
    
    public function getDataTransaksiProdukBulan($year,$month){
        $conn = mysqli_connect('localhost', $this->db->username, $this->db->password,$this->db->database);
    
        // $sql = "SELECT
        //         p.nama_produk AS 'Nama Produk',
        //         SUM(dt.subtotal_transaksi) AS 'Harga'
        //         FROM transaksi t 
        //         JOIN detail_transaksi dt 
        //         ON t.id_transaksi_produk = dt.id_transaksi_produk
        //         JOIN produk p
        //         ON dt.id_produk = p.id_produk
        //         WHERE YEAR(t.tgl_transaksi) = '$year'
        //         AND MONTHNAME(t.tgl_transaksi) = '$month'
        //         AND t.status_transaksi = '1'
        //         GROUP BY p.id_produk
        //         ";

        $sql = "SELECT
                p.nama_produk AS 'Nama Produk',
                SUM(dt.subtotal_produk) AS 'Harga'
                FROM transaksi t 
                JOIN detail_transaksi dt 
                ON t.id_transaksi = dt.id_transaksi
                JOIN produk p
                ON dt.id_produk = p.id_produk
                WHERE YEAR(t.tgl_transaksi) = 2020
                AND MONTHNAME(t.tgl_transaksi) = 1
                AND t.status_transaksi = '1'
                GROUP BY p.id_produk";
        
        $result = mysqli_query($conn,$sql);
        
        while($row = mysqli_fetch_array($result, MYSQLI_ASSOC))
        {
            $rows[] = $row;
        }
        
        return $rows;
    }
    
    
    
    
}
