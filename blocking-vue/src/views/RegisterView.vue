<template>
    <div v-if="!isLoggedIn">
        <form @submit.prevent="login" class="max-w-md mx-auto mt-10 p-6 bg-white shadow-lg rounded-xl">
            <h2 class="text-2xl font-semibold mb-4">Register</h2>
            <input v-model="username" type="text" placeholder="Username"
                class="w-full p-2 mb-3 border rounded-lg focus:outline-none focus:ring focus:border-blue-300" />
            <input v-model="password" type="password" placeholder="Password"
                class="w-full p-2 mb-3 border rounded-lg focus:outline-none focus:ring focus:border-blue-300" />
            <input v-model="repeatPassword" type="password" placeholder="Repeat Password"
                class="w-full p-2 mb-4 border rounded-lg focus:outline-none focus:ring focus:border-blue-300" />
            <button @click="register()"
                class="w-full bg-green-600 text-white py-2 rounded-lg hover:bg-green-700 transition duration-200">
                Register
            </button>
            <div class="text-center mt-4">Have account? <RouterLink to="/login">Login</RouterLink>
            </div>
        </form>
        <p v-if="errorMessage" class="text-red-500 text-center mt-4">{{ errorMessage }}</p>
    </div>
</template>
<script>
import axios from 'axios'
import router from '../router'

export default {
    data() {
        return {
            username: '',
            password: '',
            repeatPassword: '',
            role: 'cashier',
            errorMessage: '',
        }
    },
    computed: {
        isLoggedIn() {
            return !!localStorage.getItem('authToken')
        },
    },
    methods: {
        register() {
            if (this.password !== this.repeatPassword) {
                this.errorMessage = 'Passwords do not match.'
                return
            }

            axios
                .post('http://localhost:8000/api/v1/auth/register', {
                    username: this.username,
                    password: this.password,
                    role: this.role,
                })
                .then((response) => {
                    console.log(response)
                    router.push({ name: 'login' })
                })
                .catch((error) => {
                    this.errorMessage =
                        error.response?.data?.message || 'An error occurred during registration.'
                    console.error(error)
                })
        },
    },
}
</script>