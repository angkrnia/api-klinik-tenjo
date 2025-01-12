<?php

use Illuminate\Support\Facades\Route;

Route::post('login', [App\Http\Controllers\Auth\LoginController::class, 'index']);
Route::put('refresh-token', [App\Http\Controllers\Auth\LoginController::class, 'refreshToken']);
Route::post('register', App\Http\Controllers\Auth\RegisterController::class);
Route::post('forgot-password', [App\Http\Controllers\Auth\ForgotPasswordController::class, 'forgotPassword']);
Route::post('reset-password', [App\Http\Controllers\Auth\ForgotPasswordController::class, 'resetPassword']);
Route::get('public/check-antrian', [App\Http\Controllers\QueueController::class, 'publicAntrian']);
Route::get('public/list-antrian', [App\Http\Controllers\QueueController::class, 'listAntrian']);
Route::get('info-service', [App\Http\Controllers\ServiceController::class, 'index']);
Route::get('app-version', [App\Http\Controllers\ServiceController::class, 'appVersion']);
Route::post('info-service', [App\Http\Controllers\ServiceController::class, 'store']);

Route::middleware(['auth:api'])->group(function () {
	Route::get('doctors-list', [App\Http\Controllers\DoctorController::class, 'listAllDoctor']);
	Route::get('patient-list-by-user', [App\Http\Controllers\PatientController::class, 'patientListByUser']);
	Route::apiResource('patients', App\Http\Controllers\PatientController::class)->except(['destroy']);
	Route::get('doctors', [App\Http\Controllers\DoctorController::class, 'index']);
	Route::get('queue/pharmacy', [App\Http\Controllers\QueueController::class, 'pharmacy']);
	Route::get('queue/pharmacy/{queue}', [App\Http\Controllers\QueueController::class, 'detailPharmacy']);
	Route::get('queue/semua-antrian', [App\Http\Controllers\QueueController::class, 'semuaAntrian']);
	Route::put('queue/{queue}/completed', [App\Http\Controllers\QueueController::class, 'completed']);
	Route::put('queue/{queue}/vital-sign', [App\Http\Controllers\QueueController::class, 'vitalSign']);
	Route::put('queue/{queue}/update-vital-sign', [App\Http\Controllers\QueueController::class, 'updateVitalSign']);
	Route::get('queue', [App\Http\Controllers\QueueController::class, 'index']);
	Route::get('queue/check-antrian', [App\Http\Controllers\QueueController::class, 'checkAntrian']);
	Route::post('queue', [App\Http\Controllers\QueueController::class, 'store']);
	Route::get('facilities', [\App\Http\Controllers\FacilityController::class, 'index']);
	Route::put('queue/{queue}/selesai', [App\Http\Controllers\QueueController::class, 'selesai']);
	Route::put('queue/{queue}/updated-by-doctor', [App\Http\Controllers\QueueController::class, 'updateByDoctor']);
	Route::post('queue/{queue}/observation', [App\Http\Controllers\QueueController::class, 'observationExplanation']);
	Route::put('queue/{queue}/observation', [App\Http\Controllers\QueueController::class, 'observationResult']);
	Route::put('queue/{queue}/batal', [App\Http\Controllers\QueueController::class, 'batal']);
	Route::put('users/change-password', [App\Http\Controllers\UserController::class, 'changePassword']);
	Route::get('histories', App\Http\Controllers\HistoryController::class);

	// ROUTE CHART
	Route::get('chart/summary', [App\Http\Controllers\ChartController::class, 'summaryData']);
	Route::get('chart/patient-by-date', [App\Http\Controllers\ChartController::class, 'pasienByDate']);
	Route::get('chart/history-by-date', [App\Http\Controllers\ChartController::class, 'kunjunganByDate']);

	// ROUTE ADMIN
	Route::middleware(['admin'])->group(function () {
		Route::post('doctors', [App\Http\Controllers\DoctorController::class, 'store']);
		Route::put('doctors/{doctor}', [App\Http\Controllers\DoctorController::class, 'update']);
		Route::delete('doctors/{doctor}', [App\Http\Controllers\DoctorController::class, 'destroy']);
		Route::delete('patients/{patient}', [App\Http\Controllers\PatientController::class, 'destroy']);
		Route::post('facilities', [\App\Http\Controllers\FacilityController::class, 'store']);
		Route::put('facilities/{facility}', [\App\Http\Controllers\FacilityController::class, 'update']);
		Route::delete('facilities/{facility}', [\App\Http\Controllers\FacilityController::class, 'destroy']);
		Route::post('reset-antrian', [App\Http\Controllers\QueueController::class, 'resetAntrian']);
		Route::get('dashboard/summary-patien', [App\Http\Controllers\DashboardController::class, 'getSummaryPatient']);
	});

	// ROUTE DOKTER
	Route::middleware(['doctor'])->group(function () {
		Route::put('doctors/{doctor}', [App\Http\Controllers\DoctorController::class, 'update']);
		Route::get('queue/summary', [App\Http\Controllers\QueueController::class, 'summaryQueueForDoctor']);
		Route::put('queue/{queue}/panggil-antrian', [App\Http\Controllers\QueueController::class, 'panggilAntrian']);
		Route::put('queue/{queue}', [App\Http\Controllers\QueueController::class, 'update']);
		Route::post('reset-antrian', [App\Http\Controllers\QueueController::class, 'resetAntrian']);
	});

	// ROUTE
	Route::get('users/{patient}/detail', [App\Http\Controllers\UserController::class, 'detail']);
	Route::apiResource('users', App\Http\Controllers\UserController::class);
	Route::apiResource('schedules', App\Http\Controllers\ScheduleDoctorController::class);
});
