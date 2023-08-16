<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class URLController extends CI_Controller {

    protected $datasend;
	
		
    public function __construct() {
        parent::__construct();
		
        $this->datasend = [];
		if ($this->session->userdata('status') <> 'login') {
				redirect(site_url("login"));
				die();
		}
        $this->load->model("MasterData/PermissionModel", "PermissionModel");
        $this->datasend['SESSION'] = $this->session;
        $this->datasend['DtUser2'] = $this->PermissionModel->GetDtUser($this->datasend['SESSION']->FCCODE);
        $this->datasend['ROLEACCESS'] = $this->datasend['DtUser2']->USERGROUPID;
        $this->datasend['DEPARTEMENT'] = $this->PermissionModel->GetDepartement($this->datasend['SESSION']->FCCODE);
        $this->datasend['Menu'] = $this->PermissionModel->GetMenu($this->datasend['ROLEACCESS']);
    }

    public function index() {
        $this->load->model('Welcome_model');
        $this->datasend['formid'] = 'home';
        $this->datasend['sidebar'] = 'sidebar_view';
        $this->datasend['content'] = 'home/welcome';
        $data = array();
        foreach ($this->Welcome_model->cur_production() as $header) {
            $data[] = $header->QTY_PROD;
            $data[] = $header->CPO;
            $data[] = $header->BJR;
        }
        $this->datasend['service'] = $data;
        $this->load->view('template', $this->datasend);
    }

    public function ChangePassword() {
        $this->datasend['formid'] = 'CP';

        $this->datasend['sidebar'] = 'sidebar_view';
        $this->datasend['content'] = 'ChangePassword';
        $this->load->view('template', $this->datasend);
    }

//  --- Master Data Start ----
    public function MstUser() {
        $this->datasend['formid'] = '2';
        $this->datasend['ACCESS'] = $this->PermissionModel->GetAccessMenu($this->datasend['ROLEACCESS'], $this->datasend['formid']);
        if ($this->datasend['ACCESS']['VIEWS'] != 1) {
            redirect('/');
        }
        $this->datasend['DtUser'] = [];
        if (isset($_GET['type'])) {
            $this->datasend['DtPermission'] = $this->PermissionModel->GetDataActive();
            $this->datasend['DtUserAccess'] = $this->PermissionModel->BMCodeDetail('000003');
            if ($_GET['type'] == 'edit') {
                if (!isset($_GET['id'])) {
                    echo '<script>
                          alert("ID Not Found !!!");
                          window.location.href = window.location.href.split("?")[0];
                          </script>';
                }
                $this->load->model("MasterData/UsersModel", "UsersModel");
                $this->datasend['DtUser'] = $this->UsersModel->GetData($_GET['id']);
                $this->datasend['userDepart']   = $this->datasend['DtUser']->DEPARTMENT;
            }
        }
        $this->datasend['DtDepartment'] = $this->PermissionModel->GetDtDepart($this->datasend['SESSION']->FCCODE);
        $this->datasend['userDepart']   = $this->datasend['DtUser']->DEPARTMENT;
        $this->datasend['sidebar'] = 'sidebar_view';
        $this->datasend['content'] = 'MasterData/MstUser';
        $this->load->view('template', $this->datasend);
    }

    public function MstRoleAccess() {
        $this->datasend['formid'] = '3';
        $this->datasend['ACCESS'] = $this->PermissionModel->GetAccessMenu($this->datasend['ROLEACCESS'], $this->datasend['formid']);
        if ($this->datasend['ACCESS']['VIEWS'] != 1) {
            redirect('/');
        }
        $this->datasend['DtRole'] = [];
        if (isset($_GET['type'])) {
            if ($_GET['type'] == 'edit') {
                if (!isset($_GET['id'])) {
                    echo '<script>
                          alert("ID Not Found !!!");
                          window.location.href = window.location.href.split("?")[0];
                          </script>';
                }
                $this->datasend['DtRole'] = $this->PermissionModel->GetData($_GET['id']);
            }
        }
        $this->datasend['sidebar'] = 'sidebar_view';
        $this->datasend['content'] = 'MasterData/MstRoleAccess';
        $this->load->view('template', $this->datasend);
    }

//  --- General Start (Master Data) ---
    public function MstDepartement() {
        $this->datasend['formid'] = '9';
        $this->datasend['ACCESS'] = $this->PermissionModel->GetAccessMenu($this->datasend['ROLEACCESS'], $this->datasend['formid']);
        if ($this->datasend['ACCESS']['VIEWS'] != 1) {
            redirect('/');
        }
        $this->datasend['DtDepartement'] = [];
        if (isset($_GET['type'])) {
            if ($_GET['type'] == 'edit') {
                if (!isset($_GET['id'])) {
                    echo '<script>
                          alert("ID Not Found !!!");
                          window.location.href = window.location.href.split("?")[0];
                          </script>';
                }
                $this->load->model("MasterData/General/DepartementModel", "DepartementModel");
                $this->datasend['DtDepartement'] = $this->DepartementModel->GetData($_GET['id']);
            }
        }
        $this->datasend['sidebar'] = 'sidebar_view';
        $this->datasend['content'] = 'MasterData/General/MstDepartement';
        $this->load->view('template', $this->datasend);
    }

    public function DepartCat() {
        $this->datasend['formid'] = '62';
        $this->datasend['ACCESS'] = $this->PermissionModel->GetAccessMenu($this->datasend['ROLEACCESS'], $this->datasend['formid']);
        if ($this->datasend['ACCESS']['VIEWS'] != 1) {
            redirect('/');
        }
        $this->datasend['DtDepartement'] = [];
        if (isset($_GET['type'])) {

        $this->load->model("MasterData/General/DepartementModel", "DepartementModel");
        $this->load->model(['MaterialGroupModel', 'ForecastCatModel']);

        $this->datasend['departement'] = $this->DepartementModel->GetDataActive();
        $this->datasend['matgroup'] = $this->MaterialGroupModel->GetDataActive();
        $this->datasend['forcat'] = $this->ForecastCatModel->GetDataActive();

            if ($_GET['type'] == 'edit') {
                if (!isset($_GET['depart']) && !isset($_GET['matgroup']) && !isset($_GET['forcat'])) {
                    echo '<script>
                          alert("ID Not Found !!!");
                          window.location.href = window.location.href.split("?")[0];
                          </script>';
                }
                $this->datasend['DtDepartement'] = $this->DepartementModel->GetDataCat($_GET['depart'], $_GET['matgroup'], $_GET['forcat']);
            }
        }
        $this->datasend['sidebar'] = 'sidebar_view';
        $this->datasend['content'] = 'MasterData/General/MstDepartementCat';
        $this->load->view('template', $this->datasend);
    }

    public function MstCompany() {
        $this->datasend['formid'] = '6';
        $this->datasend['ACCESS'] = $this->PermissionModel->GetAccessMenu($this->datasend['ROLEACCESS'], $this->datasend['formid']);
        if ($this->datasend['ACCESS']['VIEWS'] != 1) {
            redirect('/');
        }
        $this->datasend['DtCompany'] = [];
        if (isset($_GET['type'])) {
            $this->datasend['DtCompanyType'] = $this->PermissionModel->BMCodeDetail('000002');
            if ($_GET['type'] == 'edit') {
                if (!isset($_GET['id'])) {
                    echo '<script>
                          alert("ID Not Found !!!");
                          window.location.href = window.location.href.split("?")[0];
                          </script>';
                }
                $this->load->model("MasterData/General/CompanyModel", "CompanyModel");
                $this->datasend['DtCompany'] = $this->CompanyModel->GetData($_GET['id']);
            }
        }
        $this->datasend['sidebar'] = 'sidebar_view';
        $this->datasend['content'] = 'MasterData/General/MstCompany';
        $this->load->view('template', $this->datasend);
    }

    public function CompanyGroup() {
        $this->datasend['formid'] = '22';
        $this->datasend['ACCESS'] = $this->PermissionModel->GetAccessMenu($this->datasend['ROLEACCESS'], $this->datasend['formid']);
        if ($this->datasend['ACCESS']['VIEWS'] != 1) {
            redirect('/');
        }
        $this->datasend['DtCompanyGroup'] = [];
        if (isset($_GET['type'])) {
            if ($_GET['type'] == 'edit') {
                if (!isset($_GET['id'])) {
                    echo '<script>
                          alert("ID Not Found !!!");
                          window.location.href = window.location.href.split("?")[0];
                          </script>';
                }
                $this->load->model('CompanyGroupModel');
                $this->datasend['DtCompanyGroup'] = $this->CompanyGroupModel->GetData($_GET['id']);
            }
        }
        $this->datasend['sidebar'] = 'sidebar_view';
        $this->datasend['content'] = 'MasterData/CompanyGroup';
        $this->load->view('template', $this->datasend);
    }

    public function CompanySubGroup() {
        $this->datasend['formid'] = '23';
        $this->datasend['ACCESS'] = $this->PermissionModel->GetAccessMenu($this->datasend['ROLEACCESS'], $this->datasend['formid']);
        if ($this->datasend['ACCESS']['VIEWS'] != 1) {
            redirect('/');
        }
        $this->datasend['DtCompanySubGroup'] = [];
        if (isset($_GET['type'])) {
            if ($_GET['type'] == 'edit') {
                if (!isset($_GET['id'])) {
                    echo '<script>
                          alert("ID Not Found !!!");
                          window.location.href = window.location.href.split("?")[0];
                          </script>';
                }
                $this->load->model('CompanySubGroupModel');
                $this->datasend['DtCompanySubGroup'] = $this->CompanySubGroupModel->GetData($_GET['id']);
            }
        }
        $this->datasend['sidebar'] = 'sidebar_view';
        $this->datasend['content'] = 'MasterData/CompanySubGroup';
        $this->load->view('template', $this->datasend);
    }

    public function MstBank() {
        $this->datasend['formid'] = '34';
        $this->datasend['ACCESS'] = $this->PermissionModel->GetAccessMenu($this->datasend['ROLEACCESS'], $this->datasend['formid']);
        if ($this->datasend['ACCESS']['VIEWS'] != 1) {
            redirect('/');
        }
        $this->datasend['DtBank'] = [];
        if (isset($_GET['type'])) {
            $this->load->model("MasterData/General/CompanyModel", "CompanyModel");
            $this->load->model("CompanyGroupModel");

            $this->datasend['DtCompany'] = $this->CompanyModel->GetDataActive();
            $this->datasend['DtCompanygroup'] = $this->CompanyGroupModel->GetDataActive();
            $this->datasend['DtCurrency'] = $this->PermissionModel->BMCodeDetail("000001");
            if ($_GET['type'] == 'edit') {
                if (!isset($_GET['id'])) {
                    echo '<script>
                          alert("ID Not Found !!!");
                          window.location.href = window.location.href.split("?")[0];
                          </script>';
                }
                $this->load->model("MasterData/General/BankModel", "BankModel");
                $this->datasend['DtBank'] = $this->BankModel->GetData($_GET['id']);
            }
        }
        $this->datasend['sidebar'] = 'sidebar_view';
        $this->datasend['content'] = 'MasterData/General/MstBank';
        $this->load->view('template', $this->datasend);
    }

    public function RegionalGroup() {
        $this->datasend['formid'] = '19';
        $this->datasend['ACCESS'] = $this->PermissionModel->GetAccessMenu($this->datasend['ROLEACCESS'], $this->datasend['formid']);
        if ($this->datasend['ACCESS']['VIEWS'] != 1) {
            redirect('/');
        }
        $this->datasend['DtRegionalGroup'] = [];
        if (isset($_GET['type'])) {
            if ($_GET['type'] == 'edit') {
                if (!isset($_GET['id'])) {
                    echo '<script>
                          alert("ID Not Found !!!");
                          window.location.href = window.location.href.split("?")[0];
                          </script>';
                }
                $this->load->model("MasterData/General/RegionalGroupModel", "RegionalGroupModel");
                $this->datasend['DtRegionalGroup'] = $this->RegionalGroupModel->GetData($_GET['id']);
            }
        }
        $this->datasend['sidebar'] = 'sidebar_view';
        $this->datasend['content'] = 'MasterData/General/RegionalGroup';
        $this->load->view('template', $this->datasend);
    }

    public function Regional() {
        $this->datasend['formid'] = '18';
        $this->datasend['ACCESS'] = $this->PermissionModel->GetAccessMenu($this->datasend['ROLEACCESS'], $this->datasend['formid']);
        if ($this->datasend['ACCESS']['VIEWS'] != 1) {
            redirect('/');
        }
        $this->datasend['DtRegional'] = [];
        if (isset($_GET['type'])) {
            $this->load->model("MasterData/General/RegionalGroupModel", "RegionalGroupModel");
            $this->datasend['DtRegionalGroup'] = $this->RegionalGroupModel->GetDataActive();
            if ($_GET['type'] == 'edit') {
                if (!isset($_GET['id'])) {
                    echo '<script>
                          alert("ID Not Found !!!");
                          window.location.href = window.location.href.split("?")[0];
                          </script>';
                }
                $this->load->model("MasterData/General/RegionalModel", "RegionalModel");
                $this->datasend['DtRegional'] = $this->RegionalModel->GetData($_GET['id']);
            }
        }
        $this->datasend['sidebar'] = 'sidebar_view';
        $this->datasend['content'] = 'MasterData/General/Regional';
        $this->load->view('template', $this->datasend);
    }

    public function MstBusinessUnit() {
        $this->datasend['formid'] = '12';
        $this->datasend['ACCESS'] = $this->PermissionModel->GetAccessMenu($this->datasend['ROLEACCESS'], $this->datasend['formid']);
        if ($this->datasend['ACCESS']['VIEWS'] != 1) {
            redirect('/');
        }
        $this->datasend['DtBusiness'] = [];
        if (isset($_GET['type'])) {
            $this->load->model("MasterData/General/CompanyModel", "CompanyModel");
            $this->load->model('CompanyGroupModel');
            $this->load->model('CompanySubGroupModel');
            $this->load->model("MasterData/General/RegionalModel", "RegionalModel");
            $this->load->model("MasterData/General/RegionalGroupModel", "RegionalGroupModel");

            $this->datasend['company'] = $this->CompanyModel->GetDataActive();
            $this->datasend['companygroup'] = $this->CompanyGroupModel->GetDataActive();
            $this->datasend['companysubgroup'] = $this->CompanySubGroupModel->GetDataActive();
            $this->datasend['regional'] = $this->RegionalModel->GetDataActive();
            $this->datasend['regionalgroup'] = $this->RegionalGroupModel->GetDataActive();

            if ($_GET['type'] == 'edit') {
                if (!isset($_GET['id'])) {
                    echo '<script>
                          alert("ID Not Found !!!");
                          window.location.href = window.location.href.split("?")[0];
                          </script>';
                }
                $this->load->model('BusinessUnitModel');
                $this->datasend['DtBusiness'] = $this->BusinessUnitModel->GetData($_GET['id']);
            }
        }
        $this->datasend['sidebar'] = 'sidebar_view';
        $this->datasend['content'] = 'MasterData/MstBusinessUnit';
        $this->load->view('template', $this->datasend);
    }

    public function MstVendor() {
        $this->datasend['formid'] = '30';
        $this->datasend['ACCESS'] = $this->PermissionModel->GetAccessMenu($this->datasend['ROLEACCESS'], $this->datasend['formid']);
        if ($this->datasend['ACCESS']['VIEWS'] != 1) {
            redirect('/');
        }
        $this->datasend['DtVendor'] = [];
        if (isset($_GET['type'])) {
            $this->load->model("MasterData/General/CompanyModel", "CompanyModel");
//            $this->load->model("MasterData/General/BusinessUnitModel", "BusinessUnitModel");
            $this->load->model(['BusinessUnitModel']);

            if ($_GET['type'] == 'edit') {
                if (!isset($_GET['id'])) {
                    echo '<script>
                          alert("ID Not Found !!!");
                          window.location.href = window.location.href.split("?")[0];
                          </script>';
                }
                $this->load->model('VendorModel');
                $this->datasend['DtVendor'] = $this->VendorModel->GetData($_GET['id']);
            }

            $this->datasend['company'] = $this->CompanyModel->GetDataActive();
            $this->datasend['businessunit'] = $this->BusinessUnitModel->GetDataActive();
        }
        $this->load->model('ExtSystemModel');
        $this->datasend['DtExtSystem'] = $this->ExtSystemModel->GetDataActive();
        $this->datasend['sidebar'] = 'sidebar_view';
        $this->datasend['content'] = 'MasterData/MstVendor';
        $this->load->view('template', $this->datasend);
    }

    public function MstForecastCat() {
        $this->datasend['formid'] = '32';
        $this->datasend['ACCESS'] = $this->PermissionModel->GetAccessMenu($this->datasend['ROLEACCESS'], $this->datasend['formid']);
        if ($this->datasend['ACCESS']['VIEWS'] != 1) {
            redirect('/');
        }
        $this->datasend['DtForecastCat'] = [];
        if (isset($_GET['type'])) {
            if ($_GET['type'] == 'edit') {
                if (!isset($_GET['id'])) {
                    echo '<script>
                          alert("ID Not Found !!!");
                          window.location.href = window.location.href.split("?")[0];
                          </script>';
                }
                $this->load->model('ForecastCatModel');
                $this->datasend['DtForecastCat'] = $this->ForecastCatModel->GetData($_GET['id']);
            }
        }
        $this->datasend['sidebar'] = 'sidebar_view';
        $this->datasend['content'] = 'MasterData/MstForecastCat';
        $this->load->view('template', $this->datasend);
    }

//  --- General End (Master Data) ---
//  --- Material Start (Master Data) ---
    public function MstMaterial() {
        $this->datasend['formid'] = '13';
        $this->datasend['ACCESS'] = $this->PermissionModel->GetAccessMenu($this->datasend['ROLEACCESS'], $this->datasend['formid']);
        if ($this->datasend['ACCESS']['VIEWS'] != 1) {
            redirect('/');
        }
        $this->datasend['DtMaterial'] = [];
        $this->load->model('ExtSystemModel');
        $this->datasend['DtExtSystem'] = $this->ExtSystemModel->GetDataActive();
        if (isset($_GET['type'])) {
            if ($_GET['type'] == 'edit') {
                if (!isset($_GET['id'])) {
                    echo '<script>
                          alert("ID Not Found !!!");
                          window.location.href = window.location.href.split("?")[0];
                          </script>';
                }
                $this->load->model('MaterialModel');
                $this->datasend['DtMaterial'] = $this->MaterialModel->GetData($_GET['id']);
            }
        }
        $this->datasend['sidebar'] = 'sidebar_view';
        $this->datasend['content'] = 'MasterData/Material/MstMaterial';
        $this->load->view('template', $this->datasend);
    }

    public function MstMaterialSubGroup() {
        $this->datasend['formid'] = '14';
        $this->datasend['ACCESS'] = $this->PermissionModel->GetAccessMenu($this->datasend['ROLEACCESS'], $this->datasend['formid']);
        if ($this->datasend['ACCESS']['VIEWS'] != 1) {
            redirect('/');
        }
        $this->datasend['DtMaterialSub'] = [];
        if (isset($_GET['type'])) {
            if ($_GET['type'] == 'edit') {
                if (!isset($_GET['id'])) {
                    echo '<script>
                          alert("ID Not Found !!!");
                          window.location.href = window.location.href.split("?")[0];
                          </script>';
                }
                $this->load->model('MaterialSubGroupModel');
                $this->datasend['DtMaterialSub'] = $this->MaterialSubGroupModel->GetData($_GET['id']);
            }
        }
        $this->datasend['sidebar'] = 'sidebar_view';
        $this->datasend['content'] = 'MasterData/Material/MstMaterialSubGroup';
        $this->load->view('template', $this->datasend);
    }

    public function MaterialGroup() {
        $this->datasend['formid'] = '15';
        $this->datasend['ACCESS'] = $this->PermissionModel->GetAccessMenu($this->datasend['ROLEACCESS'], $this->datasend['formid']);
        if ($this->datasend['ACCESS']['VIEWS'] != 1) {
            redirect('/');
        }
        $this->datasend['DtMaterialGroup'] = [];
        if (isset($_GET['type'])) {
            $this->load->model(['MaterialSubGroupModel', 'MaterialGroupModel', 'ForecastCatModel']);
            $this->datasend['DtMaterialSub'] = $this->MaterialSubGroupModel->GetDataActive();
            if ($_GET['type'] == 'edit') {
                if (!isset($_GET['id'])) {
                    echo '<script>
                          alert("ID Not Found !!!");
                          window.location.href = window.location.href.split("?")[0];
                          </script>';
                }
                $this->datasend['DtMaterialGroup'] = $this->MaterialGroupModel->GetData($_GET['id']);
            }
            $this->datasend['forecast'] = $this->ForecastCatModel->GetDataActive();
        }
        $this->datasend['sidebar'] = 'sidebar_view';
        $this->datasend['content'] = 'MasterData/Material/MaterialGroup';
        $this->load->view('template', $this->datasend);
    }

    public function ItemUpload() {
        $this->datasend['formid'] = '36';
        $this->datasend['ACCESS'] = $this->PermissionModel->GetAccessMenu($this->datasend['ROLEACCESS'], $this->datasend['formid']);
        if ($this->datasend['ACCESS']['VIEWS'] != 1) {
            redirect('/');
        }
        $this->load->model('ExtSystemModel');
        $this->datasend['DtExtSystem'] = $this->ExtSystemModel->GetDataActive();
        $this->datasend['UUID'] = $this->uuid->v4();
        $this->datasend['sidebar'] = 'sidebar_view';
        $this->datasend['content'] = 'MasterData/Material/MaterialUpload';
        $this->load->view('template', $this->datasend);
    }

//  --- Material End (Master Data) ---
//  --- Master Data End ----
//
//  --- Setting Start ----
    public function ExtSystem() {
        $this->datasend['formid'] = '5';
        $this->datasend['ACCESS'] = $this->PermissionModel->GetAccessMenu($this->datasend['ROLEACCESS'], $this->datasend['formid']);
        if ($this->datasend['ACCESS']['VIEWS'] != 1) {
            redirect('/');
        }
        $this->datasend['DtSystem'] = [];
        if (isset($_GET['type'])) {
            if ($_GET['type'] == 'edit') {
                if (!isset($_GET['id'])) {
                    echo '<script>
                          alert("ID Not Found !!!");
                          window.location.href = window.location.href.split("?")[0];
                          </script>';
                }
                $this->load->model('ExtSystemModel');
                $this->datasend['DtSystem'] = $this->ExtSystemModel->GetData($_GET['id']);
            }
        }
        $this->datasend['sidebar'] = 'sidebar_view';
//        $this->datasend['content_js'] = 'applicationmaster/extsystem/extsystem_js';
        $this->datasend['content'] = 'Setting/ExtSystem';
        $this->load->view('template', $this->datasend);
    }

    public function DocTemplate() {
        $this->datasend['formid'] = '10';
        $this->datasend['ACCESS'] = $this->PermissionModel->GetAccessMenu($this->datasend['ROLEACCESS'], $this->datasend['formid']);
        if ($this->datasend['ACCESS']['VIEWS'] != 1) {
            redirect('/');
        }
        $this->datasend['DtTemplate'] = [];
        if (isset($_GET['type'])) {
            if ($_GET['type'] == 'edit') {
                if (!isset($_GET['id'])) {
                    echo '<script>
                          alert("ID Not Found !!!");
                          window.location.href = window.location.href.split("?")[0];
                          </script>';
                }
                $this->load->model('DocTemplateModel');
                $this->datasend['DtTemplate'] = $this->DocTemplateModel->GetData($_GET['id']);
            }
        }
        $this->datasend['sidebar'] = 'sidebar_view';
        $this->datasend['content'] = 'Setting/DocTemplate';
        $this->load->view('template', $this->datasend);
    }

    public function DocType() {
        $this->datasend['formid'] = '11';
        $this->datasend['ACCESS'] = $this->PermissionModel->GetAccessMenu($this->datasend['ROLEACCESS'], $this->datasend['formid']);
        if ($this->datasend['ACCESS']['VIEWS'] != 1) {
            redirect('/');
        }
        $this->datasend['DtDocType'] = [];
        if (isset($_GET['type'])) {
            if ($_GET['type'] == 'edit') {
                if (!isset($_GET['id'])) {
                    echo '<script>
                          alert("ID Not Found !!!");
                          window.location.href = window.location.href.split("?")[0];
                          </script>';
                }
                $this->load->model('DocTypeModel');
                $this->datasend['DtDocType'] = $this->DocTypeModel->GetData($_GET['id']);
            }
        }
        $this->datasend['sidebar'] = 'sidebar_view';
        $this->datasend['content'] = 'Setting/DocType';
        $this->load->view('template', $this->datasend);
    }

    public function Week() {
        $this->datasend['formid'] = '33';
        $this->datasend['ACCESS'] = $this->PermissionModel->GetAccessMenu($this->datasend['ROLEACCESS'], $this->datasend['formid']);
        if ($this->datasend['ACCESS']['VIEWS'] != 1) {
            redirect('/');
        }
        $this->datasend['DtWeek'] = [];
        $this->load->model('WeekModel');
        $this->datasend['DtYear'] = $this->WeekModel->GetYear();
        $this->datasend['sidebar'] = 'sidebar_view';
        $this->datasend['content'] = 'Setting/Week';
        $this->load->view('template', $this->datasend);
    }

    public function Kurs() {
        $this->datasend['formid'] = '56';
        $this->datasend['ACCESS'] = $this->PermissionModel->GetAccessMenu($this->datasend['ROLEACCESS'], $this->datasend['formid']);
        if ($this->datasend['ACCESS']['VIEWS'] != 1) {
            redirect('/');
        }
        $this->datasend['DtKurs'] = [];
        $this->datasend['sidebar'] = 'sidebar_view';
        $this->datasend['content'] = 'Setting/Curs';
        $this->load->view('template', $this->datasend);
    }

    public function Holiday() {
        $this->datasend['formid'] = '108';
        $this->datasend['ACCESS'] = $this->PermissionModel->GetAccessMenu($this->datasend['ROLEACCESS'], $this->datasend['formid']);
        if ($this->datasend['ACCESS']['VIEWS'] != 1) {
            redirect('/');
        }
        $this->datasend['DtKurs'] = [];
        $this->datasend['sidebar'] = 'sidebar_view';
        $this->datasend['content'] = 'Setting/Holiday';
        $this->load->view('template', $this->datasend);
    }

//  --- Setting End ----
//  
//  --- Cashflow Start ---- 
    public function ForecastReport() {
        $this->datasend['DtCash'] = [];
        $this->datasend['formid'] = '8';

        $this->datasend['ACCESS'] = $this->PermissionModel->GetAccessMenu($this->datasend['ROLEACCESS'], $this->datasend['formid']);
        if ($this->datasend['ACCESS']['VIEWS'] != 1) {
            redirect('/');
        }

        $this->datasend['sidebar'] = 'sidebar_view';
        $this->datasend['content'] = 'Report/allreport_view';
        $this->load->view('template', $this->datasend);
    }

    public function YearlyForecast() {
        $this->datasend['DtCash'] = [];
        $this->datasend['formid'] = '8';


        $this->datasend['ACCESS'] = $this->PermissionModel->GetAccessMenu($this->datasend['ROLEACCESS'], $this->datasend['formid']);
        if ($this->datasend['ACCESS']['VIEWS'] != 1) {
            redirect('/');
        }

        $this->datasend['sidebar'] = 'sidebar_view';
        $this->datasend['content'] = 'Report/yearly_forecast';
        $this->load->view('templatefull', $this->datasend);
    }

    public function MonthlyForecast() {
        $this->datasend['DtCash'] = [];
        $this->datasend['formid'] = '8';
        $this->datasend['ACCESS'] = $this->PermissionModel->GetAccessMenu($this->datasend['ROLEACCESS'], $this->datasend['formid']);
        if ($this->datasend['ACCESS']['VIEWS'] != 1) {
            redirect('/');
        }
        $this->load->model("MasterData/General/CompanyModel", "CompanyModel");
        $this->datasend['DtCompany'] = $this->CompanyModel->GetDataActive();
        $this->datasend['sidebar'] = 'sidebar_view';
        $this->datasend['content'] = 'Report/monthly_forecast';
        $this->load->view('templatefull', $this->datasend);
    }

    public function MonthlyForecast_dev() {
        $this->datasend['DtCash'] = [];
        $this->datasend['formid'] = '8';
        $this->datasend['ACCESS'] = $this->PermissionModel->GetAccessMenu($this->datasend['ROLEACCESS'], $this->datasend['formid']);
        if ($this->datasend['ACCESS']['VIEWS'] != 1) {
            redirect('/');
        }
        $this->load->model("MasterData/General/CompanyModel", "CompanyModel");
        $this->datasend['DtCompany'] = $this->CompanyModel->GetDataActive();
        $this->datasend['sidebar'] = 'sidebar_view';
        $this->datasend['content'] = 'Report/monthly_forecast_dev';
        $this->load->view('templatefull', $this->datasend);
    }

    public function LeasingReport() {
        // $this->datasend['DtCash'] = [];
        $this->datasend['formid'] = '100';
        $this->datasend['ACCESS'] = $this->PermissionModel->GetAccessMenu($this->datasend['ROLEACCESS'], $this->datasend['formid']);
        if ($this->datasend['ACCESS']['VIEWS'] != 1) {
            redirect('/');
        }
        $this->load->model("MasterData/General/CompanyModel", "CompanyModel");
        $this->datasend['DtCompany'] = $this->CompanyModel->GetDataActive();
        $this->datasend['DtDepartment'] = $this->PermissionModel->GetDtDepart($this->datasend['SESSION']->FCCODE);

        $this->datasend['sidebar'] = 'sidebar_view';
        $this->datasend['content'] = 'leasing/leasingreport';
        $this->load->view('template', $this->datasend);
    }

    /*
      public function CashOut() {
      $this->datasend['DtCash'] = [];
      $this->datasend['formid'] = '16';

      $this->datasend['ACCESS'] = $this->PermissionModel->GetAccessMenu($this->datasend['ROLEACCESS'], $this->datasend['formid']);
      if ($this->datasend['ACCESS']['VIEWS'] != 1) {
      redirect('/');
      }

      $this->datasend['sidebar'] = 'sidebar_view';
      $this->datasend['content'] = 'Cashflow/Cash';
      $this->load->view('template', $this->datasend);
      }
     */

    public function EntryDoc() {
        $this->datasend['DtCash'] = [];
        $this->datasend['formid'] = '25';

        $this->datasend['ACCESS'] = $this->PermissionModel->GetAccessMenu($this->datasend['ROLEACCESS'], $this->datasend['formid']);
        if ($this->datasend['ACCESS']['VIEWS'] != 1) {
            redirect('/');
        }

        $this->load->model("MasterData/General/CompanyModel", "CompanyModel");
        $this->load->model('BusinessUnitModel');
        $this->load->model("MasterData/General/DepartementModel", "DepartementModel");
        $this->load->model('DocTypeModel');
        $this->load->model('MaterialModel');

        $this->datasend['company'] = $this->CompanyModel->GetDataActive();
        $this->datasend['businessunit'] = $this->BusinessUnitModel->GetDataActive();
        $this->datasend['departement'] = $this->DepartementModel->GetDataActive();
        $this->datasend['doctype'] = $this->DocTypeModel->GetDataActive();
        $this->datasend['material'] = $this->MaterialModel->GetDataActive();

        $this->datasend['sidebar'] = 'sidebar_view';
        $this->datasend['content'] = 'Cashflow/EntryDoc';
        $this->load->view('template', $this->datasend);
    }

    public function UploadDoc() {
        $this->datasend['formid'] = '17';
        $this->datasend['ACCESS'] = $this->PermissionModel->GetAccessMenu($this->datasend['ROLEACCESS'], $this->datasend['formid']);
        if ($this->datasend['ACCESS']['VIEWS'] != 1) {
            redirect('/');
        }
        $this->load->model('ExtSystemModel');
        $this->datasend['DtExtSystem'] = $this->ExtSystemModel->GetDataActive();
        $this->load->model('DocTypeModel');
        $this->datasend['DtDocType'] = $this->DocTypeModel->GetDataActive();
        $this->datasend['UUID'] = $this->uuid->v4();
        $this->datasend['sidebar'] = 'sidebar_view';
        $this->datasend['content'] = 'Cashflow/UploadDoc';
        $this->load->view('template', $this->datasend);
    }

    public function Upload_PO() {
        $this->datasend['formid'] = '27';
        $this->datasend['ACCESS'] = $this->PermissionModel->GetAccessMenu($this->datasend['ROLEACCESS'], $this->datasend['formid']);
        if ($this->datasend['ACCESS']['VIEWS'] != 1) {
            redirect('/');
        }
        $this->load->model('ExtSystemModel');
        $this->datasend['DtExtSystem'] = $this->ExtSystemModel->GetDataActive();
        $this->load->model('DocTypeModel');
        $this->datasend['DtDocType'] = $this->DocTypeModel->GetDataActive();
        $this->datasend['UUID'] = $this->uuid->v4();
        $this->datasend['sidebar'] = 'sidebar_view';
        $this->datasend['content'] = 'Cashflow/PO/Upload_PO';
        $this->load->view('template', $this->datasend);
    }

    public function Upload_POSAP() {
        $this->datasend['formid'] = '31';
        $this->datasend['ACCESS'] = $this->PermissionModel->GetAccessMenu($this->datasend['ROLEACCESS'], $this->datasend['formid']);
        if ($this->datasend['ACCESS']['VIEWS'] != 1) {
            redirect('/');
        }
        $this->datasend['DtDepart'] = $this->PermissionModel->GetDtDepart($this->datasend['SESSION']->FCCODE);
        $this->datasend['UUID'] = $this->uuid->v4();
        $this->datasend['sidebar'] = 'sidebar_view';
        $this->datasend['content'] = 'Cashflow/UploadPO5';
//        $this->datasend['content'] = 'Cashflow/PO/Upload_POSAP';
        $this->load->view('template', $this->datasend);
    }

    public function Upload_POSAPHana()
    {
        $this->datasend['formid'] = '64';
        $this->datasend['ACCESS'] = $this->PermissionModel->GetAccessMenu($this->datasend['ROLEACCESS'], $this->datasend['formid']);
        if ($this->datasend['ACCESS']['VIEWS'] != 1) {
            redirect('/');
        }
        $this->datasend['DtDepart'] = $this->PermissionModel->GetDtDepart($this->datasend['SESSION']->FCCODE);
        $this->datasend['UUID'] = $this->uuid->v4();
        $this->datasend['sidebar'] = 'sidebar_view';
        $this->datasend['content'] = 'Cashflow/UploadPO5Hana'; //'Cashflow/PO/Upload_POSAP';
        $this->load->view('template', $this->datasend);
    }

    public function EntryData_PO() {
        $this->datasend['formid'] = '28';
        $this->datasend['ACCESS'] = $this->PermissionModel->GetAccessMenu($this->datasend['ROLEACCESS'], $this->datasend['formid']);
        if ($this->datasend['ACCESS']['VIEWS'] != 1) {
            redirect('/');
        }

        $this->datasend['PODetails'] = [];
        $this->datasend['EntryPOs'] = [];
        $this->datasend['DtInvoice'] = [];
        $this->datasend['departement'] = $this->PermissionModel->GetDtDepart($this->datasend['SESSION']->FCCODE);
        // print_r($this->datasend['departement'][0]->DEPARTMENT);exit;

        if (isset($_GET['type'])) {
            $this->load->model("MasterData/General/CompanyModel", "CompanyModel");
            $this->load->model("MasterData/General/DepartementModel", "DepartementModel");
            $this->load->model(['BusinessUnitModel', 'EntryPOModel', 'MaterialModel', 'DocTypeModel']);
            $this->load->model('ExtSystemModel');

            $this->datasend['company'] = $this->CompanyModel->GetDataActive();
            // $this->datasend['businessunit'] = $this->BusinessUnitModel->GetDataActive();
            $this->datasend['DtExtSystem'] = $this->ExtSystemModel->GetDataActive();

            if ($_GET['type'] == 'edit') {
                if (!isset($_GET['id'])) {
                    echo '<script>
                          alert("ID Not Found !!!");
                          window.location.href = window.location.href.split("?")[0];
                          </script>';
                }

                $this->datasend['DtInvoice'] = $this->EntryPOModel->GetDataInvoice($_GET['id']);
                $this->datasend['PODetails'] = $this->EntryPOModel->GetPODetails($_GET['id']);
                $this->datasend['EntryPOs'] = $this->EntryPOModel->GetData($_GET['id']);
            }
            $this->datasend['DtDocType'] = $this->DocTypeModel->GetDataActive();
        }
        
        $this->datasend['sidebar'] = 'sidebar_view';
        $this->datasend['content'] = 'Cashflow/PO/EntryData_PO';
        $this->load->view('template', $this->datasend);
    }

    public function TempCash() {
        $this->datasend['formid'] = '29';
        $this->datasend['ACCESS'] = $this->PermissionModel->GetAccessMenu($this->datasend['ROLEACCESS'], $this->datasend['formid']);
        if ($this->datasend['ACCESS']['VIEWS'] != 1) {
            redirect('/');
        }
        $this->datasend['DtTempCash'] = [];
        if (isset($_GET['type'])) {
            $this->load->model(['TempCashModel']);

            if ($_GET['type'] == 'edit') {
                if (!isset($_GET['id'])) {
                    echo '<script>
                          alert("ID Not Found !!!");
                          window.location.href = window.location.href.split("?")[0];
                          </script>';
                }
                $this->datasend['DtTempCash'] = $this->TempCashModel->GetData($_GET['id']);
            }

            $this->datasend['tempCashParent'] = $this->TempCashModel->ShowParent();
            // print_r($this->datasend['tempCashParent']);exit;
        }
        $this->datasend['sidebar'] = 'sidebar_view';
        $this->datasend['content'] = 'Cashflow/TempCash';
        $this->load->view('template', $this->datasend);
    }

    public function Forecast() {
        $this->datasend['formid'] = '24';
        $this->datasend['ACCESS'] = $this->PermissionModel->GetAccessMenu($this->datasend['ROLEACCESS'], $this->datasend['formid']);
        if ($this->datasend['ACCESS']['VIEWS'] != 1) {
            redirect('/');
        }
        $this->datasend['DtForecast'] = [];
        $this->datasend['DtDetail'] = [];
        if (isset($_GET['type'])) {
            if ($_GET['type'] == 'edit') {
                if (!isset($_GET['id'])) {
                    echo '<script>
                          alert("ID Not Found !!!");
                          window.location.href = window.location.href.split("?")[0];
                          </script>';
                }
                $this->load->model("Cashflow/ForecastModel", "ForecastModel");
                $this->datasend['DtForecast'] = $this->ForecastModel->GetData($_GET['id']);
                $this->datasend['DtDetail'] = $this->ForecastModel->GetDetail($_GET['id']);
            }
        }
        $this->datasend['sidebar'] = 'sidebar_view';
        $this->datasend['content'] = 'Cashflow/Forecast';
        $this->load->view('template', $this->datasend);
    }

    public function ForecastAuth() {
        $this->datasend['formid'] = '51';
        $this->datasend['ACCESS'] = $this->PermissionModel->GetAccessMenu($this->datasend['ROLEACCESS'], $this->datasend['formid']);
        if ($this->datasend['ACCESS']['VIEWS'] != 1) {
            redirect('/');
        }
        $this->datasend['DtForecastAuth'] = [];

        if (isset($_GET['type'])) {
            if ($_GET['type'] == 'edit') {
                if (!isset($_GET['id'])) {
                    echo '<script>
                          alert("ID Not Found !!!");
                          window.location.href = window.location.href.split("?")[0];
                          </script>';
                }
                // $this->load->model("Cashflow/ForecastAuthModel", "ForecastAuthModel");
                // $this->datasend['DtForecastAuth'] = $this->ForecastModel->GetData($_GET['id']);
            }
        }
        $this->datasend['sidebar'] = 'sidebar_view';
        $this->datasend['content'] = 'Cashflow/ForecastAuth';
        $this->load->view('template', $this->datasend);
    }

    public function ForecastVersiB() {
        $this->datasend['formid'] = '39';
        $this->datasend['ACCESS'] = $this->PermissionModel->GetAccessMenu($this->datasend['ROLEACCESS'], $this->datasend['formid']);
        if ($this->datasend['ACCESS']['VIEWS'] != 1) {
            redirect('/');
        }
        $this->datasend['DtDepartment'] = $this->PermissionModel->GetDtDepart($this->datasend['SESSION']->FCCODE);

        $this->datasend['sidebar'] = 'sidebar_view';
        $this->datasend['content'] = 'Cashflow/Forecast/ForecastVersiB';
        $this->load->view('template', $this->datasend);
    }

    public function LogForecast() {
        $this->datasend['formid'] = '88';
        $this->datasend['ACCESS'] = $this->PermissionModel->GetAccessMenu($this->datasend['ROLEACCESS'], $this->datasend['formid']);
        if ($this->datasend['ACCESS']['VIEWS'] != 1) {
            redirect('/');
        }
        // $this->datasend['DtLeasing'] = [];
        $this->datasend['Menu'] = $this->PermissionModel->GetMenu($this->datasend['ROLEACCESS']);
        $this->load->model('MasterData/General/CompanyModel');
        $this->datasend['DtCompany'] = $this->CompanyModel->GetDataActive();
        $this->datasend['DtDepartment'] = $this->PermissionModel->GetDtDepart($this->datasend['SESSION']->FCCODE);
        $this->datasend['DtCurrency'] = $this->PermissionModel->BMCodeDetail('000001');

        $this->datasend['sidebar'] = 'sidebar_view';
        $this->datasend['content'] = 'Cashflow/Forecast/LogForecast';
        $this->load->view('template', $this->datasend);
    }

	public function cashFlow() {
		$this->apivar = 'login';
        $this->datasend['formid'] = '54';
        $this->datasend['ACCESS'] = $this->PermissionModel->GetAccessMenu($this->datasend['ROLEACCESS'], $this->datasend['formid']);
        $this->load->model("MasterData/General/CompanyModel", "CompanyModel");

        if ($this->datasend['ACCESS']['VIEWS'] != 1) {
            redirect('/');
        }

        $this->datasend['DtCompany'] = $this->CompanyModel->GetDataActive();
        $this->datasend['DtCashflow'] = [];

        $this->datasend['sidebar'] = 'sidebar_view';
        $this->datasend['content'] = 'Cashflow/Cashflow';
        $this->load->view('template', $this->datasend);
    }
	
    public function Invoice() {
        $this->datasend['formid'] = '35';
        $this->datasend['ACCESS'] = $this->PermissionModel->GetAccessMenu($this->datasend['ROLEACCESS'], $this->datasend['formid']);
        if ($this->datasend['ACCESS']['VIEWS'] != 1) {
            redirect('/');
        }
        $this->datasend['DtInvoice'] = [];
        $this->datasend['DtDetail'] = [];
        if (isset($_GET['type'])) {
            if ($_GET['type'] == 'edit') {
                if (!isset($_GET['id'])) {
                    echo '<script>
                          alert("ID Not Found !!!");
                          window.location.href = window.location.href.split("?")[0];
                          </script>';
                }
                $this->load->model("Cashflow/ForecastModel", "ForecastModel");
                $this->datasend['DtInvoice'] = $this->ForecastModel->GetDataInv($_GET['id']);
                $this->datasend['DtDetail'] = $this->ForecastModel->GetDetailInv($_GET['id'], $this->datasend['DtInvoice']->CFTRANSID);
            }
        }
        $this->datasend['sidebar'] = 'sidebar_view';
        $this->datasend['content'] = 'Cashflow/Invoice';
        $this->load->view('template', $this->datasend);
    }

    public function Payment() {
        $this->datasend['formid'] = '37';
        $this->datasend['ACCESS'] = $this->PermissionModel->GetAccessMenu($this->datasend['ROLEACCESS'], $this->datasend['formid']);
        if ($this->datasend['ACCESS']['VIEWS'] != 1) {
            redirect('/');
        }


        $this->load->model("MasterData/General/DepartementModel", "DepartementModel");
        // $this->load->model("MasterData/General/BankModel", "BankModel");

        $this->datasend['departement'] = $this->PermissionModel->GetDtDepart($this->datasend['SESSION']->FCCODE);
        // $this->datasend['bank'] = $this->BankModel->GetDataActive();
        $this->datasend['DtInvoice'] = [];

        if (isset($_GET['type'])) {
            if ($_GET['type'] == 'edit') {
                if (!isset($_GET['id'])) {
                    echo '<script>
                          alert("ID Not Found !!!");
                          window.location.href = window.location.href.split("?")[0];
                          </script>';
                }
                // $this->load->model("Cashflow/PaymentModel", "PaymentModel");
                // $this->datasend['DtForecast'] = $this->PaymentModel->GetData($_GET['id']);
            }
        }
        $this->datasend['sidebar'] = 'sidebar_view';
        $this->datasend['content'] = 'Cashflow/PaymentAccepted';
        $this->load->view('template', $this->datasend);
    }

    public function otherVoucher() {
        $this->datasend['formid'] = '55';
        $this->datasend['ACCESS'] = $this->PermissionModel->GetAccessMenu($this->datasend['ROLEACCESS'], $this->datasend['formid']);
        $this->load->model("MasterData/General/DepartementModel", "DepartementModel");
        $this->load->model("MasterData/General/CompanyModel", "CompanyModel");
        $this->load->model('ExtSystemModel');
        
        if ($this->datasend['ACCESS']['VIEWS'] != 1) {
            redirect('/');
        }

        // if($this->session->userdata('username') != 'ERPKPN'){
        //    redirect('/');
        // }

        $this->datasend['DtOtherVoucher'] = [];
        $this->datasend['departement'] = $this->PermissionModel->GetDtDepart($this->datasend['SESSION']->FCCODE);
        $this->datasend['DtExtSystem'] = $this->ExtSystemModel->GetDataActive();
        $this->datasend['DtCompany'] = $this->CompanyModel->GetDataActive();

        $this->datasend['sidebar'] = 'sidebar_view';
        $this->datasend['content'] = 'Cashflow/OtherVoucher';
        $this->load->view('template', $this->datasend);
    }

    public function DepartBudget() {
        $this->datasend['formid'] = '40';
        $this->datasend['ACCESS'] = $this->PermissionModel->GetAccessMenu($this->datasend['ROLEACCESS'], $this->datasend['formid']);
        if ($this->datasend['ACCESS']['VIEWS'] != 1) {
            redirect('/');
        }

        $this->datasend['DtForecast'] = [];

        $this->datasend['sidebar'] = 'sidebar_view';
        $this->datasend['content'] = 'Cashflow/DepartBudget';
        $this->load->view('template', $this->datasend);
    }

    public function ReportFtP() {
        $this->datasend['formid'] = '52';
        $this->datasend['ACCESS'] = $this->PermissionModel->GetAccessMenu($this->datasend['ROLEACCESS'], $this->datasend['formid']);
        $this->load->model("MasterData/General/DepartementModel", "DepartementModel");

        if ($this->datasend['ACCESS']['VIEWS'] != 1) {
            redirect('/');
        }
        $this->load->model("MasterData/General/CompanyModel", "CompanyModel");
        $this->load->model("CompanyGroupModel", "CompanyGroupModel");
        $this->load->model("CompanySubGroupModel", "CompanySubGroupModel");

        $this->datasend['DtCompany'] = $this->CompanyModel->GetDataActive();
        $this->datasend['Dtcompanygroup'] = $this->CompanyGroupModel->GetDataActive();
        $this->datasend['Dtcompanysubgroup'] = $this->CompanySubGroupModel->GetDataActive();

        $this->datasend['DtFtP'] = [];
        $this->datasend['departement'] = $this->PermissionModel->GetDtDepart($this->datasend['SESSION']->FCCODE);

        $this->datasend['sidebar'] = 'sidebar_view';
        $this->datasend['content'] = 'Cashflow/ReportFtP';
        $this->load->view('template', $this->datasend);
    }

   public function ReportBankBalance() {
        $this->datasend['formid'] = '53';
        $this->datasend['ACCESS'] = $this->PermissionModel->GetAccessMenu($this->datasend['ROLEACCESS'], $this->datasend['formid']);
        $this->load->model("MasterData/General/CompanyModel", "CompanyModel");
        $this->load->model("CompanyGroupModel", "CompanyGroupModel");
        $this->load->model("CompanySubGroupModel", "CompanySubGroupModel");

        if ($this->datasend['ACCESS']['VIEWS'] != 1) {
            redirect('/');
        }

        $this->datasend['DtCompany'] = $this->CompanyModel->GetDataActive();
        // echo '<pre>';
        // var_dump($this->datasend['DtCompany']);exit;
        $this->datasend['Dtcompanygroup'] = $this->CompanyGroupModel->GetDataActive();
        $this->datasend['Dtcompanysubgroup'] = $this->CompanySubGroupModel->GetDataActive();

        $this->datasend['sidebar'] = 'sidebar_view';
        $this->datasend['content'] = 'Report/ReportBankBalance';
        $this->load->view('template', $this->datasend);
    }
	
	public function ReportPayment() {
        $this->datasend['formid'] = '57';
        $this->datasend['ACCESS'] = $this->PermissionModel->GetAccessMenu($this->datasend['ROLEACCESS'], $this->datasend['formid']);
        $this->load->model("MasterData/General/DepartementModel", "DepartementModel");

        if ($this->datasend['ACCESS']['VIEWS'] != 1) {
            redirect('/');
        }

        $this->datasend['DtReportPayment'] = [];
        $this->datasend['departement'] = $this->PermissionModel->GetDtDepart($this->datasend['SESSION']->FCCODE);

        $this->datasend['sidebar'] = 'sidebar_view';
        $this->datasend['content'] = 'Report/ReportPayment';
        $this->load->view('template', $this->datasend);
    }
	
	public function IntercoLoans() {
        $this->datasend['formid'] = '59';
        $this->datasend['ACCESS'] = $this->PermissionModel->GetAccessMenu($this->datasend['ROLEACCESS'], $this->datasend['formid']);
        $this->load->model("MasterData/General/DepartementModel", "DepartementModel");
        $this->load->model("MasterData/General/CompanyModel", "CompanyModel");
        
        if ($this->datasend['ACCESS']['VIEWS'] != 1) {
            redirect('/');
        }

        $this->datasend['DtIntercoLoans'] = [];
        $this->datasend['departement'] = $this->PermissionModel->GetDtDepart($this->datasend['SESSION']->FCCODE);
        $this->datasend['DtCompany'] = $this->CompanyModel->GetDataActive();
        $this->datasend['DtCompanyAll'] = $this->CompanyModel->GetDataActiveAll();
		
        $this->datasend['sidebar'] = 'sidebar_view';
        $this->datasend['content'] = 'Cashflow/IntercoLoans';
        $this->load->view('template', $this->datasend);
    }
	
    public function TracingDocument(){
        $this->datasend['formid'] = '68';
        $this->datasend['ACCESS'] = $this->PermissionModel->GetAccessMenu($this->datasend['ROLEACCESS'], $this->datasend['formid']);

        $this->load->model('DocTypeModel');
        $this->datasend['DtDocType'] = $this->DocTypeModel->GetDataActive();     

        $this->load->model("MasterData/General/CompanyModel", "CompanyModel");
        $this->datasend['DtCompany'] = $this->CompanyModel->GetDataActive();

        $this->datasend['DtTracing'] = [];
        // $this->datasend['departement'] = $this->PermissionModel->GetDtDepart($this->datasend['SESSION']->FCCODE);

        $this->datasend['sidebar'] = 'sidebar_view';
        $this->datasend['content'] = 'Process/TracingDocument';
        $this->load->view('template', $this->datasend);
    }

    public function ReportLeasing() {
        $this->datasend['formid'] = '78';
        $this->datasend['ACCESS'] = $this->PermissionModel->GetAccessMenu($this->datasend['ROLEACCESS'], $this->datasend['formid']);
        if ($this->datasend['ACCESS']['VIEWS'] != 1) {
            redirect('/');
        }
        $this->datasend['DtDepartment'] = $this->PermissionModel->GetDtDepart($this->datasend['SESSION']->FCCODE);

        $this->datasend['sidebar'] = 'sidebar_view';
        $this->datasend['content'] = 'Report/ReportLeasing';
        $this->load->view('template', $this->datasend);
    }

    public function LeasingMaster() {
        $this->load->model("LeasingModel");
        $this->datasend['formid'] = '67';
        $this->datasend['ACCESS'] = $this->PermissionModel->GetAccessMenu($this->datasend['ROLEACCESS'], $this->datasend['formid']);
        $this->datasend['DtDepartment'] = $this->PermissionModel->GetDtDepart($this->datasend['SESSION']->FCCODE);
        $this->datasend['DtLeasing'] = [];
        if ($this->datasend['ACCESS']['VIEWS'] != 1) {
            redirect('/');
        }
        if (isset($_GET['type'])) {
            if ($_GET['type'] == 'edit') {
                if (!isset($_GET['UUID'])) {
                    echo '<script>
                             alert("ID Not Found !!!");
                             window.location.href = window.location.href.split("?")[0];
                          </script>';
                }
                $this->datasend['DtLeasing'] = $this->LeasingModel->GetData(["UUID" => $_GET['UUID']]);
                $this->datasend['DtUpload']  = $_GET['UUID'];
                // $this->datasend['DtUpload'] = $this->LeasingModel->ShowFileData(["UUID" => $_GET['UUID']]);
            }
            $this->load->model("MasterData/General/CompanyModel", "CompanyModel");
            $this->datasend['DtCompany'] = $this->CompanyModel->GetDataActive();
            $this->load->model(["ExtSystemModel", "DocTypeModel"]);
            $this->datasend['DtDocType'] = $this->DocTypeModel->GetDataActiveHead();
            $this->datasend['DtExtSystem'] = $this->ExtSystemModel->GetDataActive();
            $this->datasend['DtCurrency'] = $this->PermissionModel->BMCodeDetail("000001");
            $this->datasend['Menu'] = $this->PermissionModel->GetMenu($this->datasend['ROLEACCESS']);
        }

        $this->datasend['sidebar'] = 'sidebar_view';
        $this->datasend['content'] = 'leasing/leasingmaster';
        $this->load->view('template', $this->datasend);
    }

    public function Leasingtransaction() {
        $this->datasend['formid'] = '69';
        $this->datasend['ACCESS'] = $this->PermissionModel->GetAccessMenu($this->datasend['ROLEACCESS'], $this->datasend['formid']);
        if ($this->datasend['ACCESS']['VIEWS'] != 1) {
            redirect('/');
        }
        // $this->datasend['DtLeasing'] = [];
        $this->datasend['Menu'] = $this->PermissionModel->GetMenu($this->datasend['ROLEACCESS']);
        $this->load->model('MasterData/General/CompanyModel');
        $this->datasend['DtCompany'] = $this->CompanyModel->GetDataActive();
        $this->datasend['DtCurrency'] = $this->PermissionModel->BMCodeDetail('000001');

        $this->datasend['sidebar'] = 'sidebar_view';
        $this->datasend['content'] = 'leasing/leasingtransaction';
        $this->load->view('template', $this->datasend);
    }

    public function LeasingCompletion() {
        $this->datasend['formid'] = '70';
        $this->datasend['ACCESS'] = $this->PermissionModel->GetAccessMenu($this->datasend['ROLEACCESS'], $this->datasend['formid']);
        if ($this->datasend['ACCESS']['VIEWS'] != 1) {
            redirect('/');
        }
        // $this->datasend['DtLeasing'] = [];
        $this->datasend['Menu'] = $this->PermissionModel->GetMenu($this->datasend['ROLEACCESS']);
        $this->load->model('MasterData/General/CompanyModel');
        $this->datasend['DtCompany'] = $this->CompanyModel->GetDataActive();
        $this->datasend['DtDepartment'] = $this->PermissionModel->GetDtDepart($this->datasend['SESSION']->FCCODE);
        $this->datasend['DtCurrency'] = $this->PermissionModel->BMCodeDetail('000001');

        $this->datasend['sidebar'] = 'sidebar_view';
        $this->datasend['content'] = 'leasing/leasingcompletion';
        $this->load->view('template', $this->datasend);
    }

    public function LeasingPeriodControl() {
        $this->datasend['formid'] = '71';
        $this->datasend['ACCESS'] = $this->PermissionModel->GetAccessMenu($this->datasend['ROLEACCESS'], $this->datasend['formid']);
        if ($this->datasend['ACCESS']['VIEWS'] != 1) {
            redirect('/');
        }
        // $this->datasend['DtLeasing'] = [];
        $this->datasend['Menu'] = $this->PermissionModel->GetMenu($this->datasend['ROLEACCESS']);
        $this->load->model('MasterData/General/CompanyModel');
        $this->datasend['DtCompany'] = $this->CompanyModel->GetDataActive();
        $this->datasend['DtCurrency'] = $this->PermissionModel->BMCodeDetail('000001');

        $this->datasend['sidebar'] = 'sidebar_view';
        $this->datasend['content'] = 'leasing/leasingperiodcontrol';
        $this->load->view('template', $this->datasend);
    }

    public function DeleteLeasingTransaction() {
        $this->datasend['formid'] = '73';
        $this->datasend['ACCESS'] = $this->PermissionModel->GetAccessMenu($this->datasend['ROLEACCESS'], $this->datasend['formid']);
        if ($this->datasend['ACCESS']['VIEWS'] != 1) {
            redirect('/');
        }
        // $this->datasend['DtLeasing'] = [];
        $this->datasend['Menu'] = $this->PermissionModel->GetMenu($this->datasend['ROLEACCESS']);
        $this->load->model('MasterData/General/CompanyModel');
        $this->datasend['DtCompany'] = $this->CompanyModel->GetDataActive();
        $this->datasend['DtDepartment'] = $this->PermissionModel->GetDtDepart($this->datasend['SESSION']->FCCODE);
        $this->datasend['DtCurrency'] = $this->PermissionModel->BMCodeDetail('000001');

        $this->datasend['sidebar'] = 'sidebar_view';
        $this->datasend['content'] = 'leasing/deletetransaction';
        $this->load->view('template', $this->datasend);
    }

    public function DeleteForecast() {
        $this->datasend['formid'] = '72';
        $this->datasend['ACCESS'] = $this->PermissionModel->GetAccessMenu($this->datasend['ROLEACCESS'], $this->datasend['formid']);
        if ($this->datasend['ACCESS']['VIEWS'] != 1) {
            redirect('/');
        }
        // $this->datasend['DtLeasing'] = [];
        $this->datasend['Menu'] = $this->PermissionModel->GetMenu($this->datasend['ROLEACCESS']);
        $this->load->model('MasterData/General/CompanyModel');
        $this->datasend['DtCompany'] = $this->CompanyModel->GetDataActive();
        $this->datasend['DtDepartment'] = $this->PermissionModel->GetDtDepart($this->datasend['SESSION']->FCCODE);
        $this->datasend['DtCurrency'] = $this->PermissionModel->BMCodeDetail('000001');

        $this->datasend['sidebar'] = 'sidebar_view';
        $this->datasend['content'] = 'leasing/deleteforecast';
        $this->load->view('template', $this->datasend);
    }

    public function UploadFirstReceipt() {
        $this->datasend['formid'] = '79';
        $this->datasend['ACCESS'] = $this->PermissionModel->GetAccessMenu($this->datasend['ROLEACCESS'], $this->datasend['formid']);
        if ($this->datasend['ACCESS']['VIEWS'] != 1) {
            redirect('/');
        }
        $this->datasend['DtDepart'] = $this->PermissionModel->GetDtDepart($this->datasend['SESSION']->FCCODE);
        $this->datasend['UUID'] = $this->uuid->v4();
        $this->datasend['sidebar'] = 'sidebar_view';
        $this->datasend['content'] = 'elog/UploadFirstReceipt';
        $this->load->view('template', $this->datasend);
    }

    public function EFirstReceipt() {
        $this->load->model(array("MasterData/General/CompanyModel", "CompanyModel",'ElogModel'));
        $this->datasend['formid'] = '75';
        $this->datasend['ACCESS'] = $this->PermissionModel->GetAccessMenu($this->datasend['ROLEACCESS'], $this->datasend['formid']);
        // echo "string";
        // var_dump($this->datasend);exit();
        $this->datasend['DtCompany'] = $this->CompanyModel->GetDataActive();
        $this->datasend['DtCurrency'] = $this->PermissionModel->BMCodeDetail("000001");
        $this->datasend['DtDepartment'] = $this->PermissionModel->GetDtDepart($this->datasend['SESSION']->FCCODE);
        $this->datasend['DtElog'] = [];
        if ($this->datasend['ACCESS']['VIEWS'] != 1) {
            redirect('/');
        }
        if (isset($_GET['type'])) {
            if ($_GET['type'] == 'edit') {
                if (!isset($_GET['UUID'])) {
                    echo '<script>
                             alert("ID Not Found !!!");
                             window.location.href = window.location.href.split("?")[0];
                          </script>';
                }
                $this->datasend['DtElog'] = $this->ElogModel->GetData(["UUID" => $_GET['UUID']]);
            }
            
            $this->load->model(["ExtSystemModel", "DocTypeModel"]);
            $this->datasend['DtDocType'] = $this->DocTypeModel->GetDataActiveHead();
            $this->datasend['DtExtSystem'] = $this->ExtSystemModel->GetDataActive();
            $this->datasend['Menu'] = $this->PermissionModel->GetMenu($this->datasend['ROLEACCESS']);
        }

        $this->datasend['sidebar'] = 'sidebar_view';
        $this->datasend['content'] = 'elog/FirstReceipt';
        $this->load->view('template', $this->datasend);
    }

    public function ESendDocument() {
        $this->datasend['formid'] = '76';
        $this->datasend['ACCESS'] = $this->PermissionModel->GetAccessMenu($this->datasend['ROLEACCESS'], $this->datasend['formid']);
        $this->load->model('MasterData/General/CompanyModel');
        $this->datasend['DtCompany'] = $this->CompanyModel->GetDataActive();
        $this->datasend['DtDepartment'] = $this->PermissionModel->GetDtDepart($this->datasend['SESSION']->FCCODE);
        $this->datasend['DtCurrency'] = $this->PermissionModel->BMCodeDetail('000001');
        $this->datasend['cekDEPT'] = $this->session->userdata('DEPARTMENT');
        $this->datasend['DtElog'] = [];
        if ($this->datasend['ACCESS']['VIEWS'] != 1) {
            redirect('/');
        }
        // else if($this->session->userdata('DEPARTMENT') == "IT"){
            $this->datasend['DtLeasing'] = [];
            $this->datasend['Menu'] = $this->PermissionModel->GetMenu($this->datasend['ROLEACCESS']);

            $this->datasend['sidebar'] = 'sidebar_view';
            $this->datasend['content'] = 'elog/SendDocument';
            $this->load->view('template', $this->datasend);
        // }else{
        //     echo "MAINTENANCE";
        // }
        
    }

    public function EReceiveDocument() {
        $this->datasend['formid'] = '77';
        $this->datasend['ACCESS'] = $this->PermissionModel->GetAccessMenu($this->datasend['ROLEACCESS'], $this->datasend['formid']);
        $this->datasend['DtDepartment'] = $this->PermissionModel->GetDtDepart($this->datasend['SESSION']->FCCODE);
        $this->datasend['DtElog'] = [];
        if ($this->datasend['ACCESS']['VIEWS'] != 1) {
            redirect('/');
        }
        // else if($this->session->userdata('DEPARTMENT') == "IT"){
            $this->datasend['DtLeasing'] = [];
            $this->datasend['Menu'] = $this->PermissionModel->GetMenu($this->datasend['ROLEACCESS']);
            $this->load->model('MasterData/General/CompanyModel');
            $this->datasend['DtCompany'] = $this->CompanyModel->GetDataActive();
            $this->datasend['DtCurrency'] = $this->PermissionModel->BMCodeDetail('000001');

            $this->datasend['sidebar'] = 'sidebar_view';
            $this->datasend['content'] = 'elog/ReceiveDocument';
            $this->load->view('template', $this->datasend);
        // }else{
        //     echo "MAINTENANCE";
        // }
        
    }

    public function LastPosDoc(){
        $this->datasend['formid'] = '80';
        $this->datasend['ACCESS'] = $this->PermissionModel->GetAccessMenu($this->datasend['ROLEACCESS'], $this->datasend['formid']);

        $this->datasend['DtElog'] = [];
        
        $this->load->model('DocTypeModel');
        $this->datasend['DtDocType'] = $this->DocTypeModel->GetDataActive();     

        $this->load->model("MasterData/General/CompanyModel", "CompanyModel");
        $this->datasend['DtCompany'] = $this->CompanyModel->GetDataActive();
        $this->datasend['DtDepartment'] = $this->PermissionModel->GetDtDepart($this->datasend['SESSION']->FCCODE);
        $this->datasend['sidebar'] = 'sidebar_view';
        $this->datasend['content'] = 'elog/NewLastPosDoc';
        $this->load->view('template', $this->datasend);
    }

    public function HistoryDoc(){
        $this->datasend['formid'] = '81';
        $this->datasend['ACCESS'] = $this->PermissionModel->GetAccessMenu($this->datasend['ROLEACCESS'], $this->datasend['formid']);

        $this->datasend['DtElog'] = [];
        
        $this->load->model('DocTypeModel');
        $this->datasend['DtDocType'] = $this->DocTypeModel->GetDataActive();     

        $this->load->model("MasterData/General/CompanyModel", "CompanyModel");
        $this->datasend['DtCompany'] = $this->CompanyModel->GetDataActive();
        
        $this->datasend['sidebar'] = 'sidebar_view';
        $this->datasend['content'] = 'elog/HistoryDocs';
        $this->load->view('template', $this->datasend);
    }

    public function NewLastPosDoc(){
        $this->datasend['formid'] = '100';
        $this->datasend['ACCESS'] = $this->PermissionModel->GetAccessMenu($this->datasend['ROLEACCESS'], $this->datasend['formid']);

        $this->datasend['DtElog'] = [];
        
        $this->load->model('DocTypeModel');
        $this->datasend['DtDocType'] = $this->DocTypeModel->GetDataActive();     

        $this->load->model("MasterData/General/CompanyModel", "CompanyModel");
        $this->datasend['DtCompany'] = $this->CompanyModel->GetDataActive();
        $this->datasend['DtDepartment'] = $this->PermissionModel->GetDtDepart($this->datasend['SESSION']->FCCODE);
        $this->datasend['sidebar'] = 'sidebar_view';
        $this->datasend['content'] = 'elog/NewLastPosDoc';
        $this->load->view('template', $this->datasend);
    }

    public function ForecastNew() {
        $this->datasend['formid'] = '84';
        $this->datasend['ACCESS'] = $this->PermissionModel->GetAccessMenu($this->datasend['ROLEACCESS'], $this->datasend['formid']);
        if ($this->datasend['ACCESS']['VIEWS'] != 1) {
            redirect('/');
        }
        $this->datasend['DtDepartment'] = $this->PermissionModel->GetDtDepart($this->datasend['SESSION']->FCCODE);

        $this->datasend['sidebar'] = 'sidebar_view';
        $this->datasend['content'] = 'Cashflow/Forecast/ForecastNew';
        $this->load->view('template', $this->datasend);
    }

    public function NegatifAmount() {
        $this->datasend['formid'] = '85';
        $this->datasend['ACCESS'] = $this->PermissionModel->GetAccessMenu($this->datasend['ROLEACCESS'], $this->datasend['formid']);
        if ($this->datasend['ACCESS']['VIEWS'] != 1) {
            redirect('/');
        }
        $this->datasend['DtDepartment'] = $this->PermissionModel->GetDtDepart($this->datasend['SESSION']->FCCODE);

        $this->datasend['sidebar'] = 'sidebar_view';
        $this->datasend['content'] = 'Cashflow/Forecast/NegatifAmount';
        $this->load->view('template', $this->datasend);
    }

    public function PaymentPeriodControl() {
        $this->datasend['formid'] = '86';
        $this->datasend['ACCESS'] = $this->PermissionModel->GetAccessMenu($this->datasend['ROLEACCESS'], $this->datasend['formid']);
        if ($this->datasend['ACCESS']['VIEWS'] != 1) {
            redirect('/');
        }
        // $this->datasend['DtLeasing'] = [];
        $this->datasend['Menu'] = $this->PermissionModel->GetMenu($this->datasend['ROLEACCESS']);
        $this->load->model('MasterData/General/CompanyModel');
        $this->datasend['DtCompany'] = $this->CompanyModel->GetDataActive();
        $this->datasend['DtCurrency'] = $this->PermissionModel->BMCodeDetail('000001');

        $this->datasend['sidebar'] = 'sidebar_view';
        $this->datasend['content'] = 'Process/PeriodControl';
        $this->load->view('template', $this->datasend);
    }

    public function Closing() {
        $this->datasend['formid'] = '87';
        $this->datasend['ACCESS'] = $this->PermissionModel->GetAccessMenu($this->datasend['ROLEACCESS'], $this->datasend['formid']);
        if ($this->datasend['ACCESS']['VIEWS'] != 1) {
            redirect('/');
        }
        // $this->datasend['DtLeasing'] = [];
        $this->datasend['Menu'] = $this->PermissionModel->GetMenu($this->datasend['ROLEACCESS']);
        $this->load->model('MasterData/General/CompanyModel');
        $this->datasend['DtCompany'] = $this->CompanyModel->GetDataActive();
        $this->datasend['DtCurrency'] = $this->PermissionModel->BMCodeDetail('000001');

        $this->datasend['sidebar'] = 'sidebar_view';
        $this->datasend['content'] = 'Process/Closing';
        $this->load->view('template', $this->datasend);
    }

    public function MstDeptPurchOrg(){
        $this->datasend['formid'] = '89';
        $this->datasend['ACCESS'] = $this->PermissionModel->GetAccessMenu($this->datasend['ROLEACCESS'], $this->datasend['formid']);
        if ($this->datasend['ACCESS']['VIEWS'] != 1) {
            redirect('/');
        }
        $this->datasend['DtDept'] = [];
        if (isset($_GET['type'])) {
            $this->datasend['DtDepartment'] = $this->PermissionModel->GetDtDepart($this->datasend['SESSION']->FCCODE);
            if ($_GET['type'] == 'edit') {
                if (!isset($_GET['id'])) {
                    echo '<script>
                          alert("ID Not Found !!!");
                          window.location.href = window.location.href.split("?")[0];
                          </script>';
                }
                $this->load->model('MstDeptPurchOrgModel');
                $this->datasend['DtDept'] = $this->MstDeptPurchOrgModel->GetData($_GET['id']);
                $this->datasend['getDepart']   = $this->datasend['DtDept']->DEPARTMENT;
            }
        }
        $this->datasend['sidebar'] = 'sidebar_view';
        $this->datasend['content'] = 'MasterData/MstDeptPurchOrg';
        $this->load->view('template', $this->datasend);
    }

    public function MstDeptMaterialSpo(){
        $this->datasend['formid'] = '90';
        $this->datasend['ACCESS'] = $this->PermissionModel->GetAccessMenu($this->datasend['ROLEACCESS'], $this->datasend['formid']);
        if ($this->datasend['ACCESS']['VIEWS'] != 1) {
            redirect('/');
        }
        $this->datasend['DtDept'] = [];
        if (isset($_GET['type'])) {
            $this->datasend['DtDepartment'] = $this->PermissionModel->GetDtDepart($this->datasend['SESSION']->FCCODE);
            if ($_GET['type'] == 'edit') {
                if (!isset($_GET['id'])) {
                    echo '<script>
                          alert("ID Not Found !!!");
                          window.location.href = window.location.href.split("?")[0];
                          </script>';
                }
                $this->load->model('MstDeptMaterialSpoModel');
                $this->datasend['DtDept'] = $this->MstDeptMaterialSpoModel->GetData($_GET['id']);
                $this->datasend['getDepart']   = $this->datasend['DtDept']->DEPARTMENT;
            }
        }
        $this->datasend['sidebar'] = 'sidebar_view';
        $this->datasend['content'] = 'MasterData/MstDeptMaterialSpo';
        $this->load->view('template', $this->datasend);
    }

    public function UserCompany(){
        $this->datasend['formid'] = '91';
        $this->datasend['ACCESS'] = $this->PermissionModel->GetAccessMenu($this->datasend['ROLEACCESS'], $this->datasend['formid']);
        if ($this->datasend['ACCESS']['VIEWS'] != 1) {
            redirect('/');
        }
        $this->datasend['DtUserCompany'] = [];
        if (isset($_GET['type'])) {
            
            if ($_GET['type'] == 'edit') {
                if (!isset($_GET['id'])) {
                    echo '<script>
                          alert("ID Not Found !!!");
                          window.location.href = window.location.href.split("?")[0];
                          </script>';
                }
                $this->load->model('MasterData/UserCompanyModel','UserCompanyModel');
                $this->datasend['DtUserCompany'] = $this->UserCompanyModel->GetData($_GET['id']);
                // $this->datasend['getDepart']   = $this->datasend['DtUserCompany']->DEPARTMENT;
            }
        }
        $this->datasend['sidebar'] = 'sidebar_view';
        $this->datasend['content'] = 'MasterData/MstUserCompany';
        $this->load->view('template', $this->datasend);
    }

    public function TracingDocDetail(){
        $this->datasend['formid'] = '92';
        $this->datasend['ACCESS'] = $this->PermissionModel->GetAccessMenu($this->datasend['ROLEACCESS'], $this->datasend['formid']);

        $this->load->model('DocTypeModel');
        $this->datasend['DtDocType'] = $this->DocTypeModel->GetDataActive();     

        $this->load->model("MasterData/General/CompanyModel", "CompanyModel");
        $this->datasend['DtCompany'] = $this->CompanyModel->GetDataActive();

        $this->datasend['DtTracing'] = [];
        // $this->datasend['departement'] = $this->PermissionModel->GetDtDepart($this->datasend['SESSION']->FCCODE);

        $this->datasend['sidebar'] = 'sidebar_view';
        $this->datasend['content'] = 'Process/TracingDocumentDet';
        $this->load->view('template', $this->datasend);
    }

    public function MstDeptPurchOrg_Add(){
        $this->datasend['formid'] = '94';
        $this->datasend['ACCESS'] = $this->PermissionModel->GetAccessMenu($this->datasend['ROLEACCESS'], $this->datasend['formid']);
        if ($this->datasend['ACCESS']['VIEWS'] != 1) {
            redirect('/');
        }
       
        $this->datasend['DtDept'] = [];
        $this->datasend['DtDepartment'] = $this->PermissionModel->GetDtDepart($this->datasend['SESSION']->FCCODE);
        $this->datasend['sidebar'] = 'sidebar_view';
        $this->datasend['content'] = 'MasterData/MstDeptPurchOrg_Add';
        $this->load->view('template', $this->datasend);
        
    }

    public function MstDeptPurchOrg_List(){
        $this->datasend['formid'] = '95';
        $this->datasend['ACCESS'] = $this->PermissionModel->GetAccessMenu($this->datasend['ROLEACCESS'], $this->datasend['formid']);
        if ($this->datasend['ACCESS']['VIEWS'] != 1) {
            redirect('/');
        }
        $this->datasend['DtDept'] = [];
        if (isset($_GET['type'])) {
            if ($_GET['type'] == 'edit') {
                if (!isset($_GET['id'])) {
                    echo '<script>
                          alert("ID Not Found !!!");
                          window.location.href = window.location.href.split("?")[0];
                          </script>';
                }
                $this->load->model('MstDeptPurchOrgModel');
                $this->datasend['DtDept'] = $this->MstDeptPurchOrgModel->GetData($_GET['id']);
                $this->datasend['getDepart']   = $this->datasend['DtDept']->DEPARTMENT;
            }
        }
        $this->datasend['DtDepartment'] = $this->PermissionModel->GetDtDepart($this->datasend['SESSION']->FCCODE);
        $this->datasend['sidebar'] = 'sidebar_view';
        $this->datasend['content'] = 'MasterData/MstDeptPurchOrg_List';
        $this->load->view('template', $this->datasend);
    }

    public function Staging_Download_SPO(){
        $this->datasend['formid'] = '97';
        $this->datasend['ACCESS'] = $this->PermissionModel->GetAccessMenu($this->datasend['ROLEACCESS'], $this->datasend['formid']);
        if ($this->datasend['ACCESS']['VIEWS'] != 1) {
            redirect('/');
        }
        $this->load->model('StagingModel');
        $this->datasend['DtCompany'] = $this->StagingModel->loadCompany();
        $this->datasend['DtBu']       = '';//$this->StagingModel->loadBusinessUnit();
        // $this->datasend['DtDepartment'] = $this->PermissionModel->GetDtDepart($this->datasend['SESSION']->FCCODE);
        $this->datasend['sidebar'] = 'sidebar_view';
        $this->datasend['content'] = 'MasterData/Staging_SPO';
        $this->load->view('template', $this->datasend);
        
    }

    public function Staging_Download_STO(){
        $this->datasend['formid'] = '98';
        $this->datasend['ACCESS'] = $this->PermissionModel->GetAccessMenu($this->datasend['ROLEACCESS'], $this->datasend['formid']);
        if ($this->datasend['ACCESS']['VIEWS'] != 1) {
            redirect('/');
        }
        $this->load->model('StagingModel');
        $this->datasend['DtCompany'] = $this->StagingModel->loadCompanySTO();
        // $this->datasend['DtBu']       = $this->StagingModel->loadBusinessUnitSTO();
        // $this->datasend['DtDepartment'] = $this->PermissionModel->GetDtDepart($this->datasend['SESSION']->FCCODE);
        $this->datasend['sidebar'] = 'sidebar_view';
        $this->datasend['content'] = 'MasterData/Staging_STO';
        $this->load->view('template', $this->datasend);
        
    }

    public function upload_pph(){
        $this->datasend['formid'] = '99';
        $this->datasend['ACCESS'] = $this->PermissionModel->GetAccessMenu($this->datasend['ROLEACCESS'], $this->datasend['formid']);
        if ($this->datasend['ACCESS']['VIEWS'] != 1) {
            redirect('/');
        }
        
        // $this->datasend['DtDepartment'] = $this->PermissionModel->GetDtDepart($this->datasend['SESSION']->FCCODE);
        $this->datasend['sidebar'] = 'sidebar_view';
        $this->datasend['content'] = 'Process/UploadPPH';
        $this->load->view('template', $this->datasend);
        
    }

    public function UploadtoForecast(){
        $this->datasend['formid'] = '101';
        $this->datasend['ACCESS'] = $this->PermissionModel->GetAccessMenu($this->datasend['ROLEACCESS'], $this->datasend['formid']);
        if ($this->datasend['ACCESS']['VIEWS'] != 1) {
            redirect('/');
        }
        $this->load->model('ExtSystemModel');
        $this->datasend['DtExtSystem'] = $this->ExtSystemModel->GetDataActive();
        $this->load->model('DocTypeModel');
        $this->datasend['DtDocType'] = $this->DocTypeModel->GetDataActive();
        $this->datasend['sidebar'] = 'sidebar_view';
        $this->datasend['content'] = 'Cashflow/uploadForecast';
        $this->load->view('template', $this->datasend);
        
    }

    public function Znego(){
        $this->datasend['formid'] = '102';
        $this->datasend['ACCESS'] = $this->PermissionModel->GetAccessMenu($this->datasend['ROLEACCESS'], $this->datasend['formid']);
        if ($this->datasend['ACCESS']['VIEWS'] != 1) {
            redirect('/');
        }
        $this->load->model('MasterData/General/CompanyModel');
        $this->datasend['DtCompany'] = $this->CompanyModel->GetDataActive();
        $this->datasend['sidebar'] = 'sidebar_view';
        $this->datasend['content'] = 'Cashflow/Znego';
        $this->load->view('template', $this->datasend);
    }

    public function B2BReversal(){
        $this->datasend['formid'] = '103';
        $this->datasend['ACCESS'] = $this->PermissionModel->GetAccessMenu($this->datasend['ROLEACCESS'], $this->datasend['formid']);
        if ($this->datasend['ACCESS']['VIEWS'] != 1) {
            redirect('/');
        }
        $this->load->model('MasterData/General/CompanyModel');
        $this->datasend['DtCompany'] = $this->CompanyModel->GetDataActive();
        $this->datasend['sidebar'] = 'sidebar_view';
        $this->datasend['content'] = 'Cashflow/B2BReversal';
        $this->load->view('template', $this->datasend);
        
    }

    public function KMKMasterRKBD() {
        $this->load->model(array("MasterData/General/CompanyModel", "CompanyModel",'KMKModel'));
        $this->datasend['formid'] = '105';
        $this->datasend['ACCESS'] = $this->PermissionModel->GetAccessMenu($this->datasend['ROLEACCESS'], $this->datasend['formid']);
        // echo "string";
        // var_dump($this->datasend);exit();
        $this->datasend['DtCompany'] = $this->CompanyModel->GetDataActive();
        $this->datasend['DtCurrency'] = $this->PermissionModel->BMCodeDetail("000001");
        $this->datasend['DtDepartment'] = $this->PermissionModel->GetDtDepart($this->datasend['SESSION']->FCCODE);
        $this->datasend['DtKmk'] = [];
        if ($this->datasend['ACCESS']['VIEWS'] != 1) {
            redirect('/');
        }
        if (isset($_GET['type'])) {
            if ($_GET['type'] == 'edit') {
                if (!isset($_GET['UUID'])) {
                    echo '<script>
                             alert("ID Not Found !!!");
                             window.location.href = window.location.href.split("?")[0];
                          </script>';
                }
                $this->datasend['DtKmk'] = $this->KMKModel->GetDataRK(["UUID" => $_GET['UUID']]);
                // var_dump($this->db->last_query());exit;
                // $this->datasend['DtDetail'] = $this->KMKModel->HeaderDetailRK(["UUID" => $_GET['UUID']]);
                $this->datasend['DtUpload']  = $_GET['UUID'];
            }
            
            $this->load->model(["ExtSystemModel", "DocTypeModel"]);
            $this->datasend['DtDocType'] = $this->DocTypeModel->GetDataActiveHead();
            $this->datasend['DtExtSystem'] = $this->ExtSystemModel->GetDataActive();
            $this->datasend['Menu'] = $this->PermissionModel->GetMenu($this->datasend['ROLEACCESS']);
        }

        $this->datasend['sidebar'] = 'sidebar_view';
        $this->datasend['content'] = 'kmk/KMKMaster';
        $this->load->view('template', $this->datasend);
    }

    public function KMKMasterWA() {
        $this->load->model(array("MasterData/General/CompanyModel", "CompanyModel",'KMKModel'));
        $this->datasend['formid'] = '106';
        $this->datasend['ACCESS'] = $this->PermissionModel->GetAccessMenu($this->datasend['ROLEACCESS'], $this->datasend['formid']);
        // echo "string";
        // var_dump($this->datasend);exit();
        $this->datasend['DtCompany'] = $this->CompanyModel->GetDataActive();
        $this->datasend['DtCurrency'] = $this->PermissionModel->BMCodeDetail("000001");
        $this->datasend['DtDepartment'] = $this->PermissionModel->GetDtDepart($this->datasend['SESSION']->FCCODE);
        $this->datasend['DtKmk'] = [];
        $this->datasend['DtDetail'] = [];
        if ($this->datasend['ACCESS']['VIEWS'] != 1) {
            redirect('/');
        }
        if (isset($_GET['type'])) {
            if ($_GET['type'] == 'edit') {
                if (!isset($_GET['UUID'])) {
                    echo '<script>
                             alert("ID Not Found !!!");
                             window.location.href = window.location.href.split("?")[0];
                          </script>';
                }
                $this->datasend['DtKmk'] = $this->KMKModel->GetDataWA(["UUID" => $_GET['UUID']]);
                // var_dump($this->db->last_query());exit;
                $this->datasend['DtDetail'] = $this->KMKModel->HeaderDetailWA(["UUID" => $_GET['UUID']]);
                
                $this->datasend['DtUpload']  = $_GET['UUID'];
            }
            
            $this->load->model(["ExtSystemModel", "DocTypeModel"]);
            $this->datasend['DtDocType'] = $this->DocTypeModel->GetDataActiveHead();
            $this->datasend['DtExtSystem'] = $this->ExtSystemModel->GetDataActive();
            $this->datasend['Menu'] = $this->PermissionModel->GetMenu($this->datasend['ROLEACCESS']);
        }

        $this->datasend['sidebar'] = 'sidebar_view';
        $this->datasend['content'] = 'kmk/KMKMasterWA';
        $this->load->view('template', $this->datasend);
    }

    public function KMKMasterKI() {
        // ini_set('display_errors', 'On');
        $this->load->model(array("MasterData/General/CompanyModel", "CompanyModel",'KMKModel'));
        $this->datasend['formid'] = '107';
        $this->datasend['ACCESS'] = $this->PermissionModel->GetAccessMenu($this->datasend['ROLEACCESS'], $this->datasend['formid']);
        // echo "string";
        // var_dump($this->datasend);exit();
        $this->datasend['DtCompany'] = $this->CompanyModel->GetDataActive();
        $this->datasend['DtCurrency'] = $this->PermissionModel->BMCodeDetail("000001");
        $this->datasend['DtDepartment'] = $this->PermissionModel->GetDtDepart($this->datasend['SESSION']->FCCODE);
        $this->datasend['DtKmk'] = [];
        $this->datasend['DtDetail'] = [];
        $this->datasend['DtTranche'] = [];
        if ($this->datasend['ACCESS']['VIEWS'] != 1) {
            redirect('/');
        }
        if (isset($_GET['type'])) {
            if ($_GET['type'] == 'edit') {
                if (!isset($_GET['UUID'])) {
                    echo '<script>
                             alert("ID Not Found !!!");
                             window.location.href = window.location.href.split("?")[0];
                          </script>';
                }
                $this->datasend['DtKmk'] = $this->KMKModel->GetDataKI(["UUID" => $_GET['UUID']]);
                $this->datasend['DtDetail'] = $this->KMKModel->HeaderDetailKI(["UUID" => $_GET['UUID']]);
                $this->datasend['bankKI'] = $this->KMKModel->getBankKI(["UUID" => $_GET['UUID']]);
                $this->datasend['DtTranche'] = $this->KMKModel->getTrancheNumberList(["UUID" => $_GET['UUID']]);
                // var_dump($this->datasend['DtTranche']); exit;
                // var_dump($this->db->last_query());exit;
                $this->datasend['DtUpload']  = $_GET['UUID'];
            }
            
            $this->load->model(["ExtSystemModel", "DocTypeModel"]);
            $this->datasend['DtDocType'] = $this->DocTypeModel->GetDataActiveHead();
            $this->datasend['DtExtSystem'] = $this->ExtSystemModel->GetDataActive();
            $this->datasend['Menu'] = $this->PermissionModel->GetMenu($this->datasend['ROLEACCESS']);
        }

        $this->datasend['sidebar'] = 'sidebar_view';
        $this->datasend['content'] = 'kmk/KMKMasterKI';
        $this->load->view('template', $this->datasend);
    }
	
    public function Withdraw() {
        $this->load->model(array("MasterData/General/CompanyModel", "CompanyModel",'KMKModel'));
        $this->datasend['formid'] = '111';
        $this->datasend['ACCESS'] = $this->PermissionModel->GetAccessMenu($this->datasend['ROLEACCESS'], $this->datasend['formid']);
        // echo "string";
        // var_dump($this->datasend);exit();
        $this->datasend['DtCompany'] = $this->CompanyModel->GetDataActive();
        $this->datasend['DtCurrency'] = $this->PermissionModel->BMCodeDetail("000001");
        $this->datasend['DtDepartment'] = $this->PermissionModel->GetDtDepart($this->datasend['SESSION']->FCCODE);
        $this->datasend['DtKmk'] = [];
        $this->datasend['DtDetail'] = [];
        if ($this->datasend['ACCESS']['VIEWS'] != 1) {
            redirect('/');
        }
        if (isset($_GET['type'])) {
            if ($_GET['type'] == 'edit') {
                if (!isset($_GET['UUID'])) {
                    echo '<script>
                             alert("ID Not Found !!!");
                             window.location.href = window.location.href.split("?")[0];
                          </script>';
                }
                $this->datasend['DtKmk'] = $this->KMKModel->GetDataWDRK(["UUID" => $_GET['UUID']]);
                // var_dump($this->db->last_query());exit;
                $this->datasend['DtUpload']  = $_GET['UUID'];
            }
            
            $this->load->model(["ExtSystemModel", "DocTypeModel"]);
            $this->datasend['DtDocType'] = $this->DocTypeModel->GetDataActiveHead();
            $this->datasend['DtExtSystem'] = $this->ExtSystemModel->GetDataActive();
            $this->datasend['Menu'] = $this->PermissionModel->GetMenu($this->datasend['ROLEACCESS']);
        }

        $this->datasend['sidebar'] = 'sidebar_view';
        $this->datasend['content'] = 'kmk/Withdraw';
        $this->load->view('template', $this->datasend);
    }

    public function WithdrawTL() {
        $this->load->model(array("MasterData/General/CompanyModel", "CompanyModel",'KMKModel'));
        $this->datasend['formid'] = '111';
        $this->datasend['ACCESS'] = $this->PermissionModel->GetAccessMenu($this->datasend['ROLEACCESS'], $this->datasend['formid']);
        // echo "string";
        // var_dump($this->datasend);exit();
        $this->datasend['DtCompany'] = $this->CompanyModel->GetDataActive();
        $this->datasend['DtCurrency'] = $this->PermissionModel->BMCodeDetail("000001");
        $this->datasend['DtDepartment'] = $this->PermissionModel->GetDtDepart($this->datasend['SESSION']->FCCODE);
        $this->datasend['DtKmk'] = [];
        $this->datasend['DtDetail'] = [];
        if ($this->datasend['ACCESS']['VIEWS'] != 1) {
            redirect('/');
        }
        if (isset($_GET['type'])) {
            if ($_GET['type'] == 'edit') {
                if (!isset($_GET['UUID'])) {
                    echo '<script>
                             alert("ID Not Found !!!");
                             window.location.href = window.location.href.split("?")[0];
                          </script>';
                }
                $this->datasend['DtKmk'] = $this->KMKModel->GetDataWDRK(["UUID" => $_GET['UUID']]);
                // var_dump($this->db->last_query());exit;
                $this->datasend['DtUpload']  = $_GET['UUID'];
            }
            
            $this->load->model(["ExtSystemModel", "DocTypeModel"]);
            $this->datasend['DtDocType'] = $this->DocTypeModel->GetDataActiveHead();
            $this->datasend['DtExtSystem'] = $this->ExtSystemModel->GetDataActive();
            $this->datasend['Menu'] = $this->PermissionModel->GetMenu($this->datasend['ROLEACCESS']);
        }

        $this->datasend['sidebar'] = 'sidebar_view';
        $this->datasend['content'] = 'kmk/WithdrawTL';
        $this->load->view('template', $this->datasend);
    }

    public function WithdrawWA() {
        $this->load->model(array("MasterData/General/CompanyModel", "CompanyModel",'KMKModel'));
        $this->datasend['formid'] = '111';
        $this->datasend['ACCESS'] = $this->PermissionModel->GetAccessMenu($this->datasend['ROLEACCESS'], $this->datasend['formid']);
        // echo "string";
        // var_dump($this->datasend);exit();
        $this->datasend['DtCompany'] = $this->CompanyModel->GetDataActive();
        $this->datasend['DtCurrency'] = $this->PermissionModel->BMCodeDetail("000001");
        $this->datasend['DtDepartment'] = $this->PermissionModel->GetDtDepart($this->datasend['SESSION']->FCCODE);
        $this->datasend['DtKmk'] = [];
        $this->datasend['DtDetail'] = [];
        if ($this->datasend['ACCESS']['VIEWS'] != 1) {
            redirect('/');
        }
        if (isset($_GET['type'])) {
            if ($_GET['type'] == 'edit') {
                if (!isset($_GET['UUID'])) {
                    echo '<script>
                             alert("ID Not Found !!!");
                             window.location.href = window.location.href.split("?")[0];
                          </script>';
                }
                $this->datasend['DtKmk'] = $this->KMKModel->GetDataWDRK(["UUID" => $_GET['UUID']]);
                // var_dump($this->db->last_query());exit;
                $this->datasend['DtUpload']  = $_GET['UUID'];
            }
            
            $this->load->model(["ExtSystemModel", "DocTypeModel"]);
            $this->datasend['DtDocType'] = $this->DocTypeModel->GetDataActiveHead();
            $this->datasend['DtExtSystem'] = $this->ExtSystemModel->GetDataActive();
            $this->datasend['Menu'] = $this->PermissionModel->GetMenu($this->datasend['ROLEACCESS']);
        }

        $this->datasend['sidebar'] = 'sidebar_view';
        $this->datasend['content'] = 'kmk/WithdrawWA';
        $this->load->view('template', $this->datasend);
    }

    public function WithdrawKI() {
        $this->load->model(array("MasterData/General/CompanyModel", "CompanyModel",'KMKModel'));
        $this->datasend['formid'] = '111';
        $this->datasend['ACCESS'] = $this->PermissionModel->GetAccessMenu($this->datasend['ROLEACCESS'], $this->datasend['formid']);
        // echo "string";
        // var_dump($this->datasend);exit();
        $this->datasend['DtCompany'] = $this->CompanyModel->GetDataActive();
        $this->datasend['DtCurrency'] = $this->PermissionModel->BMCodeDetail("000001");
        $this->datasend['DtDepartment'] = $this->PermissionModel->GetDtDepart($this->datasend['SESSION']->FCCODE);
        $this->datasend['DtKmk'] = [];
        $this->datasend['DtDetail'] = [];
        $this->datasend['DtTranche'] = [];
        if ($this->datasend['ACCESS']['VIEWS'] != 1) {
            redirect('/');
        }
        if (isset($_GET['type'])) {
            if ($_GET['type'] == 'edit') {
                if (!isset($_GET['UUID'])) {
                    echo '<script>
                             alert("ID Not Found !!!");
                             window.location.href = window.location.href.split("?")[0];
                          </script>';
                }
                $this->datasend['DtKmk'] = $this->KMKModel->GetDataWDKI(["UUID" => $_GET['UUID']]);
                // var_dump($this->db->last_query());exit;
                $this->datasend['Uid']  = $_GET['UUID'];
                $this->datasend['DtUpload']  = $_GET['UUID'];
                $this->datasend['DtTranche'] = $this->db->where("UUID = '{$_GET['UUID']}' AND ISACTIVE = '1' AND IS_COMPLETE IS NULL")->get('FUNDS_DETAIL_KI_TRANCHE')->result();
            }
            
            $this->load->model(["ExtSystemModel", "DocTypeModel"]);
            $this->datasend['DtDocType'] = $this->DocTypeModel->GetDataActiveHead();
            $this->datasend['DtExtSystem'] = $this->ExtSystemModel->GetDataActive();
            $this->datasend['Menu'] = $this->PermissionModel->GetMenu($this->datasend['ROLEACCESS']);
        }

        $this->datasend['sidebar'] = 'sidebar_view';
        $this->datasend['content'] = 'kmk/WithdrawKI';
        $this->load->view('template', $this->datasend);
    }

    // public function WithdrawAP() {
    //     $this->load->model(array("MasterData/General/CompanyModel", "CompanyModel",'KMKModel'));
    //     $this->datasend['formid'] = '111';
    //     $this->datasend['ACCESS'] = $this->PermissionModel->GetAccessMenu($this->datasend['ROLEACCESS'], $this->datasend['formid']);
    //     // echo "string";
    //     // var_dump($this->datasend);exit();
    //     $this->datasend['DtCompany'] = $this->CompanyModel->GetDataActive();
    //     $this->datasend['DtCurrency'] = $this->PermissionModel->BMCodeDetail("000001");
    //     $this->datasend['DtDepartment'] = $this->PermissionModel->GetDtDepart($this->datasend['SESSION']->FCCODE);
    //     $this->datasend['DtKmk'] = [];
    //     $this->datasend['DtDetail'] = [];
    //     if ($this->datasend['ACCESS']['VIEWS'] != 1) {
    //         redirect('/');
    //     }
    //     if (isset($_GET['type'])) {
    //         if ($_GET['type'] == 'edit') {
    //             if (!isset($_GET['UUID'])) {
    //                 echo '<script>
    //                          alert("ID Not Found !!!");
    //                          window.location.href = window.location.href.split("?")[0];
    //                       </script>';
    //             }
    //             $this->datasend['DtKmk'] = $this->KMKModel->GetDataWDRK(["UUID" => $_GET['UUID']]);
    //             // var_dump($this->db->last_query());exit;
    //             $this->datasend['DtUpload']  = $_GET['UUID'];
    //         }
            
    //         $this->load->model(["ExtSystemModel", "DocTypeModel"]);
    //         $this->datasend['DtDocType'] = $this->DocTypeModel->GetDataActiveHead();
    //         $this->datasend['DtExtSystem'] = $this->ExtSystemModel->GetDataActive();
    //         $this->datasend['Menu'] = $this->PermissionModel->GetMenu($this->datasend['ROLEACCESS']);
    //     }

    //     $this->datasend['sidebar'] = 'sidebar_view';
    //     $this->datasend['content'] = 'kmk/WithdrawAP';
    //     $this->load->view('template', $this->datasend);
    // }

    public function WithdrawNDK() {
        $this->load->model(array("MasterData/General/CompanyModel", "CompanyModel",'KMKModel'));
        $this->datasend['formid'] = '111';
        $this->datasend['ACCESS'] = $this->PermissionModel->GetAccessMenu($this->datasend['ROLEACCESS'], $this->datasend['formid']);
        $this->datasend['DtCompany']    = $this->CompanyModel->GetDataActive();
        $this->datasend['DtCurrency']   = $this->PermissionModel->BMCodeDetail("000001");
        $this->datasend['DtDepartment'] = $this->PermissionModel->GetDtDepart($this->datasend['SESSION']->FCCODE);
        $this->datasend['DtKmk']        = [];
        $this->datasend['DtWD_ByUUID']  = [];
        
        // echo "string";
        // var_dump($this->datasend);exit();
        // if ($this->datasend['ACCESS']['VIEWS'] != 1) {
        //     redirect('/');
        // }
        if (isset($_GET['type'])) {
            if ($_GET['type'] == 'edit') {
                if (!isset($_GET['UUID'])) {
                    echo '<script>
                             alert("ID Not Found !!!");
                             window.location.href = window.location.href.split("?")[0];
                          </script>';
                }
                $this->datasend['DtKmk']    = $this->KMKModel->GetDataRK(["UUID" => $_GET['UUID']]);
                // $this->datasend['DtDetail'] = $this->KMKModel->HeaderDetailRK(["UUID" => $_GET['UUID']]);
                $this->datasend['DtWD_ByUUID']  = $_GET['UUID'];
                $this->datasend['DtUpload'] = $_GET['UUID'];
            }
            
            $this->load->model(["ExtSystemModel", "DocTypeModel"]);
            $this->datasend['DtDocType'] = $this->DocTypeModel->GetDataActiveHead();
            $this->datasend['DtExtSystem'] = $this->ExtSystemModel->GetDataActive();
            $this->datasend['Menu'] = $this->PermissionModel->GetMenu($this->datasend['ROLEACCESS']);
        }

        $this->datasend['sidebar'] = 'sidebar_view';
        $this->datasend['content'] = 'kmk/WithdrawNDK';
        $this->load->view('template', $this->datasend);
    }

    public function WithdrawAR() {
        $this->load->model(array("MasterData/General/CompanyModel", "CompanyModel",'KMKModel'));
        $this->datasend['formid'] = '111';
        $this->datasend['ACCESS'] = $this->PermissionModel->GetAccessMenu($this->datasend['ROLEACCESS'], $this->datasend['formid']);
        $this->datasend['DtCompany']    = $this->CompanyModel->GetDataActive();
        $this->datasend['DtCurrency']   = $this->PermissionModel->BMCodeDetail("000001");
        $this->datasend['DtDepartment'] = $this->PermissionModel->GetDtDepart($this->datasend['SESSION']->FCCODE);
        $this->datasend['DtKmk']        = [];
        $this->datasend['DtWD_ByUUID']  = [];
        $this->datasend['DtUpload']     = [];
        // echo "string";
        // var_dump($this->datasend);exit();
        // if ($this->datasend['ACCESS']['VIEWS'] != 1) {
        //     redirect('/');
        // }
        if (isset($_GET['type'])) {
            if ($_GET['type'] == 'edit') {
                if (!isset($_GET['UUID'])) {
                    echo '<script>
                             alert("ID Not Found !!!");
                             window.location.href = window.location.href.split("?")[0];
                          </script>';
                }
                $this->datasend['DtKmk']    = $this->KMKModel->GetDataWAAR(["UUID" => $_GET['UUID']]);
                // $this->datasend['DtDetail'] = $this->KMKModel->HeaderDetailRK(["UUID" => $_GET['UUID']]);
                $this->datasend['DtWD_ByUUID']  = $_GET['UUID'];
                $this->datasend['DtUpload'] = $_GET['UUID'];
            }
            
            $this->load->model(["ExtSystemModel", "DocTypeModel"]);
            $this->datasend['DtDocType'] = $this->DocTypeModel->GetDataActiveHead();
            $this->datasend['DtExtSystem'] = $this->ExtSystemModel->GetDataActive();
            $this->datasend['Menu'] = $this->PermissionModel->GetMenu($this->datasend['ROLEACCESS']);
        }

        $this->datasend['sidebar'] = 'sidebar_view';
        $this->datasend['content'] = 'kmk/WithdrawAR';
        $this->load->view('template', $this->datasend);
    }

    public function WithdrawData() {
        $this->load->model(array("MasterData/General/CompanyModel", "CompanyModel",'KMKModel'));
        $this->datasend['formid'] = '112';
        $this->datasend['ACCESS'] = $this->PermissionModel->GetAccessMenu($this->datasend['ROLEACCESS'], $this->datasend['formid']);
        // echo "string";
        // var_dump($this->datasend);exit();
        $this->datasend['DtCompany'] = $this->CompanyModel->GetDataActive();
        $this->datasend['DtCurrency'] = $this->PermissionModel->BMCodeDetail("000001");
        $this->datasend['DtDepartment'] = $this->PermissionModel->GetDtDepart($this->datasend['SESSION']->FCCODE);
        $this->datasend['DtKmk'] = [];
        $this->datasend['DtDetail'] = [];
        if ($this->datasend['ACCESS']['VIEWS'] != 1) {
            redirect('/');
        }
        if (isset($_GET['type'])) {
            if ($_GET['type'] == 'edit') {
                if (!isset($_GET['UUID'])) {
                    echo '<script>
                             alert("ID Not Found !!!");
                             window.location.href = window.location.href.split("?")[0];
                          </script>';
                }
                $this->datasend['DtKmk'] = $this->KMKModel->GetDataWDbyID(["UUID" => $_GET['UUID'],'IDDET' => $_GET['IDDET']]);
                $this->datasend['lastBalance'] = $this->KMKModel->getDataLastBalanceRK(['UUID'=> $_GET['UUID']]); 
                // var_dump($this->db->last_query());exit;
                $this->datasend['DtUpload']  = $_GET['UUID'];
            }
            
            $this->load->model(["ExtSystemModel", "DocTypeModel"]);
            $this->datasend['DtDocType'] = $this->DocTypeModel->GetDataActiveHead();
            $this->datasend['DtExtSystem'] = $this->ExtSystemModel->GetDataActive();
            $this->datasend['Menu'] = $this->PermissionModel->GetMenu($this->datasend['ROLEACCESS']);
        }

        $this->datasend['sidebar'] = 'sidebar_view';
        $this->datasend['content'] = 'kmk/WithdrawData';
        $this->load->view('template', $this->datasend);
    }

    public function WithdrawDataTL() {
        $this->load->model(array("MasterData/General/CompanyModel", "CompanyModel",'KMKModel'));
        $this->datasend['formid'] = '112';
        $this->datasend['ACCESS'] = $this->PermissionModel->GetAccessMenu($this->datasend['ROLEACCESS'], $this->datasend['formid']);
        // echo "string";
        // var_dump($this->datasend);exit();
        $this->datasend['DtCompany'] = $this->CompanyModel->GetDataActive();
        $this->datasend['DtCurrency'] = $this->PermissionModel->BMCodeDetail("000001");
        $this->datasend['DtDepartment'] = $this->PermissionModel->GetDtDepart($this->datasend['SESSION']->FCCODE);
        $this->datasend['DtKmk'] = [];
        $this->datasend['DtDetail'] = [];
        if ($this->datasend['ACCESS']['VIEWS'] != 1) {
            redirect('/');
        }
        if (isset($_GET['type'])) {
            if ($_GET['type'] == 'edit') {
                if (!isset($_GET['UUID'])) {
                    echo '<script>
                             alert("ID Not Found !!!");
                             window.location.href = window.location.href.split("?")[0];
                          </script>';
                }
                $this->datasend['DtKmk'] = $this->KMKModel->GetDataWDbyID(["UUID" => $_GET['UUID'],'IDDET' => $_GET['IDDET']]);
                $this->datasend['lastBalance'] = $this->KMKModel->getDataLastBalanceRK(['UUID'=> $_GET['UUID']]); 
                // var_dump($this->db->last_query());exit;
                $this->datasend['DtUpload']  = $_GET['UUID'];
            }
            
            $this->load->model(["ExtSystemModel", "DocTypeModel"]);
            $this->datasend['DtDocType'] = $this->DocTypeModel->GetDataActiveHead();
            $this->datasend['DtExtSystem'] = $this->ExtSystemModel->GetDataActive();
            $this->datasend['Menu'] = $this->PermissionModel->GetMenu($this->datasend['ROLEACCESS']);
        }

        $this->datasend['sidebar'] = 'sidebar_view';
        $this->datasend['content'] = 'kmk/WithdrawDataTL';
        $this->load->view('template', $this->datasend);
    }

    public function WithdrawDataWA() {
        $this->load->model(array("MasterData/General/CompanyModel", "CompanyModel",'KMKModel'));
        $this->datasend['formid'] = '112';
        $this->datasend['ACCESS'] = $this->PermissionModel->GetAccessMenu($this->datasend['ROLEACCESS'], $this->datasend['formid']);
        // echo "string";
        // var_dump($this->datasend);exit();
        $this->datasend['DtCompany'] = $this->CompanyModel->GetDataActive();
        $this->datasend['DtCurrency'] = $this->PermissionModel->BMCodeDetail("000001");
        $this->datasend['DtDepartment'] = $this->PermissionModel->GetDtDepart($this->datasend['SESSION']->FCCODE);
        $this->datasend['DtKmk'] = [];
        $this->datasend['DtDetail'] = [];
        if ($this->datasend['ACCESS']['VIEWS'] != 1) {
            redirect('/');
        }
        if (isset($_GET['type'])) {
            if ($_GET['type'] == 'edit') {
                if (!isset($_GET['UUID'])) {
                    echo '<script>
                             alert("ID Not Found !!!");
                             window.location.href = window.location.href.split("?")[0];
                          </script>';
                }
                $this->datasend['DtKmk'] = $this->KMKModel->GetDataWDbyID(["UUID" => $_GET['UUID'],'IDDET' => $_GET['IDDET']]);
                // var_dump($this->db->last_query());exit;
                $this->datasend['DtUpload']  = $_GET['UUID'];
            }
            
            $this->load->model(["ExtSystemModel", "DocTypeModel"]);
            $this->datasend['DtDocType'] = $this->DocTypeModel->GetDataActiveHead();
            $this->datasend['DtExtSystem'] = $this->ExtSystemModel->GetDataActive();
            $this->datasend['Menu'] = $this->PermissionModel->GetMenu($this->datasend['ROLEACCESS']);
        }

        $this->datasend['sidebar'] = 'sidebar_view';
        $this->datasend['content'] = 'kmk/WithdrawDataWA';
        $this->load->view('template', $this->datasend);
    }

    public function WithdrawDataKI() {
        $this->load->model(array("MasterData/General/CompanyModel", "CompanyModel",'KMKModel'));
        $this->datasend['formid'] = '113';
        $this->datasend['ACCESS'] = $this->PermissionModel->GetAccessMenu($this->datasend['ROLEACCESS'], $this->datasend['formid']);
        // echo "string";
        // var_dump($this->datasend);exit();
        $this->datasend['DtCompany'] = $this->CompanyModel->GetDataActive();
        $this->datasend['DtCurrency'] = $this->PermissionModel->BMCodeDetail("000001");
        $this->datasend['DtDepartment'] = $this->PermissionModel->GetDtDepart($this->datasend['SESSION']->FCCODE);
        $this->datasend['DtKmk'] = [];
        $this->datasend['DtDetail'] = [];
        if ($this->datasend['ACCESS']['VIEWS'] != 1) {
            redirect('/');
        }
        if (isset($_GET['type'])) {
            if ($_GET['type'] == 'edit') {
                if (!isset($_GET['UUID'])) {
                    echo '<script>
                             alert("ID Not Found !!!");
                             window.location.href = window.location.href.split("?")[0];
                          </script>';
                }
                $this->datasend['DtKmk'] = $this->KMKModel->GetDataWDKIbyID(["UUID" => $_GET['UUID'],'IDDET' => $_GET['IDDET']]);
                // var_dump($this->db->last_query());exit;
                $this->datasend['Uid']  = $_GET['UUID'];
                $this->datasend['DtUpload']  = $_GET['UUID'];
                $this->datasend['DtTranche'] = $this->db->get_where('FUNDS_DETAIL_KI_TRANCHE',['UUID'=>$_GET['UUID'], 'ISACTIVE' => 1])->result();
            }
            
            $this->load->model(["ExtSystemModel", "DocTypeModel"]);
            $this->datasend['DtDocType'] = $this->DocTypeModel->GetDataActiveHead();
            $this->datasend['DtExtSystem'] = $this->ExtSystemModel->GetDataActive();
            $this->datasend['Menu'] = $this->PermissionModel->GetMenu($this->datasend['ROLEACCESS']);
        }

        $this->datasend['sidebar'] = 'sidebar_view';
        $this->datasend['content'] = 'kmk/WithdrawDataKI';
        $this->load->view('template', $this->datasend);
    }

    public function WithdrawAP() {
        $this->load->model(array("MasterData/General/CompanyModel", "CompanyModel",'KMKModel'));
        $this->datasend['formid'] = '111';
        $this->datasend['ACCESS'] = $this->PermissionModel->GetAccessMenu($this->datasend['ROLEACCESS'], $this->datasend['formid']);
        // echo "string";
        // var_dump($this->datasend);exit();
        $this->datasend['DtCompany'] = $this->CompanyModel->GetDataActive();
        $this->datasend['DtCurrency'] = $this->PermissionModel->BMCodeDetail("000001");
        $this->datasend['DtDepartment'] = $this->PermissionModel->GetDtDepart($this->datasend['SESSION']->FCCODE);
        $this->datasend['DtKmk'] = [];
        $this->datasend['DtDetail'] = [];
        $this->datasend['DtWD_ByUUID']  = [];
        if ($this->datasend['ACCESS']['VIEWS'] != 1) {
            redirect('/');
        }
        if (isset($_GET['type'])) {
            if ($_GET['type'] == 'edit') {
                if (!isset($_GET['UUID'])) {
                    echo '<script>
                             alert("ID Not Found !!!");
                             window.location.href = window.location.href.split("?")[0];
                          </script>';
                }
                $this->datasend['DtKmk'] = $this->KMKModel->GetDataWAAP(["UUID" => $_GET['UUID']]);
                // var_dump($this->db->last_query());exit;
                $this->datasend['DtUpload']  = $_GET['UUID'];
                $this->datasend['DtWD_ByUUID']  = $_GET['UUID'];
            }
            
            $this->load->model(["ExtSystemModel", "DocTypeModel"]);
            $this->datasend['DtDocType'] = $this->DocTypeModel->GetDataActiveHead();
            $this->datasend['DtExtSystem'] = $this->ExtSystemModel->GetDataActive();
            $this->datasend['Menu'] = $this->PermissionModel->GetMenu($this->datasend['ROLEACCESS']);
        }

        $this->datasend['sidebar'] = 'sidebar_view';
        $this->datasend['content'] = 'kmk/WithdrawAP';
        $this->load->view('template', $this->datasend);
    }

    public function ApprovalContract() {
        $this->load->model(array("MasterData/General/CompanyModel", "CompanyModel",'KMKModel'));
        $this->datasend['formid'] = '115';
        $this->datasend['ACCESS'] = $this->PermissionModel->GetAccessMenu($this->datasend['ROLEACCESS'], $this->datasend['formid']);
        // echo "string";
        // var_dump($this->datasend);exit();
        $this->datasend['DtCompany'] = $this->CompanyModel->GetDataActive();
        $this->datasend['DtCurrency'] = $this->PermissionModel->BMCodeDetail("000001");
        $this->datasend['DtDepartment'] = $this->PermissionModel->GetDtDepart($this->datasend['SESSION']->FCCODE);
        $this->datasend['DtKmk'] = [];
        $this->datasend['DtDetail'] = [];
        $this->datasend['DtWD_ByUUID']  = [];
        if ($this->datasend['ACCESS']['VIEWS'] != 1) {
            redirect('/');
        }
        // if (isset($_GET['type'])) {
        //     if ($_GET['type'] == 'edit') {
        //         if (!isset($_GET['UUID'])) {
        //             echo '<script>
        //                      alert("ID Not Found !!!");
        //                      window.location.href = window.location.href.split("?")[0];
        //                   </script>';
        //         }
        //         $this->datasend['DtKmk'] = $this->KMKModel->GetDataWAAP(["UUID" => $_GET['UUID']]);
        //         // var_dump($this->db->last_query());exit;
        //         $this->datasend['DtUpload']  = $_GET['UUID'];
        //         $this->datasend['DtWD_ByUUID']  = $_GET['UUID'];
        //     }
            
        //     $this->load->model(["ExtSystemModel", "DocTypeModel"]);
        //     $this->datasend['DtDocType'] = $this->DocTypeModel->GetDataActiveHead();
        //     $this->datasend['DtExtSystem'] = $this->ExtSystemModel->GetDataActive();
        //     $this->datasend['Menu'] = $this->PermissionModel->GetMenu($this->datasend['ROLEACCESS']);
        // }

        $this->datasend['sidebar'] = 'sidebar_view';
        $this->datasend['content'] = 'kmk/ApprovalContract';
        $this->load->view('template', $this->datasend);
    }

    public function ApprovalWithdrawal() {
        $this->load->model(array("MasterData/General/CompanyModel", "CompanyModel",'KMKModel'));
        $this->datasend['formid'] = '116';
        $this->datasend['ACCESS'] = $this->PermissionModel->GetAccessMenu($this->datasend['ROLEACCESS'], $this->datasend['formid']);
        // echo "string";
        // var_dump($this->datasend);exit();
        $this->datasend['DtCompany'] = $this->CompanyModel->GetDataActive();
        $this->datasend['DtCurrency'] = $this->PermissionModel->BMCodeDetail("000001");
        $this->datasend['DtDepartment'] = $this->PermissionModel->GetDtDepart($this->datasend['SESSION']->FCCODE);
        $this->datasend['DtKmk'] = [];
        $this->datasend['DtDetail'] = [];
        $this->datasend['DtWD_ByUUID']  = [];
        if ($this->datasend['ACCESS']['VIEWS'] != 1) {
            redirect('/');
        }
        // if (isset($_GET['type'])) {
        //     if ($_GET['type'] == 'edit') {
        //         if (!isset($_GET['UUID'])) {
        //             echo '<script>
        //                      alert("ID Not Found !!!");
        //                      window.location.href = window.location.href.split("?")[0];
        //                   </script>';
        //         }
        //         $this->datasend['DtKmk'] = $this->KMKModel->GetDataWAAP(["UUID" => $_GET['UUID']]);
        //         // var_dump($this->db->last_query());exit;
        //         $this->datasend['DtUpload']  = $_GET['UUID'];
        //         $this->datasend['DtWD_ByUUID']  = $_GET['UUID'];
        //     }
            
        //     $this->load->model(["ExtSystemModel", "DocTypeModel"]);
        //     $this->datasend['DtDocType'] = $this->DocTypeModel->GetDataActiveHead();
        //     $this->datasend['DtExtSystem'] = $this->ExtSystemModel->GetDataActive();
        //     $this->datasend['Menu'] = $this->PermissionModel->GetMenu($this->datasend['ROLEACCESS']);
        // }

        $this->datasend['sidebar'] = 'sidebar_view';
        $this->datasend['content'] = 'kmk/ApprovalWithdrawal';
        $this->load->view('template', $this->datasend);
    }

    public function WithdrawDataAP() {
        $this->load->model(array("MasterData/General/CompanyModel", "CompanyModel",'KMKModel'));
        $this->datasend['formid'] = '112';
        $this->datasend['ACCESS'] = $this->PermissionModel->GetAccessMenu($this->datasend['ROLEACCESS'], $this->datasend['formid']);
        // echo "string";
        // var_dump($this->datasend);exit();
        $this->datasend['DtCompany'] = $this->CompanyModel->GetDataActive();
        $this->datasend['DtCurrency'] = $this->PermissionModel->BMCodeDetail("000001");
        $this->datasend['DtDepartment'] = $this->PermissionModel->GetDtDepart($this->datasend['SESSION']->FCCODE);
        $this->datasend['DtKmk'] = [];
        $this->datasend['DtDetail'] = [];
        if ($this->datasend['ACCESS']['VIEWS'] != 1) {
            redirect('/');
        }
        if (isset($_GET['type'])) {
            if ($_GET['type'] == 'edit') {
                if (!isset($_GET['UUID'])) {
                    echo '<script>
                             alert("ID Not Found !!!");
                             window.location.href = window.location.href.split("?")[0];
                          </script>';
                }
                $this->datasend['DtKmk'] = $this->KMKModel->GetDataWDAPARbyID(["UUID" => $_GET['UUID'],'IDDET'=> $_GET['IDDET'], 'IDDETAP' => $_GET['IDDETAP'], 'WD_TYPE'=>'KMK_SCF_AP']);
                // var_dump($this->db->last_query());exit;
                $this->datasend['DtUpload']  = $_GET['UUID'];
            }
            
            $this->load->model(["ExtSystemModel", "DocTypeModel"]);
            $this->datasend['DtDocType'] = $this->DocTypeModel->GetDataActiveHead();
            $this->datasend['DtExtSystem'] = $this->ExtSystemModel->GetDataActive();
            $this->datasend['Menu'] = $this->PermissionModel->GetMenu($this->datasend['ROLEACCESS']);
        }

        $this->datasend['sidebar'] = 'sidebar_view';
        $this->datasend['content'] = 'kmk/WithdrawDataAP';
        $this->load->view('template', $this->datasend);
    }

    public function WithdrawDataNDK() {
        $this->load->model(array("MasterData/General/CompanyModel", "CompanyModel",'KMKModel'));
        $this->datasend['formid'] = '112';
        $this->datasend['ACCESS'] = $this->PermissionModel->GetAccessMenu($this->datasend['ROLEACCESS'], $this->datasend['formid']);
        // echo "string";
        // var_dump($this->datasend);exit();
        $this->datasend['DtCompany'] = $this->CompanyModel->GetDataActive();
        $this->datasend['DtCurrency'] = $this->PermissionModel->BMCodeDetail("000001");
        $this->datasend['DtDepartment'] = $this->PermissionModel->GetDtDepart($this->datasend['SESSION']->FCCODE);
        $this->datasend['DtKmk'] = [];
        $this->datasend['DtDetail'] = [];
        if ($this->datasend['ACCESS']['VIEWS'] != 1) {
            redirect('/');
        }
        if (isset($_GET['type'])) {
            if ($_GET['type'] == 'edit') {
                if (!isset($_GET['UUID'])) {
                    echo '<script>
                             alert("ID Not Found !!!");
                             window.location.href = window.location.href.split("?")[0];
                          </script>';
                }
                $this->datasend['DtKmk'] = $this->KMKModel->GetDataWDNDKbyID(["UUID" => $_GET['UUID'],'IDDET' => $_GET['IDDET']]);
                // var_dump($this->db->last_query());exit;
                $this->datasend['DtUpload']  = $_GET['UUID'];
            }
            
            $this->load->model(["ExtSystemModel", "DocTypeModel"]);
            $this->datasend['DtDocType'] = $this->DocTypeModel->GetDataActiveHead();
            $this->datasend['DtExtSystem'] = $this->ExtSystemModel->GetDataActive();
            $this->datasend['Menu'] = $this->PermissionModel->GetMenu($this->datasend['ROLEACCESS']);
        }

        $this->datasend['sidebar'] = 'sidebar_view';
        $this->datasend['content'] = 'kmk/WithdrawDataNDK';
        $this->load->view('template', $this->datasend);
    }

    public function WithdrawDataAR() {
        $this->load->model(array("MasterData/General/CompanyModel", "CompanyModel",'KMKModel'));
        $this->datasend['formid'] = '112';
        $this->datasend['ACCESS'] = $this->PermissionModel->GetAccessMenu($this->datasend['ROLEACCESS'], $this->datasend['formid']);
        // echo "string";
        // var_dump($this->datasend);exit();
        $this->datasend['DtCompany'] = $this->CompanyModel->GetDataActive();
        $this->datasend['DtCurrency'] = $this->PermissionModel->BMCodeDetail("000001");
        $this->datasend['DtDepartment'] = $this->PermissionModel->GetDtDepart($this->datasend['SESSION']->FCCODE);
        $this->datasend['DtKmk'] = [];
        $this->datasend['DtDetail'] = [];
        if ($this->datasend['ACCESS']['VIEWS'] != 1) {
            redirect('/');
        }
        if (isset($_GET['type'])) {
            if ($_GET['type'] == 'edit') {
                if (!isset($_GET['UUID'])) {
                    echo '<script>
                             alert("ID Not Found !!!");
                             window.location.href = window.location.href.split("?")[0];
                          </script>';
                }
                $this->datasend['DtKmk'] = $this->KMKModel->GetDataWDAPARbyID(["UUID" => $_GET['UUID'],"IDDET" => $_GET['IDDET'], 'IDDETAP' => $_GET['IDDETAP'], 'WD_TYPE' => 'KMK_SCF_AR']);
                // var_dump($this->db->last_query());exit;
                $this->datasend['DtUpload']  = $_GET['UUID'];
            }
            
            $this->load->model(["ExtSystemModel", "DocTypeModel"]);
            $this->datasend['DtDocType'] = $this->DocTypeModel->GetDataActiveHead();
            $this->datasend['DtExtSystem'] = $this->ExtSystemModel->GetDataActive();
            $this->datasend['Menu'] = $this->PermissionModel->GetMenu($this->datasend['ROLEACCESS']);
        }

        $this->datasend['sidebar'] = 'sidebar_view';
        $this->datasend['content'] = 'kmk/WithdrawDataAR';
        $this->load->view('template', $this->datasend);
    }

    public function PaymentRequest() {
        $this->datasend['formid'] = '118';
        $this->datasend['ACCESS'] = $this->PermissionModel->GetAccessMenu($this->datasend['ROLEACCESS'], $this->datasend['formid']);
        if ($this->datasend['ACCESS']['VIEWS'] != 1) {
            redirect('/');
        }
        // $this->datasend['DtLeasing'] = [];
        $this->datasend['Menu'] = $this->PermissionModel->GetMenu($this->datasend['ROLEACCESS']);
        $this->load->model('MasterData/General/CompanyModel');
        $this->datasend['DtCompany'] = $this->CompanyModel->GetDataActive();
        // $this->datasend['DtDepartment'] = $this->PermissionModel->GetDtDepart($this->datasend['SESSION']->FCCODE);
        $this->datasend['DtCurrency'] = $this->PermissionModel->BMCodeDetail('000001');

        $this->datasend['sidebar'] = 'sidebar_view';
        $this->datasend['content'] = 'kmk/PaymentRequest';
        $this->load->view('template', $this->datasend);
    }

    public function Completion() {
        $this->datasend['formid'] = '123';
        $this->datasend['ACCESS'] = $this->PermissionModel->GetAccessMenu($this->datasend['ROLEACCESS'], $this->datasend['formid']);
        if ($this->datasend['ACCESS']['VIEWS'] != 1) {
            redirect('/');
        }
        $this->datasend['Menu'] = $this->PermissionModel->GetMenu($this->datasend['ROLEACCESS']);
        $this->load->model('MasterData/General/CompanyModel');
        $this->datasend['DtCompany'] = $this->CompanyModel->GetDataActive();
        $this->datasend['DtCurrency'] = $this->PermissionModel->BMCodeDetail('000001');

        $this->datasend['sidebar'] = 'sidebar_view';
        $this->datasend['content'] = 'kmk/Completion';
        $this->load->view('template', $this->datasend);
    }

    public function ReportFacility() {
        $this->datasend['formid'] = '120';
        $this->datasend['ACCESS'] = $this->PermissionModel->GetAccessMenu($this->datasend['ROLEACCESS'], $this->datasend['formid']);
        if ($this->datasend['ACCESS']['VIEWS'] != 1) {
            redirect('/');
        }
        // $this->datasend['DtLeasing'] = [];
        $this->datasend['Menu'] = $this->PermissionModel->GetMenu($this->datasend['ROLEACCESS']);
        $this->load->model('MasterData/General/CompanyModel');
        $this->datasend['DtCompany'] = $this->CompanyModel->GetDataActive();
        // $this->datasend['DtDepartment'] = $this->PermissionModel->GetDtDepart($this->datasend['SESSION']->FCCODE);
        $this->datasend['DtCurrency'] = $this->PermissionModel->BMCodeDetail('000001');

        $this->datasend['sidebar'] = 'sidebar_view';
        $this->datasend['content'] = 'kmk/ReportFacility';
        $this->load->view('template', $this->datasend);
    }

    public function WeeklyReport() {
        $this->datasend['formid'] = '121';
        $this->datasend['ACCESS'] = $this->PermissionModel->GetAccessMenu($this->datasend['ROLEACCESS'], $this->datasend['formid']);
        if ($this->datasend['ACCESS']['VIEWS'] != 1) {
            redirect('/');
        }
        // $this->datasend['DtLeasing'] = [];
        $this->datasend['Menu'] = $this->PermissionModel->GetMenu($this->datasend['ROLEACCESS']);
        $this->load->model(array('MasterData/General/CompanyModel', 'MasterData/General/BankModel'));
        $this->datasend['DtCompany'] = $this->CompanyModel->GetDataActive();
        $this->datasend['DtBank'] = $this->BankModel->GetDataActive();
        // $this->datasend['DtDepartment'] = $this->PermissionModel->GetDtDepart($this->datasend['SESSION']->FCCODE);
        $this->datasend['DtCurrency'] = $this->PermissionModel->BMCodeDetail('000001');

        $this->datasend['sidebar'] = 'sidebar_view';
        $this->datasend['content'] = 'kmk/WeeklyReport';
        $this->load->view('template', $this->datasend);
    }

    public function RecapReport() {
        $this->datasend['formid'] = '122';
        $this->datasend['ACCESS'] = $this->PermissionModel->GetAccessMenu($this->datasend['ROLEACCESS'], $this->datasend['formid']);
        if ($this->datasend['ACCESS']['VIEWS'] != 1) {
            redirect('/');
        }
        // $this->datasend['DtLeasing'] = [];
        $this->datasend['Menu'] = $this->PermissionModel->GetMenu($this->datasend['ROLEACCESS']);
        $this->load->model(array('MasterData/General/CompanyModel', 'MasterData/General/BankModel'));
        $this->datasend['DtCompany'] = $this->CompanyModel->GetDataActive();
        $this->datasend['DtBank'] = $this->BankModel->GetDataActive();
        // $this->datasend['DtDepartment'] = $this->PermissionModel->GetDtDepart($this->datasend['SESSION']->FCCODE);
        $this->datasend['DtCurrency'] = $this->PermissionModel->BMCodeDetail('000001');

        $this->datasend['sidebar'] = 'sidebar_view';
        $this->datasend['content'] = 'kmk/RecapReport';
        $this->load->view('template', $this->datasend);
    }

    public function KMKKIPeriodControl() {
        $this->datasend['formid'] = '124';
        $this->datasend['ACCESS'] = $this->PermissionModel->GetAccessMenu($this->datasend['ROLEACCESS'], $this->datasend['formid']);
        if ($this->datasend['ACCESS']['VIEWS'] != 1) {
            redirect('/');
        }
        // $this->datasend['DtLeasing'] = [];
        $this->datasend['Menu'] = $this->PermissionModel->GetMenu($this->datasend['ROLEACCESS']);
        $this->load->model('MasterData/General/CompanyModel');
        $this->datasend['DtCompany'] = $this->CompanyModel->GetDataActive();
        $this->datasend['DtCurrency'] = $this->PermissionModel->BMCodeDetail('000001');

        $this->datasend['sidebar'] = 'sidebar_view';
        $this->datasend['content'] = 'kmk/KMKKIPeriodControl';
        $this->load->view('template', $this->datasend);
    }
//  --- Cashflow End ---- 

/* API TRANSACTIONS */
    public function APIcfTrans() {
        $this->datasend['formid'] = '61';
        $this->datasend['ACCESS'] = $this->PermissionModel->GetAccessMenu($this->datasend['ROLEACCESS'], $this->datasend['formid']);

        if ($this->datasend['ACCESS']['VIEWS'] != 1) {
            redirect('/');
        }

        $this->datasend['sidebar'] = 'sidebar_view';
        $this->datasend['content'] = 'API/cfTransactions';
        $this->load->view('template', $this->datasend);
    }

}
