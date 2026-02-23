<template>
  <nav
    class="sticky top-0 z-40 bg-white/95 backdrop-blur-md border-b border-gray-100"
  >
    <div class="max-w-7xl mx-auto px-4 h-20 flex justify-between items-center">
      <router-link to="/" class="flex items-center gap-2 shrink-0">
        <div
          class="w-7 h-7 md:w-10 md:h-10 bg-[#FF6B6B] rounded-xl flex items-center justify-center text-white font-bold shadow-lg shadow-red-100"
        >
          F
        </div>
        <span class="text-sm md:text-2xl font-bold text-gray-800 tracking-tight"
          >Foodieland</span
        >
      </router-link>

      <div class="hidden md:flex items-center gap-8 font-bold text-gray-500">
        <router-link to="/" class="hover:text-[#FF6B6B] transition-colors"
          >Accueil</router-link
        >
        <router-link
          to="/#recipes"
          class="hover:text-[#FF6B6B] transition-colors"
          >Recettes</router-link
        >
        <router-link
          v-if="auth.isAuthenticated"
          to="/admin/dashboard"
          class="hover:text-[#FF6B6B] transition-colors"
        >
          Dashboard
        </router-link>
      </div>

      <div class="flex items-center gap-3 md:gap-4">
        <template v-if="auth.isAuthenticated">
          <div class="hidden sm:flex flex-col items-end mr-2 text-xs">
            <span class="font-bold text-gray-800">{{ auth.user?.name }}</span>
            <span class="text-gray-400 capitalize">{{
              auth.user?.role || "Chef"
            }}</span>
          </div>

          <img
            :src="
              auth.user?.avatar ||
              'https://ui-avatars.com/api/?background=FF6B6B&color=fff&name=' +
                auth.user?.name
            "
            class="w-9 h-9 md:w-10 md:h-10 rounded-full border-2 border-[#FF6B6B] object-cover shadow-sm"
          />

          <button
            @click="handleLogout"
            class="sm:flex w-10 h-10 items-center justify-center rounded-xl bg-gray-50 text-gray-400 hover:text-red-500 hover:bg-red-50 transition-all"
            title="Logging out"
          >
            <i class="fas fa-sign-out-alt"></i>
          </button>
        </template>

        <router-link
          v-else
          to="/login"
          class="hidden sm:block px-10 py-2.5 text-white bg-gradient-to-r from-red-400 to-red-500 rounded-lg hover:from-red-600 hover:to-red-800 transition-all"
        >
          Login
        </router-link>

        <button
          @click="isMenuOpen = !isMenuOpen"
          class="md:hidden w-10 h-10 rounded-xl bg-gray-50 flex items-center justify-center text-gray-800 text-xl"
        >
          <i :class="isMenuOpen ? 'fas fa-times' : 'fas fa-bars-staggered'"></i>
        </button>
      </div>
    </div>

    <transition
      enter-active-class="transition duration-300 ease-out"
      enter-from-class="transform -translate-y-4 opacity-0"
      enter-to-class="transform translate-y-0 opacity-100"
      leave-active-class="transition duration-200 ease-in"
      leave-from-class="transform translate-y-0 opacity-100"
      leave-to-class="transform -translate-y-4 opacity-0"
    >
      <div
        v-if="isMenuOpen"
        class="md:hidden bg-white border-b border-gray-100 px-6 py-8 flex flex-col gap-6 shadow-2xl absolute w-full left-0 top-20"
      >
        <div
          v-if="auth.isAuthenticated"
          class="flex items-center gap-4 p-4 bg-gray-50 rounded-2xl"
        >
          <img
            :src="
              auth.user?.avatar ||
              'https://ui-avatars.com/api/?background=FF6B6B&color=fff&name=' +
                auth.user?.name
            "
            class="w-12 h-12 rounded-full border-2 border-white object-cover"
          />
          <div>
            <p class="font-bold text-gray-900">{{ auth.user?.name }}</p>
            <p class="text-xs text-gray-400 capitalize">
              {{ auth.user?.role }}
            </p>
          </div>
        </div>

        <router-link
          @click="isMenuOpen = false"
          to="/"
          class="text-lg font-bold text-gray-700 hover:text-[#FF6B6B]"
          >Accueil</router-link
        >
        <router-link
          @click="isMenuOpen = false"
          to="/#recipes"
          class="text-lg font-bold text-gray-700 hover:text-[#FF6B6B]"
          >Recettes</router-link
        >
        <router-link
          v-if="auth.isAuthenticated"
          @click="isMenuOpen = false"
          to="/admin/dashboard"
          class="text-lg font-bold text-gray-700 hover:text-[#FF6B6B]"
        >
          Dashboard
        </router-link>

        <hr class="border-gray-100" />

        <router-link
          v-if="!auth.isAuthenticated"
          @click="isMenuOpen = false"
          to="/login"
          class="w-full py-4 bg-[#FF6B6B] text-white rounded-2xl font-bold text-center shadow-lg shadow-red-100"
        >
          Login
        </router-link>
        <button
          v-else
          @click="handleLogout"
          class="w-full py-4 bg-red-50 text-red-500 rounded-2xl font-bold flex items-center justify-center gap-2"
        >
          <i class="fas fa-sign-out-alt"></i> Logout
        </button>
      </div>
    </transition>
  </nav>
</template>

<script>
import { useAuthStore } from "../stores/auth";

export default {
  name: "Navbar",

  data() {
    return {
      isMenuOpen: false,
    };
  },

  computed: {
    auth() {
      return useAuthStore();
    },
  },

  watch: {
    "$route.path"() {
      this.isMenuOpen = false;
    },
  },

  methods: {
    handleLogout() {
      this.auth.logout();
      this.isMenuOpen = false;
      this.$router.push("/");
    },
  },
};
</script>

<style scoped>
.router-link-active:not([href*="#"]) {
  color: #540a0a;
}
</style>