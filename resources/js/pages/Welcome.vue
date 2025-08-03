<script setup lang="ts">
import axios from 'axios';
import ProductCard from '@/components/ProductCard.vue';
import { Head, Link } from '@inertiajs/vue3';
import { ref, watch, onMounted } from 'vue';
import DefaultLayout from '@/layouts/DefaultLayout.vue';
import { useProductStore } from '@/store/products';

defineOptions({ layout: DefaultLayout });
const productStore = useProductStore()

// Props only used for initial structure (no longer pulling products via props)
const props = defineProps<{
  categories: Array<{
    slug: string;
    name: string;
    url: string;
  }>;
}>();

const productList = ref<Array<any>>([]);
const categoryList = ref<Array<{ slug: string; name: string }>>([]);
const loading = ref(true);
const skip = ref(0);
const limit = 12;
const total = ref(0);

const search = ref('');
const selectedCategory = ref('all');

// Fetch products from API
// const fetchProducts = async (reset = false) => {
//   loading.value = true;
//   try {
//     const response = await axios.get('/api/products', {
//       params: {
//         search: search.value,
//         category: selectedCategory.value,
//         skip: reset ? 0 : productList.value.length,
//         limit,
//       },
//     });

//     const newProducts = response.data.products || [];
//     categoryList.value = response.data.categories || [];
//     total.value = response.data.total || 0;

//     if (reset) {
//       productList.value = [...newProducts];
//       skip.value = newProducts.length;
//     } else {
//       productList.value.push(...newProducts);
//       skip.value += newProducts.length;
//     }
//   } catch (error) {
//     console.error('Failed to fetch products:', error);
//   } finally {
//     loading.value = false;
//   }
// };

// Watch filters and refetch
watch(
  () => [productStore.search, productStore.selectedCategory],
  () => {
    productStore.fetchProducts(true)
  }
)

// Infinite scroll
const handleScroll = () => {
  const nearBottom = window.innerHeight + window.scrollY >= document.body.offsetHeight - 100
  if (
    nearBottom &&
    !productStore.loading &&
    productStore.productList.length < productStore.total
  ) {
    productStore.fetchProducts()
  }
}

onMounted(() => {
  window.addEventListener('scroll', handleScroll);
  productStore.fetchProducts(true)

});
</script>

<template>
  <Head title="Welcome" />

  <!-- Filters -->
  <div class="w-full max-w-6xl mb-6">
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
      <!-- Search -->
      <input
        v-model="productStore.search"
        type="text"
        placeholder="Search products..."
        class="w-full sm:w-1/2 px-4 py-2 border rounded-md text-sm dark:bg-[#1b1b18] dark:text-white dark:border-gray-700"
      />

      <!-- Category Filter -->
      <select
        v-model="productStore.selectedCategory"
        class="px-4 py-2 border rounded-md text-sm dark:bg-[#1b1b18] dark:text-white dark:border-gray-700"
      >
        <option value="all">All Categories</option>
        <option
          v-for="cat in productStore.categoryList"
          :key="cat.slug"
          :value="cat.slug"
        >
          {{ cat.name }}
        </option>
      </select>
    </div>
  </div>

  <!-- Product List -->
  <div class="w-full max-w-6xl">
    <section class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 w-full">
      <Link
        v-for="product in productStore.productList"
        :key="product.id"
        :href="route('products.show', product.id)"
        class="block"
      >
        <ProductCard :product="product" />
      </Link>
    </section>

    <div v-if="productStore.loading && productStore.productList.length === 0" class="text-center py-8 text-gray-500 dark:text-gray-400">
      Loading products...
    </div>

    <div v-if="productStore.loading && productStore.productList.length > 0" class="text-center py-8 text-gray-500 dark:text-gray-400">
      Loading more products...
    </div>

    <div v-if="!productStore.loading && productStore.productList.length === 0" class="text-center py-8 text-gray-500 dark:text-gray-400">
      No products found.
    </div>
  </div>
</template>

