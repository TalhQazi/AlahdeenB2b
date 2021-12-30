<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    protected $accountTypes;

    public function __construct()
    {
        $this->accountTypes = config('account_types');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {

        if($request->session()->get('user_type') == "seller") {
          $request->session()->put('user_type', 'buyer');
          return redirect()->route('dashboard');
        } else {
          if(Auth::user()->hasRole(['individual', 'business', 'corporate', 'warehouse-owner'])) {
            $request->session()->put('user_type', 'seller');
            return redirect()->route('dashboard');
          } else {
            return view('auth.become-seller');
          }

        }
    }

    public function store(Request $request)
    {
      $input = $request->input();

      Validator::make($input, [
        'account_type' => ['required', 'integer', Rule::in(array_flip($this->accountTypes))],
        'address' => ['required_if:account_type,3', 'string', 'max:255'],
        'industry' => ['required_if:account_type,2', 'string', 'max:255'],
        'job_freelance' => ['required_if:account_type,3', 'string', 'max:255'],
      ],
      [
          // custom validation messages
          'account_type' => __('Please select appropriate Account Type from the dropdown options.'),
      ],
      [
          // custom attribute values
          'account_type' => 'account type',
      ])->validate();

      DB::transaction(function () use ($input, $request) {
        $user = Auth::user();

        $user->address = $input['address'] ?? null;
        $user->industry = $input['industry'] ?? null;
        $user->job_freelance = $input['job_freelance'] ?? null;
        $user->start_as = $input['start_as'] ?? null;
        $user->save();

        $user->syncRoles($this->accountTypes[$input['account_type']]);
        $request->session()->put('user_type', 'seller');

      });

      return redirect()->route('dashboard');
    }

}
