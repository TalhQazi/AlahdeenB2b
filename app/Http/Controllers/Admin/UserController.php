<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserCollection;
use Illuminate\Http\Request;
use App\Models\User;
use Spatie\Permission\Models\Role;
use App\Traits\PaginatorTrait;
use Illuminate\Support\Facades\Session;

class UserController extends Controller
{

    private $noOfItems;

    public function __construct()
    {
        // $this->authorizeResource(User::class);
        $this->noOfItems = config('pagination.users', config('pagination.default'));
    }


    /**
    * Display a listing of the resource.
    *
    * @return \Illuminate\Http\Response
    */
    public function index(User $user, Request $request)
    {

       $this->authorize('viewAny', User::class);
       $users = $user::withTrashed();

       if($request->input('keywords')) {
           $searchParam = $request->input('keywords');
           $users = $users->where('name', 'like', '%'.$searchParam.'%')->orWhere('id', 'like', '%'.$searchParam.'%');

       }

       $users = $users->paginate($this->noOfItems);
       $users = (new UserCollection($users))->response()->getData();

       if($request->ajax()) {
            return view('components.users.index.load', [
               'users' => $users,
               'paginator' => PaginatorTrait::getPaginator($request, $users)
            ])->render();

       } else {

            return view('pages.users.index')->with([
               'users' => $users,
               'roles' => Role::all(),
               'paginator' => PaginatorTrait::getPaginator($request, $users)
            ]);
       }

    }

    public function updateRole(User $user, Request $request)
    {
        $this->authorize('updateRole', User::class);
        if($user->syncRoles($request->input('role'))) {
            Session::flash('message', __('User role has been updated successfully'));
            Session::flash('alert-class', 'alert-success');
        } else {
            Session::flash('message', __('Unable to update user role'));
            Session::flash('alert-class', 'alert-error');
        }
        return redirect()->route('admin.users.home');
    }
}
