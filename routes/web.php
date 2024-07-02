<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\ServiceConnectionsController;
use App\Http\Controllers\MemberConsumersController;
use App\Http\Controllers\ServiceAccountsController;
use App\Http\Controllers\TicketsController;
use App\Http\Controllers\ReadingSchedulesController;
use App\Http\Controllers\RatesController;
use App\Http\Controllers\ReadingsController;
use App\Http\Controllers\BillsController;
use App\Http\Controllers\TransactionIndexController;
use App\Http\Controllers\PaidBillsController;
use App\Http\Controllers\DCRSummaryTransactionsController;
use App\Http\Controllers\MeterInstallationController;
use App\Http\Controllers\SystemSettingsController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Auth::routes();

Route::get('/', function () {
    return redirect()->route('login');
});

Route::group(['middleware' => ['settings']], function () {
    Route::get('/home', [HomeController::class, 'index'])->name('home');
    Route::get('/home/get-unassigned-meters', [HomeController::class, 'fetchUnassignedMeters'])->name('home.get-unassigned-meters');
    Route::get('/home/get-new-service-connections', [HomeController::class, 'fetchNewServiceConnections'])->name('home.get-new-service-connections');
    Route::get('/home/get-requests-for-inspection', [HomeController::class, 'fetchRequestsForInspection'])->name('home.get-requests-for-inspection');
    Route::get('/home/get-approved-service-connections', [HomeController::class, 'fetchApprovedServiceConnections'])->name('home.get-approved-service-connections');
    Route::get('/home/get-for-engergization', [HomeController::class, 'fetchForEnergization'])->name('home.get-for-engergization');
    Route::get('/home/get-inspection-report', [HomeController::class, 'fetchInspectionReport'])->name('home.get-inspection-report');
    Route::get('/home/get-inspection-large-load', [HomeController::class, 'fetchInspectionLargeLoad'])->name('home.get-inspection-large-load');
    Route::get('/home/get-bom-large-load', [HomeController::class, 'fetchBomLargeLoad'])->name('home.get-bom-large-load');
    Route::get('/home/get-transformer-large-load', [HomeController::class, 'fetchTransformerLargeLoad'])->name('home.get-transformer-large-load');
    Route::get('/home/dash-get-collection-summary', [HomeController::class, 'dashGetCollectionSummary'])->name('home.dash-get-collection-summary');
    Route::get('/home/dash-get-collection-summary-graph', [HomeController::class, 'dashGetCollectionSummaryGraph'])->name('home.dash-get-collection-summary-graph');
    Route::get('/home/fetch-for-payment-approvals', [HomeController::class, 'fetchForPaymentApprovals'])->name('home.fetch-for-payment-approvals');
    Route::get('/home/fetch-for-payment-orders', [HomeController::class, 'fetchForPaymentOrders'])->name('home.fetch-for-payment-orders');
    Route::get('/home/fetch-for-turn-on-approvals', [HomeController::class, 'fetchForTurnOnApprovals'])->name('home.fetch-for-turn-on-approvals');
    Route::get('/settings/settings', [HomeController::class, 'settings'])->name('settings.settings');
    Route::get('/settings/save-general-settings', [HomeController::class, 'saveGeneralSettings'])->name('settings.save-general-settings');
    Route::get('/home/fetch-other-services', [HomeController::class, 'fetchOtherServices'])->name('home.fetch-other-services');

    // ADD PERMISSIONS TO ROLES
    Route::get('/roles/add-permissions/{id}', [RoleController::class, 'addPermissions'])->name('roles.add_permissions');
    Route::post('/roles/create-role-permissions', [RoleController::class, 'createRolePermissions']);

    // ADD ROLES TO USER
    Route::get('/users/add-user-roles/{id}', [UsersController::class, 'addUserRoles'])->name('users.add_user_roles');
    Route::post('/users/create-user-roles', [UsersController::class, 'createUserRoles']);
    Route::get('/users/add-user-permissions/{id}', [UsersController::class, 'addUserPermissions'])->name('users.add_user_permissions');
    Route::post('/users/create-user-permissions', [UsersController::class, 'createUserPermissions']);
    Route::get('/users/remove-permission/{id}/{permission}', [UsersController::class, 'removePermission'])->name('users.remove_permission');
    Route::get('/users/remove-roles/{id}', [UsersController::class, 'clearRoles'])->name('users.remove_roles');
    Route::get('/users/my-account', [UsersController::class, 'myAccount'])->name('users.my-account');

    Route::post('/users/authenticate', [UsersController::class, 'authenticate'])->name('users.authenticate');
    Route::get('/users/switch-color-modes', [UsersController::class, 'switchColorModes'])->name('users.switch-color-modes');
    Route::post('/users/update-password', [UsersController::class, 'updatePassword'])->name('users.update-password');
    Route::post('/users/update-password-admin', [UsersController::class, 'updatePasswordAdmin'])->name('users.update-password-admin');
    Route::resource('users', UsersController::class);

    Route::resource('roles', App\Http\Controllers\RoleController::class);

    Route::resource('permissions', App\Http\Controllers\PermissionController::class);


    Route::get('/member_consumers/assess_checklists/{id}', [MemberConsumersController::class, 'assessChecklists'])->name('memberConsumers.assess-checklists');
    Route::get('/member_consumers/fetchmemberconsumer', [MemberConsumersController::class, 'fetchmemberconsumer'])->name('memberConsumers.fetch-member-consumers');
    Route::get('/member_consumers/capture-image/{id}', [MemberConsumersController::class, 'captureImage'])->name('memberConsumers.capture-image');
    Route::get('/member_consumers/print-membership-application/{id}', [MemberConsumersController::class, 'printMembershipApplication'])->name('memberConsumers.print-membership-application');
    Route::get('/member_consumers/print-certificate/{id}', [MemberConsumersController::class, 'printCertificate'])->name('memberConsumers.print-certificate');
    Route::resource('memberConsumers', MemberConsumersController::class);


    Route::resource('memberConsumerTypes', App\Http\Controllers\MemberConsumerTypesController::class);


    Route::resource('towns', App\Http\Controllers\TownsController::class);


    Route::resource('barangays', App\Http\Controllers\BarangaysController::class);
    Route::get('/barangays/get-barangays-json/{townId}', [App\Http\Controllers\BarangaysController::class, 'getBarangaysJSON']);

    Route::get('/member_consumer_spouses/create/{consumerId}', [App\Http\Controllers\MemberConsumerSpouseController::class, 'create'])->name('memberConsumerSpouses.create');
    Route::get('/member_consumer_spouses/index', [App\Http\Controllers\MemberConsumerSpouseController::class, 'index'])->name('memberConsumerSpouses.index');
    Route::post('/member_consumer_spouses/store', [App\Http\Controllers\MemberConsumerSpouseController::class, 'store'])->name('memberConsumerSpouses.store');
    Route::get('/member_consumer_spouses/edit/{consumerId}', [App\Http\Controllers\MemberConsumerSpouseController::class, 'edit'])->name('memberConsumerSpouses.edit');
    Route::patch('/member_consumer_spouses/update/{id}', [App\Http\Controllers\MemberConsumerSpouseController::class, 'update'])->name('memberConsumerSpouses.update');
    // Route::resource('memberConsumerSpouses', App\Http\Controllers\MemberConsumerSpouseController::class);

    Route::get('/service_connections/fetchserviceconnections', [ServiceConnectionsController::class, 'fetchserviceconnections'])->name('serviceConnections.fetch-service-connections');
    Route::get('/service_connections/selectmembership', [ServiceConnectionsController::class, 'selectMembership'])->name('serviceConnections.selectmembership');
    Route::get('/service_connections/fetchmemberconsumer', [ServiceConnectionsController::class, 'fetchmemberconsumer'])->name('serviceConnections.fetch-member-consumers');
    Route::get('/service_connections/create_new', [ServiceConnectionsController::class, 'createNew'])->name('serviceConnections.create_new');
    Route::get('/service_connections/create_new_step_two/{scId}', [ServiceConnectionsController::class, 'createNewStepTwo'])->name('serviceConnections.create_new_step_two');
    Route::get('/service_connections/assess_checklists/{id}', [ServiceConnectionsController::class, 'assessChecklists'])->name('serviceConnections.assess-checklists');
    Route::get('/service_connections/update_checklists/{id}', [ServiceConnectionsController::class, 'updateChecklists'])->name('serviceConnections.update-checklists');
    Route::get('/service_connections/move_to_trash/{id}', [ServiceConnectionsController::class, 'moveToTrash'])->name('serviceConnections.move-to-trash');
    Route::get('/service_connections/trash', [ServiceConnectionsController::class, 'trash'])->name('serviceConnections.trash');
    Route::get('/service_connections/restore/{id}', [ServiceConnectionsController::class, 'restore'])->name('serviceConnections.restore');
    Route::get('/service_connections/fetchserviceconnectiontrash', [ServiceConnectionsController::class, 'fetchserviceconnectiontrash'])->name('serviceConnections.fetch-service-connection-trash');
    Route::get('/service_connections/energization', [ServiceConnectionsController::class, 'energization'])->name('serviceConnections.energization');
    Route::get('/service_connections/print_order/{id}', [ServiceConnectionsController::class, 'printOrder'])->name('serviceConnections.print-order');
    Route::post('/service_connections/change-station-crew', [ServiceConnectionsController::class, 'changeStationCrew']);
    Route::post('/service_connections/update-energization-status', [ServiceConnectionsController::class, 'updateEnergizationStatus'])->name('serviceConnections.update-energization-status');
    Route::get('/service_connections/select_application_type/{consumerId}', [ServiceConnectionsController::class, 'selectApplicationType'])->name('serviceConnections.select-application-type');
    Route::post('/service_connections/relay_account_type/{consumerId}', [ServiceConnectionsController::class, 'relayApplicationType'])->name('serviceConnections.relay-account-type');
    Route::get('/service_connections/dashboard', [ServiceConnectionsController::class, 'dashboard'])->name('serviceConnections.dashboard');
    Route::get('/service_connections/large-load-inspections', [ServiceConnectionsController::class, 'largeLoadInspections'])->name('serviceConnections.large-load-inspections');
    Route::post('/service_connections/large-load-inspection-update', [ServiceConnectionsController::class, 'largeLoadInspectionUpdate'])->name('serviceConnections.large-load-inspection-update');
    Route::get('/service_connections/bom-index', [ServiceConnectionsController::class, 'bomIndex'])->name('serviceConnections.bom-index');
    Route::get('/service_connections/bom-assigning/{scId}', [ServiceConnectionsController::class, 'bomAssigning'])->name('serviceConnections.bom-assigning');
    Route::get('/service_connections/forward-to-transformer-assigning/{scId}', [ServiceConnectionsController::class, 'forwardToTransformerAssigning'])->name('serviceConnections.forward-to-transformer-assigning');
    Route::get('/service_connections/transformer-assigning/{scId}', [ServiceConnectionsController::class, 'transformerAssigning'])->name('serviceConnections.transformer-assigning');
    Route::get('/service_connections/transformer_index', [ServiceConnectionsController::class, 'transformerIndex'])->name('serviceConnections.transformer-index');
    Route::get('/service_connections/pole-assigning/{scId}', [ServiceConnectionsController::class, 'poleAssigning'])->name('serviceConnections.pole-assigning');
    Route::get('/service_connections/quotation-summary/{scId}', [ServiceConnectionsController::class, 'quotationSummary'])->name('serviceConnections.quotation-summary');
    Route::get('/service_connections/spanning-assigning/{scId}', [ServiceConnectionsController::class, 'spanningAssigning'])->name('serviceConnections.spanning-assigning');
    Route::get('/service_connections/forward-to-verficaation/{scId}', [ServiceConnectionsController::class, 'forwardToVerification'])->name('serviceConnections.forward-to-verficaation');
    Route::get('/service_connections/largeload-predefined-materials/{scId}/{options}', [ServiceConnectionsController::class, 'largeLoadPredefinedMaterials'])->name('serviceConnections.largeload-predefined-materials');
    Route::get('/service_connections/fleet-monitor', [ServiceConnectionsController::class, 'fleetMonitor'])->name('serviceConnections.fleet-monitor');
    Route::get('/service_connections/metering-equipment-assigning/{scId}', [ServiceConnectionsController::class, 'meteringEquipmentAssigning'])->name('serviceConnections.metering-equipment-assigning');
    Route::get('/service_connections/daily-monitor', [ServiceConnectionsController::class, 'dailyMonitor'])->name('serviceConnections.daily-monitor');
    Route::get('/service_connections/fetch-daily-monitor-applications-data', [ServiceConnectionsController::class, 'fetchDailyMonitorApplicationsData'])->name('serviceConnections.fetch-daily-monitor-applications-data');
    Route::get('/service_connections/fetch-daily-monitor-energized-data', [ServiceConnectionsController::class, 'fetchDailyMonitorEnergizedData'])->name('serviceConnections.fetch-daily-monitor-energized-data');
    Route::get('/service_connections/applications-report', [ServiceConnectionsController::class, 'applicationsReport'])->name('serviceConnections.applications-report');
    Route::get('/service_connections/fetch-applications-report', [ServiceConnectionsController::class, 'fetchApplicationsReport'])->name('serviceConnections.fetch-applications-report');
    Route::post('/service_connections/download-applications-report', [ServiceConnectionsController::class, 'downloadApplicationsReport'])->name('serviceConnections.download-applications-report');
    Route::get('/service_connections/energization-report', [ServiceConnectionsController::class, 'energizationReport'])->name('serviceConnections.energization-report');
    Route::get('/service_connections/fetch-energization-report', [ServiceConnectionsController::class, 'fetchEnergizationReport'])->name('serviceConnections.fetch-energization-report');
    Route::post('/service_connections/download-energization-report', [ServiceConnectionsController::class, 'downloadEnergizationReport'])->name('serviceConnections.download-energization-report');
    Route::get('/service_connections/fetch-application-count-via-status', [ServiceConnectionsController::class, 'fetchApplicationCountViaStatus'])->name('serviceConnections.fetch-application-count-via-status');
    Route::get('/service_connections/print-service-connection-application/{id}', [ServiceConnectionsController::class, 'printServiceConnectionApplication'])->name('serviceConnections.print-service-connection-application');
    Route::get('/service_connections/print-service-connection-contract/{id}', [ServiceConnectionsController::class, 'printServiceConnectionContract'])->name('serviceConnections.print-service-connection-contract');
    Route::get('/service_connections/relocation-search', [ServiceConnectionsController::class, 'relocationSearch'])->name('serviceConnections.relocation-search');
    Route::get('/service_connections/create-relocation/{id}', [ServiceConnectionsController::class, 'createRelocation'])->name('serviceConnections.create-relocation');
    Route::get('/service_connections/change-name-search', [ServiceConnectionsController::class, 'changeNameSearch'])->name('serviceConnections.change-name-search');
    Route::get('/service_connections/create-change-name/{id}', [ServiceConnectionsController::class, 'createChangeName'])->name('serviceConnections.create-change-name');
    Route::post('/service_connections/store-change-name', [ServiceConnectionsController::class, 'storeChangeName'])->name('serviceConnections.store-change-name');
    Route::get('/service_connections/approve-change-name/{id}', [ServiceConnectionsController::class, 'approveForChangeName'])->name('serviceConnections.approve-change-name');
    Route::get('/service_connections/bypass-approve-inspection/{inspectionId}', [ServiceConnectionsController::class, 'bypassApproveInspection'])->name('serviceConnections.bypass-approve-inspection');
    Route::get('/service_connections/re-installation-search', [ServiceConnectionsController::class, 'reInstallationSearch'])->name('serviceConnections.re-installation-search');
    Route::get('/service_connections/create-re-installation/{id}', [ServiceConnectionsController::class, 'createReInstallation'])->name('serviceConnections.create-re-installation');
    Route::get('/service_connections/print-contract-without-membership/{id}', [ServiceConnectionsController::class, 'printContractWithoutMembership'])->name('serviceConnections.print-contract-without-membership');
    Route::get('/service_connections/print-application-form-without-membership/{id}', [ServiceConnectionsController::class, 'printApplicationFormWithoutMembership'])->name('serviceConnections.print-application-form-without-membership');
    Route::get('/service_connections/metering-installation', [ServiceConnectionsController::class, 'meteringInstallation'])->name('serviceConnections.metering-installation');
    Route::get('/service_connections/download-metering-installation-report/{from}/{to}', [ServiceConnectionsController::class, 'downloadMeteringInstallation'])->name('serviceConnections.download-metering-installation-report');
    Route::get('/service_connections/detailed-summary', [ServiceConnectionsController::class, 'detailedSummary'])->name('serviceConnections.detailed-summary');
    Route::get('/service_connections/download-detailed-summary/{status}/{from}/{to}', [ServiceConnectionsController::class, 'downloadDetailedSummary'])->name('serviceConnections.download-detailed-summary');
    Route::get('/service_connections/summary-report', [ServiceConnectionsController::class, 'summaryReport'])->name('serviceConnections.summary-report');
    Route::get('/service_connections/mriv', [ServiceConnectionsController::class, 'mriv'])->name('serviceConnections.mriv');
    Route::get('/service_connections/print-mriv/{town}/{from}/{to}', [ServiceConnectionsController::class, 'printMriv'])->name('serviceConnections.print-mriv');
    Route::get('/service_connections/update-status', [ServiceConnectionsController::class, 'updateStatus'])->name('serviceConnections.update-status');
    Route::get('/service_connections/sevice-connections-report', [ServiceConnectionsController::class, 'serviceConnectionsReport'])->name('serviceConnections.sevice-connections-report');
    Route::get('/service_connections/print-sevice-connections-report/{town}/{from}/{to}', [ServiceConnectionsController::class, 'printServiceConnectionsReport'])->name('serviceConnections.print-sevice-connections-report');
    Route::get('/service_connections/payment-order/{scid}', [ServiceConnectionsController::class, 'paymentOrder'])->name('serviceConnections.payment-order');
    Route::post('/service_connections/save-payment-order', [ServiceConnectionsController::class, 'savePaymentOrder'])->name('serviceConnections.save-payment-order');
    Route::get('/service_connections/update-payment-order/{scid}', [ServiceConnectionsController::class, 'updatePaymentOrder'])->name('serviceConnections.update-payment-order');
    Route::get('/service_connections/inspection-monitor', [ServiceConnectionsController::class, 'inspectionMonitor'])->name('serviceConnections.inspection-monitor');
    Route::get('/service_connections/get-inspection-summary-data-calendar', [ServiceConnectionsController::class, 'getInspectionSummaryDataCalendar'])->name('serviceConnections.get-inspection-summary-data-calendar');
    Route::get('/service_connections/get-inspection-summary-data', [ServiceConnectionsController::class, 'getInspectionSummaryData'])->name('serviceConnections.get-inspection-summary-data');
    Route::get('/service_connections/get-inspection-data', [ServiceConnectionsController::class, 'getInspectionData'])->name('serviceConnections.get-inspection-data');
    Route::get('/service_connections/get-inspection-summary', [ServiceConnectionsController::class, 'getInspectionSummary'])->name('serviceConnections.get-inspection-summary');
    Route::get('/service_connections/get-for-reinspection', [ServiceConnectionsController::class, 'getForReInspection'])->name('serviceConnections.get-for-reinspection');
    Route::get('/service_connections/update-reinspection-schedule', [ServiceConnectionsController::class, 'updateReInspectionSchedule'])->name('serviceConnections.update-reinspection-schedule');
    Route::get('/service_connections/for-payment', [ServiceConnectionsController::class, 'forPayment'])->name('serviceConnections.for-payment');
    Route::get('/service_connections/for-energization', [ServiceConnectionsController::class, 'forEnergization'])->name('serviceConnections.for-energization');
    Route::get('/service_connections/set-connection-schedule', [ServiceConnectionsController::class, 'setConnectionSchedule'])->name('serviceConnections.set-connection-schedule');
    Route::get('/service_connections/get-existing-accounts', [ServiceConnectionsController::class, 'getExistingAccounts'])->name('serviceConnections.get-existing-accounts');
    Route::get('/service_connections/lifeliners-view', [ServiceConnectionsController::class, 'lifelinersView'])->name('serviceConnections.lifeliners-view');
    Route::get('/service_connections/manual-energization', [ServiceConnectionsController::class, 'manualEnergization'])->name('serviceConnections.manual-energization');
    Route::get('/service_connections/print-payment-order/{id}', [ServiceConnectionsController::class, 'printPaymentOrder'])->name('serviceConnections.print-payment-order');
    Route::get('/service_connections/upload-files/{id}', [ServiceConnectionsController::class, 'uploadFiles'])->name('serviceConnections.upload-files');
    Route::post('/service_connections/save-uploaded-files', [ServiceConnectionsController::class, 'saveUploadedFiles'])->name('serviceConnections.save-uploaded-files');
    Route::get('/service_connections/turn-on-approvals', [ServiceConnectionsController::class, 'turnOnApprovals'])->name('serviceConnections.turn-on-approvals');
    Route::get('/service_connections/payment-approvals', [ServiceConnectionsController::class, 'paymentApprovals'])->name('serviceConnections.payment-approvals');
    Route::get('/service_connections/all-applications', [ServiceConnectionsController::class, 'allApplications'])->name('serviceConnections.all-applications');
    Route::get('/service_connections/search', [ServiceConnectionsController::class, 'search'])->name('serviceConnections.search');
    Route::get('/service_connections/move-to-trash-ajax', [ServiceConnectionsController::class, 'moveToTrashAjax'])->name('serviceConnections.move-to-trash-ajax');
    Route::get('/service_connections/fetch-images', [ServiceConnectionsController::class, 'fetchImages'])->name('serviceConnections.fetch-images');
    Route::post('/service_connections/trash-file', [ServiceConnectionsController::class, 'trashFile'])->name('serviceConnections.trash-file');
    Route::get('/service_connections/print-order-materials/{id}', [ServiceConnectionsController::class, 'printOrderMaterials'])->name('serviceConnections.print-order-materials');
    Route::get('/service_connections/revalidate-payment-order-materials', [ServiceConnectionsController::class, 'revalidatePaymentOrderMaterials'])->name('serviceConnections.revalidate-payment-order-materials');
    Route::get('/service_connections/print-order-meters/{id}', [ServiceConnectionsController::class, 'printOrderMeters'])->name('serviceConnections.print-order-meters');
    Route::get('/service_connections/delete-payment-order', [ServiceConnectionsController::class, 'deletePaymentOrder'])->name('serviceConnections.delete-payment-order');
    Route::get('/service_connections/other-services', [ServiceConnectionsController::class, 'otherServices'])->name('serviceConnections.other-services');
    Route::get('/service_connections/print-material-and-meters/{id}', [ServiceConnectionsController::class, 'printMaterialsAndMeters'])->name('serviceConnections.print-material-and-meters');
    Route::post('/service_connections/trash-documents', [ServiceConnectionsController::class, 'trashDocumets'])->name('serviceConnections.trash-documents');
    Route::get('/service_connections/print-inspection-fee/{id}', [ServiceConnectionsController::class, 'printInspectionFee'])->name('serviceConnections.print-inspection-fee');
    Route::get('/service_connections/update-inspection-fee', [ServiceConnectionsController::class, 'updateInspectionFee'])->name('serviceConnections.update-inspection-fee');
    Route::get('/service_connections/applied-requests', [ServiceConnectionsController::class, 'appliedRequests'])->name('serviceConnections.applied-requests');
    Route::get('/service_connections/get-applied-requests', [ServiceConnectionsController::class, 'getAppliedRequests'])->name('serviceConnections.get-applied-requests');
    Route::resource('serviceConnections', App\Http\Controllers\ServiceConnectionsController::class);


    Route::resource('serviceConnectionAccountTypes', App\Http\Controllers\ServiceConnectionAccountTypesController::class);


    Route::get('/service_connection_inspections/create_step_two/{scId}', [App\Http\Controllers\ServiceConnectionInspectionsController::class, 'createStepTwo'])->name('serviceConnectionInspections.create-step-two');
    Route::resource('serviceConnectionInspections', App\Http\Controllers\ServiceConnectionInspectionsController::class);


    Route::get('/service_connection_mtr_trnsfrmrs/assigning', [App\Http\Controllers\ServiceConnectionMtrTrnsfrmrController::class, 'assigning'])->name('serviceConnectionMtrTrnsfrmrs.assigning');
    Route::get('/service_connection_mtr_trnsfrmrs/create_step_three/{scId}', [App\Http\Controllers\ServiceConnectionMtrTrnsfrmrController::class, 'createStepThree'])->name('serviceConnectionMtrTrnsfrmrs.create-step-three');
    Route::resource('serviceConnectionMtrTrnsfrmrs', App\Http\Controllers\ServiceConnectionMtrTrnsfrmrController::class);


    Route::resource('serviceConnectionMatPayables', App\Http\Controllers\ServiceConnectionMatPayablesController::class);


    Route::resource('serviceConnectionPayParticulars', App\Http\Controllers\ServiceConnectionPayParticularsController::class);


    Route::resource('serviceConnectionMatPayments', App\Http\Controllers\ServiceConnectionMatPaymentsController::class);

    Route::get('/service_connection_pay_tansactions/create_step_four/{scId}', [App\Http\Controllers\ServiceConnectionPayTransactionController::class, 'createStepFour'])->name('serviceConnectionPayTransactions.create-step-four');
    Route::resource('serviceConnectionPayTransactions', App\Http\Controllers\ServiceConnectionPayTransactionController::class);


    Route::resource('serviceConnectionTotalPayments', App\Http\Controllers\ServiceConnectionTotalPaymentsController::class);


    Route::resource('serviceConnectionTimeframes', App\Http\Controllers\ServiceConnectionTimeframesController::class);


    Route::post('/member_consumer_checklists/complyChecklists/{id}', [App\Http\Controllers\MemberConsumerChecklistsController::class, 'complyChecklists'])->name('memberConsumerChecklists.comply-checklists');
    Route::resource('memberConsumerChecklists', App\Http\Controllers\MemberConsumerChecklistsController::class);


    Route::resource('memberConsumerChecklistsReps', App\Http\Controllers\MemberConsumerChecklistsRepController::class);


    Route::resource('serviceConnectionChecklistsReps', App\Http\Controllers\ServiceConnectionChecklistsRepController::class);

    Route::post('/service_connection_checklists_reps/complyChecklists/{id}', [App\Http\Controllers\ServiceConnectionChecklistsController::class, 'complyChecklists'])->name('serviceConnectionChecklists.comply-checklists');
    Route::post('/service_connection_checklists_reps/save-file-and-comply-checklist', [App\Http\Controllers\ServiceConnectionChecklistsController::class, 'saveFileAndComplyChecklist']);
    Route::get('/service_connection_checklists_reps/assess-checklist-completion/{scId}', [App\Http\Controllers\ServiceConnectionChecklistsController::class, 'assessChecklistCompletion'])->name('serviceConnectionChecklists.assess-checklist-completion');
    Route::get('/service_connection_checklists_reps/download-file/{scId}/{folder}/{file}', [App\Http\Controllers\ServiceConnectionChecklistsController::class, 'downloadFile'])->name('serviceConnectionChecklists.download-file');
    Route::resource('serviceConnectionChecklists', App\Http\Controllers\ServiceConnectionChecklistsController::class);


    Route::resource('serviceConnectionCrews', App\Http\Controllers\ServiceConnectionCrewController::class);


    Route::get('/service_accounts/pending-accounts/', [ServiceAccountsController::class, 'pendingAccounts'])->name('serviceAccounts.pending-accounts');
    Route::get('/service_accounts/account-migration/{id}', [ServiceAccountsController::class, 'accountMigration'])->name('serviceAccounts.account-migration');
    Route::get('/service_accounts/account-migration-step-two/{id}', [ServiceAccountsController::class, 'accountMigrationStepTwo'])->name('serviceAccounts.account-migration-step-two');
    Route::get('/service_accounts/account-migration-step-three/{id}', [ServiceAccountsController::class, 'accountMigrationStepThree'])->name('serviceAccounts.account-migration-step-three');
    Route::get('/service_accounts/update_step_one/{id}', [ServiceAccountsController::class, 'updateStepOne'])->name('serviceAccounts.update-step-one');
    Route::get('/service_accounts/merge-all-bill-arrears/{id}', [ServiceAccountsController::class,  'mergeAllBillArrears'])->name('serviceAccounts.merge-all-bill-arrears');
    Route::get('/service_accounts/unmerge-all-bill-arrears/{id}', [ServiceAccountsController::class,  'unmergeAllBillArrears'])->name('serviceAccounts.unmerge-all-bill-arrears');
    Route::get('/service_accounts/unmerge-bill-arrear/{billId}', [ServiceAccountsController::class,  'unmergeBillArrear'])->name('serviceAccounts.unmerge-bill-arrear');
    Route::get('/service_accounts/merge-bill-arrear/{billId}', [ServiceAccountsController::class,  'mergeBillArrear'])->name('serviceAccounts.merge-bill-arrear');
    Route::get('/service_accounts/accounts-map-view', [ServiceAccountsController::class,  'accountsMapView'])->name('serviceAccounts.accounts-map-view');
    Route::get('/service_accounts/get-accounts-by-town', [ServiceAccountsController::class,  'getAccountsByTown'])->name('serviceAccounts.get-accounts-by-town');
    Route::get('/service_accounts/bapa', [ServiceAccountsController::class,  'bapa'])->name('serviceAccounts.bapa');
    Route::get('/service_accounts/create-bapa', [ServiceAccountsController::class,  'createBapa'])->name('serviceAccounts.create-bapa');
    Route::get('/service_accounts/get-routes-from-district', [ServiceAccountsController::class,  'getRoutesFromDistrict'])->name('serviceAccounts.get-routes-from-district');
    Route::get('/service_accounts/add-to-bapa', [ServiceAccountsController::class,  'addToBapa'])->name('serviceAccounts.add-to-bapa');
    Route::get('/service_accounts/bapa-view/{bapaName}', [ServiceAccountsController::class,  'bapaView'])->name('serviceAccounts.bapa-view');
    Route::get('/service_accounts/remove-bapa-by-route', [ServiceAccountsController::class,  'removeBapaByRoute'])->name('serviceAccounts.remove-bapa-by-route');
    Route::get('/service_accounts/remove-bapa-by-account', [ServiceAccountsController::class,  'removeBapaByAccount'])->name('serviceAccounts.remove-bapa-by-account');
    Route::get('/service_accounts/update-bapa/{bapaName}', [ServiceAccountsController::class,  'updateBapa'])->name('serviceAccounts.update-bapa');
    Route::get('/service_accounts/search-accout-bapa', [ServiceAccountsController::class,  'searchAccountBapa'])->name('serviceAccounts.search-accout-bapa');
    Route::get('/service_accounts/add-single-account-to-bapa', [ServiceAccountsController::class,  'addSingleAccountToBapa'])->name('serviceAccounts.add-single-account-to-bapa');
    Route::get('/service_accounts/reading-account-grouper', [ServiceAccountsController::class,  'readingAccountGrouper'])->name('serviceAccounts.reading-account-grouper');
    Route::get('/service_accounts/account-grouper-view/{townCode}', [ServiceAccountsController::class,  'accountGrouperView'])->name('serviceAccounts.account-grouper-view');
    Route::get('/service_accounts/account-grouper-organizer/{townCode}/{groupCode}', [ServiceAccountsController::class,  'accountGrouperOrganizer'])->name('serviceAccounts.account-grouper-organizer');
    Route::get('/bills/bapa-view-readings/{period}/{bapaName}', [ServiceAccountsController::class,  'bapaViewReadings'])->name('bills.bapa-view-readings');
    Route::get('/service_accounts/re-sequence-accounts', [ServiceAccountsController::class,  'reSequenceAccounts'])->name('serviceAccounts.re-sequence-accounts');
    Route::get('/service_accounts/update-gps-coordinates', [ServiceAccountsController::class,  'updateGPSCoordinates'])->name('serviceAccounts.update-gps-coordinates');
    Route::get('/service_accounts/search-global', [ServiceAccountsController::class,  'searchGlobal'])->name('serviceAccounts.search-global');
    Route::get('/service_accounts/termed-payment-accounts', [ServiceAccountsController::class,  'termedPaymentAccounts'])->name('serviceAccounts.termed-payment-accounts');
    Route::get('/service_accounts/disconnect-manual', [ServiceAccountsController::class,  'disconnectManual'])->name('serviceAccounts.disconnect-manual');
    Route::get('/service_accounts/reconnect-manual', [ServiceAccountsController::class,  'reconnectManual'])->name('serviceAccounts.reconnect-manual');
    Route::get('/service_accounts/apprehend-manual', [ServiceAccountsController::class,  'apprehendManual'])->name('serviceAccounts.apprehend-manual');
    Route::get('/service_accounts/pullout-manual', [ServiceAccountsController::class,  'pulloutManual'])->name('serviceAccounts.pullout-manual');
    Route::get('/service_accounts/change-name', [ServiceAccountsController::class,  'changeName'])->name('serviceAccounts.change-name');
    Route::get('/service_accounts/relocation-form/{accountNo}/{scId}', [ServiceAccountsController::class,  'relocationForm'])->name('serviceAccounts.relocation-form');
    Route::get('/service_accounts/print-ledger/{id}/{from}/{to}', [ServiceAccountsController::class,  'printLedger'])->name('serviceAccounts.print-ledger');
    Route::post('/service_accounts/store-relocation', [ServiceAccountsController::class,  'storeRelocation'])->name('serviceAccounts.store-relocation');
    Route::get('/service_accounts/search-for-captured', [ServiceAccountsController::class,  'searchForCaptured'])->name('serviceAccounts.search-for-captured');
    Route::get('/service_accounts/print-bapa-bills-list/{bapaName}/{period}', [ServiceAccountsController::class,  'printBapaBillsList'])->name('serviceAccounts.print-bapa-bills-list');
    Route::get('/service_accounts/confirm-change-name/{id}', [ServiceAccountsController::class, 'confirmChangeName'])->name('serviceAccounts.confirm-change-name');
    Route::post('/service_accounts/update-name', [ServiceAccountsController::class, 'updateName'])->name('serviceAccounts.update-name');
    Route::get('/service_accounts/search-bapa-ajax', [ServiceAccountsController::class, 'searchBapaAjax'])->name('serviceAccounts.search-bapa-ajax');
    Route::get('/service_accounts/rename-bapa', [ServiceAccountsController::class, 'renameBapa'])->name('serviceAccounts.rename-bapa');
    Route::get('/service_accounts/validate-old-account-no', [ServiceAccountsController::class, 'validateOlAccountNo'])->name('serviceAccounts.validate-old-account-no');
    Route::get('/service_accounts/manual-account-migration-one', [ServiceAccountsController::class, 'manualAccountMigrationOne'])->name('serviceAccounts.manual-account-migration-one');
    Route::post('/service_accounts/store-manual', [ServiceAccountsController::class, 'storeManual'])->name('serviceAccounts.store-manual');
    Route::get('/service_accounts/manual-account-migration-two/{id}', [ServiceAccountsController::class, 'manualAccountMigrationTwo'])->name('serviceAccounts.manual-account-migration-two');
    Route::post('/service_accounts/store-meters-manual', [ServiceAccountsController::class, 'storeMetersManual'])->name('serviceAccounts.store-meters-manual');
    Route::get('/service_accounts/manual-account-migration-three/{id}', [ServiceAccountsController::class, 'manualAccountMigrationThree'])->name('serviceAccounts.manual-account-migration-three');
    Route::post('/service_accounts/store-transformer-manual', [ServiceAccountsController::class, 'storeTransformerManual'])->name('serviceAccounts.store-transformer-manual');
    Route::get('/service_accounts/change-meter-manual', [ServiceAccountsController::class, 'changeMeterManual'])->name('serviceAccounts.change-meter-manual');
    Route::get('/service_accounts/change-meter-manual-console/{id}', [ServiceAccountsController::class, 'changeMeterManualConsole'])->name('serviceAccounts.change-meter-manual-console');
    Route::post('/service_accounts/store-change-meter-manual', [ServiceAccountsController::class, 'storeChangeMeterManual'])->name('serviceAccounts.store-change-meter-manual');
    Route::get('/service_accounts/relocation-manual', [ServiceAccountsController::class, 'relocationManual'])->name('serviceAccounts.relocation-manual');
    Route::get('/service_accounts/relocation-form-manual/{id}', [ServiceAccountsController::class, 'relocationFormManual'])->name('serviceAccounts.relocation-form-manual');
    Route::get('/service_accounts/print-group-bills-list/{period}/{groupId}', [ServiceAccountsController::class, 'printGroupBillsList'])->name('serviceAccounts.print-group-bills-list');
    Route::get('/service_accounts/check-available-account-numbers', [ServiceAccountsController::class, 'checkAvailableAccountNumbers'])->name('serviceAccounts.check-available-account-numbers');
    Route::get('/service_accounts/disco-pullout-appr', [ServiceAccountsController::class, 'discoPulloutAppr'])->name('serviceAccounts.disco-pullout-appr');
    Route::get('/service_accounts/download-disco-pullout-appr/{from}/{to}/{town}/{status}/{period}', [ServiceAccountsController::class, 'downloadDiscoPulloutAppr'])->name('serviceAccounts.download-disco-pullout-appr');
    Route::get('/service_accounts/account-grouper-edit/{day}/{town}', [ServiceAccountsController::class, 'accountGrouperEdit'])->name('serviceAccounts.account-grouper-edit');
    Route::get('/service_accounts/fetch-route-from-mreader', [ServiceAccountsController::class, 'fetchRouteFromMeterReader'])->name('serviceAccounts.fetch-route-from-mreader');
    Route::get('/service_accounts/move-route', [ServiceAccountsController::class, 'moveRoute'])->name('serviceAccounts.move-route');
    Route::get('/service_accounts/route-checker', [ServiceAccountsController::class, 'routeChecker'])->name('serviceAccounts.route-checker');
    Route::get('/service_accounts/net-metering', [ServiceAccountsController::class, 'netMetering'])->name('serviceAccounts.net-metering');
    Route::get('/service_accounts/net-metering-dashboard', [ServiceAccountsController::class, 'netMeteringDashboard'])->name('serviceAccounts.net-metering-dashboard');
    Route::get('/service_accounts/add-prepayment-balance-manually', [ServiceAccountsController::class, 'addPrepaymentBalanceManually'])->name('serviceAccounts.add-prepayment-balance-manually');
    Route::get('/service_accounts/remove-download-tag', [ServiceAccountsController::class, 'removeDownloadedTag'])->name('serviceAccounts.remove-download-tag');
    Route::get('/service_accounts/contestable-accounts', [ServiceAccountsController::class, 'contestableAccounts'])->name('serviceAccounts.contestable-accounts');
    Route::get('/service_accounts/coop-consumption-accounts', [ServiceAccountsController::class, 'coopConsumptionAccounts'])->name('serviceAccounts.coop-consumption-accounts');
    Route::get('/service_accounts/account-list', [ServiceAccountsController::class, 'accountList'])->name('serviceAccounts.account-list');
    Route::get('/service_accounts/view-account-list', [ServiceAccountsController::class, 'viewAccountList'])->name('serviceAccounts.view-account-list');
    Route::get('/service_accounts/download-account-list/{town}/{status}', [ServiceAccountsController::class, 'downloadAccountList'])->name('serviceAccounts.download-account-list');
    Route::get('/service_accounts/mark-bouncing-check', [ServiceAccountsController::class, 'markBouncingCheck'])->name('serviceAccounts.mark-bouncing-check');
    Route::get('/service_accounts/clear-bouncing-check', [ServiceAccountsController::class, 'clearBouncingCheck'])->name('serviceAccounts.clear-bouncing-check');
    Route::get('/service_accounts/search-account-ajax', [ServiceAccountsController::class, 'searchAccountAjax'])->name('serviceAccounts.search-account-ajax');
    Route::get('/service_accounts/meter-readers', [ServiceAccountsController::class, 'meterReaders'])->name('serviceAccounts.meter-readers');
    Route::get('/service_accounts/meter-readers-view/{id}', [ServiceAccountsController::class, 'meterReadersView'])->name('serviceAccounts.meter-readers-view');
    Route::get('/service_accounts/get-accounts-by-meter-reader', [ServiceAccountsController::class, 'getAccountsByMeterReader'])->name('serviceAccounts.get-accounts-by-meter-reader');
    Route::get('/service_accounts/meter-readers-add-account/{meterReader}/{group}', [ServiceAccountsController::class, 'meterReadersAddAccount'])->name('serviceAccounts.meter-readers-add-account');
    Route::get('/service_accounts/search-account-for-meter-reader', [ServiceAccountsController::class, 'searchAccountForMeterReader'])->name('serviceAccounts.search-account-for-meter-reader');
    Route::get('/service_accounts/change-meter-reader', [ServiceAccountsController::class, 'changeMeterReader'])->name('serviceAccounts.change-meter-reader');
    Route::get('/service_accounts/change-material-deposit-state', [ServiceAccountsController::class, 'changeMaterialDepositState'])->name('serviceAccounts.change-material-deposit-state');
    Route::get('/service_accounts/change-customer-deposit-state', [ServiceAccountsController::class, 'changeCustomerDepositState'])->name('serviceAccounts.change-customer-deposit-state');
    Route::get('/service_accounts/get-existing-accounts', [ServiceAccountsController::class, 'getExistingAccounts'])->name('serviceAccounts.get-existing-accounts');
    Route::get('/service_accounts/lifeliners-view', [ServiceAccountsController::class, 'lifelinersView'])->name('serviceAccounts.lifeliners-view');
    Route::get('/service_accounts/senior-citizen-view', [ServiceAccountsController::class, 'seniorCitizenView'])->name('serviceAccounts.senior-citizen-view');
    Route::get('/service_accounts/invalidate-lifeliners-and-scs', [ServiceAccountsController::class, 'invalidateLifelinersAndSeniorCitizens'])->name('serviceAccounts.invalidate-lifeliners-and-scs');
    Route::get('/service_accounts/material-deposit-accounts', [ServiceAccountsController::class, 'materialDepositAccounts'])->name('serviceAccounts.material-deposit-accounts');
    Route::get('/service_accounts/customer-deposit-accounts', [ServiceAccountsController::class, 'customerDepositAccounts'])->name('serviceAccounts.customer-deposit-accounts');
    Route::get('/service_accounts/increment-customer-deposit-interests', [ServiceAccountsController::class, 'incrementCustomerDepositInterests'])->name('serviceAccounts.increment-customer-deposit-interests');
    Route::get('/service_accounts/refund-customer-deposit', [ServiceAccountsController::class, 'refundCustomerDeposit'])->name('serviceAccounts.refund-customer-deposit');
    Route::get('/service_accounts/mw-customer-list', [ServiceAccountsController::class, 'mwCustomerList'])->name('serviceAccounts.mw-customer-list');
    Route::post('/service_accounts/validate-mw-customer-file', [ServiceAccountsController::class, 'validateMwCustomerFile'])->name('serviceAccounts.validate-mw-customer-file');
    Route::get('/service_accounts/mw-customer-master-list', [ServiceAccountsController::class, 'mwCustomerMasterList'])->name('serviceAccounts.mw-customer-master-list');
    Route::post('/service_accounts/validate-mw-customer-master-list-file', [ServiceAccountsController::class, 'validateMwCustomerMasterListFile'])->name('serviceAccounts.validate-mw-customer-master-list-file');
    Route::get('/service_accounts/mw-advance-material-deposit-balances', [ServiceAccountsController::class, 'mwAdvanceMaterialDepositBalances'])->name('serviceAccounts.mw-advance-material-deposit-balances');
    Route::post('/service_accounts/validate-mw-advance-material-deposit-balances', [ServiceAccountsController::class, 'validateMwAdvanceMaterialDepositBalances'])->name('serviceAccounts.validate-mw-advance-material-deposit-balances');
    Route::get('/service_accounts/mw-customer-deposit-balances', [ServiceAccountsController::class, 'mwCustomerDepositBalances'])->name('serviceAccounts.mw-customer-deposit-balances');
    Route::post('/service_accounts/validate-mw-customer-deposit-balances', [ServiceAccountsController::class, 'validateMwCustomerDepositBalances'])->name('serviceAccounts.validate-mw-customer-deposit-balances');
    Route::get('/service_accounts/mw-ledgers', [ServiceAccountsController::class, 'mwLedgers'])->name('serviceAccounts.mw-ledgers');
    Route::post('/service_accounts/validate-mw-ledgers', [ServiceAccountsController::class, 'validateMwLedgers'])->name('serviceAccounts.validate-mw-ledgers');
    Route::resource('serviceAccounts', ServiceAccountsController::class);


    Route::resource('serviceConnectionLgLoadInsps', App\Http\Controllers\ServiceConnectionLgLoadInspController::class);


    Route::get('/structures/get-structures-json', [App\Http\Controllers\StructuresController::class, 'getStructuresJson'])->name('structures.get-structures-json');
    Route::get('/structures/get-structures-by-type', [App\Http\Controllers\StructuresController::class, 'getStructuresByType'])->name('structures.get-structures-by-type');
    Route::resource('structures', App\Http\Controllers\StructuresController::class);


    Route::resource('materialAssets', App\Http\Controllers\MaterialAssetsController::class);


    Route::resource('materialsMatrices', App\Http\Controllers\MaterialsMatrixController::class);


    Route::resource('billOfMaterialsIndices', App\Http\Controllers\BillOfMaterialsIndexController::class);


    Route::resource('billOfMaterialsDetails', App\Http\Controllers\BillOfMaterialsDetailsController::class);

    Route::post('/structure_assignments/insert-structure-assignment', [App\Http\Controllers\StructureAssignmentsController::class, 'insertStructureAssignment']);
    Route::get('/structure_assignments/delete-brackets', [App\Http\Controllers\StructureAssignmentsController::class, 'deleteBrackets'])->name('structureAssignments.delete-brackets');
    Route::get('/structure_assignments/get-bracket-structure', [App\Http\Controllers\StructureAssignmentsController::class, 'getBracketStructure'])->name('structureAssignments.get-bracket-structure');
    Route::resource('structureAssignments', App\Http\Controllers\StructureAssignmentsController::class);


    Route::get('/bill_of_materials_matrices/view/{scId}', [App\Http\Controllers\BillOfMaterialsMatrixController::class, 'view'])->name('billOfMaterialsMatrices.view');
    Route::get('/bill_of_materials_matrices/download-bill-of-materials/{scId}',  [App\Http\Controllers\BillOfMaterialsMatrixController::class, 'downloadBillOfMaterials'])->name('billOfMaterialsMatrices.download-bill-of-materials');
    Route::get('/bill_of_materials_matrices/get-bill-of-materials-json/', [App\Http\Controllers\BillOfMaterialsMatrixController::class, 'getBillOfMaterialsJson'])->name('billOfMaterialsMatrices.get-bill-of-materials-json');
    Route::post('/bill_of_materials_matrices/insert-transformer-bracket', [App\Http\Controllers\BillOfMaterialsMatrixController::class, 'insertTransformerBracket'])->name('billOfMaterialsMatrices.insert-transformer-bracket');
    Route::get('/bill_of_materials_matrices/get-bill-of-materials-brackets/', [App\Http\Controllers\BillOfMaterialsMatrixController::class, 'getBillOfMaterialsBrackets'])->name('billOfMaterialsMatrices.get-bill-of-materials-brackets');
    Route::post('/bill_of_materials_matrices/insert-pole', [App\Http\Controllers\BillOfMaterialsMatrixController::class, 'insertPole'])->name('billOfMaterialsMatrices.insert-pole');
    Route::get('/bill_of_materials_matrices/fetch-poles/', [App\Http\Controllers\BillOfMaterialsMatrixController::class, 'fetchPoles'])->name('billOfMaterialsMatrices.fetch-poles');
    Route::get('/bill_of_materials_matrices/delete-pole/', [App\Http\Controllers\BillOfMaterialsMatrixController::class, 'deletePole'])->name('billOfMaterialsMatrices.delete-pole');
    Route::get('/bill_of_materials_matrices/delete-material/', [App\Http\Controllers\BillOfMaterialsMatrixController::class, 'deleteMaterial'])->name('billOfMaterialsMatrices.delete-material');
    Route::post('/bill_of_materials_matrices/add-custom-material', [App\Http\Controllers\BillOfMaterialsMatrixController::class, 'addCustomMaterial'])->name('billOfMaterialsMatrices.add-custom-material');
    Route::post('/bill_of_materials_matrices/insert-spanning-materials', [App\Http\Controllers\BillOfMaterialsMatrixController::class, 'insertSpanningMaterials'])->name('billOfMaterialsMatrices.insert-spanning-materials');
    Route::get('/bill_of_materials_matrices/fetch-span-material/', [App\Http\Controllers\BillOfMaterialsMatrixController::class, 'fetchSpanMaterials'])->name('billOfMaterialsMatrices.fetch-span-material');
    Route::get('/bill_of_materials_matrices/delete-span-material/', [App\Http\Controllers\BillOfMaterialsMatrixController::class, 'deleteSpanMaterial'])->name('billOfMaterialsMatrices.delete-span-material');
    Route::post('/bill_of_materials_matrices/insert-sdw-materials', [App\Http\Controllers\BillOfMaterialsMatrixController::class, 'insertSDWMaterials'])->name('billOfMaterialsMatrices.insert-sdw-materials');
    Route::post('/bill_of_materials_matrices/insert-special-equipment', [App\Http\Controllers\BillOfMaterialsMatrixController::class, 'insertSpecialEquipment'])->name('billOfMaterialsMatrices.insert-special-equipment');
    Route::get('/bill_of_materials_matrices/fetch-equipments/', [App\Http\Controllers\BillOfMaterialsMatrixController::class, 'fetchEquipments'])->name('billOfMaterialsMatrices.fetch-equipments');
    Route::resource('billOfMaterialsMatrices', App\Http\Controllers\BillOfMaterialsMatrixController::class);


    Route::resource('transformerIndices', App\Http\Controllers\TransformerIndexController::class);

    Route::post('/transformers_assigned_matrices/create-ajax', [App\Http\Controllers\TransformersAssignedMatrixController::class, 'createAjax'])->name('transformersAssignedMatrices.create-ajax');
    Route::get('/transformers_assigned_matrices/fetch-transformers', [App\Http\Controllers\TransformersAssignedMatrixController::class, 'fetchTransformers'])->name('transformersAssignedMatrices.fetch-transformers');
    Route::resource('transformersAssignedMatrices', App\Http\Controllers\TransformersAssignedMatrixController::class);


    Route::resource('poleIndices', App\Http\Controllers\PoleIndexController::class);


    Route::resource('billsOfMaterialsSummaries', App\Http\Controllers\BillsOfMaterialsSummaryController::class);


    Route::resource('spanningIndices', App\Http\Controllers\SpanningIndexController::class);


    Route::resource('spanningDatas', App\Http\Controllers\SpanningDataController::class);


    Route::resource('preDefinedMaterials', App\Http\Controllers\PreDefinedMaterialsController::class);


    Route::post('/preDefinedMaterialsMatrices/update-data/', [App\Http\Controllers\PreDefinedMaterialsMatrixController::class, 'updateData']);
    Route::get('/preDefinedMaterialsMatrices/re-init/{scId}/{options}', [App\Http\Controllers\PreDefinedMaterialsMatrixController::class, 'reInit'])->name('preDefinedMaterialsMatrices.re-init');
    Route::post('/preDefinedMaterialsMatrices/add-material/', [App\Http\Controllers\PreDefinedMaterialsMatrixController::class, 'addMaterial']);
    Route::resource('preDefinedMaterialsMatrices', App\Http\Controllers\PreDefinedMaterialsMatrixController::class);


    Route::post('/member_consumer_images/create-image/', [App\Http\Controllers\MemberConsumerImagesController::class, 'createImage'])->name('memberConsumerImages.create-image');
    Route::get('/member_consumer_images/get-image/{id}', [App\Http\Controllers\MemberConsumerImagesController::class, 'getImage'])->name('memberConsumerImages.get-image');
    Route::resource('memberConsumerImages', App\Http\Controllers\MemberConsumerImagesController::class);


    Route::get('/tickets/create-select', [TicketsController::class, 'createSelect'])->name('tickets.create-select');
    Route::get('/tickets/get-create-ajax', [TicketsController::class, 'getCreateAjax'])->name('tickets.get-create-ajax');
    Route::get('/tickets/create-new/{id}', [TicketsController::class, 'createNew'])->name('tickets.create-new');
    Route::get('/tickets/fetch-tickets', [TicketsController::class, 'fetchTickets'])->name('tickets.fetch-tickets');
    Route::get('/tickets/print-ticket/{id}', [TicketsController::class, 'printTicket'])->name('tickets.print-ticket');
    Route::get('/tickets/trash', [TicketsController::class, 'trash'])->name('tickets.trash');
    Route::get('/tickets/restore-ticket/{id}', [TicketsController::class, 'restoreTicket'])->name('tickets.restore-ticket');
    Route::post('/tickets/update-date-filed', [TicketsController::class, 'updateDateFiled'])->name('tickets.update-date-filed');
    Route::post('/tickets/update-date-downloaded', [TicketsController::class, 'updateDateDownloaded'])->name('tickets.update-date-downloaded');
    Route::post('/tickets/update-date-arrival', [TicketsController::class, 'updateDateArrival'])->name('tickets.update-date-arrival');
    Route::post('/tickets/update-execution', [TicketsController::class, 'updateExecution'])->name('tickets.update-execution');
    Route::get('/tickets/dashboard', [TicketsController::class, 'dashboard'])->name('tickets.dashboard');
    Route::get('/tickets/fetch-dashboard-tickets-trend', [TicketsController::class, 'fetchDashboardTicketsTrend'])->name('tickets.fetch-dashboard-tickets-trend');
    Route::get('/tickets/get-ticket-statistics', [TicketsController::class, 'getTicketStatistics'])->name('tickets.get-ticket-statistics');
    Route::get('/tickets/get-ticket-statistics-details', [TicketsController::class, 'getTicketStatisticsDetails'])->name('tickets.get-ticket-statistics-details');
    Route::get('/tickets/kps-monitor', [TicketsController::class, 'kpsMonitor'])->name('tickets.kps-monitor');
    Route::get('/tickets/get-kps-ticket-crew-graph', [TicketsController::class, 'getKpsTicketCrewGraph'])->name('tickets.get-kps-ticket-crew-graph');
    Route::get('/tickets/get-ticket-avg-hours', [TicketsController::class, 'getTicketCrewAverageHours'])->name('tickets.get-ticket-avg-hours');
    Route::get('/tickets/get-overall-avg-kps', [TicketsController::class, 'getOverAllAverageKps'])->name('tickets.get-overall-avg-kps');
    Route::get('/tickets/change-meter', [TicketsController::class, 'changeMeter'])->name('tickets.change-meter');
    Route::get('/tickets/create-change-meter/{accountNumber}', [TicketsController::class, 'createChangeMeter'])->name('tickets.create-change-meter');
    Route::get('/tickets/assessments-change-meter', [TicketsController::class, 'changeMeterAssessments'])->name('tickets.assessments-change-meter');
    Route::get('/tickets/assessments-ordinary-ticket', [TicketsController::class, 'ordinaryTicketsAssessment'])->name('tickets.assessments-ordinary-ticket');
    Route::get('/tickets/assess-change-meter-form/{ticketId}', [TicketsController::class, 'assessChangeMeterForm'])->name('tickets.assess-change-meter-form');
    Route::post('/tickets/update-change-meter-assessment', [TicketsController::class, 'updateChangeMeterAssessment'])->name('tickets.update-change-meter-assessment');
    Route::post('/tickets/update-ordinary-ticket-assessment', [TicketsController::class, 'updateOrdinaryTicketAssessment'])->name('tickets.update-ordinary-ticket-assessment');
    Route::get('/tickets/ticket-summary-report', [TicketsController::class, 'ticketSummaryReport'])->name('tickets.ticket-summary-report');
    Route::get('/tickets/get-ticket-summary-report', [TicketsController::class, 'getTicketSummaryResults'])->name('tickets.get-ticket-summary-report');
    Route::get('/tickets/ticket-summary-report-download-route', [TicketsController::class, 'ticketSummaryReportDownloadRoute'])->name('tickets.ticket-summary-report-download-route');
    Route::get('/tickets/download-tickets-summary-report/{ticketParam}/{from}/{to}/{area}/{status}', [TicketsController::class, 'downloadTicketsSummaryReport'])->name('tickets.download-tickets-summary-report');
    Route::get('/tickets/disconnection-assessments', [TicketsController::class, 'disconnectionAssessments'])->name('tickets.disconnection-assessments');
    Route::get('/tickets/get-disconnection-results', [TicketsController::class, 'getDisconnectionResults'])->name('tickets.get-disconnection-results');
    Route::get('/tickets/disconnection-results-route', [TicketsController::class, 'disconnectionResultsRoute'])->name('tickets.disconnection-results-route');
    Route::get('/tickets/create-and-print-disconnection-tickets/{period}/{route}', [TicketsController::class, 'createAndPrintDisconnectionTickets'])->name('tickets.create-and-print-disconnection-tickets');
    Route::get('/tickets/ticket-tally', [TicketsController::class, 'ticketTally'])->name('tickets.ticket-tally');
    Route::get('/tickets/get-ticket-tally', [TicketsController::class, 'getTicketTally'])->name('tickets.get-ticket-tally');
    Route::get('/tickets/download-ticket-tally/{town}/{from}/{to}', [TicketsController::class, 'downloadTicketTally'])->name('tickets.download-ticket-tally');
    Route::get('/tickets/kps-summary-report', [TicketsController::class, 'kpsSummaryReport'])->name('tickets.kps-summary-report');
    Route::get('/tickets/download-kps-summary-report/{town}/{from}/{to}', [TicketsController::class, 'downloadKpsSummaryReport'])->name('tickets.download-kps-summary-report');
    Route::get('/tickets/create-select-new', [App\Http\Controllers\TicketsController::class, 'createSelectNew'])->name('tickets.create-select-new');
    Route::get('/tickets/search-account', [App\Http\Controllers\TicketsController::class, 'searchAccount'])->name('tickets.search-account');
    Route::resource('tickets', TicketsController::class);


    Route::resource('ticketsRepositories', App\Http\Controllers\TicketsRepositoryController::class);


    Route::resource('ticketLogs', App\Http\Controllers\TicketLogsController::class);

    Route::post('/special_equipment_materials/create-material', [App\Http\Controllers\SpecialEquipmentMaterialsController::class, 'createEquipment']);
    Route::resource('specialEquipmentMaterials', App\Http\Controllers\SpecialEquipmentMaterialsController::class);


    Route::resource('serviceConnectionImages', App\Http\Controllers\ServiceConnectionImagesController::class);


    Route::resource('billingTransformers', App\Http\Controllers\BillingTransformersController::class);


    Route::resource('billingMeters', App\Http\Controllers\BillingMetersController::class);


    Route::resource('meterReaders', App\Http\Controllers\MeterReadersController::class);


    Route::resource('meterReaderTrackNames', App\Http\Controllers\MeterReaderTrackNamesController::class);

    Route::get('/meter_reader_tracks/get-tracks-by-tracknameid', [App\Http\Controllers\MeterReaderTracksController::class, 'getTracksByTrackNameId'])->name('meterReaderTracks.get-tracks-by-tracknameid');
    Route::resource('meterReaderTracks', App\Http\Controllers\MeterReaderTracksController::class);


    Route::get('/damage_assessments/get-objects', [App\Http\Controllers\DamageAssessmentController::class, 'getObjects'])->name('damageAssessments.get-objects');
    Route::get('/damage_assessments/search-pole', [App\Http\Controllers\DamageAssessmentController::class, 'searchPole'])->name('damageAssessments.search-pole');
    Route::get('/damage_assessments/view-pole', [App\Http\Controllers\DamageAssessmentController::class, 'viewPole'])->name('damageAssessments.view-pole');
    Route::post('/damage_assessments/update-ajax', [App\Http\Controllers\DamageAssessmentController::class, 'updateAjax'])->name('damageAssessments.update-ajax');
    Route::resource('damageAssessments', App\Http\Controllers\DamageAssessmentController::class);


    Route::get('/reading_schedules/update-schedule/{userId}', [ReadingSchedulesController::class, 'updateSchedule'])->name('readingSchedules.update-schedule');
    Route::get('/reading_schedules/view-schedule/{userId}', [ReadingSchedulesController::class, 'viewSchedule'])->name('readingSchedules.view-schedule');
    Route::get('/reading_schedules/get-latest-schedule', [ReadingSchedulesController::class, 'getLatestSchedule'])->name('readingSchedules.get-latest-schedule');
    Route::get('/reading_schedules/reading-schedule-index', [ReadingSchedulesController::class, 'readingScheduleIndex'])->name('readingSchedules.reading-schedule-index');
    Route::get('/reading_schedules/view-meter-reading-scheds-in-period/{period}', [ReadingSchedulesController::class, 'viewMeterReadingSchedsInPeriod'])->name('readingSchedules.view-meter-reading-scheds-in-period');
    Route::get('/reading_schedules/create-reading-schedule', [ReadingSchedulesController::class, 'createReadingSchedule'])->name('readingSchedules.create-reading-schedule');
    Route::post('/reading_schedules/store-reading-schedule', [ReadingSchedulesController::class, 'storeReadingSchedules'])->name('readingSchedules.store-reading-schedule');
    Route::resource('readingSchedules', ReadingSchedulesController::class);


    Route::get('/rates/upload-rate', [RatesController::class, 'uploadRate'])->name('rates.upload-rate');
    Route::post('/rates/validate-rate-upload', [RatesController::class, 'validateRateUpload'])->name('rates.validate-rate-upload');
    Route::get('/rates/view-rates/{servicePeriod}', [RatesController::class, 'viewRates'])->name('rates.view-rates');
    Route::post('/rates/delete-rates/{servicePeriod}', [RatesController::class, 'deleteRates'])->name('rates.delete-rates');
    Route::get('/rates/get-rate', [RatesController::class, 'getRate'])->name('rates.get-rate');
    Route::resource('rates', RatesController::class);

    Route::get('/readings/reading-monitor', [ReadingsController::class, 'readingMonitor'])->name('readings.reading-monitor');
    Route::get('/readings/reading-monitor-view/{servicePeriod}', [ReadingsController::class, 'readingMonitorView'])->name('readings.reading-monitor-view');
    Route::get('/readings/get-readings-from-meter-reader', [ReadingsController::class, 'getReadingsFromMeterReader'])->name('readings.get-readings-from-meter-reader');
    Route::get('/readings/manual-reading', [ReadingsController::class, 'manualReading'])->name('readings.manual-reading');
    Route::get('/readings/manual-reading-console/{id}', [ReadingsController::class, 'manualReadingConsole'])->name('readings.manual-reading-console');
    Route::get('/readings/captured-readings-console/{id}/{readId}/{day}/{bapaName}', [ReadingsController::class, 'capturedReadingsConsole'])->name('readings.captured-readings-console');
    Route::get('/readings/get-computed-bill', [ReadingsController::class, 'getComputedBill'])->name('readings.get-computed-bill');
    Route::post('/readings/create-manual-billing', [ReadingsController::class, 'createManualBilling'])->name('readings.create-manual-billing');
    Route::get('/readings/captured-readings', [ReadingsController::class, 'capturedReadings'])->name('readings.captured-readings');
    Route::get('/readings/mark-as-done', [ReadingsController::class, 'markAsDone'])->name('readings.mark-as-done');
    Route::get('/readings/fetch-account', [ReadingsController::class, 'fetchAccount'])->name('readings.fetch-account');
    Route::get('/readings/view-full-report/{period}/{meterReader}/{day}/{town}', [ReadingsController::class, 'viewFullReport'])->name('readings.view-full-report');
    Route::get('/readings/view-full-report-bapa/{period}/{bapaName}', [ReadingsController::class, 'viewFullReportBapa'])->name('readings.view-full-report-bapa');
    Route::get('/readings/get-previous-readings', [ReadingsController::class, 'getPreviousReadings'])->name('readings.get-previous-readings');
    Route::get('/readings/create-manual-billing-ajax', [ReadingsController::class, 'createManualBillingAjax'])->name('readings.create-manual-billing-ajax');
    Route::get('/readings/check-if-account-has-bill', [ReadingsController::class, 'checkIfAccountHasBill'])->name('readings.check-if-account-has-bill');
    Route::post('/readings/create-bill-for-captured-reading', [ReadingsController::class, 'createBillForCapturedReading'])->name('readings.create-bill-for-captured-reading');
    Route::get('/readings/print-old-format-adjusted/{period}/{day}/{town}/{meterReader}', [ReadingsController::class, 'printOldFormatAdjusted'])->name('readings.print-old-format-adjusted');
    Route::get('/readings/print-new-format-adjusted/{period}/{day}/{town}/{meterReader}', [ReadingsController::class, 'printNewFormatAdjusted'])->name('readings.print-new-format-adjusted');
    Route::get('/readings/print-old-format-adjusted-bapa/{period}/{bapaName}', [ReadingsController::class, 'printOldFormatAdjustedBapa'])->name('readings.print-old-format-adjusted-bapa');
    Route::get('/readings/print-new-format-adjusted-bapa/{period}/{bapaName}', [ReadingsController::class, 'printNewFormatAdjustedBapa'])->name('readings.print-new-format-adjusted-bapa');
    Route::get('/readings/print-unbilled-by-status/{period}/{day}/{town}/{meterReader}/{status}', [ReadingsController::class, 'printUnbilledList'])->name('readings.print-unbilled-by-status');
    Route::get('/readings/print-other-unbilled-list/{period}/{day}/{town}/{meterReader}', [ReadingsController::class, 'printOtherUnbilledList'])->name('readings.print-other-unbilled-list');
    Route::get('/readings/billed-and-unbilled-reports', [ReadingsController::class, 'billAndUnbilledReport'])->name('readings.billed-and-unbilled-reports');
    Route::get('/readings/print-billed-unbilled/{type}/{meterReader}/{day}/{period}/{town}/{status}', [ReadingsController::class, 'printBilledUnbilled'])->name('readings.print-billed-unbilled');
    Route::get('/readings/print-disco-active/{meterReader}/{day}/{period}/{town}', [ReadingsController::class, 'printDiscoActive'])->name('readings.print-disco-active');
    Route::get('/readings/billed-and-unbilled-reports-bapa', [ReadingsController::class, 'billAndUnbilledReportBapa'])->name('readings.billed-and-unbilled-reports-bapa');
    Route::get('/readings/print-billed-unbilled-bapa/{type}/{bapaName}/{period}/{status}', [ReadingsController::class, 'printBilledUnbilledBapa'])->name('readings.print-billed-unbilled-bapa');
    Route::get('/readings/efficiency-report', [ReadingsController::class, 'efficiencyReport'])->name('readings.efficiency-report');
    Route::get('/readings/print-efficiency-report/{meterReader}/{month}/{office}', [ReadingsController::class, 'printEfficiencyReport'])->name('readings.print-efficiency-report');
    Route::get('/readings/print-bapa-reading-list', [ReadingsController::class, 'printBapaReadingList'])->name('readings.print-bapa-reading-list');
    Route::get('/readings/search-print-bapa-reading-list', [ReadingsController::class, 'searchPrintBapaReadingList'])->name('readings.search-print-bapa-reading-list');
    Route::get('/readings/print-bapa-reading-list-to-paper/{bapaName}/{period}', [ReadingsController::class, 'printBapaReadingListToPaper'])->name('readings.print-bapa-reading-list-to-paper');
    Route::get('/readings/print-bulk-new-format-mreader/{period}/{day}/{town}/{mreader}', [ReadingsController::class, 'printBulkBillNewFormatMreader'])->name('readings.print-bulk-new-format-mreader');
    Route::get('/readings/print-bulk-old-format-mreader/{period}/{day}/{town}/{mreader}', [ReadingsController::class, 'printBulkBillOldFormatMreader'])->name('readings.print-bulk-old-format-mreader');
    Route::get('/readings/print-group-reading-list/{bapaName}/{period}', [ReadingsController::class, 'printGroupReadingList'])->name('readings.print-group-reading-list');
    Route::get('/readings/get-meter-readers', [ReadingsController::class, 'getMeterReaders'])->name('readings.get-meter-readers');
    Route::get('/readings/erroneous-readings', [ReadingsController::class, 'erroneousReading'])->name('readings.erroneous-readings');
    Route::get('/readings/abrupt-increase-decrease', [ReadingsController::class, 'abruptIncreaseDecrease'])->name('readings.abrupt-increase-decrease');
    Route::get('/readings/analyze-abrupt-increase-decrease', [ReadingsController::class, 'analyzeAbruptIncreaseDecrease'])->name('readings.analyze-abrupt-increase-decrease');
    Route::get('/readings/show-excemptions-per-route', [ReadingsController::class, 'showExcemptionsPerRoute'])->name('readings.show-excemptions-per-route');
    Route::get('/readings/show-disconnected-per-route', [ReadingsController::class, 'showDisconnectedPerRoute'])->name('readings.show-disconnected-per-route');
    Route::get('/readings/show-outstanding-per-route', [ReadingsController::class, 'showOutstandingPerRoute'])->name('readings.show-outstanding-per-route');
    Route::get('/readings/disco-per-mreader', [ReadingsController::class, 'discoPerMeterReader'])->name('readings.disco-per-mreader');
    Route::get('/readings/print-disco-per-mreader/{period}/{meterReader}', [ReadingsController::class, 'printDiscoPerMeterReader'])->name('readings.print-disco-per-mreader');
    Route::get('/readings/uncollected-per-mreader', [ReadingsController::class, 'uncollectedPerMeterReader'])->name('readings.uncollected-per-mreader');
    Route::get('/readings/print-uncollected-per-mreader/{period}/{meterReader}', [ReadingsController::class, 'printUncollectedPerMeterReader'])->name('readings.print-uncollected-per-mreader');
    Route::get('/readings/excemptions-per-mreader', [ReadingsController::class, 'excemptionsPerMeterReader'])->name('readings.excemptions-per-mreader');
    Route::get('/readings/print-excemptions-per-mreader/{period}/{meterReader}', [ReadingsController::class, 'printExcemptionsPerMeterReader'])->name('readings.print-excemptions-per-mreader');
    Route::get('/readings/disco-per-bapa', [ReadingsController::class, 'discoPerBapa'])->name('readings.disco-per-bapa');
    Route::get('/readings/get-previous-reading', [ReadingsController::class, 'getPreviousReading'])->name('readings.get-previous-reading');
    Route::get('/readings/upload-text-file', [ReadingsController::class, 'uploadTextFile'])->name('readings.upload-text-file');
    Route::post('/readings/process-uploaded-text-file', [ReadingsController::class, 'processUploadedTextFile'])->name('readings.process-uploaded-text-file');
    Route::get('/readings/download-text-file/{svcPeriod}/{meterReader}/{groupCode}/{areaCode}', [ReadingsController::class, 'downloadTextFile'])->name('readings.download-text-file');
    Route::get('/readings/full-report', [ReadingsController::class, 'fullReport'])->name('readings.full-report');
    Route::get('/readings/get-billing-months', [ReadingsController::class, 'getBillingMonths'])->name('readings.get-billing-months');
    Route::get('/readings/get-reading-report-data', [ReadingsController::class, 'getReadingReportData'])->name('readings.get-reading-report-data');
    Route::resource('readings', ReadingsController::class);

    Route::get('/bills/unbilled-readings', [BillsController::class, 'unbilledReadings'])->name('bills.unbilled-readings');
    Route::get('/bills/unbilled-readings-console/{servicePeriod}', [BillsController::class, 'unbilledReadingsConsole'])->name('bills.unbilled-readings-console');
    Route::get('/bills/zero-readings-view/{readingId}', [BillsController::class, 'zeroReadingsView'])->name('bills.zero-readings-view');
    Route::get('/bills/average-bill/{readingId}', [BillsController::class, 'averageBill'])->name('bills.average-bill');
    Route::get('/bills/rebill-reading-adjustment/{readingId}', [BillsController::class, 'rebillReadingAdjustment'])->name('bills.rebill-reading-adjustment');
    Route::post('/bills/rebill/{readingId}', [BillsController::class, 'rebill'])->name('bills.rebill');
    Route::get('/bills/adjust-bill/{billId}', [BillsController::class, 'adjustBill'])->name('bills.adjust-bill');
    Route::get('/bills/fetch-bill-adjustment-data', [BillsController::class, 'fetchBillAdjustmentData'])->name('bills.fetch-bill-adjustment-data');
    Route::get('/bills/fetch-net-metering-bill-adjustment-data', [BillsController::class, 'fetchNetMeteringBillAdjustmentData'])->name('bills.fetch-net-metering-bill-adjustment-data');
    Route::get('/bills/all-bills', [BillsController::class,  'allBills'])->name('bills.all-bills');
    Route::get('/bills/bill-arrears-unlocking', [BillsController::class,  'billArrearsUnlocking'])->name('bills.bill-arrears-unlocking');
    Route::get('/bills/unlock-bill-arrear/{id}', [BillsController::class,  'unlockBillArrear'])->name('bills.unlock-bill-arrear');
    Route::get('/bills/reject-unlock-bill-arrear/{id}', [BillsController::class,  'rejectUnlockBillArrear'])->name('bills.reject-unlock-bill-arrear');
    Route::get('/bills/grouped-billing', [BillsController::class,  'groupedBilling'])->name('bills.grouped-billing');
    Route::get('/bills/create-group-billing-step-one', [BillsController::class,  'createGroupBillingStepOne'])->name('bills.create-group-billing-step-one');
    Route::get('/bills/create-group-billing-step-one-pre-select', [BillsController::class,  'createGroupBillingStepOnePreSelect'])->name('bills.create-group-billing-step-one-pre-select');
    Route::get('/bills/create-group-billing-step-two/{memberConsumerId}', [BillsController::class,  'createGroupBillingStepTwo'])->name('bills.create-group-billing-step-two');
    Route::post('/bills/store-group-billing-step-one', [BillsController::class,  'storeGroupBillingStepOne'])->name('bills.store-group-billing-step-one');
    Route::get('/bills/fetch-member-consumers', [BillsController::class,  'fetchMemberConsumers'])->name('bills.fetch-member-consumers');
    Route::get('/bills/search-account', [BillsController::class,  'searchAccount'])->name('bills.search-account');
    Route::get('/bills/add-to-group', [BillsController::class,  'addToGroup'])->name('bills.add-to-group');
    Route::get('/bills/remove-from-group', [BillsController::class,  'removeFromGroup'])->name('bills.remove-from-group');
    Route::get('/bills/grouped-billing-view/{memberConsumerId}', [BillsController::class,  'groupedBillingView'])->name('bills.grouped-billing-view');
    Route::get('/bills/grouped-billing-bill-view/{memberConsumerId}/{period}', [BillsController::class,  'groupedBillingBillView'])->name('bills.grouped-billing-bill-view');
    Route::get('/bills/add-two-percent', [BillsController::class,  'add2Percent'])->name('bills.add-two-percent');
    Route::get('/bills/remove-two-percent', [BillsController::class,  'remove2Percent'])->name('bills.remove-two-percent');
    Route::get('/bills/add-five-percent', [BillsController::class,  'add5Percent'])->name('bills.add-five-percent');
    Route::get('/bills/remove-five-percent', [BillsController::class,  'remove5Percent'])->name('bills.remove-five-percent');
    Route::get('/bills/print-group-billing/{memberConsumerId}/{period}/{withSurcharge}', [BillsController::class,  'printGroupBilling'])->name('bills.print-group-billing');
    Route::get('/bills/print-single-bill-new-format/{billId}', [BillsController::class,  'printSingleBillNewFormat'])->name('bills.print-single-bill-new-format');
    Route::get('/bills/print-single-bill-old/{billId}', [BillsController::class,  'printSingleBillOld'])->name('bills.print-single-bill-old');
    Route::get('/bills/bulk-print-bill', [BillsController::class,  'bulkPrintBill'])->name('bills.bulk-print-bill');
    Route::get('/bills/get-routes-from-town', [BillsController::class,  'getRoutesFromTown'])->name('bills.get-routes-from-town');
    Route::get('/bills/print-bulk-bill-new-format/{period}/{town}/{route}/{day}', [BillsController::class,  'printBulkBillNewFormat'])->name('bills.print-bulk-bill-new-format');
    Route::get('/bills/print-bulk-bill-old-format/{period}/{town}/{route}', [BillsController::class,  'printBulkBillOldFormat'])->name('bills.print-bulk-bill-old-format');
    Route::get('/bills/print-bulk-bill-old-format-bapa/{period}/{bapaName}/{from}/{route}', [BillsController::class,  'printBulkBillOldFormatBapa'])->name('bills.print-bulk-bill-old-format-bapa');
    Route::get('/bills/print-bulk-bill-new-format-bapa/{period}/{bapaName}/{from}/{route}', [BillsController::class,  'printBulkBillNewFormatBapa'])->name('bills.print-bulk-bill-new-format-bapa');
    Route::get('/bills/bapa-manual-billing', [BillsController::class,  'bapaManualBilling'])->name('bills.bapa-manual-billing');
    Route::get('/bills/search-bapa-for-billing', [BillsController::class,  'searchBapaForBilling'])->name('bills.search-bapa-for-billing');
    Route::get('/bills/bapa-manual-billing-console/{bapaName}', [BillsController::class,  'bapaManualBillingConsole'])->name('bills.bapa-manual-billing-console');
    Route::get('/bills/get-bill-computation', [BillsController::class,  'getBillComputation'])->name('bills.get-bill-computation');
    Route::get('/bills/bill-manually', [BillsController::class,  'billManually'])->name('bills.bill-manually');
    Route::get('/bills/fetch-billed-consumers-from-reading', [BillsController::class,  'fetchBilledConsumersFromReading'])->name('bills.fetch-billed-consumers-from-reading');
    Route::get('/bills/request-cancel-bill', [BillsController::class,  'requestCancelBill'])->name('bills.request-cancel-bill');
    Route::get('/bills/bills-cancellation-approval', [BillsController::class,  'billsCancellationApproval'])->name('bills.bills-cancellation-approval');
    Route::get('/bills/approve-bill-cancellation-request/{id}', [BillsController::class,  'approveBillCancellationRequest'])->name('bills.approve-bill-cancellation-request');
    Route::get('/bills/reject-bill-cancellation-request/{id}', [BillsController::class,  'rejectBillCancellationRequest'])->name('bills.reject-bill-cancellation-request');
    Route::get('/bills/change-meter-readings/{account}/{period}', [BillsController::class,  'changeMeterReadings'])->name('bills.change-meter-readings');
    Route::post('/bills/bill-change-meters', [BillsController::class,  'billChangeMeters'])->name('bills.bill-change-meters');
    Route::get('/bills/adjustment-reports', [BillsController::class,  'adjustmentReports'])->name('bills.adjustment-reports');
    Route::get('/bills/print-adjustment-report/{type}/{period}', [BillsController::class,  'printAdjustmentReport'])->name('bills.print-adjustment-report');
    Route::get('/bills/mark-as-paid', [BillsController::class, 'markAsPaid'])->name('bills.mark-as-paid');
    Route::get('/bills/dashboard', [BillsController::class, 'dashboard'])->name('bills.dashboard');
    Route::get('/bills/dashboard-reading-monitor', [BillsController::class, 'dashboardReadingMonitor'])->name('bills.dashboard-reading-monitor');
    Route::get('/bills/change-bapa-duedate', [BillsController::class, 'changeBapaDueDate'])->name('bills.change-bapa-duedate');
    Route::get('/bills/print-bulk-bill-old-format-group/{period}/{groupId}', [BillsController::class,  'printBulkBillOldFormatGroup'])->name('bills.print-bulk-bill-old-format-group');
    Route::get('/bills/print-bulk-bill-new-format-group/{period}/{groupId}', [BillsController::class,  'printBulkBillNewFormatGroup'])->name('bills.print-bulk-bill-new-format-group');
    Route::get('/bills/delete-bill-and-reading-ajax', [BillsController::class, 'deleteBillAndReadingAjax'])->name('bills.delete-bill-and-reading-ajax');
    Route::get('/bills/kwh-monitoring', [BillsController::class, 'kwhMonitoring'])->name('bills.kwh-monitoring');
    Route::get('/bills/fetch-kwh-data', [BillsController::class, 'fetchKwhData'])->name('bills.fetch-kwh-data');
    Route::get('/bills/group-bill-all', [BillsController::class, 'groupBillingBillAll'])->name('bills.group-bill-all');
    Route::get('/bills/close-billing/{period}', [BillsController::class, 'closeBilling'])->name('bills.close-billing');
    Route::get('/bills/lifeliners-report', [BillsController::class, 'lifelinersReport'])->name('bills.lifeliners-report');
    Route::get('/bills/print-lifeliners/{town}/{period}/{khwused}', [BillsController::class, 'printLifeliners'])->name('bills.print-lifeliners');
    Route::get('/bills/senior-citizen-report', [BillsController::class, 'seniorCitizenReport'])->name('bills.senior-citizen-report');
    Route::get('/bills/print-senior-citizen/{town}/{period}', [BillsController::class, 'printSeniorCitizen'])->name('bills.print-senior-citizen');
    Route::get('/bills/get-minified-collection-efficiency', [BillsController::class, 'getMinifiedCollectionEfficiency'])->name('bills.get-minified-collection-efficiency');
    Route::get('/bills/government-tax-report', [BillsController::class, 'governmentTaxReport'])->name('bills.government-tax-report');
    Route::get('/bills/print-government-tax-report/{period}/{town}/{route}', [BillsController::class, 'printGovernmentTaxReport'])->name('bills.print-government-tax-report');
    Route::get('/bills/outstanding-report', [BillsController::class, 'outstandingReport'])->name('bills.outstanding-report');
    Route::get('/bills/download-outstanding-report/{asOf}/{town}/{status}', [BillsController::class, 'downloadOutstandingReport'])->name('bills.download-outstanding-report');
    Route::get('/bills/disconnected-reports', [BillsController::class, 'disconnectedReports'])->name('bills.disconnected-reports');
    Route::get('/bills/print-single-net-metering/{billId}', [BillsController::class,  'printSingleNetMetering'])->name('bills.print-single-net-metering');
    Route::get('/bills/adjust-bill-net-metering/{billId}', [BillsController::class, 'adjustBillNetMetering'])->name('bills.adjust-bill-net-metering');
    Route::get('/bills/get-billing-adjustment-history', [BillsController::class, 'getBillingAdjustmentHistory'])->name('bills.get-billing-adjustment-history');
    Route::get('/bills/show-uncollected-dashboard', [BillsController::class, 'showUncollectedDashboard'])->name('bills.show-uncollected-dashboard');
    Route::get('/bills/unbilled-no-meter-readers', [BillsController::class, 'unbilledNoMeterReaders'])->name('bills.unbilled-no-meter-readers');
    Route::get('/bills/all-billed', [BillsController::class, 'allBilled'])->name('bills.all-billed');
    Route::get('/bills/download-all-billed/{town}/{period}/{type}', [BillsController::class, 'downloadAllBilled'])->name('bills.download-all-billed');
    Route::get('/bills/newly-energized', [BillsController::class, 'newlyEnergizedConsumers'])->name('bills.newly-energized');
    Route::get('/bills/download-newly-energized/{town}/{period}', [BillsController::class, 'downloadNewlyEnergized'])->name('bills.download-newly-energized');
    Route::get('/bills/show-unbilled-dashboard', [BillsController::class, 'showUnbilledDashboard'])->name('bills.show-unbilled-dashboard');
    Route::get('/bills/outstanding-report-mreader', [BillsController::class, 'outstandingReportMreader'])->name('bills.outstanding-report-mreader');
    Route::get('/bills/download-outstanding-report-mreader/{asOf}/{meterReader}/{status}', [BillsController::class, 'downloadOutstandingReportMreader'])->name('bills.download-outstanding-report-mreader');
    Route::get('/bills/outstanding-report-bapa', [BillsController::class, 'outstandingReportBAPA'])->name('bills.outstanding-report-bapa');
    Route::get('/bills/download-outstanding-report-bapa/{asOf}/{bapa}/{status}', [BillsController::class, 'downloadOutstandingReportBAPA'])->name('bills.download-outstanding-report-bapa');
    Route::get('/bills/adjustment-reports-with-gl', [BillsController::class,  'adjustmentReportsWithGL'])->name('bills.adjustment-reports-with-gl');
    Route::get('/bills/cancelled-bills', [BillsController::class,  'cancelledBills'])->name('bills.cancelled-bills');
    Route::get('/bills/print-cancelled-bills/{from}/{to}/{area}', [BillsController::class,  'printCancelledBills'])->name('bills.print-cancelled-bills');
    Route::get('/bills/detailed-adjustments', [BillsController::class,  'detailedAdjustments'])->name('bills.detailed-adjustments');
    Route::get('/bills/bill-adjustments-approval', [BillsController::class,  'billAdjustmentsApproval'])->name('bills.bill-adjustments-approval');
    Route::get('/bills/bill-adjustments-approval-view/{origId}', [BillsController::class,  'billAdjustmentsApprovalView'])->name('bills.bill-adjustments-approval-view');
    Route::get('/bills/bill-adjustments-approve/{origId}', [BillsController::class,  'billAdjustmentsApprove'])->name('bills.bill-adjustments-approve');
    Route::get('/bills/bill-adjustments-reject/{origId}', [BillsController::class,  'billAdjustmentsReject'])->name('bills.bill-adjustments-reject');
    Route::get('/bills/allow-skip', [BillsController::class,  'allowSkip'])->name('bills.allow-skip');
    Route::get('/bills/request-waive-surcharges', [BillsController::class,  'requestWaiveSurcharges'])->name('bills.request-waive-surcharges');
    Route::get('/bills/get-bill-ajax', [BillsController::class,  'getBillAjax'])->name('bills.get-bill-ajax');
    Route::get('/bills/get-five-percent-ajax', [BillsController::class,  'get2307FivePercentAjax'])->name('bills.get-five-percent-ajax');
    Route::get('/bills/save-withholding-taxes', [BillsController::class,  'saveWitholdingTaxes'])->name('bills.save-withholding-taxes');
    Route::get('/bills/unwaive-surcharges', [BillsController::class,  'unwaiveSurcharges'])->name('bills.unwaive-surcharges');
    Route::resource('bills', BillsController::class);


    Route::resource('readingImages', App\Http\Controllers\ReadingImagesController::class);

    Route::get('/collectibles/ledgerize', [App\Http\Controllers\CollectiblesController::class, 'ledgerize'])->name('collectibles.ledgerize');
    Route::get('/collectibles/add-to-month', [App\Http\Controllers\CollectiblesController::class, 'addToMonth'])->name('collectibles.add-to-month');
    Route::get('/collectibles/clear-ledger', [App\Http\Controllers\CollectiblesController::class, 'clearLedger'])->name('collectibles.clear-ledger');
    Route::get('/collectibles/add-new', [App\Http\Controllers\CollectiblesController::class, 'addNew'])->name('collectibles.add-new');
    Route::get('/collectibles/get-ledger-from-collectible', [App\Http\Controllers\CollectiblesController::class, 'getLedgerFromCollectible'])->name('collectibles.get-ledger-from-collectible');
    Route::resource('collectibles', App\Http\Controllers\CollectiblesController::class);


    Route::resource('arrearsLedgerDistributions', App\Http\Controllers\ArrearsLedgerDistributionController::class);

    Route::get('/transaction_indices/service-connection-collection', [TransactionIndexController::class, 'serviceConnectionCollection'])->name('transactionIndices.service-connection-collection');
    Route::get('/transaction_indices/get-payable-details', [TransactionIndexController::class, 'getPayableDetails'])->name('transactionIndices.get-payable-details');
    Route::get('/transaction_indices/get-payable-total', [TransactionIndexController::class, 'getPayableTotal'])->name('transactionIndices.get-payable-total');
    Route::get('/transaction_indices/get-power-load-payables', [TransactionIndexController::class, 'getPowerLoadPayables'])->name('transactionIndices.get-power-load-payables');
    Route::get('/transaction_indices/save-and-print-or-service-connections', [TransactionIndexController::class, 'saveAndPrintORServiceConnections'])->name('transactionIndices.save-and-print-or-service-connections');
    Route::get('/transaction_indices/print-or-service-connections/{transactionIndexId}', [TransactionIndexController::class, 'printORServiceConnections'])->name('transactionIndices.print-or-service-connections');
    Route::get('/transaction_indices/uncollected-arrears', [TransactionIndexController::class, 'uncollectedArrears'])->name('transactionIndices.uncollected-arrears');
    Route::get('/transaction_indices/search-arrear-collectibles', [TransactionIndexController::class, 'searchArrearCollectibles'])->name('transactionIndices.search-arrear-collectibles');
    Route::get('/transaction_indices/fetch-arrear-details', [TransactionIndexController::class, 'fetchArrearDetails'])->name('transactionIndices.fetch-arrear-details');
    Route::get('/transaction_indices/save-arrear-transaction', [TransactionIndexController::class, 'saveArrearTransaction'])->name('transactionIndices.save-arrear-transaction');
    Route::get('/transaction_indices/ledger-arrears-collection/{accountNo}', [TransactionIndexController::class, 'ledgerArrearsCollection'])->name('transactionIndices.ledger-arrears-collection');
    Route::get('/transaction_indices/save-ledger-arrear-transaction', [TransactionIndexController::class, 'saveLedgerArrearTransaction'])->name('transactionIndices.save-ledger-arrear-transaction');
    Route::get('/transaction_indices/print-or-termed-ledger-arrears/{transactionIndexId}', [TransactionIndexController::class, 'printORTermedLedgerArrears'])->name('transactionIndices.print-or-termed-ledger-arrears');
    Route::get('/transaction_indices/other-payments', [TransactionIndexController::class, 'otherPayments'])->name('transactionIndices.other-payments');
    Route::get('/transaction_indices/search-consumer', [TransactionIndexController::class, 'searchConsumer'])->name('transactionIndices.search-consumer');
    Route::get('/transaction_indices/fetch-account-details', [TransactionIndexController::class, 'fetchAccountDetails'])->name('transactionIndices.fetch-account-details');
    Route::get('/transaction_indices/fetch-payable-details', [TransactionIndexController::class, 'fetchPayableDetails'])->name('transactionIndices.fetch-payable-details');
    Route::get('/transaction_indices/print-other-payments/{transactionIndexId}', [TransactionIndexController::class, 'printOtherPayments'])->name('transactionIndices.print-other-payments');
    Route::get('/transaction_indices/reconnection-collection', [TransactionIndexController::class, 'reconnectionCollection'])->name('transactionIndices.reconnection-collection');
    Route::get('/transaction_indices/search-disconnected-consumers', [TransactionIndexController::class, 'searchDisconnectedConsumers'])->name('transactionIndices.search-disconnected-consumers');
    Route::get('/transaction_indices/get-arrears-data', [TransactionIndexController::class, 'getArrearsData'])->name('transactionIndices.get-arrears-data');
    Route::get('/transaction_indices/save-reconnection-transaction', [TransactionIndexController::class, 'saveReconnectionTransaction'])->name('transactionIndices.save-reconnection-transaction');
    Route::get('/transaction_indices/add-check-payment', [TransactionIndexController::class, 'addCheckPayment'])->name('transactionIndices.add-check-payment');
    Route::get('/transaction_indices/delete-check-payment', [TransactionIndexController::class, 'deleteCheckPayment'])->name('transactionIndices.delete-check-payment');
    Route::get('/transaction_indices/browse-ors', [TransactionIndexController::class, 'browseORs'])->name('transactionIndices.browse-ors');
    Route::get('/transaction_indices/browse-ors-view/{id}/{paymentType}', [TransactionIndexController::class, 'browseORView'])->name('transactionIndices.browse-ors-view');
    Route::get('/transaction_indices/print-or-transactions/{transactionIndexId}', [TransactionIndexController::class, 'printOrTransactions'])->name('transactionIndices.print-or-transactions');
    Route::get('/transaction_indices/print-reconnection-collection/{transactionIndexId}', [TransactionIndexController::class, 'printOrReconnection'])->name('transactionIndices.print-reconnection-collection');
    Route::get('/transaction_indices/or-maintenance', [TransactionIndexController::class, 'orMaintenance'])->name('transactionIndices.or-maintenance');
    Route::get('/transaction_indices/update-or-number', [TransactionIndexController::class, 'updateORNumber'])->name('transactionIndices.update-or-number');
    Route::resource('transactionIndices', TransactionIndexController::class);


    Route::resource('transactionDetails', App\Http\Controllers\TransactionDetailsController::class);

    Route::get('/paid_bills/search', [PaidBillsController::class, 'search'])->name('paidBills.search');
    Route::get('/paid_bills/fetch-details', [PaidBillsController::class, 'fetchDetails'])->name('paidBills.fetch-details');
    Route::get('/paid_bills/fetch-account', [PaidBillsController::class, 'fetchAccount'])->name('paidBills.fetch-account');
    Route::get('/paid_bills/fetch-payable', [PaidBillsController::class, 'fetchPayable'])->name('paidBills.fetch-payable');
    Route::get('/paid_bills/save-paid-bill-and-print', [PaidBillsController::class, 'savePaidBillAndPrint'])->name('paidBills.save-paid-bill-and-print');
    Route::get('/paid_bills/print-bill-payment/{paidBillId}', [PaidBillsController::class, 'printBillPayment'])->name('paidBills.print-bill-payment');
    Route::get('/paid_bills/or-cancellation', [PaidBillsController::class, 'orCancellation'])->name('paidBills.or-cancellation');
    Route::get('/paid_bills/search-or', [PaidBillsController::class, 'searchOR'])->name('paidBills.search-or');
    Route::get('/paid_bills/fetch-or-details', [PaidBillsController::class, 'fetchORDetails'])->name('paidBills.fetch-or-details');
    Route::get('/paid_bills/request-cancel-or', [PaidBillsController::class, 'requestCancelOR'])->name('paidBills.request-cancel-or');
    Route::get('/paid_bills/request-bills-payment-unlock', [PaidBillsController::class, 'requestBillsPaymentUnlock'])->name('paidBills.request-bills-payment-unlock');
    Route::get('/paid_bills/bapa-payments', [PaidBillsController::class, 'bapaPayments'])->name('paidBills.bapa-payments');
    Route::get('/paid_bills/search-bapa', [PaidBillsController::class, 'searchBapa'])->name('paidBills.search-bapa');
    Route::get('/paid_bills/bapa-payment-console/{bapaName}', [PaidBillsController::class, 'bapaPaymentConsole'])->name('paidBills.bapa-payment-console');
    Route::get('/paid_bills/get-bills-from-bapa', [PaidBillsController::class, 'getBillsFromBapa'])->name('paidBills.get-bills-from-bapa');
    Route::get('/paid_bills/save-bapa-payments', [PaidBillsController::class, 'saveBapaPayments'])->name('paidBills.save-bapa-payments');
    Route::get('/paid_bills/bills-collection', [PaidBillsController::class, 'billsCollection'])->name('paidBills.bills-collection');
    Route::get('/paid_bills/get-adjusted-bapa-bills', [PaidBillsController::class, 'getAdjustedBapaBills'])->name('paidBills.get-adjusted-bapa-bills');
    Route::get('/paid_bills/add-check-payments', [PaidBillsController::class, 'addCheckPayments'])->name('paidBills.add-check-payments');
    Route::get('/paid_bills/delete-check-payment', [PaidBillsController::class, 'deleteCheckPayment'])->name('paidBills.delete-check-payment');
    Route::get('/paid_bills/fetch-account-by-old-account-number', [PaidBillsController::class, 'fetchAccountByOldAccountNumber'])->name('paidBills.fetch-account-by-old-account-number');
    Route::get('/paid_bills/print-bapa-payments/{dcrNum}', [PaidBillsController::class, 'printBapaPayments'])->name('paidBills.print-bapa-payments');
    Route::get('/paid_bills/get-ors-from-range', [PaidBillsController::class, 'getORsFromRange'])->name('paidBills.get-ors-from-range');
    Route::get('/paid_bills/add-denomination', [PaidBillsController::class, 'addDenomination'])->name('paidBills.add-denomination');
    Route::get('/paid_bills/third-party-collection', [PaidBillsController::class, 'thirdPartyCollection'])->name('paidBills.third-party-collection');
    Route::get('/paid_bills/upload-third-party-collection', [PaidBillsController::class, 'uploadThirdPartyCollection'])->name('paidBills.upload-third-party-collection');
    Route::post('/paid_bills/validate-tpc-upload', [PaidBillsController::class, 'validateTpcUpload'])->name('paidBills.validate-tpc-upload');
    Route::get('/paid_bills/tcp-upload-validator/{seriesNo}', [PaidBillsController::class, 'tcpUploadValidator'])->name('paidBills.tcp-upload-validator');
    Route::get('/paid_bills/deposit-double-payments/{seriesNo}', [PaidBillsController::class, 'depositDoublePayments'])->name('paidBills.deposit-double-payments');
    Route::get('/paid_bills/post-payments/{seriesNo}', [PaidBillsController::class, 'postPayments'])->name('paidBills.post-payments');
    Route::get('/paid_bills/third-party-collection-dcr/{source}/{date}/{series}/{postingDate}', [PaidBillsController::class, 'thirdPartyCollectionDCR'])->name('paidBills.third-party-collection-dcr');
    Route::get('/paid_bills/clear-all-tcp-uploads', [PaidBillsController::class, 'clearAllTcpUploads'])->name('paidBills.clear-all-tcp-uploads');
    Route::get('/paid_bills/cancel-or-admin', [PaidBillsController::class, 'cancelORAdmin'])->name('paidBills.cancel-or-admin');
    Route::get('/paid_bills/collection-summary-report', [PaidBillsController::class, 'collectionSummaryReport'])->name('paidBills.collection-summary-report');
    Route::get('/paid_bills/print-collection-summary-report/{from}/{to}/{town}', [PaidBillsController::class, 'printCollectionSummaryReport'])->name('paidBills.print-collection-summary-report');
    Route::get('/paid_bills/aging-report', [PaidBillsController::class, 'agingReport'])->name('paidBills.aging-report');
    Route::get('/paid_bills/print-aging-report/{town}/{asOf}', [PaidBillsController::class, 'printAgingReport'])->name('paidBills.print-aging-report');
    Route::get('/paid_bills/third-party-report', [PaidBillsController::class, 'thirdPartyReport'])->name('paidBills.third-party-report');
    Route::get('/paid_bills/print-third-party-report/{day}/{town}', [PaidBillsController::class, 'printThirdPartyReport'])->name('paidBills.print-third-party-report');
    Route::get('/paid_bills/third-party-api-console', [PaidBillsController::class, 'thirdPartyAPIConsole'])->name('paidBills.third-party-api-console');
    Route::get('/paid_bills/third-party-collection-api-dcr/{source}/{date}', [PaidBillsController::class, 'thirdPartyCollectionAPIDCR'])->name('paidBills.third-party-collection-api-dcr');
    Route::get('/paid_bills/clear-deposit', [PaidBillsController::class, 'clearDeposit'])->name('paidBills.clear-deposit');
    Route::get('/paid_bills/fix-third-party-dcr', [PaidBillsController::class, 'fixThirdPartyDCR'])->name('paidBills.fix-third-party-dcr');
    Route::get('/paid_bills/credit-memo/{acctNo}/{period}', [PaidBillsController::class, 'creditMemo'])->name('paidBills.credit-memo');
    Route::get('/paid_bills/search-account-for-credit-memo', [PaidBillsController::class, 'searchAccountForCreditMemo'])->name('paidBills.search-account-for-credit-memo');
    Route::get('/paid_bills/get-unpaid-bills-for-credit-memo', [PaidBillsController::class, 'getUnpaidBillsForCreditMemo'])->name('paidBills.get-unpaid-bills-for-credit-memo');
    Route::get('/paid_bills/apply-credit-memo', [PaidBillsController::class, 'applyCreditMemo'])->name('paidBills.apply-credit-memo');
    Route::resource('paidBills', PaidBillsController::class);

    Route::get('/disconnection_histories/generate-turn-off-list', [App\Http\Controllers\DisconnectionHistoryController::class, 'generateTurnOffList'])->name('disconnectionHistories.generate-turn-off-list');
    Route::get('/disconnection_histories/get-turn-off-list-preview', [App\Http\Controllers\DisconnectionHistoryController::class, 'getTurnOffListPreview'])->name('disconnectionHistories.get-turn-off-list-preview');
    Route::get('/disconnection_histories/get-turn-off-list-preview-route', [App\Http\Controllers\DisconnectionHistoryController::class, 'getTurnOffListPreviewRoute'])->name('disconnectionHistories.get-turn-off-list-preview-route');
    Route::get('/disconnection_histories/print-turn-off-list/{period}/{area}/{meterReader}/{day}', [App\Http\Controllers\DisconnectionHistoryController::class, 'printTurnOffList'])->name('disconnectionHistories.print-turn-off-list');
    Route::get('/disconnection_histories/print-turn-off-list-route/{period}/{area}/{route}', [App\Http\Controllers\DisconnectionHistoryController::class, 'printTurnOffListRoute'])->name('disconnectionHistories.print-turn-off-list-route');
    Route::resource('disconnectionHistories', App\Http\Controllers\DisconnectionHistoryController::class);

    Route::get('/disco_notice_histories/generate-nod', [App\Http\Controllers\DiscoNoticeHistoryController::class, 'generateNod'])->name('discoNoticeHistories.generate-nod');
    Route::get('/disco_notice_histories/get-disco-list-preview', [App\Http\Controllers\DiscoNoticeHistoryController::class, 'getDiscoListPreview'])->name('discoNoticeHistories.get-disco-list-preview');
    Route::get('/disco_notice_histories/print-reroute', [App\Http\Controllers\DiscoNoticeHistoryController::class, 'printReroute'])->name('discoNoticeHistories.print-reroute');
    Route::get('/disco_notice_histories/get-disco-list-preview-route', [App\Http\Controllers\DiscoNoticeHistoryController::class, 'getDiscoListPreviewRoute'])->name('discoNoticeHistories.get-disco-list-preview-route');
    Route::get('/disco_notice_histories/print-disconnection-list/{period}/{area}/{meterReader}/{day}', [App\Http\Controllers\DiscoNoticeHistoryController::class, 'printDisconnectionList'])->name('discoNoticeHistories.print-disconnection-list');
    Route::get('/disco_notice_histories/print-disconnection-list-route/{period}/{area}/{route}', [App\Http\Controllers\DiscoNoticeHistoryController::class, 'printDisconnectionListRoute'])->name('discoNoticeHistories.print-disconnection-list-route');
    Route::resource('discoNoticeHistories', App\Http\Controllers\DiscoNoticeHistoryController::class);


    Route::resource('accountPayables', App\Http\Controllers\AccountPayablesController::class);


    Route::get('/cache_other_payments/fetch-cached', [App\Http\Controllers\CacheOtherPaymentsController::class, 'fetchCached'])->name('cacheOtherPayments.fetch-cached');
    Route::get('/cache_other_payments/save-other-payments', [App\Http\Controllers\CacheOtherPaymentsController::class, 'saveOtherPayments'])->name('cacheOtherPayments.save-other-payments');
    Route::resource('cacheOtherPayments', App\Http\Controllers\CacheOtherPaymentsController::class);


    Route::get('/pending_bill_adjustments/open-reading-adjustments/{servicePeriod}', [App\Http\Controllers\PendingBillAdjustmentsController::class, 'openReadingAdjustments'])->name('pendingBillAdjustments.open-reading-adjustments');
    Route::get('/pending_bill_adjustments/confirm-all-adjustments/{servicePeriod}', [App\Http\Controllers\PendingBillAdjustmentsController::class, 'confirmAllAdjustments'])->name('pendingBillAdjustments.confirm-all-adjustments');
    Route::get('/pending_bill_adjustments/confirm-adjustment/{pendingAdjustmentId}', [App\Http\Controllers\PendingBillAdjustmentsController::class, 'confirmAdjustment'])->name('pendingBillAdjustments.confirm-adjustment');
    Route::resource('pendingBillAdjustments', App\Http\Controllers\PendingBillAdjustmentsController::class);


    Route::get('/o_r_assignings/get-last-or', [App\Http\Controllers\ORAssigningController::class, 'getLastOR'])->name('oRAssignings.get-last-or');
    Route::get('/o_r_assignings/get-next-or', [App\Http\Controllers\ORAssigningController::class, 'getNextOR'])->name('oRAssignings.get-next-or');
    Route::resource('oRAssignings', App\Http\Controllers\ORAssigningController::class);


    Route::post('/kwh_sales/generate-new/', [App\Http\Controllers\KwhSalesController::class, 'generateNew'])->name('kwhSales.generate-new');
    Route::post('/kwh_sales/save-sales-report', [App\Http\Controllers\KwhSalesController::class, 'saveSalesReport'])->name('kwhSales.save-sales-report');
    Route::get('/kwh_sales/view-sales/{id}', [App\Http\Controllers\KwhSalesController::class, 'viewSales'])->name('kwhSales.view-sales');
    Route::get('/kwh_sales/print-report/{id}', [App\Http\Controllers\KwhSalesController::class, 'printReport'])->name('kwhSales.print-report');
    Route::get('/kwh_sales/sales-distribution', [App\Http\Controllers\KwhSalesController::class, 'salesDistribution'])->name('kwhSales.sales-distribution');
    Route::get('/kwh_sales/sales-distribution-view/{period}', [App\Http\Controllers\KwhSalesController::class, 'salesDistributionView'])->name('kwhSales.sales-distribution-view');
    Route::get('/kwh_sales/consolidated-per-town/{period}', [App\Http\Controllers\KwhSalesController::class, 'consolidatedPerTown'])->name('kwhSales.consolidated-per-town');
    Route::get('/kwh_sales/summary-of-sales/{period}', [App\Http\Controllers\KwhSalesController::class, 'summaryOfSales'])->name('kwhSales.summary-of-sales');
    Route::get('/kwh_sales/print-summary-of-sales/{period}', [App\Http\Controllers\KwhSalesController::class, 'printSummaryOfSales'])->name('kwhSales.print-summary-of-sales');
    Route::get('/kwh_sales/dashboard-get-annual-sales-graph', [App\Http\Controllers\KwhSalesController::class, 'dashboardGetAnnualSalesGraph'])->name('kwhSales.dashboard-get-annual-sales-graph');
    Route::get('/kwh_sales/dashboard-get-annual-sales-pie-graph', [App\Http\Controllers\KwhSalesController::class, 'dashboardGetAnnualSalesPieGraph'])->name('kwhSales.dashboard-get-annual-sales-pie-graph');
    Route::get('/kwh_sales/kwh-sales-expanded', [App\Http\Controllers\KwhSalesController::class, 'kwhSalesExpanded'])->name('kwhSales.kwh-sales-expanded');
    Route::get('/kwh_sales/kwh-sales-expanded-view/{route}/{town}/{period}', [App\Http\Controllers\KwhSalesController::class, 'kwhSalesExpandedView'])->name('kwhSales.kwh-sales-expanded-view');
    Route::get('/kwh_sales/download-kwh-sales-expanded/{period}/{town}', [App\Http\Controllers\KwhSalesController::class, 'downloadKwhSalesExpanded'])->name('kwhSales.download-kwh-sales-expanded');
    Route::get('/kwh_sales/download-merged-sales/{period}', [App\Http\Controllers\KwhSalesController::class, 'downloadMergedSales'])->name('kwhSales.download-merged-sales');
    Route::get('/kwh_sales/download-summary-per-consumer-type/{period}', [App\Http\Controllers\KwhSalesController::class, 'downloadSummaryPerConsumerType'])->name('kwhSales.download-summary-per-consumer-type');
    Route::get('/kwh_sales/download-consolidated-per-district/{period}', [App\Http\Controllers\KwhSalesController::class, 'downloadConsolidatedPerDistrict'])->name('kwhSales.download-consolidated-per-district');
    Route::get('/kwh_sales/validate-confirm-user', [App\Http\Controllers\KwhSalesController::class, 'validateConfirmUser'])->name('kwhSales.validate-confirm-user');
    Route::resource('kwhSales', App\Http\Controllers\KwhSalesController::class);


    Route::get('/pre_payment_balances/search', [App\Http\Controllers\PrePaymentBalanceController::class, 'search'])->name('prePaymentBalances.search');
    Route::get('/pre_payment_balances/get-balance-details', [App\Http\Controllers\PrePaymentBalanceController::class, 'getBalanceDetails'])->name('prePaymentBalances.get-balance-details');
    Route::resource('prePaymentBalances', App\Http\Controllers\PrePaymentBalanceController::class);


    Route::resource('prePaymentTransHistories', App\Http\Controllers\PrePaymentTransHistoryController::class);


    Route::get('/notifiers/get-notifications', [App\Http\Controllers\NotifiersController::class, 'getNotifications'])->name('notifiers.get-notifications');
    Route::resource('notifiers', App\Http\Controllers\NotifiersController::class);


    Route::get('/o_r_cancellations/approve-bills-or-cancellation/{orCancellationId}', [App\Http\Controllers\ORCancellationsController::class, 'approveBillsORCancellation'])->name('oRCancellations.approve-bills-or-cancellation');
    Route::get('/o_r_cancellations/other-payments', [App\Http\Controllers\ORCancellationsController::class, 'otherPaymentsORCancellation'])->name('oRCancellations.other-payments');
    Route::get('/o_r_cancellations/fetch-transaction-indices', [App\Http\Controllers\ORCancellationsController::class, 'fetchTransactionIndices'])->name('oRCancellations.fetch-transaction-indices');
    Route::get('/o_r_cancellations/fetch-transaction-details', [App\Http\Controllers\ORCancellationsController::class, 'fetchTransactionDetails'])->name('oRCancellations.fetch-transaction-details');
    Route::get('/o_r_cancellations/fetch-transaction-particulars', [App\Http\Controllers\ORCancellationsController::class, 'fetchParticulars'])->name('oRCancellations.fetch-transaction-particulars');
    Route::get('/o_r_cancellations/attempt-cancel-transaction-or', [App\Http\Controllers\ORCancellationsController::class, 'attemptCancelTransactionOR'])->name('oRCancellations.attempt-cancel-transaction-or');
    Route::get('/o_r_cancellations/show-other-payments/{id}', [App\Http\Controllers\ORCancellationsController::class, 'showOtherPayments'])->name('oRCancellations.show-other-payments');
    Route::get('/o_r_cancellations/approve-transaction-cancellation/{id}', [App\Http\Controllers\ORCancellationsController::class, 'approveTransactionCancellation'])->name('oRCancellations.approve-transaction-cancellation');
    Route::resource('oRCancellations', App\Http\Controllers\ORCancellationsController::class);


    Route::get('/b_a_p_a_reading_schedules/show-schedules/{period}', [App\Http\Controllers\BAPAReadingSchedulesController::class, 'showSchedules'])->name('bAPAReadingSchedules.show-schedules');
    Route::get('/b_a_p_a_reading_schedules/add-schedule', [App\Http\Controllers\BAPAReadingSchedulesController::class, 'addSchedule'])->name('bAPAReadingSchedules.add-schedule');
    Route::get('/b_a_p_a_reading_schedules/get-bapas', [App\Http\Controllers\BAPAReadingSchedulesController::class, 'getBapas'])->name('bAPAReadingSchedules.get-bapas');
    Route::get('/b_a_p_a_reading_schedules/remove-bapa-from-sched', [App\Http\Controllers\BAPAReadingSchedulesController::class, 'removeBapaFromSched'])->name('bAPAReadingSchedules.remove-bapa-from-sched');
    Route::get('/b_a_p_a_reading_schedules/remove-downloaded-status-from-bapa', [App\Http\Controllers\BAPAReadingSchedulesController::class, 'removeDownloadedStatusFromBapa'])->name('bAPAReadingSchedules.remove-downloaded-status-from-bapa');
    Route::resource('bAPAReadingSchedules', App\Http\Controllers\BAPAReadingSchedulesController::class);


    Route::resource('bAPAPayments', App\Http\Controllers\BAPAPaymentsController::class);


    Route::resource('distributionSystemLosses', App\Http\Controllers\DistributionSystemLossController::class);


    Route::resource('rateItems', App\Http\Controllers\RateItemsController::class);


    Route::resource('changeMeterLogs', App\Http\Controllers\ChangeMeterLogsController::class);


    Route::resource('accountGLCodes', App\Http\Controllers\AccountGLCodesController::class);


    Route::resource('dCRSummaryTransactions', DCRSummaryTransactionsController::class);
    Route::get('/d_c_r_summary_transactions/sales-dcr-monitor', [DCRSummaryTransactionsController::class, 'salesDcrMonitor'])->name('dCRSummaryTransactions.sales-dcr-monitor');
    Route::get('/d_c_r_summary_transactions/print-dcr/{teller}/{day}', [DCRSummaryTransactionsController::class, 'printDcr'])->name('dCRSummaryTransactions.print-dcr');
    Route::get('/d_c_r_summary_transactions/dashboard', [DCRSummaryTransactionsController::class, 'collectionDashboard'])->name('dCRSummaryTransactions.dashboard');
    Route::get('/d_c_r_summary_transactions/get-collection-per-area', [DCRSummaryTransactionsController::class, 'dashboardGetCollectionPerArea'])->name('dCRSummaryTransactions.get-collection-per-area');
    Route::get('/d_c_r_summary_transactions/collection-office-expand/{office}', [DCRSummaryTransactionsController::class, 'collectionOfficeEpand'])->name('dCRSummaryTransactions.collection-office-expand');
    Route::get('/d_c_r_summary_transactions/get-gl-code-payment-details', [DCRSummaryTransactionsController::class, 'getGLCodePaymentDetails'])->name('dCRSummaryTransactions.get-gl-code-payment-details');
    Route::get('/d_c_r_summary_transactions/application-dcr-summary', [DCRSummaryTransactionsController::class, 'applicationDcrSummary'])->name('dCRSummaryTransactions.application-dcr-summary');
    Route::get('/d_c_r_summary_transactions/fix-dcr', [DCRSummaryTransactionsController::class, 'fixDcr'])->name('dCRSummaryTransactions.fix-dcr');
    Route::get('/d_c_r_summary_transactions/collection-summary-per-teller', [DCRSummaryTransactionsController::class, 'collectionSummaryPerTeller'])->name('dCRSummaryTransactions.collection-summary-per-teller');

    Route::resource('banks', App\Http\Controllers\BanksController::class);


    Route::get('/b_a_p_a_adjustments/adjust-bapa/{bapaName}', [App\Http\Controllers\BAPAAdjustmentsController::class, 'adjustBapaPayments'])->name('bAPAAdjustments.adjust-bapa');
    Route::get('/b_a_p_a_adjustments/search-bapa', [App\Http\Controllers\BAPAAdjustmentsController::class, 'searchBapa'])->name('bAPAAdjustments.search-bapa');
    Route::get('/b_a_p_a_adjustments/save-bapa-adjustments', [App\Http\Controllers\BAPAAdjustmentsController::class, 'saveBapaAdjustments'])->name('bAPAAdjustments.save-bapa-adjustments');
    Route::get('/b_a_p_a_adjustments/search-bapa-monitor', [App\Http\Controllers\BAPAAdjustmentsController::class, 'searchBapaMonitor'])->name('bAPAAdjustments.search-bapa-monitor');
    Route::get('/b_a_p_a_adjustments/get-bapa-monitor-search-results', [App\Http\Controllers\BAPAAdjustmentsController::class, 'getBapaMonitorSearchResults'])->name('bAPAAdjustments.get-bapa-monitor-search-results');
    Route::get('/b_a_p_a_adjustments/bapa-collection-monitor-console/{bapaName}', [App\Http\Controllers\BAPAAdjustmentsController::class, 'bapaCollectionMonitorConsole'])->name('bAPAAdjustments.bapa-collection-monitor-console');
    Route::get('/b_a_p_a_adjustments/print-voucher/{representative}/{bapaName}/{period}/{discount}/{dateAdjusted}', [App\Http\Controllers\BAPAAdjustmentsController::class, 'printVoucher'])->name('bAPAAdjustments.print-voucher');
    Route::get('/b_a_p_a_adjustments/update-bapa-adjustment', [App\Http\Controllers\BAPAAdjustmentsController::class, 'updateBapaPercentage'])->name('bAPAAdjustments.update-bapa-adjustment');
    Route::get('/b_a_p_a_adjustments/delete-bapa-adjustment', [App\Http\Controllers\BAPAAdjustmentsController::class, 'deleteBapaPercentage'])->name('bAPAAdjustments.delete-bapa-adjustment');
    Route::get('/b_a_p_a_adjustments/remove-account-from-voucher', [App\Http\Controllers\BAPAAdjustmentsController::class, 'removeAccountFromVoucher'])->name('bAPAAdjustments.remove-account-from-voucher');
    Route::resource('bAPAAdjustments', App\Http\Controllers\BAPAAdjustmentsController::class);


    Route::resource('bAPAAdjustmentDetails', App\Http\Controllers\BAPAAdjustmentDetailsController::class);


    Route::resource('paidBillsDetails', App\Http\Controllers\PaidBillsDetailsController::class);


    Route::resource('transacionPaymentDetails', App\Http\Controllers\TransacionPaymentDetailsController::class);


    Route::resource('billsOriginals', App\Http\Controllers\BillsOriginalController::class);


    Route::resource('accountNameHistories', App\Http\Controllers\AccountNameHistoryController::class);


    Route::resource('mastPoles', App\Http\Controllers\MastPolesController::class);


    Route::resource('dCRIndices', App\Http\Controllers\DCRIndexController::class);


    Route::resource('accountLocationHistories', App\Http\Controllers\AccountLocationHistoryController::class);


    Route::resource('denominations', App\Http\Controllers\DenominationsController::class);


    Route::get('/excemptions/new-excemptions', [App\Http\Controllers\ExcemptionsController::class, 'newExcemption'])->name('excemptions.new-excemptions');
    Route::get('/excemptions/search-account-excemption', [App\Http\Controllers\ExcemptionsController::class, 'searchAccountExcemption'])->name('excemptions.search-account-excemption');
    Route::get('/excemptions/add-excemption', [App\Http\Controllers\ExcemptionsController::class, 'addExcemption'])->name('excemptions.add-excemption');
    Route::get('/excemptions/get-excemptions-ajax', [App\Http\Controllers\ExcemptionsController::class, 'getExcemptionsAjax'])->name('excemptions.get-excemptions-ajax');
    Route::get('/excemptions/remove-excemption', [App\Http\Controllers\ExcemptionsController::class, 'removeExcemption'])->name('excemptions.remove-excemption');
    Route::get('/excemptions/print-excemptions/{town}', [App\Http\Controllers\ExcemptionsController::class, 'printExcemptions'])->name('excemptions.print-excemptions');
    Route::resource('excemptions', App\Http\Controllers\ExcemptionsController::class);


    Route::get('/katas_ng_vats/add-katas/{series}', [App\Http\Controllers\KatasNgVatController::class, 'addKatas'])->name('katasNgVats.add-katas');
    Route::get('/katas_ng_vats/add-account-to-katas', [App\Http\Controllers\KatasNgVatController::class, 'addAccountToKatas'])->name('katasNgVats.add-account-to-katas');
    Route::get('/katas_ng_vats/search-account', [App\Http\Controllers\KatasNgVatController::class, 'searchAccount'])->name('katasNgVats.search-account');
    Route::get('/katas_ng_vats/fetch-katas', [App\Http\Controllers\KatasNgVatController::class, 'fetchKatas'])->name('katasNgVats.fetch-katas');
    Route::get('/katas_ng_vats/delete-katas', [App\Http\Controllers\KatasNgVatController::class, 'deleteKatas'])->name('katasNgVats.delete-katas');
    Route::resource('katasNgVats', App\Http\Controllers\KatasNgVatController::class);


    Route::resource('katasNgVatTotals', App\Http\Controllers\KatasNgVatTotalController::class);

    Route::get('/third_party_tokens/regenerate-token/{id}', [App\Http\Controllers\ThirdPartyTokensController::class, 'regenerateToken'])->name('thirdPartyTokens.regenerate-token');
    Route::resource('thirdPartyTokens', App\Http\Controllers\ThirdPartyTokensController::class);


    Route::resource('events', App\Http\Controllers\EventsController::class);


    Route::resource('eventAttendees', App\Http\Controllers\EventAttendeesController::class);


    Route::resource('signatories', App\Http\Controllers\SignatoriesController::class);


    Route::resource('serviceAppliedFors', App\Http\Controllers\ServiceAppliedForController::class);


    Route::resource('paymentOrders', App\Http\Controllers\PaymentOrderController::class);


    Route::resource('itemsCosts', App\Http\Controllers\ItemsCostController::class);


    Route::resource('warehouseHeads', App\Http\Controllers\WarehouseHeadController::class);

    Route::get('/warehouse_items/get-searched-materials', [App\Http\Controllers\WarehouseItemsController::class, 'getSearchedMaterials'])->name('warehouseItems.get-searched-materials');
    Route::get('/warehouse_items/get-searched-meters', [App\Http\Controllers\WarehouseItemsController::class, 'getSearchedMeters'])->name('warehouseItems.get-searched-meters');
    Route::get('/warehouse_items/remove-item', [App\Http\Controllers\WarehouseItemsController::class, 'removeItem'])->name('warehouseItems.remove-item');
    Route::resource('warehouseItems', App\Http\Controllers\WarehouseItemsController::class);


    Route::resource('items', App\Http\Controllers\ItemsController::class);


    Route::resource('costCenters', App\Http\Controllers\CostCentersController::class);


    Route::resource('projectCodes', App\Http\Controllers\ProjectCodesController::class);


    Route::resource('zones', App\Http\Controllers\ZonesController::class);


    Route::resource('blocks', App\Http\Controllers\BlocksController::class);


    Route::get('/meter_installations/create-meter-installation/{id}', [MeterInstallationController::class, 'createMeterInstallation'])->name('meterInstallations.create-meter-installation');
    Route::resource('meterInstallations', MeterInstallationController::class);


    Route::resource('notifications', App\Http\Controllers\NotificationsController::class);

    Route::resource('read-and-bill-notices', App\Http\Controllers\ReadAndBillNoticesController::class);
    Route::resource('bill-miscellaneouses', App\Http\Controllers\BillMiscellaneousController::class);
    Route::resource('bill-mirrors', App\Http\Controllers\BillMirrorController::class);
    Route::resource('collection-date-adjustments', App\Http\Controllers\CollectionDateAdjustmentsController::class);
    Route::resource('customer-deposit-interests', App\Http\Controllers\CustomerDepositInterestsController::class);
    Route::resource('customer-deposit-logs', App\Http\Controllers\CustomerDepositLogsController::class);

    Route::get('/bohol_water_service_accounts/upload-file', [App\Http\Controllers\BoholWaterServiceAccountsController::class, 'uploadFile'])->name('boholWaterServiceAccounts.upload-file');
    Route::post('/bohol_water_service_accounts/validate-uploaded-file', [App\Http\Controllers\BoholWaterServiceAccountsController::class, 'validateUploadedFile'])->name('boholWaterServiceAccounts.validate-uploaded-file');
    Route::resource('boholWaterServiceAccounts', App\Http\Controllers\BoholWaterServiceAccountsController::class);
    Route::resource('bohol-water-collections', App\Http\Controllers\BoholWaterCollectionController::class);
    Route::resource('sms-settings', App\Http\Controllers\SmsSettingsController::class);

    Route::resource('readingFromTexts', App\Http\Controllers\ReadingFromTextController::class);
    Route::resource('local-warehouse-heads', App\Http\Controllers\LocalWarehouseHeadController::class);
    Route::resource('local-warehouse-items', App\Http\Controllers\LocalWarehouseItemsController::class);

    Route::get('/material_presets/create-preset/{scid}', [App\Http\Controllers\MaterialPresetsController::class, 'createPreset'])->name('materialPresets.create-preset');
    Route::resource('materialPresets', App\Http\Controllers\MaterialPresetsController::class);

    Route::get('/service_connection_comments/get-comments', [App\Http\Controllers\ServiceConnectionCommentsController::class, 'getComments'])->name('serviceConnectionComments.get-comments');
    Route::resource('serviceConnectionComments', App\Http\Controllers\ServiceConnectionCommentsController::class);
    Route::resource('line-and-metering-services', App\Http\Controllers\LineAndMeteringServicesController::class);

    Route::resource('systemSettings', SystemSettingsController::class);
});