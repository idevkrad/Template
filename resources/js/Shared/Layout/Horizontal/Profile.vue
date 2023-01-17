<template>
<div class="row">
    <div class="col-lg-12" style="margin-top: -5px; margin-bottom: -10px;">
        <div class="card">

            <div class="card-body">
                <div class="row mt-n2 mb-n2 ms-n3 me-n3">
                    <div class="col-lg-6">
                        <div class="d-flex">
                            <div class="flex-shrink-0 me-3">
                                <img :src="currentUrl+'/images/avatars/'+ $page.props.auth.data.avatar" alt="" class="avatar-sm rounded-circle img-thumbnail">
                            </div>
                            <div class="flex-grow-1 align-self-center">
                                <div class="text-muted">
                                    <h5 class="mb-0">{{ $page.props.auth.data.firstname }} {{ $page.props.auth.data.middlename }} {{ $page.props.auth.data.lastname }}</h5>
                                    <p class="mb-0 font-size-11">{{ $page.props.auth.data.position.name }}</p>
                                    <!-- <p class="mb-0 font-size-11">{{ $page.props.auth.data.department.name }}</p> -->
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-6 align-self-center">
                        <div class="mt-0 mt-lg-0">
                            <div class="row">
                                <div class="col-sm-6">
                                </div>
                                <div class="col-sm-6">
                                    <!-- <div class="text-sm-end mt-4 mt-sm-0">
                                        <p class="text-muted mb-2">Since last month</p>
                                        <h5>+ $ 248.35   <span class="badge bg-success ms-1 align-bottom">+ 1.3 %</span></h5>
                                        
                                    </div> -->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card-body border-top py-3">
                <div class="d-flex align-items-center mt-n1 mb-n1">
                    <ul class="list-unstyled hstack gap-3 mb-0 font-size-11 flex-grow-1">
                        <li>
                            <i class="bx bx-briefcase align-middle"></i> {{ $page.props.auth.data.status.name }}
                        </li>
                        <li>
                            <i class="bx bx-building align-middle"></i> {{ $page.props.auth.data.department.name }}
                        </li>
                        <li>
                            <i class="bx bx-map align-middle"></i> {{ $page.props.auth.data.office.name }}
                        </li>
                    </ul>
                    <div class="hstack gap-2">
                        <!-- <button type="button" @click="generate($page.props.auth.data.id)" class="btn btn-primary btn-sm">Download DTR <i class="bx bx-download align-baseline ms-1"></i></button>
                        <button type="button" v-b-tooltip.hover title="Download Date Time Record" class="btn btn-light btn-sm"><i class="bx bx-bookmark align-baseline"></i></button> -->
                    </div>
                </div>
            </div>
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