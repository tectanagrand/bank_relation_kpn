<?php

defined('BASEPATH') OR exit('No direct script access allowed');

$route['default_controller'] = 'URLController';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;
// $route['Cron/(:any)'] = 'CronController/$1';
// Master Data
$route['IUsers/(:any)']['post'] = 'MasterData/MstUserController/$1';
$route['IPermission/(:any)']['post'] = 'MasterData/PermissionController/$1';
$route['UserCompany/(:any)'] = 'MasterData/UserCompanyController/$1';
// MD -> Genearl
$route['IDepartement/(:any)']['post'] = 'MasterData/General/MstDepartementController/$1';
$route['ICompany/(:any)']['post'] = 'MasterData/General/MstCompanyController/$1';
$route['IRegional/(:any)']['post'] = 'MasterData/General/RegionalController/$1';
$route['IRegionalGroup/(:any)']['post'] = 'MasterData/General/RegionalGroupController/$1';
$route['MstDeptPurch/(:any)'] = 'MasterData/MstDeptPurchController/$1';
$route['MstDeptMaterial/(:any)'] = 'MasterData/MstDeptMaterialController/$1';
$route['Staging/(:any)'] = 'MasterData/StagingController/$1';

// Cashflow
$route['Forecast/(:any)']['post'] = 'Cashflow/ForecastController/$1';
$route['OpenForecast/(:any)']['post'] = 'Cashflow/OpenForecastController/$1';
$route['ForecastAuth/(:any)']['post'] = 'Cashflow/ForecastAuthController/$1';
$route['Invoice/(:any)']['post'] = 'Cashflow/InvoiceController/$1';
$route['Payment/(:any)']['post'] = 'Cashflow/PaymentController/$1';
$route['IntercoLoans/(:any)']['post'] = 'Cashflow/IntercoLoansController/$1';
$route['Budget/(:any)']['post'] = 'Cashflow/DepartBudgetController/$1';
$route['ReportFtP/(:any)']['post'] = 'Cashflow/ReportFtPController/$1';

$route['IBank/(:any)']['post'] = 'MasterData/General/MstBankController/$1';
$route['TracingDocument/(:any)']='Cashflow/TracingDocumentController/$1';
$route['Leasing/(:any)'] = 'Cashflow/LeasingController/$1';
$route['Elog/(:any)'] = 'Cashflow/ElogController/$1';
$route['Znego/(:any)'] = 'Cashflow/ZnegoController/$1';
$route['Kmk/(:any)'] = 'Cashflow/KmkController/$1';
$route['Completion/(:any)'] = 'Cashflow/CompletionController/$1';
$route['OData/(:any)'] = 'Cashflow/ODataController/$1';
//Report
$route['Report/(:any)']['post'] = 'Report/ReportsController/$1';

//API
$route['Api/(:any)']['post'] = 'API/ApiController/$1';
$route['AP/(:any)']= 'APController/$1';

$route['ReportBankBalance/(:any)']['post'] = 'Report/ReportBankBalanceController/$1';
$route['Cashflow/(:any)']['post'] = 'Cashflow/CashflowController/$1';
$route['otherReport/(:any)']['post'] = 'Report/OtherReportController/$1';

$route['Holiday/(:any)'] = 'Setting/HolidayController/$1';
$route['IExtSystem/(:any)']['post'] = 'Setting/ExtSystemController/$1';
$route['IVendor/(:any)']['post'] = 'MasterData/MstVendorController/$1';
$route['IDocTemplate/(:any)']['post'] = 'Setting/DocTemplateController/$1';
$route['IDocType/(:any)']['post'] = 'Setting/DocTypeController/$1';
$route['IMaterial/(:any)']['post'] = 'MasterData/Material/MstMaterialController/$1';
$route['IMaterialSubGroup/(:any)']['post'] = 'MasterData/Material/MstMaterialSubGroupController/$1';
$route['IMaterialGroup/(:any)']['post'] = 'MasterData/Material/MaterialGroupController/$1';
$route['IMaterialUpload/(:any)']['post'] = 'MasterData/Material/MaterialUploadController/$1';
$route['IBusinessUnit/(:any)']['post'] = 'MasterData/MstBusinessUnitController/$1';
$route['IForecastCat/(:any)']['post'] = 'MasterData/MstForecastCatController/$1';
$route['ICompanyGroup/(:any)']['post'] = 'MasterData/CompanyGroupController/$1';
$route['ICompanySubGroup/(:any)']['post'] = 'MasterData/CompanySubGroupController/$1';
$route['Upload/(:any)']['post'] = 'Cashflow/UploadDocController/$1';
$route['Cash/(:any)'] = 'Cashflow/CashController/$1';
$route['EntryDoc/(:any)']['post'] = 'Cashflow/EntryDocController/$1';
$route['EntryPO/(:any)']['post'] = 'Cashflow/EntryPOController/$1';
$route['TempCash/(:any)']['post'] = 'Cashflow/TempCashController/$1';
$route['IWeek/(:any)']['post'] = 'Setting/WeekController/$1';
$route['IKurs/(:any)']['post'] = 'Setting/KursController/$1';
$route['login'] = 'Login/index';
$route['login/(:any)']['post'] = 'Login/$1';
$route['(:any)'] = 'URLController/$1';

$route['ReportFacility/(:any)'] = 'ReportFacilityController/$1';
$route['WeeklyReport/(:any)'] = 'ReportFacilityController/$1';
?>