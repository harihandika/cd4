<?php

namespace App\Controllers;
use App\Models\Soalmodel;
use App\Models\Latihanmodel;
class Latihan extends BaseController
{
    protected $soalmodel;
    protected $latihanmodel;
    public function __construct()
	{
		$this->session = \Config\Services::session();
        $this->session->start();
        $this->soalmodel = new Soalmodel();
        $this->latihanmodel = new Latihanmodel();
	}


    public function index()
    {
        if ($this->session->get("user_nm") == "") {
			return redirect('/');
		} else {
            $data = [
                'jenis_soal' => $this->latihanmodel->getJenisSoal()->getResult(),
                'materiSK' => $this->soalmodel->getMateriSK()->getResult(),
            ];
            return view('front/latihan',$data);
        }
        
    }

    public function petunjuksoal() {
		$jenis_id = $this->request->getPost('jenis_id');
        $resjenis = $this->latihanmodel->getJenisByid($jenis_id)->getResult();
        if (count($resjenis)>0) {
            $ret = "<div class='col-lg-12' style='text-align:center;min-height: 400px;color:#000000;'>
                        <h1 style='margin:20px;'>Petunjuk ".$resjenis[0]->jenis_nm."</h1>
                        <p style='text-align:center;font-size:20px;'>".$resjenis[0]->petunjuk."</p>
                        <button style='font-size: 30px;' class='btn btn-primary' onclick='startlatihan(\"start\",11,1,$jenis_id,\"null\",\"null\")'>Mulai</button>
                    </div>";
        } else {
            $ret = "<div class='col-lg-12' style='text-align:center;min-height: 400px;color:#000000;'>
                        <h1 style='margin:20px;'>Jenis Soal tidak ada</h1>
                    </div>";
        }
        
        echo json_encode($ret);
    }

    public function startlatihan() {
		$soal_id        = $this->request->getPost('soal_id');
		$class_soal     = $this->request->getPost('class_soal');
		$materi         = $this->request->getPost('materi');
		$no_soal        = $this->request->getPost('no_soal');
		$jenis_id       = $this->request->getPost('jenis_id');
		$jawaban_id     = $this->request->getPost('jawaban_id');
		$pilihan_nm     = $this->request->getPost('pilihan_nm');
		$used           = $this->request->getPost('used');
        
        $date = date("Y-m-d H:i:s");

        if (!isset($jawaban_id)) {
            echo json_encode("jawabankosong");
        } else {

            if ($jawaban_id !== "null" && isset($soal_id)) {
                $data = [
                    "jawaban_id" => $jawaban_id,
                    "pilihan_nm" => $pilihan_nm,
                    "soal_id" => $soal_id,
                    "no_soal" => $no_soal - 1,
                    "materi" => $materi,
                    "jenis_id" => $jenis_id,
                    "used" => 0,
                    "created_user_id" => $this->session->user_id,
                    "created_dttm" => $date,
                    "session" => $this->session->session
                ];
    
                $respon_id = $this->latihanmodel->simpanRespon($data);
            }

            $max_no_soal = $this->latihanmodel->getMaxNoSoal($jenis_id,$materi)->getResult();
            $max_no_soal = $max_no_soal[0]->max_nosoal + 1;
            
            if ($no_soal <= $max_no_soal) {
                $boxnomorsoal = "";
                $res = $this->latihanmodel->getSoal($no_soal,$jenis_id)->getResult();
                if (count($res)>0) {
                    $res_ttlsoal = $this->latihanmodel->getTotalSoal($jenis_id)->getResult();
                    foreach ($res_ttlsoal as $boxsoal) {
                        $getResponBox = $this->latihanmodel->getResponBox($boxsoal->no_soal,$jenis_id,$this->session->user_id)->getResult();
    
                        if (count($getResponBox)>0) {
                            $style = "style='border:2px solid #79db79;width:50px;heigth:50px;margin:5px;'";
                        } else {
                            $style = "style='border:2px solid red;width:50px;heigth:50px;margin:5px;'";
                        }
    
                        $boxnomorsoal .= "<label $style>".$boxsoal->no_soal."</label>";
                    }
                        $ret = "<div class='row'>
                        <div class='col-lg-4'>
                        <div style='border:1px solid black;text-align:center;'>";
                            $ret .= $boxnomorsoal; 
                            if ($res[0]->soal_img == "") {
                                $img_soal = "";
                            } else {
                                $img_soal = "<img style='max-width:300px;heigth:100%;' src='".base_url()."/images/soal_latihan/jenis/$jenis_id/".$res[0]->soal_img.".jpg'>";
                            }
                              
                        $ret .= "</div></div>
                                <div class='col-lg-7' style='margin-left:20px;padding-left:20px;'>
                                <div style='width:100%;text-align:center;'>
                                <h1 style='margin:10px;text-decoration:underline;'>".$res[0]->jenis_nm."</h1>
                                </div>
                                <div>
                                <input type='hidden' name='soal_id' id='soal_id' value='".$res[0]->soal_id."' />
                                    <label style='font-size:20px;'>Soal ".$res[0]->no_soal."</label>
                                    <p style='font-size:25px;'>".$res[0]->soal_nm." $img_soal</p>
                                    
                                </div>
                                <div>
                                    <label style='font-size:20px;'>Jawaban</label>";
                                    $getjawaban = $this->latihanmodel->getjawaban($res[0]->soal_id)->getResult();
                                    foreach ($getjawaban as $key) {
                                        if ($key->jawaban_img == "") {
                                            $img_jwb = "";
                                        } else {
                                            $img_jwb = "<img style='max-width:150px;heigth:100%;' src='".base_url()."/images/jawaban_latihan/jenis/$jenis_id/".$key->jawaban_img.".jpg'>";
                                        }
                                        
                                        $jawaban_idbox = $key->jawaban_id;
                                        $ret .= "<div class='col-md-12 row' style='margin-bottom:10px;'><div class='col-md-1' style='text-align: center;'><input  type='radio' name='jawaban' id='jawaban_${jawaban_idbox}' value='".$key->jawaban_id."' data-pilihan='".$key->pilihan_nm."'/> </div><div class='col-md-11' style='padding:0px;'><label style='font-size:20px;' for='jawaban_${jawaban_idbox}'>".$key->pilihan_nm.". ".$key->jawaban_nm."</label> $img_jwb</div></div>";
                                    }
                                    $ret .= "<div><div style='text-align:right;'><button style='font-size:25px;' onclick='startlatihan(\"nextsoal\",$materi,$no_soal,$jenis_id,\"radio\")' class='btn btn-primary'>Next</button></div></div>";
                            $ret .= "</div>
                            </div>";
                } else {
                    $ret = "belumadasoal";
                }
                
                
            } else {
                $ret = "finish";
            }

            echo json_encode(array("html"=>$ret,"jenis_id"=>$jenis_id,"no_soal"=>$no_soal,"class_soal"=>$class_soal),JSON_UNESCAPED_SLASHES);
        }

        
    }

    public function showresult() {
        $jenis_id = $this->request->getPost('jenis_id');
        $benar  = 0;
        $salah  = 0;
        $res = $this->latihanmodel->getResponJenis($jenis_id,$this->session->user_id)->getResult();
        if (count($res)>0) {
            foreach ($res as $k) {
                if ($k->kunci_soal == $k->pilihan_respon) {
                    $benar = $benar + 1;
                } else {
                    $salah = $salah + 1;
                }
            }
        } else {
            # code...
        }
        
            $ret = "<div class='col-lg-12'>
                        <div style='width:100%;text-align:center;color:#000000;'>
                            <h1 style='margin:10px;'>SELAMAT !</h1>
                            <h1 style='margin:10px;'><span>SKOR ANDA</span> </h1>
                            <h1 style='margin:10px;'><span>Benar : $benar</span></h1>
                            <h1 style='margin:10px;'><span>Salah : $salah</span></h1>
                        </div>
                    </div>";

        echo json_encode(array("html"=>$ret));
    }

    public function showsk_grp() {
        $res = $this->latihanmodel->getSKgroup()->getResult();
        
        if (count($res)>0) {
            $db = db_connect();
            $user_id = $this->session->user_id;
            $ret = "<div class='row'>
                <div class='col-lg-12' style='text-align:center;'>
                <h1 style='margin:5px;text-decoration:underline;font-weight:bold;color:#ffffff;'> LATIHAN </h1>
                </div>";
            foreach ($res as $key) {
                $sk_group_id = $key->sk_group_id;
                $query = $db->query("SELECT * FROM respon a JOIN soal b ON b.soal_id=a.soal_id WHERE a.materi = 5 AND a.created_user_id = $user_id AND b.sk_group_id = $sk_group_id")->getResultArray();

                if (count($query)>0) {
                $used = $query[0]['used'] + 1;
                } else {
                $used = 1;
                }
                $ret .= "<div class='col-lg-2' style='cursor:pointer;margin-top:20px;text-align:center;'>
                            <div onclick='petunjuksoalsk(\"petunjuk\",0,$used,$sk_group_id)' id='sk_group_".$key->sk_group_id."'><img style='height:200px;width:150px;border: 2px solid white;border-radius:10px;' src='images/bg/photo3.jpg'></div>
                            <label style='color:#ffffff;font-size:20px;'>".$key->sk_group_nm."</label>
                        </div>";
            }
            $ret .= "</div>";
        } else {
            $ret = "Belum ada soal";
        }
        
        echo json_encode($ret);
    }
}
