
<template>
    <b-modal v-model="showModal" size="lg" title="View Request" centered>
        
        <div class="card-body font-size-13">
            <div class="row" v-if="data.type.id == 69">
                 <div class="col-md-6">
                    <table class="table">
                        <tbody>
                            <tr>
                                <th scope="row">Type:</th>
                                <td>{{ data.type.name }}</td>
                            </tr>
                            <tr>
                                <th scope="col">Date:</th>
                                <td scope="col">{{ data.date }}</td>
                            </tr>
                            <tr>
                                <th scope="row">Status:</th>
                                <td>{{ data.status.name }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="col-md-6">
                    <table class="table">
                        <tbody>
                            <tr>
                                <th scope="col">Type of Leave:</th>
                                <td scope="col">{{ data.data.type.name }}</td>
                            </tr>
                            <tr>
                                <th scope="row">Details of Leave:</th>
                                <td>{{ data.data.detail.name }}</td>
                            </tr>
                             <tr>
                                <th scope="row">Created At:</th>
                                <td>{{ data.created_at }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="col-md-12 mt-n3">
                    <table class="table">
                        <tbody>
                            <tr>
                                <th width="12%">Specify:</th>
                                <td width="88%">{{ data.data.specify }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="row" v-else>
                 <div class="col-md-6">
                    <table class="table">
                        <tbody>
                            <tr>
                                <th scope="row">Type:</th>
                                <td>{{ data.type.name }}</td>
                            </tr>
                            <tr>
                                <th scope="col">Date:</th>
                                <td scope="col">{{ data.date }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="col-md-6">
                    <table class="table">
                        <tbody>
                            <tr>
                                <th scope="col">Status:</th>
                                <td scope="col">{{ data.status.name }}</td>
                            </tr>
                             <tr>
                                <th scope="row">Created At:</th>
                                <td>{{ data.created_at }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="col-md-12 mt-n3">
                    <table class="table">
                        <tbody>
                            <tr>
                                <th width="12%">Purpose:</th>
                                <td width="88%">{{ data.data.purpose }}</td>
                            </tr>
                            <tr v-if="data.status.name == 'Disapproved'">
                                <th width="12%">Due:</th>
                                <td width="88%">{{ data.due }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="col-md-12 mt-1" v-if="status == true">
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="mdi mdi-block-helper me-2"></i>
                        You are required to write the reason here.
                    </div>
                    <div class="form-floating mb-3">
                        <input type="text" v-model="due" class="form-control" id="floatingnameInput" placeholder="Enter Name">
                        <label for="floatingnameInput">Due</label>
                    </div>
                </div>
            </div>
        </div>
        <template v-slot:footer>
            <b-button v-if="status == false && role == 'Employee'" variant="light" @click="showModal = false" block>Close</b-button>
            <b-button v-if="status == true" variant="light" @click="showModal = false" block>Cancel</b-button>
            <b-button v-if="status == true" variant="success" @click="disapprove(data.id)" block>Confirm</b-button>
            <b-button v-if="status == false && (role == 'Assistant Regional Director' || role == 'Regional Director')" variant="danger" @click="status = true" block>Disapproved</b-button>
            <b-button v-if="role == 'Assistant Regional Director' && status == false" variant="info" @click="save(data.id,56)" block>Recommend</b-button>
            <b-button v-if="role == 'Regional Director' && status == false" variant="info" @click="save(data.id,57)" block>Approved</b-button>
        </template>
    </b-modal>
</template>

<script>
    import Multiselect from '@suadelabs/vue3-multiselect';
    export default {
        components : { Multiselect },
        props: ['role'],
        data() {
            return {
                currentUrl: window.location.origin,
                errors: [],
                data: {
                    profile : {},
                    type: {},
                    status: {},
                    leave: {},
                    cto: {},
                    data: {
                        type: {},
                        detail: {},
                        purpose: {}
                    }
                },
                form: {},
                showModal: false,
                status: false,
                due: ''
            }
        },

        methods: {
            set(data) {
                this.data = data;
                this.status = false;
                this.showModal = true;
            },

            save(id,status){
                if(status == 'disapprove'){
                    this.status = true;
                }else{
                    this.form = this.$inertia.form({
                        id: id,
                        status_id: status
                    });

                    this.form.put('/application/update',{
                        preserveScroll: true,
                        onSuccess: (response) => {
                            this.hide();
                        }
                    });
                }
            },

            disapprove(id){
                if(this.due != ''){
                    this.form = this.$inertia.form({
                        id: id,
                        status_id: 58,
                        due: this.due
                    });

                    this.form.put('/application/update',{
                        preserveScroll: true,
                        onSuccess: (response) => {
                            this.hide();
                        }
                    });
                }
            },

            hide(){
                this.showModal = false;
                this.due = '';
            }
        }
    }

</script>
