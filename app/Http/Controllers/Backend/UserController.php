<?php

namespace App\Http\Controllers\Backend;

use App\Helpers\UUIDGenerate;
use App\User;
use Exception;
use App\Wallet;
use Carbon\Carbon;
use Jenssegers\Agent\Agent;
use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\UserEditRequest;
use App\Http\Requests\UserStoreRequest;

class UserController extends Controller
{
    public function index()
    {
        return view('backend.user.index');
    }
    public function create()
    {
        return view('backend.user.create');
    }
    public function store(UserStoreRequest $request)
    {
        DB::beginTransaction();
        try {
            $user = new User();
            $user->name = $request->name;
            $user->email = $request->email;
            $user->phone = $request->phone;
            $user->password = Hash::make($request->password);
            $user->save();
            ## create user wallet account
            // $check = Wallet::where('user_id',$user->id)->first();
            // if($check){

            // }
            ## use  firstOrCreate is ready made above method is mean => in the wallet table one user one account
            Wallet::firstOrCreate(
                ['user_id' => $user->id], // check
                [
                    'account_number' => UUIDGenerate::accountNumber(),
                    'amount' => 0
                ]
            );
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('warning', 'Something wrong');
        }
        DB::commit();
        return redirect()->back()->with('create', 'Create successfully!');
    }
    public function edit(Request $request, $id)
    {
        $user = User::findOrFail($id);
        return view('backend.user.edit', compact('user'));
    }
    public function update(UserEditRequest $request, $id)
    {
        DB::beginTransaction();
        try {
            $user = User::findOrFail($id);
            $user->name = $request->name;
            $user->email = $request->email;
            $user->phone = $request->phone;
            ##
            //   $user->password = $request->password ? Hash::make($request->password) : $user->password;
            if ($request->password) {
                $user->password = Hash::make($request->password);
            }
            $user->update();

            ## Check cover up if user account has but no wallet account , it create wallet account if user account update do
            Wallet::firstOrCreate(
                ['user_id' => $user->id], // check wallet acc exist in wallet table this user id
                [
                    'account_number' => UUIDGenerate::accountNumber(),
                     'amount' => 0
                ]
            );
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('warning', 'Something wrong');
        }
        DB::commit();

        return redirect()->back()->with('update', 'Update successfully!');
    }
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();
        return 'success';
    }
    ## Datatable Ajax
    public function ssd()
    {
        $data = User::query();
        ##edit
        return Datatables::of($data)
            ->editColumn('created_at', function ($each) {
                return Carbon::parse($each->created_at)->format('Y-m-d H:m:s');
            })
            ->editColumn('updated_at', function ($each) {
                return Carbon::parse($each->updated_at)->format('Y-m-d H:m:s');
            })
            ->editColumn('user_agent', function ($each) {
                $agent = new Agent();
                $agent->setUserAgent($each->user_agent);
                $device = $agent->device();
                $platform = $agent->platform();
                $browser = $agent->browser();
                if ($each->user_agent) {
                    return '<table class="table table-bordered">
                    <tbody>
                      <tr>
                         <td>Device</td>
                         <td>' . $device . '</td>
                      </tr>
                      <tr>
                         <td>Platform</td>
                         <td>' . $platform . '</td>
                      </tr>
                      <tr>
                         <td>Browser</td>
                         <td>' . $browser . '</td>
                      </tr>
                    </tbody>
                  </table>';
                } else {
                    return '-';
                }
            })
            ->addColumn('action', function ($each) {
                $edit_icon = '<a href="' . route('admin.user.edit', $each->id) . '" class="text-success"><i class="fas fa-user-edit"></i></a>';
                $delete_icon =  '<a href="#" data-id="' . $each->id . '" class="text-danger delete"><i class="fas fa-trash-alt"></i></a>';
                return "<div class='action-icon'>" . $edit_icon . $delete_icon . "</div>";
            })
            ->rawColumns(['user_agent', 'action']) ## this is inclued  html code in datatable //  advertisement
            ->make(true);
        ## simple get data
        //return Datatables::of($data)->make(true);

        ## edit from database data to ui show
        // return Datatables::of($data)->editColumn('name',function($each){
        //     return $each->name.' -- ha ha';
        // })->make(true);
    }
}
