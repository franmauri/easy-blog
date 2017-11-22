<template>

    <div class="left-sidebar">
        <div class="signup-box">
            <h3>
                New to Easy Blog?
            </h3> 
            <p>
                Sign up now to get your own personalized timeline, modified sidebar, customizable design, and real-time experience!
            </p> 
            <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Sign Up</button>


        </div>


        <div class="well login-form">

            <div class="control-panel-box" v-if="loggedin">

                <img src="images/cp.jpg">

                <div class="list-group">
                    <span class="list-group-item disabled">Adminsitrator</span>
                    <router-link  class="list-group-item" to="/">Blog</router-link>
                    <router-link  class="list-group-item" to="/users">Users</router-link>
                    <router-link class="list-group-item" to="/create-users">Create users</router-link>


                    <router-link class="list-group-item" to="/posts">Posts</router-link>
                    <router-link class="list-group-item" to="/create-posts">Create Posts</router-link>


                    <a class="list-group-item" href="http://easy-blog.dev/logout" @click="logout">
                        <i class="fa fa-sign-out"></i>Log out</a>

                    <form id="logout-form" action="http://easy-blog.dev/logout" method="POST" style="display: none;">
                        <input type="hidden" name="_token" value="nxGYGqO2l0sx0yI7mLtAlpy0ShBL01BlKuw0M8AV">
                    </form>



                </div>


            </div>


            <form id="loginForm"  method="POST" action="/login/" novalidate="novalidate" v-else>
                <div class="form-group">
                    <label for="email" class="control-label">Email</label>
                    <input type="email" class="form-control" id="email" name="email" value="" required="" title="Please enter you email" placeholder="example@gmail.com" v-model="email">
                    <span class="help-block"></span>
                </div>
                <div class="form-group">
                    <label for="password" class="control-label">Password</label>
                    <input type="password" class="form-control" id="password" name="password" value="" required="" title="Please enter your password" v-model="password">
                    <span class="help-block"></span>
                </div>
                <div id="loginErrorMsg" class="alert alert-error" :class="{ hide: isError }">Wrong email or password</div>
                <div class="checkbox">
                    <label>
                        <input type="checkbox" name="remember" id="remember"> Remember login
                    </label>
                    <p class="help-block">(if this is a private computer)</p>
                </div>
                <button type="submit" class="btn btn-default btn-block" @click="login">Login</button>
                <a href="/forgot/" class="btn btn-default btn-block">Help to login</a>
            </form>
        </div>


    </div>






</template>

<script>
    import State from '../../state/store';
    import commons from '../../mixins/common';

    export default {
        State,
        mixins: [commons],
        data() {
            return {
                password: '', email: '', loggedin: false, isError: false
            }

        },
        components: {

        },

        mounted() {


            setTimeout(() => {
                console.log('getuser2');
                console.log(State.getters.getUser.id);
                this.loggedin = undefined != State.getters.getUser.id;
            }, 500)




        },
        methods: {

            login: function (e) {
                e.preventDefault();
                let baseUrl= this.unsetApiBaseUrlAxios();
                var el=this;
                axios.post('login', {'email': this.email, 'password': this.password}).then(response => {

                    if (!response.errors) {
                        window.location='/';
                        return;
                        this.isError = false;
                        this.loggedin = true;

                    } else {
                        this.isError = true;

                    }
                    el.setApiBaseUrlAxios(baseUrl);

                });
                
            },

            logout: function (e) {
                e.preventDefault();
                let baseUrl= this.unsetApiBaseUrlAxios();
                var el=this;
                console.log(window);
                axios.post('logout', {'_token': $('#token').val()}).then(response => {
                    if (!response.errors) {
                        this.isError = false;
                        this.loggedin = false;

                    } else {
                        this.isError = true;

                    }
                    el.setApiBaseUrlAxios(baseUrl);
                });
                
            }

        }
    }
</script>
