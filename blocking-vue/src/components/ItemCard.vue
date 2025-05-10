<template>
    <div
        class="bg-white rounded-2xl shadow-lg overflow-hidden border border-emerald-600 flex flex-col w-full min-w-[220px] hover:shadow-xl transition">
        <!-- Gambar atau Inisial -->
        <div v-if="item.image" class="h-40 bg-gray-100">
            <img :src="item.image" :alt="item.name" class="w-full h-full object-cover" />
        </div>
        <div v-else class="h-40 flex items-center justify-center bg-emerald-100 text-emerald-600 text-5xl font-bold">
            {{ item.name.charAt(0).toUpperCase() }}
        </div>

        <!-- Konten -->
        <div class="p-4 flex-1 flex flex-col justify-between">
            <h2 class="text-lg font-semibold text-gray-800 truncate mb-1">{{ item.name }}</h2>
            <p class="text-gray-500 text-sm mb-3 line-clamp-2">{{ item.description || 'No description' }}</p>
            <div class="flex items-center justify-between mt-auto">
                <div class="text-emerald-600 font-bold text-base">
                    Rp {{ formatPrice(item.price) }}
                </div>
                <button @click="$emit('add-to-cart', item)"
                    class="bg-emerald-600 text-white px-4 py-1.5 rounded-lg hover:bg-emerald-700 transition text-sm">
                    Add
                </button>
            </div>
        </div>
    </div>
</template>

<script setup>
defineProps(['item'])
defineEmits(['add-to-cart'])

function formatPrice(value) {
    return new Intl.NumberFormat('id-ID').format(value)
}
</script>