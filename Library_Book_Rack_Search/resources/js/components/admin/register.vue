<template>
    <div class="d-flex justify-content-center bg-green" style="padding: 3rem;">
        <div class="d-flex flex-column gap-4">
            <div class="register-text">
                <h2 class="mb-0">Register</h2>
            </div>

            <div class="welcome-box p-5">
                <div class="d-flex flex-column gap-4">
                    <div>
                        <input type="text" class="form-control" v-model="form.username" placeholder="Username" />
                        <span v-if="validationErrors.username" class="text-danger validation-error">{{ validationErrors.username[0] }}</span>
                    </div>

                    <div>
                        <input type="text" class="form-control" v-model="form.email" placeholder="Email" />
                        <span v-if="validationErrors.email" class="text-danger validation-error">{{ validationErrors.email[0] }}</span>
                    </div>
                   
                    <div>
                        <div class="input-group">
                            <input :type="isShowPassword ? 'text' : 'password'" class="form-control" v-model="form.password" placeholder="Password" /> 
                            <label class="input-group-text trigger-password" @click="triggerPassword"><i :class="isShowPassword ? 'fas fa-eye' : 'fas fa-eye-slash'"></i></label>
                        </div>
                        <span v-if="validationErrors.password" class="text-danger validation-error">{{ validationErrors.password[0] }}</span>
                    </div>

                    <div>
                        <div class="input-group">
                            <input :type="isShowConfirmedPassword ? 'text' : 'password'" class="form-control" v-model="form.confirmedPassword" placeholder="Confirmed Password" /> 
                            <label class="input-group-text trigger-password" @click="triggerConfirmedPassword"><i :class="isShowConfirmedPassword ? 'fas fa-eye' : 'fas fa-eye-slash'"></i></label>
                        </div>
                        <span v-if="validationErrors.confirmedPassword" class="text-danger validation-error">{{ validationErrors.confirmedPassword[0] }}</span>
                    </div>
                    
                    <button class="btn btn-dark btn-register w-100" @click="register">Register</button>

                    <p class="mb-0">Already have an account? <router-link to="/api" class="text-decoration-none">Login here</router-link></p>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import { ref, onMounted, getCurrentInstance } from 'vue';
    import axios from 'axios';

    export default {
        name: 'login',
        data() {
            return {
                form: {
                    username: null,
                    email: null,
                    password: null,
                    confirmedPassword: null
                },
                isShowPassword: false,
                isShowConfirmedPassword: false,
                validationErrors: {}
            };
        },
        mounted(){
            //
        },
        methods: {
            triggerPassword(){
                this.isShowPassword = !this.isShowPassword
            },
            triggerConfirmedPassword(){
                this.isShowConfirmedPassword = !this.isShowConfirmedPassword
            },
            async register(){
                try {
                    const response = await axios.post('/api/register', this.form);

                    this.$swal.fire({
                        title: 'Registration successful',
                        icon: 'success',
                        confirmButtonColor: '#007bff',
                        confirmButtonText: 'Ok'
                    });

                    //redirect to login page
                    this.$router.push('/api');

                } catch (error) {
                    if(error.response && error.response.status === 422){
                        // Handle validation errors
                        const errors = error.response.data.errors;
                        this.validationErrors = errors;
                    }else{
                        console.error('An error occurred:', error);
                    }
                }
            }
        }
    };
</script>

<style scoped>
    .bg-green{
        background-color: #f2ffea;
        height: 100vh;
    }

    .register-text{
        text-align: center;
        background-color: white;
        padding: 15px;
        border: 1px solid green;
        border-bottom-right-radius: 35px;
        border-top-right-radius: 35px;
        border-top-left-radius: 35px;
        border-bottom-left-radius: 35px;
    }

    .welcome-box{
        border: 1px solid green;
        background-color: white; 
        border-radius: 5px;
    }

    .trigger-password{
        cursor: pointer;
    }

    .btn-register{
        border-bottom-right-radius: 10px;
        border-top-right-radius: 10px;
        border-top-left-radius: 10px;
        border-bottom-left-radius: 10px;
    }
</style>