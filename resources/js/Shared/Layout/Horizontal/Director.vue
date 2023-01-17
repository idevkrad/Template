<template>
    <div class="col-lg-3">
        <div class="card-body">
            <div class="d-flex">
                <div class="flex-shrink-0 ms-n4 me-2 mt-n3">
                    <img :src="currentUrl+'/images/avatars/'+ $page.props.auth.data.avatar" alt=""
                        class="avatar-md rounded-circle img-thumbnail">
                </div>
                <div class="flex-grow-1">
                    <div class="d-flex">
                        <div class="flex-grow-1">
                            <div class="text-muted">
                                <h5 class="mb-1">{{ $page.props.auth.data.firstname }}
                                    {{ $page.props.auth.data.middlename }} {{ $page.props.auth.data.lastname }}</h5>
                                <p class="mb-0">Regional Director</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="py-1">
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
                                <p class="text-muted font-size-12 text-truncate mb-0 documentcount">1 pending documents
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
                                <div class="avatar-title rounded bg-transparent text-warning font-size-20"><i
                                        class="text-info bx bxs-book-bookmark h2 mt-1"></i></div>
                            </div>
                            <div class="overflow-hidden mr-auto">
                                <h5 class="font-size-13 text-truncate mb-0">My Documents</h5>
                                <p class="text-muted font-size-12 text-truncate mb-0 documentcount">1 pending documents
                                </p>
                            </div>
                        </div>
                    </div>
                </a>
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
                                <p class="text-muted font-size-12 text-truncate mb-0 travelcount">2 pending travels</p>
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

    </div>
</template>
<script>
export default {
    data(){
        return {
            currentUrl: window.location.origin,
        }
    }
}
</script>