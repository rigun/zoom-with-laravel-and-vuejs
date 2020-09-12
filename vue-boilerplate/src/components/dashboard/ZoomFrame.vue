<template>
  <div class="iframe-container">
    <meta charset="utf-8">
    <link type="text/css" rel="stylesheet" href="https://source.zoom.us/1.8.0/css/react-select.css" />
    <meta name="format-detection" content="telephone=no">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <meta http-equiv="origin-trial" content="">
  </div>
</template>

<script>
import { ZoomMtg } from "@zoomus/websdk";


const simd = async () => WebAssembly.validate(new Uint8Array([0, 97, 115, 109, 1, 0, 0, 0, 1, 4, 1, 96, 0, 0, 3, 2, 1, 0, 10, 9, 1, 7, 0, 65, 0, 253, 15, 26, 11]))
simd().then((res) => {
    console.log("simd check", res);
});
console.log("checkSystemRequirements");
console.log(JSON.stringify(ZoomMtg.checkSystemRequirements()));
// CDN version default
// ZoomMtg.setZoomJSLib('https://dmogdx0jrul3u.cloudfront.net/1.3.7/lib', '/av'); 
var API_KEY = 'kotL5SFzRIyflHQfAwVYLg';
var API_SECRET = 'HsEXexHpEGclDmPcOXgNT5xWqsfjFMxBLM2m';

export default {
  name: "ZoomFrame",
  data: function() {
    return {
      src: "",
      meetConfig: {},
      signature: {},
    };
  },
  props: {
    meetingPwd: String,
    meetingId: String,
    username: String,
  },
  created() {
    ZoomMtg.setZoomJSLib('https://source.zoom.us/1.8.0/lib', '/av'); 
    ZoomMtg.preLoadWasm();
    ZoomMtg.prepareJssdk();
    this.$nextTick(()=>{
        this.zoomFunction()
    })
  },
  methods:{
      zoomFunction(){
            this.meetConfig = {
                apiKey: API_KEY,
                apiSecret: API_SECRET,
                meetingNumber: parseInt(this.meetingId),
                userName: localStorage.getItem('name'),
                passWord: this.meetingPwd,
                leaveUrl: "/dashboard",
                role: 1
            };
            // Generate Signature function
            this.signature = ZoomMtg.generateSignature({
            meetingNumber: this.meetConfig.meetingNumber,
            apiKey: this.meetConfig.apiKey,
            apiSecret: this.meetConfig.apiSecret,
            role: this.meetConfig.role,
            success: function(res) {
                // eslint-disable-next-line
                console.log("success signature: " + res.result);
            }
            });
            // join function
            ZoomMtg.init({
            leaveUrl: this.meetConfig.leaveUrl,
            success: () => {
                ZoomMtg.join({
                meetingNumber: this.meetConfig.meetingNumber,
                userName: this.meetConfig.userName,
                signature: this.signature,
                apiKey: this.meetConfig.apiKey,
                userEmail: "",
                passWord: this.meetConfig.passWord,
                success: function(res) {
                    // eslint-disable-next-line
                    console.log(res);
                    console.log("join meeting success");
                    console.log("get attendeelist");
                    ZoomMtg.getAttendeeslist({});
                    ZoomMtg.getCurrentUser({
                    success: function (res) {
                        console.log("success getCurrentUser", res.result.currentUser);
                    },
                    });
                },
                error: function(res) {
                    // eslint-disable-next-line
                    console.log(res);
                }
                });
            },
            error: function(res) {
                // eslint-disable-next-line
                console.log(res);
            }
            });
            ZoomMtg.inMeetingServiceListener('onUserJoin', function (data) {
            console.log('inMeetingServiceListener onUserJoin', data);
            });
        
            ZoomMtg.inMeetingServiceListener('onUserLeave', function (data) {
            console.log('inMeetingServiceListener onUserLeave', data);
            });
        
            ZoomMtg.inMeetingServiceListener('onUserIsInWaitingRoom', function (data) {
            console.log('inMeetingServiceListener onUserIsInWaitingRoom', data);
            });
        
            ZoomMtg.inMeetingServiceListener('onMeetingStatus', function (data) {
            console.log('inMeetingServiceListener onMeetingStatus', data);
            });
      },
  }
};
</script>