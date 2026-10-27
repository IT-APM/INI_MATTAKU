<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Session;
// 
use App\Http\Controllers\KaryawanController;
use App\Http\Controllers\KaryawanCopyController;
use App\Http\Controllers\KaryawanPendidikanController;
use App\Http\Controllers\KaryawanPelatihanController;
use App\Http\Controllers\KaryawanSertifikasiController;
use App\Http\Controllers\KaryawanAnakController;
use App\Http\Controllers\KaryawanPerubahanController;
use App\Http\Controllers\PerusahaanController;
use App\Http\Controllers\MtrJoinController;
use App\Http\Controllers\DivisiController;
use App\Http\Controllers\DepartemenController;
use App\Http\Controllers\SubDepartemenController;
use App\Http\Controllers\FeedController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\AttendanceDownloadController;
use App\Http\Controllers\RecapAttendanceController;
use App\Http\Controllers\ReminderSpController;
use App\Http\Controllers\TrxRecruitmentController;
use App\Http\Controllers\TrxPayrollController;
use App\Http\Controllers\NoAttendanceController;
use App\Http\Controllers\OvertimeController;
use App\Http\Controllers\WorkingDayController;
use App\Http\Controllers\WorkingGroupController;
// 
use App\Http\Controllers\MtrPenilaian1Controller;
use App\Http\Controllers\MtrPenilaian2Controller;
use App\Http\Controllers\MtrPenilaian3Controller;
use App\Http\Controllers\MtrPenilaianStatusController;
use App\Http\Controllers\MtrPenilaianTipeController;
use App\Http\Controllers\TrxPenilaianController;
use App\Http\Controllers\TrxPenilaianAutoController;
use App\Http\Controllers\TrxPenilaianReminderController;
use App\Http\Controllers\TrxPenilaianLogController;
use App\Http\Controllers\TrxPenilaianLogHistoryController;
use App\Http\Controllers\TrxPenilaianSkorController;
// 
use App\Http\Controllers\TrxCutiController;
use App\Http\Controllers\TrxCutiLogController;
use App\Http\Controllers\TrxSakitController;
// 
use App\Http\Controllers\TrxReimburseController;
use App\Http\Controllers\TrxReimburseDetailController;
use App\Http\Controllers\ReimbursePlafonController;
// 
use App\Http\Controllers\TrxRekomendasiController;
// 
use App\Http\Controllers\TrxKebutuhanController;
use App\Http\Controllers\TrxKebutuhanDetailController;
// 
use App\Http\Controllers\TrxPelatihanController;
use App\Http\Controllers\TrxPelatihanSertifikatController;
// 
use App\Http\Controllers\TrxNonaktifController;
use App\Http\Controllers\TrxNonaktifAutoController;
// 
use App\Http\Controllers\TrxIjinController;
// 
use App\Http\Controllers\PekerjaanBidangController;
use App\Http\Controllers\PekerjaanKategoriController;
use App\Http\Controllers\PekerjaanMasterController;
use App\Http\Controllers\PekerjaanDetailController;
// 
use App\Http\Controllers\PelatihanMasterController;
use App\Http\Controllers\SertifikasiMasterController;
use App\Http\Controllers\KeterampilanMasterController;
use App\Http\Controllers\KepribadianMasterController;
// 
use App\Http\Controllers\JabatanController;
use App\Http\Controllers\HolidayController;
use App\Http\Controllers\BannerController;
use App\Http\Controllers\IphoneUserController;
use App\Http\Controllers\HcoUserController;
use App\Http\Controllers\UtilitasController;
// 
use App\Http\Controllers\TrxToDoListController;
// 
use App\Http\Controllers\TrxAbsensiKesehatanController;
use App\Http\Controllers\TrxKesehatanReportingController;
// 
use App\Http\Controllers\TrxCiCoAttendanceController;
use App\Http\Controllers\TrxCiCoOvertimeController;
// 
use App\Http\Controllers\LoginController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\DataCleansingController;
// 
use App\Http\Controllers\JadwalClsController;
use App\Http\Controllers\JadwalCls2Controller;
use App\Http\Controllers\JadwalCls3Controller;
use App\Http\Controllers\JadwalNocController;
use App\Http\Controllers\JadwalNoc2Controller;
use App\Http\Controllers\JadwalSecController;
use App\Http\Controllers\JadwalQatsMemberController;
use App\Http\Controllers\JadwalServiceDeskController;
use App\Http\Controllers\JadwalQatsLeaderController;
use App\Http\Controllers\JadwalQatsLeader2Controller;
use App\Http\Controllers\JadwalQatsController;
use App\Http\Controllers\JadwalRandomController;
// 
use App\Http\Controllers\TrxBpdController;
use App\Http\Controllers\MtrDataBpdController;
use App\Http\Controllers\MtrDataBpdCoordinatorController;
use App\Http\Controllers\MtrDataBpdBodController;
use App\Http\Controllers\MtrDataBpdCoaController;
use App\Http\Controllers\MtrDataBpdProjectAdminController;
use App\Http\Controllers\MtrDataBpdProjectAdminPicController;
// 
use App\Http\Controllers\OpenDataController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LocalizationController;
// 
use App\Http\Controllers\TrxFindimProjectController;
use App\Http\Controllers\TrxFindimDivisiController;
use App\Http\Controllers\TrxFindimDepartemenController;
use App\Http\Controllers\TrxFindimSubdepartemenController;
// 
use App\Http\Controllers\TrxPdpController;
// 
use App\Http\Controllers\MtrStrukturGajiController;
// 
use App\Http\Controllers\TrxBirthdayController;
// 
Route::resource('birthday', TrxBirthdayController::class)->middleware('can:hc');
Route::post('birthday/send_email_exe', [TrxBirthdayController::class, 'send_email_exe'])->name('birthday.send_email_exe')->middleware('can:hc');
Route::get('birthday/scheduler/{id}', [TrxBirthdayController::class, 'scheduler'])->name('scheduler');
// 
Route::resource('findim_project', TrxFindimProjectController::class)->middleware('can:hc');
Route::resource('findim_divisi', TrxFindimDivisiController::class)->middleware('can:hc');
Route::resource('findim_departemen', TrxFindimDepartemenController::class)->middleware('can:hc');
Route::resource('findim_subdepartemen', TrxFindimSubdepartemenController::class)->middleware('can:hc');
// 
Route::get('getKaryawanPerusahaan/{id}', [OpenDataController::class, 'getKaryawanPerusahaan'])->name('getKaryawanPerusahaan');
Route::get('getDivisi/{id}', [OpenDataController::class, 'getDivisi'])->name('getDivisi');
Route::get('getDepartemen/{id}', [OpenDataController::class, 'getDepartemen'])->name('getDepartemen');
Route::get('getSubDepartemen/{id}', [OpenDataController::class, 'getSubDepartemen'])->name('getSubDepartemen');
Route::get('getDivisi', [OpenDataController::class, 'getDivisi'])->name('getDivisi');
Route::get('getDepartemen', [OpenDataController::class, 'getDepartemen'])->name('getDepartemen');
Route::get('getSubDepartemen', [OpenDataController::class, 'getSubDepartemen'])->name('getSubDepartemen');
Route::get('get_pekerjaan/{id}', [OpenDataController::class, 'get_pekerjaan'])->name('get_pekerjaan');
Route::get('get_pelatihan/{id}', [OpenDataController::class, 'get_pelatihan'])->name('get_pelatihan');
Route::get('get_sertifikasi/{id}', [OpenDataController::class, 'get_sertifikasi'])->name('get_sertifikasi');
Route::get('get_keterampilan/{id}', [OpenDataController::class, 'get_keterampilan'])->name('get_keterampilan');
Route::get('get_kepribadian/{id}', [OpenDataController::class, 'get_kepribadian'])->name('get_kepribadian');
// Route::get('encrypt_link', [OpenDataController::class, 'encrypt_link'])->name('encrypt_link');
Route::get('get_propinsi_kota/{id}', [OpenDataController::class, 'get_propinsi_kota'])->name('get_propinsi_kota');
// 
Route::get('payroll/get_divisi/{id}', [DashboardController::class, 'get_divisi'])->name('payroll.get_divisi');
Route::get('payroll/get_departemen/{id}', [DashboardController::class, 'get_departemen'])->name('payroll.get_departemen');
Route::get('payroll/get_subdepartemen/{id}', [DashboardController::class, 'get_subdepartemen'])->name('payroll.get_subdepartemen');
// 
Route::resource('jadwalcls', JadwalClsController::class)->middleware('can:hc');
Route::get('generate_cls', [JadwalClsController::class, 'generate'])->name('generate_cls')->middleware('can:hc');
Route::resource('jadwalcls2', JadwalCls2Controller::class)->middleware('can:hc');
Route::get('generate_cls2', [JadwalCls2Controller::class, 'generate'])->name('generate_cls2')->middleware('can:hc');
Route::resource('jadwalcls3', JadwalCls3Controller::class)->middleware('can:hc');
Route::get('generate_cls3', [JadwalCls3Controller::class, 'generate'])->name('generate_cls3')->middleware('can:hc');
Route::resource('jadwalnoc', JadwalNocController::class)->middleware('can:hc');
Route::get('generate_noc', [JadwalNocController::class, 'generate'])->name('generate_noc')->middleware('can:hc');
Route::resource('jadwalnoc2', JadwalNoc2Controller::class)->middleware('can:hc');
Route::get('generate_noc2', [JadwalNoc2Controller::class, 'generate'])->name('generate_noc2')->middleware('can:hc');
Route::resource('jadwalsec', JadwalSecController::class)->middleware('can:hc');
Route::get('generate_sec', [JadwalSecController::class, 'generate'])->name('generate_sec')->middleware('can:hc');
Route::resource('jadwalqatsmember', JadwalQatsMemberController::class)->middleware('can:hc');
Route::get('generate_qatsmember', [JadwalQatsMemberController::class, 'generate'])->name('generate_qatsmember')->middleware('can:hc');
Route::resource('jadwalservicedesk', JadwalServiceDeskController::class)->middleware('can:hc');
Route::get('generate_servicedesk', [JadwalServiceDeskController::class, 'generate'])->name('generate_servicedesk')->middleware('can:hc');
Route::resource('jadwalqatsleader', JadwalQatsLeaderController::class)->middleware('can:hc');
Route::get('generate_qatsleader', [JadwalQatsLeaderController::class, 'generate'])->name('generate_qatsleader')->middleware('can:hc');
Route::resource('jadwalqatsleader2', JadwalQatsLeader2Controller::class)->middleware('can:hc');
Route::get('generate_qatsleader2', [JadwalQatsLeader2Controller::class, 'generate'])->name('generate_qatsleader2')->middleware('can:hc');
Route::resource('jadwalqats', JadwalQatsController::class)->middleware('can:hc');
Route::get('generate_qats', [JadwalQatsController::class, 'generate'])->name('generate_qats')->middleware('can:hc');
Route::resource('jadwalrandom', JadwalRandomController::class)->middleware('can:hc');
Route::get('generate_random', [JadwalRandomController::class, 'generate'])->name('generate_random')->middleware('can:hc');
// 
Route::resource('dc', DataCleansingController::class);
Route::get('dc/todolist/{id}', [DataCleansingController::class, 'CleansingToDoList']);
// 
Route::resource('notif', NotificationController::class);
Route::get('notif_cico_reg', [NotificationController::class, 'notif_cico_reg'])->name('notif_cico_reg');
Route::get('notif_cico_ot', [NotificationController::class, 'notif_cico_ot'])->name('notif_cico_ot');
Route::get('notif_approval_ot', [NotificationController::class, 'notif_approval_ot'])->name('notif_approval_ot');
Route::get('notif_formulir', [NotificationController::class, 'notif_formulir'])->name('notif_formulir');
// 
// Route::resource('login', LoginController::class);
Route::post('loginapp', [LoginController::class, 'authenticate'])->name('loginapp');
Route::post('resetpwd', [LoginController::class, 'resetpwd'])->name('resetpwd');
Route::get('resetview', [LoginController::class, 'resetview'])->name('resetview');
Route::post('resetaction', [LoginController::class, 'resetaction'])->name('resetaction');
Route::post('logoutapp', [LoginController::class, 'logout'])->name('logoutapp');
// Route::post('loginindex', [LoginController::class, 'index'])->name('loginindex');
// Route::resource('loginctrl', LoginController::class);
// 
Route::resource('todolist', TrxToDoListController::class)->middleware('auth');
Route::get('todolist/recap/{id}', [TrxToDoListController::class, 'recap'])->name('todolist.recap')->middleware('can:hc');
Route::get('todolist/recap/detail/{id}', [TrxToDoListController::class, 'detail'])->name('todolist.recap.detail')->middleware('can:hc');
Route::post('survey_pora', [ TrxToDoListController::class, 'survey_pora' ])->name('survey_pora')->middleware('auth');
// Route::get('survey_pora/result/{id}', [ TrxToDoListController::class, 'survey_result' ])->name('survey_pora.result')->middleware('auth');
Route::get('survey_pora/mobile/{id}', [TrxToDoListController::class, 'survey_mobile'])->name('survey_pora.mobile');
Route::post('survey_mobile_post', [ TrxToDoListController::class, 'survey_mobile_post' ])->name('survey_mobile_post');
// 
Route::resource('karyawan', KaryawanController::class);
Route::resource('karyawanperubahan', KaryawanPerubahanController::class);
Route::resource('karyawanpendidikan', KaryawanPendidikanController::class)->middleware('auth');
Route::resource('karyawanpelatihan', KaryawanPelatihanController::class)->middleware('auth');
Route::resource('karyawansertifikasi', KaryawanSertifikasiController::class)->middleware('auth');
Route::resource('karyawananak', KaryawanAnakController::class)->middleware('auth');
Route::resource('karyawancopy', KaryawanCopyController::class)->middleware('can:hc');
Route::post('karyawan/update_profile/{id}', [ KaryawanController::class, 'update_profile' ])->name('karyawan.update_profile');
Route::get('karyawan/dashboard/{id}', [KaryawanController::class, 'dashboard'])->name('karyawan.dashboard')->middleware('can:hc');
// 
Route::get('karyawan/get_divisi/{id}', [KaryawanController::class, 'get_divisi'])->name('karyawan.get_divisi');
Route::get('karyawan/get_divisi', [KaryawanController::class, 'get_divisi'])->name('get_divisi');
Route::get('karyawan/get_departemen/{id}', [KaryawanController::class, 'get_departemen'])->name('karyawan.get_departemen');
Route::get('karyawan/get_departemen', [KaryawanController::class, 'get_departemen'])->name('get_departemen');
Route::get('karyawan/get_sub_departemen/{id}', [KaryawanController::class, 'get_sub_departemen'])->name('karyawan.get_sub_departemen');
Route::get('karyawan/get_sub_departemen', [KaryawanController::class, 'get_sub_departemen'])->name('get_sub_departemen');
Route::post('karyawan/image/store', [ KaryawanController::class, 'ImageUploadStore' ])->name('upload.image.store');
Route::get('karyawan/upload_data/{id}', [KaryawanController::class, 'upload_data'])->name('karyawan.upload_data')->middleware('can:hc');
Route::get('karyawan/download_allphoto/{id}', [KaryawanController::class, 'download_allphoto'])->name('karyawan.download_allphoto')->middleware('can:hc');
Route::get('karyawan/bulanan/{id}', [KaryawanController::class, 'bulanan'])->name('karyawan.bulanan')->middleware('can:hc');
Route::post('karyawan/bulanan_update', [KaryawanController::class, 'bulanan_update'])->name('karyawan.bulanan_update')->middleware('can:hc');
Route::post('karyawan/upload_data/xlsx/{id}', [KaryawanController::class, 'upload_data_xlsx'])->name('karyawan.upload_data_xlsx')->middleware('can:hc');
// 
Route::resource('perusahaan', PerusahaanController::class)->middleware('can:hc');
Route::resource('divisi', DivisiController::class)->middleware('can:hc');
Route::resource('departemen', DepartemenController::class)->middleware('can:hc');
Route::resource('subdepartemen', SubDepartemenController::class)->middleware('can:hc');
Route::resource('join', MtrJoinController::class)->middleware('can:hc');
// 
Route::resource('jabatan', JabatanController::class)->middleware('can:hc');
Route::resource('holiday', HolidayController::class)->middleware('can:hc');
Route::resource('banner', BannerController::class)->middleware('can:hc');
Route::resource('iphoneuser', IphoneUserController::class)->middleware('can:hc');
Route::resource('hcouser', HcoUserController::class)->middleware('can:hc');
Route::resource('utilitas', UtilitasController::class)->middleware('can:hc');
Route::post('utilitas/update_sp/{id}', [ UtilitasController::class, 'update_sp'])->name('utilitas.update_sp')->middleware('can:hc');
Route::post('utilitas/update_payroll/{id}', [ UtilitasController::class, 'update_payroll'])->name('utilitas.update_payroll')->middleware('can:payroll');
// 
Route::resource('feeds', FeedController::class)->middleware('can:hc');
Route::post('feeds/image/store', [ FeedController::class, 'ImageUploadStore' ])->name('feeds.image.store')->middleware('can:hc');
Route::get('feeds/image/remove', [ FeedController::class, 'RemoveImage'])->name('feeds.image.remove')->middleware('can:hc');
// 
Route::resource('attendance', AttendanceController::class)->middleware('auth');
Route::resource('attendance_download', AttendanceDownloadController::class)->middleware('can:hc');
Route::get('attendance/source/{id}', [AttendanceController::class, 'attendance_source'])->name('attendance.source')->middleware('can:hc');
Route::resource('recap_attendance', RecapAttendanceController::class)->middleware('auth');
Route::resource('reminder_sp', ReminderSpController::class)->middleware('can:hc');
Route::get('reminder_sp/reminder_sp/{id}', [ReminderSpController::class, 'reminder_sp'])->name('reminder_sp.reminder_sp');
// 
Route::resource('payroll', TrxPayrollController::class)->middleware('can:payroll_hco_fin');
Route::get('payroll/master/{id}', [TrxPayrollController::class, 'master'])->name('payroll.master')->middleware('can:payroll');
Route::post('payroll/master/addsub', [TrxPayrollController::class, 'upsert_master'])->name('payroll.upsert_master')->middleware('can:payroll');
Route::post('payroll/upload_payroll', [ TrxPayrollController::class, 'upload_payroll'])->name('payroll.upload_payroll')->middleware('can:payroll_hco_fin');
Route::get('payroll/bkp/{id}', [TrxPayrollController::class, 'bkp'])->name('payroll.bkp');
Route::get('payroll/history/{id}', [TrxPayrollController::class, 'history'])->name('payroll.history')->middleware('can:payroll_hco_fin');
Route::get('payroll/slip/{id}', [TrxPayrollController::class, 'slip'])->name('payroll.slip')->middleware('auth');
Route::get('payroll/slip/detail/{id}', [TrxPayrollController::class, 'slip_detail'])->name('payroll.slip.detail')->middleware('auth');
// Route::get('payroll/surat/upah/{id}', [TrxPayrollController::class, 'surat_upah'])->name('payroll.surat.upah');
Route::get('payroll/employee_gap/{id}', [TrxPayrollController::class, 'employee_gap'])->name('payroll.employee_gap')->middleware('can:payroll');
Route::get('payroll/send_email/{id}', [TrxPayrollController::class, 'send_email'])->name('payroll.send_email')->middleware('can:payroll');
Route::post('payroll/send_email_exe', [TrxPayrollController::class, 'send_email_exe'])->name('payroll.send_email_exe')->middleware('can:payroll');
Route::get('payroll/set_paid_date/{id}', [TrxPayrollController::class, 'set_paid_date'])->name('payroll.set_paid_date');
Route::get('payroll/edit_payroll/{id}', [TrxPayrollController::class, 'edit_payroll'])->name('payroll.edit_payroll')->middleware('can:payroll');
Route::post('payroll/save_edit_payroll', [TrxPayrollController::class, 'save_edit_payroll'])->name('payroll.save_edit_payroll')->middleware('can:payroll');
Route::get('payroll/download_xlsx/{id}', [TrxPayrollController::class, 'download_xlsx'])->name('payroll.download_xlsx')->middleware('can:payroll');
Route::post('payroll/upload_pemberitahuan_upah/{id}', [ TrxPayrollController::class, 'upload_pemberitahuan_upah'])->name('payroll.upload_pemberitahuan_upah')->middleware('can:payroll');
Route::get('payroll/review/{id}', [TrxPayrollController::class, 'review'])->name('payroll.review');
Route::post('payroll/review_post', [ TrxPayrollController::class, 'review_post'])->name('payroll.review_post');
// 
Route::resource('recruitment', TrxRecruitmentController::class)->middleware('can:hc');
Route::get('recruitment/apply/{id}', [TrxRecruitmentController::class, 'apply'])->name('recruitment.apply');
Route::get('recruitment/psychotest/{id}', [TrxRecruitmentController::class, 'psychotest'])->name('recruitment.psychotest');
Route::post('recruitment/psychotest/{id}', [TrxRecruitmentController::class, 'psychotest'])->name('recruitment.psychotest');
Route::post('recruitment/psychotest_file', [ TrxRecruitmentController::class, 'psychotest_file'])->name('recruitment.psychotest_file');
Route::post('recruitment/apply_save', [ TrxRecruitmentController::class, 'apply_save'])->name('recruitment.apply_save');
Route::post('recruitment/applicant_save', [ TrxRecruitmentController::class, 'applicant_save'])->name('recruitment.applicant_save')->middleware('can:hc');
Route::get('recruitment/applicant_list/{id}', [TrxRecruitmentController::class, 'applicant_list'])->name('recruitment.applicant_list')->middleware('auth');
Route::get('recruitment/applicant_item/{id}', [TrxRecruitmentController::class, 'applicant_item'])->name('recruitment.applicant_item')->middleware('can:hc');
Route::get('recruitment/download_applicant/{id}', [TrxRecruitmentController::class, 'download_applicant'])->name('recruitment.download_applicant')->middleware('can:hc');
Route::get('recruitment/download_cv/{id}', [TrxRecruitmentController::class, 'download_cv'])->name('recruitment.download_cv')->middleware('can:hc');
Route::get('recruitment/applicant_process/{id}', [TrxRecruitmentController::class, 'applicant_process'])->name('recruitment.applicant_process')->middleware('can:hc');
Route::get('recruitment/interview_item/{id}', [TrxRecruitmentController::class, 'interview_item'])->name('recruitment.interview_item')->middleware('auth');
Route::post('recruitment/interview_save', [ TrxRecruitmentController::class, 'interview_save'])->name('recruitment.interview_save')->middleware('auth');
Route::get('recruitment/psychotestexcel/{id}', [TrxRecruitmentController::class, 'psychotestexcel'])->name('recruitment.psychotestexcel')->middleware('auth');
Route::post('recruitment/negotiation_send', [ TrxRecruitmentController::class, 'negotiation_send'])->name('recruitment.negotiation_send')->middleware('can:hc');
Route::get('recruitment/negotiation_process/{id}', [TrxRecruitmentController::class, 'negotiation_process'])->name('recruitment.negotiation_process');
Route::post('recruitment/negotiation_save', [ TrxRecruitmentController::class, 'negotiation_save'])->name('recruitment.negotiation_save');
Route::post('recruitment/atasan_save', [ TrxRecruitmentController::class, 'atasan_save'])->name('recruitment.atasan_save');
Route::get('recruitment/upload_dokumen_get/{id}', [TrxRecruitmentController::class, 'upload_dokumen_get'])->name('recruitment.upload_dokumen_get');
Route::post('recruitment/upload_dokumen_post', [ TrxRecruitmentController::class, 'upload_dokumen_post'])->name('recruitment.upload_dokumen_post');
Route::get('recruitment/upload_psikotest/{id}', [TrxRecruitmentController::class, 'upload_psikotest'])->name('recruitment.upload_psikotest')->middleware('auth');
Route::post('recruitment/upload_psikotest_post', [ TrxRecruitmentController::class, 'upload_psikotest_post'])->name('recruitment.upload_psikotest_post')->middleware('auth');
Route::get('recruitment/get_interpretation/{id}', [TrxRecruitmentController::class, 'get_interpretation'])->name('recruitment.get_interpretation')->middleware('can:hc');
Route::get('recruitment/interpretation/download/{id}', [TrxRecruitmentController::class, 'download_interpretation'])->name('recruitment.download_interpretation')->middleware('can:hc');
Route::get('recruitment/get_offering/{id}', [TrxRecruitmentController::class, 'get_offering'])->name('recruitment.get_offering');
Route::get('recruitment/get_offering/download/{id}', [TrxRecruitmentController::class, 'download_offering'])->name('recruitment.download_offering');
Route::post('recruitment/pedoman_post', [ TrxRecruitmentController::class, 'pedoman_post'])->name('recruitment.pedoman_post');
Route::post('recruitment/post_atribute', [TrxRecruitmentController::class, 'post_atribute'])->name('recruitment.post_atribute')->middleware('can:hc');
// 
Route::resource('noattendance', NoAttendanceController::class)->middleware('can:hc');
Route::resource('overtime', OvertimeController::class)->middleware('auth');
Route::get('overtime/dashboard/{id}', [OvertimeController::class, 'dashboard'])->name('overtime.dashboard')->middleware('can:hc');
Route::resource('working', WorkingDayController::class)->middleware('can:hc');
Route::post('working/upload_jadwal/{id}', [WorkingDayController::class, 'upload_jadwal'])->name('working.upload_jadwal')->middleware('can:hc');
Route::resource('wg', WorkingGroupController::class)->middleware('auth');
// 
Route::resource('cico_att', TrxCiCoAttendanceController::class)->middleware('can:iphone');
Route::resource('cico_ot', TrxCiCoOvertimeController::class)->middleware('can:iphone');
// 
Route::resource('mtrpenilaian1', MtrPenilaian1Controller::class)->middleware('auth');
Route::resource('mtrpenilaian2', MtrPenilaian2Controller::class)->middleware('auth');
Route::resource('mtrpenilaian3', MtrPenilaian3Controller::class)->middleware('auth');
Route::resource('mtrpenilaianstatus', MtrPenilaianStatusController::class)->middleware('auth');
Route::resource('mtrpenilaiantipe', MtrPenilaianTipeController::class)->middleware('auth');
Route::resource('penilaian', TrxPenilaianController::class)->middleware('auth');
Route::get('revisi_penilaian/{id}', [ TrxPenilaianController::class, 'revisi'])->name('revisi_penilaian')->middleware('auth');
Route::post('revisi_penilaian_save', [ TrxPenilaianController::class, 'revisi_save'])->name('revisi_penilaian_save')->middleware('can:hc');
Route::get('penilaian/kontrak/{id}', [ TrxPenilaianController::class, 'kontrak'])->name('penilaian.kontrak')->middleware('can:hc');
Route::get('penilaian/kontrak_dok/{id}', [ TrxPenilaianController::class, 'kontrak_dok'])->name('penilaian.kontrak_dok')->middleware('can:hc');
Route::post('penilaian/kontrak_update', [TrxPenilaianController::class, 'kontrak_update'])->name('penilaian.kontrak_update')->middleware('can:hc');
Route::resource('penilaian_auto', TrxPenilaianAutoController::class)->middleware('can:hc');
Route::resource('penilaian_reminder', TrxPenilaianReminderController::class)->middleware('can:hc');
Route::resource('penilaianlog', TrxPenilaianLogController::class)->middleware('auth');
Route::resource('penilaianloghistory', TrxPenilaianLogHistoryController::class)->middleware('can:hc');
Route::resource('penilaianskor', TrxPenilaianSkorController::class);
// 
Route::resource('cuti', TrxCutiController::class);
Route::resource('cutilog', TrxCutiLogController::class)->middleware('auth');
Route::resource('sakit', TrxSakitController::class);
// 
Route::resource('reimburse', TrxReimburseController::class);
// Route::resource('reimbursedetail', TrxReimburseDetailController::class)->middleware('auth');
Route::resource('plafon', ReimbursePlafonController::class)->middleware('can:hc');
Route::post('plafon/upload_data', [ ReimbursePlafonController::class, 'upload_data'])->name('plafon.upload_data')->middleware('can:hc');
Route::get('reimburse/editDetail/{id}', [TrxReimburseController::class, 'editDetail'])->name('reimburse.editDetail')->middleware('auth');
Route::post('reimburse/updateDetail/{id}', [TrxReimburseController::class, 'updateDetail'])->name('reimburse.updateDetail')->middleware('auth');
// 
Route::resource('rekomendasi', TrxRekomendasiController::class);
// 
Route::resource('pelatihan', TrxPelatihanController::class);
Route::post('pelatihan/save_update/{id}', [TrxPelatihanController::class, 'save_update'])->name('pelatihan.save_update')->middleware('can:hc');
Route::resource('pelatihan_sertifikat', TrxPelatihanSertifikatController::class)->middleware('can:hc');
Route::post('pelatihan_sertifikat/upload_file/{id}', [TrxPelatihanSertifikatController::class, 'upload_file'])->name('pelatihan_sertifikat.upload_file')->middleware('can:hc');
Route::get('pelatihan_sertifikat/scheduler/{id}', [TrxPelatihanSertifikatController::class, 'scheduler'])->name('pelatihan_sertifikat.scheduler');
Route::get('pelatihan_sertifikat/kadaluarsa/{id}', [TrxPelatihanSertifikatController::class, 'kadaluarsa'])->name('pelatihan_sertifikat.kadaluarsa')->middleware('can:hc');
// 
Route::resource('nonaktif', TrxNonaktifController::class);
Route::post('nonaktif/save_edit/{id}', [TrxNonaktifController::class, 'save_edit'])->name('nonaktif.save_edit')->middleware('auth');
Route::resource('nonaktif_auto', TrxNonaktifAutoController::class)->middleware('can:hc');
// 
Route::resource('izin', TrxIjinController::class);
Route::get('izin/recap/{id}', [TrxIjinController::class, 'recap'])->name('izin.recap')->middleware('can:hc');
Route::get('izin/recap/detail/{id}', [TrxIjinController::class, 'detail'])->name('izin.recap.detail')->middleware('can:hc');
// 
Route::resource('kebutuhan', TrxKebutuhanController::class);
Route::resource('kebutuhan_detail', TrxKebutuhanDetailController::class)->middleware('can:hc');
Route::get('kebutuhan/dashboard/{id}', [TrxKebutuhanController::class, 'dashboard'])->name('kebutuhan.dashboard')->middleware('can:hc');
// 
Route::resource('pekerjaan_bidang', PekerjaanBidangController::class)->middleware('auth');
Route::resource('pekerjaan_kategori', PekerjaanKategoriController::class)->middleware('auth');
Route::resource('pekerjaan_master', PekerjaanMasterController::class)->middleware('auth');
Route::resource('pekerjaan_detail', PekerjaanDetailController::class)->middleware('auth');
// 
Route::resource('covid', TrxAbsensiKesehatanController::class);
Route::resource('health', TrxKesehatanReportingController::class)->middleware('can:hc');
// 
Route::get('penilaian/layer/{id}', [TrxPenilaianController::class, 'layer'])->name('penilaian.layer');
Route::get('penilaian/layer', [TrxPenilaianController::class, 'layer'])->name('penilaian_layer');
// 
Route::get('rekomendasi/layer/{id}', [TrxRekomendasiController::class, 'layer'])->name('rekomendasi.layer');
Route::get('rekomendasi/layer', [TrxRekomendasiController::class, 'layer'])->name('rekomendasi_layer');
// 
Route::get('kebutuhan/kota/{id}', [TrxKebutuhanController::class, 'kota'])->name('kebutuhan.kota');
Route::get('kebutuhan/kota', [TrxKebutuhanController::class, 'kota'])->name('kebutuhan_kota');
Route::get('kebutuhan/layer/{id}', [TrxKebutuhanController::class, 'layer'])->name('kebutuhan.layer');
Route::get('kebutuhan/layer', [TrxKebutuhanController::class, 'layer'])->name('kebutuhan_layer');
// 
Route::get('kebutuhan/edit_kolom/{id}', [TrxKebutuhanController::class, 'edit_kolom'])->name('kebutuhan.edit_kolom')->middleware('can:hc');
Route::post('kebutuhan/post_kolom/{id}', [TrxKebutuhanController::class, 'post_kolom'])->name('kebutuhan.post_kolom')->middleware('can:hc');
// Route::get('kebutuhan/get_atribute/{id}', [TrxKebutuhanController::class, 'get_atribute'])->name('kebutuhan.get_atribute')->middleware('can:hc');
// Route::post('kebutuhan/post_atribute/{id}', [TrxKebutuhanController::class, 'post_atribute'])->name('kebutuhan.post_atribute')->middleware('can:hc');
// 
Route::get('kebutuhan/export_word/{id}', [TrxKebutuhanController::class, 'export_word'])->name('kebutuhan.export_word')->middleware('can:hc');
// 
Route::get('pekerjaan_master/pekerjaan/{id}', [PekerjaanMasterController::class, 'pekerjaan'])->name('pekerjaan_master.pekerjaan');
Route::get('pekerjaan_master/pekerjaan', [PekerjaanMasterController::class, 'pekerjaan'])->name('pekerjaan_master_pekerjaan');
// 
Route::resource('_pelatihan_master', PelatihanMasterController::class)->middleware('auth');
Route::resource('_sertifikasi_master', SertifikasiMasterController::class)->middleware('auth');
Route::resource('_keterampilan_master', KeterampilanMasterController::class)->middleware('auth');
Route::resource('_kepribadian_master', KepribadianMasterController::class)->middleware('auth');
// 
// Route::get('sendmail', function () {
//     $name = "Funny Coder";
//     Mail::to('hadidisini@yahoo.com')->send(new MattaRebornMail($name));
//     // return new MattaRebornMail();
// });
// 
Route::get('feeds.view', [FeedController::class, 'view'])->name('feeds.view')->middleware('auth');
// 
Route::get('approval-index', [OvertimeController::class, 'view'])->name('approval-index')->middleware('auth');
Route::get('approval-review/{id}', [OvertimeController::class, 'review'])->name('approval-review')->middleware('auth');
// 
Route::get('approval-review-todolist/{id}', [OvertimeController::class, 'review_todolist'])->name('approval-review-todolist');
Route::get('approval-appject-todolist/{id}', [OvertimeController::class, 'appject_todolist'])->name('approval-appject-todolist');
// 
Route::get('approval-appject/{id}', [OvertimeController::class, 'appject'])->name('approval-appject')->middleware('auth');
Route::get('reporting-index', [OvertimeController::class, 'reporting'])->name('reporting-index')->middleware('can:spv');
Route::get('adj_ot/{id}', [OvertimeController::class, 'adj_ot'])->name('adj_ot')->middleware('can:spv');
// 
// Route::resource('bpd', TrxBpdController::class)->middleware('auth');
Route::resource('bpd', TrxBpdController::class)->middleware('auth');
Route::get('bpd/project/{id}', [TrxBpdController::class, 'get_project'])->name('bpd.project');
Route::get('bpd/project', [TrxBpdController::class, 'get_project'])->name('bpd_project');
Route::get('bpd/coa/{id}', [TrxBpdController::class, 'get_coa'])->name('bpd.coa');
Route::get('bpd/coa', [TrxBpdController::class, 'get_coa'])->name('bpd_coa');
Route::get('bpd/delete_info/{id}', [TrxBpdController::class, 'delete_info'])->name('bpd.delete_info');
Route::get('bpd/delete_biaya/{id}', [TrxBpdController::class, 'delete_biaya'])->name('bpd.delete_biaya');
Route::post('bpd/update_biaya/{id}', [TrxBpdController::class, 'update_biaya'])->name('bpd.update_biaya');
Route::get('bpd/exportexcel/{id}', [TrxBpdController::class, 'exportexcel'])->name('bpd.exportexcel')->middleware('can:hc');
// Route::get('bpd/get_data/{id}', [TrxBpdController::class, 'get_data'])->middleware('can:hc');
// 
Route::resource('mtrdatabpd', MtrDataBpdController::class)->middleware('can:hc');
Route::resource('mtrdatacoordinatorbpd', MtrDataBpdCoordinatorController::class)->middleware('can:hc');
Route::resource('mtrdatabodbpd', MtrDataBpdBodController::class)->middleware('can:hc');
Route::resource('mtrdatacoabpd', MtrDataBpdCoaController::class)->middleware('can:hc');
Route::resource('mtrdataprojectadmin', MtrDataBpdProjectAdminController::class)->middleware('can:hc');
Route::resource('mtrdataprojectadminpic', MtrDataBpdProjectAdminPicController::class)->middleware('can:hc');
// 
Route::resource('pdp', TrxPdpController::class)->middleware('auth');
// 
Route::resource('salary_str', MtrStrukturGajiController::class)->middleware('can:hc');
Route::post('salary_str/upload_template', [ MtrStrukturGajiController::class, 'upload_template'])->name('salary_str.upload_template')->middleware('can:hc');
// 
Route::get('/', function () {
    // return redirect()->route('index');
    return redirect()->route('login-one');
})->name('/');

//Language Change
Route::get('lang/{locale}', function ($locale) {
    if (!in_array($locale, ['en', 'de', 'es', 'fr', 'pt', 'cn', 'ae'])) {
        abort(400);
    }
    Session()->put('locale', $locale);
    Session::get('locale');
    return redirect()->back();
})->name('lang');
Route::resource('dashboard', DashboardController::class)->middleware('can:hc_dashboard');
Route::get('dashboard/biaya/{id}', [DashboardController::class, 'biaya'])->name('dashboard.biaya')->middleware('can:hc_dashboard');
Route::get('dashboard/biaya_lain/{id}', [DashboardController::class, 'biaya_lain'])->name('dashboard.biaya_lain')->middleware('can:hc_dashboard');
Route::get('dashboard/survey_pora/{id}', [DashboardController::class, 'survey_pora'])->name('dashboard.survey_pora')->middleware('can:hc_dashboard');
// 
Route::get('lang/{locale}', [LocalizationController::class , 'lang']);
// 
Route::prefix('dashboard')->middleware('auth')->group(function () {
    Route::view('index', 'dashboard.index')->name('index');
    Route::view('dashboard-02', 'dashboard.dashboard-02')->name('dashboard-02');
    Route::view('dashboard-03', 'dashboard.dashboard-03')->name('dashboard-03');
    Route::view('dashboard-04', 'dashboard.dashboard-04')->name('dashboard-04');
    Route::view('dashboard-05', 'dashboard.dashboard-05')->name('dashboard-05');
});
// Route::prefix('formulir')->middleware('auth')->group(function () {
//     Route::view('kebutuhan-index', 'formulir.kebutuhan.index')->name('kebutuhan-index');
//     Route::view('penilaian-index', 'formulir.penilaian.index')->name('penilaian-index');
//     Route::view('rekomendasi-index', 'formulir.rekomendasi.index')->name('rekomendasi-index');
//     Route::view('nonaktif-index', 'formulir.nonaktif.index')->name('nonaktif-index');
//     Route::view('reimbursement-index', 'formulir.reimbursement.index')->name('reimbursement-index');
//     Route::view('cuti-index', 'formulir.cuti.index')->name('cuti-index');
//     Route::view('ijin-index', 'formulir.ijin.index')->name('ijin-index');
//     Route::view('pelatihan-index', 'formulir.pelatihan.index')->name('pelatihan-index');
// });

// Route::view('feeds', 'feeds.index')->name('feeds');

// Route::view('attendance', 'attendance.index')->name('attendance');

// Route::prefix('overtime')->middleware('auth')->group(function () {
//     Route::view('propose-index', 'overtime.propose.index')->name('propose-index');
// Route::view('approval-index', 'overtime.approval.index')->name('approval-index');
// });

Route::prefix('widgets')->middleware('auth')->group(function () {
    Route::view('general-widget', 'widgets.general-widget')->name('general-widget');
    Route::view('chart-widget', 'widgets.chart-widget')->name('chart-widget');
});

Route::prefix('page-layouts')->middleware('auth')->group(function () {
    Route::view('box-layout', 'page-layout.box-layout')->name('box-layout');
    Route::view('layout-rtl', 'page-layout.layout-rtl')->name('layout-rtl');
    Route::view('layout-dark', 'page-layout.layout-dark')->name('layout-dark');
    Route::view('hide-on-scroll', 'page-layout.hide-on-scroll')->name('hide-on-scroll');
    Route::view('footer-light', 'page-layout.footer-light')->name('footer-light');
    Route::view('footer-dark', 'page-layout.footer-dark')->name('footer-dark');
    Route::view('footer-fixed', 'page-layout.footer-fixed')->name('footer-fixed');
});

Route::prefix('project')->middleware('auth')->group(function () {
    Route::view('projects', 'project.projects')->name('projects');
    Route::view('projectcreate', 'project.projectcreate')->name('projectcreate');
});

Route::view('file-manager', 'file-manager')->name('file-manager')->middleware('auth');
Route::view('kanban', 'kanban')->name('kanban')->middleware('auth');

Route::prefix('ecommerce')->middleware('auth')->group(function () {
    Route::view('product', 'apps.product')->name('product');
    Route::view('page-product', 'apps.product-page')->name('product-page');
    Route::view('list-products', 'apps.list-products')->name('list-products');
    Route::view('payment-details', 'apps.payment-details')->name('payment-details');
    Route::view('order-history', 'apps.order-history')->name('order-history');
    Route::view('invoice-template', 'apps.invoice-template')->name('invoice-template');
    Route::view('cart', 'apps.cart')->name('cart');
    Route::view('list-wish', 'apps.list-wish')->name('list-wish');
    Route::view('checkout', 'apps.checkout')->name('checkout');
    Route::view('pricing', 'apps.pricing')->name('pricing');
});

Route::prefix('email')->middleware('auth')->group(function () {
    Route::view('email-application', 'apps.email-application')->name('email-application');
    Route::view('email-compose', 'apps.email-compose')->name('email-compose');
});

Route::prefix('chat')->middleware('auth')->group(function () {
    Route::view('chat', 'apps.chat')->name('chat');
    Route::view('video-chat', 'apps.video-chat')->name('chat-video');
});

Route::prefix('users')->middleware('auth')->group(function () {
    Route::view('user-profile', 'apps.user-profile')->name('user-profile');
    Route::view('edit-profile', 'apps.edit-profile')->name('edit-profile');
    Route::view('user-cards', 'apps.user-cards')->name('user-cards');
});


Route::view('bookmark', 'apps.bookmark')->name('bookmark')->middleware('auth');
Route::view('contacts', 'apps.contacts')->name('contacts')->middleware('auth');
Route::view('task', 'apps.task')->name('task')->middleware('auth');
Route::view('calendar-basic', 'apps.calendar-basic')->name('calendar-basic')->middleware('auth');
Route::view('social-app', 'apps.social-app')->name('social-app')->middleware('auth');
Route::view('to-do', 'apps.to-do')->name('to-do')->middleware('auth');
Route::view('search', 'apps.search')->name('search')->middleware('auth');

Route::prefix('ui-kits')->middleware('auth')->group(function () {
    Route::view('state-color', 'ui-kits.state-color')->name('state-color');
    Route::view('typography', 'ui-kits.typography')->name('typography');
    Route::view('avatars', 'ui-kits.avatars')->name('avatars');
    Route::view('helper-classes', 'ui-kits.helper-classes')->name('helper-classes');
    Route::view('grid', 'ui-kits.grid')->name('grid');
    Route::view('tag-pills', 'ui-kits.tag-pills')->name('tag-pills');
    Route::view('progress-bar', 'ui-kits.progress-bar')->name('progress-bar');
    Route::view('modal', 'ui-kits.modal')->name('modal');
    Route::view('alert', 'ui-kits.alert')->name('alert');
    Route::view('popover', 'ui-kits.popover')->name('popover');
    Route::view('tooltip', 'ui-kits.tooltip')->name('tooltip');
    Route::view('loader', 'ui-kits.loader')->name('loader');
    Route::view('dropdown', 'ui-kits.dropdown')->name('dropdown');
    Route::view('accordion', 'ui-kits.accordion')->name('accordion');
    Route::view('tab-bootstrap', 'ui-kits.tab-bootstrap')->name('tab-bootstrap');
    Route::view('tab-material', 'ui-kits.tab-material')->name('tab-material');
    Route::view('box-shadow', 'ui-kits.box-shadow')->name('box-shadow');
    Route::view('list', 'ui-kits.list')->name('list');
});

Route::prefix('bonus-ui')->middleware('auth')->group(function () {
    Route::view('scrollable', 'bonus-ui.scrollable')->name('scrollable');
    Route::view('tree', 'bonus-ui.tree')->name('tree');
    Route::view('bootstrap-notify', 'bonus-ui.bootstrap-notify')->name('bootstrap-notify');
    Route::view('rating', 'bonus-ui.rating')->name('rating');
    Route::view('dropzone', 'bonus-ui.dropzone')->name('dropzone');
    Route::view('tour', 'bonus-ui.tour')->name('tour');
    Route::view('sweet-alert2', 'bonus-ui.sweet-alert2')->name('sweet-alert2');
    Route::view('modal-animated', 'bonus-ui.modal-animated')->name('modal-animated');
    Route::view('owl-carousel', 'bonus-ui.owl-carousel')->name('owl-carousel');
    Route::view('ribbons', 'bonus-ui.ribbons')->name('ribbons');
    Route::view('pagination', 'bonus-ui.pagination')->name('pagination');
    Route::view('breadcrumb', 'bonus-ui.breadcrumb')->name('breadcrumb');
    Route::view('range-slider', 'bonus-ui.range-slider')->name('range-slider');
    Route::view('image-cropper', 'bonus-ui.image-cropper')->name('image-cropper');
    Route::view('sticky', 'bonus-ui.sticky')->name('sticky');
    Route::view('basic-card', 'bonus-ui.basic-card')->name('basic-card');
    Route::view('creative-card', 'bonus-ui.creative-card')->name('creative-card');
    Route::view('tabbed-card', 'bonus-ui.tabbed-card')->name('tabbed-card');
    Route::view('dragable-card', 'bonus-ui.dragable-card')->name('dragable-card');
    Route::view('timeline-v-1', 'bonus-ui.timeline-v-1')->name('timeline-v-1');
    Route::view('timeline-v-2', 'bonus-ui.timeline-v-2')->name('timeline-v-2');
    Route::view('timeline-small', 'bonus-ui.timeline-small')->name('timeline-small');
});

Route::prefix('builders')->middleware('auth')->group(function () {
    Route::view('form-builder-1', 'builders.form-builder-1')->name('form-builder-1');
    Route::view('form-builder-2', 'builders.form-builder-2')->name('form-builder-2');
    Route::view('pagebuild', 'builders.pagebuild')->name('pagebuild');
    Route::view('button-builder', 'builders.button-builder')->name('button-builder');
});

Route::prefix('animation')->middleware('auth')->group(function () {
    Route::view('animate', 'animation.animate')->name('animate');
    Route::view('scroll-reval', 'animation.scroll-reval')->name('scroll-reval');
    Route::view('aos', 'animation.aos')->name('aos');
    Route::view('tilt', 'animation.tilt')->name('tilt');
    Route::view('wow', 'animation.wow')->name('wow');
});


Route::prefix('icons')->middleware('auth')->group(function () {
    Route::view('flag-icon', 'icons.flag-icon')->name('flag-icon');
    Route::view('font-awesome', 'icons.font-awesome')->name('font-awesome');
    Route::view('ico-icon', 'icons.ico-icon')->name('ico-icon');
    Route::view('themify-icon', 'icons.themify-icon')->name('themify-icon');
    Route::view('feather-icon', 'icons.feather-icon')->name('feather-icon');
    Route::view('whether-icon', 'icons.whether-icon')->name('whether-icon');
    Route::view('simple-line-icon', 'icons.simple-line-icon')->name('simple-line-icon');
    Route::view('material-design-icon', 'icons.material-design-icon')->name('material-design-icon');
    Route::view('pe7-icon', 'icons.pe7-icon')->name('pe7-icon');
    Route::view('typicons-icon', 'icons.typicons-icon')->name('typicons-icon');
    Route::view('ionic-icon', 'icons.ionic-icon')->name('ionic-icon');
});

Route::prefix('buttons')->middleware('auth')->group(function () {
    Route::view('buttons', 'buttons.buttons')->name('buttons');
    Route::view('flat-buttons', 'buttons.flat-buttons')->name('flat-buttons');
    Route::view('edge-buttons', 'buttons.buttons-edge')->name('buttons-edge');
    Route::view('raised-button', 'buttons.raised-button')->name('raised-button');
    Route::view('button-group', 'buttons.button-group')->name('button-group');
});

Route::prefix('forms')->middleware('auth')->group(function () {
    Route::view('form-validation', 'forms.form-validation')->name('form-validation');
    Route::view('base-input', 'forms.base-input')->name('base-input');
    Route::view('radio-checkbox-control', 'forms.radio-checkbox-control')->name('radio-checkbox-control');
    Route::view('input-group', 'forms.input-group')->name('input-group');
    Route::view('megaoptions', 'forms.megaoptions')->name('megaoptions');
    Route::view('datepicker', 'forms.datepicker')->name('datepicker');
    Route::view('time-picker', 'forms.time-picker')->name('time-picker');
    Route::view('datetimepicker', 'forms.datetimepicker')->name('datetimepicker');
    Route::view('daterangepicker', 'forms.daterangepicker')->name('daterangepicker');
    Route::view('touchspin', 'forms.touchspin')->name('touchspin');
    Route::view('select2', 'forms.select2')->name('select2');
    Route::view('switch', 'forms.switch')->name('switch');
    Route::view('typeahead', 'forms.typeahead')->name('typeahead');
    Route::view('clipboard', 'forms.clipboard')->name('clipboard');
    Route::view('default-form', 'forms.default-form')->name('default-form');
    Route::view('form-wizard', 'forms.form-wizard')->name('form-wizard');
    Route::view('form-two-wizard', 'forms.form-wizard-two')->name('form-wizard-two');
    Route::view('wizard-form-three', 'forms.form-wizard-three')->name('form-wizard-three');
    Route::post('form-wizard-three', function () {
        return redirect()->route('form-wizard-three');
    })->name('form-wizard-three-post');
});

Route::prefix('tables')->middleware('auth')->group(function () {
    Route::view('bootstrap-basic-table', 'tables.bootstrap-basic-table')->name('bootstrap-basic-table');
    Route::view('bootstrap-sizing-table', 'tables.bootstrap-sizing-table')->name('bootstrap-sizing-table');
    Route::view('bootstrap-border-table', 'tables.bootstrap-border-table')->name('bootstrap-border-table');
    Route::view('bootstrap-styling-table', 'tables.bootstrap-styling-table')->name('bootstrap-styling-table');
    Route::view('table-components', 'tables.table-components')->name('table-components');
    Route::view('datatable-basic-init', 'tables.datatable-basic-init')->name('datatable-basic-init');
    Route::view('datatable-advance', 'tables.datatable-advance')->name('datatable-advance');
    Route::view('datatable-styling', 'tables.datatable-styling')->name('datatable-styling');
    Route::view('datatable-ajax', 'tables.datatable-ajax')->name('datatable-ajax');
    Route::view('datatable-server-side', 'tables.datatable-server-side')->name('datatable-server-side');
    Route::view('datatable-plugin', 'tables.datatable-plugin')->name('datatable-plugin');
    Route::view('datatable-api', 'tables.datatable-api')->name('datatable-api');
    Route::view('datatable-data-source', 'tables.datatable-data-source')->name('datatable-data-source');
    Route::view('datatable-ext-autofill', 'tables.datatable-ext-autofill')->name('datatable-ext-autofill');
    Route::view('datatable-ext-basic-button', 'tables.datatable-ext-basic-button')->name('datatable-ext-basic-button');
    Route::view('datatable-ext-col-reorder', 'tables.datatable-ext-col-reorder')->name('datatable-ext-col-reorder');
    Route::view('datatable-ext-fixed-header', 'tables.datatable-ext-fixed-header')->name('datatable-ext-fixed-header');
    Route::view('datatable-ext-html-5-data-export', 'tables.datatable-ext-html-5-data-export')->name('datatable-ext-html-5-data-export');
    Route::view('datatable-ext-key-table', 'tables.datatable-ext-key-table')->name('datatable-ext-key-table');
    Route::view('datatable-ext-responsive', 'tables.datatable-ext-responsive')->name('datatable-ext-responsive');
    Route::view('datatable-ext-row-reorder', 'tables.datatable-ext-row-reorder')->name('datatable-ext-row-reorder');
    Route::view('datatable-ext-scroller', 'tables.datatable-ext-scroller')->name('datatable-ext-scroller');
    Route::view('jsgrid-table', 'tables.jsgrid-table')->name('jsgrid-table');
});

Route::prefix('charts')->middleware('auth')->group(function () {
    Route::view('echarts', 'charts.echarts')->name('echarts');
    Route::view('chart-apex', 'charts.chart-apex')->name('chart-apex');
    Route::view('chart-google', 'charts.chart-google')->name('chart-google');
    Route::view('chart-sparkline', 'charts.chart-sparkline')->name('chart-sparkline');
    Route::view('chart-flot', 'charts.chart-flot')->name('chart-flot');
    Route::view('chart-knob', 'charts.chart-knob')->name('chart-knob');
    Route::view('chart-morris', 'charts.chart-morris')->name('chart-morris');
    Route::view('chartjs', 'charts.chartjs')->name('chartjs');
    Route::view('chartist', 'charts.chartist')->name('chartist');
    Route::view('chart-peity', 'charts.chart-peity')->name('chart-peity');
});

Route::view('sample-page', 'pages.sample-page')->name('sample-page')->middleware('auth');
Route::view('internationalization', 'pages.internationalization')->name('internationalization')->middleware('auth');

// Route::prefix('starter-kit')->middleware('auth')->group(function () {
// });

Route::prefix('errors')->middleware('auth')->group(function () {
    Route::view('400', 'errors.400')->name('error-400');
    Route::view('401', 'errors.401')->name('error-401');
    Route::view('403', 'errors.403')->name('error-403');
    Route::view('404', 'errors.404')->name('error-404');
    Route::view('500', 'errors.500')->name('error-500');
    Route::view('503', 'errors.503')->name('error-503');
    Route::view('error_db', 'errors.error_db')->name('error_db');
});

Route::prefix('authentication')->group(function () {
    Route::view('login', 'authentication.login')->name('login')->middleware('auth');
    Route::view('login-one', 'authentication.login-one')->name('login-one');
    Route::view('login-one-reset', 'authentication.login-one-reset')->name('login-one-reset');
    Route::view('login-two', 'authentication.login-two')->name('login-two')->middleware('auth');
    Route::view('login-bs-validation', 'authentication.login-bs-validation')->name('login-bs-validation')->middleware('auth');
    Route::view('login-bs-tt-validation', 'authentication.login-bs-tt-validation')->name('login-bs-tt-validation')->middleware('auth');
    Route::view('login-sa-validation', 'authentication.login-sa-validation')->name('login-sa-validation')->middleware('auth');
    Route::view('sign-up', 'authentication.sign-up')->name('sign-up')->middleware('auth');
    Route::view('sign-up-one', 'authentication.sign-up-one')->name('sign-up-one')->middleware('auth');
    Route::view('sign-up-two', 'authentication.sign-up-two')->name('sign-up-two')->middleware('auth');
    Route::view('sign-up-wizard', 'authentication.sign-up-wizard')->name('sign-up-wizard')->middleware('auth');
    Route::view('unlock', 'authentication.unlock')->name('unlock')->middleware('auth');
    Route::view('forget-password', 'authentication.forget-password')->name('forget-password')->middleware('auth');
    Route::view('reset-password', 'authentication.reset-password')->name('reset-password')->middleware('auth');
    Route::view('maintenance', 'authentication.maintenance')->name('maintenance')->middleware('auth');
});

Route::view('comingsoon', 'comingsoon.comingsoon')->name('comingsoon')->middleware('auth');
Route::view('comingsoon-bg-video', 'comingsoon.comingsoon-bg-video')->name('comingsoon-bg-video')->middleware('auth');
Route::view('comingsoon-bg-img', 'comingsoon.comingsoon-bg-img')->name('comingsoon-bg-img')->middleware('auth');

Route::view('basic-template', 'email-templates.basic-template')->name('basic-template')->middleware('auth');
Route::view('email-header', 'email-templates.email-header')->name('email-header')->middleware('auth');
Route::view('template-email', 'email-templates.template-email')->name('template-email')->middleware('auth');
Route::view('template-email-2', 'email-templates.template-email-2')->name('template-email-2')->middleware('auth');
Route::view('ecommerce-templates', 'email-templates.ecommerce-templates')->name('ecommerce-templates')->middleware('auth');
Route::view('email-order-success', 'email-templates.email-order-success')->name('email-order-success')->middleware('auth');


Route::prefix('gallery')->middleware('auth')->group(function () {
    Route::view('index', 'apps.gallery')->name('gallery');
    Route::view('with-gallery-description', 'apps.gallery-with-description')->name('gallery-with-description');
    Route::view('gallery-masonry', 'apps.gallery-masonry')->name('gallery-masonry');
    Route::view('masonry-gallery-with-disc', 'apps.masonry-gallery-with-disc')->name('masonry-gallery-with-disc');
    Route::view('gallery-hover', 'apps.gallery-hover')->name('gallery-hover');
});

Route::prefix('blog')->middleware('auth')->group(function () {
    Route::view('index', 'apps.blog')->name('blog');
    Route::view('blog-single', 'apps.blog-single')->name('blog-single');
    Route::view('add-post', 'apps.add-post')->name('add-post');
});


Route::view('faq', 'apps.faq')->name('faq')->middleware('auth');

Route::prefix('job-search')->middleware('auth')->group(function () {
    Route::view('job-cards-view', 'apps.job-cards-view')->name('job-cards-view');
    Route::view('job-list-view', 'apps.job-list-view')->name('job-list-view');
    Route::view('job-details', 'apps.job-details')->name('job-details');
    Route::view('job-apply', 'apps.job-apply')->name('job-apply');
});

Route::prefix('learning')->middleware('auth')->group(function () {
    Route::view('learning-list-view', 'apps.learning-list-view')->name('learning-list-view');
    Route::view('learning-detailed', 'apps.learning-detailed')->name('learning-detailed');
});

Route::prefix('maps')->middleware('auth')->group(function () {
    Route::view('map-js', 'apps.map-js')->name('map-js');
    Route::view('vector-map', 'apps.vector-map')->name('vector-map');
});

Route::prefix('editors')->middleware('auth')->group(function () {
    Route::view('summernote', 'apps.summernote')->name('summernote');
    Route::view('ckeditor', 'apps.ckeditor')->name('ckeditor');
    Route::view('simple-mde', 'apps.simple-mde')->name('simple-mde');
    Route::view('ace-code-editor', 'apps.ace-code-editor')->name('ace-code-editor');
});

Route::view('knowledgebase', 'apps.knowledgebase')->name('knowledgebase')->middleware('auth');
Route::view('support-ticket', 'apps.support-ticket')->name('support-ticket')->middleware('auth');
Route::view('landing-page', 'pages.landing-page')->name('landing-page')->middleware('auth');

Route::prefix('layouts')->middleware('auth')->group(function () {
    Route::view('compact-sidebar', 'admin_unique_layouts.compact-sidebar'); //default //Dubai
    Route::view('box-layout', 'admin_unique_layouts.box-layout');    //default //New York //
    Route::view('dark-sidebar', 'admin_unique_layouts.dark-sidebar');

    Route::view('default-body', 'admin_unique_layouts.default-body');
    Route::view('compact-wrap', 'admin_unique_layouts.compact-wrap');
    Route::view('enterprice-type', 'admin_unique_layouts.enterprice-type');

    Route::view('compact-small', 'admin_unique_layouts.compact-small');
    Route::view('advance-type', 'admin_unique_layouts.advance-type');
    Route::view('material-layout', 'admin_unique_layouts.material-layout');

    Route::view('color-sidebar', 'admin_unique_layouts.color-sidebar');
    Route::view('material-icon', 'admin_unique_layouts.material-icon');
    Route::view('modern-layout', 'admin_unique_layouts.modern-layout');
});

Route::get('layout-{light}', function ($light) {
    session()->put('layout', $light);
    session()->get('layout');
    if ($light == 'vertical-layout') {
        return redirect()->route('pages-vertical-layout');
    }
    return redirect()->route('index');
    return 1;
});
Route::get('/clear-cache', function () {
    Artisan::call('config:cache');
    Artisan::call('cache:clear');
    Artisan::call('config:clear');
    Artisan::call('view:clear');
    Artisan::call('route:clear');
    return "Cache is cleared";
})->name('clear.cache');