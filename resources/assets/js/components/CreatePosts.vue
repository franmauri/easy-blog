<template>

    <div class="admin-side create-posts">
        <h2 class="admin-title" >Create Posts <i class="fa fa-users"></i></h2>
        <div class="btn-toolbar">

            <button class="btn" @click="addLanguage">Add Language</button>
            <button class="btn" @click="removeLanguage">Remove Language</button>
            <button class="btn btn-primary" @click="addPost">Submit</button>
        </div>




        <div class="container">
            <div class="row">
                <div class="alert alert-danger col-sm-12" role="alert" v-show="insertError">
                    {{insertError }}
                </div>

                <form class="form-horizontal pull-left" v-for="(pForm, n) in postForms">

                    <fieldset>
                        <!-- Text input-->
                        <div class="form-group" >
                            <label class="col-md-4 control-label" for="title">Title</label>  
                            <div class="col-md-12">
                                <input id="username" name="title" type="text"  class="form-control input-md" required="" v-model="pForm.title">

                            </div>
                        </div>
                        <!-- Text input-->
                        <div class="form-group" >
                            <label class="col-md-4 control-label" for="content">Content</label>  
                            <div class="col-md-12">

                                <froala :tag="'textarea'" :config="config" v-model="pForm.content" v-html="pForm.content"></froala>

                            </div>
                        </div>


                        <!-- Password input-->
                        <div class="form-group" >
                            <label class="col-md-4 control-label" for="confirmasipassword">Language</label>
                            <div class="col-md-12">
                                <select id="language" name="language"  class="form-control input-md" required="" v-model="pForm.lang">
                                    <option value="es" selected="selected">Es</option>
                                    <option value="en">En</option>
                                </select>

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
    Vue.use(VueFroala)
    var SimpleVueValidation = require('simple-vue-validator');
    var Validator = SimpleVueValidation.Validator;
    Vue.use(SimpleVueValidation);

    export default {

        data() {
            return {
                insertError: false,
                postForms: [{title: 'uno', content: 'one conent', lang: 'en'}],
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

        created() {



        },
        methods: {

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

            addPost: function (e) {
                e.preventDefault();
                axios.post('posts', {'form': this.postForms, 'image': this.viewImage}).then(response => {
                    if (!response.data.error) {
                        this.insertError = false;
                        //window.location.href = '/posts';

                    } else {
                        this.insertError = response.data.error.code;

                    }
                })
            },

            addLanguage: function () {
                if (this.postForms.length > 1)
                    return false;


                this.postForms.push({title: '', content: '', lang: ''});
            },

            removeLanguage: function (seat) {
                if (this.postForms.length < 2)
                    return false;

                this.postForms.splice(this.postForms.length - 1, 1);

            },

            addedFile(file) {
                console.log(file);
                this.files.push(file)
            }



        }

    }
</script>
