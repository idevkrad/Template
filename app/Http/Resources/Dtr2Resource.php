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
            case 'C': 
                $sched =  ['am_in' => '8:00:00', 'am_out' => '12:00:00', 'pm_in' => '13:00:00', 'pm_out' => '17:00:00'];
            break;
            default:
                $sched = ['am_in' => '7:30:00', 'am_out' => '12:00:00', 'pm_in' => '13:00:00', 'pm_out' => '18:30:00',];
        }
        $times = [$sched['am_in'],$sched['am_out'],$sched['pm_in'],$sched['pm_out']];
        $amin = '';  $amout = '';  $pmin = '';  $pmout = ''; $amabsent = false; $pmabsent = false; $flex = false;

        $am_in = json_decode($this->am_in_at);
        $am_out = json_decode($this->am_out_at);
        $pm_in = json_decode($this->pm_in_at);
        $pm_out = json_decode($this->pm_out_at);
        $notset = ['ip' => 'Not Available'];
        
        if($am_in != [] && $am_out != []){
            if(strtotime($am_in->time) > strtotime($times[0])){
                if($pm_in == [] && $pm_out == []){
                    $amin = 'text-danger';
                }else{
                    if($schedule != 'C'){
                        if($pm_out != []){
                            if(strtotime($am_in->time) > strtotime('07:30:00') && strtotime($am_in->time) < strtotime('08:00:00')){
                                $amin = 'text-success';
                                $newDate = date('H:i:s', strtotime('18:30:00'. ' +'.$am_in->minutes.' minutes'));
                                if(strtotime($pm_out->time) < strtotime($newDate)){
                                    $pmout = 'text-danger';
                                }else{
                                    $flex = true;
                                    $pmout = 'text-success';
                                }
                            }else{
                                $amin = 'text-danger';
                            }
                        }
                    }else{
                        $amin = 'text-danger';
                    }
                }
            }
            if(strtotime($am_out->time) < strtotime($times[1])){
                $amout = 'text-danger';
            }
        }else{
            $amabsent = true;
        }
        if($pm_in != [] && $pm_out != []){
            if(strtotime($pm_in->time) > strtotime($times[2])){
                $pmin = 'text-danger';
            }
            if(!$flex){
                if(strtotime($pm_out->time) < strtotime($times[3])){
                    $pmout = 'text-danger';
                }
            }
        }else{
            $pmabsent = true;
        }
        // if(date('M d, Y', strtotime(now())) == date('M d, Y', strtotime($this->date))){ 
        //     $tardiness = 0; $undertime = 0;
        // }else{
        //     if($am_in != [] && $am_out != []){
        //         if($am_in != []){
        //             if(strtotime($am_in->time) > strtotime($times[0])){
        //                 $startTime = Carbon::parse(date('h:i',strtotime($am_in->time)));
        //                 $finishTime = Carbon::parse(strtotime($times[0]));
        //                 $totalIn = $finishTime->diffInMinutes($startTime);
                        
        //                 if($schedule != 'C'){
        //                     if(strtotime($am_in->time) < strtotime('08:00:00')){
        //                         $newDate = date('H:i:s', strtotime($times[3]. ' +'.$totalIn.' minutes'));
        //                         if($pm_out != []){
        //                             if($pm_out->time < $newDate){
        //                                 $startTime = Carbon::parse(date('H:i',strtotime($pm_out->time)));
        //                                 $finishTime = Carbon::parse(strtotime($newDate));
        //                                 $undertime = $undertime + $finishTime->diffInMinutes($startTime);
        //                                 $pmout = 'text-danger';
        //                             }
        //                         }else{
        //                             if($pm_out == []){
        //                                 $tardiness = $tardiness +$totalIn;
        //                                 $amin = 'text-danger';
        //                             }
        //                         }
        //                     }else{
        //                         $tardiness = $tardiness + $totalIn;
        //                         $amin = 'text-danger';
        //                     }
        //                 }else{
        //                     $tardiness = $tardiness + $totalIn;
        //                     $amin = 'text-danger';
        //                 }
        //             }
        //         }
        //         if($am_out != []){
        //             if(strtotime($am_out->time) < strtotime($times[1])){
        //                 $startTime = Carbon::parse(date('H:i',strtotime($am_out->time)));
        //                 $finishTime = Carbon::parse(strtotime($times[1]));
        //                 $undertime = $undertime + $finishTime->diffInMinutes($startTime);
        //                 $amout = 'text-danger';
        //             }
        //         }
        //     }else{
        //         $tardiness = $tardiness + $am;
        //         $amabsent = true;
        //     }

        //     if($pm_in != [] && $pm_out != []){
        //         if($pm_in != []){
        //             if(strtotime($pm_in->time) > strtotime($times[2])){
        //                 $startTime = Carbon::parse(strtotime($pm_in->time));
        //                 $finishTime = Carbon::parse(strtotime($times[2]));
        //                 $tardiness = $tardiness + $finishTime->diffInMinutes($startTime);
        //                 $pmin = 'text-danger';
        //             }
        //         }
        //         if($pm_out != []){
        //             if($pm_out->time < $times[3]){
        //                 $startTime = Carbon::parse(date('H:i',strtotime($pm_out->time)));
        //                 $finishTime = Carbon::parse(strtotime($times[3]));

        //                 if($schedule == 'C'){
        //                     $undertime = $undertime + $finishTime->diffInMinutes($startTime);
        //                     $pmout = 'text-danger';
        //                 }else{
        //                     if($am_in != []){
        //                         if(strtotime($am_in->time) > strtotime('08:00:00')){
        //                             $undertime = $undertime + $finishTime->diffInMinutes($startTime);
        //                             $pmout = 'text-danger';
        //                         }
        //                     }
        //                 }
        //             }
                    
        //         }
        //     }else{
        //         $undertime = $undertime + $pm;
        //         $pmabsent = true;
        //     }
        // }

      

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
            'is_completed' => $this->completed,
            'tardiness' => $this->tardiness,
            'undertime' => $this->undertime
        ];
    }
}
