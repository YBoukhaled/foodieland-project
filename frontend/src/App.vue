<script setup>
import { RouterView } from "vue-router";
import Navbar from "./components/Navbar.vue";
import Footer from "./components/Footer.vue";
</script>

<template>
  <div class="antialiased text-slate-900 bg-white min-h-screen">
    <Navbar />

    <main class="flex-grow">
      <router-view v-slot="{ Component }">
        <transition
          mode="out-in"
          enter-active-class="duration-300 ease-out"
          enter-from-class="transform opacity-0 translate-y-2"
          enter-to-class="opacity-100 translate-y-0"
          leave-active-class="duration-200 ease-in"
          leave-from-class="opacity-100"
          leave-to-class="opacity-0 -translate-y-2"
        >
          <component :is="Component" />
        </transition>
      </router-view>
    </main>

    <Footer />
  </div>
</template>

<style>
body {
  @apply bg-white text-gray-900;
  font-family: "Inter", sans-serif;
}
</style>