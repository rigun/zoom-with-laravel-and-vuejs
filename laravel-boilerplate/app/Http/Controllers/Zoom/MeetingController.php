<?php

namespace App\Http\Controllers\Zoom;

use App\Http\Controllers\Controller;
use App\Http\Traits\ZoomJWT;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\ZoomData;

class MeetingController extends Controller
{
    use ZoomJWT;

    const MEETING_TYPE_INSTANT = 1;
    const MEETING_TYPE_SCHEDULE = 2;
    const MEETING_TYPE_RECURRING = 3;
    const MEETING_TYPE_FIXED_RECURRING_FIXED = 8;

    public function list(Request $request)
    {
        $path = 'users/me/meetings';
        $response = $this->zoomGet($path);
    
        $data = json_decode($response->body(), true);
        $data['meetings'] = array_map(function (&$m) {
            $m['start_at'] = $this->toUnixTimeStamp($m['start_time'], $m['timezone']);
            return $m;
        }, $data['meetings']);
    
        return [
            'success' => $response->ok(),
            'data' => $data,
        ];
    }

    public function create(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
        ]);
        
        if ($validator->fails()) {
            return $this->responseJson("Data tidak valid",0);

        }
        $data = $validator->validated();

        $path = 'users/'.$data['email'].'/meetings';
        $response = $this->zoomPost($path, [
            'topic' => "Test meeting wit zoom SDK",
            'type' => 1,
            'settings' => [
                'host_video' => true,
                'participant_video' => true
            ]
        ]);

        $responseJson = json_decode($response->body(),true);
        $item = new ZoomData();
        $item->email = $data['email'];
        $item->meetingPwd = $responseJson['password'];
        $item->meetingId = $responseJson['id'];
        $item->save();

        return $this->responseJson('Success',1,json_decode($response->body(),true));
        // return [
        //     'success' => $response->status() === 201,
        //     'data' => json_decode($response->body(), true),
        // ];
    }
    public function getZoomData(){
        $item = ZoomData::get();
        return $this->responseJson('Success',1,$item);
    }

    public function get(Request $request, string $id)
    {
        $path = 'meetings/' . $id;
        $response = $this->zoomGet($path);
    
        $data = json_decode($response->body(), true);
        if ($response->ok()) {
            $data['start_at'] = $this->toUnixTimeStamp($data['start_time'], $data['timezone']);
        }
    
        return [
            'success' => $response->ok(),
            'data' => $data,
        ];
    }

    public function update(Request $request, string $id) { /**/ }
    public function delete(Request $request, string $id) { /**/ }
}
