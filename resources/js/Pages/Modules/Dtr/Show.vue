<template>

    <Head title="Welcome DOST Scholars" />

   <body>
    <div class="account-pages my-4 pt-sm-5">
        <div class="container">
                
           
            <div class="row justify-content-center">
                <div class="col-md-10">
                    <!-- <div class="row mb-3">
                        <div class="col-1">
                            <img src="images/dost.png" alt="" class="avatar-sm">
                        </div>
                        <div class="col-11 ms-n3">
                            <div class="text-primary mt-0">
                                <p style="font-size: 16px;">Department of Science & Technology - IX<br>Human Resource - Date Time Record</p>
                            </div>
                        </div>
                    </div> -->
                    <div class="text-center mb-4">
                        <img src="/images/dost.png" alt="" class="avatar-sm mb-3">
                        <p class="fw-bold">Department of Science & Technology - IX</p>
                        <p class="font-size-15 text-muted" style="margin-top: -20px;">Human Resource - Date Time Record</p>
                    </div>
                    <div class="card border bg-primary">
                        <h4 class="text-white p-3" style="margin-bottom: -28px;">{{ currentDate}}</h4>
                        <div class="card-body mt-4 mb-n3 bg-white bg-soft">
                            <h1 style="font-size: 140px; margin-top: -10px; margin-bottom: -2px;" class="text-primary text-center dfw-medium" v-text="currentTime"></h1>
                        </div>
                    
                        <div class="card-body border-top bg-soft" style="background-color: white; ">
                            <div class="p-2">
                                <div class="text-center">
                                   <div class=" mt-n2 mb-n2">
                                        <b-tabs v-model="activebutton" 
                                                pills nav-class="bg-light rounded nav-justified fw-bold" 
                                                content-class="mt-3">
                                            <b-tab title="AM IN" v-on:click="swap('Time In (am)','0')"></b-tab>
                                            <b-tab title="AM OUT" v-on:click="swap('Time Out (am)','1')"></b-tab>
                                            <b-tab title="PM IN" v-on:click="swap('Time In (pm)','2')"></b-tab>
                                            <b-tab title="PM OUT" v-on:click="swap('Time Out (pm)','3')"></b-tab>
                                        </b-tabs>
                                    </div>
                                    <input @keyup.enter="find" v-model="id" autofocus type="text" class="form-control form-control-lg text-center" style="font-size: 30px; text-transform: uppercase; background-color: #eff2f7;">
                                </div>

                                <!-- <div class="alert alert-success mt-2" role="alert" v-if="user">
                                    <p v-if="activebutton == 0">Goodmorning!</p>
                                    <p v-if="activebutton == 1">Happy Lunch!</p>
                                    <p v-if="activebutton == 2">Goodafternoon!</p>
                                    <p v-if="activebutton == 3">Goodbye!</p>
                                </div> -->
                                
                                <!-- <div class="card bg-success bg-soft border shadow-none mt-2" v-if="user" style="margin-bottom: -11px;">
                                    <div class="table-responsive mb-n3" >
                                        <table class="table table-nowrap table-borderless table-centered align-middle">
                                            <tbody>
                                                <tr>
                                                    <td style="width: 3%;"><img :src="currentUrl+'/images/avatars/'+user.avatar" alt="" class="rounded-circle avatar-xs"></td>
                                                    <td class="fw-bold"> 
                                                        <h5 class="font-size-13 mb-0 text-dark fw-bold" style="margin-left: -10px;">
                                                            {{user.name}} <span class="font-size-11 text-success">({{user.username}})</span>
                                                        </h5>
                                                        <p class="font-size-12 text-muted mb-0" style="margin-left: -10px;">{{user.department }}</p>
                                                    </td>
                                                    <td class="fw-bold text-end"> 
                                                        <h5 class="font-size-13 mb-0 text-dark fw-bold">{{user.time}}</h5>
                                                        <p class="font-size-12 text-muted mb-0">{{ type }}</p>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div> -->
                                <div class="alert alert-success mt-2" v-if="user" role="alert" style="margin-bottom: -10px;">
                                    <table class="table table-nowrap table-borderless table-centered align-middle" style="margin-bottom: -11px; margin-top: -11px; margin-left: -15px; margin-right: -15px;">
                                        <tbody>
                                            <tr>
                                                <td style="width: 3%;"><img :src="currentUrl+'/images/avatars/'+user.avatar" alt="" class="rounded-circle avatar-xs"></td>
                                                <td class="fw-bold"> 
                                                    <h5 class="font-size-13 mb-0 text-dark fw-bold" style="margin-left: -10px;">
                                                        {{user.name}} <span class="font-size-11 text-success">({{user.username}})</span>
                                                    </h5>
                                                    <p class="font-size-12 text-muted mb-0" style="margin-left: -10px;">{{user.department }}</p>
                                                </td>
                                                <td class="fw-bold text-end"> 
                                                    <h5 class="font-size-13 mb-0 text-dark fw-bold">{{user.time}}</h5>
                                                    <p class="font-size-12 text-muted mb-0">{{ type }}</p>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <div :class="'alert alert-'+status+' mt-2 text-center'" v-else-if="inactive" role="alert" style="margin-bottom: -10px;">
                                    <span class="fw-bold">{{ message }}</span>
                                </div>

                            </div>
                        </div>
                    </div>
                    <div class="mt-5 text-center">
                        <p>© DOST-IX. Crafted with <i class="mdi mdi-heart text-danger"></i> by Krad</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</template>
<script>
    const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric'  };
    const options1 = { hour12: false  };
    const twelve = new Date("2022-03-25 11:00:00").toLocaleTimeString("en-US",options1);
    const twelvethirty = new Date("2022-03-25 12:30:00").toLocaleTimeString("en-US",options1);
    const one = new Date("2022-03-25 15:00:00").toLocaleTimeString("en-US",options1);
    
    export default {
        layout: null,
        data() {
            return {
                currentUrl: window.location.origin,
                currentDate: null,
                currentTime: null,
                currentTime2: null,
                id: '',
                user: '',
                type: 'AM IN',
                activebutton: 0,
                inactive: false,
                message: '',
                status: ''
            };
        },

        created(){
            this.filter();
        },

        mounted() {
            setInterval(() => {
                this.currentSecond = new Date().toLocaleTimeString([],{seconds: '2-digit'});
                this.currentTime = new Date().toLocaleTimeString("en-US");
                this.currentDate = new Date().toLocaleDateString("en-US",options);
            }, 1000);
        },

        methods: {
            find(){
                this.user = ''; this.inactive = false;
                axios.get('/dtr/entry',{
                    params : {
                        id : this.id,
                        date : new Date().toLocaleDateString("en-US"),
                        // time : new Date().toLocaleTimeString("en-US",options1),
                        time: '12:04:00',
                        type: this.type
                    }
                })
                .then(response => {
                    if(response.data.status == 'no timeout'){
                        this.inactive = true;
                        this.message = 'Hi '+ response.data.name +', Please time out first.';
                        this.status = 'warning';
                        setInterval(() => { this.id = ''; this.inactive = false;}, 9000);
                    }else if(response.data.data != null){
                        this.user = response.data.data;
                        this.id = '';
                        setInterval(() => {this.user = '';}, 9000); 
                    }else if(response.data.status == 'duplicate'){
                        this.id = ''; 
                        this.inactive = true;
                        this.status = 'warning';
                        this.message = 'Hi '+ response.data.name +', you have already '+this.type+'.';
                        setInterval(() => { this.inactive = false;}, 9000);
                    }else{
                        this.inactive = true;
                        this.status = 'danger';
                        this.message = 'Employee not found, Please contact administrator.';
                        setInterval(() => { this.id = ''; this.inactive = false;}, 9000);
                    }
                })
                .catch(err => console.log(err));
            },

            filter(){
                this.currentTime2 = new Date().toLocaleTimeString("en-US",options1);
                if(this.currentTime2 < twelve){
                    this.activebutton = 0; this.type = 'Time In (am)';
                }else if(this.currentTime2 >= twelve && this.currentTime2 < twelvethirty){
                    this.activebutton = 1; this.type = 'Time Out (am)';
                }else if(this.currentTime2 >= twelvethirty && this.currentTime2 < one){
                    this.activebutton = 2; this.type = 'Time In (pm)';
                }else{
                    this.activebutton = 3; this.type = 'Time Out (pm)';
                }
            },

            swap(type,action){
                this.type = type;
                this.activebutton = action;
                setInterval(() => {
                    this.filter();
                    this.user = '';
                }, 20000); 
            }
        }
    }
</script>
<style>
.nav-pills .nav-link {
    font-weight: bold;
    font-size: 16px;
}
</style>