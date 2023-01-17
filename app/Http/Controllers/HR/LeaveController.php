<?php

namespace App\Http\Controllers\HR;

use App\Models\Leave;
use App\Models\User;
use App\Models\ListDropdown;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Resources\LeaveResource;

class LeaveController extends Controller
{
    public function index(Request $request){
        if($request->search == 'lists'){
            $q = Leave::query();
            $q->with('status','detail','type','user:id,avatar','user.profile:id,user_id,firstname,lastname,middlename','user.employee:id,user_id,position_id,department_id,office_id','user.employee.position','user.employee.department','user.employee.office');
            if($request->type == 'solo'){
                $q->where('user_id',\Auth::user()->id)->orderBy('status_id','ASC');
            }else{
                $q->when($request->department, function ($query, $keyword) {
                    $query->whereHas('user',function ($query) use ($keyword){
                        $query->whereHas('employee',function ($query) use ($keyword){
                            $query->whereHas('department',function ($query) use ($keyword){
                                $query->where('department_id',$keyword);
                            });
                        });
                    });
                });
                $q->when($request->status, function ($query, $keyword) {
                    $query->where('status_id',$keyword);
                });
            }
            $q->orderBy('status_id','ASC');
            $data = $q->paginate($request->count)->withQueryString();
            return LeaveResource::collection($data);
        }else{
            return inertia('HR/Leave/Index');
        }
    }

    public function show(Request $request){
        if($request->leave == 'staff'){
            return inertia('HR/Leave/Admin');
        }
    }

    public function store(Request $request)
    { 
        $data = \DB::transaction(function () use ($request){
            return $data = Leave::create($request->all());
        });

        return back()->with([
            'message' => 'Application for Leave sent.',
            'data' => new LeaveResource($data),
            'type' => 'bxs-check-circle'
        ]); 
    }

    public function update(Request $request){
        $data = \DB::transaction(function () use ($request){
            $data = Leave::findOrFail($request->id);
            $data->update($request->all());
            return  $data = Leave::findOrFail($request->id);
        });

        return back()->with([
            'message' => 'Application Updated.',
            'data' => new LeaveResource($data),
            'type' => 'bxs-check-circle'
        ]); 
    }

    public function edit($id)
    {
        $data = Leave::with('status','detail','type','user:id','user.profile:id,user_id,firstname,lastname,middlename','user.employee:id,user_id,position_id,department_id,office_id','user.employee.position','user.employee.department','user.employee.office')
            ->where('id',$id)->first();
        $department_id = $data->user->employee->department->id;

        $ard = User::with('profile:id,user_id,firstname,lastname,middlename')->whereHas('employee',function ($query) use ($department_id) {
           $query->where('department_id',$department_id);
        })->whereHas('roles',function ($query) {
            $query->where('userable_id', 2);
        })->first(); // get from list roles where id is 2 // assintant regional director

        $rd = User::with('profile:id,user_id,firstname,lastname,middlename')->whereHas('roles',function ($query) {
             $query->where('userable_id', 1);
         })->first(); // get from list roles where id is 1 //regional director

        $hh = User::with('profile:id,user_id,firstname,lastname,middlename')->whereHas('roles',function ($query) {
            $query->where('userable_id', 7);
        })->first(); // get from list roles where id is 1 // hr head
       
        $array = [
            'ard' => $ard,
            'rd' => $rd,
            'hh' => $hh,
            'employee' => new LeaveResource($data),
            'types' => ListDropdown::where('classification','Leave')->get(),
            'titles' => ListDropdown::where('classification','Details of Leave')->select('type')->distinct()->get(),
            'details' => ListDropdown::where('classification','Details of Leave')->get()
        ];

        $pdf = \PDF::loadView('prints.leave',$array)->setPaper('a4', 'portrait');
        return $pdf->download('leave-form.pdf');
    }
}
