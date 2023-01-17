<template>
    <div v-if="lists.length == 0" class="alert alert-secondary text-center text-secondary">
        No request found  
    </div>
    <div class="vstack gap-4 mt-5">
        <div class="border-bottom d-flex mt-n3" v-for="(list,index) in lists" v-bind:key="index">
            <div class="ms-2 flex-grow-1">
                <h6 class="mb-0 font-size-14">{{ list.profile.name }}</h6>
                <p class="text-muted font-size-12 mb-0">{{ list.type.name }}</p>
            </div>
            <div class="mb-3">
                <button @click="view(list)" class="btn btn-sm btn-light mt-1" type="button">
                    <i class="bx bxs-show"></i>
                </button>
            </div>
        </div>
    </div>
    <View :role="$page.props.auth.data.role" ref="view"/>
</template>
<script>
    import View from './Modals/View.vue';
    export default {
        inject: ['count3', 'heightProfile'],
        components : { View},
        data() {
            return {
                currentUrl: window.location.origin,
                lists: []
            }
        },

        created(){
            this.fetch();
        },

        methods: {
            fetch() {
                axios.get(this.currentUrl + '/application',{
                    params : {type : 'admin'}
                })
                .then(response => {
                    this.lists = response.data.data;
                    this.$emit('list-length', this.lists.length);
                })
                .catch(err => console.log(err));
            },

            view(data){
                this.$refs.view.set(data);
            },

            remove(id){
                let index = this.lists.findIndex(x => x.id === id);
                this.lists.splice(index,1);
            }

        }
    }

</script>