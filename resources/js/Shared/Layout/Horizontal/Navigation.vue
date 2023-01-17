<template>
    <div class="col-md-3">

        <div class="card jobs-categories">
            <div class="card-body">
                <div class="d-grid gap-2 mb-3">
                    <div class="input-group input-group-sm">
                        <select v-model="user_type" class="form-select form-select-sm">
                            <option value="Employee" selected>Employee</option>
                            <option :value="role.name" v-for="(role,index) in user.roles" v-bind:key="index">{{role.name}}</option>
                        </select>
                        <label class="input-group-text">Roles</label>
                    </div>
                </div>
                <div class="py-1" v-if="user_type == 'Employee'">
                    <div class="card border shadow-none mb-2">
                        <Link href="/home" class="text-body">
                        <div class="p-2">
                            <div class="d-flex">
                                <div class="avatar-xs align-self-center mt-1 ms-2 me-3">
                                    <div class="avatar-title rounded bg-transparent text-success font-size-20"><i
                                            class="text-primary bx bxs-dashboard h2 mt-1"></i></div>
                                </div>
                                <div class="overflow-hidden mr-auto">
                                    <h5 class="font-size-13 text-truncate mb-0">Home</h5>
                                    <p class="text-muted font-size-12 text-truncate mb-0">Dost IX</p>
                                </div>
                            </div>
                        </div>
                        </Link>
                    </div>
                    <div class="card border shadow-none mb-2">
                        <Link href="/dtr" class="text-body">
                        <div class="p-2">
                            <div class="d-flex">
                                <div class="avatar-xs align-self-center mt-1 ms-2 me-3">
                                    <div class="avatar-title rounded bg-transparent text-warning font-size-20"><i
                                            class="text-success bx bxs-timer h2 mt-1"></i></div>
                                </div>
                                <div class="overflow-hidden mr-auto">
                                    <h5 class="font-size-13 text-truncate mb-0">Daily Time Record</h5>
                                    <p class="text-muted font-size-12 text-truncate mb-0 documentcount">1 pending
                                        documents
                                    </p>
                                </div>
                            </div>
                        </div>
                        </Link>
                    </div>
                    <div class="card border shadow-none mb-2">
                        <Link href="/documents" class="text-body">
                        <div class="p-2">
                            <div class="d-flex">
                                <div class="avatar-xs align-self-center mt-1 ms-2 me-3">
                                    <div class="avatar-title rounded bg-transparent text-warning font-size-20"><i
                                            class="text-info bx bxs-book-bookmark h2 mt-1"></i></div>
                                </div>
                                <div class="overflow-hidden mr-auto">
                                    <h5 class="font-size-13 text-truncate mb-0">My Documents</h5>
                                    <p class="text-muted font-size-12 text-truncate mb-0 documentcount">1 pending
                                        documents
                                    </p>
                                </div>
                            </div>
                        </div>
                        </Link>
                    </div>
                    <div class="card border shadow-none mb-2">
                        <a class="text-body">
                            <div class="p-2">
                                <div class="d-flex">
                                    <div class="avatar-xs align-self-center mt-1 ms-2 me-3">
                                        <div class="avatar-title rounded bg-transparent text-danger font-size-20"><i
                                                class="text-danger bx bx-trip h2 mt-1"></i></div>
                                    </div>
                                    <div class="overflow-hidden mr-auto">
                                        <h5 class="font-size-13 text-truncate mb-0">My Travels</h5>
                                        <p class="text-muted font-size-12 text-truncate mb-0 travelcount">2 pending
                                            travels</p>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="card border shadow-none mb-2">
                        <Link href="/application" class="text-body">
                        <div class="p-2">
                            <div class="d-flex">
                                <div class="avatar-xs align-self-center mt-1 ms-2 me-3">
                                    <div class="avatar-title rounded bg-transparent text-warning font-size-20"><i
                                            class="text-warning bx bxs-notepad h2 mt-1"></i></div>
                                </div>
                                <div class="overflow-hidden mr-auto">
                                    <h5 class="font-size-13 text-truncate mb-0">My Requests</h5>
                                    <p class="text-muted font-size-12 text-truncate mb-0 requestcount">0 requests</p>
                                </div>
                            </div>
                        </div>
                        </Link>
                    </div>
                </div>

                <div class="py-1" v-else-if="user_type == 'Document Staff'">
                    <div class="card border shadow-none mb-2">
                        <Link href="/documents/lists" class="text-body">
                        <div class="p-2">
                            <div class="d-flex">
                                <div class="avatar-xs align-self-center mt-1 ms-2 me-3">
                                    <div class="avatar-title rounded bg-transparent text-success font-size-20"><i
                                            class="text-success bx bxs-book-bookmark h2 mt-1"></i></div>
                                </div>
                                <div class="overflow-hidden mr-auto">
                                    <h5 class="font-size-13 text-truncate mb-0">Documents</h5>
                                    <p class="text-muted font-size-12 text-truncate mb-0">List of Users</p>
                                </div>
                            </div>
                        </div>
                        </Link>
                    </div>
                </div>


                <div class="py-1" v-else>
                    <div class="card border shadow-none mb-2">
                        <Link href="/employees" class="text-body">
                        <div class="p-2">
                            <div class="d-flex">
                                <div class="avatar-xs align-self-center mt-1 ms-2 me-3">
                                    <div class="avatar-title rounded bg-transparent text-success font-size-20"><i
                                            class="text-success bx bxs-user-circle h2 mt-1"></i></div>
                                </div>
                                <div class="overflow-hidden mr-auto">
                                    <h5 class="font-size-13 text-truncate mb-0">Employees</h5>
                                    <p class="text-muted font-size-12 text-truncate mb-0">List of Users</p>
                                </div>
                            </div>
                        </div>
                        </Link>
                    </div>
                    <div class="card border shadow-none mb-2">
                        <Link href="/dtr/lists" class="text-body">
                            <div class="p-2">
                                <div class="d-flex">
                                    <div class="avatar-xs align-self-center mt-1 ms-2 me-3">
                                        <div class="avatar-title rounded bg-transparent text-success font-size-20">
                                            <i class="text-danger bx bxs-timer h2 mt-1"></i>
                                        </div>
                                    </div>
                                    <div class="overflow-hidden mr-auto">
                                        <h5 class="font-size-13 text-truncate mb-0">Daily Time Records</h5>
                                        <p class="text-muted font-size-12 text-truncate mb-0">List of Users</p>
                                    </div>
                                </div>
                            </div>
                        </Link>
                    </div>
                    <div class="card border shadow-none mb-2">
                        <Link href="/dtr" class="text-body">
                            <div class="p-2">
                                <div class="d-flex">
                                    <div class="avatar-xs align-self-center mt-1 ms-2 me-3">
                                        <div class="avatar-title rounded bg-transparent text-success font-size-20">
                                            <i class="text-info bx bxs-notepad h2 mt-1"></i>
                                        </div>
                                    </div>
                                    <div class="overflow-hidden mr-auto">
                                        <h5 class="font-size-13 text-truncate mb-0">Requests</h5>
                                        <p class="text-muted font-size-12 text-truncate mb-0">List of Users</p>
                                    </div>
                                </div>
                            </div>
                        </Link>
                    </div>
                </div>

            </div>
        </div>
    </div>
</template>
<script>
    export default {
        props: ['auth','dropdowns'],
        data() {
            return {
                user_type: (localStorage.getItem("user_type") === null) ? 'Employee' : localStorage.getItem("user_type"),
                user: this.$page.props.auth.data,
               
            }
        },

        watch: {
            user_type: function(val) {
                localStorage.setItem('user_type', val);
            }
        }
    }
</script>
