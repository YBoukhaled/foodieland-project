<template>
  <div class="min-h-screen bg-white font-sans selection:bg-red-100 w-full mx-auto max-w-[1280px]">
    <div
      v-if="loading"
      class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 animate-pulse space-y-8"
    >
      <div class="h-12 w-3/4 bg-gray-200 rounded-xl"></div>
      <div class="grid lg:grid-cols-3 gap-8">
        <div class="lg:col-span-2 aspect-video bg-gray-200 rounded-3xl"></div>
        <div class="h-full bg-gray-100 rounded-3xl min-h-[400px]"></div>
      </div>
    </div>

    <div v-else-if="error" class="max-w-xl mx-auto px-4 py-24 text-center">
      <div class="text-7xl mb-6">🍳</div>
      <h2 class="text-3xl font-bold mb-3 text-gray-900">Recipe not found</h2>
      <p class="text-gray-500 mb-8 text-lg">
        We couldn't load this deliciousness. Let's try getting back into the
        kitchen.
      </p>
      <button
        @click="fetchRecipe"
        class="px-8 py-4 text-white rounded-full font-bold transition-all hover:scale-105 active:scale-95 shadow-lg shadow-red-200"
        style="background: #ff6b6b"
      >
        Try Again
      </button>
    </div>

    <div v-else-if="recipe" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
      <div class="mb-10">
        <h1
          class="text-3xl md:text-5xl lg:text-6xl font-extrabold text-gray-900 mb-8 leading-tight"
        >
          {{ recipe.title }}
        </h1>

        <div
          class="flex flex-col md:flex-row md:items-center justify-between gap-6 border-b border-gray-100 pb-8"
        >
          <div class="flex flex-wrap items-center gap-6 md:gap-10">
            <div class="flex items-center gap-3">
              <img
                :src="authorAvatar"
                class="w-12 h-12 rounded-full object-cover ring-2 ring-gray-50"
              />
              <div>
                <p class="text-sm font-bold text-gray-900">{{ authorName }}</p>
                <p class="text-xs text-gray-500">{{ authorDate }}</p>
              </div>
            </div>

            <div class="flex items-center gap-6 text-gray-500">
              <div class="flex items-center gap-2">
                <i class="far fa-clock text-red-400"></i>
                <div class="text-xs uppercase tracking-wider">
                  <span class="block text-gray-400">Prep</span>
                  <span class="font-bold text-gray-900">{{
                    recipe.prep_time || recipe.prepTime || "—"
                  }}</span>
                </div>
              </div>
              <div class="flex items-center gap-2">
                <i class="fas fa-fire text-red-400"></i>
                <div class="text-xs uppercase tracking-wider">
                  <span class="block text-gray-400">Cook</span>
                  <span class="font-bold text-gray-900">{{
                    recipe.cook_time || recipe.cookTime || "—"
                  }}</span>
                </div>
              </div>
              <div class="flex items-center gap-2">
                <i class="fas fa-utensils text-red-400"></i>
                <div class="text-xs uppercase tracking-wider">
                  <span class="block text-gray-400">Type</span>
                  <span class="font-bold text-gray-900">{{
                    categoryName
                  }}</span>
                </div>
              </div>
            </div>
          </div>

          <button
            @click="shareRecipe"
            class="w-12 h-12 rounded-full bg-gray-50 flex items-center justify-center text-gray-500 hover:bg-red-50 hover:text-[#FF6B6B] transition-all"
          >
            <i class="fas fa-share-alt"></i>
          </button>
        </div>
      </div>

      <div class="grid lg:grid-cols-3 gap-8 mb-16">
        <div
          class="lg:col-span-2 relative rounded-[2rem] overflow-hidden shadow-2xl group"
        >
          <!-- Video player (shown when showVideo is true) -->
          <div
            v-if="showVideo"
            class="absolute inset-0 w-full h-full"
            style="background: #000"
          >
            <iframe
              :src="'https://www.youtube.com/embed/dA0VGEbbw4g?autoplay=1&rel=0&modestbranding=1&playsinline=1'"
              class="w-full h-full"
              frameborder="0"
              allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
              allowfullscreen
              style="
                position: absolute;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
              "
            ></iframe>
          </div>

          <!-- Thumbnail image (shown when showVideo is false) -->
          <img
            v-else
            :src="
              recipe.image_url ||
              recipe.image ||
              'https://images.unsplash.com/photo-1603133872878-684f208fb74b?w=1200'
            "
            :alt="recipe.title"
            class="w-full h-full object-cover min-h-[300px] md:min-h-[500px]"
          />

          <!-- Play button (only shown when video is not playing) -->
          <button
            v-if="!showVideo"
            @click="showVideo = true"
            class="absolute inset-0 flex items-center justify-center bg-black/10 group-hover:bg-black/20 transition-all"
          >
            <div
              class="w-20 h-20 rounded-full flex items-center justify-center text-2xl shadow-2xl scale-90 group-hover:scale-100 transition-transform bg-white/90 backdrop-blur-md text-[#ff6b6b]"
            >
              <i class="fas fa-play ml-1"></i>
            </div>
          </button>
        </div>

        <div
          class="rounded-[2rem] p-8 bg-[#E7FAFE] flex flex-col justify-between"
        >
          <div>
            <h3 class="text-xl font-bold text-gray-900 mb-8">
              Nutrition Information
            </h3>
            <div class="space-y-5">
              <div
                v-for="row in nutritionRows"
                :key="row.label"
                class="flex justify-between items-center py-2 border-b border-gray-200/50 last:border-0"
              >
                <span class="text-gray-600 font-medium">{{ row.label }}</span>
                <span class="font-bold text-gray-900">{{ row.value }}</span>
              </div>
            </div>
          </div>
          <p class="text-sm text-gray-400 mt-10 text-center italic">
            Nutritional values are per serving and may vary based on ingredients
            used.
          </p>
        </div>
      </div>

      <div class="grid lg:grid-cols-3 gap-12 lg:gap-16">
        <div class="lg:col-span-2">
          <div class="prose prose-lg max-w-none mb-12">
            <h2 class="text-3xl font-bold text-gray-900 mb-6">Description</h2>
            <p class="text-gray-600 leading-relaxed">
              {{ recipe.description }}
            </p>
          </div>

          <div class="mb-16">
            <h2 class="text-3xl font-bold text-gray-900 mb-8">Ingredients</h2>

            <div class="space-y-10">
              <div v-if="mainIngredients.length">
                <h3
                  class="text-lg font-black text-gray-900 mb-6 uppercase tracking-widest border-l-4 border-red-400 pl-4"
                >
                  Main Course
                </h3>
                <div class="grid sm:grid-cols-2 gap-4">
                  <div
                    v-for="(item, i) in mainIngredients"
                    :key="'m' + i"
                    @click="toggleMain(i)"
                    class="flex items-center gap-4 p-4 rounded-2xl border border-transparent hover:border-gray-100 hover:bg-gray-50 cursor-pointer transition-all"
                  >
                    <div
                      class="w-6 h-6 rounded-full border-2 flex-shrink-0 flex items-center justify-center transition-all"
                      :class="
                        checkedMain.includes(i)
                          ? 'bg-[#FF6B6B] border-[#FF6B6B]'
                          : 'border-gray-300'
                      "
                    >
                      <i
                        v-if="checkedMain.includes(i)"
                        class="fas fa-check text-white text-[10px]"
                      ></i>
                    </div>
                    <span
                      class="text-gray-700"
                      :class="{
                        'line-through text-gray-400': checkedMain.includes(i),
                      }"
                    >
                      {{ item }}
                    </span>
                  </div>
                </div>
              </div>

              <div v-if="sauceIngredients.length">
                <h3
                  class="text-lg font-black text-gray-900 mb-6 uppercase tracking-widest border-l-4 border-red-400 pl-4"
                >
                  The Sauce
                </h3>
                <div class="grid sm:grid-cols-2 gap-4">
                  <div
                    v-for="(item, i) in sauceIngredients"
                    :key="'s' + i"
                    @click="toggleSauce(i)"
                    class="flex items-center gap-4 p-4 rounded-2xl border border-transparent hover:border-gray-100 hover:bg-gray-50 cursor-pointer transition-all"
                  >
                    <div
                      class="w-6 h-6 rounded-full border-2 flex-shrink-0 flex items-center justify-center transition-all"
                      :class="
                        checkedSauce.includes(i)
                          ? 'bg-[#FF6B6B] border-[#FF6B6B]'
                          : 'border-gray-300'
                      "
                    >
                      <i
                        v-if="checkedSauce.includes(i)"
                        class="fas fa-check text-white text-[10px]"
                      ></i>
                    </div>
                    <span
                      class="text-gray-700"
                      :class="{
                        'line-through text-gray-400': checkedSauce.includes(i),
                      }"
                    >
                      {{ item }}
                    </span>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <div>
            <h2 class="text-3xl font-bold text-gray-900 mb-10">Directions</h2>
            <div class="space-y-12">
              <div
                v-for="(step, i) in directionSteps"
                :key="'step' + i"
                class="flex flex-col sm:flex-row gap-6"
              >
                <div class="flex-shrink-0">
                  <div
                    class="w-10 h-10 rounded-full text-white flex items-center justify-center text-lg font-bold shadow-lg shadow-red-100 bg-gradient-to-br from-[#ff6b6b] to-[#ff8e8e]"
                  >
                    {{ i + 1 }}
                  </div>
                </div>
                <div
                  class="flex-1 pb-10 border-b border-gray-100 last:border-0"
                >
                  <h4 class="text-xl font-bold text-gray-900 mb-4">
                    {{ step.title }}
                  </h4>
                  <p class="text-gray-600 leading-relaxed mb-6">
                    {{ step.text }}
                  </p>

                  <div
                    v-if="i === 0"
                    class="rounded-3xl overflow-hidden shadow-lg max-w-2xl"
                  >
                    <img
                      :src="
                        recipe.videoThumbnail ||
                        recipe.image_url ||
                        recipe.image
                      "
                      class="w-full object-cover max-h-[350px]"
                    />
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="lg:sticky lg:top-8 h-fit">
          <div class="bg-gray-50 rounded-[2rem] p-8 border border-gray-100">
            <h3 class="text-2xl font-bold text-gray-900 mb-8">
              You may also like
            </h3>

            <div v-if="loadingSimilar" class="space-y-6">
              <div v-for="n in 3" :key="n" class="flex gap-4 animate-pulse">
                <div
                  class="w-24 h-24 rounded-2xl bg-gray-200 flex-shrink-0"
                ></div>
                <div class="flex-1 space-y-3 pt-2">
                  <div class="h-4 bg-gray-200 rounded w-full"></div>
                  <div class="h-4 bg-gray-100 rounded w-2/3"></div>
                </div>
              </div>
            </div>

            <div v-else class="space-y-8">
              <div
                v-for="other in similarRecipes"
                :key="other.id"
                class="flex gap-4 group cursor-pointer"
                @click="$router.push('/recipe/' + other.id)"
              >
                <div
                  class="w-24 h-24 rounded-2xl overflow-hidden flex-shrink-0 shadow-sm group-hover:shadow-md transition-shadow"
                >
                  <img
                    :src="
                      other.image_url ||
                      other.image ||
                      'https://images.unsplash.com/photo-1529042410759-befb1204b468?w=200'
                    "
                    class="w-full h-full object-cover transform group-hover:scale-110 transition-transform duration-500"
                  />
                </div>
                <div class="flex flex-col justify-center">
                  <h4
                    class="font-bold text-gray-900 group-hover:text-[#FF6B6B] transition-colors line-clamp-2 leading-snug"
                  >
                    {{ other.title }}
                  </h4>
                  <p class="text-sm text-gray-400 mt-2 font-medium">
                    By {{ other.author?.name || "Foodieland" }}
                  </p>
                </div>
              </div>
              <p
                v-if="!similarRecipes.length"
                class="text-sm text-gray-400 text-center py-4"
              >
                No related recipes found.
              </p>
            </div>
          </div>
        </div>
      </div>
    </div>

    <Transition name="fade">
      <div
        v-if="showVideo"
        class="fixed inset-0 z-[100] flex items-center justify-center p-4 md:p-10 bg-black/95 backdrop-blur-sm"
        @click.self="showVideo = false"
      >
        <div
          class="relative w-full max-w-5xl aspect-video bg-black rounded-3xl overflow-hidden shadow-2xl"
        >
          <button
            @click="showVideo = false"
            class="absolute top-6 right-6 z-10 w-12 h-12 rounded-full bg-white/10 hover:bg-white/20 text-white transition-all"
          >
            <i class="fas fa-times text-xl"></i>
          </button>
          <div class="w-full h-full flex items-center justify-center">
            <p class="text-white text-xl font-medium tracking-widest uppercase">
              Video Player Loading...
            </p>
          </div>
        </div>
      </div>
    </Transition>
  </div>
</template>

<script>
import axios from "axios";

const api = axios.create({
  baseURL: "http://127.0.0.1:8000/api",
  withCredentials: true,
  headers: {
    Accept: "application/json",
    "Content-Type": "application/json",
    "X-Requested-With": "XMLHttpRequest",
  },
});

export default {
  name: "RecipeDetails",

  data() {
    return {
      recipe: null,
      similarRecipes: [],
      loading: true,
      loadingSimilar: false,
      error: false,
      showVideo: false,
      checkedMain: [],
      checkedSauce: [],
      nutritionRows: [
        { label: "Calories", value: "219.9 kcal" },
        { label: "Total Fat", value: "10.7 g" },
        { label: "Protein", value: "7.9 g" },
        { label: "Carbohydrate", value: "22.3 g" },
        { label: "Cholesterol", value: "37.4 mg" },
        { label: "Sodium", value: "115.2 mg" },
      ],
    };
  },

  computed: {
    recipeId() {
      return this.$route.params.id;
    },

    categoryName() {
      return this.recipe?.category?.name || this.recipe?.category || "General";
    },

    authorName() {
      return this.recipe?.author?.name || "Foodieland";
    },

    authorAvatar() {
      return (
        this.recipe?.author?.avatar ||
        "https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=100"
      );
    },

    authorDate() {
      if (this.recipe?.author?.date) return this.recipe.author.date;
      if (this.recipe?.created_at) {
        return new Date(this.recipe.created_at).toLocaleDateString("en-US", {
          day: "numeric",
          month: "long",
          year: "numeric",
        });
      }
      return "";
    },

    mainIngredients() {
      if (!this.recipe) return [];
      if (Array.isArray(this.recipe.ingredients?.main))
        return this.recipe.ingredients.main;
      if (typeof this.recipe.ingredients === "string") {
        const lines = this.recipe.ingredients
          .split("\n")
          .map((l) => l.trim())
          .filter(Boolean);
        return lines.slice(0, Math.ceil(lines.length / 2));
      }
      return [];
    },

    sauceIngredients() {
      if (!this.recipe) return [];
      if (Array.isArray(this.recipe.ingredients?.sauce))
        return this.recipe.ingredients.sauce;
      if (typeof this.recipe.ingredients === "string") {
        const lines = this.recipe.ingredients
          .split("\n")
          .map((l) => l.trim())
          .filter(Boolean);
        return lines.slice(Math.ceil(lines.length / 2));
      }
      return [];
    },

    directionSteps() {
      if (!this.recipe) return [];
      if (Array.isArray(this.recipe.directions)) {
        return this.recipe.directions.map((s, i) =>
          typeof s === "string" ? { title: `Step ${i + 1}`, text: s } : s
        );
      }
      if (typeof this.recipe.directions === "string") {
        return this.recipe.directions
          .split("\n")
          .filter(Boolean)
          .map((text, i) => ({ title: `Step ${i + 1}`, text }));
      }
      return [];
    },
  },

  watch: {
    "$route.params.id"() {
      this.init();
    },
  },

  mounted() {
    this.init();
  },

  methods: {
    async init() {
      window.scrollTo(0, 0);
      this.loading = true;
      this.error = false;
      this.recipe = null;
      this.similarRecipes = [];
      this.checkedMain = [];
      this.checkedSauce = [];
      await this.fetchRecipe();
    },

    async fetchRecipe() {
      try {
        const res = await api.get(`/recipes/${this.recipeId}`);
        this.recipe = res.data?.data ?? res.data;
        this.fetchSimilar();
      } catch (e) {
        console.error(e);
        this.error = true;
      } finally {
        this.loading = false;
      }
    },

    async fetchSimilar() {
      this.loadingSimilar = true;
      try {
        const res = await api.get("/recipes");
        const all = res.data?.data ?? res.data;
        const catId = this.recipe.category_id || this.recipe.category?.id;
        const others = all.filter((r) => r.id !== this.recipe.id);
        if (catId) {
          const sameCat = others.filter(
            (r) => (r.category_id || r.category?.id) === catId
          );
          this.similarRecipes = (sameCat.length ? sameCat : others).slice(0, 3);
        } else {
          this.similarRecipes = others.slice(0, 3);
        }
      } catch (e) {
        console.error(e);
      } finally {
        this.loadingSimilar = false;
      }
    },

    toggleMain(i) {
      const idx = this.checkedMain.indexOf(i);
      idx === -1 ? this.checkedMain.push(i) : this.checkedMain.splice(idx, 1);
    },

    toggleSauce(i) {
      const idx = this.checkedSauce.indexOf(i);
      idx === -1 ? this.checkedSauce.push(i) : this.checkedSauce.splice(idx, 1);
    },

    shareRecipe() {
      if (navigator.share) {
        navigator.share({
          title: this.recipe?.title,
          url: window.location.href,
        });
      } else {
        navigator.clipboard?.writeText(window.location.href);
        alert("Link copied to clipboard!");
      }
    },
  },
};
</script>

<style scoped>
.fade-enter-active,
.fade-leave-active {
  transition: opacity 0.4s ease;
}
.fade-enter-from,
.fade-leave-to {
  opacity: 0;
}

::-webkit-scrollbar {
  width: 8px;
}
::-webkit-scrollbar-track {
  background: #f1f1f1;
}
::-webkit-scrollbar-thumb {
  background: #ff6b6b;
  border-radius: 10px;
}
</style>