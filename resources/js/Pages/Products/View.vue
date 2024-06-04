<script setup>
import { ref } from "vue";
import AppLayout from "@/Layouts/AppLayout.vue";
import { Link } from "@inertiajs/vue3";
import { InertiaLink } from "@inertiajs/inertia-vue3";
import { usePage } from "@inertiajs/vue3";

const { props } = usePage();
const product = ref(props.product);
const storageUrl = ref(props.storageUrl);

const formatCurrency = (value) => {
    if (!value) return "";
    return new Intl.NumberFormat("en-LK", {
        style: "currency",
        currency: "LKR",
    }).format(value);
};

const user = props.auth_data?.user || null;
const userRoles = user?.roles || [];
const isAdmin = userRoles.includes("admin");
const isModerator = userRoles.includes("moderator");
</script>

<template>
    <AppLayout title="View Product">
        <div class="product-details-container">
            <div class="product-image-container">
                <img
                    :src="`${storageUrl}/${product.image}`"
                    alt="Product Image"
                    class="product-image"
                />
            </div>
            <div class="product-info-box">
                <h1 class="product-name">{{ product.name }}</h1>
                <p class="product-price">{{ formatCurrency(product.price) }}</p>
                <p class="product-description">{{ product.description }}</p>
                <p class="product-directions">{{ product.directions }}</p>
                <!-- Display edit link if user is admin or moderator -->
                <template v-if="isAdmin || isModerator">
                    <Link
                        :href="route('products.edit', { product: product.id })"
                        class="text-blue-500 mt-2 inline-block"
                        >Edit</Link
                    >
                </template>

                <!-- Display delete link if user is admin -->
                <template v-if="isAdmin">
                    |
                    <Link
                        :href="
                            route('products.delete', { product: product.id })
                        "
                        class="text-blue-500 mt-2 inline-block"
                        >Delete</Link
                    >
                </template>
            </div>
        </div>
    </AppLayout>
</template>

<style scoped>
.product-details-container {
    display: flex;
    justify-content: center;
    align-items: stretch;
    padding: 20px;
    flex-wrap: wrap;
}

.product-image-container {
    flex: 1;
    display: flex;
    justify-content: center;
    max-width: 50%;
    padding: 0 20px;
}

.product-image {
    max-width: 100%;
    height: auto;
    border-radius: 8px;
    object-fit: cover;
    max-height: 600px;
}

.product-info-box {
    flex: 1;
    max-width: 600px;
    background-color: white;
    padding: 40px;
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    text-align: justify;
}

.product-name {
    font-size: 2rem;
    margin-bottom: 1rem;
}

.product-price {
    font-size: 1.5rem;
    margin-bottom: 1rem;
}

.product-description,
.product-directions {
    font-size: 1rem;
    margin-bottom: 1rem;
}
</style>
