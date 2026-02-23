import { defineStore } from 'pinia'
import axios from 'axios'

export const useRecipeStore = defineStore('recipes', {
    state: () => ({
        recipes: [],
        loading: false
    }),
    actions: {
        // ACTION : Utilise Axios pour remplir le State
        async fetchRecipes() {
            this.loading = true
            try {
                // On appelle ton backend Docker
                const response = await axios.get('http://127.0.0.1:8000/api/recipes')
                // On stocke le résultat dans le state
                this.recipes = response.data 
            } catch (error) {
                console.error("Erreur Axios :", error)
            } finally {
                this.loading = false
            }
        }
    }
})