<template>

    <div class="admin-side create-users">
        <h2 class="admin-title" >Create Users <i class="fa fa-users"></i></h2>




        <div class="container">
            <div class="row">
                <form class="form-horizontal">
                    <div class="alert alert-danger" role="alert" v-show="insertError">
                        {{insertError }}
                    </div>
                    <fieldset>


                        <!-- Text input-->
                        <div class="form-group" :class="{error: validation.hasError('name')}">
                             <label class="col-md-4 control-label" for="username">Username</label>  
                            <div class="col-md-12">
                                <input id="username" name="username" type="text" placeholder="Your username" class="form-control input-md" required="" v-model="name">
                                <div class="message">{{ validation.firstError('name') }}</div>

                            </div>
                        </div>
                        <!-- Text input-->
                        <div class="form-group" :class="{error: validation.hasError('email')}">
                             <label class="col-md-4 control-label" for="email">Email</label>  
                            <div class="col-md-12">
                                <input type="text" placeholder="Your email here" class="form-control input-md" required="" v-model="email">
                                <div class="message">{{ validation.firstError('email') }}</div>
                                <span class="help-block">xxxxxxxxx@xxxxx.xxx</span> 


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

                        <!-- Multiple Radios (inline) -->
                        <div class="form-group" :class="{error: validation.hasError('role')}">
                             <label class="col-md-4 control-label" for="gender">Role</label>
                            <div class="col-md-4"> 
                                <label class="radio-inline" for="role-0">
                                    <input type="radio" name="role" id="role-admin" value="admin" checked="checked" v-model="role">
                                    Admin
                                </label> 
                                <label class="radio-inline" for="role-1">
                                    <input type="radio" name="role" id="role-moderator" value="moderator" v-model="role">
                                    Moderator
                                </label>
                                <label class="radio-inline" for="role-1">
                                    <input type="radio" name="role" id="role-user" value="user" v-model="role">
                                    User
                                </label>

                            </div>
                            <div class="message">{{ validation.firstError('role') }}</div>
                        </div>

                        <!-- Password input-->
                        <div class="form-group" :class="{error: validation.hasError('language')}">
                             <label class="col-md-4 control-label" for="language">Language</label>
                            <div class="col-md-12">
                                <select id="language" name="language" type="password" placeholder="language" class="form-control input-md" required="" v-model="language">
                                    <option value="es" selected="selected">Es</option>
                                    <option value="en">En</option>
                                </select>
                                <div class="message">{{ validation.firstError('language') }}</div>
                            </div>
                        </div>

                        <!-- Button -->
                        <div class="form-group">
                            <label class="col-md-4 control-label" for="confirmation"></label>
                            <div class="col-md-4">
                                <button id="confirmation" name="confirmation" class="btn btn-primary" @click="addUser">Submit</button>
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

    var Vue = require('vue');
    var SimpleVueValidation = require('simple-vue-validator');
    var Validator = SimpleVueValidation.Validator;
    Vue.use(SimpleVueValidation);

    export default {

        data() {
            return {
                name: 'Fran',
                email: 'fran@mail.es',
                password: '123456',
                conPassword: '123456',
                role: 'admin',
                lang: 'es',
                insertError: false,
                language: ''

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
            language: function (value) {
                return Validator.value(value).required();
            }
        },
        components: {

        },

        mounted() {
            console.log('Component mounted.')
        },
        created() {



        },
        methods: {

            addUser: function (e) {
                e.preventDefault();
                if (this.conPassword != this.password) {
                    this.insertError = 'Password does not match the confirm password.';
                    return false;
                }

                axios.post('users', {'email': this.email, 'password': this.password, 'name': this.name, 'role': this.role, 'language': this.lang}).then(response => {
                    alert('ok');
                    if (!response.data.error) {
                        this.insertError = false;
                        window.location.href = '/users';
                    } else {
                        this.insertError = response.data.error.code;

                    }
                });

//                this.$validate()
//                        .then(function (success) {
//                            if (success) {
//                                //this ajax is not executed
//                                axios.post('users', {'email': this.email, 'password': this.password, 'name': this.name, 'role': this.role, 'language': this.lang}).then(response => {
//                                    alert('ok');
//                                    if (!response.data.error) {
//                                        this.insertError = false;
//                                        window.location.href = '/users';
//                                    } else {
//                                        this.insertError = response.data.error.code;
//
//                                    }
//                                });
//                            } else {
//
//                                this.insertError = 'Please check error fields.';
//
//                            }
//                        });



            },

        }

    }
</script>
