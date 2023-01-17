<template>
    <b-modal v-model="showModal" @ok="create($event)" id="create" size="xl" title="New Document" no-close-on-backdrop centered>
        <div class="row p-2">
            <div class="col-md-12">
                <b-form class="customform">
                    <div class="row mt-1 mb-2">
                        <div class="col-md-8">
                            <div class="form-group">
                                <label>Subject: <span v-if="form.errors" v-text="form.errors.subject" class="haveerror"></span></label>
                                <input type="text" class="form-control" v-model="document.subject" style="text-transform: capitalize;">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Type: <span v-if="form.errors" v-text="form.errors.type_id" class="haveerror"></span></label>
                                <Multiselect 
                                v-model="document.type_id" 
                                :options="types"
                                :allow-empty="false"
                                :show-labels="false"
                                label="name" track-by="id"
                                placeholder="Select Type"/>
                            </div>
                        </div>

                        <div class="col-md-4 mt-3">
                            <div class="form-group">
                                <label>Document Date: <span v-if="form.errors" v-text="form.errors.document" class="haveerror"></span></label>
                                <date-picker
                                    v-model:value="document.document"
                                    type="date" format="YYYY-MM-DD"
                                    lang="en"
                                    placeholder="Select Date"
                                    :clearable="false"
                                    >
                                </date-picker>
                            </div>
                        </div>
                        <div class="col-md-4 mt-3">
                            <div class="form-group">
                                <label>Sent Date: <span v-if="form.errors" v-text="form.errors.received" class="haveerror"></span></label>
                                <date-picker
                                    v-model:value="document.received"
                                    type="date" format="YYYY-MM-DD"
                                    lang="en"
                                    placeholder="Select Date"
                                    :clearable="false"
                                    >
                                </date-picker>
                            </div>
                        </div>
                        <div class="col-md-4 mt-3">
                            <div class="form-group">
                                <label>Remarks: <span v-if="form.errors" v-text="form.errors.remarks" class="haveerror"></span></label>
                                <input type="text" class="form-control" v-model="document.remarks" style="text-transform: capitalize;">
                            </div>
                        </div>

                        <div class="col-md-4 mt-3">
                            <div class="form-group">
                                <label>Addressee: <span v-if="form.errors" v-text="form.errors.sender_id" class="haveerror"></span></label>
                                <Multiselect 
                                id="sender"
                                @search-change="asyncSender"
                                v-model="document.sender_id" 
                                :options="senders"
                                :allow-empty="false"
                                :show-labels="false"
                                @tag="addSender"
                                :taggable="true"
                                label="name" track-by="id"
                                tag-placeholder="press enter to add new" 
                                placeholder="Search or add addressee"/>
                            </div>
                        </div>
                        <div class="col-md-4 mt-3">
                            <div class="form-group">
                                <label>Company: <span v-if="form.errors" v-text="form.errors.company_id" class="haveerror"></span></label>
                                <Multiselect 
                                id="company"
                                @search-change="asyncCompany"
                                v-model="document.company_id" 
                                :options="companies"
                                :allow-empty="false"
                                :show-labels="false"
                                @tag="addCompany"
                                :taggable="true"
                                label="name" track-by="id"
                                tag-placeholder="press enter to add new" 
                                placeholder="Search or add company"/>
                            </div>
                        </div>
                        <div class="col-md-4 mt-4">
                             <input class="mt-2 mb-2" multiple type="file" @input="document.attachments = $event.target.files">
                             <span v-if="form.errors" v-text="form.errors.files" class="haveerror"></span>
                        </div>
                        <div class="col-md-12 mt-3">
                            <div class="form-group">
                                <label>Action Needed <span v-if="form.errors" v-text="form.errors.actions" class="haveerror"></span></label>
                                <Multiselect 
                                v-model="document.actions" 
                                :options="actions"
                                :allow-empty="false"
                                :show-labels="false"
                                :multiple="true"
                                label="name" track-by="id"
                                placeholder="Select Action"/>
                            </div>
                        </div>
                         <div class="col-md-12 mt-3">
                            <div class="form-group">
                                <label>Keywords <span v-if="form.errors" v-text="form.errors.keywords" class="haveerror"></span></label>
                                <Multiselect 
                                ref="ky"
                                id="keywords"
                                v-model="keywords"
                                :options="options"
                                :show-labels="false" 
                                :allow-empty="false"
                                :multiple="true"
                                :taggable="true" @tag="addKeywords"
                                label="data" track-by="data"
                                tag-placeholder="press enter to separate" 
                                placeholder="Enter Keywords"/>
                            </div>
                        </div>

                    </div>
                </b-form>
            </div>
        </div>
        <template v-slot:footer>
            <b-button @click="hide()" variant="secondary" block>Cancel</b-button>
            <b-button @click="create('ok')" variant="primary" :disabled="form.processing" block>Save</b-button>
        </template>
    </b-modal>
</template>

<script>
    import DatePicker from 'vue-datepicker-next';
    import 'vue-datepicker-next/index.css';
    import Multiselect from '@suadelabs/vue3-multiselect';
    export default {
        props: ['dropdowns'],
        components: { Multiselect, DatePicker },
        data() {
            return {
                currentUrl: window.location.origin,
                document: {
                    id: '',
                    subject: '',
                    type_id: '',
                    received: '',
                    document: '',
                    remarks: '',
                    sender_id: '',
                    company_id: '',
                    actions: '',
                    attachments: ''
                },
                companies: [],
                senders: [],
                keywords: [],
                options: [],
                attachments: [],
                errors: [],
                form: {},
                editable: false,
                showModal: false,
            }
        },
        computed: {
            types : function() {
                return this.dropdowns.filter(x => x.classification === 'Document Type');
            },
            actions : function() {
                return this.dropdowns.filter(x => x.classification === 'Document Action');
            },
        },
        methods: {
            create(){
                var result = (this.document.actions.length > 0) ? this.document.actions.map(function(a) {return a.id;}) : '';
                this.form = this.$inertia.form({
                    subject: this.document.subject,
                    type_id: (this.document.type_id != undefined) ? this.document.type_id.id : '',
                    sender_id: (this.document.sender_id != undefined) ? this.document.sender_id.id : '',
                    company_id: (this.document.company_id != undefined) ? this.document.company_id.id : '',
                    document: (this.document.document) ? new Date(this.document.document).toLocaleDateString("af-ZA") : '',
                    received: (this.document.received) ? new Date(this.document.received).toLocaleDateString("af-ZA") : '',
                    remarks: this.document.remarks,
                    keywords: (this.keywords.length == 0) ? '' : JSON.stringify(this.keywords),
                    actions: (this.document.actions.length == 0) ? '' : JSON.stringify(result),
                    files: this.document.attachments,
                    is_incoming : 1
                });

                this.form.post('/documents',{
                    preserveScroll: true,
                    forceFormData: true,
                    onSuccess: (response) => {
                        this.hide();
                    },
                    onError: () => {
                        console.log('Contact Administrator'); //this.$page.props.errors
                    }
                });
            },

            show() {
                this.showModal = true;
            },

            addCompany(data) {
                axios.get(this.currentUrl + '/documents/keywords', {params : { name: data,is_company: 1}})
                .then(response => {this.document.company_id = response.data; this.companies.push(response.data);})
                .catch(err => console.log(err)); 
            },

            addSender(data) {
                axios.get(this.currentUrl + '/documents/keywords', {params : {name: data,is_company: 0}})
                .then(response => {this.document.sender_id = response.data; this.senders.push(response.data);})
                .catch(err => console.log(err)); 
            },

            asyncCompany(value) {
                axios.get(this.currentUrl + '/documents/search', {params : {word: value, is_company: 1}})
                .then(response => {this.companies = response.data })
                .catch(err => console.log(err));
            },

            asyncSender(value) {
                axios.get(this.currentUrl + '/documents/search', {params : {word: value, is_company: 0}})
                .then(response => {this.senders = response.data})
                .catch(err => console.log(err));
            },

            addKeywords(data) {
                const tag = {
                    data: data
                };
                this.keywords.push(tag);
                this.$refs.ky.$el.focus();
            },

            hide(){
                this.form.reset()
                this.showModal = false;
            }
        }
    }
</script>
