import { defineStore } from 'pinia';
import api from '../services/api'; 

export const useAuthStore = defineStore('auth', {
    state: () => ({
        // Re-hydrate state from localStorage on app load
        user: JSON.parse(localStorage.getItem('user')) || null,
        token: localStorage.getItem('token') || null,
        isAuthenticated: !!localStorage.getItem('token'),
    }),

    actions: {
        async login(credentials) {
            try {
                const response = await api.login(credentials);
                
                /**
                 * JWT TIP: 
                 * Some backends return the token as 'token', others as 'access_token'.
                 * We handle both just in case.
                 */
                const userData = response.data.user;
                const tokenData = response.data.token || response.data.access_token;

                if (!tokenData) throw new Error("No token received from server");

                // Update Pinia State
                this.user = userData;
                this.token = tokenData;
                this.isAuthenticated = true;

                // Persist to LocalStorage
                localStorage.setItem('user', JSON.stringify(userData));
                localStorage.setItem('token', tokenData);
                
                return { success: true };
            } catch (error) {
                console.error("Login error:", error.response?.data?.message || error.message);
                return { 
                    success: false, 
                    message: error.response?.data?.message || "Identifiants incorrects" 
                };
            }
        },

        async logout() {
            try {
                // Call the API logout to blacklist the token on the server
                await api.logout();
            } catch (error) {
                console.error("Server-side logout failed, clearing local state anyway", error);
            } finally {
                // ALWAYS clear local state even if the server request fails
                this.user = null;
                this.token = null;
                this.isAuthenticated = false;

                localStorage.removeItem('user');
                localStorage.removeItem('token');
            }
        }
    }
});