<template>
  <footer class="bg-white pt-12 md:pt-20 pb-10 border-t border-gray-100">
    <div class="max-w-7xl mx-auto px-4">
      <div
        class="flex flex-col md:flex-row justify-between items-center md:items-start text-center md:text-left gap-10 md:gap-12 mb-12 md:mb-16"
      >
        <div class="max-w-2xl">
          <div
            class="flex items-center justify-center md:justify-start gap-2 mb-6"
          >
            <div
              class="w-10 h-10 bg-[#FF6B6B] rounded-xl flex items-center justify-center text-white font-bold text-xl"
            >
              F
            </div>
            <span class="text-2xl font-bold text-gray-800">Foodieland</span>
          </div>
          <p class="text-gray-500 leading-relaxed text-sm md:text-base">
            Foodieland is a modern, high-end recipe platform designed for the
            passionate home cook. It bridges the gap between professional
            culinary techniques and everyday convenience, offering a curated
            collection of diverse, "simple and tasty" recipes.
          </p>
        </div>

        <div class="w-full md:w-auto">
          <div class="flex justify-center md:justify-start gap-8">
            <a
              href="#"
              class="text-gray-400 hover:text-[#FF6B6B] text-xl transition-colors"
              ><i class="fab fa-facebook-f"></i
            ></a>
            <a
              href="#"
              class="text-gray-400 hover:text-[#FF6B6B] text-xl transition-colors"
              ><i class="fab fa-twitter"></i
            ></a>
            <a
              href="#"
              class="text-gray-400 hover:text-[#FF6B6B] text-xl transition-colors"
              ><i class="fab fa-instagram"></i
            ></a>
          </div>
        </div>
      </div>

      <div
        class="pt-8 border-t border-gray-100 flex flex-col md:flex-row justify-center items-center gap-4 text-xs md:text-sm text-gray-400"
      >
        <p>
          © {{ new Date().getFullYear() }} Foodieland. Tous droits réservés.
        </p>
      </div>
    </div>
  </footer>
</template>