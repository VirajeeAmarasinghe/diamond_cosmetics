<script setup>
import { ref, watch, onMounted } from "vue";
import { defineProps, defineEmits } from "vue";

const props = defineProps({
    modelValue: {
        type: [String, Object, null],

        required: true,
    },
    baseUrl: {
        type: String,
        required: false,
    },
});

const emit = defineEmits(["update:modelValue"]);

const inputFile = ref(null);
const preview = ref(
    typeof props.modelValue === "string"
        ? `${props.baseUrl}/${props.modelValue}`
        : null
);

const handleFileChange = (event) => {
    const file = event.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = (e) => {
            preview.value = e.target.result;
            emit("update:modelValue", file);
        };
        reader.readAsDataURL(file);
    } else {
        preview.value = null;
        emit("update:modelValue", null);
    }
};

const clearPreview = () => {
    preview.value = null;
};

onMounted(() => {
    watch(
        () => props.modelValue,
        (newValue) => {
            if (newValue === null) {
                clearPreview();
            } else if (typeof newValue === "string") {
                preview.value = `${props.baseUrl}/${newValue}`;
            } else {
                preview.value = URL.createObjectURL(newValue);
            }
        }
    );
});
</script>

<template>
    <div>
        <input
            type="file"
            accept="image/*"
            :ref="inputFile"
            class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm w-1/2"
            @change="handleFileChange"
            name="image"
        />
        <div v-if="preview" class="mt-4">
            <img
                :src="preview"
                alt="Image Preview"
                class="max-w-full h-auto rounded-md shadow-sm"
            />
        </div>
    </div>
</template>

<style scoped>
input[type="file"] {
    display: block;
    margin-bottom: 1rem;
}

img {
    max-width: 100%;
    height: auto;
    border: 1px solid #ccc;
    padding: 5px;
    background: #f9f9f9;
    border-radius: 5px;
}
</style>
