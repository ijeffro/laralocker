<?php

/*
|--------------------------------------------------------------------------
| LaraLocker Routes
|--------------------------------------------------------------------------
|
| This file is where you may override any of the routes that are included
| with LaraLocker.
|
*/


Route::group(['prefix' => 'laralocker'], function () {

    $controller_namespace = "\Ijeffro\Laralocker\Http\Controllers\\";

    // Aggregation
    // Route::post('aggregation', $controller_namespace . 'AggregationController@save')->name('learning_locker.post.aggregation');

    // Client
    Route::get('clients', $controller_namespace . 'ClientController@index')->name('learning_locker.list.clients');
    Route::get('clients/{id}', $controller_namespace . 'ClientController@show')->name('learning_locker.get.client');
    Route::post('clients', $controller_namespace . 'ClientController@save')->name('learning_locker.create.client');
    Route::patch('clients/{id}', $controller_namespace . 'ClientController@update')->name('learning_locker.update.client');
    Route::delete('clients/{id}', $controller_namespace . 'ClientController@destroy')->name('learning_locker.delete.client');

    // Client
    Route::get('clients', $controller_namespace . 'ClientController@index')->name('learning_locker.list.clients');
    Route::get('clients/{id}', $controller_namespace . 'ClientController@show')->name('learning_locker.get.client');
    Route::post('clients', $controller_namespace . 'ClientController@save')->name('learning_locker.create.client');
    Route::patch('clients/{id}', $controller_namespace . 'ClientController@update')->name('learning_locker.update.client');
    Route::delete('clients/{id}', $controller_namespace . 'ClientController@destroy')->name('learning_locker.delete.client');

    // Dashboard
    Route::get('dashboards', $controller_namespace . 'DashboardController@index')->name('learning_locker.list.dashboards');
    Route::get('dashboards/{id}', $controller_namespace . 'DashboardController@show')->name('learning_locker.get.dashboard');
    Route::post('dashboards', $controller_namespace . 'DashboardController@save')->name('learning_locker.create.dashboard');
    Route::patch('dashboards/{id}', $controller_namespace . 'DashboardController@update')->name('learning_locker.update.dashboard');
    Route::delete('dashboards/{id}', $controller_namespace . 'DashboardController@destroy')->name('learning_locker.delete.dashboard');

    // Downloads
    Route::get('downloads', $controller_namespace . 'DownloadController@index')->name('learning_locker.list.downloads');
    Route::get('downloads/{id}', $controller_namespace . 'DownloadController@show')->name('learning_locker.get.download');
    Route::post('downloads', $controller_namespace . 'DownloadController@save')->name('learning_locker.create.download');
    Route::patch('downloads/{id}', $controller_namespace . 'DownloadController@update')->name('learning_locker.update.download');
    Route::delete('downloads/{id}', $controller_namespace . 'DownloadController@destroy')->name('learning_locker.delete.download');

    // Exports
    Route::get('exports', $controller_namespace . 'ExportController@index')->name('learning_locker.list.exports');
    Route::get('exports/{id}', $controller_namespace . 'ExportController@show')->name('learning_locker.get.export');
    Route::post('exports', $controller_namespace . 'ExportController@save')->name('learning_locker.create.export');
    Route::patch('exports/{id}', $controller_namespace . 'ExportController@update')->name('learning_locker.update.export');
    Route::delete('exports/{id}', $controller_namespace . 'ExportController@destroy')->name('learning_locker.delete.export');

    // Journeys
    Route::get('journeys', $controller_namespace . 'JourneyController@index')->name('learning_locker.list.journeys');
    Route::get('journeys/{id}', $controller_namespace . 'JourneyController@show')->name('learning_locker.get.journey');
    Route::post('journeys', $controller_namespace . 'JourneyController@save')->name('learning_locker.create.journey');
    Route::patch('journeys/{id}', $controller_namespace . 'JourneyController@update')->name('learning_locker.update.journey');
    Route::delete('journeys/{id}', $controller_namespace . 'JourneyController@destroy')->name('learning_locker.delete.journey');

    // Journeys Progress
    Route::get('journeyprogress', $controller_namespace . 'JourneyProgressController@index')->name('learning_locker.list.journey.progress');
    Route::get('journeyprogress/{id}', $controller_namespace . 'JourneyProgressController@show')->name('learning_locker.get.journey.progress');
    Route::post('journeyprogress', $controller_namespace . 'JourneyProgressController@save')->name('learning_locker.create.journey.progress');
    Route::patch('journeyprogress/{id}', $controller_namespace . 'JourneyProgressController@update')->name('learning_locker.update.journeyprogress');
    Route::delete('journeyprogress/{id}', $controller_namespace . 'JourneyProgressController@destroy')->name('learning_locker.delete.journey.progress');

    // Organisations
    Route::get('organisations', $controller_namespace . 'OrganisationController@index')->name('learning_locker.list.organisations');
    Route::get('organisations/{id}', $controller_namespace . 'OrganisationController@show')->name('learning_locker.get.organisation');
    Route::post('organisations', $controller_namespace . 'OrganisationController@save')->name('learning_locker.create.organisation');
    Route::patch('organisations/{id}', $controller_namespace . 'OrganisationController@update')->name('learning_locker.update.organisation');
    Route::delete('organisations/{id}', $controller_namespace . 'OrganisationController@destroy')->name('learning_locker.delete.organisation');

    // Personas
    Route::get('personas', $controller_namespace . 'PersonaController@index')->name('learning_locker.list.personas');
    Route::get('personas/{id}', $controller_namespace . 'PersonaController@show')->name('learning_locker.get.persona');
    Route::post('personas', $controller_namespace . 'PersonaController@save')->name('learning_locker.create.persona');
    Route::patch('personas/{id}', $controller_namespace . 'PersonaController@update')->name('learning_locker.update.persona');
    Route::delete('personas/{id}', $controller_namespace . 'PersonaController@destroy')->name('learning_locker.delete.persona');

    // Personas Identifiers
    // TODO

    // Personas Attributes
    // TODO

    // Query
    Route::get('query', $controller_namespace . 'QueryController@index')->name('learning_locker.list.queries');
    Route::get('query/{id}', $controller_namespace . 'QueryController@show')->name('learning_locker.get.query');
    Route::post('query', $controller_namespace . 'QueryController@save')->name('learning_locker.create.query');
    Route::patch('query/{id}', $controller_namespace . 'QueryController@update')->name('learning_locker.update.query');
    Route::delete('query/{id}', $controller_namespace . 'QueryController@destroy')->name('learning_locker.delete.query');

    // Roles
    Route::get('roles', $controller_namespace . 'RoleController@index')->name('learning_locker.list.roles');
    Route::get('roles/{id}', $controller_namespace . 'RoleController@show')->name('learning_locker.get.role');
    Route::post('roles', $controller_namespace . 'RoleController@save')->name('learning_locker.create.role');
    Route::patch('roles/{id}', $controller_namespace . 'RoleController@update')->name('learning_locker.update.role');
    Route::delete('roles/{id}', $controller_namespace . 'RoleController@destroy')->name('learning_locker.delete.role');

    // Statements
    Route::get('statements', $controller_namespace . 'StatementController@index')->name('learning_locker.list.statements');
    Route::get('statements/{id}', $controller_namespace . 'StatementController@show')->name('learning_locker.get.statement');
    Route::post('statements', $controller_namespace . 'StatementController@save')->name('learning_locker.create.statement');
    Route::patch('statements/{id}', $controller_namespace . 'StatementController@update')->name('learning_locker.update.statement');

    // Completion
    Route::post('completion', $controller_namespace . 'CompletionController@save')->name('receive.learning_locker.completion');

    // Statement Deletion
    Route::delete('statement/{id}', $controller_namespace . 'StatementController@destroy')->name('learning_locker.delete.statement');

    // Statement Forwarding
    Route::get('statementforwarding', $controller_namespace . 'StatementForwardingController@index')->name('learning_locker.list.statement.forward');
    Route::get('statementforwarding/{id}', $controller_namespace . 'StatementForwardingController@show')->name('learning_locker.get.statement.forward');
    Route::post('statementforwarding', $controller_namespace . 'StatementForwardingController@save')->name('learning_locker.create.statement.forward');
    Route::patch('statementforwarding/{id}', $controller_namespace . 'StatementForwardingController@update')->name('learning_locker.update.statement.forward');
    Route::delete('statementforwarding/{id}', $controller_namespace . 'StatementForwardingController@destroy')->name('learning_locker.delete.statement.forward');

    // Stores
    Route::get('stores', $controller_namespace . 'StoreController@index')->name('learning_locker.list.stores');
    Route::get('stores/{id}', $controller_namespace . 'StoreController@show')->name('learning_locker.get.store');
    Route::post('stores', $controller_namespace . 'StoreController@save')->name('learning_locker.create.store');
    Route::patch('stores/{id}', $controller_namespace . 'StoreController@update')->name('learning_locker.update.store');
    Route::delete('stores/{id}', $controller_namespace . 'StoreController@destroy')->name('learning_locker.delete.store');

    // Users
    Route::get('users', $controller_namespace . 'UserController@index')->name('learning_locker.list.users');
    Route::get('users/{id}', $controller_namespace . 'UserController@show')->name('learning_locker.get.user');
    Route::post('users', $controller_namespace . 'UserController@save')->name('learning_locker.create.user');
    Route::patch('users/{id}', $controller_namespace . 'UserController@update')->name('learning_locker.update.user');
    Route::delete('users/{id}', $controller_namespace . 'UserController@destroy')->name('learning_locker.delete.user');

    // Visualisations
    Route::get('visualisations', $controller_namespace . 'VisualisationController@index')->name('learning_locker.list.visualisations');
    Route::get('visualisations/{id}', $controller_namespace . 'VisualisationController@show')->name('learning_locker.get.visualisation');
    Route::post('visualisations', $controller_namespace . 'VisualisationController@save')->name('learning_locker.create.visualisation');
    Route::patch('visualisations/{id}', $controller_namespace . 'VisualisationController@update')->name('learning_locker.update.visualisation');
    Route::delete('visualisations/{id}', $controller_namespace . 'VisualisationController@destroy')->name('learning_locker.delete.visualisation');

    // Route::group(['prefix' => 'xapi'], function () {

    //     // Visualisations
    //     Route::get('about', $controller_namespace . 'VisualisationController@index')->name('learning_locker.list.visualisations');
    //     Route::get('visualisations/{id}', $controller_namespace . 'VisualisationController@show')->name('learning_locker.get.visualisation');
    //     Route::post('visualisations', $controller_namespace . 'VisualisationController@save')->name('learning_locker.create.visualisation');
    //     Route::patch('visualisations/{id}', $controller_namespace . 'VisualisationController@update')->name('learning_locker.update.visualisation');
    //     Route::delete('visualisations/{id}', $controller_namespace . 'VisualisationController@destroy')->name('learning_locker.delete.visualisation');

    // });
});
