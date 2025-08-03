<script setup lang="ts">
import { onMounted } from 'vue'
import { Head, router } from '@inertiajs/vue3'
import DefaultLayout from '@/layouts/DefaultLayout.vue'
import { useProductStore } from '@/store/products';

defineOptions({ layout: DefaultLayout })

const props = defineProps<{ productId: number, user: any }>()

const productStore = useProductStore()

const handleCheckout = () => {
  if (!props.user) {
    router.visit(route('login'), {
      preserveState: true,
      preserveScroll: true,
    });
  }
  router.visit(route('checkout'), {
    method: 'post',
    data: { productId: productStore.product?.id },
    preserveState: true,
    preserveScroll: true,
  });

};

onMounted(() => {
  productStore.fetchProduct(props.productId)
})
</script>

<template>
  <div class="max-w-6xl mx-auto p-6">
    <Head :title="productStore.product?.title || 'Product Details'" />

    <!-- Loader -->
    <div v-if="productStore.loading" class="text-center py-10 text-gray-500 dark:text-gray-400">
      Loading product...
    </div>

    <!-- Product Not Found -->
    <div
      v-if="!productStore.loading && !productStore.product"
      class="text-center py-10 text-red-500 dark:text-red-400"
    >
      Product not found.
    </div>

    <!-- Product Details -->
    <div v-if="!productStore.loading && productStore.product">
      <div class="mb-4 text-sm text-gray-500">
        <a href="/" class="hover:underline">Home</a> /
        <span class="text-gray-700">{{ productStore.product.title }}</span>
      </div>

      <div class="flex flex-col lg:flex-row gap-10">
        <!-- Images -->
        <div class="lg:w-1/2 space-y-4">
          <img :src="productStore.product.thumbnail" alt="" class="w-full rounded-md shadow-sm" />
          <div class="grid grid-cols-3 gap-2">
            <img
              v-for="(img, i) in productStore.product.images"
              :key="i"
              :src="img"
              alt="Product"
              class="w-full h-24 object-cover rounded-md border"
            />
          </div>
        </div>

        <!-- Info -->
        <div class="lg:w-1/2">
          <h1 class="text-3xl font-semibold text-[#1b1b18] dark:text-white mb-2">
            {{ productStore.product.title }}
          </h1>
          <p class="text-gray-600 dark:text-gray-300 mb-2">
            Brand: <strong>{{ productStore.product.brand }}</strong>
          </p>
          <p class="text-sm text-gray-500 mb-4">Category: {{ productStore.product.category }}</p>
          <p class="text-xl font-bold text-green-600 dark:text-green-400 mb-4">
            ${{ productStore.product.price }}
          </p>
          <p class="mb-4 text-gray-700 dark:text-gray-300">
            {{ productStore.product.description }}
          </p>
          <div class="flex items-center gap-2 text-sm text-yellow-500">
            ⭐ {{ productStore.product.rating }} &middot; {{ productStore.product.stock }} in stock
          </div>
          <button class="mt-6 px-6 py-2 bg-[#1b1b18] text-white text-sm rounded hover:bg-black" @click="handleCheckout">
            Add to Cart
          </button>
        </div>
      </div>

      <!-- Reviews -->
      <div class="mt-12" v-if="productStore.product.reviews?.length">
        <h2 class="text-xl font-semibold mb-4">Customer Reviews</h2>
        <div
          v-for="(review, i) in productStore.product.reviews"
          :key="i"
          class="border-t py-4"
        >
          <div class="flex justify-between items-center mb-1">
            <span class="font-semibold">{{ review.reviewerName }}</span>
            <span class="text-yellow-500">⭐ {{ review.rating }}</span>
          </div>
          <p class="text-sm text-gray-600 dark:text-gray-300">{{ review.comment }}</p>
          <p class="text-xs text-gray-400">
            {{ new Date(review.date).toLocaleDateString() }}
          </p>
        </div>
      </div>
    </div>
  </div>
</template>
