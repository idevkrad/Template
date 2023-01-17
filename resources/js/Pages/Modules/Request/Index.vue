<template>
    <Head title="Requests"></Head>
    <div class="card">
        <div class="card-body border-bottom py-3">
            <div class="d-flex align-items-center mt-n1 mb-n1">
                <ul class="list-unstyled hstack gap-3 mb-0 font-size-11 flex-grow-1">
                    <li class="fw-bold font-size-13">Requests</li>
                </ul>
                <div class="hstack gap-2">
                    <div class="input-group input-group-sm">
                    <select v-model="status" @change="fetch()" class="form-select form-select-sm" style="width: 150px;">
                        <option value="" selected>All Status</option>
                        <option :value="status.id" v-for="(status,index) in statuses" v-bind:key="index">{{status.name}}</option>
                    </select>
                    <label class="input-group-text">Status</label>
                    <select v-model="request_type" @change="fetch()" class="form-select form-select-sm" style="width: 200px;">
                        <option value="" selected>All Type</option>
                        <option :value="type.id" v-for="(type,index) in types" v-bind:key="index">{{type.name}}</option>
                    </select>
                    <label class="input-group-text">Type</label>
                </div>
                    <button type="button" @click="generate($page.props.auth.data.id)" class="btn btn-primary btn-sm"><i class="bx bxs-plus-circle align-baseline"></i></button>
                    <!-- <button @click="generate($page.props.auth.data.id)" type="button" v-b-tooltip.hover title="Print DTR" class="btn btn-primary btn-sm"><i class="bx bx-printer align-baseline"></i></button> -->
                </div>
            </div>
        </div>
        <div class="card-body font-size-12" :style="{ height: heightProfile + 'px' }">
            <div class="table-responsive">
                <table class="table table-centered table-nowrap align-middle">
                    <thead class="thead-light">
                        <tr class="font-size-11">
                            <th style="width: 1%;" class="text-center">#</th>
                            <th style="width: 15%;" class="text-center">Code</th>
                            <th style="width: 15%;" class="text-center">Type</th>
                            <th style="width: 15%;" class="text-center">Date</th>
                            <th style="width: 10%;" class="text-center">Created At</th>
                            <th style="width: 10%;" class="text-center">Status</th>
                            <th style="width: 5%;" class="text-end"></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(list,index) in lists" v-bind:key="list.id">
                            <td>{{page + (index+1)}}</td>
                            <td class="text-center fw-bold">{{ list.code }}</td>
                            <td class="text-center">{{ list.type.name }}</td>
                            <td class="text-center">{{ list.date }}</td>
                            <td class="text-center">{{ list.created_at }}</td>
                            <td class="text-center">
                                 <span :class="'badge bg-'+list.status.color">{{list.status.name}}</span>
                            </td>
                            <td class="text-end">
                               <button type="button" @click="view(list)" class="btn btn-primary btn-sm waves-effect waves-light">View Details</button>
                            </td>
                        </tr>
                        <tr v-if="lists.length == 0">
                            <td class="text-center" colspan="5">
                                No application found.
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <Request :dropdowns="dropdowns" ref="request"/>
    <View :role="auth.data.role" ref="view"/>
</template>
<script>
import View from './Modals/View.vue';
import Request from './Modals/Request.vue'
export default {
    inject:['count3', 'heightProfile'],
    props: ['dropdowns','auth'],
    components : { Request, View },
    data(){
        return {
            currentUrl: window.location.origin,
            lists: [],
            meta: {},
            links: {},
            keyword: '',
            request_type: '',
            status: '',
            page: ''
        }
    },

    watch: {
        datares: {
            deep: true,
            handler(val = null) {
                if(val != null && val !== ''){
                    this.message(val);
                }
            },
        }
    },

    computed: {
        datares() {
            return this.$page.props.flash.datares;
        },
        types : function() {
            return this.dropdowns.filter(x => x.classification === 'Request Type');
        },
        statuses : function() {
            return this.dropdowns.filter(x => x.classification === 'Status');
        },
    },

    created(){
        this.fetch();
    },

    methods : {
        fetch(page_url){
            page_url = page_url || '/application';
            axios.get(page_url,{
                params : {
                    keyword : this.keyword,
                    status: this.status,
                    request_type: this.request_type,
                    count: this.count3,
                    type: 'solo'
                }
            })
            .then(response => {
                if(response){
                    this.lists = response.data.data;                    
                    this.meta = response.data.meta;
                    this.links = response.data.links;
                    this.page = this.meta.per_page*(this.meta.current_page - 1);
                }
            })
            .catch(err => console.log(err));
        },

        generate(id){
            this.$refs.request.set(id);
        },

        view(data){
            this.$refs.view.set(data);
        },

        message(list){
            this.fetch();
        },
    }
}
</script>