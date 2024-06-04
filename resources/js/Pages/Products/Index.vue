<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { Link } from '@inertiajs/vue3';
import { InertiaLink } from '@inertiajs/inertia-vue3';
import { usePage } from '@inertiajs/vue3';

const props = defineProps({
    all_products: Object,
    storage_url: String
});

const { props: pageProps } = usePage();
const user = pageProps.auth_data?.user || null;
const userRoles = user?.roles || [];
const isAdmin = userRoles.includes('admin');
const isModerator = userRoles.includes('moderator');
</script>

<template>
    <AppLayout title="Products">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Products
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-4">
                    <Link :href="route('products.create')" class="text-blue-500 mt-2 inline-block mb-3">Create new product</Link>
                    
                    <div v-if="all_products.data.length > 0" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                        <div v-for="product in all_products.data" :key="product.id" class="mb-4 p-4 border rounded">
                            <img :src="`${storage_url}/${product.image}`" alt="Product Image" class="w-full h-48 object-cover rounded-md mb-4">
                            <div class="text-lg font-semibold">{{ product.name }}</div>
                            <div class="text-gray-600">${{ product.price }}</div>
                            <div class="text-gray-500">Category: {{ product.category.name }}</div>

                            <!-- Display edit link if user is admin or moderator -->
                            <template v-if="isAdmin || isModerator">
                                <Link :href="route('products.edit', {product: product.id})" class="text-blue-500 mt-2 inline-block">Edit</Link> | 
                            </template>

                            <!-- Display delete link if user is admin -->
                            <template v-if="isAdmin">
                                <Link :href="route('products.delete', {product: product.id})" class="text-blue-500 mt-2 inline-block">Delete</Link> |    
                            </template>                            
                                                     
                            <Link :href="route('products.view', {product: product.id})" class="text-blue-500 mt-2 inline-block">View More</Link>
                        </div>
                    </div>
                    <div v-else>
                        You don't have any products yet. <Link :href="route('products.create')" class="text-blue-500">Create one now</Link>
                    </div>
                    
                    <!-- Pagination Links -->
                    <div class="mt-4 text-center">
                        <Link 
                            v-for="link in all_products.links" 
                            :key="link.label" 
                            :href="link.url" 
                            v-html="link.label" 
                            :class="['mr-2', link.active ? 'text-blue-500' : '']" 
                            :disabled="!link.url"
                        ></Link>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>


