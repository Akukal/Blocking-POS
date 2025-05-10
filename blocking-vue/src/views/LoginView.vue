<template>
    <div v-if="!isLoggedIn">
        <form @submit.prevent="login" class="max-w-md mx-auto mt-10 p-6 bg-white shadow-lg rounded-xl">
            <h2 class="text-2xl font-semibold mb-4">Login</h2>
            <input v-model="username" type="text" placeholder="Username"
                class="w-full p-2 mb-3 border rounded-lg focus:outline-none focus:ring focus:border-blue-300" />
            <input v-model="password" type="password" placeholder="Password"
                class="w-full p-2 mb-4 border rounded-lg focus:outline-none focus:ring focus:border-blue-300" />
            <button @click="login()"
                class="w-full bg-green-600 text-white py-2 rounded-lg hover:bg-green-700 transition duration-200">
                Login
            </button>
            <div class="text-center mt-4">
                Don't have account? <RouterLink to="/register">Register</RouterLink>
            </div>
        </form>
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
        }
    },
    computed: {
        isLoggedIn() {
            return !!localStorage.getItem('authToken')
        },
    },
    methods: {
        login() {
            axios
                .post('http://localhost:8000/api/v1/auth/login', {
                    username: this.username,
                    password: this.password,
                })
                .then((response) => {
                    console.log(response)
                    // Simpan token login ke localStorage
                    localStorage.setItem('authToken', response.data.token)
                    localStorage.setItem('username', response.data.user)
                    localStorage.setItem('role', response.data.role)
                    // Redirect ke halaman utama
                    if (response.data.role === 'cashier') {
                        router.push({ name: 'home' })
                    } else if (response.data.role === 'owner') {
                        router.push({ name: 'owner' })
                    }
                })
                .catch((error) => {
                    console.error(error)
                })
        },
    },
}
</script>