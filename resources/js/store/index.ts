// import { useAuthStore } from './auth'
// import { useCartStore } from './cart'
import { useProductStore } from './products'

// import { useUIStore } from './ui'

export function useStores() {
  return {
    // auth: useAuthStore(),
    // cart: useCartStore(),
    product: useProductStore(),
    // ui: useUIStore(),
  }
}
