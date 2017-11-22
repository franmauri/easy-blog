<template>

    <div class="admin-side edit-users">
        <h2 class="admin-title" >Create Users <i class="fa fa-users"></i></h2>




        <div class="container">
            <div class="row">
                <form class="form-horizontal">
                    <div class="alert alert-danger" role="alert" v-show="updateError">
                        {{updateError }}
                    </div>
                    <div class="alert alert-success" role="alert" v-show="updateOk">
                        {{updateOk }}
                    </div>
                    <fieldset>


                        <!-- Text input-->
                        <div class="form-group" :class="{error: validation.hasError('name')}">
                             <label class="col-md-4 control-label" for="username">Username</label>  
                            <div class="col-md-12">
                                <input id="username" name="name" type="text" placeholder="Your username" class="form-control input-md" required="" v-model="name">
                                <div class="message">{{ validation.firstError('name') }}</div>
                            </div>
                        </div>
                        <!-- Text input-->
                        <div class="form-group" :class="{error: validation.hasError('email')}">
                             <label class="col-md-4 control-label" for="email">Email</label>  
                            <div class="col-md-12">
                                <input id="email" name="email" type="text" placeholder="Your email here" class="form-control input-md" required="" v-model="email">
                                <div class="message">{{ validation.firstError('email') }}</div>
                                <span class="help-block">xxxxxxxxx@xxxxx.xxx</span>  
                            </div>
                        </div>

                        <!-- Multiple Radios (inline) -->
                        <div class="form-group" >
                            <label class="col-md-4 control-label" for="gender">Change password</label>
                            <div class="col-md-4"> 
                                <label class="radio-inline" for="newPassword">
                                    <input type="checkbox" value="newPassword" id="newPassword" v-model="newPassword">
                                </label> 

                            </div>
                        </div>

                        <div v-if="newPassword">

                            <!-- Password input-->
                            <div class="form-group" :class="{error: validation.hasError('oldPassword')}">
                                 <label class="col-md-4 control-label" for="oldPassword">Old password </label>
                                <div class="col-md-12">
                                    <input id="oldPassword" name="oldPassword" type="password" placeholder="Old Password" class="form-control input-md" required="" v-model="oldPassword">
                                    <div class="message">{{ validation.firstError('oldPassword') }}</div>
                                </div>
                            </div>

                            <!-- Password input-->
                            <div class="form-group" :class="{error: validation.hasError('password')}">
                                 <label class="col-md-4 control-label" for="password">Password </label>
                                <div class="col-md-12">
                                    <input id="password" name="password" type="password" placeholder="Password" class="form-control input-md" required="" v-model="password">
                                    <div class="message">{{ validation.firstError('password') }}</div>
                                </div>
                            </div>

                            <!-- Password input-->
                            <div class="form-group" :class="{error: validation.hasError('conPassword')}">
                                 <label class="col-md-4 control-label" for="confirmasipassword">Confirma Password</label>
                                <div class="col-md-12">
                                    <input id="confirmasipassword" name="confirmasipassword" type="password" placeholder="Confirmation password" class="form-control input-md" required="" v-model="conPassword">
                                    <span class="help-block">Type again your password</span>
                                    <div class="message">{{ validation.firstError('conPassword') }}</div>
                                </div>
                            </div>
                        </div>
                        <!-- Multiple Radios (inline) -->
                        <div class="form-group" :class="{error: validation.hasError('role')}">
                             <label class="col-md-4 control-label" for="gender">Role</label>
                            <div class="col-md-4"> 
                                <label class="radio-inline" for="role-0">
                                    <input type="radio" name="role" id="role-admin" value="admin" :checked="[role=='admin'?'checked':'false']" v-model="role">

                                    Admin
                                </label> 
                                <label class="radio-inline" for="role-1">
                                    <input type="radio" name="role" id="role-moderator" value="moderator" :checked="[role=='moderator'?'checked':'false']" v-model="role">
                                    Moderator
                                </label>
                                <label class="radio-inline" for="role-1">
                                    <input type="radio" name="role" id="role-user" value="user" :checked="[role=='user'?'checked':'false']" v-model="role">
                                    User
                                </label>
                            </div>
                            <div class="message">{{ validation.firstError('role') }}</div>
                        </div>

                        <!-- Password input-->
                        <div class="form-group" :class="{error: validation.hasError('lang')}">
                             <label class="col-md-4 control-label" for="confirmasipassword">Language</label>
                            <div class="col-md-12">
                                <select id="lang" name="lang" type="password" placeholder="lang" class="form-control input-md" required="" v-model="lang">
                                    <option value="es" selected="selected">Es</option>
                                    <option value="en">En</option>
                                </select>

                            </div>
                            <div class="message">{{ validation.firstError('lang') }}</div>
                        </div>

                        <!-- Button -->
                        <div class="form-group">
                            <label class="col-md-4 control-label" for="confirmation"></label>
                            <div class="col-md-4">
                                <button id="confirmation" name="confirmation" class="btn btn-primary" @click="updateUser">Submit</button>
                            </div>
                        </div>

                    </fieldset>
                </form>

            </div>
        </div>
    </div>



</template>

<script>
    //import Helpers from '../mixins/helpers'
    import router from '../router';
    //import commons from '../../mixins/common';

    var Vue = require('vue');
    var SimpleVueValidation = require('simple-vue-validator');
    var Validator = SimpleVueValidation.Validator;
    Vue.use(SimpleVueValidation);

    export default {
        props: ['id'],
        //mixins: [commons],

        data() {
            return {
                name: '',
                email: '',
                lang: '',
                role: '',

                updateError: false,
                updateOk: false,

                password: '',
                oldPassword: '',
                conPassword: '',
                newPassword: false


            }

        },

        validators: {
            email: function (value) {
                return Validator.value(value).required().email();
            },
            name: function (value) {
                return Validator.value(value).required();
            },
            password: function (value) {
                return Validator.value(value).required();
            },
            conPassword: function (value) {
                return Validator.value(value).required();
            },
            role: function (value) {
                return Validator.value(value).required();
            },
            lang: function (value) {
                return Validator.value(value).required();
            }
        },

        components: {

        },

        mounted() {
            this.fetchUser();
        },
        created() {



        },
        methods: {

            fetchUser() {
                axios.get('users/' + this.id).then(response => {
                    this.name = response.data.data.name;
                    this.role = response.data.data.role;
                    this.lang = response.data.data.lang;
                    this.email = response.data.data.email;
                    console.log('this.user');
                    console.log(this.user);
                });

            },

            updateUser: function (e) {
                e.preventDefault();
                console.log(this.role);
                var data = {'email': this.email, 'name': this.name, 'role': this.role, 'language': this.lang};
                if (this.newPassword) {
                    Object.assign(data, {'password': this.password, 'oldPassword': this.oldPassword, 'conPassword': this.conPassword});
                }
                axios.patch('users/' + this.id, data).then(response => {
                    if (!response.data.error) {
                        this.updateError = false;
                        this.updateOk = 'Congratulations! User Updated.';


                    } else {
                        this.updateError = response.data.error.code;
                        this.updateOk = false;

                    }
                })
            },

        }

    }
</script>
