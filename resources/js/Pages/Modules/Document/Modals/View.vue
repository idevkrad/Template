<template>
    <b-modal v-model="showModal" @ok="create($event)" id="create" size="lg" title="View Document" centered  hide-footer>
        <div class="row p-2">
            <div class="col-md-12" ref="infoBox">
                <div class="d-flex">
                    <div class="flex-grow-1 overflow-hidden">
                        <h5 class="font-size-15">{{ document.subject}}</h5>
                        <!-- <p class="text-muted mt-n2">{{ document.route_slip}}</p> -->
                        <ol class="breadcrumb ms-n3 mt-n3">
                            <li class="breadcrumb-item"><a class="">Route Slip</a></li>
                            <li class="breadcrumb-item active"><span active="true" disabled="false">
                                    {{ document.route_slip}}</span></li>
                        </ol>
                    </div>
                </div>
                <div class="col-md-12">

                    <b-tabs content-class="p-3 text-muted">
                        <b-tab active class="border-0">
                            <template v-slot:title>
                                <span class="d-inline-block d-sm-none">
                                    <i class="fas fa-home"></i>
                                </span>
                                <span class="d-none d-sm-inline-block">Information</span>
                            </template>
                            <div style="height: 280px;">
                                <div class="row font-size-12 mt-1">
                                    <div class="col-sm-4">
                                        <h6 class="font-size-12"><i class="bx bx-notepad text-info"></i> Type</h6>
                                        <p class="font-size-13 text-muted mb-0">{{ document.type.name }}</p>
                                    </div>
                                    <div class="col-sm-4">
                                        <h6 class="font-size-12"><i class="bx bxs-user-circle me-1 text-info"></i>
                                            Sender</h6>
                                        <p class="text-muted mb-0">{{ document.sender.name }}</p>
                                    </div>
                                    <div class="col-sm-4">
                                        <h6 class="font-size-12"><i class="bx bxs-bank me-1 text-info"></i>Company</h6>
                                        <p class="font-size-13 text-muted mb-0">{{ document.company.name }}</p>
                                    </div>
                                </div>
                                <hr>
                                <div class="row font-size-12">
                                    <div class="col-sm-4">
                                        <h6 class="font-size-12"><i class="bx bx-calendar text-info"></i> Document Date
                                        </h6>
                                        <p class="font-size-13 text-muted mb-0">{{ document.document_at }}</p>
                                    </div>
                                    <div class="col-sm-4">
                                        <h6 class="font-size-12"><i
                                                class="bx bx-calendar-check me-1 text-info"></i>Received Date
                                        </h6>
                                        <p class="font-size-13 text-muted mb-0">{{ document.received_at }}</p>
                                    </div>
                                    <div class="col-sm-4">
                                        <h6 class="font-size-12"><i
                                                class="bx bx-calendar-plus me-1 text-info"></i>Created Date</h6>
                                        <p class="font-size-13 text-muted mb-0">{{ document.created_at}}</p>
                                    </div>
                                </div>
                                <hr>
                                <span class="badge bg-primary me-1 font-size-12"
                                    v-for="(keyword,index) in document.keywords" v-bind:key="index">
                                    {{ keyword.data }}
                                </span>

                                <div class="row mt-4">
                                    <div class="col-xl-6" v-for="attachment in document.attachments"
                                        v-bind:key="attachment.id">
                                        <div class="card border mb-2 p-3">
                                            <a @click="download(attachment.id)" class="text-body">
                                                <div class="row">
                                                    <div class="col-md-2">
                                                        <div class="avatar-xs">
                                                            <span class="avatar-title rounded-circle">
                                                                <i class="bx bxs-file-doc"></i>
                                                            </span>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-10">
                                                        <h5 class="font-size-12 mb-0 text-truncate"><a href="#"
                                                                class="text-dark">{{attachment.name}}</a></h5>
                                                        <small>Size : 3.25 MB</small>
                                                    </div>
                                                </div>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </b-tab>

                        <b-tab>
                            <template v-slot:title>
                                <span class="d-inline-block d-sm-none">
                                    <i class="far fa-user"></i>
                                </span>
                                <span class="d-none d-sm-inline-block">Employees</span>
                            </template>
                            <div style="height: 280px;">
                                <div class="table-responsive">
                                    <table class="table table-nowrap align-middle">
                                        <tbody>
                                            <tr v-for="user in document.routings" v-bind:key="user.id">
                                                <td v-if="user.avatar != 'avatar.jpg'" style="width: 5%;">
                                                    <img :src="currentUrl+'/images/avatars/'+user.avatar" class="rounded-circle avatar-xs" alt="">
                                                </td>
                                                <td v-else style="width: 5%;">
                                                    <div class="avatar-xs">
                                                        <span class="avatar-title rounded-circle bg-soft-primary text-white font-size-16">{{ user.name[0] }}</span>
                                                    </div>
                                                </td>
                                                <td style="width: 50%;">
                                                    <h5 class="font-size-14 m-0"><a href="" class="text-dark">{{ user.name }}</a></h5>
                                                </td>
                                                <td style="width: 15%;">{{ user.seened_at}}</td>
                                                <td style="width: 15%;">{{ user.completed_at}}</td>
                                                <td style="width: 15%;">
                                                    <div class="align-self-center avatar-xs mr-3">
                                                        <div :class="'avatar-title bg-light rounded-circle font-size-16 '+user.is_completed.color">
                                                            <i :class="'bx '+user.is_completed.icon"></i>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </b-tab>
                    </b-tabs>

                </div>
            </div>
        </div>
        <!-- <template v-slot:footer>
            <b-button @click="hide()" variant="secondary" block>Close</b-button>
        </template> -->
    </b-modal>
</template>

<script>
    export default {
        props: ['dropdowns'],
        data() {
            return {
                currentUrl: window.location.origin,
                showModal: false,
                document: {
                    sender: {},
                    company: {},
                    type: {}
                },
            }
        },

        methods: {
            show(data) {
                this.document = data;
                this.showModal = true;
            },

            create() {

            },

            hide() {
                this.form.reset()
                this.showModal = false;
            },

            download(data) {

            },
        }
    }

</script>
