<template>
    <b-modal v-model="showModal" @ok="create($event)" id="leave" size="lg" ok-title="Confirm" title="File CTO" centered>        
        <div class="row p-2">
            <div class="col-md-12">
                <b-form class="customform">
                    <div class="row mt-1 mb-2">

                        <div class="col-md-6">
                            <div class="form-group" style="margin-top: 5px;">
                                <label>Start: <span v-if="form.errors" v-text="form.errors.start" class="haveerror"></span></label>
                                <date-picker
                                    v-model:value="cto.start"
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
                                    v-model:value="cto.end"
                                    type="date" format="YYYY-MM-DD"
                                    lang="en"
                                    placeholder="Select Date"
                                    :clearable="false"
                                    >
                                </date-picker>
                            </div>
                        </div>

                        <div class="col-md-12" style="margin-top: 15px;">
                            <div class="form-group">
                                <label>Purpose: <span v-if="form.errors" v-text="form.errors.purpose" class="haveerror"></span></label>
                                <textarea v-model="cto.purpose" class="form-control" maxlength="225" rows="2" placeholder="Purpose / Target Deliverables"></textarea>
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
    export default {
        components : { DatePicker },
        data(){
            return {
                currentUrl: window.location.origin,
                errors: [], 
                user: {},
                showModal: false,
                cto: {
                    start: '',
                    end: '',
                    purpose: ''
                },
                form: {},
                request_type: ''
            }
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
                    status_id: 54,
                    request_type : this.request_type,
                    start: (this.cto.start != '') ? new Date(this.cto.start).toLocaleDateString("af-ZA") : '',
                    end: (this.cto.end != '') ? new Date(this.cto.end).toLocaleDateString("af-ZA") : '',
                    purpose: (this.cto.purpose != '') ? this.cto.purpose : 'n/a',
                });

                this.form.post('/application',{
                    preserveScroll: true,
                    onSuccess: (response) => {
                        this.hide();
                    }
                });
            },

            hide(){
                (this.form.hasOwnProperty('reset') ) ? this.form.reset().clearErrors() : this.user = {}; this.cto = {};
                this.showModal = false;
            }
        }
    }
</script>
