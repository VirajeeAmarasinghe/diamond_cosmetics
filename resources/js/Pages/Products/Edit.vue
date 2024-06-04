<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { Link } from "@inertiajs/vue3";
import EditToolForm from './EditToolForm.vue';

const props = defineProps({
    product: Object,
    baseUrl: String
});


</script>

<template>
    <AppLayout title="Create Product">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Edit Product
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <EditToolForm :product="product" :baseUrl="baseUrl"/>                   
                </div>
            </div>
        </div>
    </AppLayout>
</template>
