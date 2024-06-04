<script setup>
import FormSection from "@/Components/FormSection.vue";
import TextInput from "@/Components/TextInput.vue";
import InputLabel from "@/Components/InputLabel.vue";
import InputError from "@/Components/InputError.vue";
import ActionMessage from "@/Components/ActionMessage.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import { useForm } from "@inertiajs/vue3";
import TextArea from "@/Components/TextArea.vue";
import ImageUpload from "@/components/ImageUpload.vue";
import Dropdown from "@/components/Dropdown.vue";
import { ref, onMounted, watch } from "vue";
import axios from "axios";

const emit = defineEmits(["clearPreview"]);

const props = defineProps({
    product: Object,
    baseUrl: String,
});

const submitForm = () => {
    console.log("form submitted");

    form.category_id = selectedCategory.value ? selectedCategory.value.id : "";

    const formData = new FormData();
    formData.append("name", form.name);
    formData.append("description", form.description);
    formData.append("directions", form.directions);
    formData.append("price", form.price);
    formData.append("in_stock", form.in_stock);
    formData.append(
        "category_id",
        selectedCategory.value ? selectedCategory.value.id : ""
    );

    if (form.image && typeof form.image === "object") {
        formData.append("image", form.image);
    } else if (form.image && typeof form.image === "string") {
        formData.append("image_url", form.image);
    }

    axios
        .post(
            route("products.update", { product: props.product.id }),
            formData,
            {
                headers: {
                    "Content-Type": "multipart/form-data",
                },
            }
        )
        .then((response) => {
            // Handle successful response
            console.log("Product updated successfully");
            form.success = response.data.message;
        })
        .catch((error) => {
            // Handle error response
            console.error("Error updating product:", error);

            if (
                error.response &&
                error.response.data &&
                error.response.data.errors
            ) {
                form.errors = error.response.data.errors;
            }
        });
};

const form = useForm({
    name: props.product.name,
    description: props.product.description,
    directions: props.product.directions,
    price: props.product.price,
    in_stock: props.product.in_stock,
    category_id: props.product.category_id,
    image: props.product.image,
});

const categories = ref([]);
const selectedCategory = ref(null);

const fetchCategories = async () => {
    try {
        const response = await axios.get("/api/categories");

        categories.value = response.data;

        selectedCategory.value =
            categories.value.find(
                (cat) => cat.id === props.product.category_id
            ) || null;
    } catch (error) {
        console.error("Error fetching categories:", error);
    }
};

onMounted(() => {
    fetchCategories();

    if (props.product.category_id) {
        selectedCategory.value = categories.value.find(
            (cat) => cat.id === props.product.category_id
        );
    }
});

watch(selectedCategory, (newCategory) => {
    form.category_id = newCategory ? newCategory.id : "";
});
</script>

<template>
    <div id="create-form" class="px-4 py-4">
        <FormSection @submitted="submitForm()">
            <template #title> Edit your product </template>
            <template #description>
                This is where you edit your product
            </template>
            <template #form>
                <div class="row">
                    <div class="col-12">
                        <InputLabel value="Name" />
                        <TextInput v-model="form.name" />
                        <InputError :message="form.errors.name" />
                    </div>
                    <div class="col-12 mt-5">
                        <InputLabel value="Description" />
                        <TextArea v-model="form.description" />
                        <InputError :message="form.errors.description" />
                    </div>
                    <div class="col-12 mt-5">
                        <InputLabel value="Directions" />
                        <TextArea v-model="form.directions" />
                        <InputError :message="form.errors.directions" />
                    </div>
                    <div class="col-12 mt-5">
                        <InputLabel value="Price" />
                        <TextInput v-model.number="form.price" />
                        <InputError :message="form.errors.price" />
                    </div>
                    <div class="col-12 mt-5">
                        <InputLabel value="In Stock" />
                        <TextInput v-model.number="form.in_stock" />
                        <InputError :message="form.errors.in_stock" />
                    </div>
                    <div class="col-12 mt-5">
                        <InputLabel value="Category" />
                        <Dropdown
                            v-model.number="selectedCategory"
                            :contentClasses="['py-1', 'bg-white']"
                            :txtWidthClass="'w-1/2'"
                        >
                            <template #trigger>
                                <div
                                    class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm text-left"
                                >
                                    {{
                                        selectedCategory
                                            ? selectedCategory.name
                                            : "Select a Category"
                                    }}
                                </div>
                            </template>
                            <template #content="{ selectOption }">
                                <a
                                    v-for="category in categories"
                                    :key="category.id"
                                    href="#"
                                    class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100"
                                    @click.prevent="selectOption(category)"
                                >
                                    {{ category.name }}
                                </a>
                            </template>
                        </Dropdown>
                        <InputError :message="form.errors.category_id" />
                    </div>
                    <div class="col-12 mt-5">
                        <ImageUpload
                            v-model="form.image"
                            ref="imageUpload"
                            :baseUrl="props.baseUrl"
                        />
                        <InputError :message="form.errors.image" />
                        <InputError :message="form.errors.image_url" />
                    </div>
                </div>
            </template>

            <template #actions>
                <ActionMessage class="mr-2" :on="form.success !== ''">
                    {{ form.success }}
                </ActionMessage>
                <PrimaryButton>Update Product</PrimaryButton>
            </template>
        </FormSection>
    </div>
</template>
