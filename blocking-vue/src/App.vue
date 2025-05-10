<template>
  <!-- Header -->
  <header class="bg-white border-b p-4 flex justify-between items-center" v-if="$route.name != 'login' && $route.name != 'register'">
    <div class="font-semibold text-lg text-emerald-600">POS - Hello, {{ username }}</div>
    <div class="flex gap-2 flex-wrap items-center">
      <button v-if="role === 'cashier'" @click="editItem" class="bg-yellow-400 text-black px-3 py-1 rounded-md hover:bg-yellow-500 transition text-sm" style="border-radius: calc(infinity * 1px);">Edit</button>
      <button @click="showCalculator = true" class="bg-emerald-600 text-white px-3 py-1 rounded-full hover:bg-emerald-700 transition text-sm" style="border-radius: calc(infinity * 1px);">Calc</button>
      <button @click="logout" class="text-red-500 justify-center m-8">Logout<i class="bi bi-box-arrow-right text-red-500 ml-2"></i></button>
    </div>
  </header>

  <!-- Calculator Modal -->
  <div v-if="showCalculator" class="fixed inset-0 bg-black/40 flex items-center justify-center z-50">
    <div class="bg-white p-4 rounded-xl w-72 shadow-md border border-emerald-100">
      <h2 class="text-lg font-semibold text-emerald-600 text-center mb-3">Calculator</h2>
      <input
        v-model="calculatorInput"
        readonly
        placeholder="0"
        class="w-full p-2 border rounded-md text-right text-lg mb-2 focus:outline-none"
      />
      <div v-if="calculatorResult !== null" class="text-right text-emerald-600 font-bold mb-2">
        = {{ calculatorResult }}
      </div>
      <div class="grid grid-cols-4 gap-2 mb-3 text-sm">
        <button
          v-for="n in ['7','8','9','/','4','5','6','*','1','2','3','-','C','0','=','+']"
          :key="n"
          @click="n === '=' ? calculate() : n === 'C' ? clearInput() : appendToInput(n)"
          :class="[
            'p-2 rounded-md',
            n === '=' ? 'bg-emerald-600 text-white hover:bg-emerald-700' :
            n === 'C' ? 'bg-red-500 text-white hover:bg-red-600' :
            '/-*+*/'.includes(n) ? 'bg-emerald-100 text-emerald-800 hover:bg-emerald-200' :
            'bg-gray-100 hover:bg-gray-200'
          ]"
        >
          {{ n }}
        </button>
      </div>
      <button @click="showCalculator = false" class="w-full text-sm bg-gray-200 py-1 rounded-md hover:bg-gray-300">
        Close
      </button>
    </div>
  </div>

  <RouterView />
</template>

<script setup>
import { ref } from 'vue'
import { useRouter } from 'vue-router'

const router = useRouter()
const username = localStorage.getItem('username') || 'Guest'
const showCalculator = ref(false)
const calculatorInput = ref('')
const calculatorResult = ref(null)

const appendToInput = (val) => calculatorInput.value += val
const clearInput = () => {
  calculatorInput.value = ''
  calculatorResult.value = null
}
const calculate = () => {
  try {
    calculatorResult.value = eval(calculatorInput.value)
  } catch {
    alert('Invalid input')
  }
}
const logout = () => {
  localStorage.clear()
  router.push('/login')
}
const editItem = () => router.push({ name: 'edititem' })
</script>

<style scoped>
/* Tailwind classes are applied directly in the template */
</style>
