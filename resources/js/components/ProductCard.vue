<script setup lang="ts">
  defineProps<{
    product: {
      title: string;
      description: string;
      thumbnail: string;
      price: number;
      discountPercentage: number;
      rating: number;
      stock: number;
      availabilityStatus: string;
      brand: string;
      category: string;
    };
  }>();
</script>

<template>
  <div class="rounded-lg border bg-white p-4 shadow-sm hover:shadow-md dark:bg-[#1c1c1c] dark:border-[#333]">
    <img
      :src="product.thumbnail || 'https://via.placeholder.com/400x300?text=Product+Image'"
      :alt="product.title"
      class="mb-3 h-40 w-full rounded object-cover"
    />
    <div class="flex justify-between items-start mb-2">
      <h3 class="text-lg font-semibold dark:text-white">{{ product.title }}</h3>
      <span class="text-sm px-2 py-0.5 bg-blue-100 text-blue-800 rounded dark:bg-blue-900 dark:text-blue-100">
        {{ product.category }}
      </span>
    </div>
    <p class="text-sm text-gray-600 dark:text-gray-400 line-clamp-2 mb-2">
      {{ product.description }}
    </p>
    <div class="flex items-center justify-between mb-2">
      <div>
        <span class="text-md font-bold text-[#1b1b18] dark:text-[#EDEDEC]">₹{{ product.price }}</span>
        <span class="ml-1 text-xs text-green-600 dark:text-green-400">-{{ product.discountPercentage }}%</span>
      </div>
      <span class="text-xs text-yellow-500 dark:text-yellow-400">★ {{ product.rating }}</span>
    </div>
    <p
      :class="[
        'text-xs font-medium',
        product.availabilityStatus === 'Low Stock' ? 'text-red-500' : 'text-green-500',
      ]"
    >
      {{ product.availabilityStatus }} ({{ product.stock }} left)
    </p>
    <button
      class="mt-3 w-full rounded bg-black px-4 py-2 text-sm text-white hover:bg-gray-800 dark:bg-white dark:text-black dark:hover:bg-gray-300"
    >
      Buy Now
    </button>
  </div>
</template>
