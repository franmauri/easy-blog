<template>

    <div class="admin-side list-posts">
        <h2 class="admin-title" >Manage Posts <i class="fa fa-users"></i></h2>


        <div class="btn-toolbar">
            <router-link to="/create-users" tag="button" class="btn btn-primary">New Post</router-link>
            <button class="btn">Import</button>
            <button class="btn">Export</button>
        </div>
        <div class="well">

            <vuetable :load-on-start="true" ref="vuetable" api-url="api/posts" :fields="fields" pagination-path="pagination"  :per-page="5" @vuetable:pagination-data="onPaginationData">
                <template slot="title" slot-scope="props">   
                    <span>{{JSON.parse(props.rowData.title).en?JSON.parse(props.rowData.title).en:JSON.parse(props.rowData.title).es}}</span>
                </template>
                
                <template slot="languages" slot-scope="props">   
                    <span>{{(props.rowData.languages).join('/').replace(/^\/+|\/+$/g, '')}}</span>
                </template>
                
                <template slot="actions" slot-scope="props">   
                    <router-link :to="'/edit-posts/' + props.rowData.id" role="link" data-toggle="modal"><i class="fa fa-pencil"></i></router-link>
                    <a href="#myhModal" role="button" data-toggle="modal" @click="deletePost(props.rowData.id, props.rowIndex)"><i class="fa fa-remove"></i></a>
                </template>

            </vuetable>

            <div class="row pagination-bar">

                <div class="col-md-8 col-sm-12">
                    <vuetable-pagination 
                        @vuetable-pagination:change-page="onChangePage"
                        :css="css.pagination"
                        ref="paginationBottom"></vuetable-pagination>
                </div>
            </div> 

        </div>


        <div class="modal small hide fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h3 id="myModalLabel">Delete Confirmation</h3>
            </div>
            <div class="modal-body">
                <p class="error-text">Are you sure you want to delete the user?</p>
            </div>
            <div class="modal-footer">
                <button class="btn" data-dismiss="modal" aria-hidden="true">Cancel</button>
                <button class="btn btn-danger" data-dismiss="modal">Delete</button>
            </div>
        </div>
    </div>



</template>

<script>
    //import Helpers from '../mixins/helpers'
    import Vue from 'vue';
    import Vuetable from 'vuetable-2/src/components/Vuetable';
    import VuetablePagination from 'vuetable-2/src/components/VuetablePagination'
            Vue.use(Vuetable);


    export default {

        data() {
            return {
                name: 'Fran',
                email: 'fran@mail.es',
                password: '123456',
                role: [],
                lang: 'es',
                insertError: false,
                language: '',
                users: [],

                css: {
                    table: {
                        tableClass: 'table table-striped documents-list',
                        ascendingIcon: 'glyphicon glyphicon-chevron-up',
                        descendingIcon: 'glyphicon glyphicon-chevron-down',
                        handleIcon: 'glyphicon glyphicon-menu-hamburger',
                        renderIcon: function (classes, options) {
                            return `<span class="${classes.join(' ')}"></span>`
                        }
                    },
                    pagination: {
                        wrapperClass: "pagination pull-right",
                        activeClass: "btn-active",
                        disabledClass: "disabled",
                        pageClass: "btn btn-default",
                        linkClass: "btn btn-default",
                        icons: {
                            first: "",
                            prev: "",
                            next: "",
                            last: ""
                        }
                    }
                },

            }

        },
        components: {

            Vuetable, VuetablePagination
        },

        mounted() {
            console.log('Component mounted.')
        },
        created() {
            //this.loadMorePosts()

//            axios.get('users?limit=5&page=' + this.Page).then(users => {
//                if (users.status === 200 && users.data.data !== null) {
//                    setTimeout(() => {
//                        this.users = users.data.data;
//                    }, 1000)
//                } else {
//                    this.allLoaded = true
//                    this.loading = false
//                }
//            })


        },
        methods: {

            deletePost: function (id, i) {

                axios.delete('posts/' + id).then(response => {
                    if (!response.data.success) {

                    } else {
//                        this.$tableData.splice(i, 1);
//                        this.$delete(this.$tableData, i)
                        window.location.href = '/posts';
                    }
                })
            },

            onPaginationData(paginationData) {
                // this.$refs.paginationInfoTop.setPaginationData(paginationData) 
                //this.$refs.paginationTop.setPaginationData(paginationData)
                // this.$refs.paginationInfoBottom.setPaginationData(paginationData) 
                this.$refs.paginationBottom.setPaginationData(paginationData)
            },
            onChangePage(page) {
                this.$refs.vuetable.changePage(page)
            },
        },
        computed: {
//            getLanguages: function (n) {
//                return n;//this.message.split('').reverse().join('')
//            },

            fields() {
                return [

                    {
                        name: 'id',
                        title: '#',

                    },
                    {
                        name: '__slot:title',
                        title: 'title',

                    },
                    {
                        name: '__slot:languages',
                        title: 'languages',

                    },

                    {
                        name: '__slot:actions',
                        title: 'actions',

                    },
                ];
            }

        }

    }
</script>
