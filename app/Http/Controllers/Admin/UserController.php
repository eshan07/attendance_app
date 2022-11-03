<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserRequest;
use App\Http\Traits\Helpers;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    use Helpers;
    public function __construct()
    {
        $this->middleware('permission:user-list|user-create|user-edit|user-delete', ['only' => ['index', 'store']]);
        $this->middleware('permission:user-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:user-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:user-delete', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $month_year = \request()->month;
        $users = User::select('id', 'name', 'email', 'avatar', 'created_at')->whereNull('is_admin');
         if ($month_year){
             $date_m_y = explode('/',$month_year);
             $monthNumber = date('m', strtotime($date_m_y[0]));
             $year = date('Y', strtotime($date_m_y[1]));
             $users = $users->withCount(['present'=>function ($q) use ($monthNumber){
                 return $q->whereMonth('attendance_date', '=', $monthNumber);
             }, 'absent', 'leave'])->paginate(25);
         }else{
             $month_year = date('F').'/'.date('Y');
             $users = $users->withCount(['present', 'absent', 'leave'])->withSum('present as total_working_hours', 'working_hours')->paginate(25);
         }
        $total_working_days = $this->getWorkingDays();
        return view('layouts.admin.user.index', compact('users', 'total_working_days', 'month_year'));

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $roles = Role::pluck('name','name')->all();
        return view('layouts.admin.user.create', compact('roles'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(UserRequest $request)
    {
        $validated = $request->validated();
        if (array_key_exists('avatar', $validated)) {
           $validated['avatar'] = $this->imageUpload($validated['avatar']);
        }
        $validated['password'] = bcrypt($validated['password']);
        $user = User::create($validated);
        $user->assignRole($validated['role']);
        $total_working_days = $this->getWorkingDays();
        $month_year = date('F').'/'.date('Y');
        $users = User::select('id', 'name', 'email', 'avatar', 'created_at')->whereNull('is_admin')->paginate(25);
        return view('layouts.admin.user.index', compact('users', 'total_working_days', 'month_year'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {
            $total_working_days = $this->getWorkingDays();
            $user = User::select('id', 'name', 'email', 'avatar', 'created_at')->withCount(['present', 'absent', 'leave'])->withSum('present as total_working_hours', 'working_hours')->findOrFail(decrypt($id));
        }catch (\Exception $error){
            return back()->with('error', 'Something went wrong!');
        }
        return view('layouts.admin.user.show', compact('user', 'total_working_days'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $user = $this->decryptFind(User::class,$id);
        return view('layouts.admin.user.edit', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $user = $this->decryptFind(User::class,$id);
        $validator = Validator::make($request->all(), [
            'name' => ['required','string','max:100'],
            'email' => ['required','email','unique:users,email,'.$user->id],
            'password' => ['nullable', 'string', Password::min(8)],
            'avatar' => ['nullable', 'image', 'mimes:jpeg,jpg,png,webp'],
            'role' => ['required']
    ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        $user->name = $validator->valid()['name'];
        $user->email = $validator->valid()['email'];
        $user->password = is_null($validator->valid()['password'])?$user->password:bcrypt($validator->valid()['password']);
        $user->is_admin = $validator->valid()['role']=='admin' ? true :null;
        if (array_key_exists('avatar', $validator->valid())) {
            $this->removeDirectoryFile($user->avatar);
            $user->avatar = $this->imageUpload($validator->valid()['avatar']);
        }
        if ($user->update()){
            return redirect()->route('admin.users.show', encrypt($user->id))->with(['success' => 'User updated!', $user]);
        }
        return redirect()->back()->with('error', 'User unable to update!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
       $user = $this->decryptFind(User::class, $id);
       return $this->deleteItem($user, 'User');
    }
}
