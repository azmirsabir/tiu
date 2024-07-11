<?php

namespace App\Http\Controllers\Main;

use App\Helper;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Traits\CSVExporter;
use App\Traits\CSVReader;
use App\Utilities\egsrequeststructure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class userController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    use CSVExporter, CSVReader;
    public function index()
    {
        return view('users.user', ['roles' => DB::table("users")->select('type', DB::raw('count(*) as total'))->groupBy('type')->get(),'usersDataTableLength'=>session()->get('usersDataTableLength')]);
    }
    public function get_users(Request $request)
    {
        $unique_id = Helper::generate_uuid();
        try {
            $start = isset($request->start) ? $request->get("start") : "";
            $row_per_page = isset($request->length) ? $request->get("length") : "";
//        $columns=isset($request->columns)?$request->get('columns'):"";
            session()->put('usersDataTableLength', $row_per_page);
            $search_arr = isset($request->search) ? $request->get('search') : "";
            $searchValue = isset($search_arr['value']) ? $search_arr['value'] : "";

            $recordsTotal = DB::table("users")->count();

            $query = User::with("roles")
                ->select('users.id', 'users.status', 'users.type', 'users.user_name')
                ->where(function ($query) use ($request, $searchValue) {
                    $query->whereRaw('LOWER(users.user_name) like?', ['%' . strtolower($searchValue) . '%']);
                })
//                ->where('users.type','<>','normal')
                ->where('users.type', 'like', '%' . $request->type . '%');

            $recordsFiltered = 0;
            foreach ($query->groupBy('users.id','users.status','users.type','users.user_name')->get() as $key=>$value){
                $recordsFiltered = $recordsFiltered + 1;
            }

            $res = $query
                ->skip($start)
                ->take($row_per_page)
//                ->orderBy('created_at','desc')
                ->groupBy('users.id','users.status','users.type','users.user_name')
                ->get();

            $data = ['user_data' => $res];

            $r = array(
                "draw" => intval($request->input('draw')),
                "recordsTotal" => $recordsTotal,
                "recordsFiltered" => $recordsFiltered,
                "data" => $data
            );
            return $r;


        } catch (\Exception $e) {
            Log::channel('custom_log')->error($unique_id . " : " . __FUNCTION__ . " : " . __CLASS__ . ' : ' . $e->getMessage());
            return array(
                "draw" => intval($request->input('draw')),
                "recordsTotal" => 0,
                "recordsFiltered" => 0,
                "data" => []
            );
        }
    }
    public function get_modules()
    {
        return ['modules' => DB::table('roles')->get()];
    }
    function get_user_by_id($id)
    {
        return [
            'user' => User::with("roles")->select('id', 'status', 'type', 'user_name')->Where('id', '=', $id)->get()
        ];
    }
    public function store(Request $request)
    {
        try {
            if ($request->has('user_id')) {
                //update
                $update = DB::table('users')->where('id', '=', $request->user_id)->update([
                    'user_name'=>strtolower($request->username),
                    'type' => $request->type
                ]);
                if ($update) {
                    DB::table("role_user")->where('user_id','=',$request->user_id)->delete();
                    if($request->role){
                        foreach ($request->role as $key=>$role)
                        {
                            DB::table('role_user')->insert([
                                'user_id'=>$request->user_id,
                                'role_id'=>$role,
                            ]);
                        }
                    }
                    return ['status' => 'success','message'=>'Successfully done.'];
                }
                Log::error( __FUNCTION__ . " : " . __CLASS__ . ' : Update failed');
                return ['status' => 'error','message'=>'Failed'];
            }

            //insert
            $query=DB::table('users')->where('user_name',strtolower($request->username));
            if($query->exists()){
                $user_id = $query->update([
                    'user_name' => strtolower($request->username),
                    'type' => $request->type
                ]);
                return ['status' => 'success','message'=>'Successfully done.'];
            }
            $user_id = DB::table('users')->insertGetId([
                'user_name' => strtolower($request->username),
                'password' => Hash::make($request->password),
                'status' => 1,
                'type' => $request->type
            ]);
            if($request->role){
                foreach ($request->role as $key=>$role_id){
                    DB::table('role_user')->insert([
                        'user_id'=>$user_id,
                        'role_id'=>$role_id,
                    ]);
                }
            }
            return ['status' => 'success','message'=>'Successfully done.'];

        } catch (\Exception $e) {
            Log::channel('custom_log')->error(__FUNCTION__ . " : " . __CLASS__ . ' : ' . $e->getMessage());
            return ['status' => 'error','message'=>$e->getMessage()];
        }

    }
    public function update(Request $request, $user_id)
    {
        try {
            $update = DB::table('users')->where('id', '=', $user_id)->update([
                'status' => $request->status
            ]);
            if ($update) {
                return ['message' => 'success'];
            } else {
                Log::error( __FUNCTION__ . " : " . __CLASS__ . ' : update failed');
                return ['message' => 'fail'];
            }
        } catch (\Exception $e) {
            Log::error(__FUNCTION__ . " : " . __CLASS__ . ' : ' . $e->getMessage());
            return ['message' => 'fail'];
        }

    }
    public function destroy($id)
    {
        $unique_id = Helper::generate_uuid();
        try {
            $delete = DB::table('users')->where('id', '=', $id)->delete();
            if ($delete) {
                DB::table('role_user')->where('user_id', '=', $id)->delete();
                return ['message' => 'success'];
            }
            Log::channel('custom_log')->error($unique_id . " : " . __FUNCTION__ . " : " . __CLASS__ . ' : user not deleted');
            return ['message' => 'fail'];

        } catch (\Exception $e) {
            Log::channel('custom_log')->error($unique_id . " : " . __FUNCTION__ . " : " . __CLASS__ . ' : ' . $e->getMessage());
            return ['message' => 'fail'];
        }

    }
    public function export_users(Request $request)
    {
        $unique_id = Helper::generate_uuid();
        try {
            $query=DB::table('users')->select('user_name as Name','type')
            ->where('type','<>','normal');
            if ($request->type) {
                $query->where('type' , $request->type);
            }

            $data= $query->orderBy('id')->get();
            return $this->export_array_data_to_csv($data, array_keys(get_object_vars($data[0])), 'file');
        } catch (\Exception $e) {
            Log::channel('custom_log')->error($unique_id . " : " . __FUNCTION__ . " : " . __CLASS__ . ' : ' . $e->getMessage());
            return ['message' => 'fail'];
        }

    }
}
