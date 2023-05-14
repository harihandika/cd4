<?php

namespace App\Controllers\Admin;
use App\Controllers\BaseController;
use App\Models\Soalmodel;
use App\Models\Latihanmodel;
class Soallatihan extends BaseController
{
    protected $soalmodel;
    protected $latihanmodel;
    public function __construct()
	{
		$this->session = \Config\Services::session();
        $this->soalmodel = new Soalmodel();
        $this->latihanmodel = new Latihanmodel();
	}


    public function index()
    {
        if ($this->session->get("user_nm") == "") {
			return redirect('/');
		} else {
            $data = [
                'jenis' => $this->latihanmodel->getJenisSoal()->getResult(),
                'group' => $this->soalmodel->getGroup()->getResult(),
                'soal' => $this->showsoal()
            ];
            return view('admin/soallatihan',$data);
        }
        
    }

    public function showsoal() {
        $jenis     = $this->request->getPost('jenis');
        $filter     = $this->request->getPost('filter');
        $this->session->set("jenis",$jenis);
        $res = $this->latihanmodel->getAllSoal($jenis)->getResult();
        
        $no = 1;
        
        $ret = "<table id='example2' class='table table-bordered table-hover'>
                    <thead>
                    <tr>
                    <th style='text-align:center;width:50px;'>No.</th>
                    <th style='text-align:center;width:100px;'>No. Soal</th>
                    <th style='text-align:center;'>Soal</th>
                    <th style='text-align:center;width:50px;'>Kunci</th>
                    <th style='text-align:center;width:50px;'>Jenis</th>
                    <th style='text-align:center;'>Gambar</th>
                    <th style='text-align:center;'>Action</th>
                    </tr>
                    </thead>
                    <tbody>";
                    
                foreach ($res as $key) {
                    $soal_id = $key->soal_id;
                    $ret .= "<tr>
                            <td style='text-align:center;'>". $no++."</td>
                            <td style='text-align:center;'>".$key->no_soal."</td>
                            <td style='text-align:center;'>".$key->soal_nm."</td>
                            <td style='text-align:center;'>".$key->kunci."</td>
                            <td style='text-align:center;'>".$key->jenis_nm."</td>";
                        $ret .= "<td style='text-align:center;'>";
                            if ($key->soal_img == "") {
                                $ret .= "";
                            } else {
                                $ret .= "<img style='max-width:300px;heigth:100%;' src='".base_url()."/images/soal/materi/".$key->materi."/".$key->soal_img."'>";
                            }
                    $ret .= "</td>
                            <td style='text-align:center;'><button onclick='editsoal(".$key->soal_id.")' style='font-size:16px;' class='btn btn-secondary' data-toggle='modal' data-target='#modal-lg'>Edit</button> <button onclick='hapussoal(".$key->soal_id.")' style='font-size:16px;' class='btn btn-danger'>Hapus</button> <div class='form-group'>
                            <div class='custom-control custom-switch'>
                              <input onclick='checkboxenable($soal_id)' type='checkbox' class='custom-control-input' id='customSwitch1_${soal_id}' ".($key->status_soal=='normal'?'checked':'')."/>
                              <label class='custom-control-label' for='customSwitch1_${soal_id}'>enable/disable</label>
                            </div>
                          </div></td>
                            </tr>
                                <tr class='tr_parentdata' style='background-color:#ececec54;' id='tr_data_${soal_id}'>
                                </tr>";
                        }
                    
                $ret .= "</tbody>
                </table>";

        return $ret;
    }


    public function editsoal() {
        $soal_id    = $this->request->getPost('soal_id');
        $res = $this->latihanmodel->getSoalbyid($soal_id)->getResult();
        foreach ($res as $key) {
                $ret = "<div class='card'>
                    <div class='card-body'>
                    <div class='row'>
                    <div class='col-sm-12'>
                    <div class='form-group'>
                        <div class='card-body'>
                        <div class='form-group row'>
                        <label>Jenis : </label>";
                $ret .= "<div class='col-10'>
                <select class='form-control' id='jenis_soal'>
                        <option>Pilih Jenis</option>";
                        $resjenis = $this->latihanmodel->getJenisSoal()->getResult();
                            foreach ($resjenis as $k) {
                                $jenis_id = $k->jenis_id;
                                $ret .= "<option ".($key->jenis_id==$jenis_id?'selected':'')." value='$jenis_id'>".$k->jenis_nm."</option>";
                            }
                $ret .= "</select>";
                        $ret .= "</div></div>
                        <div class='form-group row'>
                            <label for='no_soal' class='col-sm-2 col-form-label'>No Soal</label>
                            <div class='col-2'>
                            <input type='text' class='form-control' id='no_soal' name='no_soal' value='".$key->no_soal."'>
                            </div>
                            <label for='no_soal' class='col-sm-2 col-form-label'>Kunci</label>
                            <div class='col-2'>
                            <input type='text' class='form-control' id='kunci' name='kunci' value='".$key->kunci."'>
                            </div>
                        </div>
                        <div class='form-group row'>
                            <label for='soal_nm' class='col-sm-2 col-form-label'>Soal</label>
                            <div class='col-sm-10'>
                            <textarea class='form-control' id='soal_nm' name='soal_nm'>".$key->soal_nm."</textarea>
                            </div>
                        </div>
                        <div class='form-group row'>
                            <label for='soal_img' class='col-sm-2 col-form-label'>Gambar</label>
                            <div class='col-sm-10'>
                            <input type='file' class='form-control' id='soal_img' name='soal_img'/>
                            <input type='hidden' class='form-control' id='soal_img_lama' name='soal_img_lama' value='".$key->soal_img."'/>";
                            if ($key->soal_img == "") {
                                $ret .= "";
                            } else {
                                $ret .= "<img src='".base_url()."/images/soal/jenis/".$key->jenis_id."/".$key->soal_img."' style='width: 150px;height: 150px;'>";
                            }
                            
                        $ret .= "</div>
                        </div>
                        </div>
                        <div class='card-footer'>
                        <button onclick='updatesoallatihan(".$key->soal_id.")' type='button' class='btn btn-info'>Update</button>
                        <button type='button' class='btn btn-default float-right' data-dismiss='modal' aria-label='Close'>Cancel</button>
                        </div>";
                        
            $ret .= "</div>
                    </div>
                    </div>
                    </div>
                    </div>";
        }
        

        return $ret;
    }

    public function simpansoal() {
        $soal_nm    = $this->request->getPost('soal_nm');
        $jenis_soal   = $this->request->getPost('jenis_soal');
        $kunci      = $this->request->getPost('kunci');
        $no_soal    = $this->request->getPost('no_soal');
        $this->session->set("jenis",$jenis_soal);
        $newName = "";
        if($imagefile = $this->request->getFiles()){
            foreach($imagefile['soal_img'] as $img){
               if ($img->isValid() && ! $img->hasMoved()){
                    $newName = $img->getClientName();
                    $img->move("../public/images/soal_latihan/jenis/$jenis_soal", $newName);
                    

                   }
             }
        }

        $data = [
            'soal_nm' => $soal_nm,
            'no_soal' => $no_soal,
            'kunci' => $kunci,
            'jenis_id' => $jenis_soal,
            'status_cd' => 'normal',
            'soal_img' => $newName,
        ];
        $soal_id = $this->latihanmodel->simpansoal($data);
        echo "sukses";
        // echo json_encode($group[0]->group_nm);
    }

    public function updatesoal() {
        $soal_id        = $this->request->getPost('soal_id');
        $soal_nm        = $this->request->getPost('soal_nm');
        $jenis_soal     = $this->request->getPost('jenis_soal');
        $kunci          = $this->request->getPost('kunci');
        $no_soal        = $this->request->getPost('no_soal');
        $soal_img_lama  = $this->request->getPost('soal_img_lama');

        $newName = "";
        if($imagefile = $this->request->getFiles()){
            foreach($imagefile['soal_img'] as $img){
               if ($img->isValid() && ! $img->hasMoved()){
                    $newName = $img->getClientName();
                    $img->move("../public/images/soal/jenis/$jenis_soal", $newName);
                        $data = [
                            'soal_nm' => $soal_nm,
                            'no_soal' => $no_soal,
                            'kunci' => $kunci,
                            'jenis_id' => $jenis_soal,
                            'status_cd' => 'normal',
                            'soal_img' => $newName,
                        ];
                        $this->latihanmodel->updatesoal($soal_id,$data);

                   }
             }
        } else {
            $data = [
                'soal_nm' => $soal_nm,
                'no_soal' => $no_soal,
                'kunci' => $kunci,
                'jenis_id' => $jenis_soal,
                'status_cd' => 'normal'
            ];
            $this->latihanmodel->updatesoal($soal_id,$data);
        }

        
        // echo json_encode(array("soal_id"=>$soal_id,"group_nm"=>$group[0]->group_nm));
        echo "sukses";
    }

    public function hapussoal() {
        $soal_id = $this->request->getPost('soal_id');
        $data = [
            'status_cd' => 'nullified'
        ];
        $this->latihanmodel->hapussoal($soal_id,$data);
        // echo json_encode(array("soal_id"=>$soal_id,"group_nm"=>$group[0]->group_nm));
        echo json_encode("sukses");
    }


    public function tambahsoallatihan() {
        $ret = "<div class='card'>
                <div class='card-body'>
                <div class='row'>
                <div class='col-sm-12'>
                <div class='form-group'>
                    <div class='card-body'>
                    <div class='form-group row'>
                    <label>Jenis : </label>";
            $ret .= "<div class='col-10'>
            <select class='form-control' id='jenis_soal'>
                    <option>Pilih Jenis</option>";
                    $resjenis = $this->latihanmodel->getJenisSoal()->getResult();
                        foreach ($resjenis as $k) {
                            $jenis_id = $k->jenis_id;
                            $ret .= "<option value='$jenis_id'>".$k->jenis_nm."</option>";
                        }
            $ret .= "</select>";
                    $ret .= "</div></div>
                    <div class='form-group row'>
                        <label for='no_soal' class='col-sm-2 col-form-label'>No Soal</label>
                        <div class='col-2'>
                        <input type='text' class='form-control' id='no_soal' name='no_soal'>
                        </div>
                        <label for='no_soal' class='col-sm-2 col-form-label'>Kunci</label>
                        <div class='col-2'>
                        <input type='text' class='form-control' id='kunci' name='kunci'>
                        </div>
                    </div>
                    <div class='form-group row'>
                        <label for='soal_nm' class='col-sm-2 col-form-label'>Soal</label>
                        <div class='col-sm-10'>
                        <textarea class='form-control' id='soal_nm' name='soal_nm'></textarea>
                        </div>
                    </div>
                    <div class='form-group row'>
                        <label for='soal_img' class='col-sm-2 col-form-label'>Gambar</label>
                        <div class='col-sm-10'>
                        <input type='file' class='form-control' id='soal_img' name='soal_img'/>
                        </div>
                    </div>
                    </div>
                    <div class='card-footer'>
                    <button onclick='simpansoallatihan()' type='button' class='btn btn-info'>Simpan</button>
                    <button type='button' class='btn btn-default float-right' data-dismiss='modal' aria-label='Close'>Cancel</button>
                    </div>";
                    
        $ret .= "</div>
                </div>
                </div>
                </div>
                </div>";

        return $ret;
    }

    

    public function updatestatus() {
        $jawaban_nm = $this->request->getPost('jawaban_nm');
        $kolom_id = $this->request->getPost('kolom_id');
        $status_cd = $this->request->getPost('status_cd');
        $old_status = $this->request->getPost('old_status');

        $update = $this->latihanmodel->updatestatus($jawaban_nm,$kolom_id,$status_cd,$old_status);
        log_message("debug",$status_cd);
    }

}
