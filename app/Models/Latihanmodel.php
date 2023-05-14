<?php namespace App\Models;

use CodeIgniter\Model;

class Latihanmodel extends Model
{
    protected $table      = 'jenis_soal';
    protected $primaryKey = 'jenis_id ';
    protected $allowedFields = ['jenis_id','jenis_nm','group_id','status_cd','petunjuk','jawaban_img'];

    public function getSoalAll() {
        return $this->db->table('soal_latihan a')
                        ->select('*,a.status_cd as status_soal')
                        ->join('jenis_soal b','b.jenis_id=a.jenis_id','left')
                        ->where('a.status_cd','normal')
                        ->get();
    }

    public function getAllSoal($jenis_id) {
        return $this->db->table('soal_latihan a')
                        ->select('*,a.status_cd as status_soal')
                        ->join('jenis_soal b','b.jenis_id=a.jenis_id','left')
                        ->where('a.jenis_id',$jenis_id)
                        ->where('a.status_cd','normal')
                        ->get();
    }

    public function getSoalbyid($soal_id) {
        return $this->db->table('soal_latihan a')
                        ->select('*')
                        ->join('jenis_soal b','b.jenis_id=a.jenis_id','left')
                        ->where('a.soal_id',$soal_id)
                        ->get();
    }

    public function getJenisSoal() {
        return $this->db->table('jenis_soal')
                        ->select('*')
                        ->where('status_cd','normal')
                        ->get();
    }

    public function getJenisByid($jenis_id) {
        return $this->db->table('jenis_soal')
                        ->select('*')
                        ->where('jenis_id',$jenis_id)
                        ->where('status_cd','normal')
                        ->get();
    }

    public function getMaxNoSoal($jenis_id,$materi) {
        return $this->db->table('soal_latihan')
                        ->select('MAX(no_soal) as max_nosoal')
                        ->where('status_cd','normal')
                        ->where('jenis_id',$jenis_id)
                        ->where('materi',$materi)
                        ->get();
    }
    public function getSoal($no_soal,$jenis_id) {
        return $this->db->table('soal_latihan a')
                        ->select('*')
                        ->join('jenis_soal b','b.jenis_id=a.jenis_id','left')
                        ->where('a.no_soal',$no_soal)
                        ->where('a.jenis_id',$jenis_id)
                        ->where('a.status_cd','normal')
                        ->get();
    }

    public function getTotalSoal($jenis_id) {
        return $this->db->table('soal_latihan')
                        ->select('*')
                        ->where('jenis_id',$jenis_id)
                        ->where('status_cd','normal')
                        ->orderby('no_soal','asc')
                        ->get();
    }

    public function getResponBox($no_soal,$jenis_id,$user_id) {
        return $this->db->table('respon_latihan')
                        ->select('*')
                        ->where('no_soal',$no_soal)
                        ->where('jenis_id',$jenis_id)
                        ->where('created_user_id',$user_id)
                        ->get();
    }

    public function getjawaban($soal_id) {
        return $this->db->table('jawaban_latihan')
                        ->select('*')
                        ->where('soal_id',$soal_id)
                        ->where('status_cd','normal')
                        ->get();
    }

    public function simpansoal($data) {
        $this->db->table('soal_latihan')
                 ->insert($data);
        return $this->db->insertID();
    }

    public function simpanjawaban($data) {
        $this->db->table('jawaban_latihan')
                 ->insert($data);
        return $this->db->insertID();
    }

    public function simpanRespon($data) {
        $this->db->table('respon_latihan')
                 ->insert($data);
        return $this->db->insertID();
    }

    public function updatesoal($soal_id,$data) {
        return $this->db->table('soal_latihan')
                        ->set($data)
                        ->where('soal_id',$soal_id)
                        ->update();
    }

    public function hapussoal($soal_id,$data) {
        return $this->db->table('soal_latihan')
                        ->set($data)
                        ->where('soal_id',$soal_id)
                        ->update();
    }

    public function getJawabanbyjenis($jenis) {
        return $this->db->table('jawaban_latihan a')
                        ->select('*')
                        ->join('soal_latihan b','b.soal_id=a.soal_id','left')
                        ->join('jenis_soal c','c.jenis_id=b.jenis_id','left')
                        ->where('b.jenis_id',$jenis)
                        ->where('a.status_cd','normal')
                        ->where('b.status_cd','normal')
                        ->get();
    }

    public function getJawabanbyid($jawaban_id) {
        return $this->db->table('jawaban_latihan a')
                        ->select('*')
                        ->join('soal_latihan b','b.soal_id=a.soal_id','left')
                        ->join('jenis_soal c','c.jenis_id=b.jenis_id','left')
                        ->where('a.jawaban_id',$jawaban_id)
                        ->get();
    }

    public function updatejawaban($jawaban_id,$data) {
        return $this->db->table('jawaban_latihan')
                        ->set($data)
                        ->where('jawaban_id',$jawaban_id)
                        ->update();
    }

    public function hapusjawaban($soal_id,$data) {
        return $this->db->table('jawaban_latihan')
                        ->set($data)
                        ->where('jawaban_id',$jawaban_id)
                        ->update();
    }

    public function getResponJenis($jenis_id,$user_id) {
        return $this->db->table('respon_latihan a')
                        ->select('*, a.pilihan_nm as pilihan_respon, a.no_soal as no_soal_respon,c.kunci as kunci_soal,b.jawaban_nm as jawaban_nmx')
                        ->join('jawaban_latihan b','b.jawaban_id=a.jawaban_id','left')
                        ->join('soal_latihan c','c.soal_id=b.soal_id','left')
                        ->where('a.jenis_id',$jenis_id)
                        ->where('a.created_user_id',$user_id)
                        ->get(); 
    }

    public function getSKgroup() {
        return $this->db->table('sk_group')
                        ->select('*')
                        ->where('status_cd','normal')
                        ->get();
    }
    
}
