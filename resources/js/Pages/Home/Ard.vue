<template>
    <div class="row g-3">

        <div class="col-md-8">
            <div class="card">
                <div class="card-body" :style="{ height: (heightProfile+51) + 'px' }"></div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card border shadow-none" style="margin-bottom: 10px;">
                <div class="card-body border-bottom">
                    <span class="float-end badge bg-warning mb-n2">{{ applications }}</span>
                    <div class="d-flex mt-n2 mb-n3">
                        <div class="avatar-xs align-self-center ms-n2 me-1">
                            <div class="avatar-title rounded bg-transparent">
                                <i class="text-warning bx bxs-notepad h4 mt-1"></i>
                            </div>
                        </div>
                        <div class="overflow-hidden mr-auto align-self-center">
                            <h5 class="font-size-14 text-secondary fw-bold mt-1">Requests </h5>
                        </div>
                    </div>
                </div>
                <div class="card-body" :style="{ height: (halfSize-48) + 'px' }">
                    <SimpleBar :style="{ height: (halfSize-85) + 'px' }">
                        <Applications @list-length="handleData($event,'applications')"/>
                    </SimpleBar>
                </div>
            </div>

            <div class="card border shadow-none" style="margin-bottom: 10px;">
                <div class="card-body border-bottom">
                    <div class="d-flex mt-n2 mb-n3">
                        <div class="avatar-xs align-self-center ms-n2 me-1">
                            <div class="avatar-title rounded bg-transparent">
                                <i class="text-danger bx bx-trip h4 mt-1"></i>
                            </div>
                        </div>
                        <div class="overflow-hidden mr-auto align-self-center">
                            <h5 class="font-size-14 text-secondary fw-bold mt-1">Travels</h5>
                        </div>
                    </div>
                </div>
                <div class="card-body" :style="{ height: (halfSize-10) + 'px' }">
                    <SimpleBar :style="{ height: (halfSize-85) + 'px' }">
                        
                    </SimpleBar>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
    import { SimpleBar } from 'simplebar-vue3';
    import Applications from '../Modules/Request/Admin.vue';
    export default {
        inject: ['count3', 'heightProfile'],
        components : { SimpleBar, Applications},
        props: ['auth'],
        data() {
            return {
                currentUrl: window.location.origin,
                halfSize: this.heightProfile/2,
                applications: [],
                travels: [],
                documents: []
            }
        },

        methods : {
            handleData(count,type){
                switch(type){
                    case 'applications':
                        this.applications = count;
                    break;
                    case 'travels':
                        this.travels = count;
                    break;
                    case 'documents':
                        this.documents = count;
                    break;
                }
            }
        }
    }

</script>
