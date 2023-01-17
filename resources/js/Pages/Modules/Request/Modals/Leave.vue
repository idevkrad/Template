<template>
    <b-modal v-model="showModal" @ok="create($event)" id="leave" size="lg" ok-title="Confirm" title="File Leave" centered>        
        <div class="row p-2">
            <div class="col-md-12">
                <b-form class="customform">
                    <div class="row mt-3">

                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Leave Type: <span v-if="form.errors" v-text="form.errors.type_id" class="haveerror"></span></label>
                                <multiselect 
                                    v-model="leave.type" 
                                    id="ajax" label="name" track-by="id"
                                    placeholder="Select Leave Type" 
                                    open-direction="bottom" 
                                    :options="types"
                                    :searchable="true" 
                                    :allow-empty="false"
                                    :show-labels="false">
                                </multiselect> 
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group" style="margin-top: 5px;">
                                <label>Start: <span v-if="form.errors" v-text="form.errors.start" class="haveerror"></span></label>
                                <date-picker
                                    v-model:value="leave.start"
                                    type="date" format="YYYY-MM-DD"
                                    lang="en"
                                    placeholder="Select Date"
                                    :clearable="false"
                                    >
                                </date-picker>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group" style="margin-top: 5px;">
                                <label>End: <span v-if="form.errors" v-text="form.errors.end" class="haveerror"></span></label>
                                <date-picker
                                    v-model:value="leave.end"
                                    type="date" format="YYYY-MM-DD"
                                    lang="en"
                                    placeholder="Select Date"
                                    :clearable="false"
                                    >
                                </date-picker>
                            </div>
                        </div>

                         <div class="col-md-6">
                            <div class="form-group" style="margin-top: 5px;">
                                <label>Subtype</label>
                                <multiselect 
                                    v-model="subtype" 
                                    id="ajax"
                                    placeholder="Select Detail Type" 
                                    open-direction="bottom" 
                                    :options="subtypes"
                                    :searchable="true" 
                                    :allow-empty="false"
                                    :show-labels="false">
                                </multiselect> 
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group" style="margin-top: 5px;">
                                <label>Detail Type <span v-if="form.errors" v-text="form.errors.detail_id" class="haveerror"></span></label>
                                <multiselect 
                                    v-model="leave.detail" 
                                    id="ajax" label="name" track-by="id"
                                    placeholder="Select Detail Type" 
                                    open-direction="bottom" 
                                    :options="details"
                                    :searchable="true" 
                                    :allow-empty="false"
                                    :show-labels="false">
                                </multiselect> 
                            </div>
                        </div>

                        <div class="col-md-12" style="margin-top: 15px;" v-if="leave.detail.others == 'specify'">
                            <div class="form-group">
                                <label>Specify (Optional): <span v-if="form.errors" v-text="form.errors.specify" class="haveerror"></span></label>
                                <input type="text" class="form-control" v-model="leave.specify" style="text-transform: lowercase;">
                            </div>
                        </div>

                    </div>
                </b-form>
            </div>
        </div>
    </b-modal>
</template>

<script>
    import DatePicker from 'vue-datepicker-next';
    import 'vue-datepicker-next/index.css';
    import Multiselect from '@suadelabs/vue3-multiselect';
    export default {
        props: ['dropdowns'],
        components : { Multiselect, DatePicker },
        data(){
            return {
                currentUrl: window.location.origin,
                errors: [], 
                user: {},
                showModal: false,
                subtypes: ['Vacation/Special Privilege Leave','Sick Leave','Special Leave Benefits for Women','Study Leave','Others'],
                subtype: '',
                request_type: '',
                leave: {
                    type: '',
                    detail: { others : ''},
                    start: '',
                    end: '',
                    specify: ''
                },
                form: {},
                disabledDates: {
                    to: new Date(Date.now() - 8640000)
                }
            }
        },

        watch: {
            subtype() {
                this.leave.detail = {};
            },
        },

        computed: {
            types : function() {
                return this.dropdowns.filter(x => x.classification === 'Leave');
            },
            details : function() {
                return this.dropdowns.filter(x => x.type === this.subtype);
            },
        },

        methods : {
            set(user,type){
                this.user = user;
                this.request_type = type;
                this.showModal = true;
            },

            create(){
                this.form = this.$inertia.form({
                    user_id: this.user,
                    type_id: this.leave.type.id,
                    detail_id: this.leave.detail.id,
                    request_type : this.request_type,
                    status_id: 54,
                    start: (this.leave.start != '') ? new Date(this.leave.start).toLocaleDateString("af-ZA") : '',
                    end: (this.leave.end != '') ? new Date(this.leave.end).toLocaleDateString("af-ZA") : '',
                    specify: (this.leave.specify != '') ? this.leave.specify : 'n/a',
                });

                this.form.post('/application',{
                    preserveScroll: true,
                    onSuccess: (response) => {
                        this.hide();
                    }
                });
            },

            hide(){
                (this.form.hasOwnProperty('reset') ) ? this.form.reset().clearErrors() : this.user = {}; this.leave = {};
                this.showModal = false;
            }
        }
    }
</script>
