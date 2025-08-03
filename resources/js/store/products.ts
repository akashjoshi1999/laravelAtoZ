import { defineStore } from 'pinia'
import axios from 'axios'

export const useProductStore = defineStore('product', {
  state: () => ({
    productList: [] as Array<any>,
    product: null as any,
    categoryList: [] as Array<{ slug: string; name: string }>,
    total: 0,
    skip: 0,
    limit: 12,
    loading: false,
    search: '',
    selectedCategory: 'all',
  }),

  actions: {
    async fetchProducts(reset = false) {
      this.loading = true
      try {
        const response = await axios.get('/api/products', {
          params: {
            search: this.search,
            category: this.selectedCategory,
            skip: reset ? 0 : this.productList.length,
            limit: this.limit,
          },
        })

        const newProducts = response.data.products || []
        this.categoryList = response.data.categories || []
        this.total = response.data.total || 0

        if (reset) {
          this.productList = [...newProducts]
          this.skip = newProducts.length
        } else {
          this.productList.push(...newProducts)
          this.skip += newProducts.length
        }
      } catch (error) {
        console.error('Failed to fetch products:', error)
      } finally {
        this.loading = false
      }
    },
    async fetchProduct(id: number) {
        this.loading = true
        try {
            const response = await axios.get(`/api/products/${id}`)
            this.product = response.data.product // ensure the shape matches
        } catch (error) {
            console.error('Failed to fetch product:', error)
            this.product = null
        } finally {
            this.loading = false
        }
    },
    async fetchCategories() {
      const res = await axios.get('/api/categories')
      this.categoryList = res.data
    },
  },
})
