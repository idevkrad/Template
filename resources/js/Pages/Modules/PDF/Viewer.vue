<template>
<body>
    <section class="my-5 pt-sm-5">
        <div class="container">
            <div class="row">
                <div class="col-12 text-center">
                    <div style="width: 700px; height: auto;">
                        <pdf :src="currentUrl+'/storage/uploads/2022-IN12-ORD-0001/2022-IN12-ORD-0001.pdf'"></pdf>
                    </div>
                </div>
            </div>
        </div>
    </section>
</body>
</template>

<script>
import pdf from '@jbtje/vue3pdf'

export default {
    layout: null,
    components: {
        pdf
    },
    data(){
        return {
            currentUrl: window.location.origin,
        }
    },
    mounted () {
        this.matchHeight()
    },
    methods : {
        matchHeight () {
            let height = this.$refs.infoBox.clientHeight;
        }
    }
}
</script>