<script setup lang="ts">
import { onMounted } from 'vue'
import { Head, router } from '@inertiajs/vue3'

import DefaultLayout from '@/layouts/DefaultLayout.vue'
import { useProductStore } from '@/store/products'; 
defineOptions({ layout: DefaultLayout })

const props = defineProps<{ productId: number, user: any }>()
const productStore = useProductStore()

onMounted(() => {
  productStore.fetchProduct(props.productId)
})

</script>

<template>
    <Head title="Checkout" />
    <div class="max-w-6xl mx-auto p-6">
        Ch
    </div>
</template>