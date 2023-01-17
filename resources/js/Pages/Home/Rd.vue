<template>
    <div class="row g-3">
        <div class="col-lg-4">
            <div class="card border mini-stats-wid">
                <div class="card-body">
                    <div class="d-flex flex-wrap">
                        <div class="me-3">
                            <p class="text-muted mb-2">Total Scholars</p>
                            <h5 class="mb-0">1007</h5>
                        </div>
                        <div class="avatar-sm ms-auto">
                            <div class="avatar-title bg-light rounded-circle font-size-20 text-success"><i
                                    class="bx bx-group"></i></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card border mini-stats-wid">
                <div class="card-body">
                    <div class="d-flex flex-wrap">
                        <div class="me-3">
                            <p class="text-muted mb-2">Total Graduates</p>
                            <h5 class="mb-0">651</h5>
                        </div>
                        <div class="avatar-sm ms-auto">
                            <div class="avatar-title bg-light rounded-circle font-size-20 text-info"><i
                                    class="bx bxs-graduation"></i></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card border mini-stats-wid">
                <div class="card-body">
                    <div class="d-flex flex-wrap">
                        <div class="me-3">
                            <p class="text-muted mb-2">Ongoing Scholars</p>
                            <h5 class="mb-0">193</h5>
                        </div>
                        <div class="avatar-sm ms-auto">
                            <div class="avatar-title bg-light rounded-circle font-size-20 text-primary"><i
                                    class="bx bx-user-circle"></i></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row g-3 mt-n4">
        <div class="col-lg-4">
            <div class="card border mini-stats-wid">
                <div class="card-body" :style="{ height: halfSize + 'px' }">

                </div>
            </div>
        </div>
        <div class="col-lg-8">
            <div class="card border mini-stats-wid">
                <div class="card-body" :style="{ height: halfSize + 'px' }">
                    <b-tabs pills nav-class="bg-light rounded" content-class="mt-4">
                        <b-tab title="Travels" active @click="type = 'Travels'">
                            <b-card-text>
                                <simplebar style="max-height: 250px">
                                    <div class="table-responsive">
                                        <table class="table table-nowrap align-mid table-hover mb-0">
                                            <tbody>
                                                <tr>
                                                    <td style="width: 50px">
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="checkbox"
                                                                id="tasklistCheck01" />
                                                            <label class="form-check-label"
                                                                for="tasklistCheck01"></label>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <h5 class="text-truncate font-size-14 mb-1">
                                                            <a href="javascript: void(0);" class="text-dark">
                                                                Skote Saas Dashboard
                                                            </a>
                                                        </h5>
                                                        <p class="text-muted mb-0">Assigned to Mark</p>
                                                    </td>
                                                    <td style="width: 90px">
                                                        <div>
                                                            <ul class="list-inline mb-0 font-size-16">
                                                                <li class="list-inline-item">
                                                                    <a href="javascript: void(0);"
                                                                        class="text-success p-1">
                                                                        <i class="bx bxs-edit-alt"></i>
                                                                    </a>
                                                                </li>
                                                                <li class="list-inline-item">
                                                                    <a href="javascript: void(0);"
                                                                        class="text-danger p-1">
                                                                        <i class="bx bxs-trash"></i>
                                                                    </a>
                                                                </li>
                                                            </ul>
                                                        </div>
                                                    </td>
                                                </tr>

                                            </tbody>
                                        </table>
                                    </div>
                                </simplebar>
                            </b-card-text>
                        </b-tab>
                        <b-tab title="Documents" @click="type = 'Documents'">
                            <b-card-text>
                                <simplebar style="max-height: 250px">
                                    <div class="table-responsive">
                                        <table class="table table-nowrap align-mid table-hover mb-0">
                                            <tbody>
                                                <tr>
                                                    <td style="width: 50px">
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="checkbox"
                                                                id="tasklistCheck01" />
                                                            <label class="form-check-label"
                                                                for="tasklistCheck01"></label>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <h5 class="text-truncate font-size-14 mb-1">
                                                            <a href="javascript: void(0);" class="text-dark">
                                                                WEW
                                                            </a>
                                                        </h5>
                                                        <p class="text-muted mb-0">ASDADA</p>
                                                    </td>
                                                    <td style="width: 90px">
                                                        <div>
                                                            <ul class="list-inline mb-0 font-size-16">
                                                                <li class="list-inline-item">
                                                                    <a href="javascript: void(0);"
                                                                        class="text-success p-1">
                                                                        <i class="bx bxs-edit-alt"></i>
                                                                    </a>
                                                                </li>
                                                                <li class="list-inline-item">
                                                                    <a href="javascript: void(0);"
                                                                        class="text-danger p-1">
                                                                        <i class="bx bxs-trash"></i>
                                                                    </a>
                                                                </li>
                                                            </ul>
                                                        </div>
                                                    </td>
                                                </tr>

                                               
                                            </tbody>
                                        </table>
                                    </div>
                                </simplebar>
                            </b-card-text>
                        </b-tab>
                        <b-tab title="Requests" v-if="auth.data.role == 'Regional Director'" @click="type = 'Requests'">
                            <b-card-text>
                                <simplebar style="max-height: 250px">
                                    <Applications ref="applications"/>
                                </simplebar>
                            </b-card-text>
                        </b-tab>
                    </b-tabs>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
    import { SimpleBar } from 'simplebar-vue3';
    import Applications from '../Modules/Request/Admin.vue';
    export default {
        inject: ['count3', 'heightProfile'],
        components: { SimpleBar, Applications },
        props: ['auth'],
        data() {
            return {
                currentUrl: window.location.origin,
                halfSize: this.heightProfile / 2,
                type: 'Travels'
            }
        },

        watch: {
            datares: { deep: true,
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
        },

        methods: {
            message(val){
                switch(this.type){
                    case 'Travels':

                    break;
                    case 'Documents':

                    break;
                    case 'Requests':
                        this.$refs.applications.remove(val.id);
                    break;
                }
            }
        }
    }

</script>
