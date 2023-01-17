<template>
    <Rd :auth="auth" v-if="auth.data.role == 'Regional Director'"></Rd>
    <Ard :auth="auth" v-if="auth.data.role == 'Assistant Regional Director'"></Ard>
    <Employee :auth="auth" v-if="auth.data.role == 'Employee'"></Employee>
</template>
<script>
import Employee from './Employee.vue';
import Ard from './Ard.vue';
import Rd from './Rd.vue';
export default {
    props: ['auth'],
    components : { Employee, Ard, Rd },
    data(){
        return {
            currentUrl: window.location.origin,
            content: 'test'
        }
    }
}
</script>