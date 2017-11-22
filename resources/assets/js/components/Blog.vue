<template>

    <div>
        <ul class="flat-nav">

            <li class="item" v-bind:class="{ active: sortedBy=='new' }">
                <a href="#" v-on:click="loadMore('new')">New</a>
            </li> 
            <li class="item" v-bind:class="{ active: sortedBy=='old' }">
                <a href="#" v-on:click="loadMore('old')">Old</a>
            </li>
            <li class="item " v-bind:class="{ active: sortedBy=='hot' }">
                <a href="#" v-on:click="loadMore('hot')">Hot</a>
            </li> 
        </ul>
        <div class="posts" v-infinite-scroll="loadMore" infinite-scroll-disabled="busy" infinite-scroll-distance="1">

            <div class="item" v-for="(post, index) in posts" :key="index">
                <div class="voting-side" >
                    <a v-bind:class="{ active: post.current_user_like=='up' }" href="#" v-on:click="like($event,post, 'like')"><i class="fa fa-thumbs-up"></i> </a>
                    <span>{{post.no_of_likes}}</span>
                    <a v-bind:class="{ active: post.current_user_like=='down' }" href="#" v-on:click="like($event,post, 'dislike')"><i class="fa fa-thumbs-down" ></i> </a>
                </div>
                <article class="post-body" >
                    <img v-if="post.image" v-bind:src="post.image">
                    <div style="max-width: 600px;">
                        <span style=" font-size: 12px;">{{post.date}}</span>
                        <h2 class="post-title"><a href="#" v-on:click="show(post)">{{post.title_lang}}</a></h2>
                        <p v-html="post.excerpt"></p>
                    </div>
                </article>
            </div>

        </div>
        <modal name="showPost" @before-open="beforeOpen" :resizable="true" :scrollable="true" :adaptive="true" :delay="4"  >

            <h1>{{modalTitle}}</h1>
            <span >{{modalDate}}</span>
            <p v-html="modalContent"></p>
            <img v-if="modalImage" v-bind:src="modalImage">
        </modal>
    </div>





</template>
<style>
    .v--modal{padding: 15px;overflow-y: scroll !important;}
    .v--modal h1{font-size: 25px;
                 margin-bottom: 10px;
                 padding-bottom: 5px;
                 border-bottom: solid thin #ccc;
    }
    .v--modal span{
        margin-bottom: 10px;font-size: 12px;display: block;
    }
</style>

<script>
    import infiniteScroll from 'vue-infinite-scroll';

    let Vue = require('vue');
    import VModal from 'vue-js-modal';

    Vue.use(VModal)
    export default {
        directives: {infiniteScroll},

        data() {
            return{
                posts: [],
                busy: false,
                page: 1,
                postsDone: false,
                modalTitle: '',
                modalContent: '',
                modalDate: '',
                modalImage: '',
                params: '',
                sortedBy:'new'
            }
        },
        methods: {

            show(post) {
                this.$modal.show('showPost', {title: post.title_lang, content: post.content_lang, date: post.date, image: post.image});
            },
            beforeOpen(event) {
                this.modalTitle = event.params.title;
                this.modalContent = event.params.content;
                this.modalDate = event.params.date;
                this.modalImage = event.params.image;
            },
            hide() {
                this.$modal.hide('hello-world');
            },
            like(e, thePost, like) {
                e.preventDefault();
                console.log(e);
                axios.patch('posts/' + thePost.id + '/' + like).then(post => {
                    console.log(post.data.data.no_of_likes);
                    thePost.no_of_likes = post.data.data.no_of_likes;
                    thePost.current_user_like = 'like' == like ? 'up' : 'down';
                });
            },
            loadMore(term = false) {
                if (term) {
                    this.postsDone = false;
                    this.page = 1;
                    this.posts = [];
                    this.params = '&sortBy=' + term;
                    this.sortedBy=term;

                }

                if (!this.postsDone) {
                    this.busy = true;
                    this.loading = true;
                    axios.get('posts?per_page=5&page=' + this.page + this.params).then(post => {
                        if (!post.data.data.length) {
                            this.postsDone = true;
                            return;
                        }

                        if (post.status === 200 && post.data.data !== null && post.data.data.length) {

                            for (var p in post.data.data) {
                                console.log('this.posts.data');
                                console.log(post.data.data[p]);
                                this.posts.push(post.data.data[p]);
                            }
                            console.log('this.posts');
                            console.log(this.posts);
                            this.page = post.data.pagination.current_page + 1;
                            this.busy = false;
                            this.loading = false;
                        } else {
                            this.postsDone = true
                            this.loading = false
                        }
                    });
            }

            }



        },
        mounted() {
            console.log('Component mounted.')
        },
        created() {
            this.loadMore();
        }
    }
</script>
