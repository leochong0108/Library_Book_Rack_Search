<template>
    <div class="d-flex justify-content-center bg-green" style="padding: 5rem;">
        <div class="welcome-box p-5">
            <div class="position-absolute welcome-text">
                <h3 class="mb-0">Welcome Admin!</h3>
            </div>
            
            <div class="d-flex flex-column gap-4">
                <h5 class="mb-0 text-muted text-center">Sign in to your account</h5>

                <div>
                    <input type="text" class="form-control" v-model="form.usernameOrEmail" placeholder="Username or Email" />
                    <span v-if="validationErrors.usernameOrEmail" class="text-danger">{{ validationErrors.usernameOrEmail[0] }}</span>
                </div>

                <div>
                    <div class="input-group">
                        <input :type="isShowPassword ? 'text' : 'password'" class="form-control" v-model="form.password" placeholder="Password" /> 
                        <label class="input-group-text trigger-password" @click="triggerPassword"><i :class="isShowPassword ? 'fas fa-eye' : 'fas fa-eye-slash'"></i></label>
                    </div>
                    <span v-if="validationErrors.password" class="text-danger">{{ validationErrors.password[0] }}</span>
                </div>  
                  
                <button class="btn btn-primary btn-login w-100" @click="login">Login</button>
               
                <span class="text-center">Don't have an account? <router-link to="/api/register" class="text-decoration-none">Sign Up now!</router-link></span>

                <router-link to="/api" class="text-decoration-none text-center" @click="forgetPassword">Forget password</router-link>
                <!-- /api/forget-password -->
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
                    usernameOrEmail: null,
                    password: null,
                    role: 'admin'
                },
                isShowPassword: false,
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
            async login(){
                try {
                    const response = await axios.post('/api/login', this.form);

                    this.$swal.fire({
                        title: 'Login successful',
                        icon: 'success',
                        confirmButtonColor: '#007bff',
                        confirmButtonText: 'Ok'
                    });

                    localStorage.setItem('access_token', response.data.access_token);
                    localStorage.setItem('user_role', response.data.user_role);

                    // Set the Authorization header for future requests
                    axios.defaults.headers.common['Authorization'] = `Bearer ${response.data.access_token}`;

                    //redirect to login page
                    this.$router.push('/api/index');

                } catch (error) {
                    
                    if(error.response && error.response.status === 422){
                        // Handle validation errors
                        const errors = error.response.data.errors;
                        this.validationErrors = errors;
                    }else{
                        console.error('An error occurred:', error);
                    }
                }
            },
            forgetPassword(){
                //
            }
        }
    };
</script>

<style scoped>
    .bg-green{
        background-color: #f2ffea;
        height: 100vh;
    }

    .welcome-box{
        border: 1px solid green;
        width: 30%;
        background-color: white; 
        border-radius: 5px;
        height: 70%;
    }

    .welcome-text{
        top: 46px;
        left: 644px;
        background-color: white;
        padding: 15px;
        border: 1px solid green;
        border-bottom-right-radius: 35px;
        border-top-right-radius: 35px;
        border-top-left-radius: 35px;
        border-bottom-left-radius: 35px;
    }

    .trigger-password{
        cursor: pointer;
    }

    .btn-login{
        border-bottom-right-radius: 20px;
        border-top-right-radius: 20px;
        border-top-left-radius: 20px;
        border-bottom-left-radius: 20px;
    }
</style>