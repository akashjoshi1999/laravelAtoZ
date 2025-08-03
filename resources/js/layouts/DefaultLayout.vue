<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
</script>

<template>
  <div class="min-h-screen bg-[#FDFDFC] text-[#1b1b18] dark:bg-[#0a0a0a] dark:text-[#EDEDEC] flex flex-col">
    <Head>
      <link rel="preconnect" href="https://rsms.me/" />
      <link rel="stylesheet" href="https://rsms.me/inter/inter.css" />
    </Head>

    <!-- Header -->
    <header class="w-full max-w-6xl mx-auto text-sm py-4 border-b px-4 dark:border-gray-700 flex justify-between items-center">
      <h1 class="text-2xl font-bold">MyShop</h1>
      <nav class="flex gap-4">
        <Link
          v-if="$page.props.auth.user"
          :href="route('dashboard')"
          class="text-sm font-medium hover:underline"
        >
          Dashboard
        </Link>
        <template v-else>
          <Link :href="route('login')" class="text-sm font-medium hover:underline">
            Login
          </Link>
          <Link :href="route('register')" class="text-sm font-medium hover:underline">
            Register
          </Link>
        </template>
      </nav>
    </header>

    <!-- Main Content Slot -->
    <main class="w-full max-w-6xl mx-auto px-4 py-6 grow">
      <slot />
    </main>

    <!-- Footer -->
    <footer class="mt-10 w-full max-w-6xl mx-auto border-t pt-6 px-4 text-sm text-center text-gray-500 dark:border-[#333] dark:text-gray-400">
      &copy; {{ new Date().getFullYear() }} MyShop. All rights reserved.
    </footer>
  </div>
</template>
