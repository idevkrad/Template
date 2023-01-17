<template>
    <b-modal v-model="showModal" @ok="generate($event)" id="generate" ok-title="Generate" title="Generate DTR" centered>        
        <div class="row p-2">
            <div class="col-md-12">
                <div role="alert" aria-live="polite" aria-atomic="true" class="alert alert-warning">You can print current DTR by leaving it blank</div>

                    <multiselect 
                        v-model="month" 
                        id="ajax"
                        placeholder="Select Month" 
                        open-direction="bottom" 
                        :options="months"
                        :searchable="true" 
                        :allow-empty="true"
                        :show-labels="false">
                    </multiselect> 
            </div>
        </div>
    </b-modal>
</template>

<script>
    import Multiselect from '@suadelabs/vue3-multiselect';
    export default {
        components : { Multiselect },
        data(){
            return {
                currentUrl: window.location.origin,
                errors: [], 
                user: {},
                showModal: false,
                month: '',
                year: '',
                months: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
            }
        },

        methods : {
            set(user){
                this.user = user;
                this.showModal = true;
            },

            generate(){
                let info = {
                    'month' : this.month,
                    'id' : this.user,
                    'year' : this.year
                };
                window.open(this.currentUrl + '/dtr/'+JSON.stringify(info)+'/edit');
            }
        }
    }
</script>
