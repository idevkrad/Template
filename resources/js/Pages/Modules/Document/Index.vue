<template>
    <Head title="Documents"></Head>
    <div class="card">
        <div class="card-body border-bottom py-3">
            <div class="d-flex align-items-center mt-n1 mb-n1">
                <ul class="list-unstyled hstack gap-3 mb-0 font-size-11 flex-grow-1">
                    <li class="fw-bold font-size-13">Documents</li>
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
                            <th style="width: 15%;" class="text-center">Routing Slip</th>
                            <th style="width: 15%;" class="text-center">Subject</th>
                            <th style="width: 15%;" class="text-center">Type</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="list in lists" v-bind:key="list.id">
                            <!-- <td>{{page + (index+1)}}</td> -->
                            <td>
                                <div class="align-self-center avatar-xs mr-3">
                                    <div :class="'avatar-title bg-light rounded-circle font-size-16 '+list.is_completed.color">
                                        <i :class="'bx '+list.is_completed.icon"></i>
                                    </div>
                                </div>
                            </td>
                            <td class="text-center text-success fw-bold">{{ list.route_slip }}</td>
                            <td class="text-center text-muted">{{ list.subject }}</td>
                            <td class="text-center">{{ list.type.name }}</td>
                            <td class="text-end">
                                <button @click="view(list)" class="btn btn-sm btn-primary mt-1" type="button">
                                    View
                                </button>
                            </td>
                        </tr>
                        <tr v-if="lists.length == 0">
                            <td class="text-center" colspan="5">
                                No document found.
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <View ref="view"/>
</template>
<script>
import View from './Modals/View.vue';
export default {
    inject:['count3', 'heightProfile'],
    props: ['dropdowns','auth'],
    components : { View },
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
            page_url = page_url || '/documents';
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

        view(list){
            if(!list.completed){
                axios.get(this.currentUrl + '/documents/'+list.id, { params: { actions: list.actions }})
                .then(response => {
                    this.$refs.view.show(list);
                })
                .catch(err => console.log(err));
            }
        },

        message(data){
            console.log(data);
        }
    }
}
</script>