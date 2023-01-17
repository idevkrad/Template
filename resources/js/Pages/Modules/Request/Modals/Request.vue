<template>
    <b-modal v-model="showModal" @ok="create($event)" id="leave" size="lg" ok-title="Confirm" title="File Request" centered>        
        <div class="row p-2">
            <div class="col-md-12">
                <b-form class="customform">

                    <div class="row mt-3">
                        <div class="col-xl-12 mb-3">
                            <p class="text-muted text-center">Please choose a request</p>
                        </div>
                        <div class="col-md-8 mx-auto">
                            <div class="row">
                                <div class="col-xl-6 text-center" v-for="(request,index) in requests" v-bind:key="index">
                                    <label class="card-radio-label mb-3">
                                        <input type="radio" v-model="request_type" :value="request" class="card-radio-input" v-on:change="radio">
                                        <div class="card-radio">
                                            <i :class="'bx '+request.others+' font-size-24 text-primary align-middle me-2'"></i>
                                            <span>{{ request.name }}</span>
                                        </div>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </b-form>
            </div>
        </div>
        <template v-slot:footer>
            <b-button variant="light" @click="showModal = false" block>Close</b-button>
        </template>
    </b-modal>
    <Leave :dropdowns="dropdowns" ref="leave"/>
    <Cto :dropdowns="dropdowns" ref="cto"/>
</template>

<script>
    import Leave from './Leave.vue';
    import Cto from './Cto.vue';
    export default {
        props: ['dropdowns'],
        components : { Leave, Cto },
        data(){
            return {
                currentUrl: window.location.origin,
                errors: [], 
                user: {},
                showModal: false,
                request_type: '',
                id: ''
            }
        },

        computed: {
            requests : function() {
                return this.dropdowns.filter(x => x.classification === 'Request Type');
            },
        },

        methods : {
            radio(){
                this.showModal = false;
                if(this.request_type.name == 'Leave Request'){
                    this.$refs.leave.set(this.id,this.request_type.id);
                }else{
                    this.$refs.cto.set(this.id,this.request_type.id);
                }
                this.request_type = '';
            },

            set(id){
                this.id = id;
                this.showModal = true;
            },

        }
    }
</script>
