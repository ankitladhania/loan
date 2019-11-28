<?php

Route::prefix('accounts')->middleware(['auth', 'sentinel', 'branch'])->group(function() {
  Route::post('/', 'AccountApplicationController@create');
});