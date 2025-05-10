<!-- pages/PosPage.vue -->
<template>
  <div class="max-w-7xl mx-auto mt-10 px-4 flex flex-col lg:flex-row gap-8" v-if="role === 'cashier'">
    <!-- Daftar Item -->
    <main class="flex-1">
      <h1 class="text-2xl font-bold text-emerald-600 mb-4">Point of Sale</h1>

      <div v-if="loading" class="text-center text-gray-500">Loading...</div>
      <div v-else-if="errorMessage" class="text-red-500 text-center">
        {{ errorMessage }}
      </div>

      <div v-else class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-4">
        <ItemCard v-for="item in items" :key="item.id" :item="item" @add-to-cart="addToCart" />
      </div>
    </main>

    <!-- Keranjang -->
    <aside class="w-full lg:w-1/3 bg-gray-50 p-4 rounded-lg shadow-md">
      <h2 class="text-xl font-bold text-emerald-600 mb-4">Cart</h2>

      <div v-if="cart.length === 0" class="text-gray-500">Your cart is empty.</div>
      <div v-else>
        <ul>
          <li
            v-for="(cartItem, index) in cart"
            :key="index"
            class="flex justify-between items-start mb-4 border-b pb-2"
          >
            <div>
              <h3 class="font-semibold">{{ cartItem.name }}</h3>
              <p class="text-sm text-gray-500">
                Qty: {{ cartItem.quantity }} x Rp
                {{ formatPrice(cartItem.price) }}
              </p>
              <p class="font-semibold">
                Total: Rp {{ formatPrice(cartItem.price * cartItem.quantity) }}
              </p>
            </div>
            <button
              @click="removeFromCart(index)"
              class="bg-red-500 text-white px-2 py-1 rounded-lg hover:bg-red-600"
            >
              Remove
            </button>
          </li>
        </ul>
        <div class="text-right mt-4">
          <h3 class="text-lg font-bold">
            Total: Rp
            {{ formatPrice(cart.reduce((sum, item) => sum + item.price * item.quantity, 0)) }}
          </h3>
          <button
            @click="checkout"
            class="bg-emerald-600 text-white px-4 py-2 rounded-lg mt-2 hover:bg-emerald-700 transition"
          >
            Checkout
          </button>
        </div>
      </div>
    </aside>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import axios from 'axios'
import ItemCard from '@/components/ItemCard.vue'

const role = ref(localStorage.getItem('role') || 'guest')

const items = ref([])
const cart = ref([])
const loading = ref(true)
const errorMessage = ref('')

function fetchItems() {
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

function addToCart(item) {
  const existing = cart.value.find((c) => c.id === item.id)
  if (existing) {
    existing.quantity++
  } else {
    cart.value.push({ ...item, quantity: 1 })
  }
}

function removeFromCart(index) {
  cart.value.splice(index, 1)
}

function checkout() {
  alert('Checkout successful!')
  cart.value = []
}

function formatPrice(value) {
  return new Intl.NumberFormat('id-ID').format(value)
}

onMounted(fetchItems)
</script>
  