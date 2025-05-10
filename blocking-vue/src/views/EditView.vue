<template>
    <div class="max-w-7xl mx-auto p-6 bg-white shadow-md rounded-lg">
        <h2 class="text-2xl font-semibold text-gray-800 mb-4">Manage Items</h2>

        <!-- Search Bar -->
        <div class="mb-4">
            <input type="text" v-model="searchQuery" placeholder="Search items..."
                class="w-full p-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-emerald-500" />
        </div>

        <!-- Tabel Daftar Item -->
        <div class="overflow-x-auto">
            <table class="min-w-full table-auto">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="px-4 py-2 text-left text-sm font-medium text-gray-700">Name</th>
                        <th class="px-4 py-2 text-left text-sm font-medium text-gray-700">Description</th>
                        <th class="px-4 py-2 text-left text-sm font-medium text-gray-700">Price</th>
                        <th class="px-4 py-2 text-left text-sm font-medium text-gray-700">Quantity</th>
                        <th class="px-4 py-2 text-left text-sm font-medium text-gray-700">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="item in filteredItems" :key="item.id">
                        <td class="px-4 py-2 text-sm text-gray-600">{{ item.name }}</td>
                        <td class="px-4 py-2 text-sm text-gray-600">{{ item.description }}</td>
                        <td class="px-4 py-2 text-sm text-gray-600">{{ formatPrice(item.price) }}</td>
                        <td class="px-4 py-2 text-sm text-gray-600">{{ item.quantity }}</td>
                        <td class="px-4 py-2">
                            <button @click="selectItem(item.id)"
                                class="bg-emerald-600 text-white px-2 py-1 rounded-md hover:bg-emerald-700">
                                Edit
                            </button>
                            <button @click="deleteItem(item.id)"
                                class="bg-red-500 text-white px-2 py-1 rounded-md hover:bg-red-600 ml-2">
                                Delete
                            </button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Form untuk Mengedit Item -->
        <div v-if="selectedItem" class="mt-6">
            <h3 class="text-xl font-semibold mb-4">Edit Item</h3>
            <form @submit.prevent="updateItem">
                <div class="mb-4">
                    <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
                    <input v-model="selectedItem.name" type="text" id="name"
                        class="mt-1 block w-full p-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-emerald-500" />
                </div>

                <div class="mb-4">
                    <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                    <textarea v-model="selectedItem.description" id="description" rows="4"
                        class="mt-1 block w-full p-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-emerald-500"></textarea>
                </div>

                <div class="mb-4">
                    <label for="price" class="block text-sm font-medium text-gray-700">Price</label>
                    <input v-model="selectedItem.price" type="number" id="price" min="0"
                        class="mt-1 block w-full p-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-emerald-500" />
                </div>

                <div class="mb-4">
                    <label for="quantity" class="block text-sm font-medium text-gray-700">Quantity</label>
                    <input v-model="selectedItem.quantity" type="number" id="quantity" min="0"
                        class="mt-1 block w-full p-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-emerald-500" />
                </div>

                <div class="flex justify-end">
                    <button type="submit"
                        class="bg-emerald-600 text-white px-4 py-2 rounded-md hover:bg-emerald-700 transition">
                        Update Item
                    </button>
                </div>
            </form>
        </div>

        <!-- Aside untuk Menambahkan Item -->
        <aside v-if="showAside" class="fixed inset-0 bg-black/40 flex items-center justify-center z-50">
            <div class="bg-white p-6 rounded-lg w-96 shadow-md">
                <h3 class="text-xl font-semibold mb-4">Add New Item</h3>
                <form @submit.prevent="createItem">
                    <div class="mb-4">
                        <label for="newName" class="block text-sm font-medium text-gray-700">Name</label>
                        <input v-model="newItem.name" type="text" id="newName" required
                            class="mt-1 block w-full p-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-emerald-500" />
                    </div>

                    <div class="mb-4">
                        <label for="newDescription" class="block text-sm font-medium text-gray-700">Description</label>
                        <textarea v-model="newItem.description" id="newDescription" rows="4" required
                            class="mt-1 block w-full p-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-emerald-500"></textarea>
                    </div>

                    <div class="mb-4">
                        <label for="newPrice" class="block text-sm font-medium text-gray-700">Price</label>
                        <input v-model.number="newItem.price" type="number" id="newPrice" min="0" required
                            class="mt-1 block w-full p-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-emerald-500" />
                    </div>

                    <div class="mb-4">
                        <label for="newQuantity" class="block text-sm font-medium text-gray-700">Quantity</label>
                        <input v-model.number="newItem.quantity" type="number" id="newQuantity" min="0" required
                            class="mt-1 block w-full p-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-emerald-500" />
                    </div>

                    <div class="flex justify-end">
                        <button type="submit"
                            class="bg-emerald-600 text-white px-4 py-2 rounded-md hover:bg-emerald-700 transition">
                            Add Item
                        </button>
                        <button @click="showAside = false" type="button"
                            class="ml-2 bg-gray-200 text-gray-800 px-4 py-2 rounded-md hover:bg-gray-300 transition">
                            Cancel
                        </button>
                    </div>
                </form>
            </div>
        </aside>

        <div class="mt-6 flex justify-end">
            <button @click="showAside = true"
                class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600 transition">
                Add New Item
            </button>
        </div>
    </div>
</template>

<script>
import { ref, computed, onMounted } from 'vue'
import axios from 'axios'

export default {
    setup() {
        const items = ref([])
        const searchQuery = ref('') // Variabel untuk input pencarian
        const filteredItems = computed(() => {
            return items.value.filter(item =>
                item.name.toLowerCase().includes(searchQuery.value.toLowerCase())
            )
        })
        const selectedItem = ref(null)
        const showAside = ref(false)
        const newItem = ref({
            name: '',
            description: '',
            price: 0,
            quantity: 0,
        })
        const errorMessage = ref('')
        const loading = ref(true)

        const fetchItems = () => {
            const token = localStorage.getItem('authToken')
            if (!token) {
                errorMessage.value = 'You are not authenticated. Please log in.'
                loading.value = false
                return
            }

            axios
                .get('http://localhost:8000/api/v1/item/all', {
                    headers: { Authorization: `Bearer ${token}` },
                })
                .then((res) => {
                    items.value = res.data
                })
                .catch((err) => {
                    errorMessage.value = err.response?.data?.message || 'Failed to fetch items.'
                    console.error(err)
                })
                .finally(() => {
                    loading.value = false
                })
        }

        const selectItem = (id) => {
            const item = items.value.find(item => item.id === id)
            selectedItem.value = item ? { ...item } : null
        }

        const updateItem = async () => {
            const token = localStorage.getItem('authToken')
            if (!token) {
                errorMessage.value = 'You are not authenticated. Please log in.'
                loading.value = false
                return
            }
            if (!selectedItem.value) return
            axios
                .put(`http://localhost:8000/api/v1/item/update/${selectedItem.value.id}`, selectedItem.value, {
                    headers: { Authorization: `Bearer ${token}` },
                })
                .then(() => {
                    fetchItems()
                    selectedItem.value = null
                })
                .catch((err) => {
                    errorMessage.value = err.response?.data?.message || 'Failed to update item.'
                    console.error(err)
                })
        }

        const deleteItem = async (id) => {
            const token = localStorage.getItem('authToken')
            if (!token) {
                errorMessage.value = 'You are not authenticated. Please log in.'
                loading.value = false
                return
            }
            axios
                .delete(`http://localhost:8000/api/v1/item/delete/${id}`, {
                    headers: { Authorization: `Bearer ${token}` },
                })
                .then(() => {
                    fetchItems()
                })
                .catch((err) => {
                    errorMessage.value = err.response?.data?.message || 'Failed to delete item.'
                    console.error(err)
                })
        }

        const createItem = async () => {
            const token = localStorage.getItem('authToken')
            if (!token) {
                errorMessage.value = 'You are not authenticated. Please log in.'
                loading.value = false
                return
            }
            axios
                .post('http://localhost:8000/api/v1/item/add', newItem.value, {
                    headers: { Authorization: `Bearer ${token}` },
                })
                .then(() => {
                    fetchItems()
                    newItem.value = {
                        name: '',
                        description: '',
                        price: 0,
                        quantity: 0,
                    }
                    showAside.value = false
                })
                .catch((err) => {
                    errorMessage.value = err.response?.data?.message || 'Failed to create item.'
                    console.error(err)
                })
        }

        const formatPrice = (value) => {
            if (isNaN(value)) return value
            return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR' }).format(value)
        }

        onMounted(fetchItems)

        return {
            items,
            searchQuery,
            filteredItems,
            selectedItem,
            showAside,
            newItem,
            errorMessage,
            loading,
            selectItem,
            updateItem,
            deleteItem,
            createItem,
            formatPrice,
        }
    },
}
</script>

<style scoped>
/* Add any needed scoped styles here */
</style>