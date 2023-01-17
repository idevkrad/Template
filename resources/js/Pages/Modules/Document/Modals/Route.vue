<template>
    <b-modal v-model="showModal" @ok="create($event)"  size="lg" title="Route Document" no-close-on-backdrop centered>
        <div class="row p-2">
            <div class="col-md-12">
               <b-form class="customform">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label>Route Mode: <span v-if="form.errors" v-text="form.errors.option" class="haveerror"></span></label>
                            <Multiselect 
                            v-model="option" 
                            :options="options"
                            :allow-empty="false"
                            :show-labels="false"
                            @input="onChange(option)"
                            placeholder="Select Mode"/>
                        </div>
                    </div>
                    <div class="col-md-12 mt-4" v-if="option == 'By Group'">
                        <div class="form-group">
                            <label>Groups: <span v-if="form.errors" v-text="form.errors.groups" class="haveerror"></span></label>
                            <Multiselect 
                            v-model="group" 
                            :options="groups" 
                            :show-labels="false"
                            label="name" track-by="id" 
                            :allow-empty="false"
                            :multiple="true"
                            placeholder="Select Group">
                            </Multiselect>
                        </div>
                    </div>
                    <div class="col-md-12 mt-4" v-if="option == 'Individually'">
                        <div class="form-group">
                            <label>Individuals: <span v-if="form.errors" v-text="form.errors.individuals" class="haveerror"></span></label>
                            <multiselect 
                            ref="indi"
                            v-model="individuals" 
                            :options="employees" 
                            :show-labels="false"
                            track-by="id" label="name"
                            :allow-empty="false"
                            :multiple="true"
                            id="ajax" @search-change="asyncFind" 
                            placeholder="Select Individually">
                            </multiselect>
                        </div>
                    </div>
               </b-form>
            </div>
        </div>
        <template v-slot:footer>
            <b-button @click="hide()" variant="secondary" block>Cancel</b-button>
            <b-button @click="create('ok')" variant="primary" block>Route</b-button>
        </template>
    </b-modal>
</template>

<script>
    import Multiselect from '@suadelabs/vue3-multiselect';
    export default {
        props: ['dropdowns'],
        components : { Multiselect },
        data() {
            return {
                currentUrl: window.location.origin,
                showModal: false,
                errors: [],
                form:{},
                document: '',
                options: ['All Employees','By Group','Individually'],
                option: '',
                groups: [],
                group: [],
                employees: [],
                individuals: []
            }
        },

        computed: {
            groups : function() {
                return this.dropdowns.filter(x => x.classification === 'Group');
            },
        },
      
        methods: {
            asyncFind(value) {
                if(value != ''){
                    axios.get(this.currentUrl + '/employees', {
                        params: { keyword: value, type: 'individual' }
                    })
                    .then(response => {
                        this.employees = response.data.data;
                    })
                    .catch(err => console.log(err));
                }
            },

            onChange(){
                alert('wew');
            },

            create(){
                // let data = new FormData();
                // data.append('id', (this.document.id != undefined) ? this.document.id : '');
                // data.append('option', this.option);
                // data.append('_method','PUT');
                
                // switch(this.option){
                //     case 'By Group':
                //         var result = this.group.map(function(a) {return a.id;});
                //         data.append('groups', (this.group.length > 0) ? JSON.stringify(result) : '');
                //     break;
                //     case 'Individually':
                //         var result = this.individuals.map(function(a) {return a.id;});
                //         data.append('individuals', (this.individuals.length > 0) ? JSON.stringify(result) : '');
                //     break;
                // }

                // axios.post(this.currentUrl + '/documents/route', data)
                // .then(response => {
                //     this.hide();
                // })
                // .catch(error => {
                //     if (error.response.status == 422) {
                //         this.errors = error.response.data.errors;
                //     }
                // });

                // this.form = this.$inertia.form({data});
        var result, result2;
                switch(this.option){
                    case 'By Group':
                        result = this.group.map(function(a) {return a.id;});
                        result = (this.group.length > 0) ? JSON.stringify(result) : '';
                    break;
                    case 'Individually':
                        result2 = this.individuals.map(function(a) {return a.id;});
                        result2 = (this.individuals.length > 0) ? JSON.stringify(result2) : '';
                    break;
                }

                 this.form = this.$inertia.form({
                    id :  (this.document.id != undefined) ? this.document.id : '',
                    option: this.option,
                    individuals: result2,
                    groups: result
                 });

                 this.form.put('/documents/route',{
                    preserveScroll: true,
                    onSuccess: (response) => {
                        this.hide();
                    },
                    onError: () => {
                        console.log('Contact Administrator'); //this.$page.props.errors
                    }
                });
            },

            // haha(){
            //     this.employees = [];
            //     this.$refs.indi.$el.focus();
            // },

            hide(){
                this.showModal = false;
            },
            
            show(data) {
                this.document = data;
                this.showModal = true;
            }
        }
    }
</script>
