<?php


namespace App\Http\Controllers\App;


use App\Helpers\Response;
use App\Http\Controllers\Controller;
use App\Models\AccountApplication;
use Carbon\Carbon;
use Cartalyst\Sentinel\Laravel\Facades\Sentinel;
use Laracasts\Flash\Flash;

class AccountApplicationController extends Controller {
  public function create() {
    $response = new Response();
    if (!Sentinel::hasAccess('reports')) { //TODO add real permission
      $response->setStatusCode(Response::HTTP_BAD_REQUEST, false);
      $response->setMessage('Unauthorised');
      return $response->sendApiResponse();
    }

    $data = request()->all();

    $application = new AccountApplication();
    $application->name = $data['name'];
    $application->m_no = $data['m_no'];
    $application->address = $data['address'];
    $application->father_name = $data['father_name'];
    $application->aadhar_no = $data['aadhar_no'];
    $application->pan_no = $data['pan_no'];
    $application->landholding = $data['landholding'];
    $application->crops_grown = $data['crops_grown'];
    $application->created_at = Carbon::now();

    $application->save();

  }
}