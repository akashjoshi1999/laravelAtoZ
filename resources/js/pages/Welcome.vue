<script setup lang="ts">
import axios from 'axios';
import ProductCard from '@/components/ProductCard.vue';
import { Head, Link } from '@inertiajs/vue3';
import { ref, watch, onMounted } from 'vue';
import DefaultLayout from '@/layouts/DefaultLayout.vue';

defineOptions({ layout: DefaultLayout });

const props = defineProps<{
  products: Array<any>;
  total: number;
  skip: number;
  limit: number;
  categories: Array<{
    slug: string;
    name: string;
    url: string;
  }>;
  filters: {
    search: string;
    category: string;
  };
}>();

const productList = ref([...props.products]);
const loading = ref(false);
const skip = ref(props.skip);
const limit = props.limit;
const total = props.total;

const search = ref(props.filters.search || '');
const selectedCategory = ref(props.filters.category || 'all');

// Watch filters (optional - still avoids URL change)
watch([search, selectedCategory], async ([s, c]) => {
  skip.value = 0;
  loading.value = true;

  const response = await axios.get('/', {
    params: {
      search: s,
      category: c,
      skip: 0,
      limit,
    },
    headers: {
      'X-Inertia': true, // prevent full page render
    }
  });

  productList.value = response.data.props.products;
  loading.value = false;
});

// Lazy load on scroll
const loadMore = async () => {
  if (loading.value || productList.value.length >= total) return;

  loading.value = true;

  const response = await axios.get('/', {
    params: {
      search: search.value,
      category: selectedCategory.value,
      skip: productList.value.length,
      limit,
    },
    headers: {
      'X-Inertia': true,
    }
  });

  const moreProducts = response.data.props.products;
  productList.value.push(...moreProducts);
  loading.value = false;
};

const handleScroll = () => {
  const bottom = window.innerHeight + window.scrollY >= document.body.offsetHeight - 100;
  if (bottom) loadMore();
};

onMounted(() => {
  window.addEventListener('scroll', handleScroll);
});
</script>

<template>
  <Head title="Welcome">
    <link rel="preconnect" href="https://rsms.me/" />
    <link rel="stylesheet" href="https://rsms.me/inter/inter.css" />
  </Head>

  <!-- <div class="flex min-h-screen flex-col items-center bg-[#FDFDFC] p-6 text-[#1b1b18] lg:justify-center lg:p-8 dark:bg-[#0a0a0a]"> -->
    <!-- Header -->
    

    <!-- Filters -->
    <div class="w-full max-w-6xl mb-6">
      <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
        <!-- Search -->
        <input
          v-model="search"
          type="text"
          placeholder="Search products..."
          class="w-full sm:w-1/2 px-4 py-2 border rounded-md text-sm dark:bg-[#1b1b18] dark:text-white dark:border-gray-700"
        />

        <!-- Category Filter -->
        <select
          v-model="selectedCategory"
          class="px-4 py-2 border rounded-md text-sm dark:bg-[#1b1b18] dark:text-white dark:border-gray-700"
        >
          <option value="all">All Categories</option>
          <option
            v-for="cat in props.categories"
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
            v-for="product in productList"
            :key="product.id"
            :href="route('products.show', product.id)"
            class="block"
            >
            <ProductCard :product="product" />
            </Link>
        </section>

        <div v-if="loading" class="text-center py-8 text-gray-500 dark:text-gray-400">
            Loading more products...
        </div>
    </div>


   
  <!-- </div> -->
</template>
