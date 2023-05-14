<?php

namespace App\Controllers\Admin;
use App\Controllers\BaseController;
use App\Models\Usersmodel;
use App\Models\Soalmodel;
class Dashboard extends BaseController
{
    protected $usermodel;
    protected $soalmodel;
    public function __construct()
	{
		$this->session = \Config\Services::session();
        $this->usermodel = new Usersmodel();
        $this->soalmodel = new Soalmodel();
	}


    public function index()
    {
        if ($this->session->get("user_nm") == "") {
			return redirect('/');
		} else {
            $benar_passhand = 0;
            $salah_passhand = 0;
            $benar_kec = 0;
            $salah_kec = 0;
            $benar_keb = 0;
            $salah_keb = 0;
            $benar_sk  = 0;
            $salah_sk  = 0;
            $total_skor  = 0;
            $persen_kec  = 0;
            $persen_kep  = 0;
            $persen_sk = 0;
            $ttl_benar_sk = 0;
            $userlulus  = 0;
            $usertidaklulus = 0;

            $resmateri = $this->soalmodel->getjawAllJMateri()->getResult();
            foreach ($resmateri as $mtr) {
                $getuser = $this->usermodel->getbynormal()->getResult();
                foreach ($getuser as $usr) {
                    $kecerdasanskor = $this->soalmodel->getKecerdasanSkor($usr->user_id,"",1,$mtr->materi_id)->getResult();
                    foreach ($kecerdasanskor as $kec) {
                        if ($kec->kunci == $kec->pilihan_nm) {
                            $benar_kec = $benar_kec + 1;
                        } else {
                            $salah_kec = $salah_kec + 1;
                        }
                    }
                    $persen_kec = ($benar_kec * 0.0025) * 100;
                }

                $kepskor = $this->soalmodel->getKepribadianSkor($usr->user_id,"",1,$mtr->materi_id)->getResult();
                foreach ($kepskor as $kep) {
                    if ($kep->kunci == $kep->pilihan_nm) {
                        $benar_keb = $benar_keb + 1;
                    } else {
                        $salah_keb = $salah_keb + 1;
                    }
                }
                $persen_kep = ($benar_keb * 0.005) * 100;

                // SIKAP KERJA
                $klm = $this->soalmodel->getKolomSoal()->getResult();
                foreach ($klm as $key) {
                    $benar = 0;
                    $salah = 0;
                    $res_responSK = $this->soalmodel->getResponSikapKerja($usr->user_id,"",$key->kolom_id,$mtr->materi_id)->getResult();
                    if (count($res_responSK)>0) {
                        foreach ($res_responSK as $rSK) {
                            if ($rSK->pilihan_respon == $rSK->kunci) {
                                $benar = $benar + 1;
                            } else {
                                $salah = $salah + 1;
                            }  
                        }
                    } else {
                        
                    }
                    $ttl_benar_sk = $ttl_benar_sk + $benar;
                
                    $persen_sk = ($ttl_benar_sk * 0.0005) * 100;
                    $total_skor = $persen_sk + $persen_kep + $persen_kec;
                    if ($total_skor >= 61) {
                    $userlulus = $userlulus + 1;
                    } else {
                    $usertidaklulus = $usertidaklulus + 1;
                    }
                }


                if ($mtr->materi_id == 1) {
                    $userlulus1 = $userlulus;
                    $usertidaklulus1 = $usertidaklulus;
                } else if ($mtr->materi_id == 2) {
                    $userlulus2 = $userlulus;
                    $usertidaklulus2 = $usertidaklulus;
                } else if ($mtr->materi_id == 3) {
                    $userlulus3 = $userlulus;
                    $usertidaklulus3 = $usertidaklulus;
                } else if ($mtr->materi_id == 4) {
                    $userlulus4 = $userlulus;
                    $usertidaklulus4 = $usertidaklulus;
                } 
                

            }
            
            $data = [
                "userlulus1" => $userlulus1,
                "userlulus2" => $userlulus2,
                "userlulus3" => $userlulus3,
                "userlulus4" => $userlulus4,
                "usertidaklulus1" => $usertidaklulus1,
                "usertidaklulus2" => $usertidaklulus2,
                "usertidaklulus3" => $usertidaklulus3,
                "usertidaklulus4" => $usertidaklulus4,                
                "user" => $this->usermodel->getbynormal()->getResult(),
            ];
            return view('admin/dashboard',$data);
        }
        
    }

    
}
