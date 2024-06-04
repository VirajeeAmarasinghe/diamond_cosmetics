<script setup>
import FormSection from "@/Components/FormSection.vue";
import TextInput from "@/Components/TextInput.vue";
import InputLabel from "@/Components/InputLabel.vue";
import InputError from "@/Components/InputError.vue";
import ActionMessage from "@/Components/ActionMessage.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import { useForm } from "@inertiajs/vue3";
import TextArea from "@/Components/TextArea.vue";
import { ref, onMounted, watch } from "vue";
import ImageUpload from "@/components/ImageUpload.vue";
import Dropdown from "@/components/Dropdown.vue";
import axios from "axios";

const emit = defineEmits(["clearPreview"]);

const submitForm = () => {
    console.log("form submitted");
    form.category_id = selectedCategory.value ? selectedCategory.value.id : "";
    form.post(route("products.store"), {
        preserveScroll: true,
    });

    // Reset form fields after successful submission
    form.name = "";
    form.description = "";
    form.directions = "";
    form.price = "";
    form.in_stock = "";
    form.category_id = "";
    selectedCategory.value = null;
    form.image = null;
    emit("clearPreview");
};

const form = useForm({
    name: "",
    description: "",
    directions: "",
    price: "",
    in_stock: "",
    category_id: "",    
    image: null,
});

const categories = ref([]);
const selectedCategory = ref(null);

onMounted(async () => {
    try {
        const response = await axios.get("/api/categories");
        categories.value = response.data;
    } catch (error) {
        console.error("Error fetching categories:", error);
    }
});

watch(selectedCategory, (newCategory) => {
    form.category_id = newCategory ? newCategory.id : "";
});
</script>

<template>
    <div id="create-form" class="px-4 py-4">
        <FormSection @submitted="submitForm">
            <template #title> Create your product </template>
            <template #description>
                This is where you create your product
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
                        <TextInput v-model="form.price" />
                        <InputError :message="form.errors.price" />
                    </div>
                    <div class="col-12 mt-5">
                        <InputLabel value="In Stock" />
                        <TextInput v-model="form.in_stock" />
                        <InputError :message="form.errors.in_stock" />
                    </div>
                    <div class="col-12 mt-5">
                        <InputLabel value="Category" />
                        <Dropdown
                            v-model="selectedCategory"
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
                                    class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 w-1/2"
                                    @click.prevent="selectOption(category)"
                                >
                                    {{ category.name }}
                                </a>
                            </template>
                        </Dropdown>
                        <InputError :message="form.errors.category_id" />
                    </div>
                    <div class="col-12 mt-5">
                        <ImageUpload v-model="form.image" ref="imageUpload" />
                    </div>
                </div>
            </template>

            <template #actions>
                <ActionMessage class="mr-2" :on="form.recentlySuccessful"
                    >Product created successfully</ActionMessage
                >
                <PrimaryButton>Create Product</PrimaryButton>
            </template>
        </FormSection>
    </div>
</template>
