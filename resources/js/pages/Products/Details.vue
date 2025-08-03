<script setup lang="ts">
import DefaultLayout from '@/layouts/DefaultLayout.vue';
import { Head, Link } from '@inertiajs/vue3';

defineOptions({ layout: DefaultLayout });

const props = defineProps<{
  product: {
    id: number;
    title: string;
    description: string;
    price: number;
    category: string;
    rating: number;
    stock: number;
    brand: string;
    thumbnail: string;
    images: string[];
    reviews: Array<{
      rating: number;
      comment: string;
      reviewerName: string;
      date: string;
    }>;
  };
}>();
</script>

<template>
  <Head :title="product.title" />

  <div class="max-w-6xl mx-auto p-6">
    <!-- Breadcrumb -->
    <div class="mb-4 text-sm text-gray-500">
      <Link href="/" class="hover:underline">Home</Link> /
      <span class="text-gray-700">{{ product.title }}</span>
    </div>

    <!-- Product Section -->
    <div class="flex flex-col lg:flex-row gap-10">
      <!-- Images -->
      <div class="lg:w-1/2 space-y-4">
        <img :src="product.thumbnail" alt="" class="w-full rounded-md shadow-sm">
        <div class="grid grid-cols-3 gap-2">
          <img
            v-for="(img, i) in product.images"
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
          {{ product.title }}
        </h1>
        <p class="text-gray-600 dark:text-gray-300 mb-2">Brand: <strong>{{ product.brand }}</strong></p>
        <p class="text-sm text-gray-500 mb-4">Category: {{ product.category }}</p>
        <p class="text-xl font-bold text-green-600 dark:text-green-400 mb-4">${{ product.price }}</p>

        <p class="mb-4 text-gray-700 dark:text-gray-300">{{ product.description }}</p>

        <div class="flex items-center gap-2 text-sm text-yellow-500">
          ⭐ {{ product.rating }} &middot; {{ product.stock }} in stock
        </div>

        <button class="mt-6 px-6 py-2 bg-[#1b1b18] text-white text-sm rounded hover:bg-black">
          Add to Cart
        </button>
      </div>
    </div>

    <!-- Reviews -->
    <div class="mt-12">
      <h2 class="text-xl font-semibold mb-4">Customer Reviews</h2>
      <div
        v-for="(review, i) in product.reviews"
        :key="i"
        class="border-t py-4"
      >
        <div class="flex justify-between items-center mb-1">
          <span class="font-semibold">{{ review.reviewerName }}</span>
          <span class="text-yellow-500">⭐ {{ review.rating }}</span>
        </div>
        <p class="text-sm text-gray-600 dark:text-gray-300">{{ review.comment }}</p>
        <p class="text-xs text-gray-400">{{ new Date(review.date).toLocaleDateString() }}</p>
      </div>
    </div>
  </div>
</template>
