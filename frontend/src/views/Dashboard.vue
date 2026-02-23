<template>
  <div
    class="min-h-screen bg-gradient-to-b from-[#E7F9FD] via-white to-white font-['Inter',sans-serif] text-[#1A202C] antialiased pb-20"
  >
    <div
      class="max-w-7xl mx-auto px-4 md:px-6 pt-6 md:pt-10 flex flex-col sm:flex-row justify-between items-center gap-4"
    >
      <h1 class="text-2xl font-black tracking-tight sm:block hidden">
        Dashboard
      </h1>

      <div class="flex items-center gap-3 md:gap-6 w-full sm:w-auto">
        <div
          class="flex-1 sm:flex-none flex items-center bg-gray-100/50 rounded-2xl px-4 py-2 border border-transparent focus-within:border-orange-500 transition-all"
        >
          <svg
            class="text-gray-400 shrink-0"
            width="18"
            height="18"
            viewBox="0 0 24 24"
            fill="none"
            stroke="currentColor"
            stroke-width="2.5"
          >
            <circle cx="11" cy="11" r="8" />
            <line x1="21" y1="21" x2="16.65" y2="16.65" />
          </svg>
          <input
            v-model="search"
            type="text"
            placeholder="Search recipes..."
            class="bg-transparent border-none focus:ring-0 text-sm font-semibold ml-2 w-full sm:w-48 outline-none"
          />
        </div>

        <button
          @click="openModal('create')"
          class="bg-[#1A202C] text-white px-5 py-3 rounded-2xl font-bold text-sm hover:bg-orange-600 transition-all active:scale-95 shadow-lg shadow-gray-200 shrink-0"
        >
          + <span class="hidden sm:inline ml-1">Add Recipe</span>
        </button>
      </div>
    </div>

    <main class="max-w-7xl mx-auto px-4 md:px-6 mt-8 md:mt-10">
      <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 md:gap-6 mb-12">
        <div
          v-for="stat in stats"
          :key="stat.label"
          class="bg-white p-4 md:p-6 rounded-[2rem] md:rounded-[2.5rem] shadow-sm border border-gray-50 flex flex-col sm:flex-row items-center text-center sm:text-left gap-3 md:gap-5 hover:shadow-md transition-shadow"
        >
          <div
            class="w-12 h-12 md:w-14 md:h-14 bg-[#E7F9FD] rounded-[1rem] md:rounded-[1.2rem] flex items-center justify-center text-xl md:text-2xl shadow-inner"
          >
            {{ stat.icon }}
          </div>
          <div>
            <div class="text-xl md:text-2xl font-black leading-none">
              {{ stat.value }}
            </div>
            <div
              class="text-[9px] md:text-[10px] font-black text-gray-400 uppercase tracking-widest mt-1"
            >
              {{ stat.label }}
            </div>
          </div>
        </div>
      </div>

      <div
        class="flex flex-col md:flex-row justify-between items-center mb-10 gap-6"
      >
        <div
          class="flex gap-2 p-1.5 bg-gray-100/50 rounded-[1.5rem] overflow-x-auto w-full md:w-auto no-scrollbar"
        >
          <button
            @click="activeCategory = 'All'"
            :class="
              activeCategory === 'All'
                ? 'bg-white shadow-sm text-black'
                : 'text-gray-500 hover:text-black'
            "
            class="px-5 md:px-6 py-2 rounded-xl font-bold text-sm transition-all whitespace-nowrap"
          >
            All
          </button>
          <button
            v-for="cat in categoryList"
            :key="cat"
            @click="activeCategory = cat"
            :class="
              activeCategory === cat
                ? 'bg-white shadow-sm text-black'
                : 'text-gray-500 hover:text-black'
            "
            class="px-5 md:px-6 py-2 rounded-xl font-bold text-sm transition-all whitespace-nowrap"
          >
            {{ cat }}
          </button>
        </div>

        <div
          class="hidden sm:flex bg-gray-100/50 p-1.5 rounded-2xl border border-gray-100"
        >
          <button
            @click="view = 'grid'"
            :class="
              view === 'grid'
                ? 'bg-white shadow-sm text-orange-500'
                : 'text-gray-400'
            "
            class="p-2.5 rounded-xl transition-all"
          >
            <svg
              width="20"
              height="20"
              viewBox="0 0 24 24"
              fill="none"
              stroke="currentColor"
              stroke-width="2.5"
            >
              <rect x="3" y="3" width="7" height="7" />
              <rect x="14" y="3" width="7" height="7" />
              <rect x="3" y="14" width="7" height="7" />
              <rect x="14" y="14" width="7" height="7" />
            </svg>
          </button>
          <button
            @click="view = 'list'"
            :class="
              view === 'list'
                ? 'bg-white shadow-sm text-orange-500'
                : 'text-gray-400'
            "
            class="p-2.5 rounded-xl transition-all"
          >
            <svg
              width="20"
              height="20"
              viewBox="0 0 24 24"
              fill="none"
              stroke="currentColor"
              stroke-width="2.5"
            >
              <line x1="8" y1="6" x2="21" y2="6" />
              <line x1="8" y1="12" x2="21" y2="12" />
              <line x1="8" y1="18" x2="21" y2="18" />
            </svg>
          </button>
        </div>
      </div>

      <div
        v-if="loading"
        class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 md:gap-8 animate-pulse"
      >
        <div
          v-for="i in 6"
          :key="i"
          class="bg-gray-100/50 rounded-[2.5rem] p-5 h-[400px]"
        >
          <div class="w-full h-48 bg-gray-200 rounded-[2rem] mb-6"></div>
          <div class="h-6 bg-gray-200 rounded-full w-3/4 mb-4 ml-1"></div>
          <div class="h-4 bg-gray-200 rounded-full w-full mb-2 ml-1"></div>
          <div class="h-4 bg-gray-200 rounded-full w-5/6 ml-1"></div>
        </div>
      </div>

      <transition-group
        v-else-if="view === 'grid'"
        tag="div"
        enter-active-class="transition duration-500 ease-out"
        enter-from-class="opacity-0 translate-y-4"
        enter-to-class="opacity-100 translate-y-0"
        class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 md:gap-8"
      >
        <div
          v-for="recipe in paginatedRecipes"
          :key="recipe.id"
          class="group bg-white rounded-[2.5rem] p-5 shadow-sm border border-gray-100 hover:shadow-2xl hover:-translate-y-2 transition-all duration-300 relative"
        >
          <div
            class="relative h-48 md:h-56 mb-6 overflow-hidden rounded-[2rem] bg-gray-50"
          >
            <img
              v-if="recipe.image_url"
              :src="recipe.image_url"
              class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110"
            />
            <div
              v-else
              class="w-full h-full flex items-center justify-center text-4xl font-black text-gray-200"
            >
              ?
            </div>
            <div
              class="absolute top-4 left-4 bg-white/90 backdrop-blur-md px-4 py-1.5 rounded-full text-[10px] font-black uppercase tracking-wider text-orange-600 shadow-sm"
            >
              {{ recipe.category?.name || "Recipe" }}
            </div>
            <div
              class="absolute inset-0 bg-black/20 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center gap-3"
            >
              <button
                @click.stop="openModal('edit', recipe)"
                class="bg-white p-3 rounded-full hover:bg-orange-500 hover:text-white transition-all transform hover:scale-110 shadow-lg"
              >
                <i class="fas fa-edit"></i>
              </button>
              <button
                @click.stop="handleDelete(recipe)"
                class="bg-white p-3 rounded-full hover:bg-red-500 hover:text-white transition-all transform hover:scale-110 shadow-lg"
              >
                <i class="fas fa-trash"></i>
              </button>
            </div>
          </div>
          <h3
            class="text-lg md:text-xl font-black mb-2 px-1 line-clamp-1 group-hover:text-orange-600 transition-colors"
          >
            {{ recipe.title }}
          </h3>
          <p
            class="text-gray-400 text-sm px-1 line-clamp-2 leading-relaxed mb-6"
          >
            {{ recipe.description }}
          </p>
          <div
            class="flex items-center gap-4 px-1 pt-5 border-t border-gray-50"
          >
            <div
              class="flex items-center gap-2 text-[10px] font-black text-gray-400 uppercase tracking-widest"
            >
              <i class="fa-regular fa-clock text-orange-500 text-sm"></i>
              {{ recipe.prep_time }}m
            </div>
            <div
              class="flex items-center gap-2 text-[10px] font-black text-gray-400 uppercase tracking-widest"
            >
              <i class="fa-solid fa-fire text-orange-500 text-sm"></i>
              {{ recipe.cook_time }}m
            </div>
          </div>
        </div>
      </transition-group>

      <div
        v-else
        class="bg-white rounded-[2rem] md:rounded-[2.5rem] border border-gray-100 shadow-sm overflow-hidden"
      >
        <div class="overflow-x-auto">
          <table class="w-full text-left border-collapse min-w-[600px]">
            <thead
              class="bg-gray-50/50 text-[10px] font-black uppercase tracking-[0.2em] text-gray-400"
            >
              <tr>
                <th class="p-6 md:p-8">Recipe</th>
                <th class="p-6 md:p-8">Category</th>
                <th class="p-6 md:p-8">Total Time</th>
                <th class="p-6 md:p-8 text-right">Actions</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
              <tr
                v-for="recipe in paginatedRecipes"
                :key="recipe.id"
                class="hover:bg-gray-50/30 transition-colors group"
              >
                <td class="p-6 md:p-8 flex items-center gap-4">
                  <div
                    class="w-12 h-12 rounded-xl bg-gray-100 overflow-hidden shrink-0 shadow-inner"
                  >
                    <img
                      v-if="recipe.image_url"
                      :src="recipe.image_url"
                      class="w-full h-full object-cover"
                    />
                  </div>
                  <span
                    class="font-black text-sm group-hover:text-orange-600 transition-colors"
                    >{{ recipe.title }}</span
                  >
                </td>
                <td class="p-6 md:p-8">
                  <span
                    class="bg-[#E7F9FD] text-orange-600 text-[9px] font-black px-3 py-1 rounded-full uppercase"
                    >{{ recipe.category?.name || "Misc" }}</span
                  >
                </td>
                <td class="p-6 md:p-8 text-sm font-bold text-gray-400">
                  {{
                    (parseInt(recipe.prep_time) || 0) +
                    (parseInt(recipe.cook_time) || 0)
                  }}m
                </td>
                <td class="p-6 md:p-8 text-right">
                  <div class="flex justify-end gap-2">
                    <button
                      @click="openModal('edit', recipe)"
                      class="p-2 text-gray-300 hover:text-orange-500 transition-colors"
                    >
                      <i class="fas fa-edit"></i>
                    </button>
                    <button
                      @click="handleDelete(recipe)"
                      class="p-2 text-gray-300 hover:text-red-500 transition-colors"
                    >
                      <i class="fas fa-trash"></i>
                    </button>
                  </div>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>

      <div
        v-if="totalPages > 1"
        class="flex flex-wrap justify-center items-center gap-2 md:gap-3 mt-12"
      >
        <button
          :disabled="page === 1"
          @click="page--"
          class="w-10 h-10 md:w-12 md:h-12 flex items-center justify-center bg-white rounded-xl md:rounded-2xl shadow-sm border border-gray-100 disabled:opacity-20 transition-colors"
        >
          ←
        </button>
        <button
          v-for="p in totalPages"
          :key="p"
          @click="page = p"
          :class="
            p === page
              ? 'bg-[#1A202C] text-white w-10 md:w-12 shadow-lg shadow-gray-200'
              : 'bg-white text-gray-400 w-8 md:w-10'
          "
          class="h-10 md:h-12 rounded-xl md:rounded-2xl font-black text-xs transition-all flex items-center justify-center border border-gray-100"
        >
          {{ p }}
        </button>
        <button
          :disabled="page === totalPages"
          @click="page++"
          class="w-10 h-10 md:w-12 md:h-12 flex items-center justify-center bg-white rounded-xl md:rounded-2xl shadow-sm border border-gray-100 disabled:opacity-20 transition-colors"
        >
          →
        </button>
      </div>
    </main>

    <div
      v-if="modalOpen"
      class="fixed inset-0 z-[100] flex items-center justify-center p-0 sm:p-4"
    >
      <div
        class="absolute inset-0 bg-[#1A202C]/30 backdrop-blur-md"
        @click="closeModal"
      ></div>
      <div
        class="relative bg-white w-full h-full sm:h-auto sm:max-w-2xl sm:rounded-[3rem] shadow-2xl overflow-hidden flex flex-col max-h-[100vh] sm:max-h-[90vh]"
      >
        <div class="p-6 md:p-12 overflow-y-auto">
          <div class="flex justify-between items-start mb-8 md:mb-10">
            <div>
              <span
                class="text-[10px] font-black text-orange-500 uppercase tracking-[0.2em]"
                >{{ modalMode }} recipe</span
              >
              <h2 class="text-2xl md:text-3xl font-black tracking-tighter mt-1">
                {{ modalMode === "edit" ? "Update Entry" : "Create New Dish" }}
              </h2>
            </div>
            <button
              @click="closeModal"
              class="p-3 bg-gray-100 rounded-2xl hover:bg-gray-200 transition-colors"
            >
              <i class="fas fa-times"></i>
            </button>
          </div>

          <form @submit.prevent="saveRecipe" class="space-y-5 md:space-y-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5 md:gap-6">
              <div class="space-y-2">
                <label
                  class="text-[10px] font-black uppercase tracking-widest text-gray-400 ml-1"
                  >Recipe Title</label
                >
                <input
                  v-model="form.title"
                  class="w-full bg-gray-50 border-none rounded-2xl py-4 px-6 focus:ring-2 focus:ring-orange-500 outline-none font-bold text-sm"
                  placeholder="Pasta Carbonara..."
                  required
                />
              </div>
              <div class="space-y-2">
                <label
                  class="text-[10px] font-black uppercase tracking-widest text-gray-400 ml-1"
                  >Category</label
                >
                <select
                  v-model="form.category_id"
                  class="w-full bg-gray-50 border-none rounded-2xl py-4 px-6 focus:ring-2 focus:ring-orange-500 outline-none font-bold text-sm cursor-pointer appearance-none"
                  required
                >
                  <option value="" disabled>Select a category</option>
                  <option
                    v-for="cat in categories"
                    :key="cat.id"
                    :value="cat.id"
                  >
                    {{ cat.name }}
                  </option>
                </select>
              </div>
            </div>

            <div class="grid grid-cols-2 gap-5 md:gap-6">
              <div class="space-y-2">
                <label
                  class="text-[10px] font-black uppercase tracking-widest text-gray-400 ml-1"
                  >Prep (Min)</label
                >
                <input
                  v-model="form.prep_time"
                  type="number"
                  class="w-full bg-gray-50 border-none rounded-2xl py-4 px-6 focus:ring-2 focus:ring-orange-500 font-bold text-sm"
                />
              </div>
              <div class="space-y-2">
                <label
                  class="text-[10px] font-black uppercase tracking-widest text-gray-400 ml-1"
                  >Cook (Min)</label
                >
                <input
                  v-model="form.cook_time"
                  type="number"
                  class="w-full bg-gray-50 border-none rounded-2xl py-4 px-6 focus:ring-2 focus:ring-orange-500 font-bold text-sm"
                />
              </div>
            </div>

            <div class="space-y-2">
              <label
                class="text-[10px] font-black uppercase tracking-widest text-gray-400 ml-1"
                >Image URL</label
              >
              <input
                v-model="form.image_url"
                class="w-full bg-gray-50 border-none rounded-2xl py-4 px-6 focus:ring-2 focus:ring-orange-500 font-bold text-sm"
                placeholder="https://images..."
              />
            </div>

            <div class="space-y-2">
              <label
                class="text-[10px] font-black uppercase tracking-widest text-gray-400 ml-1"
                >Ingredients</label
              >
              <textarea
                v-model="form.ingredients"
                rows="3"
                class="w-full bg-gray-50 border-none rounded-2xl py-4 px-6 focus:ring-2 focus:ring-orange-500 font-bold text-sm"
                placeholder="List ingredients separated by commas..."
              ></textarea>
            </div>

            <div class="space-y-2">
              <label
                class="text-[10px] font-black uppercase tracking-widest text-gray-400 ml-1"
                >Instructions / Description</label
              >
              <textarea
                v-model="form.description"
                rows="3"
                class="w-full bg-gray-50 border-none rounded-2xl py-4 px-6 focus:ring-2 focus:ring-orange-500 font-bold text-sm"
              ></textarea>
            </div>

            <div
              class="flex flex-col-reverse sm:flex-row justify-end gap-3 pt-6"
            >
              <button
                type="button"
                @click="closeModal"
                class="px-8 py-4 font-black text-[10px] uppercase tracking-widest text-gray-400 hover:text-black transition-colors"
              >
                Cancel
              </button>
              <button
                type="submit"
                :disabled="saving"
                class="bg-orange-500 text-white px-10 py-4 rounded-3xl font-black text-[10px] uppercase tracking-widest shadow-lg shadow-orange-200 hover:bg-orange-600 transition-all active:scale-95 flex items-center justify-center gap-3"
              >
                <i v-if="saving" class="fas fa-spinner fa-spin"></i>
                {{ modalMode === "edit" ? "Update Recipe" : "Publish Recipe" }}
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import api from "../services/api";
import { useAuthStore } from "../stores/auth";
import Swal from "sweetalert2/dist/sweetalert2.js";
import "sweetalert2/dist/sweetalert2.css";

export default {
  data() {
    return {
      auth: useAuthStore(),
      recipes: [],
      categories: [],
      loading: false,
      saving: false,
      search: "",
      activeCategory: "All",
      view: "grid",
      page: 1,
      perPage: 9,
      modalOpen: false,
      modalMode: "create",
      currentRecipe: null,
      form: this.blankForm(),
    };
  },
  computed: {
    stats() {
      return [
        { label: "Total Recipes", value: this.recipes.length, icon: "🍳" },
        { label: "Avg Prep", value: this.avgTime + "m", icon: "⏱️" },
        { label: "Categories", value: this.categories.length, icon: "📂" },
        { label: "User", value: this.auth.user?.name || "Chef", icon: "👤" },
      ];
    },
    categoryList() {
      return this.categories.map((c) => c.name);
    },
    filtered() {
      let list = [...this.recipes];
      if (this.search) {
        const q = this.search.toLowerCase();
        list = list.filter((r) => r.title?.toLowerCase().includes(q));
      }
      if (this.activeCategory !== "All") {
        list = list.filter(
          (r) => (r.category?.name || r.category) === this.activeCategory
        );
      }
      return list;
    },
    totalPages() {
      return Math.ceil(this.filtered.length / this.perPage);
    },
    paginatedRecipes() {
      const s = (this.page - 1) * this.perPage;
      return this.filtered.slice(s, s + this.perPage);
    },
    avgTime() {
      if (!this.recipes.length) return 0;
      const total = this.recipes.reduce(
        (acc, r) => acc + (parseInt(r.prep_time) || 0),
        0
      );
      return Math.round(total / this.recipes.length);
    },
  },
  mounted() {
    this.fetchData();
  },
  methods: {
    blankForm() {
      return {
        title: "",
        ingredients: "",
        description: "",
        prep_time: 0,
        cook_time: 0,
        image_url: "",
        category_id: "",
      };
    },

    // ✅ Nouvelle méthode pour les alertes
    showAlert(message, type = "error") {
      Swal.fire({
        icon: type,
        title: type === "error" ? "Error" : "Success",
        text: message,
        confirmButtonColor: "#f97316",
        confirmButtonText: "OK",
        timer: type === "success" ? 3000 : null,
        timerProgressBar: type === "success",
      });
    },

    // ✅ Nouvelle méthode pour la confirmation
    showConfirm(message) {
      return Swal.fire({
        title: "Confirmation",
        text: message,
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#ef4444", // red-500
        cancelButtonColor: "#6b7280", // gray-500
        confirmButtonText: "Yes, Delete",
        cancelButtonText: "Cancel",
        reverseButtons: true,
      });
    },

    async fetchData() {
      this.loading = true;
      try {
        const [recipeRes, catRes] = await Promise.all([
          api.getRecipes(),
          api.getCategories(),
        ]);
        this.recipes = recipeRes.data.data || recipeRes.data;
        this.categories = catRes.data.data || catRes.data;
      } catch (e) {
        console.error("Dashboard Load Error:", e);
        this.showAlert("Impossible to load data", "error");
      } finally {
        this.loading = false;
      }
    },

    openModal(mode, recipe = null) {
      this.modalMode = mode;
      this.modalOpen = true;
      if (mode === "edit" && recipe) {
        this.currentRecipe = recipe;
        this.form = {
          ...recipe,
          category_id: recipe.category?.id || recipe.category_id,
        };
      } else {
        this.currentRecipe = null;
        this.form = this.blankForm();
      }
    },

    closeModal() {
      this.modalOpen = false;
    },

    async saveRecipe() {
      this.saving = true;
      try {
        const selectedCat = this.categories.find(
          (c) => c.id === this.form.category_id
        );

        const payload = {
          ...this.form,
          category: selectedCat?.name || "",
        };

        let res;
        if (this.modalMode === "create") {
          res = await api.createRecipe(payload);
          this.recipes.unshift(res.data.data || res.data);
          this.showAlert("Recette successfully created !", "success");
        } else {
          res = await api.updateRecipe(this.currentRecipe.id, payload);
          const i = this.recipes.findIndex(
            (r) => r.id === this.currentRecipe.id
          );
          this.recipes.splice(i, 1, res.data.data || res.data);
          this.showAlert("Recette successfully updated !", "success");
        }
        this.closeModal();
      } catch (e) {
        const message = e.response?.data?.message || "Action failed";
        this.showAlert(message, "error");
      } finally {
        this.saving = false;
      }
    },

    async handleDelete(recipe) {
      const result = await this.showConfirm(
        `Are you sure you want to delete -- ${recipe.title} -- Recipe ?`
      );

      if (result.isConfirmed) {
        try {
          await api.deleteRecipe(recipe.id);
          this.recipes = this.recipes.filter((r) => r.id !== recipe.id);
          this.showAlert("Recipe successfully removed !", "success");
        } catch (e) {
          this.showAlert("Deletion failed", "error");
        }
      }
    },
  },
};
</script>

<style scoped>
/* Custom utility for clean UI */
.no-scrollbar::-webkit-scrollbar {
  display: none;
}
.no-scrollbar {
  -ms-overflow-style: none;
  scrollbar-width: none;
}

/* Modal animation */
.fade-enter-active,
.fade-leave-active {
  transition: opacity 0.3s ease;
}
.fade-enter-from,
.fade-leave-to {
  opacity: 0;
}
</style>