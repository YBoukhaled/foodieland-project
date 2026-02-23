import axios from 'axios';

const BASE_URL = 'http://127.0.0.1:8000';

const apiClient = axios.create({
  baseURL: `${BASE_URL}/api`,
  headers: {
    'Accept': 'application/json',
    'Content-Type': 'application/json',
  },
});

// JWT — attach token to every request
apiClient.interceptors.request.use((config) => {
  const token = localStorage.getItem('token');
  if (token) {
    config.headers.Authorization = `Bearer ${token}`;
  }
  return config;
}, (error) => Promise.reject(error));

// Auto-handle expired token
apiClient.interceptors.response.use(
  (response) => response,
  (error) => {
    if (error.response?.status === 401) {
      localStorage.removeItem('token');
      localStorage.removeItem('user');
      if (window.location.pathname !== '/login') {
        window.location.href = '/login';
      }
    }
    return Promise.reject(error);
  }
);

export default {
  // ── Auth ──
  login(credentials) { return apiClient.post('/admin/login', credentials); },
  logout()           { return apiClient.post('/logout'); },
  getUser()          { return apiClient.get('/user'); },

  // ── Categories ──
  async getCategories() {
    const res = await apiClient.get('/categories');
    const data = res.data?.data ?? res.data;
    const fixed = data.map(cat => ({
      ...cat,
      image_url: cat.image_url
        ? `${BASE_URL}/${cat.image_url}`
        : null,
    }));
    return { ...res, data: fixed };
  },

  // ── Recipes ──
  // Pass categoryId to filter: GET /api/recipes?category_id=2
  getRecipes(categoryId = null) {
    const params = categoryId ? { category_id: categoryId } : {};
    return apiClient.get('/recipes', { params });
  },
  getRecipe(id)          { return apiClient.get(`/recipes/${id}`); },
  createRecipe(recipe)   { return apiClient.post('/recipes', recipe); },
  updateRecipe(id, data) { return apiClient.put(`/recipes/${id}`, data); },
  deleteRecipe(id)       { return apiClient.delete(`/recipes/${id}`); },
};