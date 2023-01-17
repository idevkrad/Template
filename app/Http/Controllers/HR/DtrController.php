<?php

namespace App\Http\Controllers\HR;

use App\Models\Cto;
use App\Models\User;
use App\Models\Dtr;
use App\Models\Leave;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Resources\DtrResource;
use App\Http\Resources\Dtr2Resource;

class DtrController extends Controller
{
    public function index(Request $request)
    {   
        if($request->search == 'solo'){
            $month = $request->month+1;
            $year = $request->year;
            $today = date('Y-m-d', strtotime(now()));
            $array = [];
            $absent = 0;
            $keyword = $request->keyword;

            if($keyword != ''){
                $user = User::where('username', 'LIKE', "%{$keyword}%")->first();
                $username = $user->id;
            }else{
                $username = \Auth::user()->id;
            }

            $start_date = "01-".$month."-".$year;
            $start_time = strtotime($start_date);
            $end_time = strtotime("+1 month", $start_time);

            $group = \Auth::user()->groups->where('type','Schedule')->first();
            $schedule = $group['name'];

            if($schedule == 'A'){
                $day_words = 'Friday';
            }else if($schedule == 'B'){
                $day_words = 'Monday';
            }else{
                $day_words = '';
            }

            for($i=$start_time; $i<$end_time; $i+=86400)
            {
                $date = date('Y-m-d', $i);
                $day = date('l', $i);

                $data = Dtr::with('user.profile')
                ->whereDate('date',$date)
                ->where('user_id',$username)->first();

                if($day == 'Saturday' || $day == 'Sunday' || $day == $day_words){
                    $cto = Cto::where('user_id',$username)->where('status_id',[57,58])->whereDate('start', '<=', $date)->whereDate('end', '>=', $date)->first();
                    if($cto){
                        $array[] =  [
                            'date' => date('Y-m-d', $i),
                            'text' => date('F d, Y', $i),
                            'day' => date('l', $i),
                            'data' => 'CTO',
                            'bg' => 'bg bg-info',
                            'is_with' => false
                        ];
                    }else{
                        $array[] = [
                            'date' => date('Y-m-d', $i),
                            'text' => date('F d, Y', $i),
                            'day' => date('l', $i),
                            'data' => ($day == 'Monday' || $day == 'Friday') ? 'DAY OFF' :'NON-WORKING DAY',
                            'bg' => 'bg bg-secondary bg-soft',
                            'is_with' => false
                        ];
                    }
                }else{
                    $date = date('Y-m-d', $i);
                    $leave = Leave::where('user_id',$username)->where('status_id',57)->whereDate('start', '<=', $date)->whereDate('end', '>=', $date)->first();

                    if($leave){
                        $array[] =  [
                            'date' => date('Y-m-d', $i),
                            'text' => date('F d, Y', $i),
                            'day' => date('l', $i),
                            'data' => 'LEAVE',
                            'bg' => 'bg bg-info bg-soft',
                            'is_with' => false
                        ];
                    }else{
                        if($data){
                            if($data->am_in_at == '[]' || $data->am_out_at == '[]' || $data->pm_out_at == '[]' || $data->pm_in_at == '[]'){
                                $is_completed = false;
                            }else{
                                $is_completed = true;
                            }
                            $chck = ($date < $today) ? 'bg bg-soft bg-warning' : '';

                            $array[] = [
                                'date' => date('Y-m-d', $i),
                                'text' => date('F d, Y', $i),
                                'day' => date('l', $i),
                                'data' => new Dtr2Resource($data),
                                'bg' => ($is_completed) ? 'bg bg-soft bg-success' : $chck ,
                                'is_with' => true
                            ];
                        }else{
                            if($date < $today){
                                $absent++;
                                $array[] =  [
                                    'date' => date('Y-m-d', $i),
                                    'text' => date('F d, Y', $i),
                                    'day' => date('l', $i),
                                    'data' => 'ABSENT',
                                    'bg' => 'bg bg-danger bg-soft',
                                    'is_with' => false
                                ];
                            }else{
                                $array[] =  [
                                    'date' => date('Y-m-d', $i),
                                    'text' => date('F d, Y', $i),
                                    'day' => date('l', $i),
                                    'data' => [],
                                    'bg' => '',
                                    'is_with' => true
                                ];
                            }
                        }
                    }
                }
            }
            return [
                'data' => $array,
                'absent' => $absent,
                'id' => $username
            ];
        }else if($request->search){ 
            $data = Dtr2Resource::collection(
                Dtr::query()
                ->with('user.profile')
                ->when($request->keyword, function ($query, $keyword) {
                    $query->whereHas('user',function ($query) use ($keyword) {
                        $query->where('username', 'LIKE', "%{$keyword}%");
                    });
                })
                ->paginate($request->count)
                ->withQueryString()
            );
            return $data;
        }else{
            return inertia('HR/DTR/Index');
        }
    }

    public function show(Request $request){
        if($request->search){
            $data = User::with('profile')->with('employee')->where('is_active',1)->where('username',$request->id)->first();
            if($data){
                $date = date_format(date_create("$request->date"),"Y-m-d");
                $type = $request->type;
                $dtr = Dtr::whereDate('date',$date)->where('user_id',$data->id)->first();

                $info = [
                    'ip' => \Request::ip(), 
                    'pcname' => gethostname(),
                    'browser' => $request->header('User-Agent'),
                    'time' => $request->time,
                    'is_updated' => false
                ];

                if($dtr){
                
                    switch($type){
                        case 'Time In (am)': 
                            if(!empty(json_decode($dtr->am_in_at))){
                                return ['status' => 'duplicate','name' => $data->profile->firstname.' '.$data->profile->lastname];
                            }else{
                                $dtr->am_out_at = json_encode($info);
                                $dtr->save();
                                return new DtrResource($data);
                            }
                        break;
                        case 'Time Out (am)': 
                            if(empty(json_decode($dtr->am_out_at))){
                                $dtr->am_out_at = json_encode($info);
                                $dtr->save();
                                return new DtrResource($data);
                            }else{
                                return ['status' => 'duplicate','name' => $data->profile->firstname.' '.$data->profile->lastname];
                            }
                        break;
                        case 'Time In (pm)': 
                            if(empty(json_decode($dtr->am_out_at)) && !empty(json_decode($dtr->am_in_at))){
                                return ['status' => 'no timeout','name' => $data->profile->firstname.' '.$data->profile->lastname];
                            }else if(!empty(json_decode($dtr->pm_in_at))){
                                return ['status' => 'duplicate','name' => $data->profile->firstname.' '.$data->profile->lastname];
                            }else{
                                $dtr->pm_in_at = json_encode($info);
                                $dtr->save();
                                return new DtrResource($data);
                            }
                        break;
                        case 'Time Out (pm)': 
                            if(!empty(json_decode($dtr->pm_out_at))){
                                return ['status' => 'duplicate','name' => $data->profile->firstname.' '.$data->profile->lastname];
                            }else{
                                $dtr->pm_out_at = json_encode($info);
                                $dtr->save();
                                return new DtrResource($data);
                            }
                        break;
                    }

                }else{

                    $group = [];
                    $dtr = new Dtr;
                    $dtr->date = now();
                    $dtr->am_in_at = ($type == 'Time In (am)') ? json_encode($info) : json_encode([]);
                    $dtr->am_out_at = ($type == 'Time Out (am)') ? json_encode($info) : json_encode([]);
                    $dtr->pm_in_at = ($type == 'Time In (pm)') ? json_encode($info) : json_encode([]);
                    $dtr->pm_out_at = ($type == 'Time Out (pm)') ? json_encode($info) : json_encode([]);
                    $dtr->remarks = json_encode([]);
                    $dtr->user_id = $data->id;
                    $dtr->save();
                }
            
                return new DtrResource($data);
            }else{ 
                return [
                    'data' => null,
                ];
            }
        }else if($request->dtr == 'board'){
            return inertia('Dtr');
        }else if($request->dtr == 'staff'){
            return inertia('HR/DTR/Admin');
        }else{
            return inertia('HR/DTR/Index');
        }
    }

    public function edit($info)
    {
        $info = (!empty(json_decode($info))) ? json_decode($info) : NULL;
        $month = ($info->month == '') ? date('n') :  date("n", strtotime($info->month));
        $year = ($info->year == '') ? date('Y') :  date("Y", strtotime($info->year));
        $user = Dtr::with('user.profile')->where('user_id',$info->id)->first();
        $user = $user->user->profile;

        $today = date('Y-m-d', strtotime(now()));
        $array = [];

        $user_id = $info->id ;
        $start_date = "01-".$month."-".$year;
        $start_time = strtotime($start_date);
        $end_time = strtotime("+1 month", $start_time);

        for($i=$start_time; $i<$end_time; $i+=86400)
        {
            $date = date('Y-m-d', $i);
            $day = date('l', $i);

            $data = Dtr::with('user.profile')
            ->whereDate('date',$date)
            ->where('user_id',$info->id)->first();

            if($day == 'Saturday' || $day == 'Sunday'){
                $cto = Cto::where('user_id',$info->id)->where('status_id',[57,58])->whereDate('start', '<=', $date)->whereDate('end', '>=', $date)->first();
                    if($cto){
                        $array[] =  [
                            'date' => date('Y-m-d', $i),
                            'text' => date('F d, Y', $i),
                            'day' => date('l', $i),
                            'data' => 'CTO',
                            'bg' => 'bg bg-info',
                            'is_with' => false
                        ];
                    }else{
                        $array[] = [
                            'date' => date('Y-m-d', $i),
                            'text' => date('F d, Y', $i),
                            'day' => date('l', $i),
                            'data' => 'NON-WORKING DAY',
                            'bg' => 'bg bg-secondary bg-soft',
                            'is_with' => false
                        ];
                    }
            }else{
                $date = date('Y-m-d', $i);
                $leave = Leave::where('user_id',$user_id)->where('status_id',57)->whereDate('start', '<=', $date)->whereDate('end', '>=', $date)->first();

                if($leave){
                    $array[] =  [
                        'date' => date('Y-m-d', $i),
                        'text' => date('F d, Y', $i),
                        'day' => date('l', $i),
                        'data' => 'LEAVE',
                        'bg' => 'bg bg-info bg-soft',
                        'is_with' => false
                    ];
                }else{
                    if($data){
                        if($data->am_in_at == '[]' || $data->am_out_at == '[]' || $data->pm_out_at == '[]' || $data->pm_in_at == '[]'){
                            $is_completed = false;
                        }else{
                            $is_completed = true;
                        }
                        $chck = ($date < $today) ? 'bg bg-soft bg-warning' : '';

                        $array[] = [
                            'date' => date('Y-m-d', $i),
                            'text' => date('F d, Y', $i),
                            'day' => date('l', $i),
                            'data' => new Dtr2Resource($data),
                            'bg' => ($is_completed) ? 'bg bg-soft bg-success' : $chck ,
                            'is_with' => true
                        ];
                    }else{
                        if($date < $today){
                            $array[] =  [
                                'date' => date('Y-m-d', $i),
                                'text' => date('F d, Y', $i),
                                'day' => date('l', $i),
                                'data' => 'ABSENT',
                                'bg' => 'bg bg-danger bg-soft',
                                'is_with' => false
                            ];
                        }else{
                            $array[] =  [
                                'date' => date('Y-m-d', $i),
                                'text' => date('F d, Y', $i),
                                'day' => date('l', $i),
                                'data' => [],
                                'bg' => '',
                                'is_with' => true
                            ];
                        }
                    }
                }
            }
        }

         $array = [
            'user' => $user, 
            'lists' => $array, 
            'month' => $month, 
            'year' => $year, 
            'search' => $year.'-'.$month.'-',
            'today' => date('Y-m-d', strtotime(now()))
        ];

        $pdf = \PDF::loadView('prints.dtr',$array)->setPaper('a4', 'portrait');
        return $pdf->download('dtr.pdf');
    }

    public function update(Request $request){
        $data = Dtr::findOrFail($request->id);
        if($request->am_in != 'good' && $request->am_in != null){
            $info = [
                'ip' => \Request::ip(), 
                'pcname' => gethostname(),
                'browser' => $request->header('User-Agent'),
                'time' => $request->am_in,
                'is_updated' => true
            ];
            $data->am_in_at = json_encode($info);
        }
        if($request->am_out != 'good' && $request->am_out != null){
            $info = [
                'ip' => \Request::ip(), 
                'pcname' => gethostname(),
                'browser' => $request->header('User-Agent'),
                'time' => $request->am_out,
                'is_updated' => true
            ];
            $data->am_out_at = json_encode($info);
        }
        if($request->pm_in != 'good' && $request->pm_in != null){
            $info = [
                'ip' => \Request::ip(), 
                'pcname' => gethostname(),
                'browser' => $request->header('User-Agent'),
                'time' => $request->pm_in,
                'is_updated' => true
            ];
            $data->pm_in_at = json_encode($info);
        } 
        if($request->pm_out != 'good' && $request->pm_out != null){
            $info = [
                'ip' => \Request::ip(), 
                'pcname' => gethostname(),
                'browser' => $request->header('User-Agent'),
                'time' => $request->pm_out,
                'is_updated' => true
            ];
            $data->pm_out_at = json_encode($info);
        }
        $remarks = [
            'remarks' => $request->remarks,
            'updated_by' => \Auth::user()
        ];
        $data->remarks = json_encode($remarks);
        $data->is_updated = 1;
        $data->save();

        return back()->with([
            'data' => new Dtr2Resource($data),
            'message' => 'Employee dtr updated successfully. Thanks',
            'type' => 'bxs-check-circle'
        ]);
    }
}
