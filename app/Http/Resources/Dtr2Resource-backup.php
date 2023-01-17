<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class Dtr2Resource extends JsonResource
{
    public function toArray($request)
    {
        $group = \Auth::user()->groups->where('type','Schedule')->first();
        $schedule = $group['name'];

        switch($schedule){
            case 'A': 
                $sched = ['group' => 'A', 'am_in' => '7:30:00', 'am_out' => '12:00:00', 'pm_in' => '13:00:00', 'pm_out' => '18:30:00', 'dayoff' => 'friday'];
                $am = 270; $pm = 330;
            break;
            case 'B': 
                $sched = ['group' => 'B', 'am_in' => '7:30:00', 'am_out' => '12:00:00', 'pm_in' => '13:00:00', 'pm_out' => '18:30:00', 'dayoff' => 'monday'];
                $am = 270; $pm = 330;
            break;
            case 'C': 
                $sched =  ['group' => 'C', 'am_in' => '8:00:00', 'am_out' => '12:00:00', 'pm_in' => '13:00:00', 'pm_out' => '17:00:00'];
                $am = 240; $pm = 240;
            break;
        }

        $amin = '';  $amout = '';  $pmin = '';  $pmout = ''; $amabsent = false; $pmabsent = false;
        $tardiness = 0; $undertime = 0;

        $am_in = json_decode($this->am_in_at);
        $am_out = json_decode($this->am_out_at);
        $pm_in = json_decode($this->pm_in_at);
        $pm_out = json_decode($this->pm_out_at);
        $notset = ['ip' => 'Not Available'];
        
        $times = [$sched['am_in'],$sched['am_out'],$sched['pm_in'],$sched['pm_out']];
        $totalIn = 0;

        $totalDuration = 0;
        if(date('M d, Y', strtotime(now())) == date('M d, Y', strtotime($this->date))){ 
            $tardiness = 0; $undertime = 0;
        }else{
            if($am_in != [] && $am_out != []){
                if($am_in != []){
                    if(strtotime($am_in->time) > strtotime($times[0])){
                        $startTime = Carbon::parse(date('h:i',strtotime($am_in->time)));
                        $finishTime = Carbon::parse(strtotime($times[0]));
                        $tardiness = $tardiness + $finishTime->diffInMinutes($startTime);

                        if($schedule != 'C' && strtotime($am_in->time) < strtotime('08:00:00')){
                            $totalIn = $tardiness;  
                            $newDate = date('H:i:s', strtotime($times[3]. ' +'.$totalIn.' minutes'));
                            if($pm_out->time < $newDate){
                                $startTime = Carbon::parse(date('h:i',strtotime($pm_out->time)));
                                $finishTime = Carbon::parse(strtotime($newDate));
                                $undertime = $undertime + $finishTime->diffInMinutes($startTime) - $totalIn;
                            }
                        }else{
                            $amin = 'text-danger';
                        }
                    }
                }
                if($am_out != []){
                    if(strtotime($am_out->time) < strtotime($times[1])){
                        $startTime = Carbon::parse(date('h:i',strtotime($am_out->time)));
                        $finishTime = Carbon::parse(strtotime($times[1]));
                        $undertime = $undertime + $finishTime->diffInMinutes($startTime);
                        $amout = 'text-danger';
                    }
                }
            }else{
                $tardiness = $tardiness + $am;
                $amabsent = true;
            }

            if($pm_in != [] && $pm_out != []){
                if($pm_in != []){
                    if(strtotime($pm_in->time) > strtotime($times[2])){
                        $startTime = Carbon::parse(strtotime($pm_in->time));
                        $finishTime = Carbon::parse(strtotime($times[2]));
                        $tardiness = $tardiness + $finishTime->diffInMinutes($startTime);
                        $pmin = 'text-danger';
                    }
                }
                if($pm_out != []){
                    
                    if($schedule != 'C'){
                        if($totalIn != 0){
                            $newDate = date('H:i:s', strtotime($times[3]. ' +'.$totalIn.' minutes'));
                            if($pm_out->time < $newDate){
                                $startTime = Carbon::parse(strtotime($pm_out->time));
                                $finishTime = Carbon::parse(strtotime($newDate));
                                $undertime = $undertime + $finishTime->diffInMinutes($startTime) - $totalIn;
                            }else{
                                // $undertime = $undertime - $totalIn;
                            }
                        }else{
                            if($pm_out->time < $times[3]){
                                $startTime = Carbon::parse(strtotime($pm_out->time));
                                $finishTime = Carbon::parse(strtotime($times[3]));
                                $undertime = $undertime + $finishTime->diffInMinutes($startTime);
                                $pmout = 'text-danger';
                            }
                        }
                    }else{
                        if(strtotime($pm_out->time) < strtotime($times[3])){
                            $startTime = Carbon::parse(strtotime($pm_out->time));
                            $finishTime = Carbon::parse(strtotime($times[3]));
                            $undertime = $undertime + $finishTime->diffInMinutes($startTime);
                        }
                    }        
                }
            }else{
                $undertime = $undertime + $pm;
                $pmabsent = true;
            }
        }

      

        return [
            'id' => $this->id,
            'username' => $this->user->username,
            'name' => $this->user->profile->firstname.' '.$this->user->profile->middlename[0].'. '.$this->user->profile->lastname,
            'avatar' => $this->user->avatar,
            'date' => date('M d, Y', strtotime($this->date)),
            'day' => date('l', strtotime($this->date)),
            'am_in' => (!empty($am_in)) ? date('g:i a', strtotime($am_in->time)) : '-',
            'am_out' => (!empty($am_out)) ? date('g:i a', strtotime($am_out->time)) : '-',
            'pm_in' => (!empty($pm_in)) ? date('g:i a', strtotime($pm_in->time)) : '-',
            'pm_out' => (!empty($pm_out)) ? date('g:i a', strtotime($pm_out->time)) : '-',
            'am_in_at' => (!empty($am_in)) ? $am_in : $notset,
            'am_out_at' => (!empty($am_out)) ? $am_out : $notset,
            'pm_in_at' => (!empty($pm_in)) ? $pm_in : $notset,
            'pm_out_at' => (!empty($pm_out)) ? $pm_out : $notset,
            'amin' => $amin,
            'amout' => $amout,
            'pmin' => $pmin,
            'pmout' => $pmout,
            'amabsent' => $amabsent,
            'pmabsent' => $pmabsent,
            'remarks' => json_decode($this->remarks),
            'is_updated' => $this->updated,
            'tardiness' => ($tardiness == 0) ? '' : $tardiness,
            'undertime' => ($undertime == 0) ? '' : $undertime
        ];
    }
}
