<template>
  <div
    class="min-h-screen bg-white flex flex-col mx-auto w-full max-w-[1280px] px-4 py-8 md:py-12"
  >
    <section
      v-if="loadingRecipes && !latestRecipe"
      class="bg-gray-100 animate-pulse rounded-[30px] md:rounded-[40px] h-[500px] md:h-[640px] mb-12 md:mb-16"
    ></section>

    <section
      v-else-if="latestRecipe"
      class="bg-[#E7FAFE] rounded-[30px] md:rounded-[40px] flex flex-col md:flex-row items-stretch overflow-hidden mb-12 md:mb-16 w-full h-auto md:h-[640px] transition-all duration-700 relative"
    >
      <div
        class="w-full md:basis-[620px] p-6 sm:p-8 md:p-12 lg:p-16 flex flex-col items-center sm:items-start justify-center text-center sm:text-left order-2 md:order-1"
      >
        <div
          class="flex items-center gap-2 bg-white w-fit px-4 py-2 rounded-full mb-4 md:mb-6 shadow-sm mx-auto sm:mx-0"
        >
          <span
            class="text-[10px] md:text-xs font-bold uppercase tracking-widest text-orange-500"
            >🔥 Hot Recipes</span
          >
        </div>
        <h1
          class="text-2xl sm:text-3xl md:text-4xl lg:text-5xl font-semibold tracking-[-0.04em] mb-4 md:mb-6 leading-tight"
        >
          {{ latestRecipe.title }}
        </h1>
        <p
          class="text-[#00000099] text-sm sm:text-base md:text-lg mb-6 md:mb-8 max-w-md line-clamp-3"
        >
          {{
            latestRecipe.description ||
            "Discover our latest culinary creation, prepared with passion and fresh ingredients."
          }}
        </p>
        <div
          class="flex flex-wrap items-center justify-center sm:justify-start gap-4 md:gap-6 mb-8 md:mb-10"
        >
          <div
            class="flex items-center justify-center gap-2 bg-gray-200 rounded-full py-3 px-6 w-auto"
          >
            <i class="fa-regular fa-clock"></i>
            <span
              class="font-[500] text-[14px] leading-[100%] tracking-[-0.02em]"
              >{{ latestRecipe.cook_time }} Minutes</span
            >
          </div>
          <div
            class="flex items-center justify-center gap-2 bg-gray-200 rounded-full py-3 px-6 w-auto"
          >
            <i class="fa-solid fa-utensils"></i>
            <span
              class="font-[500] text-[14px] leading-[100%] tracking-[-0.02em]"
              >{{
                latestRecipe.category?.name || latestRecipe.category || "Lunch"
              }}</span
            >
          </div>
        </div>
        <div class="w-full sm:w-auto flex justify-center sm:justify-start">
          <button
            @click="goToDetails(latestRecipe.id)"
            class="w-full sm:w-auto bg-black text-white py-4 px-8 sm:px-10 md:py-5 rounded-2xl font-[600] text-[14px] tracking-[-0.02em] leading-[100%] hover:bg-gray-800 transition-all active:scale-95 shadow-lg shadow-black/10 inline-flex items-center justify-center gap-3"
          >
            <span>View Recipe</span>
            <i class="fas fa-play-circle"></i>
          </button>
        </div>
      </div>

      <div
        class="hidden sm:block md:absolute md:top-24 md:left-1/2 -translate-x-1/2 -translate-y-1/2 z-10"
      >
        <img
          src="../assets/images/Badge.png"
          alt="Badge"
          class="h-20 sm:h-24 md:h-32"
        />
      </div>

      <div
        class="w-full md:basis-[660px] h-64 sm:h-80 md:h-auto order-1 md:order-2 overflow-hidden"
      >
        <img
          :src="latestRecipe.image_url"
          class="w-full h-full object-cover transition-transform duration-1000 hover:scale-105"
          :alt="latestRecipe.title"
        />
      </div>
    </section>

    <section class="mb-16">
      <div
        class="flex flex-col sm:flex-row justify-between items-center gap-4 mb-8"
      >
        <h2 class="text-2xl md:text-3xl font-bold">Categories</h2>
        <button
          @click="clearFilter"
          :disabled="loadingRecipes"
          class="w-full sm:w-auto px-6 py-2 rounded-xl font-bold transition-all active:scale-95 disabled:opacity-50 disabled:cursor-not-allowed"
          :class="
            selectedCategoryId === null
              ? 'bg-black text-white'
              : 'bg-[#E7F9FD] hover:bg-[#d0f1f8] text-gray-800'
          "
        >
          <span v-if="loadingRecipes && selectedCategoryId">Loading...</span>
          <span v-else>View All Categories</span>
        </button>
      </div>

      <div
        v-if="loadingCategories"
        class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4 md:gap-6"
      >
        <div
          v-for="i in 6"
          :key="i"
          class="h-40 bg-gray-50 rounded-[30px] animate-pulse border border-gray-100"
        ></div>
      </div>

      <div
        v-else
        class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4 md:gap-6"
      >
        <div
          v-for="category in categories"
          :key="category.id"
          @click="filterByCategory(category.id)"
          class="group rounded-[30px] p-6 text-center border cursor-pointer transition-all duration-300"
          :class="
            selectedCategoryId === category.id
              ? 'bg-white shadow-xl border-black/10 scale-105'
              : 'border-black/5 bg-gradient-to-b from-transparent to-[#E7F9FD]/50 hover:bg-white hover:shadow-lg hover:-translate-y-1'
          "
        >
          <img
            :src="category.image_url"
            class="w-12 h-12 md:w-16 md:h-16 mx-auto mb-4 object-contain group-hover:rotate-12 transition-transform duration-500"
            :alt="category.name"
          />
          <p class="font-bold text-sm md:text-base">{{ category.name }}</p>
        </div>
      </div>
    </section>

    <section id="recipes">
      <div class="text-center py-12">
        <h2 class="text-2xl md:text-4xl font-bold mb-4">
          {{
            selectedCategoryId
              ? selectedCategoryName + " Recipes"
              : "Simple and tasty recipes"
          }}
        </h2>
        <p class="text-gray-600 text-sm md:text-base max-w-2xl mx-auto px-4">
          Elevate your culinary game without the complexity. Foodieland bridges
          the gap between sophisticated flavors and everyday simplicity.
        </p>
      </div>

      <div
        v-if="loadingRecipes"
        class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 md:gap-8"
      >
        <div
          v-for="i in 6"
          :key="i"
          class="h-96 bg-gray-50 rounded-3xl animate-pulse border border-gray-100"
        ></div>
      </div>

      <div
        v-else-if="recipes.length === 0"
        class="text-center py-20 bg-gray-50 rounded-[40px]"
      >
        <p class="text-5xl mb-4">🍳</p>
        <h3 class="text-xl font-bold text-gray-800">No recipes found</h3>
        <p class="text-gray-500 mt-2">
          Try selecting another category or view all recipes.
        </p>
        <button
          @click="clearFilter"
          class="mt-6 px-8 py-3 bg-black text-white rounded-full font-bold hover:bg-gray-800 transition-all"
        >
          Show Everything
        </button>
      </div>

      <transition-group
        v-else
        tag="div"
        enter-active-class="transition duration-500 ease-out"
        enter-from-class="opacity-0 translate-y-8"
        enter-to-class="opacity-100 translate-y-0"
        move-class="transition duration-500"
        class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 md:gap-8 items-stretch"
      >
        <div
          v-for="recipe in recipes"
          :key="recipe.id"
          @click="goToDetails(recipe.id)"
          class="group bg-gradient-to-b from-white to-[#E7F9FD]/40 w-full rounded-3xl p-4 border border-black/5 shadow-sm flex flex-col h-full hover:shadow-2xl hover:-translate-y-2 transition-all duration-300 cursor-pointer"
        >
          <div
            class="h-48 sm:h-56 md:h-64 lg:h-72 overflow-hidden rounded-2xl mb-4 shrink-0"
          >
            <img
              :src="recipe.image_url"
              class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700"
              :alt="recipe.title"
            />
          </div>
          <h3
            class="text-lg md:text-xl font-bold line-clamp-2 h-[3.5rem] mb-2 group-hover:text-orange-600 transition-colors"
          >
            {{ recipe.title }}
          </h3>
          <div class="flex-grow"></div>
          <div
            class="flex items-center gap-4 py-2 mt-4 text-gray-500 text-xs md:text-sm border-t border-black/5 pt-4"
          >
            <div class="flex items-center gap-2">
              <i class="fa-regular fa-clock"></i>
              <span>{{ recipe.prep_time || recipe.cook_time }} Minutes</span>
            </div>
            <div class="flex items-center gap-2">
              <i class="fa-solid fa-utensils"></i>
              <span class="font-medium">{{
                recipe.category?.name || recipe.category || "Lunch"
              }}</span>
            </div>
          </div>
        </div>
      </transition-group>
    </section>
  </div>
</template>

<script>
import api from "../services/api";

export default {
  name: "Home",
  data() {
    return {
      categories: [],
      recipes: [],
      loadingCategories: false,
      loadingRecipes: false,
      selectedCategoryId: null,
    };
  },
  computed: {
    latestRecipe() {
      if (this.recipes.length === 0) return null;
      return [...this.recipes].sort((a, b) => b.id - a.id)[0];
    },

    selectedCategoryName() {
      if (!this.selectedCategoryId) return "";
      const cat = this.categories.find((c) => c.id === this.selectedCategoryId);
      return cat?.name || "";
    },
  },
  methods: {
    async fetchData() {
      this.loadingCategories = true;
      this.loadingRecipes = true;
      try {
        const [catRes, recipeRes] = await Promise.all([
          api.getCategories(),
          api.getRecipes(),
        ]);
        this.categories = catRes.data;

        this.recipes = recipeRes.data?.data ?? recipeRes.data;
      } catch (error) {
        console.error("API Fetch Error:", error);
      } finally {
        this.loadingCategories = false;
        this.loadingRecipes = false;
      }
    },

    async filterByCategory(id) {
      if (this.selectedCategoryId === id) {
        this.clearFilter();
        return;
      }
      this.selectedCategoryId = id;
      this.loadingRecipes = true;
      try {
        const res = await api.getRecipes(id);
        this.recipes = res.data?.data ?? res.data;
      } catch (error) {
        console.error("Filtering Error:", error);
      } finally {
        this.loadingRecipes = false;
      }
    },
    async clearFilter() {
      this.selectedCategoryId = null;
      this.loadingRecipes = true;
      try {
        const res = await api.getRecipes();
        this.recipes = res.data?.data ?? res.data;
      } catch (error) {
        console.error("Clear Filter Error:", error);
      } finally {
        this.loadingRecipes = false;
      }
    },
    goToDetails(id) {
      this.$router.push({ name: "RecipeDetails", params: { id } });
    },
  },
  mounted() {
    this.fetchData();
  },
};
</script>

<style scoped>
.v-move {
  transition: all 0.6s cubic-bezier(0.4, 0, 0.2, 1);
}

.category-card {
  will-change: transform, box-shadow;
}
</style>