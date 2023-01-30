<?php

namespace App\Http\Controllers;

use App\Models\Accounts;
use App\Models\LoginHistory;
use App\Models\Role;
use App\Models\User;
use App\Models\UserDetail;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    function login()
    {
        return view("users.login");
    }

    function dashboard()
    {
        $info = Session::get('isLogin');
        if ($info->role_id == 4)
            return redirect(route('teacher_classes'));
        else
            return view("users.home");
    }

    function login_submitted(Request $request)
    {
        try {
            $data = $request->all();
            $request->validate([
                'email' => 'email',
                'password' => 'required',
            ]);
            $email = isset($data['email']) ? $data['email'] : '';
            $password = isset($data['password']) ? $data['password'] : '';
            $password = crypt($password, md5($password));
            //echo $password;exit;
            $info = DB::table("users as u")
                ->select("d.user_id", "u.company_id", "u.email", "u.status", "u.phone_number", "u.role_id", "d.first_name", "d.last_name", "d.gender", "d.photo", "u.is_login")
                ->join("user_details as d", "d.user_id", "u.id")
                ->where("u.email", "=", $email)
                ->where("u.password", "=", $password)
                ->first();
            if (empty($info) && !isset($info->user_id)) {
                return redirect(route("login"))->with("error", "Invalid email or password");
            }

            if (isset($info->user_id) && ($info->status != 1)) {
                return redirect(route("login"))->with("error", "Your account is deactivated please contact customer support");
            }
            $permissions = DB::table("role_role_permissions as rp")
                ->select("route")
                ->join("role_permissions as p", "p.id", "=", "rp.role_permissions_id")
                ->where("rp.role_id", "=", $info->role_id)
                ->pluck('route')
                ->toArray();
            $l_history = new LoginHistory();
            $l_history->user_id = $info->user_id;
            $l_history->login_time = date("Y-m-d h:m:s");
            $l_history->save();

            $info->allowed_routes = $permissions;
            Session::put('isLogin', $info);
            $redirect = '';
            switch ($info->role_id) {
                case 1:
                    $redirect = route('dashboard');
                    break;
                case 4:
                    $redirect = route('teacher_classes');
                    break;
                default:
                    $redirect = route('dashboard');
            }
            return redirect($redirect);
        } catch (Exception $exception) {
            return redirect(route('login'))->with('error', $exception->getMessage());
        }
    }

    function users(Request $request)
    {
        $data = $request->all();
        $data['roles'] = Role::query()
            ->where("status", "=", "1")
            ->get();
        //-----------------------------------------------------------------------
        $users_qry = DB::table("users as u")
            ->select("u.id", "u.email", "u.status", "u.role_id", "roles.role_name", "d.user_id", "u.phone_number", "d.first_name", "d.last_name", "u.is_login")
            ->join('roles', 'u.role_id', '=', 'roles.id')
            ->join('user_details as d', 'd.user_id', '=', 'u.id');

        if (isset($data['name']) and !empty($data['name'])) {
            $value = $data['name'];
            $users_qry->where(function ($query2) use ($value) {
                $query2->where('d.first_name', 'LIKE', '%' . $value . '%')
                    ->orWhere('d.last_name', 'LIKE', '%' . $value . '%');
            });
        }

        if ((isset($data['date_from']) and !empty($data['date_from'])) and (isset($data['date_to']) and !empty($data['date_from']))) {
            $from = date('Y-m-d', strtotime($data['date_from']));
            $to = date('Y-m-d', strtotime($data['date_to']));
            $users_qry->whereDate('u.created_at', '>=', $from);
            $users_qry->whereDate('u.created_at', '<=', $to);
        }

        if (isset($data['role_id']) and !empty($data['role_id'])) {
            $role_id = $data['role_id'];
            $data['role_id'] = $role_id;
            $users_qry->where('u.role_id', '=', $role_id);
        }

        $users_qry->orderBy("u.id", "DESC");
        $data['users'] = $users_qry->get();

        //-----------------------------------------------------------------------
        // echo "<pre>";
        // print_r($data['users']);exit;
        //->paginate(20);
        return view("users.all_users", $data);
    }

    function change_user_status(Request $request)
    {
        $id = $request->user_id;
        $status = $request->status;
        $user = User::select("*")
            ->where("id", "=", $id)
            ->first();

        if ($status == '0' || $status == '1' || $status == '2') {
            $user->status = $status;
            $user->save();
            if ($status == 2) {
                return redirect(route('users'))->with('success', 'User is Deleted successfully');
            } else if ($status == 1) {
                return redirect(route('users'))->with('success', 'User is Activated successfully');
            } else if ($status == 0) {
                // echo $status.' aaaaaaaaa';exit;
                return redirect(route('users'))->with('success', 'User is Deactivated Successfully');
            }
        }
        //return redirect(route('users'))->with('error', 'oops..! something went wrong');
    }

    function all_invites()
    {
        return view("users.all_invites");
    }

    function add_user()
    {
        $data['title'] = 'Add User';
        $data['roles'] = $this->get_role();
        $data['countries'] = DB::table("country")->select("id", "country_name", "code")->get();
        return view('users.add-user', $data);
    }

    function get_role()
    {
        return Role::query()->select('id', 'role_name')
            ->where('status', '=', '1')
            ->get();
    }

    function user_submitted(Request $request)
    {
        try {
            $data = $request->all();
            $request->validate([
                'first_name' => 'required',
                'last_name' => 'required',
                'gender' => 'required',
                'role_id' => 'required',
                'phone_number' => 'required|unique:users',
                'email' => 'required|email|unique:users',
                'password' => 'required_with:password_confirmation|same:password_confirmation',
            ]);
            //---------------------------------------------------------------------------------------------
            $user = new User();
            $password = crypt($data['password'], md5($data['password']));
            $user->email = $data['email'];
            $user->phone_number = $data['phone_number'];
            $user->role_id = $data['role_id'];
            $user->password = $password;
            $user->status = '1';
            $user->save();
            //---------------------------------------------------------------------------------------------
            $profile = new UserDetail();
            $profile->user_id = $user->id;
            $profile->first_name = $data['first_name'];
            $profile->last_name = $data['last_name'];
            $profile->gender = $data['gender'];
            $profile->currency = isset($data['currency']) ? $data['currency'] : '';
            $profile->country_id = isset($data['country']) ? $data['country'] : '';
            if ($profile->save()) {
                $account = new Accounts();
                $account->user_id = $user->id;
            }
            //---------------------------------------------------------------------------------------------
            return redirect(route('users'))->with('success', "User added successfully..!");
        } catch (Exception $exception) {
            return redirect(route('add_user'))->with('error', $exception->getMessage());
        }
    }

    function edit_user(Request $request)
    {
        $data = $request->all();
        $data['title'] = 'Update User';
        $user_id = isset($data['user_id']) ? $data['user_id'] : '';
        $data['user'] = User::query()->select('id', 'role_id', 'email', 'phone_number')
            ->where('status', '!=', '2')
            ->where('id', '=', $user_id)
            ->first();
        if ($data['user']) {
            $data['user_types'] = $this->get_role();
            $data['countries'] = DB::table("countries as c")
                ->select("c.id", "c.country_name", "c.code")
                ->get();
            $data['detail'] = UserDetail::query()
                ->select('first_name', 'last_name', 'gender', 'country_id', 'currency')
                ->where('user_id', '=', $user_id)
                ->first();
            return view('users.edit_user', $data);
        }
        return redirect()->back()->with('error', 'oops.. something went wrong.');
    }

    function edit_user_submitted(Request $request)
    {
        $data = $request->all();
        $user_id = isset($data['user_id']) ? $data['user_id'] : '';
        $request->validate([
            'first_name' => 'required',
            'last_name' => 'required',
            'gender' => 'required',
            'user_type' => 'required',
        ]);
        if ($user_id != '') {
            $user = User::query()->select("*")
                ->where("id", "=", $user_id)
                ->first();
            $details = UserDetail::query()->select("*")
                ->where("user_id", "=", $user_id)
                ->first();
            if ($user) {
                $user->role_id = $data['user_type'];
                $user->status = '1';
                $details->first_name = $data['first_name'];
                $details->last_name = $data['last_name'];
                $details->gender = $data['gender'];
                if ($user->save() && $user_id != '') {
                    $details->user_id = $user->id;
                    $details->save();
                    return redirect(route('users'))->with('success', 'User updated successfully');
                }
            }
        }
        return redirect(route('users'))->with("error", 'oops..! something went wrong.');
    }

    function login_history(Request $request)
    {
        $data = $request->all();
        $users_qry = DB::table("user_login_history as h")
            ->select("h.user_id", "u.email", "u.phone_number", "u.is_login", "d.first_name", "d.last_name", "r.role_name", "h.login_time", "h.logout_time")
            ->join("users as u", "u.id", "h.user_id")
            ->join("user_details as d", "d.user_id", "h.user_id")
            ->join("roles as r", "r.id", "u.role_id")
            ->groupBy("h.user_id")
            ->orderBy("h.id", "DESC");
        //  echo "hereeeeee"

        if (isset($data['name']) and !empty($data['name'])) {
            $value = $data['name'];
            $users_qry->where(function ($query2) use ($value) {
                $query2->where('d.first_name', 'LIKE', '%' . $value . '%')
                    ->orWhere('d.last_name', 'LIKE', '%' . $value . '%');
            });

        }

        if ((isset($data['date_from']) and !empty($data['date_from'])) and (isset($data['date_to']) and !empty($data['date_from']))) {
            $from = date('Y-m-d', strtotime($data['date_from']));
            $to = date('Y-m-d', strtotime($data['date_to']));
            $users_qry->whereDate('h.created_at', '>=', $from);
            $users_qry->whereDate('h.created_at', '<=', $to);
        }
        $data['history'] = $users_qry->get();
        // print_r($data['history']);exit;
        return view("users.login_history", $data);
    }

    function change_password()
    {
        $info = Session::get('isLogin');
        if ($info->role_id == 4)
            return view("users.teacher_change_password");
        else
            return view("users.change_password");
    }

    function profile()
    {
        $data['info'] = $info = Session::get('isLogin');

        if ($info->role_id == 4)
            return view("users.teacher-profile", $data);
        else
            return view("users.profile", $data);
    }


    function forgot_password(Request $request)
    {
        $email = $request->email;
        $user = User::select("id", "password", 'verification_code')
            ->where(['email' => $email])
            ->first();
        // print_r($user);exit;
        if (!empty($user)) {
            $code = mt_rand(100000, 999999);
            $user->verification_code = $code;
            $user->save();
            $details = UserDetail::query()
                ->select('id', 'first_name', 'last_name')
                ->where('user_id', '=', $user['id'])
                ->first();
            //print_r($details);exit;
            $token = base64_encode($email . ':' . $code);
            $info = array(
                'first_name' => $details->first_name,
                'last_name' => $details->last_name,
                'link' => route('reset_password', ['token' => $token])
            );

            // Mail::to($email)->send(new ForgotPassword($info));

            $html = view('Emails.forgot_password', ['info' => $info])->render();

            //sendEmailViaSendGrid($email, "Forgot password (ThreadFoot)", $html);
            //mail($email, "Forgot password (Online teaching software)", $html);
            mail('sajidanwar2020@gmail.com', "Forgot password (Online teaching software)", 'HIHIHI');
            //Send Email
            return 1;
        }
        return "No such email are found.";
    }

    function reset_password(Request $request)
    {
        $data = $request->all();
        $info = base64_decode($data['token']);
        $info = explode(":", $info);
        $info['token'] = $data['token'];
        // echo "<pre>";print_r($info);exit;
        $email = $info[0];
        $code = $info[1];
        //echo $email.' '.$code;exit;
        if ($email != '' && $code != '') {
            $user = User::select("id", "password", 'verification_code')
                ->where(['email' => $email, 'verification_code' => $code])
                ->first();
            if (empty($user)) {
                return redirect(route('login'))->with('error', 'Sorry your password reset link is expired');
            }
        }
        return view('users.reset_password', $info);
    }

    function reset_update_password(Request $request)
    {
        $this->validate($request, [
            'password' => 'required|confirmed|min:6',
        ]);
        $data = $request->all();
        $info = base64_decode($data['token']);
        $info = explode(":", $info);
        // echo "<pre>";
        //print_r($info);
        //exit;
        //exit;
        $email = $info[0];
        $code = $info[1];
        //echo $email.' '.$code;exit;
        if ($email != '' && $code != '') {
            $user = User::select("id", "password", 'verification_code')
                ->where(['email' => $email, 'verification_code' => $code])
                ->first();
            // print_r($user);exit;
            if (empty($user)) {
                return redirect(route('reset_password', ['token' => $data['token']]))->with('error', 'Sorry your password reset link is expired');
            } else {
                $password = crypt($data['password'], md5($data['password']));
                $user->password = $password;
                $user->verification_code = '';
                if ($user->save()) {
                    return redirect(route('login'))->with('success', 'Your password has been changed successfully');
                } else {
                    return redirect(route('reset_password', ['token' => $data['token']]))->with('error', 'Opps something went wrong');
                }
            }
        } else {
            return redirect(route('reset_password', ['token' => $data['token']]));
        }
    }


    function profile_submitted(Request $request)
    {
        try {
            $data = $request->all();
            $info = Session::get("isLogin");
            $user = User::where("id", "=", $info->user_id)
                ->first();
            $user->phone_number = $data['phone_number'];
            $user->save();

            $profile = UserDetail::where("user_id", "=", $info->user_id)
                ->first();

            $file = $request->file("photo");
            if ($file) {
                Storage::disk('uploads')->delete('users/' . $profile->photo);
                $file_name = "user" . $info->user_id . "_" . time() . "." . $file->getClientOriginalExtension();
                $file->storeAs("users", $file_name, 'uploads');
                $profile->photo = $file_name;
            }

            $profile->first_name = $data['first_name'];
            $profile->last_name = $data['last_name'];
            $profile->save();

            $info = DB::table("users as u")
                ->select("d.user_id", "u.company_id", "u.email", "u.phone_number", "u.role_id", "d.first_name", "d.last_name", "d.gender", "d.photo", "u.is_login")
                ->join("user_details as d", "d.user_id", "u.id")
                ->where('u.id', "=", $profile->user_id)
                ->first();


            $permissions = DB::table("role_role_permissions as rp")
                ->select("route")
                ->join("role_permissions as p", "p.id", "=", "rp.role_permissions_id")
                ->where("rp.role_id", "=", $info->role_id)
                ->pluck('route')
                ->toArray();
            $info->allowed_routes = $permissions;
            Session::put('isLogin', $info);
            return redirect(route('profile'))->with('info', "Updated profile");
        } catch (Exception $exception) {
            return redirect(route('profile'))->with('error', $exception->getMessage());
        }
    }

    function update_password(Request $request)
    {
        try {
            $this->validate($request, [
                'old_pass' => 'required',
                'password' => 'required|confirmed|min:6',
            ]);
            $data = $request->all();
            $info = Session::get("isLogin");
            //print_r($info);exit;
            $user = User::select("id", "password")
                ->where(['id' => $info->user_id])
                ->first();
            $old_pass = crypt($data['old_pass'], md5($data['old_pass']));
            if ($user->password != $old_pass) {
                return redirect(route('change_password'))->with('error', 'The old password you have entered is incorrect');
            }
            $password = crypt($data['password'], md5($data['password']));
            $user->password = $password;
            if ($user->save()) {
                return redirect(route('change_password'))->with('success', 'Your password has been changed successfully');
            } else {
                return redirect(route('change_password'))->with('error', 'Opps something went wrong');
            }
        } catch (Exception $exception) {
            return redirect(route('change_password'))->with('error', $exception->getMessage());
        }
    }

    function search_parent(Request $request)
    {
        $data = $request->all();
        $users_qry = User::query()
            ->join('user_details', 'user_details.user_id', '=', 'users.id');
        if (isset($data['search']) and !empty($data['search'])) {
            $value = $data['search'];
            $users_qry->where(function ($query2) use ($value) {
                $query2->where('first_name', 'LIKE', '%' . $value . '%')
                    ->orWhere('last_name', 'LIKE', '%' . $value . '%')
                    ->orWhere('email', 'LIKE', '%' . $value . '%')
                    ->orWhere('phone_number', 'LIKE', '%' . $value . '%');
            });
        }

        $users_qry->orderBy("users.id", "DESC");
        $users = $users_qry->get();

        $output = '<ul class="studnet_ul">';

        if ($users) {
            foreach ($users as $user) {
                $output .= '<li data-userid="' . $user->user_id . '">' . ucwords($user->first_name) . '</li>';
            }
        } else {
            $output .= '<li> result not Found</li>';
        }

        $output .= '</ul>';
        echo $output;
        exit;
    }

    function logout()
    {
        Session::flush();
        return redirect(route('login'));
    }
}
