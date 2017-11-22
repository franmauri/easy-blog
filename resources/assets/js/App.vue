<template>

    <div class="container-fluid">

        <page-header></page-header>




        <div class="main">
            <left-sidebar></left-sidebar>

            <div class="col-lg-9 content-area">
                <router-view></router-view>

            </div>




        </div>



    </div>


</template>
<script>
    import _Header from './components/partials/Header.vue';
    import _LeftSidebar from './components/partials/LeftSidebar.vue';

    export default {
        components: {
            pageHeader: _Header,
            leftSidebar: _LeftSidebar,
        },
        data() {
            return {

            }
        },
        mounted() {

        },
        methods: {

        }
    }
</script>
<style>

</style>