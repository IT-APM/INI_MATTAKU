<div class="sidebar-wrapper" sidebar-layout="stroke-svg">
    <div>
        <div class="logo-wrapper text-start">
            <a href="<?php echo e(route('todolist.index')); ?>">
                
                
                <img height="60" src="<?php echo e(asset('assets/images/logo/logo_2.jpg')); ?>" alt="image">
            </a>
            <div class="back-btn"><i class="fa fa-angle-left"></i></div>
            <div class="toggle-sidebar"><i class="status_toggle middle sidebar-toggle" data-feather="grid"> </i></div>
        </div>
        <div class="logo-icon-wrapper">
            <a href="<?php echo e(route('todolist.index')); ?>">
                <img class="img-fluid" src="<?php echo e(asset('assets/images/logo/logo-icon.png')); ?>" alt="">
            </a>
        </div>
        <nav class="sidebar-main">
            <div class="left-arrow" id="left-arrow"><i data-feather="arrow-left"></i></div>
            <div id="sidebar-menu">
                <ul class="sidebar-links" id="simple-bar">
                    <li class="back-btn">
                        <a href="<?php echo e(route('todolist.index')); ?>"><img class="img-fluid" src="<?php echo e(asset('assets/images/logo/logo-icon.png')); ?>" alt=""></a>
                        <div class="mobile-back text-end"><span>Back</span><i class="fa fa-angle-right ps-2" aria-hidden="true"></i></div>
                    </li>
                    <li class="sidebar-main-title">
                        <div>
                            <h6>Home Page</h6>
                        </div>
                    </li>
                    <li class="sidebar-list mt-3">
                        <!-- <label class="badge badge-light-primary">5</label> -->
                        <a class="sidebar-link sidebar-title" href="#">
                            <svg class="stroke-icon">
                                <use href="<?php echo e(asset('assets/svg/icon-sprite.svg#stroke-home')); ?>"></use>
                            </svg>
                            <svg class="fill-icon">
                                <use href="<?php echo e(asset('assets/svg/icon-sprite.svg#fill-home')); ?>"></use>
                            </svg>
                            <span>Home</span></a>
                        <ul class="sidebar-submenu">
                            
                                    
                                
                            
                            
                            <li><a href="<?php echo e(route('todolist.index')); ?>">To Do List</a></li>
                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('hc')): ?>
                            <li><a href="<?php echo e(route('todolist.recap', 0)); ?>">To Do List Recap</a></li>
                            <?php endif; ?>
                            <!-- <li><a href="<?php echo e(route('dashboard-02')); ?>">Dashboard 1</a></li>
                            <li><a href="<?php echo e(route('dashboard-03')); ?>">Dashboard 2</a></li>
                            <li><a href="<?php echo e(route('dashboard-04')); ?>">Crypto</a></li>
                            <li><a href="<?php echo e(route('dashboard-05')); ?>">Social</a></li> -->
                        </ul>
                    </li>
                    <!-- <li class="sidebar-list"><a class="sidebar-link sidebar-title" href="#">
                            <svg class="stroke-icon">
                                <use href="<?php echo e(asset('assets/svg/icon-sprite.svg#stroke-widget')); ?>"></use>
                            </svg>
                            <svg class="fill-icon">
                                <use href="<?php echo e(asset('assets/svg/icon-sprite.svg#fill-widget')); ?>"></use>
                            </svg><span class="lan-6">Widgets</span></a>
                        <ul class="sidebar-submenu">
                            <li><a href="<?php echo e(route('general-widget')); ?>">General</a></li>
                            <li><a href="<?php echo e(route('chart-widget')); ?>">Chart</a></li>
                        </ul>
                    </li> -->
                    <!-- <li class="sidebar-list"><a class="sidebar-link sidebar-title" href="#">
                            <svg class="stroke-icon">
                                <use href="<?php echo e(asset('assets/svg/icon-sprite.svg#stroke-layout')); ?>"></use>
                            </svg>
                            <svg class="fill-icon">
                                <use href="<?php echo e(asset('assets/svg/icon-sprite.svg#fill-layout')); ?>"></use>
                            </svg><span class="lan-7">Page layout</span></a>
                        <ul class="sidebar-submenu">
                            <li><a href="<?php echo e(route('box-layout')); ?>">Boxed</a></li>
                            <li><a href="<?php echo e(route('layout-rtl')); ?>">RTL</a></li>
                            <li><a href="<?php echo e(route('layout-dark')); ?>">Dark Layout</a></li>
                            <li><a href="<?php echo e(route('hide-on-scroll')); ?>">Hide Nav Scroll</a></li>
                            <li><a href="<?php echo e(route('footer-light')); ?>">Footer Light</a></li>
                            <li><a href="<?php echo e(route('footer-dark')); ?>">Footer Dark</a></li>
                            <li><a href="<?php echo e(route('footer-fixed')); ?>">Footer Fixed</a></li>
                        </ul>
                    </li> -->
                    <li class="sidebar-main-title">
                        <div>
                            <h6>Applications</h6>
                        </div>
                    </li>
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('hc')): ?>
                    <li class="sidebar-list"><a class="sidebar-link sidebar-title link-nav" href="<?php echo e(route('birthday.index')); ?>">
                            <svg class="stroke-icon">
                                <use href="<?php echo e(asset('assets/svg/icon-sprite.svg#stroke-knowledgebase')); ?>"></use>
                            </svg>
                            <svg class="fill-icon">
                                <use href="<?php echo e(asset('assets/svg/icon-sprite.svg#fill-knowledgebase')); ?>"></use>
                            </svg><span>Birthday</span></a></li>
                    <?php endif; ?>
                    <li class="sidebar-list"><a class="sidebar-link sidebar-title link-nav" href="<?php echo e(route('feeds.view')); ?>">
                            <svg class="stroke-icon">
                                <use href="<?php echo e(asset('assets/svg/icon-sprite.svg#stroke-knowledgebase')); ?>"></use>
                            </svg>
                            <svg class="fill-icon">
                                <use href="<?php echo e(asset('assets/svg/icon-sprite.svg#fill-knowledgebase')); ?>"></use>
                            </svg><span>Feeds</span></a></li>
                    <li class="sidebar-list"><a class="sidebar-link sidebar-title" href="#">
                            <svg class="stroke-icon">
                                <use href="<?php echo e(asset('assets/svg/icon-sprite.svg#stroke-form')); ?>"></use>
                            </svg>
                            <svg class="fill-icon">
                                <use href="<?php echo e(asset('assets/svg/icon-sprite.svg#fill-form')); ?>"> </use>
                            </svg><span>Forms</span></a>
                        <ul class="sidebar-submenu">
                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('asm')): ?>
                                <li><a href="<?php echo e(route('kebutuhan.index')); ?>">Add Employees</a></li>
                            <?php endif; ?>
                            <li><a href="<?php echo e(route('penilaian.index')); ?>">Evaluation</a></li>
                            <li><a href="<?php echo e(route('nonaktif.index')); ?>">Exit Clearance</a></li>
                            <li><a href="<?php echo e(route('cuti.index')); ?>">Leave</a></li>
                            <li><a href="<?php echo e(route('izin.index')); ?>">Permit</a></li>
                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('asm')): ?>
                                <li><a href="<?php echo e(route('rekomendasi.index')); ?>">Recommendation</a></li>
                            <?php endif; ?>
                            <li><a href="<?php echo e(route('reimburse.index')); ?>">Reimbursement</a></li>
                            <li><a href="<?php echo e(route('sakit.index')); ?>">Sick Leave</a></li>
                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('spv')): ?>
                                <li><a href="<?php echo e(route('pelatihan.index')); ?>">Training</a></li>
                            <?php endif; ?>
                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('bpd')): ?>
                                <li><a href="<?php echo e(route('bpd.index')); ?>">Travel Expense</a></li>
                            <?php endif; ?>
                        </ul>
                    </li>
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('iphone')): ?>
                        <li class="sidebar-list"><a class="sidebar-link sidebar-title link-nav" href="<?php echo e(route('covid.index')); ?>">
                                <svg class="stroke-icon">
                                    <use href="<?php echo e(asset('assets/svg/icon-sprite.svg#stroke-form')); ?>"></use>
                                </svg>
                                <svg class="fill-icon">
                                    <use href="<?php echo e(asset('assets/svg/icon-sprite.svg#fill-form')); ?>"></use>
                                </svg><span>Health</span></a></li>
                        <li class="sidebar-main-title">
                            <div>
                                <h6>Clock-in Clock-out</h6>
                            </div>
                        </li>
                        <li class="sidebar-list"><a class="sidebar-link sidebar-title link-nav" href="<?php echo e(route('cico_att.index')); ?>">
                                <svg class="stroke-icon">
                                    <use href="<?php echo e(asset('assets/svg/icon-sprite.svg#stroke-table')); ?>"></use>
                                </svg>
                                <svg class="fill-icon">
                                    <use href="<?php echo e(asset('assets/svg/icon-sprite.svg#fill-table')); ?>"></use>
                                </svg><span>Attendance</span></a></li>
                        <li class="sidebar-list"><a class="sidebar-link sidebar-title link-nav" href="<?php echo e(route('cico_ot.index')); ?>">
                                <svg class="stroke-icon">
                                    <use href="<?php echo e(asset('assets/svg/icon-sprite.svg#stroke-table')); ?>"></use>
                                </svg>
                                <svg class="fill-icon">
                                    <use href="<?php echo e(asset('assets/svg/icon-sprite.svg#fill-table')); ?>"></use>
                                </svg><span>Overtime</span></a></li>
                    <?php endif; ?>
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('hc_dashboard')): ?>
                    <li class="sidebar-main-title">
                        <div>
                            <h6>Dashboard</h6>
                        </div>
                    </li>
                    <?php
                    $all = App\Models\TrxKebutuhanMaster::all();
                    $arr_direktorat = [];
                    $arr_divisi = [];
                    $arr_departemen = [];
                    $arr_status_kerja = [];
                    $arr_area = [];
                    foreach($all as $data){
                        $pengaju_property = json_decode($data->pengaju_property);
                        array_push($arr_direktorat, $pengaju_property->divisi);
                        array_push($arr_divisi, $pengaju_property->dep);
                        array_push($arr_departemen, $pengaju_property->sub_dep);
                        array_push($arr_status_kerja, App\Models\StatusKerja::find($data->id_status_kerja)->status_kerja);
                        array_push($arr_area, App\Models\Propinsi::find($data->id_area)->propinsi);
                    }
                    $arr_select_tahun = [];
                    $select_direktorat = array_values(array_unique($arr_direktorat));
                    sort($select_direktorat);
                    $arr_select_direktorat = [];
                    $select_divisi = array_values(array_unique($arr_divisi));
                    sort($select_divisi);
                    $arr_select_divisi = [];
                    $select_departemen = array_values(array_unique($arr_departemen));
                    sort($select_departemen);
                    $arr_select_departemen = [];
                    $select_status_kerja = array_values(array_unique($arr_status_kerja));
                    sort($select_status_kerja);
                    $arr_select_status_kerja = [];
                    $select_area = array_values(array_unique($arr_area));
                    sort($select_area);
                    ?>
                    <li class="sidebar-list"><a class="sidebar-link sidebar-title" href="#">
                                <svg class="stroke-icon">
                                    <use href="<?php echo e(asset('assets/svg/icon-sprite.svg#stroke-charts')); ?>"></use>
                                </svg>
                                <svg class="fill-icon">
                                    <use href="<?php echo e(asset('assets/svg/icon-sprite.svg#fill-charts')); ?>"> </use>
                                </svg><span>Dashboard</span></a>
                        <ul class="sidebar-submenu">
                            <li><a href="<?php echo e(route('dashboard.biaya', 0)); ?>">Biaya Gaji</a></li>
                            <li><a href="<?php echo e(route('dashboard.biaya_lain', 0)); ?>">Biaya Lain</a></li>
                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('hc')): ?>
                            <li><a href="<?php echo e(route('dashboard.survey_pora', 0)); ?>">Hasil Survey PORA</a></li>
                            <li><a href="<?php echo e(route('karyawan.dashboard', 0)); ?>">Infografis</a></li>
                            <li><a href="<?php echo e(route('overtime.dashboard', 1)); ?>">Overtime</a></li>
                            <li><a href="<?php echo e(route('kebutuhan.dashboard', [
                                'id'=>0, 'filter'=>'true', 'tahun'=>[now()->year],
                                'type_direktorat'=>'bar', 'direktorat'=>$select_direktorat,
                                'type_divisi'=>'bar', 'divisi'=>$select_divisi,
                                'type_departemen'=>'bar', 'departemen'=>$select_departemen,
                                'type_status_kerja'=>'bar', 'status_kerja'=>$select_status_kerja,
                                'type_area'=>'bar', 'area'=>$select_area,
                                ])); ?>">Recruitment</a></li>
                            <?php endif; ?>
                        </ul>
                    </li>
					<?php endif; ?>
                    
                    
                    <?php $allowed_nik = ['P0826165']; ?>
                    <?php if(auth()->user()->can('hc') || in_array(auth()->user()->nik, $allowed_nik)): ?>
                    <li class="sidebar-main-title">
                        <div>
                            <h6>Recruitment</h6>
                        </div>
                    </li>
                    
                    <li class="sidebar-list"><a class="sidebar-link sidebar-title link-nav" href="<?php echo e(route('recruitment.applicant_list', 0)); ?>">
                        <svg class="stroke-icon">
                            <use href="<?php echo e(asset('assets/svg/icon-sprite.svg#stroke-form')); ?>"></use>
                        </svg>
                        <svg class="fill-icon">
                            <use href="<?php echo e(asset('assets/svg/icon-sprite.svg#fill-form')); ?>"> </use>
                        </svg><span>List of Applicant</span></a></li>
                    <li class="sidebar-list"><a class="sidebar-link sidebar-title link-nav" href="<?php echo e(route('recruitment.index')); ?>">
                        <svg class="stroke-icon">
                            <use href="<?php echo e(asset('assets/svg/icon-sprite.svg#stroke-form')); ?>"></use>
                        </svg>
                        <svg class="fill-icon">
                            <use href="<?php echo e(asset('assets/svg/icon-sprite.svg#fill-form')); ?>"> </use>
                        </svg><span>List of Recruitment</span></a></li>
                    <?php endif; ?>
                    
                    <li class="sidebar-main-title">
                        <div>
                            <h6>Payroll</h6>
                        </div>
                    </li>
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('payroll')): ?>
                    <li class="sidebar-list"><a class="sidebar-link sidebar-title link-nav" href="<?php echo e(route('payroll.send_email', 0)); ?>">
                            <svg class="stroke-icon">
                                <use href="<?php echo e(asset('assets/svg/icon-sprite.svg#stroke-others')); ?>"></use>
                            </svg>
                            <svg class="fill-icon">
                                <use href="<?php echo e(asset('assets/svg/icon-sprite.svg#fill-others')); ?>"></use>
                            </svg><span>Payroll Email</span></a></li>
                    <li class="sidebar-list"><a class="sidebar-link sidebar-title link-nav" href="<?php echo e(route('payroll.employee_gap', 0)); ?>">
                            <svg class="stroke-icon">
                                <use href="<?php echo e(asset('assets/svg/icon-sprite.svg#stroke-others')); ?>"></use>
                            </svg>
                            <svg class="fill-icon">
                                <use href="<?php echo e(asset('assets/svg/icon-sprite.svg#fill-others')); ?>"></use>
                            </svg><span>Payroll Employee Gap</span></a></li>
                    <?php endif; ?>
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('payroll_hco_fin')): ?>
                    <li class="sidebar-list"><a class="sidebar-link sidebar-title link-nav" href="<?php echo e(route('payroll.history', 0)); ?>">
                            <svg class="stroke-icon">
                                <use href="<?php echo e(asset('assets/svg/icon-sprite.svg#stroke-others')); ?>"></use>
                            </svg>
                            <svg class="fill-icon">
                                <use href="<?php echo e(asset('assets/svg/icon-sprite.svg#fill-others')); ?>"></use>
                            </svg><span>Payroll History</span></a></li>
                    <?php endif; ?>
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('payroll')): ?>
                    <li class="sidebar-list"><a class="sidebar-link sidebar-title link-nav" href="<?php echo e(route('payroll.master', 0)); ?>">
                            <svg class="stroke-icon">
                                <use href="<?php echo e(asset('assets/svg/icon-sprite.svg#stroke-others')); ?>"></use>
                            </svg>
                            <svg class="fill-icon">
                                <use href="<?php echo e(asset('assets/svg/icon-sprite.svg#fill-others')); ?>"></use>
                            </svg><span>Payroll Master</span></a></li>
                    <?php endif; ?>
                    <li class="sidebar-list"><a class="sidebar-link sidebar-title link-nav" href="<?php echo e(route('payroll.slip', (new App\Http\Controllers\OpenDataController)->encryptWithKey(auth()->user()->nik))); ?>">
                            <svg class="stroke-icon">
                                <use href="<?php echo e(asset('assets/svg/icon-sprite.svg#stroke-others')); ?>"></use>
                            </svg>
                            <svg class="fill-icon">
                                <use href="<?php echo e(asset('assets/svg/icon-sprite.svg#fill-others')); ?>"></use>
                            </svg><span>Payroll Slip</span></a></li>
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('payroll_hco_fin')): ?>
                    <li class="sidebar-list"><a class="sidebar-link sidebar-title link-nav" href="<?php echo e(route('payroll.index')); ?>">
                            <svg class="stroke-icon">
                                <use href="<?php echo e(asset('assets/svg/icon-sprite.svg#stroke-others')); ?>"></use>
                            </svg>
                            <svg class="fill-icon">
                                <use href="<?php echo e(asset('assets/svg/icon-sprite.svg#fill-others')); ?>"></use>
                            </svg><span>Payroll Transaction</span></a></li>
                    <?php endif; ?>
                    <li class="sidebar-main-title">
                        <div>
                            <h6>Attendance</h6>
                        </div>
                    </li>
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('hc')): ?>
                    <li class="sidebar-list"><a class="sidebar-link sidebar-title link-nav" href="<?php echo e(route('attendance_download.index')); ?>">
                            <svg class="stroke-icon">
                                <use href="<?php echo e(asset('assets/svg/icon-sprite.svg#stroke-table')); ?>"></use>
                            </svg>
                            <svg class="fill-icon">
                                <use href="<?php echo e(asset('assets/svg/icon-sprite.svg#fill-table')); ?>"></use>
                            </svg><span>Attendance Download</span></a></li>
                    <?php endif; ?>
                    <li class="sidebar-list"><a class="sidebar-link sidebar-title link-nav" href="<?php echo e(route('attendance.index')); ?>">
                            <svg class="stroke-icon">
                                <use href="<?php echo e(asset('assets/svg/icon-sprite.svg#stroke-table')); ?>"></use>
                            </svg>
                            <svg class="fill-icon">
                                <use href="<?php echo e(asset('assets/svg/icon-sprite.svg#fill-table')); ?>"></use>
                            </svg><span>Attendance Log</span></a></li>
                    <li class="sidebar-list"><a class="sidebar-link sidebar-title link-nav" href="<?php echo e(route('recap_attendance.index')); ?>">
                            <svg class="stroke-icon">
                                <use href="<?php echo e(asset('assets/svg/icon-sprite.svg#stroke-table')); ?>"></use>
                            </svg>
                            <svg class="fill-icon">
                                <use href="<?php echo e(asset('assets/svg/icon-sprite.svg#fill-table')); ?>"></use>
                            </svg><span>Attendance Recap</span></a></li>
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('hc')): ?>
                    <li class="sidebar-list"><a class="sidebar-link sidebar-title link-nav" href="<?php echo e(route('attendance.source', 1)); ?>">
                            <svg class="stroke-icon">
                                <use href="<?php echo e(asset('assets/svg/icon-sprite.svg#stroke-table')); ?>"></use>
                            </svg>
                            <svg class="fill-icon">
                                <use href="<?php echo e(asset('assets/svg/icon-sprite.svg#fill-table')); ?>"></use>
                            </svg><span>Attendance Source</span></a></li>
                    <li class="sidebar-list"><a class="sidebar-link sidebar-title link-nav" href="<?php echo e(route('health.index')); ?>">
                            <svg class="stroke-icon">
                                <use href="<?php echo e(asset('assets/svg/icon-sprite.svg#stroke-form')); ?>"></use>
                            </svg>
                            <svg class="fill-icon">
                                <use href="<?php echo e(asset('assets/svg/icon-sprite.svg#fill-form')); ?>"></use>
                            </svg><span>Health Reporting</span></a></li>
                    <li class="sidebar-list"><a class="sidebar-link sidebar-title link-nav" href="<?php echo e(route('noattendance.index')); ?>">
                            <svg class="stroke-icon">
                                <use href="<?php echo e(asset('assets/svg/icon-sprite.svg#stroke-table')); ?>"></use>
                            </svg>
                            <svg class="fill-icon">
                                <use href="<?php echo e(asset('assets/svg/icon-sprite.svg#fill-table')); ?>"></use>
                            </svg><span>No Attendance</span></a></li>
                    <li class="sidebar-list"><a class="sidebar-link sidebar-title link-nav" href="<?php echo e(route('reminder_sp.index')); ?>">
                            <svg class="stroke-icon">
                                <use href="<?php echo e(asset('assets/svg/icon-sprite.svg#stroke-table')); ?>"></use>
                            </svg>
                            <svg class="fill-icon">
                                <use href="<?php echo e(asset('assets/svg/icon-sprite.svg#fill-table')); ?>"></use>
                            </svg><span>Reminder Warning Letter</span></a></li>
                    <?php endif; ?>
                    <li class="sidebar-main-title">
                        <div>
                            <h6>Overtime</h6>
                        </div>
                    </li>
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('spv')): ?>
                        <li class="sidebar-list">
                            <a class="sidebar-link sidebar-title link-nav" href="<?php echo e(route('approval-index')); ?>">
                                <svg class="stroke-icon">
                                    <use href="<?php echo e(asset('assets/svg/icon-sprite.svg#stroke-to-do')); ?>"></use>
                                </svg>
                                <svg class="fill-icon">
                                    <use href="<?php echo e(asset('assets/svg/icon-sprite.svg#fill-to-do')); ?>"></use>
                                </svg><span>Approval</span>
                            </a>
                        </li>
                    <?php endif; ?>
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('staf')): ?>
                        <li class="sidebar-list">
                            <a class="sidebar-link sidebar-title link-nav" href="<?php echo e(route('overtime.index')); ?>">
                                <svg class="stroke-icon">
                                    <use href="<?php echo e(asset('assets/svg/icon-sprite.svg#stroke-task')); ?>"></use>
                                </svg>
                                <svg class="fill-icon">
                                    <use href="<?php echo e(asset('assets/svg/icon-sprite.svg#fill-task')); ?>"></use>
                                </svg><span>Propose</span>
                            </a>
                        </li>
                    <?php endif; ?>
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('spv')): ?>
                        <li class="sidebar-list">
                            <a class="sidebar-link sidebar-title link-nav" href="<?php echo e(route('reporting-index')); ?>">
                                <svg class="stroke-icon">
                                    <use href="<?php echo e(asset('assets/svg/icon-sprite.svg#stroke-icons')); ?>"></use>
                                </svg>
                                <svg class="fill-icon">
                                    <use href="<?php echo e(asset('assets/svg/icon-sprite.svg#fill-icons')); ?>"></use>
                                </svg><span>Reporting</span>
                            </a>
                        </li>
                    <?php endif; ?>
                    <!-- <li class="sidebar-main-title">
                        <div>
                            <h6>Reports</h6>
                        </div>
                    </li>
                    <li class="sidebar-list"><a class="sidebar-link sidebar-title" href="#">
                            <svg class="stroke-icon">
                                <use href="<?php echo e(asset('assets/svg/icon-sprite.svg#stroke-widget')); ?>"></use>
                            </svg>
                            <svg class="fill-icon">
                                <use href="<?php echo e(asset('assets/svg/icon-sprite.svg#fill-widget')); ?>"></use>
                            </svg><span>Reports</span></a>
                        <ul class="sidebar-submenu">
                            <li><a href="<?php echo e(route('general-widget')); ?>">General</a></li>
                            <li><a href="<?php echo e(route('bootstrap-basic-table')); ?>">Table</a></li>
                        </ul>
                    </li> -->
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('hc')): ?>
                        <li class="sidebar-main-title">
                            <div>
                                <h6>Data Master & Setting</h6>
                            </div>
                        </li>
                        <li class="sidebar-list"><a class="sidebar-link sidebar-title" href="#">
                                <svg class="stroke-icon">
                                    <use href="<?php echo e(asset('assets/svg/icon-sprite.svg#stroke-project')); ?>"></use>
                                </svg>
                                <svg class="fill-icon">
                                    <use href="<?php echo e(asset('assets/svg/icon-sprite.svg#fill-project')); ?>"></use>
                                </svg><span>Data Master</span></a>
                            <ul class="sidebar-submenu">
                                <li><a href="<?php echo e(route('banner.index')); ?>">Banner</a></li>
                                <li><a href="<?php echo e(route('perusahaan.index')); ?>">Company</a></li>
                                <li><a href="<?php echo e(route('subdepartemen.index')); ?>">Department</a></li>
                                <li><a href="<?php echo e(route('divisi.index')); ?>">Directorate</a></li>
                                <li><a href="<?php echo e(route('departemen.index')); ?>">Division</a></li>
                                <li><a href="<?php echo e(route('karyawan.index')); ?>">Employee</a></li>
                                <li><a href="<?php echo e(route('feeds.index')); ?>">Feeds</a></li>
                                <li><a href="<?php echo e(route('findim_departemen.index')); ?>">FinDim Department</a></li>
                                <li><a href="<?php echo e(route('findim_divisi.index')); ?>">FinDim Division</a></li>
                                <li><a href="<?php echo e(route('findim_project.index')); ?>">FinDim Project</a></li>
                                <li><a href="<?php echo e(route('findim_subdepartemen.index')); ?>">FinDim Sub Department</a></li>
                                <li><a href="<?php echo e(route('hcouser.index')); ?>"><?php echo e(App\Models\MtrUtility::find(51)->informasi); ?> Team</a></li>
                                <li><a href="<?php echo e(route('plafon.index')); ?>">Health Reimbursement<br />Limits</a></li>
                                <li><a href="<?php echo e(route('iphoneuser.index')); ?>">iPhone Users</a></li>
                                <li><a href="<?php echo e(route('pekerjaan_bidang.index')); ?>">Job Field</a></li>
                                <li><a href="<?php echo e(route('jabatan.index')); ?>">Job Position</a></li>
                                <li><a href="<?php echo e(route('join.index')); ?>">Join Status</a></li>
                                <li><a href="<?php echo e(route('holiday.index')); ?>">National Holiday</a></li>
                                <li><a href="<?php echo e(route('pdp.index')); ?>">PDP</a></li>
                                <li><a href="<?php echo e(route('salary_str.index')); ?>">Salary Structure</a></li>
                                <li><a href="<?php echo e(route('utilitas.index')); ?>">Utility</a></li>
                            </ul>
                        </li>
                        <li class="sidebar-list"><a class="sidebar-link sidebar-title" href="#">
                                <svg class="stroke-icon">
                                    <use href="<?php echo e(asset('assets/svg/icon-sprite.svg#stroke-project')); ?>"></use>
                                </svg>
                                <svg class="fill-icon">
                                    <use href="<?php echo e(asset('assets/svg/icon-sprite.svg#fill-project')); ?>"></use>
                                </svg><span>Shift Schedule</span></a>
                            <ul class="sidebar-submenu">
                                <li><a href="<?php echo e(route('jadwalcls.index')); ?>">CLS Project</a></li>
                                <li><a href="<?php echo e(route('jadwalcls2.index')); ?>">CLS Project 2</a></li>
                                <li><a href="<?php echo e(route('jadwalcls3.index')); ?>">CLS Project 3</a></li>
                                <li><a href="<?php echo e(route('working.show', [22, 'req'=>'view'])); ?>">NOC 3 Group</a></li>
                                <li><a href="<?php echo e(route('jadwalnoc.index')); ?>">NOC Alita Fiber Hub</a></li>
                                <li><a href="<?php echo e(route('jadwalnoc2.index')); ?>">NOC Alita Fiber Hub 2</a></li>
                                
                                <li><a href="<?php echo e(route('working.show', [15, 'req'=>'view'])); ?>">QA Technical Support</a></li>
                                <li><a href="<?php echo e(route('jadwalqatsleader.index')); ?>">QA Technical Support<br />Leader</a></li>
                                <li><a href="<?php echo e(route('jadwalqatsleader2.index')); ?>">QA Technical Support<br />Leader 2</a></li>
                                <li><a href="<?php echo e(route('jadwalqatsmember.index')); ?>">QA Technical Support<br />Member</a></li>
                                <li><a href="<?php echo e(route('jadwalservicedesk.index')); ?>">QA Technical Support<br />Service Desk</a></li>
                                <li><a href="<?php echo e(route('jadwalrandom.index')); ?>">Random</a></li>
                                <li><a href="<?php echo e(route('working.show', [21, 'req'=>'view'])); ?>">Reguler Freelance</a></li>
                                <li><a href="<?php echo e(route('jadwalsec.index')); ?>">Security</a></li>
                            </ul>
                        </li>
                        <li class="sidebar-list"><a class="sidebar-link sidebar-title" href="#">
                                <svg class="stroke-icon">
                                    <use href="<?php echo e(asset('assets/svg/icon-sprite.svg#stroke-project')); ?>"></use>
                                </svg>
                                <svg class="fill-icon">
                                    <use href="<?php echo e(asset('assets/svg/icon-sprite.svg#fill-project')); ?>"></use>
                                </svg><span>Travel Expense</span></a>
                            <ul class="sidebar-submenu">
                                <li><a href="<?php echo e(route('mtrdatabodbpd.index')); ?>">BOD Line Approval</a></li>
                                <li><a href="<?php echo e(route('mtrdatacoabpd.index')); ?>">COA</a></li>
                                <li><a href="<?php echo e(route('mtrdatacoordinatorbpd.index')); ?>">Coordinator</a></li>
                                <li><a href="<?php echo e(route('mtrdatabpd.index')); ?>">NIP vs Admin</a></li>
                                <li><a href="<?php echo e(route('mtrdataprojectadmin.index')); ?>">Project vs Admin</a></li>
                            </ul>
                        </li>
                    <?php endif; ?>
                    <!-- <li class="sidebar-list"><a class="sidebar-link sidebar-title" href="#">
                            <svg class="stroke-icon">
                                <use href="<?php echo e(asset('assets/svg/icon-sprite.svg#stroke-others')); ?>"></use>
                            </svg>
                            <svg class="fill-icon">
                                <use href="<?php echo e(asset('assets/svg/icon-sprite.svg#fill-others')); ?>"></use>
                            </svg><span>Setting</span></a>
                        <ul class="sidebar-submenu">
                            <li><a href="#">Data 2.1</a></li>
                            <li><a href="#">Data 2.2</a></li>
                        </ul>
                    </li> -->
                    <!-- <li class="sidebar-list">
                        <label class="badge badge-light-secondary">New</label><a class="sidebar-link sidebar-title"
                            href="#">
                            <svg class="stroke-icon">
                                <use href="<?php echo e(asset('assets/svg/icon-sprite.svg#stroke-project')); ?>"></use>
                            </svg>
                            <svg class="fill-icon">
                                <use href="<?php echo e(asset('assets/svg/icon-sprite.svg#fill-project')); ?>"></use>
                            </svg><span>Project </span></a>
                        <ul class="sidebar-submenu">
                            <li><a href="<?php echo e(route('projects')); ?>">Project List</a></li>
                            <li><a href="<?php echo e(route('projectcreate')); ?>">Create new</a></li>
                        </ul>
                    </li>
                    <li class="sidebar-list"><a class="sidebar-link sidebar-title link-nav" href="<?php echo e(route('file-manager')); ?>">
                            <svg class="stroke-icon">
                                <use href="<?php echo e(asset('assets/svg/icon-sprite.svg#stroke-file')); ?>"></use>
                            </svg>
                            <svg class="fill-icon">
                                <use href="<?php echo e(asset('assets/svg/icon-sprite.svg#fill-file')); ?>"></use>
                            </svg><span>File manager</span></a></li>
                    <li class="sidebar-list">
                        <label class="badge badge-light-danger">Latest </label><a
                            class="sidebar-link sidebar-title link-nav" href="<?php echo e(route('kanban')); ?>">
                            <svg class="stroke-icon">
                                <use href="<?php echo e(asset('assets/svg/icon-sprite.svg#stroke-board')); ?>"></use>
                            </svg>
                            <svg class="fill-icon">
                                <use href="<?php echo e(asset('assets/svg/icon-sprite.svg#fill-board')); ?>"></use>
                            </svg><span>kanban Board</span></a>
                    </li>
                    <li class="sidebar-list"><a class="sidebar-link sidebar-title" href="#">
                            <svg class="stroke-icon">
                                <use href="<?php echo e(asset('assets/svg/icon-sprite.svg#stroke-ecommerce')); ?>"></use>
                            </svg>
                            <svg class="fill-icon">
                                <use href="<?php echo e(asset('assets/svg/icon-sprite.svg#fill-ecommerce')); ?>"></use>
                            </svg><span>Ecommerce</span></a>
                        <ul class="sidebar-submenu">
                            <li><a href="<?php echo e(route('product')); ?>">Product</a></li>
                            <li><a href="<?php echo e(route('product-page')); ?>">Product page</a></li>
                            <li><a href="<?php echo e(route('list-products')); ?>">Product list</a></li>
                            <li><a href="<?php echo e(route('payment-details')); ?>">Payment Details</a></li>
                            <li><a href="<?php echo e(route('order-history')); ?>">Order History</a></li>
                            <li><a href="<?php echo e(route('invoice-template')); ?>">Invoice</a></li>
                            <li><a href="<?php echo e(route('cart')); ?>">Cart</a></li>
                            <li><a href="<?php echo e(route('list-wish')); ?>">Wishlist</a></li>
                            <li><a href="<?php echo e(route('checkout')); ?>">Checkout</a></li>
                            <li><a href="<?php echo e(route('pricing')); ?>">Pricing </a></li>
                        </ul>
                    </li>
                    <li class="sidebar-list"><a class="sidebar-link sidebar-title" href="#">
                            <svg class="stroke-icon">
                                <use href="<?php echo e(asset('assets/svg/icon-sprite.svg#stroke-email')); ?>"></use>
                            </svg>
                            <svg class="fill-icon">
                                <use href="<?php echo e(asset('assets/svg/icon-sprite.svg#fill-email')); ?>"></use>
                            </svg><span>Email</span></a>
                        <ul class="sidebar-submenu">
                            <li><a href="<?php echo e(route('email-application')); ?>">Email App</a></li>
                            <li><a href="<?php echo e(route('email-compose')); ?>">Email Compose</a></li>
                        </ul>
                    </li>
                    <li class="sidebar-list"><a class="sidebar-link sidebar-title" href="#">
                            <svg class="stroke-icon">
                                <use href="<?php echo e(asset('assets/svg/icon-sprite.svg#stroke-chat')); ?>"></use>
                            </svg>
                            <svg class="fill-icon">
                                <use href="<?php echo e(asset('assets/svg/icon-sprite.svg#fill-chat')); ?>"></use>
                            </svg><span>Chat</span></a>
                        <ul class="sidebar-submenu">
                            <li><a href="<?php echo e(route('chat')); ?>">Chat App</a></li>
                            <li><a href="<?php echo e(route('chat-video')); ?>">Video chat</a></li>
                        </ul>
                    </li>
                    <li class="sidebar-list"><a class="sidebar-link sidebar-title" href="#">
                            <svg class="stroke-icon">
                                <use href="<?php echo e(asset('assets/svg/icon-sprite.svg#stroke-user')); ?>"></use>
                            </svg>
                            <svg class="fill-icon">
                                <use href="<?php echo e(asset('assets/svg/icon-sprite.svg#fill-user')); ?>"></use>
                            </svg><span>Users</span></a>
                        <ul class="sidebar-submenu">
                            <li><a href="<?php echo e(route('user-profile')); ?>">Users Profile</a></li>
                            <li><a href="<?php echo e(route('edit-profile')); ?>">Users Edit</a></li>
                            <li><a href="<?php echo e(route('user-cards')); ?>">Users Cards</a></li>
                        </ul>
                    </li>
                    <li class="sidebar-list"><a class="sidebar-link sidebar-title link-nav" href="<?php echo e(route('bookmark')); ?>">
                            <svg class="stroke-icon">
                                <use href="<?php echo e(asset('assets/svg/icon-sprite.svg#stroke-bookmark')); ?>"></use>
                            </svg>
                            <svg class="fill-icon">
                                <use href="<?php echo e(asset('assets/svg/icon-sprite.svg#fill-bookmark')); ?>"> </use>
                            </svg><span>Bookmarks</span></a></li>
                    <li class="sidebar-list"><a class="sidebar-link sidebar-title link-nav" href="<?php echo e(route('contacts')); ?>">
                            <svg class="stroke-icon">
                                <use href="<?php echo e(asset('assets/svg/icon-sprite.svg#stroke-contact')); ?>"></use>
                            </svg>
                            <svg class="fill-icon">
                                <use href="<?php echo e(asset('assets/svg/icon-sprite.svg#fill-contact')); ?>"> </use>
                            </svg><span>Contacts</span></a></li>
                    <li class="sidebar-list"><a class="sidebar-link sidebar-title link-nav" href="<?php echo e(route('task')); ?>">
                            <svg class="stroke-icon">
                                <use href="<?php echo e(asset('assets/svg/icon-sprite.svg#stroke-task')); ?>"></use>
                            </svg>
                            <svg class="fill-icon">
                                <use href="<?php echo e(asset('assets/svg/icon-sprite.svg#fill-task')); ?>"> </use>
                            </svg><span>Tasks</span></a></li>
                    <li class="sidebar-list"><a class="sidebar-link sidebar-title link-nav"
                            href="<?php echo e(route('calendar-basic')); ?>">
                            <svg class="stroke-icon">
                                <use href="<?php echo e(asset('assets/svg/icon-sprite.svg#stroke-calendar')); ?>"></use>
                            </svg>
                            <svg class="fill-icon">
                                <use href="<?php echo e(asset('assets/svg/icon-sprite.svg#fill-calender')); ?>"></use>
                            </svg><span>Calendar</span></a></li>
                    <li class="sidebar-list"><a class="sidebar-link sidebar-title link-nav" href="<?php echo e(route('social-app')); ?>">
                            <svg class="stroke-icon">
                                <use href="<?php echo e(asset('assets/svg/icon-sprite.svg#stroke-social')); ?>"></use>
                            </svg>
                            <svg class="fill-icon">
                                <use href="<?php echo e(asset('assets/svg/icon-sprite.svg#fill-social')); ?>"> </use>
                            </svg><span>Social App</span></a></li>
                    <li class="sidebar-list"><a class="sidebar-link sidebar-title link-nav" href="<?php echo e(route('to-do')); ?>">
                            <svg class="stroke-icon">
                                <use href="<?php echo e(asset('assets/svg/icon-sprite.svg#stroke-to-do')); ?>"></use>
                            </svg>
                            <svg class="fill-icon">
                                <use href="<?php echo e(asset('assets/svg/icon-sprite.svg#fill-to-do')); ?>"> </use>
                            </svg><span>To-Do</span></a></li>
                    <li class="sidebar-list"><a class="sidebar-link sidebar-title link-nav" href="<?php echo e(route('search')); ?>">
                            <svg class="stroke-icon">
                                <use href="<?php echo e(asset('assets/svg/icon-sprite.svg#stroke-search')); ?>"></use>
                            </svg>
                            <svg class="fill-icon">
                                <use href="<?php echo e(asset('assets/svg/icon-sprite.svg#fill-search')); ?>"> </use>
                            </svg><span>Search Result</span></a></li>
                    <li class="sidebar-main-title">
                        <div>
                            <h6>Forms & Table</h6>
                        </div>
                    </li>
                    <li class="sidebar-list"><a class="sidebar-link sidebar-title" href="#">
                            <svg class="stroke-icon">
                                <use href="<?php echo e(asset('assets/svg/icon-sprite.svg#stroke-form')); ?>"></use>
                            </svg>
                            <svg class="fill-icon">
                                <use href="<?php echo e(asset('assets/svg/icon-sprite.svg#fill-form')); ?>"> </use>
                            </svg><span>Forms</span></a>
                        <ul class="sidebar-submenu">
                            <li><a class="submenu-title" href="#">Form Controls<span class="sub-arrow"><i class="fa fa-angle-right"></i></span></a>
                                <ul class="nav-sub-childmenu submenu-content">
                                    <li><a href="<?php echo e(route('form-validation')); ?>">Form Validation</a></li>
                                    <li><a href="<?php echo e(route('base-input')); ?>">Base Inputs</a></li>
                                    <li><a href="<?php echo e(route('radio-checkbox-control')); ?>">Checkbox & Radio</a></li>
                                    <li><a href="<?php echo e(route('input-group')); ?>">Input Groups</a></li>
                                    <li><a href="<?php echo e(route('megaoptions')); ?>">Mega Options</a></li>
                                </ul>
                            </li>
                            <li><a class="submenu-title" href="#">Form Widgets<span class="sub-arrow"><i class="fa fa-angle-right"></i></span></a>
                                <ul class="nav-sub-childmenu submenu-content">
                                    <li><a href="<?php echo e(route('datepicker')); ?>">Datepicker</a></li>
                                    <li><a href="<?php echo e(route('time-picker')); ?>">Timepicker</a></li>
                                    <li><a href="<?php echo e(route('datetimepicker')); ?>">Datetimepicker</a></li>
                                    <li><a href="<?php echo e(route('daterangepicker')); ?>">Daterangepicker</a></li>
                                    <li><a href="<?php echo e(route('touchspin')); ?>">Touchspin</a></li>
                                    <li><a href="<?php echo e(route('select2')); ?>">Select2</a></li>
                                    <li><a href="<?php echo e(route('switch')); ?>">Switch</a></li>
                                    <li><a href="<?php echo e(route('typeahead')); ?>">Typeahead</a></li>
                                    <li><a href="<?php echo e(route('clipboard')); ?>">Clipboard</a></li>
                                </ul>
                            </li>
                            <li><a class="submenu-title" href="#">Form layout<span class="sub-arrow"><i class="fa fa-angle-right"></i></span></a>
                                <ul class="nav-sub-childmenu submenu-content">
                                    <li><a href="<?php echo e(route('default-form')); ?>">Default Forms</a></li>
                                    <li><a href="<?php echo e(route('form-wizard')); ?>">Form Wizard 1</a></li>
                                    <li><a href="<?php echo e(route('form-wizard-two')); ?>">Form Wizard 2</a></li>
                                    <li><a href="<?php echo e(route('form-wizard-three')); ?>">Form Wizard 3</a></li>
                                </ul>
                            </li>
                        </ul>
                    </li>
                    <li class="sidebar-list"><a class="sidebar-link sidebar-title" href="#">
                            <svg class="stroke-icon">
                                <use href="<?php echo e(asset('assets/svg/icon-sprite.svg#stroke-table')); ?>"></use>
                            </svg>
                            <svg class="fill-icon">
                                <use href="<?php echo e(asset('assets/svg/icon-sprite.svg#fill-table')); ?>"></use>
                            </svg><span>Tables</span></a>
                        <ul class="sidebar-submenu">
                            <li><a class="submenu-title" href="#">Bootstrap Tables<span class="sub-arrow"><i class="fa fa-angle-right"></i></span></a>
                                <ul class="nav-sub-childmenu submenu-content">
                                    <li><a href="<?php echo e(route('bootstrap-basic-table')); ?>">Basic Tables</a></li>
                                    <li><a href="<?php echo e(route('table-components')); ?>">Table components</a></li>
                                </ul>
                            </li>
                            <li><a class="submenu-title" href="#">Data Tables<span class="sub-arrow"><i class="fa fa-angle-right"></i></span></a>
                                <ul class="nav-sub-childmenu submenu-content">
                                    <li><a href="<?php echo e(route('datatable-basic-init')); ?>">Basic Init</a></li>
                                    <li><a href="<?php echo e(route('datatable-api')); ?>">API</a></li>
                                    <li><a href="<?php echo e(route('datatable-data-source')); ?>">Data Sources</a></li>
                                </ul>
                            </li>
                            <li><a href="<?php echo e(route('datatable-ext-autofill')); ?>">Ex. Data Tables</a></li>
                            <li><a href="<?php echo e(route('jsgrid-table')); ?>">Js Grid Table </a></li>
                        </ul>
                    </li>
                    <li class="sidebar-main-title">
                        <div>
                            <h6>Components</h6>
                        </div>
                    </li>
                    <li class="sidebar-list"><a class="sidebar-link sidebar-title" href="#">
                            <svg class="stroke-icon">
                                <use href="<?php echo e(asset('assets/svg/icon-sprite.svg#stroke-ui-kits')); ?>"></use>
                            </svg>
                            <svg class="fill-icon">
                                <use href="<?php echo e(asset('assets/svg/icon-sprite.svg#fill-ui-kits')); ?>"></use>
                            </svg><span>Ui Kits</span></a>
                        <ul class="sidebar-submenu">
                            <li><a href="<?php echo e(route('typography')); ?>">Typography</a></li>
                            <li><a href="<?php echo e(route('avatars')); ?>">Avatars</a></li>
                            <li><a href="<?php echo e(route('helper-classes')); ?>">helper classes</a></li>
                            <li><a href="<?php echo e(route('grid')); ?>">Grid</a></li>
                            <li><a href="<?php echo e(route('tag-pills')); ?>">Tag & pills</a></li>
                            <li><a href="<?php echo e(route('progress-bar')); ?>">Progress</a></li>
                            <li><a href="<?php echo e(route('modal')); ?>">Modal</a></li>
                            <li><a href="<?php echo e(route('alert')); ?>">Alert</a></li>
                            <li><a href="<?php echo e(route('popover')); ?>">Popover</a></li>
                            <li><a href="<?php echo e(route('tooltip')); ?>">Tooltip</a></li>
                            <li><a href="<?php echo e(route('loader')); ?>">Spinners</a></li>
                            <li><a href="<?php echo e(route('dropdown')); ?>">Dropdown</a></li>
                            <li><a href="<?php echo e(route('accordion')); ?>">Accordion</a></li>
                            <li><a class="submenu-title" href="#">Tabs<span class="sub-arrow"><i class="fa fa-angle-right"></i></span></a>
                                <ul class="nav-sub-childmenu submenu-content">
                                    <li><a href="<?php echo e(route('tab-bootstrap')); ?>">Bootstrap Tabs</a></li>
                                    <li><a href="<?php echo e(route('tab-material')); ?>">Line Tabs</a></li>
                                </ul>
                            </li>
                            <li><a href="<?php echo e(route('box-shadow')); ?>">Shadow</a></li>
                            <li><a href="<?php echo e(route('list')); ?>">Lists</a></li>
                        </ul>
                    </li>
                    <li class="sidebar-list"><a class="sidebar-link sidebar-title" href="#">
                            <svg class="stroke-icon">
                                <use href="<?php echo e(asset('assets/svg/icon-sprite.svg#stroke-bonus-kit')); ?>"></use>
                            </svg>
                            <svg class="fill-icon">
                                <use href="<?php echo e(asset('assets/svg/icon-sprite.svg#fill-bonus-kit')); ?>"></use>
                            </svg><span>Bonus Ui</span></a>
                        <ul class="sidebar-submenu">
                            <li><a href="<?php echo e(route('scrollable')); ?>">Scrollable</a></li>
                            <li><a href="<?php echo e(route('tree')); ?>">Tree view</a></li>
                            <li><a href="<?php echo e(route('bootstrap-notify')); ?>">Bootstrap Notify</a></li>
                            <li><a href="<?php echo e(route('rating')); ?>">Rating</a></li>
                            <li><a href="<?php echo e(route('dropzone')); ?>">dropzone</a></li>
                            <li><a href="<?php echo e(route('tour')); ?>">Tour</a></li>
                            <li><a href="<?php echo e(route('sweet-alert2')); ?>">SweetAlert2</a></li>
                            <li><a href="<?php echo e(route('modal-animated')); ?>">Animated Modal</a></li>
                            <li><a href="<?php echo e(route('owl-carousel')); ?>">Owl Carousel</a></li>
                            <li><a href="<?php echo e(route('ribbons')); ?>">Ribbons</a></li>
                            <li><a href="<?php echo e(route('pagination')); ?>">Pagination</a></li>
                            <li><a href="<?php echo e(route('breadcrumb')); ?>">Breadcrumb</a></li>
                            <li><a href="<?php echo e(route('range-slider')); ?>">Range Slider</a></li>
                            <li><a href="<?php echo e(route('image-cropper')); ?>">Image cropper</a></li>
                            <li><a href="<?php echo e(route('sticky')); ?>">Sticky</a></li>
                            <li><a href="<?php echo e(route('basic-card')); ?>">Basic Card</a></li>
                            <li><a href="<?php echo e(route('creative-card')); ?>">Creative Card</a></li>
                            <li><a href="<?php echo e(route('tabbed-card')); ?>">Tabbed Card</a></li>
                            <li><a href="<?php echo e(route('dragable-card')); ?>">Draggable Card</a></li>
                            <li><a class="submenu-title" href="#">Timeline<span class="sub-arrow"><i
                                            class="fa fa-angle-right"></i></span></a>
                                <ul class="nav-sub-childmenu submenu-content">
                                    <li><a href="<?php echo e(route('timeline-v-1')); ?>">Timeline 1</a></li>
                                    <li><a href="<?php echo e(route('timeline-v-2')); ?>">Timeline 2</a></li>
                                </ul>
                            </li>
                        </ul>
                    </li>
                    <li class="sidebar-list"><a class="sidebar-link sidebar-title" href="#">
                            <svg class="stroke-icon">
                                <use href="<?php echo e(asset('assets/svg/icon-sprite.svg#stroke-builders')); ?>"></use>
                            </svg>
                            <svg class="fill-icon">
                                <use href="<?php echo e(asset('assets/svg/icon-sprite.svg#fill-builders')); ?>"></use>
                            </svg><span>Builders</span></a>
                        <ul class="sidebar-submenu">
                            <li><a href="<?php echo e(route('form-builder-1')); ?>"> Form Builder 1</a></li>
                            <li><a href="<?php echo e(route('form-builder-2')); ?>"> Form Builder 2</a></li>
                            <li><a href="<?php echo e(route('pagebuild')); ?>">Page Builder</a></li>
                            <li><a href="<?php echo e(route('button-builder')); ?>">Button Builder</a></li>
                        </ul>
                    </li>
                    <li class="sidebar-list"><a class="sidebar-link sidebar-title" href="#">
                            <svg class="stroke-icon">
                                <use href="<?php echo e(asset('assets/svg/icon-sprite.svg#stroke-animation')); ?>"></use>
                            </svg>
                            <svg class="fill-icon">
                                <use href="<?php echo e(asset('assets/svg/icon-sprite.svg#fill-animation')); ?>"></use>
                            </svg><span>Animation</span></a>
                        <ul class="sidebar-submenu">
                            <li><a href="<?php echo e(route('animate')); ?>">Animate</a></li>
                            <li><a href="<?php echo e(route('scroll-reval')); ?>">Scroll Reveal</a></li>
                            <li><a href="<?php echo e(route('aos')); ?>">AOS animation</a></li>
                            <li><a href="<?php echo e(route('tilt')); ?>">Tilt Animation</a></li>
                            <li><a href="<?php echo e(route('wow')); ?>">Wow Animation</a></li>
                        </ul>
                    </li>
                    <li class="sidebar-list"><a class="sidebar-link sidebar-title" href="#">
                            <svg class="stroke-icon">
                                <use href="<?php echo e(asset('assets/svg/icon-sprite.svg#stroke-icons')); ?>"></use>
                            </svg>
                            <svg class="fill-icon">
                                <use href="<?php echo e(asset('assets/svg/icon-sprite.svg#fill-icons')); ?>"></use>
                            </svg><span>Icons</span></a>
                        <ul class="sidebar-submenu">
                            <li><a href="<?php echo e(route('flag-icon')); ?>">Flag icon</a></li>
                            <li><a href="<?php echo e(route('font-awesome')); ?>">Fontawesome Icon</a></li>
                            <li><a href="<?php echo e(route('ico-icon')); ?>">Ico Icon</a></li>
                            <li><a href="<?php echo e(route('themify-icon')); ?>">Themify Icon</a></li>
                            <li><a href="<?php echo e(route('feather-icon')); ?>">Feather icon</a></li>
                            <li><a href="<?php echo e(route('whether-icon')); ?>">Whether Icon</a></li>
                        </ul>
                    </li>
                    <li class="sidebar-list"><a class="sidebar-link sidebar-title" href="#">
                            <svg class="stroke-icon">
                                <use href="<?php echo e(asset('assets/svg/icon-sprite.svg#stroke-button')); ?>"></use>
                            </svg>
                            <svg class="fill-icon">
                                <use href="<?php echo e(asset('assets/svg/icon-sprite.svg#fill-botton')); ?>"></use>
                            </svg><span>Buttons</span></a>
                        <ul class="sidebar-submenu">
                            <li><a href="<?php echo e(route('buttons')); ?>">Default Style</a></li>
                            <li><a href="<?php echo e(route('flat-buttons')); ?>">Flat Style</a></li>
                            <li><a href="<?php echo e(route('buttons-edge')); ?>">Edge Style</a></li>
                            <li><a href="<?php echo e(route('raised-button')); ?>">Raised Style</a></li>
                            <li><a href="<?php echo e(route('button-group')); ?>">Button Group</a></li>
                        </ul>
                    </li>
                    <li class="sidebar-list"><a class="sidebar-link sidebar-title" href="#">
                            <svg class="stroke-icon">
                                <use href="<?php echo e(asset('assets/svg/icon-sprite.svg#stroke-charts')); ?>"></use>
                            </svg>
                            <svg class="fill-icon">
                                <use href="<?php echo e(asset('assets/svg/icon-sprite.svg#fill-charts')); ?>"></use>
                            </svg><span>Charts</span></a>
                        <ul class="sidebar-submenu">
                            <li><a href="<?php echo e(route('echarts')); ?>">Echarts</a></li>
                            <li><a href="<?php echo e(route('chart-apex')); ?>">Apex Chart</a></li>
                            <li><a href="<?php echo e(route('chart-google')); ?>">Google Chart</a></li>
                            <li><a href="<?php echo e(route('chart-sparkline')); ?>">Sparkline chart</a></li>
                            <li><a href="<?php echo e(route('chart-flot')); ?>">Flot Chart</a></li>
                            <li><a href="<?php echo e(route('chart-knob')); ?>">Knob Chart</a></li>
                            <li><a href="<?php echo e(route('chart-morris')); ?>">Morris Chart</a></li>
                            <li><a href="<?php echo e(route('chartjs')); ?>">Chatjs Chart</a></li>
                            <li><a href="<?php echo e(route('chartist')); ?>">Chartist Chart</a></li>
                            <li><a href="<?php echo e(route('chart-peity')); ?>">Peity Chart</a></li>
                        </ul>
                    </li>
                    <li class="sidebar-main-title">
                        <div>
                            <h6>Pages</h6>
                        </div>
                    </li>
                    <li class="sidebar-list"><a class="sidebar-link sidebar-title link-nav" href="<?php echo e(route('sample-page')); ?>">
                            <svg class="stroke-icon">
                                <use href="<?php echo e(asset('assets/svg/icon-sprite.svg#stroke-sample-page')); ?>"></use>
                            </svg>
                            <svg class="fill-icon">
                                <use href="<?php echo e(asset('assets/svg/icon-sprite.svg#fill-sample-page')); ?>"></use>
                            </svg><span>Sample page</span></a></li>
                    <li class="sidebar-list"><a class="sidebar-link sidebar-title link-nav"
                            href="<?php echo e(route('internationalization')); ?>">
                            <svg class="stroke-icon">
                                <use href="<?php echo e(asset('assets/svg/icon-sprite.svg#stroke-internationalization')); ?>">
                                </use>
                            </svg>
                            <svg class="fill-icon">
                                <use href="<?php echo e(asset('assets/svg/icon-sprite.svg#fill-internationalization')); ?>"></use>
                            </svg><span>Internationalization</span></a></li>
                    <li class="sidebar-list"><a class="sidebar-link sidebar-title link-nav"
                            href="#" target="_blank">
                            <svg class="stroke-icon">
                                <use href="<?php echo e(asset('assets/svg/icon-sprite.svg#stroke-starter-kit')); ?>"></use>
                            </svg>
                            <svg class="fill-icon">
                                <use href="<?php echo e(asset('assets/svg/icon-sprite.svg#fill-starter-kit')); ?>"></use>
                            </svg><span>Starter kit</span></a></li>
                    <li class="mega-menu"><a class="sidebar-link sidebar-title" href="#">
                            <svg class="stroke-icon">
                                <use href="<?php echo e(asset('assets/svg/icon-sprite.svg#stroke-others')); ?>"></use>
                            </svg>
                            <svg class="fill-icon">
                                <use href="<?php echo e(asset('assets/svg/icon-sprite.svg#fill-others')); ?>"></use>
                            </svg><span>Others</span></a>
                        <div class="mega-menu-container menu-content">
                            <div class="container-fluid">
                                <div class="row">
                                    <div class="col mega-box">
                                        <div class="link-section">
                                            <div class="submenu-title">
                                                <h5>Error Page</h5>
                                            </div>
                                            <ul class="submenu-content opensubmegamenu">
                                                <li><a href="<?php echo e(route('error-400')); ?>">Error 400</a></li>
                                                <li><a href="<?php echo e(route('error-401')); ?>">Error 401</a></li>
                                                <li><a href="<?php echo e(route('error-403')); ?>">Error 403</a></li>
                                                <li><a href="<?php echo e(route('error-404')); ?>">Error 404</a></li>
                                                <li><a href="<?php echo e(route('error-500')); ?>">Error 500</a></li>
                                                <li><a href="<?php echo e(route('error-503')); ?>">Error 503</a></li>
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="col mega-box">
                                        <div class="link-section">
                                            <div class="submenu-title">
                                                <h5> Authentication</h5>
                                            </div>
                                            <ul class="submenu-content opensubmegamenu">
                                                <li><a href="<?php echo e(route('login')); ?>" target="_blank">Login Simple</a></li>
                                                <li><a href="<?php echo e(route('login-one')); ?>" target="_blank">Login with bg image</a>
                                                </li>
                                                <li><a href="<?php echo e(route('login-two')); ?>" target="_blank">Login with image two </a>
                                                </li>
                                                <li><a href="<?php echo e(route('login-bs-validation')); ?>" target="_blank">Login With
                                                        validation</a></li>
                                                <li><a href="<?php echo e(route('login-bs-tt-validation')); ?>" target="_blank">Login with
                                                        tooltip</a></li>
                                                <li><a href="<?php echo e(route('login-sa-validation')); ?>" target="_blank">Login with
                                                        sweetalert</a></li>
                                                <li><a href="<?php echo e(route('sign-up')); ?>" target="_blank">Register Simple</a></li>
                                                <li><a href="<?php echo e(route('sign-up-one')); ?>" target="_blank">Register with Bg Image
                                                    </a></li>
                                                <li><a href="<?php echo e(route('sign-up-two')); ?>" target="_blank">Register with image
                                                        two</a></li>
                                                <li><a href="<?php echo e(route('sign-up-wizard')); ?>" target="_blank">Register wizard</a>
                                                </li>
                                                <li><a href="<?php echo e(route('unlock')); ?>">Unlock User</a></li>
                                                <li><a href="<?php echo e(route('forget-password')); ?>">Forget Password</a></li>
                                                <li><a href="<?php echo e(route('reset-password')); ?>">Reset Password</a></li>
                                                <li><a href="<?php echo e(route('maintenance')); ?>">Maintenance</a></li>
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="col mega-box">
                                        <div class="link-section">
                                            <div class="submenu-title">
                                                <h5>Coming Soon</h5>
                                            </div>
                                            <ul class="submenu-content opensubmegamenu">
                                                <li><a href="<?php echo e(route('comingsoon')); ?>">Coming Simple</a></li>
                                                <li><a href="<?php echo e(route('comingsoon-bg-video')); ?>">Coming with Bg video</a></li>
                                                <li><a href="<?php echo e(route('comingsoon-bg-img')); ?>">Coming with Bg Image</a></li>
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="col mega-box">
                                        <div class="link-section">
                                            <div class="submenu-title">
                                                <h5>Email templates</h5>
                                            </div>
                                            <ul class="submenu-content opensubmegamenu">
                                                <li><a href="<?php echo e(route('basic-template')); ?>">Basic Email</a></li>
                                                <li><a href="<?php echo e(route('email-header')); ?>">Basic With Header</a></li>
                                                <li><a href="<?php echo e(route('template-email')); ?>">Ecomerce Template</a></li>
                                                <li><a href="<?php echo e(route('template-email-2')); ?>">Email Template 2</a></li>
                                                <li><a href="<?php echo e(route('ecommerce-templates')); ?>">Ecommerce Email</a></li>
            <li><a href="<?php echo e(route('email-order-success')); ?>">Order Success</a></li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </li>
                    <li class="sidebar-main-title">
                        <div>
                            <h6>Miscellaneous</h6>
                        </div>
                    </li>
                    <li class="sidebar-list"><a class="sidebar-link sidebar-title" href="#">
                            <svg class="stroke-icon">
                                <use href="<?php echo e(asset('assets/svg/icon-sprite.svg#stroke-gallery')); ?>"></use>
                            </svg>
                            <svg class="fill-icon">
                                <use href="<?php echo e(asset('assets/svg/icon-sprite.svg#fill-gallery')); ?>"></use>
                            </svg><span>Gallery</span></a>
                        <ul class="sidebar-submenu">
                            <li><a href="<?php echo e(route('gallery')); ?>">Gallery Grid</a></li>
                            <li><a href="<?php echo e(route('gallery-with-description')); ?>">Gallery Grid Desc</a></li>
                            <li><a href="<?php echo e(route('gallery-masonry')); ?>">Masonry Gallery</a></li>
                            <li><a href="<?php echo e(route('masonry-gallery-with-disc')); ?>">Masonry with Desc</a></li>
                            <li><a href="<?php echo e(route('gallery-hover')); ?>">Hover Effects</a></li>
                        </ul>
                    </li>
                    <li class="sidebar-list"><a class="sidebar-link sidebar-title" href="#">
                            <svg class="stroke-icon">
                                <use href="<?php echo e(asset('assets/svg/icon-sprite.svg#stroke-blog')); ?>"></use>
                            </svg>
                            <svg class="fill-icon">
                                <use href="<?php echo e(asset('assets/svg/icon-sprite.svg#fill-blog')); ?>"></use>
                            </svg><span>Blog</span></a>
                        <ul class="sidebar-submenu">
                            <li><a href="<?php echo e(route('blog')); ?>">Blog Details</a></li>
                            <li><a href="<?php echo e(route('blog-single')); ?>">Blog Single</a></li>
                            <li><a href="<?php echo e(route('add-post')); ?>">Add Post</a></li>
                        </ul>
                    </li>
                    <li class="sidebar-list"><a class="sidebar-link sidebar-title link-nav" href="<?php echo e(route('faq')); ?>">
                            <svg class="stroke-icon">
                                <use href="<?php echo e(asset('assets/svg/icon-sprite.svg#stroke-faq')); ?>"></use>
                            </svg>
                            <svg class="fill-icon">
                                <use href="<?php echo e(asset('assets/svg/icon-sprite.svg#fill-faq')); ?>"></use>
                            </svg><span>FAQ</span></a></li>
                    <li class="sidebar-list"><a class="sidebar-link sidebar-title" href="#">
                            <svg class="stroke-icon">
                                <use href="<?php echo e(asset('assets/svg/icon-sprite.svg#stroke-job-search')); ?>"></use>
                            </svg>
                            <svg class="fill-icon">
                                <use href="<?php echo e(asset('assets/svg/icon-sprite.svg#fill-job-search')); ?>"></use>
                            </svg><span>Job Search</span></a>
                        <ul class="sidebar-submenu">
                            <li><a href="<?php echo e(route('job-cards-view')); ?>">Cards view</a></li>
                            <li><a href="<?php echo e(route('job-list-view')); ?>">List View</a></li>
                            <li><a href="<?php echo e(route('job-details')); ?>">Job Details</a></li>
                            <li><a href="<?php echo e(route('job-apply')); ?>">Apply</a></li>
                        </ul>
                    </li>
                    <li class="sidebar-list"><a class="sidebar-link sidebar-title" href="#">
                            <svg class="stroke-icon">
                                <use href="<?php echo e(asset('assets/svg/icon-sprite.svg#stroke-learning')); ?>"></use>
                            </svg>
                            <svg class="fill-icon">
                                <use href="<?php echo e(asset('assets/svg/icon-sprite.svg#fill-learning')); ?>"></use>
                            </svg><span>Learning</span></a>
                        <ul class="sidebar-submenu">
                            <li><a href="<?php echo e(route('learning-list-view')); ?>">Learning List</a></li>
                            <li><a href="<?php echo e(route('learning-detailed')); ?>">Detailed Course</a></li>
                        </ul>
                    </li>
                    <li class="sidebar-list"><a class="sidebar-link sidebar-title" href="#">
                            <svg class="stroke-icon">
                                <use href="<?php echo e(asset('assets/svg/icon-sprite.svg#stroke-maps')); ?>"></use>
                            </svg>
                            <svg class="fill-icon">
                                <use href="<?php echo e(asset('assets/svg/icon-sprite.svg#fill-maps')); ?>"></use>
                            </svg><span>Maps</span></a>
                        <ul class="sidebar-submenu">
                            <li><a href="<?php echo e(route('map-js')); ?>">Maps JS</a></li>
                            <li><a href="<?php echo e(route('vector-map')); ?>">Vector Maps</a></li>
                        </ul>
                    </li>
                    <li class="sidebar-list"><a class="sidebar-link sidebar-title" href="#">
                            <svg class="stroke-icon">
                                <use href="<?php echo e(asset('assets/svg/icon-sprite.svg#stroke-editors')); ?>"></use>
                            </svg>
                            <svg class="fill-icon">
                                <use href="<?php echo e(asset('assets/svg/icon-sprite.svg#fill-editors')); ?>"></use>
                            </svg><span>Editors</span></a>
                        <ul class="sidebar-submenu">
                            <li><a href="<?php echo e(route('summernote')); ?>">Summer Note</a></li>
                            <li><a href="<?php echo e(route('ckeditor')); ?>">CK editor</a></li>
                            <li><a href="<?php echo e(route('simple-mde')); ?>">MDE editor</a></li>
                            <li><a href="<?php echo e(route('ace-code-editor')); ?>">ACE code editor </a></li>
                        </ul>
                    </li>
                    <li class="sidebar-list"><a class="sidebar-link sidebar-title link-nav"
                            href="<?php echo e(route('knowledgebase')); ?>">
                            <svg class="stroke-icon">
                                <use href="<?php echo e(asset('assets/svg/icon-sprite.svg#stroke-knowledgebase')); ?>"></use>
                            </svg>
                            <svg class="fill-icon">
                                <use href="<?php echo e(asset('assets/svg/icon-sprite.svg#fill-knowledgebase')); ?>"></use>
                            </svg><span>Knowledgebase</span></a></li>
                    <li class="sidebar-list"><a class="sidebar-link sidebar-title link-nav"
                            href="<?php echo e(route('support-ticket')); ?>">
                            <svg class="stroke-icon">
                                <use href="<?php echo e(asset('assets/svg/icon-sprite.svg#stroke-support-tickets')); ?>"></use>
                            </svg>
                            <svg class="fill-icon">
                                <use href="<?php echo e(asset('assets/svg/icon-sprite.svg#fill-support-tickets')); ?>"></use>
                            </svg><span>Support Ticket</span></a></li>
                </ul>
            </div> -->
                    <p>&nbsp;</p>
                    <div class="right-arrow" id="right-arrow"><i data-feather="arrow-right"></i></div>
        </nav>
    </div>
</div>
<?php /**PATH C:\xampp\htdocs\matta_dev\resources\views/layouts/simple/sidebar.blade.php ENDPATH**/ ?>