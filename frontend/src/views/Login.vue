<template>
  <div
    class="min-h-screen bg-[#F9F9F9] flex items-center justify-center p-4 sm:p-6 lg:p-8 relative overflow-hidden"
  >
    <div
      class="absolute top-[-10%] left-[-10%] w-72 h-72 bg-red-50 rounded-full blur-3xl opacity-60"
    ></div>
    <div
      class="absolute bottom-[-10%] right-[-10%] w-96 h-96 bg-blue-50 rounded-full blur-3xl opacity-60"
    ></div>

    <div class="w-full max-w-md z-10">
      <div class="text-center mb-8">
        <div
          class="inline-flex items-center justify-center w-16 h-16 bg-white rounded-2xl shadow-sm mb-4"
        >
          <span class="text-3xl">🍳</span>
        </div>
        <h1 class="text-3xl font-extrabold text-gray-900 tracking-tight">
          Welcome Back
        </h1>
        <p class="text-gray-500 mt-2">Manage your recipes and dashboard</p>
      </div>

      <div
        class="bg-white rounded-[2rem] shadow-xl shadow-gray-200/50 p-8 md:p-10 border border-gray-100"
      >
        <form @submit.prevent="handleLogin" class="space-y-6">
          <div>
            <label
              for="email"
              class="block text-sm font-bold text-gray-700 mb-2 ml-1"
              >Email Address</label
            >
            <input
              v-model="email"
              type="email"
              id="email"
              placeholder="chef@foodieland.com"
              required
              class="w-full px-5 py-4 bg-gray-50 border border-gray-100 rounded-2xl focus:ring-4 focus:ring-red-500/10 focus:border-[#FF6B6B] focus:bg-white transition-all outline-none text-gray-900"
            />
          </div>

          <div>
            <div class="flex justify-between items-center mb-2 ml-1">
              <label for="password" class="text-sm font-bold text-gray-700"
                >Password</label
              >
              <a
                href="#"
                class="text-xs font-semibold text-[#FF6B6B] hover:underline"
                >Forgot?</a
              >
            </div>
            <input
              v-model="password"
              type="password"
              id="password"
              placeholder="••••••••"
              required
              class="w-full px-5 py-4 bg-gray-50 border border-gray-100 rounded-2xl focus:ring-4 focus:ring-red-500/10 focus:border-[#FF6B6B] focus:bg-white transition-all outline-none text-gray-900"
            />
          </div>

          <Transition name="fade">
            <div
              v-if="errorMessage"
              class="flex items-center gap-2 text-red-500 text-sm font-medium bg-red-50 p-3 rounded-xl border border-red-100"
            >
              <i class="fas fa-exclamation-circle"></i>
              {{ errorMessage }}
            </div>
          </Transition>

          <button
            type="submit"
            :disabled="isLoading"
            class="w-full py-4 px-6 text-white font-bold rounded-2xl shadow-lg hover:bg-gray-800 active:scale-[0.98] transition-all disabled:opacity-70 disabled:cursor-not-allowed flex items-center justify-center gap-3"
            style="background: #111827"
          >
            <template v-if="isLoading">
              <svg class="animate-spin h-5 w-5 text-white" viewBox="0 0 24 24">
                <circle
                  class="opacity-25"
                  cx="12"
                  cy="12"
                  r="10"
                  stroke="currentColor"
                  stroke-width="4"
                  fill="none"
                ></circle>
                <path
                  class="opacity-75"
                  fill="currentColor"
                  d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"
                ></path>
              </svg>
              Connecting...
            </template>
            <span v-else>Login to Dashboard</span>
          </button>
        </form>

        <div class="mt-8 pt-6 border-t border-gray-50 text-center">
          <router-link
            to="/"
            class="group inline-flex items-center gap-2 text-sm font-bold text-gray-500 hover:text-[#FF6B6B] transition-colors"
          >
            <i
              class="fas fa-arrow-left text-xs group-hover:-translate-x-1 transition-transform"
            ></i>
            Return to Public Site
          </router-link>
        </div>
      </div>

      <p class="text-center text-gray-400 text-xs mt-8">
        &copy; 2026 Foodieland Admin. All rights reserved.
      </p>
    </div>
  </div>
</template>

<script>
import { useAuthStore } from "../stores/auth"; // adjust path if needed

export default {
  name: "LoginPage",

  data() {
    return {
      email: "",
      password: "",
      isLoading: false,
      errorMessage: "",
    };
  },

  methods: {
    async handleLogin() {
      this.isLoading = true;
      this.errorMessage = "";

      const authStore = useAuthStore();

      const result = await authStore.login({
        email: this.email,
        password: this.password,
      });

      if (result.success) {
        this.$router.push({ name: "Dashboard" });
      } else {
        this.errorMessage = result.message || "Invalid email or password.";
      }

      this.isLoading = false;
    },
  },
};
</script>

<style scoped>
.fade-enter-active,
.fade-leave-active {
  transition: all 0.3s ease;
}
.fade-enter-from,
.fade-leave-to {
  opacity: 0;
  transform: translateY(-10px);
}
</style>