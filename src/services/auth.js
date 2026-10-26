// Authentication & RBAC Service
import { ref } from 'vue';

const currentUser = ref(null);
const currentToken = ref(localStorage.getItem('ieee_aau_token') || null);

// Initialize user from local storage
try {
  const storedUser = localStorage.getItem('ieee_aau_user');
  if (storedUser) currentUser.value = JSON.parse(storedUser);
} catch (e) {}

export const authService = {
  user: currentUser,
  token: currentToken,

  isLoggedIn() {
    return Boolean(currentUser.value && currentToken.value);
  },

  getUser() {
    return currentUser.value;
  },

  getRole() {
    return currentUser.value ? currentUser.value.role : 'visitor';
  },

  async login(email, password) {
    try {
      const res = await fetch('./api.php?action=login', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ email, password })
      });
      const data = await res.json();
      if (res.ok && data.status === 'success') {
        this.setSession(data.token, data.user);
        return { success: true, user: data.user, redirect: data.redirect };
      }
      return { success: false, message: data.message || 'Login failed' };
    } catch (e) {
      // Local fallback for offline mode
      return this.localLoginFallback(email, password);
    }
  },

  localLoginFallback(email, password) {
    const defaultAccounts = [
      { id: 1, name: 'Branch Chair (Super Admin)', email: 'admin@ieee-aau.org', role: 'office_member', phone: '+249912000001' },
      { id: 2, name: 'HR Coordinator', email: 'hr@ieee-aau.org', role: 'hr', phone: '+249912000002' },
      { id: 3, name: 'Programs Coordinator', email: 'programs@ieee-aau.org', role: 'programs_coordinator', phone: '+249912000003' },
      { id: 4, name: 'Magazine Editor', email: 'magazine@ieee-aau.org', role: 'magazine_coordinator', phone: '+249912000004' },
      { id: 5, name: 'Media Lead', email: 'media@ieee-aau.org', role: 'media_coordinator', phone: '+249912000005' },
      { id: 6, name: 'Ahmed Mohamed (Student)', email: 'student@aau.edu', role: 'registered_user', phone: '+249912345678' }
    ];

    const found = defaultAccounts.find(a => a.email.toLowerCase() === email.toLowerCase());
    if (found && password === 'password123') {
      const token = btoa(found.email + ':' + found.id);
      this.setSession(token, found);
      return { success: true, user: found, redirect: (found.role === 'registered_user') ? '/' : '/admin' };
    }
    return { success: false, message: 'Invalid email or password' };
  },

  async register(name, email, password, phone = '') {
    try {
      const res = await fetch('./api.php?action=register', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ name, email, password, phone })
      });
      const data = await res.json();
      if (res.ok && data.status === 'success') {
        this.setSession(data.token, data.user);
        return { success: true, user: data.user };
      }
      return { success: false, message: data.message || 'Registration failed' };
    } catch (e) {
      // Local fallback
      const newUser = { id: Date.now(), name, email, phone, role: 'registered_user' };
      const token = btoa(email + ':' + newUser.id);
      this.setSession(token, newUser);
      return { success: true, user: newUser };
    }
  },

  setSession(token, user) {
    currentUser.value = user;
    currentToken.value = token;
    localStorage.setItem('ieee_aau_token', token);
    localStorage.setItem('ieee_aau_user', JSON.stringify(user));
  },

  logout() {
    currentUser.value = null;
    currentToken.value = null;
    localStorage.removeItem('ieee_aau_token');
    localStorage.removeItem('ieee_aau_user');
  },

  isChairman() {
    if (!currentUser.value) return false;
    const role = currentUser.value.role;
    const email = (currentUser.value.email || '').toLowerCase();
    return role === 'chairman' || (role === 'office_member' && email === 'admin@ieee-aau.org');
  },

  // 🔴 RBAC Matrix Enforcement (Section 5.2 in CR v0.3.1 & Spec v0.3.4 Section 2.1)
  can(module, action = 'view') {
    if (!currentUser.value) return false;
    const role = currentUser.value.role;

    // Spec v0.3.4 Exception: manage_office_terms is granted to Chairman ONLY!
    if (module === 'office_terms') {
      if (action === 'manage') {
        return this.isChairman();
      }
      return role === 'office_member' || role === 'chairman';
    }

    // Super Admin (Office Member) has 100% access to all other modules
    if (role === 'office_member') return true;

    const matrix = {
      dashboard: ['office_member', 'hr', 'programs_coordinator', 'magazine_coordinator', 'media_coordinator'],
      applications: ['office_member', 'hr'],
      members: action === 'view' ? ['office_member', 'hr', 'programs_coordinator'] : ['office_member', 'hr'],
      volunteers: action === 'view' ? ['office_member', 'hr', 'programs_coordinator'] : ['office_member', 'hr'],
      events: action === 'view' ? ['office_member', 'programs_coordinator', 'hr'] : ['office_member', 'programs_coordinator'],
      magazine: ['office_member', 'magazine_coordinator'],
      news: ['office_member', 'media_coordinator'],
      articles: ['office_member', 'media_coordinator'],
      reports: action === 'events_only' ? ['office_member', 'hr', 'programs_coordinator'] : ['office_member', 'hr'],
      terms: action === 'view' ? ['office_member', 'hr'] : ['office_member'],
      settings: ['office_member']
    };

    if (matrix[module]) {
      return matrix[module].includes(role);
    }
    return false;
  }
};
