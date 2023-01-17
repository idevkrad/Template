<template>

    <Head title="Documents"></Head>
    <div class="card">
        <div class="card-body border-bottom py-3">
            <div class="d-flex align-items-center mt-n1 mb-n1">
                <ul class="list-unstyled hstack gap-3 mb-0 font-size-11 flex-grow-1">
                    <li class="fw-bold font-size-13">Documents</li>
                </ul>
                <div class="hstack gap-2">
                    <ul class="list-inline user-chat-nav text-end mb-n2 mt-n2 dropdown">
                        <Pagination v-if="meta" @fetch="fetch" :links="links" :pagination="meta" />
                    </ul>
                </div>
            </div>
        </div>
        <div class="card-body font-size-12" :style="{ height: heightProfile + 'px' }">
            <div class="d-flex align-items-center mt-n2 mb-2">
                <div class="input-group input-group-sm me-2">
                    <input type="text" class="form-control" placeholder="Search..." style="width: 40%;"
                        v-model="keyword" />
                    <label class="input-group-text">Sender</label>
                    <input type="text" class="form-control" placeholder="Enter Sender" style="width: 15%;"
                        v-model="sender" />
                    <label class="input-group-text">Company</label>
                    <input type="text" class="form-control" placeholder="Enter Company" style="width: 15%;"
                        v-model="company" />
                    <label class="input-group-text">Type</label>
                    <select v-model="doc_type" @change="fetch()" class="form-select form-select-sm" style="width: 15%;">
                        <option value="" selected>All Type</option>
                        <option :value="type.id" v-for="(type,index) in types" v-bind:key="index">{{type.name}}</option>
                    </select>
                </div>
                <button type="button" @click="create()" class="btn btn-primary btn-sm"><i
                        class="bx bxs-plus-circle align-baseline"></i></button>
            </div>
            <div class="table-responsive">
                <table class="table table-centered table-nowrap align-middle">
                    <thead class="thead-light">
                        <tr class="font-size-11">
                            <th style="width: 1%;" class="text-center">#</th>
                            <th style="width: 15%;" class="text-center">Routing Slip</th>
                            <th style="width: 15%;" class="text-center">Subject</th>
                            <th style="width: 15%;" class="text-center">Type</th>
                            <th style="width: 5%;" class="text-end"></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="list in lists" v-bind:key="list.id">
                            <!-- <td>{{page + (index+1)}}</td> -->
                            <td>
                                <i v-if="list.is_completed" class="bx bx-sm bxs-check-circle text-success" v-b-tooltip.hover title="Document Completed"></i>
                                <i v-else v-b-tooltip.hover :title="[(list.is_status == 0 ? 'No Employee was tag' : 'Not All Employee complied')]" v-bind:class="[(list.is_status == 0 ? 'bx bx-sm bxs-info-circle text-danger bx-flashing' : 'bx bx-sm bxs-info-circle text-warning')]"></i>
                            </td>
                            <td class="text-center text-success fw-bold">{{ list.route_slip }}</td>
                            <td class="text-center text-muted">{{ list.subject }}</td>
                            <td class="text-center">{{ list.type.name }}</td>
                            <td class="text-end font-size-14">
                                <a @click="route(list)" class="me-3 text-success"><i class="bx bxs-purchase-tag"></i></a> 
                                <a @click="view(list)" class="me-3 text-info"><i class="bx bx-show"></i></a> 
                                <a class="me-3 text-warning"><i class="bx bx-edit-alt"></i></a> 
                                <a class="text-danger"><i class="bx bx-trash"></i></a>
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
    <Create :dropdowns="dropdowns" ref="create" />
    <View :dropdowns="dropdowns" ref="view" />
    <Route :dropdowns="dropdowns" ref="route" />
</template>
<script>
    import Route from './Modals/Route.vue';
    import Create from './Modals/Create.vue';
    import View from './Modals/View.vue';
    import Pagination from "@/Shared/Pagination.vue";
    export default {
        inject: ['count', 'heightProfile'],
        props: ['dropdowns', 'auth'],
        components: { Create, Pagination, View, Route },
        data() {
            return {
                currentUrl: window.location.origin,
                lists: [],
                meta: {},
                links: {},
                keyword: '',
                sender: '',
                company: '',
                doc_type: '',
                page: '',
                button: ''
            }
        },

        watch: {
            datares: {
                deep: true,
                handler(val = null) {
                    if (val != null && val !== '') {
                        this.message(val);
                        console.log(val);
                    }
                },
            },
            keyword(newVal) {
                this.checkSearchStr(newVal)
            },
            sender(newVal) {
                this.checkSearchStr(newVal)
            },
            company(newVal) {
                this.checkSearchStr(newVal)
            }
        },

        computed: {
            datares() {
                return this.$page.props.flash.datares;
            },
            types: function () {
                return this.dropdowns.filter(x => x.classification === 'Document Type');
            },
        },

        created() {
            this.fetch();
        },

        methods: {
            checkSearchStr: _.debounce(function (string) {
                this.fetch();
            }, 300),

            fetch(page_url) {
                page_url = page_url || '/documents';
                axios.get(page_url, {
                    params: {
                        keyword: this.keyword,
                        sender: this.sender,
                        company: this.company,
                        count: this.count,
                        doc_type: this.doc_type,
                        type: 'staff'
                    }
                })
                .then(response => {
                    if (response) {
                        this.lists = response.data.data;
                        this.meta = response.data.meta;
                        this.links = response.data.links;
                        this.page = this.meta.per_page * (this.meta.current_page - 1);
                    }
                })
                .catch(err => console.log(err));
            },

            create() {
                this.$refs.create.show();
                this.button = 'create';
            },

            view(data) {
                this.$refs.view.show(data);
            },

            route(data) {
                this.$refs.route.show(data);
                this.button = 'route';
            },

            message(list) {
                if(this.button == 'create'){
                    this.lists.unshift(list.data);
                }else{
                    let index = this.lists.findIndex(u => u.id === list.id);
                    this.lists[index] = list;
                }
            }
        }
    }

</script>
