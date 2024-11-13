<template>
    <div>
        <div class="semi-circle"></div>
        <div class="d-flex justify-content-center bg-green" style="padding: 5rem;">
            <div class="welcome-box p-5">
                <div class="position-absolute welcome-text">
                    <h3 class="mb-0">Welcome</h3>
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
                   
                    <span class="text-center">Don't have an account? <router-link to="/api/user/register" class="text-decoration-none">Sign Up now!</router-link></span>
    
                    <!-- <router-link to="/api" class="text-decoration-none text-center" @click="forgetPassword">Forget password</router-link> -->
                </div>
            </div>
        </div>
    </div>
   
</template>

<script>
    import { ref, reactive, onMounted } from 'vue';
    import { useRouter } from 'vue-router';
    import axios from 'axios';
    import Swal from 'sweetalert2';
    
    export default {
        setup() {

            const isShowPassword = ref(false);
            const form = reactive({
                usernameOrEmail: '',
                password: '',
                role: 'user'
            });

            const router = useRouter();

            const validationErrors = reactive({});

            const login = async () => {
                try {
                    const response = await axios.post('/api/login', form);

                    Swal.fire({
                        title: 'Login successful',
                        icon: 'success',
                        confirmButtonColor: '#007bff',
                        confirmButtonText: 'Ok'
                    });

                    localStorage.setItem('user_role', response.data.user_role);
                    localStorage.setItem('access_token', response.data.access_token);
                    localStorage.setItem('user_data', JSON.stringify(response.data.user));
                    
                    // Set the Authorization header for future requests
                    axios.defaults.headers.common['Authorization'] = `Bearer ${response.data.access_token}`;

                    //redirect to home page
                    router.push('/api/home');

                } catch (error) {
                    console.log(error.response.status)
                    
                    if(error.response && error.response.status === 422){
                        const errors = error.response.data.errors;

                        Object.assign(validationErrors, errors);
                    }else{
                        console.error('An error occurred:', error);
                    }
                }
            };

            const triggerPassword = () => {
                isShowPassword.value = !isShowPassword.value;
            };
    
            onMounted(() => {
                //
            });
    
            return {
                form,
                isShowPassword,
                validationErrors,
                login,
                triggerPassword,
            };
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
        z-index: 2;
    }

    .welcome-text{
        top: 46px;
        left: 686px;
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

    .semi-circle{
        width: 100%;
        height: 200px; /* Adjust this for desired height */
        background-color: #c5ffe0; /* Your desired color */
        border-radius: 0 0 150% 150%; 
        border: 2px solid #b0e5ec;
        position: absolute;
        z-index: 1;
    }
</style>