<template>
    <div class="p-6 bg-gray-50 min-h-screen" v-if="role === 'owner'">
        <h1 class="text-2xl font-bold text-emerald-600 mb-6">Owner Dashboard</h1>

        <!-- Omset -->
        <div class="bg-white p-4 rounded-xl shadow border border-emerald-200 mb-6">
            <h2 class="text-xl text-emerald-600 font-semibold mb-2">Omset</h2>
            <div class="text-gray-700 space-y-1">
                <p>Omset Harian: <span class="font-medium">Rp{{ dailyRevenue }}</span></p>
                <p>Omset Bulanan: <span class="font-medium">Rp{{ monthlyRevenue }}</span></p>
            </div>
        </div>

        <!-- Data Item -->
        <div class="bg-white p-4 rounded-xl shadow border border-emerald-200 mb-6">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-4">
                <h2 class="text-xl text-emerald-600 font-semibold">Data Barang</h2>
                <div class="flex gap-2">
                    <input type="file" @change="importItems"
                        class="file:bg-emerald-600 file:text-white file:font-semibold file:rounded-md file:px-4 file:py-2 file:border-0 text-sm" />
                    <button @click="exportItems"
                        class="bg-emerald-600 hover:bg-emerald-700 text-white px-4 py-2 rounded-md text-sm">
                        Export Barang
                    </button>
                    <button @click="exportSales"
                        class="bg-emerald-600 hover:bg-emerald-700 text-white px-4 py-2 rounded-md text-sm">
                        Export Penjualan
                    </button>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full text-sm text-left border border-gray-200 rounded-md">
                    <thead class="bg-emerald-100 text-emerald-800">
                        <tr>
                            <th class="px-4 py-2">Nama</th>
                            <th class="px-4 py-2">Harga</th>
                            <th class="px-4 py-2">Stok</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="item in items" :key="item.id" class="hover:bg-emerald-50">
                            <td class="px-4 py-2">{{ item.name }}</td>
                            <td class="px-4 py-2">Rp{{ item.price }}</td>
                            <td class="px-4 py-2">{{ item.quantity }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div v-else>
        <p class="text-red-500">You do not have access to this page.</p>
    </div>
</template>

<script>
import axios from 'axios';
import { ref, onMounted } from 'vue';
import { utils, writeFile, read } from 'xlsx';

export default {
    setup() {
        const role = ref(localStorage.getItem('role') || 'guest');
        const items = ref([]);
        const dailyRevenue = ref(0);
        const monthlyRevenue = ref(0);
        const loading = ref(true);
        const errorMessage = ref('');

        const fetchItems = async () => {
            const token = localStorage.getItem('authToken');
            if (!token) {
                errorMessage.value = 'Token not found. Please login again.';
                return;
            }
            try {
                const res = await axios.get('http://localhost:8000/api/v1/item/all', {
                    headers: { Authorization: `Bearer ${token}` },
                });
                items.value = res.data;
            } catch (err) {
                console.error(err);
            } finally {
                loading.value = false;
            }
        };

        const fetchDailyRevenue = async () => {
            const token = localStorage.getItem('authToken');
            if (!token) {
                errorMessage.value = 'Token not found. Please login again.';
                return;
            }
            try {
                const res = await axios.get('http://localhost:8000/api/v1/sales/daily', {
                    headers: { Authorization: `Bearer ${token}` },
                });
                dailyRevenue.value = res.data.total;
            } catch (err) {
                console.error(err);
            }
        };

        const fetchMonthlyRevenue = async () => {
            const token = localStorage.getItem('authToken');
            if (!token) {
                errorMessage.value = 'Token not found. Please login again.';
                return;
            }
            try {
                const res = await axios.get('http://localhost:8000/api/v1/sales/monthly', {
                    headers: { Authorization: `Bearer ${token}` },
                });
                monthlyRevenue.value = res.data.total;
            } catch (err) {
                console.error(err);
            }
        };

        const exportItems = () => {
            const ws = utils.json_to_sheet(items.value);
            const wb = utils.book_new();
            utils.book_append_sheet(wb, ws, 'DataBarang');
            writeFile(wb, 'data_barang.xlsx');
        };

        const exportSales = async () => {
            try {
                const res = await axios.get('http://localhost:8000/api/v1/sales/all'); // Buat endpoint ini di backend
                const ws = utils.json_to_sheet(res.data);
                const wb = utils.book_new();
                utils.book_append_sheet(wb, ws, 'Penjualan');
                writeFile(wb, 'data_penjualan.xlsx');
            } catch (err) {
                console.error(err);
            }
        };

        const importItems = (event) => {
            const file = event.target.files[0];
            const reader = new FileReader();
            reader.onload = async (e) => {
                const data = new Uint8Array(e.target.result);
                const workbook = read(data, { type: 'array' });
                const sheet = workbook.Sheets[workbook.SheetNames[0]];
                const json = utils.sheet_to_json(sheet);
                try {
                    await axios.post('/api/v1/item/import', json); // Buat endpoint ini di backend
                    fetchItems();
                } catch (err) {
                    console.error(err);
                }
            };
            reader.readAsArrayBuffer(file);
        };

        onMounted(() => {
            fetchItems();
            fetchDailyRevenue(); // Panggil fungsi untuk omset harian
            fetchMonthlyRevenue(); // Panggil fungsi untuk omset bulanan
        });

        return {
            role,
            items,
            dailyRevenue,
            monthlyRevenue,
            loading,
            errorMessage,
            fetchItems,
            fetchDailyRevenue,
            fetchMonthlyRevenue,
            exportItems,
            exportSales,
            importItems,
        };
    },
};
</script>