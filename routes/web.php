<?php

use Illuminate\Support\Facades\Route;

Route::get('/', 'UserController@login')->name('login');
Route::post('login_submitted', 'UserController@login_submitted')->name('login_submitted');
Route::get('forgot_password', 'UserController@forgot_password')->name('forgot_password');
Route::get('/reset_password', 'UserController@reset_password')->name('reset_password');
Route::post('/reset_update_password', 'UserController@reset_update_password')->name('reset_update_password');
Route::group(['middleware' => ['IsCustomer']], function () {

    Route::get('dashboard', 'UserController@dashboard')->name('dashboard');

    Route::get('roles', 'RoleController@roles')->name('roles')->middleware('checkPermission:manage_permissions');
    Route::post('role_submitted', 'RoleController@role_submitted')->name('role_submitted')->middleware('checkPermission:manage_permissions');
    Route::get('permissions', 'RoleController@permissions')->name('permissions')->middleware('checkPermission:manage_permissions');
    Route::get('add_permission', 'RoleController@add_permission')->name('add_permission')->middleware('checkPermission:manage_permissions');
    Route::get('change_role_status', 'RoleController@change_role_status')->name('change_role_status')->middleware('checkPermission:manage_permissions');
    Route::get('role_details', 'RoleController@role_details')->name('role_details')->middleware('checkPermission:manage_permissions');
    Route::post('role_permission_submitted', 'RoleController@role_permission_submitted')->name('role_permission_submitted')->middleware('checkPermission:manage_permissions');

    Route::get('users', 'UserController@users')->name('users')->middleware('checkPermission:manage_users');
    Route::post('update_password', 'UserController@update_password')->name('update_password');
    Route::post('profile_submitted', 'UserController@profile_submitted')->name('profile_submitted');
    Route::get('add_user', 'UserController@add_user')->name('add_user')->middleware('checkPermission:manage_users');
    Route::post("user_submitted", "UserController@user_submitted")->name('user_submitted')->middleware('checkPermission:manage_users');

    Route::get('all_invites', 'UserController@all_invites')->name('all_invites')->middleware('checkPermission:manage_users');
    Route::get('change_password', 'UserController@change_password')->name('change_password');
    Route::get('login_history', 'UserController@login_history')->name('login_history')->middleware('checkPermission:manage_users');
    Route::get("edit_user", "UserController@edit_user")->name('edit_user')->middleware('checkPermission:manage_users');
    Route::post("edit_user_submitted", "UserController@edit_user_submitted")->name('edit_user_submitted')->middleware('checkPermission:manage_users');
    Route::get("change_user_status", "UserController@change_user_status")->name('change_user_status')->middleware('checkPermission:manage_users');

    Route::get('all-student', 'StudentController@all_student')->name('all-student')->middleware('checkPermission:manage_users');
    Route::get('add-student', 'StudentController@add_student')->name('add-student')->middleware('checkPermission:manage_users');
    Route::post('add_student_submitted', 'StudentController@add_student_submitted')->name('add_student_submitted')->middleware('checkPermission:manage_users');
    Route::get("edit_student", "StudentController@edit_student")->name('edit_student')->middleware('checkPermission:manage_users');
    Route::post("edit_student_submitted", "StudentController@edit_student_submitted")->name('edit_student_submitted')->middleware('checkPermission:manage_users');
    Route::get("change_student_status", "StudentController@change_student_status")->name('change_student_status')->middleware('checkPermission:manage_users');
    //Invoices
    Route::get('invoice_settings', 'InvoiceController@invoice_settings')->name('invoice_settings')->middleware('checkPermission:invoice_settings');
    Route::get('add_invoice', 'InvoiceController@add_invoice')->name('add_invoice')->middleware('checkPermission:add_invoice');
    Route::post('add_invoice_submitted', 'InvoiceController@add_invoice_submitted')->name('add_invoice_submitted')->middleware('checkPermission:add_invoice');
    Route::get("edit_invoice", "InvoiceController@edit_invoice")->name('edit_invoice')->middleware('checkPermission:add_invoice');
    Route::post("edit_invoice_submitted", "InvoiceController@edit_invoice_submitted")->name('edit_invoice_submitted')->middleware('checkPermission:add_invoice');
    Route::get("change_invoice_status", "InvoiceController@change_invoice_status")->name('change_invoice_status')->middleware('checkPermission:add_invoice');
    Route::get('invoice_templates', 'InvoiceController@invoice_templates')->name('invoice_templates')->middleware('checkPermission:invoice_templates');
    Route::get('all_invoices', 'InvoiceController@all_invoices')->name('all_invoices')->middleware('checkPermission:all_invoices');
    Route::get('send_invoice', 'InvoiceController@send_invoice')->name('send_invoice')->middleware('checkPermission:all_invoices');
    Route::post('send_invoice_submitted', 'InvoiceController@send_invoice_submitted')->name('send_invoice_submitted')->middleware('checkPermission:all_invoices');

    Route::get('search_parent', 'UserController@search_parent')->name('search_parent');
    Route::get('thr_search_slots', 'TeacherController@thr_search_slots')->name('thr_search_slots')->middleware('checkPermission:manage_teachers');
    Route::get('thr_office_timings', 'TeacherController@thr_office_timings')->name('thr_office_timings')->middleware('checkPermission:manage_teachers');
    Route::post('thr_office_timings_submitted', 'TeacherController@thr_office_timings_submitted')->name('thr_office_timings_submitted')->middleware('checkPermission:manage_teachers');
    Route::get('get_thr_office_timing', 'TeacherController@get_thr_office_timing')->name('get_thr_office_timing');
    Route::post('thr_free_slots', 'TeacherController@thr_free_slots')->name('thr_free_slots')->middleware('checkPermission:manage_teachers');
    Route::post('load_thr_sdt', 'TeacherController@load_thr_sdt')->name('load_thr_sdt')->middleware('checkPermission:manage_teachers');
    Route::get('add_std_schedule', 'StudentScheduleController@add_std_schedule')->name('add_std_schedule')->middleware('checkPermission:manage_teachers');
    Route::get('assign_students', 'TeacherController@assign_students')->name('assign_students')->middleware('checkPermission:manage_teachers');
    Route::get('search_student', 'TeacherController@search_student')->name('search_student')->middleware('checkPermission:manage_teachers');
    Route::post('assign_students_submitted', 'TeacherController@assign_students_submitted')->name('assign_students_submitted')->middleware('checkPermission:manage_teachers');
    //Student Schedule
    Route::get('thr_std_schedule', 'TeacherController@thr_std_schedule')->name('thr_std_schedule')->middleware('checkPermission:manage_teachers');
    Route::post('std_schedule_submitted', 'StudentScheduleController@std_schedule_submitted')->name('std_schedule_submitted')->middleware('checkPermission:manage_teachers');
    Route::get('delete_thr_std_schedule', 'StudentScheduleController@delete_thr_std_schedule')->name('delete_thr_std_schedule')->middleware('checkPermission:manage_teachers');

    Route::get('load_classes', 'ClassesController@load_classes')->name('load_classes')->middleware('checkPermission:manage_classes');
    Route::post('ajax_load_classes', 'ClassesController@ajax_load_classes')->name('ajax_load_classes');
    Route::post('view_cls_report', 'ClassesController@view_cls_report')->name('view_cls_report');
    Route::post('reschedule_class', 'ClassesController@reschedule_class')->name('reschedule_class');
    Route::post('available_teacher', 'ClassesController@available_teacher')->name('available_teacher');
    Route::get('configure_classes', 'ClassesController@configure_classes')->name('configure_classes')->middleware('checkPermission:manage_classes');
    Route::post('configure_classes_submitted', 'ClassesController@configure_classes_submitted')->name('configure_classes_submitted')->middleware('checkPermission:manage_classes');
    //--------------------------------------------------------------------------------------------------------------------
    Route::get('add_course', 'CourseController@add_course')->name('add_course')->middleware('checkPermission:manage_courses');
    Route::post('add_course_submitted', 'CourseController@add_course_submitted')->name('add_course_submitted')->middleware('checkPermission:manage_courses');
    Route::get('edit_course', 'CourseController@edit_course')->name('edit_course')->middleware('checkPermission:manage_courses');
    Route::post('edit_course_submitted', 'CourseController@edit_course_submitted')->name('edit_course_submitted')->middleware('checkPermission:manage_courses');
    Route::get('all_courses', 'CourseController@all_courses')->name('all_courses')->middleware('checkPermission:manage_courses');
    Route::get('change_course_status', 'CourseController@change_course_status')->name('change_course_status')->middleware('checkPermission:manage_courses');

    Route::get('all_course_lessons', 'CourseController@all_course_lessons')->name('all_course_lessons')->middleware('checkPermission:manage_courses');
    Route::get('add_lesson', 'CourseController@add_lesson')->name('add_lesson')->middleware('checkPermission:manage_courses');
    Route::post('add_lesson_submitted', 'CourseController@add_lesson_submitted')->name('add_lesson_submitted')->middleware('checkPermission:manage_courses');

    Route::post('add_lessons_submitted', 'CourseController@add_course_submitted')->name('add_lessons_submitted')->middleware('checkPermission:manage_courses');
    Route::get('edit_lesson', 'CourseController@edit_lesson')->name('edit_lesson')->middleware('checkPermission:manage_courses');
    Route::post('edit_lessons_submitted', 'CourseController@edit_lessons_submitted')->name('edit_lessons_submitted')->middleware('checkPermission:manage_courses');
    Route::get('all_lessons', 'CourseController@all_lessons')->name('all_lessons')->middleware('checkPermission:manage_courses');
    Route::get('change_lesson_status', 'CourseController@change_lesson_status')->name('change_lesson_status')->middleware('checkPermission:manage_courses');
    Route::any('lesson_content', 'CourseController@lesson_content')->name('lesson_content');
    Route::any('lesson_detail', 'CourseController@lesson_detail')->name('lesson_detail');
    //------------------------------------------------------------------------------------------------------------------
    Route::get('my_student', 'StudentController@my_student')->name('my_student')->middleware('checkPermission:my_student');
    Route::get('student_schedule_detail', 'TeacherController@student_schedule_detail')->name('student_schedule_detail')->middleware('checkPermission:my_student');
    Route::get('teacher_classes', 'TeacherClassesController@teacher_classes')->name('teacher_classes')->middleware('checkPermission:my_student_classes');
    Route::post('ajax_my_student_classes', 'TeacherClassesController@ajax_my_student_classes')->name('ajax_my_student_classes');
    Route::get('my_courses', 'CourseController@my_courses')->name('my_courses')->middleware('checkPermission:my_courses');
    Route::get('my_course_lessons', 'CourseController@my_course_lessons')->name('my_course_lessons');
    Route::get('start_cls', 'TeacherClassesController@start_cls')->name('start_cls');
    Route::post('start_cls_submitted', 'TeacherClassesController@start_cls_submitted')->name('start_cls_submitted');
    Route::get('load_lesson', 'TeacherClassesController@load_lesson')->name('load_lesson');
    Route::get('taught_lesson', 'TeacherClassesController@taught_lesson')->name('taught_lesson');
    //------------------------------------------------------------------------------------------------------------------
    Route::get('parent_invoices', 'InvoiceController@parent_invoices')->name('parent_invoices');
    Route::get('student_lesson_history', 'StudentController@student_lesson_history')->name('student_lesson_history');
    Route::get('complaints', 'InvoiceController@complaints')->name('complaints');

    //--------------------------------------------------------------------------------------------------------------------
    Route::get('change_password', 'UserController@change_password')->name('change_password');
    Route::get('profile', 'UserController@profile')->name('profile');
    Route::get('logout', 'UserController@logout')->name('logout');

    Route::get('send_invoices', 'CronController@send_invoices')->name('send_invoices');

});
