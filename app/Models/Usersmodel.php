<?php namespace App\Models;

use CodeIgniter\Model;

class Usersmodel extends Model
{
    protected $table      = 'users';
    // protected $primaryKey = 'user_id';
    protected $allowedFields = ['user_nm', 'pwd0','user_group','person_id','status_cd', 'created_dttm','created_user','update_dttm','update_user','nullified_dttm','nullified_user'];


    public function checklogin($u,$p) {
        return $this->db->table('users')
                        ->where('user_nm', $u)
                        ->where('pwd0',$p)
                        ->get();
    }

    public function getbyCellphone($cellphone) {
        return $this->db->table('person a')
                        ->select('*')
                        ->join('users b', 'b.person_id = a.person_id','left')
                        ->where('a.status_cd', 'normal')
                        ->where('a.cellphone', $cellphone)
                        ->get();
    }

    public function getbynormal() {
        return $this->db->table('person a')
                        ->select('*')
                        ->join('users b', 'b.person_id = a.person_id','left')
                        ->where('a.status_cd', 'normal')
                        ->get();
    }

    public function getbyId($id){
        return $this->db->table('person a')
                 ->select('*')
                 ->join('users b', 'b.person_id = a.person_id','left')
                 ->where('a.person_id',$id)
                 ->get();
    }

    public function getbyUserId($user_id){
        return $this->db->table('person a')
                 ->select('*')
                 ->join('users b', 'b.person_id = a.person_id','left')
                 ->where('b.user_id',$user_id)
                 ->get();
    }

    public function getbyUsernm($user_nm){
        return $this->db->table('users')
                        ->where('user_nm',$user_nm)
                        ->get();
    }

    public function updateuser($person_id,$data) {
        return $this->db->table('users')
                        ->set($data)
                        ->where('person_id',$person_id)
                        ->update();
    }

    public function updateperson($person_id,$data) {
        return $this->db->table('person')
                        ->set($data)
                        ->where('person_id',$person_id)
                        ->update();
    }

    public function getMaxSessionUser($user_id) {
        return $this->db->table('session_soal')
                        ->select('session_soal_nm')
                        ->where('user_id',$user_id)
                        ->orderby('session_soal_id','desc')
                        ->limit(1)
                        ->get();
    }

    public function simpanSessionUser($data) {
        $this->db->table('session_soal')
                 ->insert($data);
    }

    public function simpanuser($data) {
        $this->db->table('users')
                 ->insert($data);
        return $this->db->insertID();
    }

    public function simpanperson($data) {
        $this->db->table('person')
                 ->insert($data);
        return $this->db->insertID();
    }

    public function hapususer($person_id) {
        $this->db->table("person")
                 ->set("status_cd","nullified")
                 ->where("person_id",$person_id)
                 ->update();

        $this->db->table("users")
                 ->set("status_cd","nullified")
                 ->where("person_id",$person_id)
                 ->update();
    }

    
}