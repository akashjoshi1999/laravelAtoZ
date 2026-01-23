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

// Simple popup modal state for starting a (dummy) WhatsApp conversation
const showModal = ref(false);
const dummyMessage = ref('Hello from LaravelAtoZ! This is a dummy WhatsApp message.');

const openModal = () => (showModal.value = true);
const closeModal = () => (showModal.value = false);

const sendWhatsApp = () => {
  const text = dummyMessage.value || '';
  const url = `https://wa.me/?text=${encodeURIComponent(text)}`;
  // open WhatsApp web with prefilled message
  window.open(url, '_blank');
  closeModal();
};
</script>

<template>
  <Head title="Welcome" />

  <!-- Floating chat button to open popup modal -->
  <button
    @click="openModal"
    class="fixed bottom-6 right-6 bg-green-500 hover:bg-green-600 text-white rounded-full p-4 shadow-lg z-40"
    aria-label="Start conversation"
  >
    Chat
  </button>

  <!-- Simple modal for composing a dummy WhatsApp message -->
  <div v-if="showModal" class="fixed inset-0 z-50 flex items-center justify-center">
    <div class="fixed inset-0 bg-black opacity-50" @click="closeModal"></div>
    <div class="bg-white dark:bg-gray-800 rounded-lg p-6 z-60 max-w-md w-full mx-4">
      <h3 class="text-lg font-semibold mb-2 text-gray-900 dark:text-white">Start Conversation</h3>
      <p class="text-sm text-gray-600 dark:text-gray-300 mb-3">This is a dummy message that will be sent to WhatsApp web when you press <strong>Send WhatsApp</strong>.</p>
      <textarea v-model="dummyMessage" rows="4" class="w-full p-2 border rounded mb-4 dark:bg-[#1b1b18] dark:text-white"></textarea>
      <div class="flex justify-end gap-2">
        <button @click="closeModal" class="px-4 py-2 border rounded">Close</button>
        <button @click="sendWhatsApp" class="px-4 py-2 bg-green-500 text-white rounded">Send WhatsApp</button>
      </div>
    </div>
  </div>

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

