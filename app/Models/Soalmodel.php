<?php namespace App\Models;

use CodeIgniter\Model;

class Soalmodel extends Model
{
    protected $table      = 'soal';
    protected $primaryKey = 'soal_id ';
    protected $allowedFields = ['soal_id','no_soal','soal_nm','soal_img','kunci','status_cd','group_id','materi'];

    public function getAllJMateri() {
        return $this->db->table('materi')
                        ->select('*')
                        ->where('status_cd','normal')
                        ->get();
    }
    
    public function getjawAllJMateri() {
        return $this->db->table('materi')
                        ->select('*')
                        ->where('status_cd','normal')
                        ->whereNotIn('materi_nm',["Sikap Kerja","Latihan"])
                        ->get();
    }

    public function getMateriSK() {
        return $this->db->table('materi')
                        ->select('*')
                        ->where('status_cd','normal')
                        ->whereIn('materi_nm',["Sikap Kerja"])
                        ->get();
    }

    public function getKolomSoal() {
        return $this->db->table('kolom_soal')
                        ->select('*')
                        ->where('status_cd','normal')
                        ->get();
    }

    public function getGroup() {
        return $this->db->table('group_soal')
                        ->select('*')
                        ->get();
    }

    public function getGroupByid($group_id) {
        return $this->db->table('group_soal')
                        ->select('*')
                        ->where('group_soal_id',$group_id)
                        ->get();
    }

    public function getAllSoal() {
        return $this->db->table('soal a')
                        ->select('*')
                        ->join('group_soal b','b.group_soal_id=a.group_id')
                        ->join('materi c','c.materi_id=a.materi')
                        ->where('a.status_cd','normal')
                        ->orderby('a.group_id','ASC')
                        ->get();
    }

    public function getMaxNoSoal($group_id,$materi) {
        return $this->db->table('soal')
                        ->select('MAX(no_soal) as max_nosoal')
                        ->where('status_cd','normal')
                        ->where('group_id',$group_id)
                        ->where('materi',$materi)
                        ->get();
    }


public function getAllSoalSK() {
        return $this->db->table('soal a')
                        ->select('*')
                        ->join('group_soal b','b.group_soal_id=a.group_id')
                        ->whereIn('a.status_cd',['normal','disable'])
                        ->where('a.group_id',4)
                        ->where('a.materi',5)
                        ->orderby('a.kolom_id','ASC')
                        ->get();
    }
    public function getSoalBygrmt($group_id,$materi) {
        return $this->db->table('soal a')
                        ->select('*')
                        ->join('group_soal b','b.group_soal_id=a.group_id')
                        ->where('a.status_cd','normal')
                        ->where('a.group_id',$group_id)
                        ->where('a.materi',$materi)
                        ->get();
    }

    public function getTotalSoal($group_id,$materi) {
        return $this->db->table('soal')
                        ->select('*')
                        ->where('group_id',$group_id)
                        ->where('materi',$materi)
                        ->where('status_cd','normal')
                        ->orderby('no_soal','asc')
                        ->get();
    }

    public function getSoal($no_soal,$group_id,$materi,$kolom_id) {
        return $this->db->table('soal a')
                        ->select('*')
                        ->join('group_soal b','b.group_soal_id=a.group_id','left')
                        ->where('a.kolom_id',$kolom_id)
                        ->where('a.no_soal',$no_soal)
                        ->where('a.group_id',$group_id)
                        ->where('a.materi',$materi)
                        ->where('a.status_cd','normal')
                        ->get();
    }

    public function getSoalSK($no_soal,$group_id,$materi,$kolom_id,$sk_group_id) {
        return $this->db->table('soal a')
                        ->select('*')
                        ->join('group_soal b','b.group_soal_id=a.group_id','left')
                        ->where('a.no_soal',$no_soal)
                        ->where('a.group_id',$group_id)
                        ->where('a.materi',$materi)
                        ->where('a.kolom_id',$kolom_id)
                        ->where('a.sk_group_id',$sk_group_id)
                        ->where('a.status_cd','normal')
                        ->get();
    }

    public function getSoalByid($soal_id) {
        return $this->db->table('soal')
                        ->select('*')
                        ->where('soal_id',$soal_id)
                        ->get();
    }

    public function getSoalBymateri($materi) {
        return $this->db->table('soal a')
                        ->select('*')
                        ->join('group_soal b','b.group_soal_id=a.group_id')
                        ->where('a.status_cd','normal')
                        ->where('a.materi',$materi)
                        ->get();
    }

    public function resSoalKec($group_id,$materi) {
        return $this->db->table('soal a')
                        ->select('*')
                        ->join('group_soal b','b.group_soal_id=a.group_id')
                        ->where('a.status_cd','normal')
                        ->where('a.materi',$materi)
                        ->where('a.group_id',$group_id)
                        ->get();
    }

    public function getjawaban($soal_id) {
        return $this->db->table('jawaban')
                        ->select('*')
                        ->where('soal_id',$soal_id)
                        ->where('status_cd','normal')
                        ->get();
    }

    public function getJawabanBysoalId($soal_id) {
        return $this->db->table('jawaban')
                        ->select('*')
                        ->where('soal_id',$soal_id)
                        ->where('status_cd','normal')
                        ->get();
    }

    public function getResponexcel($soal_id,$jawaban_id,$user_id,$materi) {
        return $this->db->table('respon')
                        ->select('*')
                        ->where('soal_id',$soal_id)
                        ->where('group_id',2)
                        ->where('materi',$materi)
                        ->where('created_user_id',$user_id)
                        ->get();
    }

    public function getResponexcelx($soal_id,$jawaban_id,$user_id,$materi) {
        return $this->db->table('respon')
                        ->select('*')
                        ->where('soal_id',$soal_id)
                        ->where('group_id',3)
                        ->where('materi',$materi)
                        ->where('created_user_id',$user_id)
                        ->get();
    }

    public function getResponByPrev($soal_id,$group_id,$materi,$user_id,$session) {
        return $this->db->table('respon')
                        ->select('*')
                        ->where('soal_id',$soal_id)
                        ->where('group_id',$group_id)
                        ->where('materi',$materi)
                        ->where('created_user_id',$user_id)
                        ->where('session',$session)
                        ->get();
    }

    public function getResponByPrevSK($soal_id,$group_id,$materi,$user_id,$session,$kolom_id) {
        return $this->db->table('respon')
                        ->select('*')
                        ->where('kolom_id',$kolom_id)
                        ->where('group_id',$group_id)
                        ->where('materi',$materi)
                        ->where('created_user_id',$user_id)
                        ->where('session',$session)
                        ->get();
    }

    public function getResponLatihan($user_id) {
        return $this->db->table('respon a')
                        ->select('*,a.pilihan_nm as pilihan_respon,a.kolom_id as kolom_respon,a.soal_id as soal_id_respon,b.soal_id as soal_id_jwb,a.created_dttm as used_dttm')
                        ->join('jawaban b','b.jawaban_id=a.jawaban_id','left')
                        ->join('soal c','c.soal_id=b.soal_id','left')
                        ->where('b.status_cd','normal')
                        ->where('a.created_user_id',$user_id)
                        // ->where('a.session',$session)
                        ->where('a.materi',5)
                        ->where('a.group_id',4)
                        ->groupby('a.used')
                        ->get();
    }

    public function getMaxUsed($user_id) {
        return $this->db->table('respon')
                        ->select('MAX(used) as maxused')
                        ->where('created_user_id',$user_id)
                        ->where('materi',5)
                        ->where('group_id',4)
                        ->get();
    }

    public function getResponSK($user_id,$session,$kolom_id,$materi,$used,$sk_group_id) {
        if ($session == "") {
            return $this->db->table('respon a')
                        ->select('*,a.pilihan_nm as pilihan_respon')
                        ->join('jawaban b','b.jawaban_id=a.jawaban_id','left')
                        ->join('soal c','c.soal_id=b.soal_id','left')
                        ->where('b.status_cd','normal')
                        ->where('a.created_user_id',$user_id)
                        ->where('a.used',$used)
                        ->where('a.materi',$materi)
                        ->where('a.kolom_id',$kolom_id)
                        ->where('c.sk_group_id',$sk_group_id)
                        ->where('a.group_id',4)
                        ->get();
        } else {
            return $this->db->table('respon a')
                        ->select('*,a.pilihan_nm as pilihan_respon')
                        ->join('jawaban b','b.jawaban_id=a.jawaban_id','left')
                        ->join('soal c','c.soal_id=b.soal_id','left')
                        ->where('b.status_cd','normal')
                        ->where('a.created_user_id',$user_id)
                        ->where('a.session',$session)
                        ->where('a.used',$used)
                        ->where('a.materi',$materi)
                        ->where('a.kolom_id',$kolom_id)
                        ->where('a.group_id',4)
                        ->get();
        }
    }

    public function getResponSKLatihan($user_id,$used,$kolom_id,$materi) {
        return $this->db->table('respon a')
                        ->select('*,a.pilihan_nm as pilihan_respon')
                        ->join('jawaban b','b.jawaban_id=a.jawaban_id','left')
                        ->join('soal c','c.soal_id=b.soal_id','left')
                        ->where('b.status_cd','normal')
                        ->where('a.created_user_id',$user_id)
                        ->where('a.used',$used)
                        ->where('a.materi',$materi)
                        ->where('a.kolom_id',$kolom_id)
                        ->where('a.group_id',4)
                        ->get();
    }

    public function getResponSikapKerja($user_id,$session,$kolom_id,$materi) {
        if ($session == "") {
            return $this->db->table('respon a')
                        ->select('*,a.pilihan_nm as pilihan_respon,a.kolom_id as kolom_respon,a.soal_id as soal_id_respon,b.soal_id as soal_id_jwb')
                        ->join('jawaban b','b.jawaban_id=a.jawaban_id','left')
                        ->join('soal c','c.soal_id=b.soal_id','left')
                        ->where('b.status_cd','normal')
                        ->where('a.created_user_id',$user_id)
                        // ->where('a.session',$session)
                        ->where('a.materi',$materi)
                        ->where('a.kolom_id',$kolom_id)
                        ->where('a.group_id',4)
                        ->get();
        } else {
            return $this->db->table('respon a')
                        ->select('*,a.pilihan_nm as pilihan_respon,a.kolom_id as kolom_respon,a.soal_id as soal_id_respon,b.soal_id as soal_id_jwb')
                        ->join('jawaban b','b.jawaban_id=a.jawaban_id','left')
                        ->join('soal c','c.soal_id=b.soal_id','left')
                        ->where('b.status_cd','normal')
                        ->where('a.created_user_id',$user_id)
                        ->where('a.session',$session)
                        ->where('a.materi',$materi)
                        ->where('a.kolom_id',$kolom_id)
                        ->where('a.group_id',4)
                        ->get();
        }
    }

    public function getResponByJawabanId($jawaban_id,$group_id,$materi,$user_id,$session) {
        return $this->db->table('respon')
                        ->select('*')
                        ->where('jawaban_id',$jawaban_id)
                        ->where('group_id',$group_id)
                        ->where('created_user_id',$user_id)
                        ->where('session',$session)
                        ->where('materi',$materi)
                        ->get();
    }

    public function getResponBox($no_soal,$group_id,$materi,$user_id,$session) {
        return $this->db->table('respon')
                        ->select('*')
                        ->where('no_soal',$no_soal)
                        ->where('group_id',$group_id)
                        ->where('created_user_id',$user_id)
                        ->where('session',$session)
                        ->where('materi',$materi)
                        ->get();
    }


    public function getPasshandSkor($user_id,$session,$materi) {
        if ($session == "") {
            return $this->db->table('respon a')
                        ->select('*, a.pilihan_nm as pilihan_respon, a.no_soal as no_soal_respon')
                        ->join('jawaban b','b.jawaban_id=a.jawaban_id','left')
                        ->join('soal c','c.soal_id=b.soal_id','left')
                        ->where('b.status_cd','normal')
                        ->where('a.group_id',1)
                        ->where('a.created_user_id',$user_id)
                        // ->where('a.session',$session)
                        ->where('c.materi',$materi)
                        ->get();
        } else {
            return $this->db->table('respon a')
                        ->select('*, a.pilihan_nm as pilihan_respon, a.no_soal as no_soal_respon')
                        ->join('jawaban b','b.jawaban_id=a.jawaban_id','left')
                        ->join('soal c','c.soal_id=b.soal_id','left')
                        ->where('a.group_id',1)
                        ->where('a.created_user_id',$user_id)
                        ->where('a.session',$session)
                        ->where('c.materi',$materi)
                        ->get();
        }
    }

    public function getKecerdasanSkor($user_id,$session,$materi) {
        if ($session == "") {
            return $this->db->table('respon a')
                        ->select('*, a.pilihan_nm as pilihan_respon, a.no_soal as no_soal_respon,c.kunci as kunci_soal,b.jawaban_nm as jawaban_nmx')
                        ->join('jawaban b','b.jawaban_id=a.jawaban_id','left')
                        ->join('soal c','c.soal_id=b.soal_id','left')
                        ->where('a.group_id',2)
                        ->where('a.created_user_id',$user_id)
                        // ->where('a.session',$session)
                        ->where('a.materi',$materi)
                        ->get(); 
        } else {
            return $this->db->table('respon a')
                        ->select('*')
                        ->join('jawaban b','b.jawaban_id=a.jawaban_id','left')
                        ->join('soal c','c.soal_id=b.soal_id','left')
                        ->where('a.group_id',2)
                        ->where('a.created_user_id',$user_id)
                        // ->where('a.session',$session)
                        ->where('a.materi',$materi)
                        ->get();
        }
        
        
    }

    public function getKepribadianSkor($user_id,$session,$materi) {
        if ($session == "") {
            return $this->db->table('respon a')
                        ->select('*, a.pilihan_nm as pilihan_respon, a.no_soal as no_soal_respon,c.kunci as kunci_soal,b.jawaban_nm as jawaban_nmx')
                        ->join('jawaban b','b.jawaban_id=a.jawaban_id','left')
                        ->join('soal c','c.soal_id=b.soal_id','left')
                        ->where('a.group_id',3)
                        ->where('a.created_user_id',$user_id)
                        // ->where('a.session',$session)
                        ->where('c.materi',$materi)
                        ->get();
        } else {
            return $this->db->table('respon a')
                        ->select('*')
                        ->join('jawaban b','b.jawaban_id=a.jawaban_id','left')
                        ->join('soal c','c.soal_id=b.soal_id','left')
                        ->where('a.group_id',3)
                        ->where('a.created_user_id',$user_id)
                        ->where('a.session',$session)
                        ->where('c.materi',$materi)
                        ->get();
        }
        
        
    }

    public function getSikapKerjaSkor($user_id,$session,$materi) {
        if ($session == "") {
            return $this->db->table('respon a')
                        ->select('*')
                        ->join('jawaban b','b.jawaban_id=a.jawaban_id','left')
                        ->join('soal c','c.soal_id=b.soal_id','left')
                        ->where('a.group_id',4)
                        ->where('a.created_user_id',$user_id)
                        // ->where('a.session',$session)
                        ->where('c.materi',$materi)
                        ->get();
        } else {
            return $this->db->table('respon a')
                        ->select('*')
                        ->join('jawaban b','b.jawaban_id=a.jawaban_id','left')
                        ->join('soal c','c.soal_id=b.soal_id','left')
                        ->where('a.group_id',4)
                        ->where('a.created_user_id',$user_id)
                        ->where('a.session',$session)
                        ->where('c.materi',$materi)
                        ->get();
        }
        
    }

    public function getMaxSessionUser($user_id) {
        return $this->db->table('session_soal')
                        ->select('MAX(session_soal_nm) as session')
                        ->where('user_id',$user_id)
                        ->get();
    }

    public function simpanRespon($data) {
        $this->db->table('respon')
                 ->insert($data);
        return $this->db->insertID();
    }

    public function simpansoal($data) {
        $this->db->table('soal')
                 ->insert($data);
        return $this->db->insertID();
    }

    public function simpanjawaban($data) {
        $this->db->table('jawaban')
                 ->insert($data);
        return $this->db->insertID();
    }

    public function updateResponPrev($soal_id,$jawaban_id,$group_id,$materi,$user_id,$session,$data) {
        return $this->db->table('respon')
                        ->set($data)
                        ->where('soal_id',$soal_id)
                        ->where('group_id',$group_id)
                        ->where('materi',$materi)
                        ->where('created_user_id',$user_id)
                        ->where('session',$session)
                        ->update();
    }

    public function updatejawaban($jawaban_id,$data) {
        return $this->db->table('jawaban')
                        ->set($data)
                        ->where('jawaban_id',$jawaban_id)
                        ->update();
    }

    public function updatesoal($soal_id,$data) {
        return $this->db->table('soal')
                        ->set($data)
                        ->where('soal_id',$soal_id)
                        ->update();
    }

    public function updatestatus($jawaban_nm,$kolom_id,$status_cd,$old_status) {
        $db = db_connect();
        return $db->query("UPDATE jawaban a JOIN soal b ON b.soal_id = a.soal_id SET a.status_cd = '$status_cd' WHERE a.jawaban_nm = '$jawaban_nm' AND b.kolom_id = $kolom_id");
    }

    public function hapussoal($soal_id,$data) {
        return $this->db->table('soal')
                        ->set($data)
                        ->where('soal_id',$soal_id)
                        ->update();
    }

    

    public function deletejawaban($jawaban_id) {
        return $this->db->table('jawaban')
                        ->set('status_cd','nullified')
                        ->where('jawaban_id',$jawaban_id)
                        ->update();
    }

    public function insertsoalSKlatihan($data) {
        $this->db->table('soal')
                 ->insert($data);
        return $this->db->insertID();
    }

    public function insertjawabanSKlatihan($datax) {
        $this->db->table('jawaban')
                 ->insert($datax);
    }

    public function insertsessionskor($data) {
        return $this->db->table('session_soal')
                        ->insert($data);
    }

    public function getSessionSkor($user_id) {
        return $this->db->table('session_soal')
                        ->select('*')
                        ->where('session_soal_nm','materi4')
                        ->where('user_id',$user_id)
                        ->get();
    }

    public function getSKgroup() {
        return $this->db->table('sk_group')
                        ->select('*')
                        ->where('status_cd','normal')
                        ->orderby('sk_group_id','DESC')
                        ->limit(1)
                        ->get();
    }

    public function insertSKgroup($data) {
        $this->db->table('sk_group')
                 ->insert($data);
        return $this->db->insertID();
    }

    public function cekRespon($group_id,$materi,$user_id,$session) {
        return $this->db->table('respon a')
                        ->select('count(a.respon_id) as jml_respon')
                        ->where('a.group_id',2)
                        ->where('a.created_user_id',$user_id)
                        ->where('a.session',$session)
                        ->where('a.materi',$materi)
                        ->get();
    }

    public function getSKgroups() {
        return $this->db->table('sk_group')
                        ->select('*')
                        ->where('status_cd','normal')
                        ->get();
    }
}
