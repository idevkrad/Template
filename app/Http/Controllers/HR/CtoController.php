<?php

namespace App\Http\Controllers\HR;

use App\Models\Cto;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Resources\CtoResource;

class CtoController extends Controller
{
    public function index(Request $request){
        if($request->search == 'lists'){
            $department_id = $request->department;
            $q = Cto::query();
            $q->with('status','user:id,avatar','user.profile:id,user_id,firstname,lastname,middlename','user.employee:id,user_id,position_id,department_id,office_id','user.employee.position','user.employee.department','user.employee.office');
            if($request->type == 'solo'){
                $q->where('user_id',\Auth::user()->id);
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
            return CtoResource::collection($data);
        }else{
            return inertia('HR/Cto/Index');
        }
    }

    public function store(Request $request)
    { 
        $data = \DB::transaction(function () use ($request){
            return $data = Cto::create(array_merge($request->all(), ['total' => '00:00:00', 'reports' => '[]']));
        });

        return back()->with([
            'message' => 'Application for CTO sent.',
            'data' => new CtoResource($data),
            'type' => 'bxs-check-circle'
        ]); 
    }

    public function show(Request $request){
        if($request->cto == 'staff'){
            return inertia('HR/Cto/Admin');
        }
    }

    public function update(Request $request){
        $data = \DB::transaction(function () use ($request){
            $data = Cto::findOrFail($request->id);
            $data->update($request->all());
            return  $data = Cto::findOrFail($request->id);
        });

        return back()->with([
            'message' => 'Application Updated.',
            'data' => new CtoResource($data),
            'type' => 'bxs-check-circle'
        ]); 
    }
}
