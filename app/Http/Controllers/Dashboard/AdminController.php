<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Dashboard\OrdersModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\Datatables\Datatables;
use App\Repositories\UsersInterface;

class AdminController extends Controller
{
    public $user;
    protected $usersIntObj;
    public function __construct(UsersInterface $usersInterfaceObject)
    {
        $this->usersIntObj = $usersInterfaceObject;
        $this->middleware(function ($request, $next) {
            $this->user = Auth::user();
            return $next($request);
        });
    }
    /**
     * this is following is ajax called of user
     */
    public function userLists(Request $request){
        if (is_null($this->user) || !Auth::user()->hasRole('admin')) {
            abort(403, 'Sorry !! You are Unauthorized to view any admin !');
        }
        if ($request->ajax()) {
            //$data = User::orderBy('created_at', 'desc')->get();
            $data = $this->usersIntObj->all();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('name', function($user){
                    return $user->name;
                })
                ->addColumn('email', function($user){
                    return $user->email;
                })
                ->addColumn('premium', function($user){
                    $checkIfUserIsPremiumOrNot = OrdersModel::checkIfUserHasPremiumAccess($user->id);
                    return $checkIfUserIsPremiumOrNot === false ? "0" : $checkIfUserIsPremiumOrNot;
                })
                ->addColumn('verified', function($user){
                    return $user->email_verified_at == null ? "0" : "1";
                })

                ->addColumn('nip', function($user){
                    return $user->nip;
                })
                ->addColumn('roles', function ($user) {
                    $role_name = '';
                    foreach ($user->getRoleNames() as $role) {
                        $role_name .= $role . ', ';
                    }
                    return rtrim($role_name, ", ");
                })
                ->addColumn('last_login', function($user){
                    return $user->last_login_at;
                })
                ->addColumn('action', function ($user) {
                    $btn = '<a class="btn btn-info text-white" href=' . route("change.password.form", $user->id) . '>Edit</a>';
                    return $btn;
                })
                ->rawColumns(['action', 'premium', 'verified', 'last_login'])
                
                ->make(true);
        } else {
            return view('admin.admin-default');
        }
    }
    /**
     * change password from admin panel 
     */
    public function changePassword(Request $request){
        if (is_null($this->user) || !Auth::user()->hasRole('admin')) {
            abort(403, 'Sorry !! You are Unauthorized to edit user password !');
        }
        $loadUser = $this->usersIntObj->findOrFail($request->user_id);
        return view('admin.change-password', compact('loadUser'));
    }
    /**
     * update password
     */
    public function updateUserInfo(Request $request){
        if (is_null($this->user) || !Auth::user()->hasRole('admin')) {
            abort(403, 'Sorry !! You are Unauthorized to edit user password !');
        }
        $request->validate(
            [
                'name' => 'required|max:255',
                'password' => 'required|min:6|confirmed',
            ]
        );
        try {
            $userUpdate = $this->usersIntObj->updateUser($request->all());
            if ($userUpdate) {
                return redirect()->route('home')->with(['message' => __('passwords.reset')]);
            } else {
                $request->session()->flash('error', 'User not found');
                return back();
            }
        } catch (\Exception $e) {
            $request->session()->flash('error', $e->getMessage());
            return back();
        }
    }
}
