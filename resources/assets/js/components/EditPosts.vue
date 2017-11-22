<template>

    <div class="admin-side edit-posts">
        <h2 class="admin-title" >Edit Posts <i class="fa fa-users"></i></h2>
        <div class="btn-toolbar">

            <button class="btn" @click="addLanguage">Add Language</button>
            <button class="btn" @click="removeLanguage">Remove Language</button>
            <button class="btn btn-primary" @click="updatePost">Submit</button>
        </div>

        <div class="container">

            <div class="alert alert-danger col-sm-12" role="alert" v-show="updateError">
                {{updateError }}
            </div>
            <div class="alert alert-success col-sm-12" role="alert" v-show="updateOk">
                {{updateOk }}
            </div>

            <div class="row">


                <form class="form-horizontal pull-left" v-for="(pForm, n) in postForms">

                    <fieldset>


                        <!-- Text input-->
                        <div class="form-group" :class="{error: validation.hasError('title')}">
                             <label class="col-md-4 control-label" for="title">Title</label>  
                            <div class="col-md-12">
                                <input id="username" name="title" type="text"  class="form-control input-md" required="" v-model="pForm.title">
                                <div class="message">{{ validation.firstError('title') }}</div>

                            </div>
                        </div>
                        <!-- Text input-->
                        <div class="form-group" :class="{error: validation.hasError('content')}">
                             <label class="col-md-4 control-label" for="content">Content</label>  
                            <div class="col-md-12">
                                <froala :tag="'textarea'" :config="config" v-model="pForm.content" v-html="pForm.content"></froala>
                                <div class="message">{{ validation.firstError('content') }}</div>

                            </div>
                        </div>


                        <!-- Password input-->
                        <div class="form-group" :class="{error: validation.hasError('language')}">
                             <label class="col-md-4 control-label" for="confirmasipassword">Language</label>
                            <div class="col-md-12">
                                <select id="language" name="language" type="password" placeholder="language" class="form-control input-md" required="" v-model="pForm.lang">
                                    <option value="es" selected="selected">Es</option>
                                    <option value="en">En</option>
                                </select>
                                <div class="message">{{ validation.firstError('language') }}</div>

                            </div>
                        </div>



                    </fieldset>
                </form>


                <input @change="attachFile($event)" type="file" accept="image/jpeg,image/png"  id="postImage" style="display: none">
                <div class="add-photo">
                    <a @click="showOpenDialog('postImage')" href="javascript:void(0)">
                        <span class="fa fa-image"></span>
                    </a>
                </div>
                <img v-if="viewImage" :src="viewImage"/>
                <span v-if="errImage">{{errImage}}</span>

            </div>
        </div>
    </div>



</template>

<script>
    import router from '../router';
    
    // Require Froala Editor js file.
    require('froala-editor/js/froala_editor.pkgd.min')

// Require Froala Editor css files.
    require('froala-editor/css/froala_editor.pkgd.min.css')
    require('font-awesome/css/font-awesome.css')
    require('froala-editor/css/froala_style.min.css')

// Import and use Vue Froala lib.
    import VueFroala from 'vue-froala-wysiwyg';
    
    
    var Vue = require('vue');
    var SimpleVueValidation = require('simple-vue-validator');
    var Validator = SimpleVueValidation.Validator;
    Vue.use(SimpleVueValidation);





    export default {
        props: ['id'],

        data() {
            return {
                post: [],
                updateError: false,
                updateOk: false,
                postForms: [],
                currentForm: 0,
                viewImage: false,
                errImage: false,
                config: {
                    events: {
                        'froalaEditor.initialized': function () {
                            console.log('initialized')
                        }
                    }
                },

            }

        },
        validators: {
            title: function (value) {
                return Validator.value(value).required();
            },
            content: function (value) {
                return Validator.value(value).required();
            },

            language: function (value) {
                return Validator.value(value).required();
            }
        },


        created() {

            axios.get('posts/' + this.id).then(response => {
                var data = response.data.data;
                var title = JSON.parse(data.title);
                var content = JSON.parse(this.jsonEscape(data.content));
                for (var i in title) {
                    this.postForms.push({title: title[i], content: content[i], lang: i})

                }
                if (data.image)
                    this.viewImage = data.image;

            });


        },
        methods: {
            jsonEscape(str) {
                return str.replace(/\n/g, "\\\\n").replace(/\r/g, "\\\\r").replace(/\t/g, "\\\\t");
            },

            showOpenDialog(id) {
                document.getElementById(id).click();
            },
            attachFile(event) {
                let input = event.target;
                if (input.files && input.files[0]) {
                    let reader = new FileReader();
                    let vm = this;
                    reader.onload = function (e) {
                        if (e.total > 80000) {
                            vm.errImage = "Image To big!!";
                            vm.viewImage = '';
                            return false;
                        }
                        vm.errImage = false;
                        vm.viewImage = e.target.result;
                    }
                    reader.readAsDataURL(input.files[0]);
                }
            },

            updatePost: function (e) {
                e.preventDefault();

                axios.patch('posts/' + this.id, {'form': this.postForms, 'image': this.viewImage}).then(response => {
                    if (!response.data.error) {
                        this.updateError = false;
                        this.updateOk = 'Congratulations, Post Updated!.';


                    } else {
                        this.updateError = response.data.error.code;
                        this.updateOk = false;

                    }
                })
            },

            addLanguage: function () {

                this.currentForm++;
                this.postForms.push({title: '', content: '', lang: ''});
            },

            removeLanguage: function (seat) {

                this.postForms.splice(this.currentForm, 1);
                this.currentForm--;

            },

            addedFile(file) {
                console.log(file);
                this.files.push(file)
            }



        }

    }
</script>
