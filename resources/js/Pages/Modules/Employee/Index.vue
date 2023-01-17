<template>
    <Head title="Employees"></Head>
    <div class="card">
        <div class="card-body border-bottom py-3">
            <div class="d-flex align-items-center mt-n1 mb-n1">
                <ul class="list-unstyled hstack gap-3 mb-0 font-size-11 flex-grow-1">
                    <li class="fw-bold font-size-13">Requests</li>
                </ul>
                <div class="hstack gap-2">
                    <ul class="list-inline user-chat-nav text-end mb-n2 mt-n2 dropdown">
                        <Pagination v-if="meta" @fetch="fetch" :links="links" :pagination="meta"/>
                    </ul>
                </div>
            </div>
        </div>
        <div class="card-body font-size-12" :style="{ height: heightProfile + 'px' }">

            <div class="d-flex align-items-center mt-n2 mb-2">
                <div class="input-group input-group-sm me-2">
                    <input type="text" class="form-control" placeholder="Search..." style="width: 20%;" v-model="keyword"/>
                    <select v-model="office" @change="fetch()" class="form-select form-select-sm" style="width: 15%;">
                        <option value="" selected>All Office</option>
                        <option :value="office.id" v-for="(office,index) in offices" v-bind:key="index">{{office.name}}</option>
                    </select>
                    <select v-model="department" @change="fetch()" class="form-select form-select-sm" style="width: 20%;">
                        <option value="" selected>All Department</option>
                        <option :value="department.id" v-for="(department,index) in departments" v-bind:key="index">{{department.name}}</option>
                    </select>
                    <label class="input-group-text">Office</label>
                    <select v-model="status" @change="fetch()" class="form-select form-select-sm" style="width: 10%;">
                        <option value="" selected>All Status</option>
                        <option :value="status.id" v-for="(status,index) in types" v-bind:key="index">{{status.name}}</option>
                    </select>
                    <label class="input-group-text">Type</label>
                </div>
                <button type="button" @click="create()" class="btn btn-primary btn-sm"><i class="bx bxs-plus-circle align-baseline"></i></button>
            </div>

            <div class="table-responsive">
                <table class="table table-centered table-nowrap align-middle">
                    <thead class="thead-light">
                        <tr class="font-size-11">
                            <th style="width: 2%;"></th>
                            <th style="width: 30%;">Name</th>
                            <th style="width: 20%;" class="text-center">Position</th>
                            <th style="width: 25%;" class="text-center">Contact Information</th>
                            <th style="width: 13;" class="text-center">Status</th>
                            <th style="width: 10%;" class="text-end"></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="list in lists" v-bind:key="list.id">
                            <td>
                                <div class="avatar-xs" v-if="list.avatar == 'avatar.jpg'">
                                    <span class="avatar-title rounded-circle">{{list.lastname.charAt(0)}}</span>
                                </div>
                                <div v-else>
                                    <img class="rounded-circle avatar-xs" :src="currentUrl+'/images/avatars/'+list.avatar" alt="">
                                </div>
                            </td>
                            <td>
                                <h5 class="font-size-13 mb-0 text-dark">{{list.lastname}}, {{list.firstname}} {{list.middlename}}</h5>
                                <p class="font-size-12 text-muted mb-0">{{list.department.name }}</p>
                            </td>
                            <td class="text-center">
                                <h5 class="font-size-12 mb-0 text-dark">{{list.position.name}}</h5>
                                <p class="font-size-12 text-muted mb-0 ">{{list.status.name}}</p>
                            </td>
                            <td class="text-center">
                                <h5 class="font-size-12 mb-0 text-dark">{{list.email}}</h5>
                                <p class="font-size-12 text-muted mb-0">{{list.mobile}}</p>
                            </td>
                            <td class="text-center">
                                <span v-if="list.is_active == 1" class="badge bg-success fs-11">Active</span>
                                <span v-else class="badge bg-danger fs-11">Inactive</span>
                            </td>
                            <td class="text-end">
                                <a v-b-tooltip.hover title="Employee Activation" class="me-3 " @click="update(list)">
                                    <i v-bind:class="(list.is_active == 1) ? 'text-success bx bx-lock-open' : 'text-dark bx bxs-lock'"></i>
                                </a>
                                <a v-b-tooltip.hover title="Employee Roles" @click="role(list.roles,list.id)" class="me-3 text-info"><i class='bx bxs-user-detail'></i></a>
                                <a v-b-tooltip.hover title="Employee Group" @click="group(list.groups,list.id)" class="me-3 text-info"><i class='bx bx-building-house'></i></a>
                                <a v-b-tooltip.hover title="Edit Employee" class="me-3 text-warning" @click="edit(list)"><i class='bx bx-edit-alt' ></i></a>
                                <a class="text-info" v-b-tooltip.hover title="Verify Employee"  @click="verify(list)"><i class='bx bx-mail-send'></i></a>
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
    <Update @info="message" ref="update"/>
    <Create :dropdowns="dropdowns" :positions="positions" @info="message" ref="create"/>
    <Verify ref="verify"/>
    <Group :groups="groups" ref="group"/>
    <Role :roles="roles" ref="role"/>
</template>
<script>
import Update from './Modals/Update.vue';
import Create from './Modals/Create.vue';
import Verify from './Modals/Verify.vue';
import Group from './Modals/Group.vue';
import Role from './Modals/Role.vue';
import Pagination from "@/Shared/Pagination.vue";
import _ from 'lodash';
export default {
    components : { Pagination, Update, Create, Verify, Group, Role },
    inject:['count', 'heightProfile'],
    props: ['dropdowns', 'positions', 'groups', 'roles'],
    data(){
        return {
            currentUrl: window.location.origin,
            lists: [],
            meta: {},
            links: {},
            type: '',
            office: '',
            status: '',
            department: '',
            keyword: ''
        }
    },

    created(){
        this.fetch();
    },

    watch: {
        datares: {
            deep: true,
            handler(val = null) {
                if(val != null && val !== ''){
                    this.message(val);
                }
            },
        },
        keyword(newVal){
            this.checkSearchStr(newVal)
        }
    },

    computed: {
        datares() {
            return this.$page.props.flash.datares;
        },
        types : function() {
            return this.dropdowns.filter(x => x.type === 'Employment Status');
        },
        offices : function() {
            return this.dropdowns.filter(x => x.classification === 'Office');
        },
        departments : function() {
            return this.dropdowns.filter(x => x.type === 'Department');
        },
        positions : function() {
            return this.dropdowns.filter(x => x.classification === 'Office');
        },
    },

    methods : {
        checkSearchStr: _.debounce(function(string) {
            this.fetch();
        }, 300),

        fetch(page_url){
            page_url = page_url || '/employees';
            axios.get(page_url,{
                params : {
                    keyword : this.keyword,
                    office: this.office,
                    status: this.status,
                    department: this.department,
                    count: this.count,
                    type: 'lists'
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

        update(list){
            this.$refs.update.set(list);
            this.editable = true;
        },

        create(){
            this.$refs.create.show();
            this.editable = false;
        },

        edit(list){
            this.$refs.create.edit(list);
            this.editable = true;
        },

        group(groups,user){
            this.$refs.group.set(groups,user);
            this.editable = true;
        },

        role(roles,user){
            this.$refs.role.set(roles,user);
            this.editable = true;
        },

        verify(list){
            this.$refs.verify.set(list);
        },

        message(list){
            if(!this.editable){
                this.lists.unshift(list);
            }else{
                let index = this.lists.findIndex(u => u.id === list.id);
                this.lists[index] = list;
            }
        },
    }   
}
</script>